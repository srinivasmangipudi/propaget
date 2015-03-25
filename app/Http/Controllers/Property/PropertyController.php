<?php namespace App\Http\Controllers\Property;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Properties;
use Auth;
use Aws\CloudFront\Exception\Exception;
use Illuminate\Support\Facades\Response;
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
        try {
            //$userId = Auth::user()->id;
            $userId = 1;
            $prop = new Properties;
            $prop->agentId = 1;
            $prop->clientId = 1;
            $prop->location = Request::input('location');
            $prop->area = Request::input('area');
            $prop->price = Request::input('price');
            $prop->title = Request::input('title');
            if (!$prop->save()) {
                $errors = $prop->getErrors()->all();
                return Response::json(array('message' => 'Bad request.', 'prop' => $errors), 422);
            }

            return Response::json(array('message' => 'Property saved.', 'prop' => $prop), 201);

        } catch (Exception $e) {
            return Response::json(array('message' => 'Property not saved.'), 500);
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
