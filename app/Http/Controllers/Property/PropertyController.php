<?php namespace App\Http\Controllers\Property;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Properties;
use Auth;
use App\User;

use Request;

class PropertyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $userId = Auth::user()->id;
        $properties = Properties::where('agentId', '=', $userId)->get();
        return $properties;
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
	public function store()
	{
        //$userId = Auth::user()->id;
        $userId = 1;
        $propertyData = Request::all();
        $propertyData['agentId'] = $userId;
        $propertyData['clientId'] = $userId;
        $properties = Properties::create($propertyData);
        return $properties;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return $id;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $property = Properties::find($id);
        return $property;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $propertyData = Request::all();
        unset($propertyData['updated_at']);
        unset($propertyData['created_at']);
        print_r($propertyData);
        Properties::where('id', $id)->update($propertyData);
        return 'Updated property';
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        Properties::destroy($id);
        return "Delete Property";
	}

}
