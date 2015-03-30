<?php namespace App\Http\Controllers\Property;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Config;
use App\Properties;
use Aws\CloudFront\Exception\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class PropertyController extends Controller {

    public function __construct()
    {
        //$this->middleware('oauth');
    }

    /*Start View Pages Code*/
    public function indexpage()
    {
        return view('property/index');
    }

    public function listing()
    {
        return view('property/list');
    }

    public function add()
    {
        return view('property/add');
    }
    public function view()
    {
        return view('property/view');
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
    public function store(Request $request)
    {
        $userId = $request['user_id'];

        try {
            $propertyData = $request->input();
            
            $pro = new Properties;
            $pro->agentId = $userId;
            $pro->clientId = 1;
            $pro->title = $propertyData['title'];
            $pro->description = $propertyData['description'];
            $pro->clientEmail = $propertyData['clientEmail'];
            $pro->address = $propertyData['address'];
            $pro->location = $propertyData['location'];
            $pro->area = $propertyData['area'];
            $pro->price = $propertyData['price'];
            $pro->type = $propertyData['type'];

            if (!$pro->save()) {
                $errors = $pro->getErrors()->all();
                $data = $errors;
                $message = 'Property not added.';
                return Response::json(array('message' => $message ,'data'=> [
                    'prop' => $data,
                    'type' => 'error'
                ]), Config::get('statuscode.validationFailCode'));
            }

            $data = $pro;
            $message = 'Property added successfully';
            return Response::json(array('message' => $message ,'data'=> [
                'prop' => $data,
                'type' => 'save'
            ]), Config::get('statuscode.successCode'));

        } catch (Exception $e) {

            $data = '';
            $message = 'Property not Added.';
            return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.internalServerErrorCode'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $user_id = $request['user_id'];
        $property = Properties::find($id);
        /*Check if the user is owner of the Property list or not*/
        if ($property->agentId != $user_id) {

            return response([
                'message' => 'This Property does not belong to you.'
            ], Config::get('statuscode.validationFailCode'));
        }

        return $property;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $user_id = $request['user_id'];
        $property = Properties::find($id);

        /*Check if the user is owner of the Property list or not*/
        if ($property->agentId != $user_id) {

            return response([
                'message' => 'This Property does not belong to you.'
            ], Config::get('statuscode.validationFailCode'));
        }

        return $property;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        try{
            $userId = $request['user_id'];
            $user = User::find($userId);
            $propertyData = $request->input();
            $pro = Properties::find($id);

            $pro->agentId = $userId;
            $pro->clientId = 1;
            $pro->title = $propertyData['title'];
            $pro->description = $propertyData['description'];
            $pro->clientEmail = $propertyData['clientEmail'];
            $pro->address = $propertyData['address'];
            $pro->location = $propertyData['location'];
            $pro->area = $propertyData['area'];
            $pro->price = $propertyData['price'];
            $pro->type = $propertyData['type'];

            if (!$pro->save()) {
                $errors = $pro->getErrors()->all();
                //Log::info('this update'. print_r($errors, true));
                $data = $errors;
                $message = 'Property not updated.';
                return Response::json(array('message' => $message ,'data'=> [
                    'prop' => $data,
                    'type' => 'error'
                ]), Config::get('statuscode.validationFailCode'));
            }
            $data = $pro;
            $message = 'Property updated successfully';
            return Response::json(array('message' => $message ,'data'=> [
                'prop' => $data,
                'type' => 'Update'
            ]), Config::get('statuscode.successCode'));

        }
        catch(Exception $e)
        {
            $data = $id;
            $message = 'Property not updated.';
            return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.internalServerErrorCode'));
        }


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
        $property = Properties::find($id);

        /*Check if the user is owner of the Property list or not*/
        if ($property->agentId != $user_id) {
            return response([
                'message' => 'This Property does not belong to you.'
            ], Config::get('statuscode.validationFailCode'));
        }

        try
        {
            Properties::destroy($id);
            $message = 'Property Deleted.';
            return Response::json(array('message' => $message ,'data'=> [
                'prop' => $id,
                'type' => 'delete'
            ]), Config::get('statuscode.successCode'));
        }
        catch(Exception $e)
        {
            $data = $id;
            $message = 'Property not Deleted.';
            return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.internalServerErrorCode'));
        }
    }
}
