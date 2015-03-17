<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Faker;
use Illuminate\Support\Facades\Hash;
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

    /**
     * @param $listData
     * @return DistList
     */
    private function saveDistributionList($listData)
    {
        // save the distribution list
        $distList = new DistList;
        $distList->name = $listData['name'];
        $distList->createdBy = $listData['createdBy'];
        $distList->save();

        return $distList;
    }

    /**
     * @param $members
     * @return array
     */
    private function checkExistingAndNewUser($members)
    {
//        Log::info("Members");
//        Log::info(print_r($members, true));

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


//        Log::info("Final");
//        Log::info(print_r($finalArray, true));

//        Log::info("Not present");
        $notPresent = array_diff($members, $finalArray);
//        Log::info(print_r($notPresent, true));

        if (!empty($notPresent))
        {
            $newUserArray = $this->createNewUsers($notPresent);

            foreach ($newUserArray as $key => $value)
            {
                $finalArray[$key] = $value;
            }
        }

        return $finalArray;
    }

    /**
     * @param $notPresent
     * @return array
     */
    private function createNewUsers($notPresent)
    {
        // final array of new users that will be created
        $newUserArray = array();

        // create faker instance
        $faker = Faker\Factory::create();

        // create user for each new number
        foreach ($notPresent as $userNumber)
        {
            $user = new User;
            $user->name = $faker->userName;
            $user->phoneNumber = $userNumber;
            $user->email = $faker->email;
            $user->password = Hash::make('password');
            $user->userType = 'normal';
            $user->userId = '0';
            $user->save();
            $newUserArray[$user->id] = $userNumber;
            Log::info('User created: ' . $user->id);
        }

        return $newUserArray;
    }

}
