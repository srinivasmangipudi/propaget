<?php namespace App\Http\Controllers\Property;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Support\Facades\Config;
use App\Properties;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Watson\Validating\ValidationException;

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
        $properties = Properties::where('agent_id', '=', $userId)->get();
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
        $propertyData = $request->input();

        return $this->save_property($userId, $propertyData);

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
        if ($property->agent_id != $user_id) {

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
        if ($property->agent_id != $user_id) {

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

        $userId = $request['user_id'];
        $propertyData = $request->input();

        return $this->save_property($userId, $propertyData, $id);
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
        if ($property->agent_id != $user_id) {
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


    /** Helper Function to Save property **/
    private  function save_property($userId, $propertyData, $propertyID=NULL) {
        DB::beginTransaction();
        try {

            if(!$propertyID) {
                /* Create client user if not exists */
                $clientUser = User::where('email', $propertyData['client_email'])->first();

                if (!$clientUser) {
                    $clientUser = new User;
                    $clientUser->email = $propertyData['client_email'];
                    $clientUser->password = 'password';
                    $clientUser->role = 'client';
                    $clientUser->save();
                }

                $clientId = $clientUser->id;
            }

            if($propertyID) {
                $pro = Properties::find($propertyID);
            }else {
                $pro = new Properties;
                $pro->client_id = $clientId;
            }

            $pro->agent_id = $userId;
            $pro->title = $propertyData['title'];
            if(isset($propertyData['description']) && $propertyData['description'])
            {
                $pro->description = $propertyData['description'];
            }
            $pro->client_email = $propertyData['client_email'];
            $pro->address = $propertyData['address'];
            $pro->location = $propertyData['location'];
            $pro->area = $propertyData['area'];
            $pro->price = $propertyData['price'];
            $pro->type = $propertyData['type'];
            $pro->save();

            if($propertyID) {
                $data = $pro;
                $message = 'Property updated successfully';
                $type =  'Update';
            }else {
                $data = $pro;
                $message = 'Property added successfully';
                $type =  'save';
            }

        }
        catch (ValidationException $e) {
            DB::rollback();
            Log::info('Catch exception');

            $errors = $e->getErrors()->all();

            $data = $errors;

            if($propertyID) {
                $message = 'Property not updated.';
            }else {
                $message = 'Property not Added.';
            }

            return Response::json(array('message' => $message ,'data'=> [
                'prop' => $data,
                'type' => 'error'
            ]), Config::get('statuscode.validationFailCode'));

        }
        catch (Exception $e) {
            DB::rollback();

            if($propertyID) {
                $data = $propertyID;
                $message = 'Property not updated.';
            }else {
                $data = '';
                $message = 'Property not Added.';
            }


            return Response::json(array('message' => $message ,'data'=>$data),
                Config::get('statuscode.internalServerErrorCode'));
        }

        DB::commit();

        return Response::json(array('message' => $message ,'data'=> [
            'prop' => $data,
            'type' => $type,
        ]), Config::get('statuscode.successCode'));



    }

}
