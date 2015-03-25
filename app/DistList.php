<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DistList extends Model {

    protected $table = 'dist_lists';

    /**
     * @param array $listData
     * @param array $members
     * @return DistList
     */
    public function saveEntireDistributionList(array $listData, array $members)
    {
        // sanitize the member numbers
        $members = $this->sanitizeMemberNumbers($members);

        // validate the list data and member data
        // TODO: Need to write the validation rules

        // save the distribution list
        $distList = $this->saveDistributionList($listData);

        // check users exist and new users
        $finalArray = $this->checkExistingAndNewUser($members, $listData['createdBy']);

        // save the member data
        $distListId = $distList->id;

        // creating entries of the distribution list and member relation
        foreach ($finalArray as $key => $row)
        {
            $distListMem = new DistListMembers;
            $distListMem->distListId = $distListId;
            $distListMem->userId = $key;
            $distListMem->save();
        }

        return $distList;
    }

    /**
     * This function will sanitize the member phone numbers.
     *
     * @param $members
     * @return mixed
     */
    private function sanitizeMemberNumbers($members)
    {
        foreach ($members as $key => $member)
        {
            $members[$key] = str_replace(' ', '', $member);
        }

        $members = array_unique($members);

        return $members;
    }

    /**
     * Saving the distribution list name and other details.
     *
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
     * Checking for existing users and also adding new users who are not in our system.
     *
     * @param $members
     * @return array
     */
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

        // if there are users in dist list not present in system
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
     * Create user for the numbers which are sent in the distribution list
     * but they are not present in our system.
     *
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
            $userNumber = str_replace(' ', '', $userNumber);
            $user = new User;
            $user->name = "propagate_" . $userNumber;
            $user->phoneNumber = $userNumber;
            $user->email = $userNumber . '@propagate.com';
            $user->password = Hash::make('password');
            $user->userType = 'normal';
            $user->userId = '0';
            $user->save();
            $newUserArray[$user->id] = $userNumber;
        }

        return $newUserArray;
    }

}
