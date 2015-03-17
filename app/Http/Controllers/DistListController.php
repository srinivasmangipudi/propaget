<?php namespace App\Http\Controllers;

use App\Device;
use App\DistList;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        // get all post data
		$postData = $request->input();
        $members = json_decode($postData['members']);
        // handle the saving of the distribution list and all it's members
        $distList = new DistList;
        $distList->saveEntireDistributionList(array(
            'name' => $postData['name'],
            'createdBy' => $postData['createdBy']
        ), $members);
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
