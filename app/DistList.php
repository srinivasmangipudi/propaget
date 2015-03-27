<?php namespace App;

use App\Events\DistListMembersAdded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Faker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DistList extends Model {

    protected $table = 'dist_lists';

    public function runQueueToSaveDistList(array $members, $distListId)
    {
        $this->saveEntireDistributionList($members, $distListId);
    }

    public function loadFullDistribution()
    {
        //SELECT t1.id,t1.name, IF( t2.totaluser IS NULL , 0, t2.totaluser ) AS totalusers FROM dist_lists t1 LEFT JOIN ( SELECT count( t3.userid ) AS totaluser, t3.distListId FROM dist_list_members t3 GROUP BY (distListId)) AS t2 ON t1.id = t2.distListId

        $results = DB::select( DB::raw("SELECT t1.id,t1.name, IF( t2.totaluser IS NULL , 0, t2.totaluser ) AS totalusers FROM dist_lists t1 LEFT JOIN ( SELECT count( t3.userid ) AS totaluser, t3.distListId FROM dist_list_members t3 GROUP BY (distListId)) AS t2 ON t1.id = t2.distListId") );

        return $results;
    }
    /**
     * @param array $members
     * @param $distListId
     * @return DistList
     * @internal param array $listData
     */
    private function saveEntireDistributionList(array $members, $distListId)
    {
        // sanitize the member numbers
        $members = $this->sanitizeMemberNumbers($members);

        // validate the list data and member data
        // TODO: Need to write the validation rules

        // save the distribution list
        //$distList = $this->saveDistributionList($listData);

        // check users exist and new users
        $finalArray = $this->checkExistingAndNewUser($members);

        // creating entries of the distribution list and member relation
        foreach ($finalArray as $key => $row)
        {
            $distListMem = new DistListMembers;
            $distListMem->dist_list_id = $distListId;
            $distListMem->user_id = $key;
            $distListMem->save();
        }

        Event::fire(new DistListMembersAdded());

        return $distListId;
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
        $distList->created_by = $listData['createdBy'];
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
