<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DistList extends Model {

	protected $table = 'dist_list';

    public function saveEntireDistributionList(array $listData, array $members)
    {
        // validate the list data and member data
        // TODO: Need to write the validation rules

        // save the distribution list
        $distList = $this->saveDistributionList($listData);

        // correct to the member data
        array_unshift($members, $listData['createdBy']);

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
                unset($members[$key]);
            }
        }

        $notPresent = array_diff($members, $finalArray);

        return $finalArray;
    }

    private function createNewUsers($notPresent)
    {

    }

}
