<?php namespace App\Http\Controllers\Requirementctrl;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Requirement;
use App\User;
use Auth;
use Aws\CloudFront\Exception\Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class RequirementController extends Controller {
    
    public function __construct()
    {
        //$this->middleware('oauth');
    }

    /*Start View Pages Code*/
    public function indexpage()
    {
        return view('requirements/index');
    }
    public function listing()
    {
        return view('requirements/list');
    }
    public function view()
    {
        return view('requirements/view');
    }
    public function add()
    {
        return view('requirements/add');
    }
    /*Start View Pages Code*/

    /**
     * Display a listing of the resource.
     *
     * @param Request $requests
     * @return Response
     */
    public function index(Request $requests)
    {
        $userId = $requests['user_id'];
        $allRequirement = Requirement::where('agent_id','=',$userId)->get();
        return $allRequirement;
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
        $userId = $request['user_id'];

        try{
            $requirementData = $request->input();

            $user = User::find($userId);

            $req = new Requirement();
            $req->agent_id = $userId;
            $req->client_id = 1;
            $req->title = $requirementData['title'];
            $req->description = $requirementData['description'];
            $req->client_email = $requirementData['client_email'];
            $req->location = $requirementData['location'];
            $req->area = $requirementData['area'];
            $req->range = $requirementData['range'];
            $req->price = $requirementData['price'];
            $req->price_range = $requirementData['price_range'];
            $req->type = $requirementData['type'];

            if (!$req->save(['user' => $user, 'requirement' => $req])) {
                $errors = $req->getErrors()->all();
                $data = $errors;
                $message = 'Requirement not added.';
                return Response::json(array('message' => $message ,'data'=> [
                    'req' => $data,
                    'type' => 'error'
                ]), Config::get('statuscode.validationFailCode'));
            }

            $data = $req;
            $message = 'Requirement added successfully';
            return Response::json(array('message' => $message ,'data'=> [
                'req' => $data,
                'type' => 'save'
            ]), Config::get('statuscode.successCode'));

        }
        catch(Exception $e)
        {
            $data = 'Exception';
            $message = 'Requirement not Added.';
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
        $requirement = Requirement::find($id);
        /*Check if the user is owner of the Property list or not*/
        if ($requirement->agent_id != $user_id) {

            return response([
                'message' => 'This Requirement does not belong to you.'
            ], Config::get('statuscode.validationFailCode'));
        }

        return $requirement;
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
        $requirement = Requirement::find($id);
        /*Check if the user is owner of the Property list or not*/
        if ($requirement->agent_id != $user_id) {

            return response([
                'message' => 'This Requirement does not belong to you.'
            ], Config::get('statuscode.validationFailCode'));
        }

        return $requirement;
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

            $requirementData = $request->input();
            $req =  Requirement::find($id);
            $req->agent_id = $userId;
            $req->client_id = 1;
            $req->title = $requirementData['title'];
            $req->description = $requirementData['description'];
            $req->client_email = $requirementData['client_email'];
            $req->location = $requirementData['location'];
            $req->area = $requirementData['area'];
            $req->range = $requirementData['range'];
            $req->price = $requirementData['price'];
            $req->price_range = $requirementData['price_range'];
            $req->type = $requirementData['type'];

            if (!$req->save(['user' => $user, 'requirement' => $req])) {
                $errors = $req->getErrors()->all();
                $data = $errors;
                $message = 'Requirement not updated.';
                return Response::json(array('message' => $message ,'data'=> [
                    'req' => $data,
                    'type' => 'error'
                ]), Config::get('statuscode.validationFailCode'));
            }

            $data = $req;
            $message = 'Requirement updated successfully';
            return Response::json(array('message' => $message ,'data'=> [
                'req' => $data,
                'type' => 'Update'
            ]), Config::get('statuscode.successCode'));
        }
        catch(Exception $e)
        {
            $data = $id;
            $message = 'Requirement not updated.';
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
        $req = Requirement::find($id);

        /*Check if the user is owner of the distribution list or not*/
        if ($req->agent_id != $user_id) {
            return response([
                'message' => 'This Requirement does not belong to you.'
            ], Config::get('statuscode.validationFailCode'));
        }

        try
        {
            Requirement::destroy($id);
            $message = 'Requirement deleted';
            return Response::json(array('message' => $message ,'data'=> [
                'req' => $id,
                'type' => 'delete'
            ]), Config::get('statuscode.successCode'));

        }
        catch(Exception $e)
        {
            $data = $id;
            $message = 'Requirement not Deleted.';
            return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.internalServerErrorCode'));
        }

    }
}
