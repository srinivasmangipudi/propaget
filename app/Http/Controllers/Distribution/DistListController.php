<?php namespace App\Http\Controllers\Distribution;

use App\DistList;
use App\DistListMembers;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class DistListController extends Controller {

    /*Start View Pages Code*/
    public function indexpage()
    {
        return view('distribution/index');
    }

    public function listing()
    {
        return view('distribution/list');
    }

    public function view()
    {
        return view('distribution/view');
    }
    /*Start View Pages Code*/

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $requests)
    {
        $userId = $requests['user_id'];
        $disMember = new DistList();
        $response = $disMember->loadFullDistribution($userId);
        return $response;
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
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // get all post data
        $postData = $request->input();
        Auth::loginUsingId($request['user_id']); // log in user

        $distList = new DistList;
        $distList->name = $postData['name'];
        $distList->created_by = $request['user_id'];

        if (!$distList->save()) {
            return response([
                'data' => $postData,
                'message' => 'Could not save data'
            ], 500);
        }

        $members = json_decode($postData['members']);

        watchdog_message('New distribution list created.', 'normal', ['distList' => $distList, 'members' => $members]);

        //\Queue::push(new SaveDistributionList($members, $distList->id));

        return response(array(
            'data' => ['type' => 'save', 'list' => $distList],
            'message' => "Your list {$distList->name} has been saved successfully."
        ), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $disMember = new DistListMembers();
        $response = $disMember->loadDisMemberList($id);
        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $userId = 2;
        $response = DistList::where('createdBy','=',$userId)->where('id','=',$id)->get();
        return $response;
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
    public function destroy($id, Request $request)
    {
        $user_id = $request['user_id'];

        $distList = DistList::find($id);

        /*Check if the user is owner of the distribution list or not*/
        if ($distList->created_by != $user_id) {
            return response([
                'message' => 'This distribution list does not belong to you.'
            ], 422);
        }

        if ($distList->delete()) {
            return response([
                'data' => ['type' => 'delete', 'id' => $id],
                'message' => "Distribution list {$distList->name} has been deleted."
            ], 201);
        } else {
            return response([
                'data' => $id,
                'message' => "Not able to delete the list."
            ], 500);
        }
    }

    public function getAllRequirement($id = null)
    {

        Log::info('I was here');
        return array(1,2,3);
    }

}
