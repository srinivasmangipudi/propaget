<?php namespace App\Http\Controllers;

use App\Commands\SaveDistributionList;
use App\DistList;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response;

class DistListController extends Controller {
    
    public function __construct()
    {
//        $this->middleware('oauth');
    }

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
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // get all post data
        $postData = $request->input();

        $distList = new DistList;
        $distList->name = $postData['name'];
        $distList->createdBy = $postData['createdBy'];

        if (!$distList->save()) {

        }

        $members = json_decode($postData['members']);

        Queue::push(new SaveDistributionList($members, $distList->id));
        Log::info(print_r($distList->id, true));
//        $distList->runQueueToSaveDistList($members, $distList->id);

        return response(array(
            'data' => $distList,
            'message' => 'Distribution list saved successfully.'
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
