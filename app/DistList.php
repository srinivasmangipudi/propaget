<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Faker;
use Illuminate\Support\Facades\Log;

class DistList extends Model {

	protected $table = 'dist_lists';

    /**
     * @param array $listData
     * @param array $members
     */
    public function saveEntireDistributionList(array $listData, array $members)
    {
        // validate the list data and member data
        // TODO: Need to write the validation rules

        // save the distribution list
        $distList = $this->saveDistributionList($listData);

        // check users exist and new users
        $finalArray = $this->checkExistingAndNewUser($members, $listData['createdBy']);

        // save the member data
        $distListId = $distList->id;

        foreach ($finalArray as $key => $row)
        {
            $distListMem = new DistListMembers;
            $distListMem->distListId = $distListId;
            $distListMem->userId = $key;
            $distListMem->save();
        }

    }

    private function saveDistributionList($listData)
    {
        // save the distribution list
        $distList = new DistList;
        $distList->name = $listData['name'];
        $distList->createdBy = $listData['createdBy'];
        $distList->save();

        return $distList;
    }

    private function checkExistingAndNewUser($members)
    {
        Log::info("Members");
        Log::info(print_r($members, true));

        // get users from DB based on members provided
        $userData = DB::table('users')->whereIn('phoneNumber', $members)->get();

        $finalArray = array();

        foreach ($userData as $row)
        {
            if (in_array($row->phoneNumber, $members))
            {
                // push to the final array
                $finalArray[$row->id] = $row->phoneNumber;
                // search for the key
                $key = array_search($row->phoneNumber, $members);
                // unset the array index so that next time the search is quicker.
                /*unset($members[$key]);*/
            }
        }


        Log::info("Final");
        Log::info(print_r($finalArray, true));

        Log::info("Not present");
        $notPresent = array_diff($members, $finalArray);
        Log::info(print_r($notPresent, true));

        if (!empty($notPresent))
        {
            $this->createNewUsers($notPresent);
        }

        return $finalArray;
    }

    private function createNewUsers($notPresent)
    {
        $faker = Faker\Factory::create();
//        Log::info(print_r($notPresent, true));
        foreach ($notPresent as $userNumber)
        {

        }
    }

}
