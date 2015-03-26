<?php namespace App\Http\Controllers\Property;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Properties;
use Aws\CloudFront\Exception\Exception;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;


class PropertyController extends Controller {

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
            $postData = $request->input();
            //$userId = Auth::user()->id;
            $prop = new Properties;
            $prop->agentId = $userId;
            $prop->clientId = 1;
            $prop->location = $postData['location'];
            $prop->area = $postData['area'];
            $prop->price = $postData['price'];
            $prop->title = $postData['title'];

            if (!$prop->save()) {
                $errors = $prop->getErrors()->all();
                $data = $errors;
                $message = 'Property not added.';
                return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.validationFailCode'));
            }

            $message = 'Property added successfully';
            return Response::json(array('message' => $message ,'data'=> [
                'type' => 'save',
                'prop' => $prop
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
        try{
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
                $data = $pro;
                $message = 'Property updated successfully';
                return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.successCode'));
            }
            else
            {
                $data = $errors;
                $message = 'Property not updated.';
                return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.validationFailCode'));
            }
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

        /*Check if the user is owner of the distribution list or not*/
        if ($property->created_by != $user_id) {
            return response([
                'message' => 'This distribution list does not belong to you.'
            ], 422);
        }

        try
        {
            Properties::destroy($id);
            $data = $id;
            $message = 'Property Deleted.';
            return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.successCode'));
        }
        catch(Exception $e)
        {
            $data = $id;
            $message = 'Property not Deleted.';
            return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.internalServerErrorCode'));
        }
    }

}
