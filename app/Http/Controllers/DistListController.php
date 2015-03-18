<?php namespace App\Http\Controllers;

use App\Device;
use App\DistList;
use App\DistListMembers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DistListController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        Log::info('I was here');
		$data = DB::table('migrations')->get();
        return $data;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * @return Response
	 */
	public function store(Request $request)
	{
        Log::info('I was here: ' . time());
        // get all post data
		$postData = $request->input();
        $contacts = json_decode($postData['members']);

        $distList = new DistList;
        $distList->name = $postData['name'];
        $distList->createdBy = $postData['createdBy'];
//        $distList->save();

        $distListId = $distList->id;
        $arrUserIds = array();

        // sanitise
        foreach ($contacts as $key => $member)
        {
            $contacts[$key] = str_replace(' ', '', $member);
        }

        $existingContacts = DB::table('users')->whereIn('phoneNumber', $contacts)->get();

        if (count($existingContacts) == 0)
        {
            // all new contacts
            foreach ($contacts as $c)
            {
                $user = new User;
                $user->name = "propagate_" . $c;
                $user->phoneNumber = $c;
                $user->email = $c . '@propagate.com';
                $user->password = Hash::make('password');
                $user->userType = 'normal';
                $user->userId = '0';
//                $user->save();
                $arrUserIds[] = $user->id;
            }
        }
        else
        {
            // some users needs to be created
            Log::info('some users needs to be created');

            $arrDBUsers = array();
            foreach ($existingContacts as $dbUsers)
            {
                $arrDBUsers[] = $dbUsers->phoneNumber;
                $arrUserIds[] = $dbUsers->id;
            }

            $uniqueUsers = array_diff($contacts, $arrDBUsers);
            Log::info('unique users');
            Log::info(print_r($uniqueUsers, true));

            foreach ($uniqueUsers as $c)
            {
                $user = new User;
                $user->name = "propagate_" . $c;
                $user->phoneNumber = $c;
                $user->email = $c . '@propagate.com';
                $user->password = Hash::make('password');
                $user->userType = 'normal';
                $user->userId = '0';
//                $user->save();
                $arrUserIds[] = $user->id;
            }
        }

        foreach ($arrUserIds as $ref)
        {
            $distListMem = new DistListMembers;
            $distListMem->distListId = $distListId;
            $distListMem->userId = $ref;
//            $distListMem->save();
        }

        return array();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

    public function getAllRequirement($id = null    )
    {
        Log::info('I was here');
        return array(1,2,3);
    }

}
