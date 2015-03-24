<?php namespace App\Http\Controllers\Property;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Properties;
use Auth;
use App\User;
use Illuminate\Support\Facades\Log;

use Request;

class PropertyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        //$userId = Auth::user()->id;
        $userId = 2;
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
        //$user = User::find(1);
        $propertyData = Request::all();

        $pro = new Properties();
        $pro->agentId = 2;
        $pro->clientId = 1;
        $pro->title = $propertyData['title'];
        $pro->description = $propertyData['description'];
        $pro->clientEmail = $propertyData['clientEmail'];
        $pro->address = $propertyData['address'];
        $pro->location = $propertyData['location'];
        $pro->area = $propertyData['area'];
        $pro->price = $propertyData['price'];
        $pro->type = $propertyData['type'];
        $pro->save();

        $errors = $pro->getErrors()->all();
        if (empty($errors))
        {
            echo "Property added successfully";
        }
        else
        {
            echo "Property not added ";
        }

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $response = Properties::where('agentId','=','2')->where('id','=',$id)->get();
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
        /*$propertyData = Request::all();
        unset($propertyData['updated_at']);
        unset($propertyData['created_at']);
        print_r($propertyData);
        Properties::where('id', $id)->update($propertyData);
        return 'Updated property';*/

        //$user = User::find(1);
        $propertyData = Request::all();
        $pro = Properties::find($id);
        $pro->agentId = 2;
        $pro->clientId = 1;
        $pro->title = $propertyData['title'];
        $pro->description = $propertyData['description'];
        $pro->clientEmail = $propertyData['clientEmail'];
        $pro->address = $propertyData['address'];
        $pro->location = $propertyData['location'];
        $pro->area = $propertyData['area'];
        $pro->price = $propertyData['price'];
        $pro->type = $propertyData['type'];

        $pro->save();

        $errors = $pro->getErrors()->all();
        //Log::info('this update'. print_r($errors, true));
        if (empty($errors))
        {
            echo "Property updated successfully";
        }
        else
        {
            echo "Property not updated ";
        }
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
        echo  "Delete Property";
	}

}
