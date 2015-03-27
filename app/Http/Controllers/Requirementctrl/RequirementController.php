<?php namespace App\Http\Controllers\Requirementctrl;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Requirement;
use App\User;
use Auth;
use Aws\CloudFront\Exception\Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class RequirementController extends Controller {
    
    public function __construct()
    {
        $this->middleware('oauth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $requests
     * @return Response
     */
    public function index(Request $requests)
    {
        $userId = $requests['user_id'];
        $allRequirement = Requirement::where('agentId','=',$userId)->get();
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
            $req->agentId = $userId;
            $req->clientId = 1;
            $req->title = $requirementData['title'];
            $req->description = $requirementData['description'];
            $req->clientEmail = $requirementData['clientEmail'];
            $req->location = $requirementData['location'];
            $req->area = $requirementData['area'];
            $req->range = $requirementData['range'];
            $req->price = $requirementData['price'];
            $req->priceRange = $requirementData['priceRange'];
            $req->type = $requirementData['type'];
            $req->save(['user' => $user, 'requirement' => $req]);
            $errors = $req->getErrors()->all();

            if (empty($errors))
            {
                $data = $req;
                $message = 'Requirement added successfully';
                return Response::json(array('message' => $message ,'data'=> [
                    'req' => $data,
                    'type' => 'save'
                ]), Config::get('statuscode.successCode'));

            }
            else
            {
                $data = $errors;
                $message = 'Requirement not added.';
                return Response::json(array('message' => $message ,'data'=> [
                    'req' => $data,
                    'type' => 'error'
                ]), Config::get('statuscode.validationFailCode'));
            }
        }
        catch(Exception $e)
        {
            $data = '';
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
    public function show($id)
    {
        $response = Requirement::where('agentId','=','2')->where('id','=',$id)->get();
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
        $requirement = Requirement::find($id);
        return $requirement;
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
            $user = User::find(1);
            $requirementData = Request::all();
            $req =  Requirement::find($id);
            $req->agentId = 2;
            $req->clientId = 1;
            $req->title = $requirementData['title'];
            $req->description = $requirementData['description'];
            $req->clientEmail = $requirementData['clientEmail'];
            $req->location = $requirementData['location'];
            $req->area = $requirementData['area'];
            $req->range = $requirementData['range'];
            $req->price = $requirementData['price'];
            $req->priceRange = $requirementData['priceRange'];
            $req->type = $requirementData['type'];
            $req->save(['user' => $user, 'requirement' => $req]);
            $errors = $req->getErrors()->all();

            if (empty($errors))
            {
                $data = $req;
                $message = 'Requirement updated successfully';
                return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.successCode'));

            }
            else
            {
                $data = $errors;
                $message = 'Requirement not updated.';
                return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.validationFailCode'));
            }
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
        if ($req->agentId != $user_id) {
            return response([
                'message' => 'This distribution list does not belong to you.'
            ], 422);
        }

        try
        {
            Requirement::destroy($id);
            $message = 'Requirement Deleted.';
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
