<?php namespace App\Http\Controllers;

use App\Commands\SaveDistributionList;
use App\DistList;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response;

class DistListController extends Controller {
    
    public function __construct()
    {
        $this->middleware('oauth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $requests)
    {
        $user_id = $requests['user_id'];

        /* fetch distribution list which the user owns */
        $data = DB::table('dist_lists')
            ->where('created_by', $user_id)
            ->get();

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
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // get all post data
        $postData = $request->input();

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

        Queue::push(new SaveDistributionList($members, $distList->id));

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

}
