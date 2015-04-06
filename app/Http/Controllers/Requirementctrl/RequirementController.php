<?php namespace App\Http\Controllers\Requirementctrl;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Requirement;
use App\User;
use Auth;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Watson\Validating\ValidationException;


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
        $requirementData = $request->input();
        return $this->save_requirement($userId, $requirementData);
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
        /*Check if the user is owner of the Requirement list or not*/
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
        /*Check if the user is owner of the Requirement list or not*/
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
        $userId = $request['user_id'];
        $requirementData = $request->input();

        return $this->save_requirement($userId, $requirementData, $id);
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

    /* Helper function to save Requirement*/
    private function save_requirement($userId, $requirementData,$requirementId=NULL)
    {
        Log::error('i m funh');
        DB::beginTransaction();
        try{
            if(!$requirementId) {
                /* Create client user if not exists */
                $clientUser = User::where('email', $requirementData['client_email'])->first();

                if (!$clientUser) {
                    $clientUser = new User;
                    $clientUser->email = $requirementData['client_email'];
                    $clientUser->password = Hash::make('password');
                    $clientUser->role = 'client';
                    $clientUser->save();
                }

                $clientId = $clientUser->id;
            }

            if($requirementId) {
                $req =  Requirement::find($requirementId);
            }else {
                $req = new Requirement;
                $req->client_id = $clientId;
            }

            $req->agent_id = $userId;
            $user = User::find($userId);
            $req->title = $requirementData['title'];
            if(isset($requirementData['description']))
            {
                $req->description = $requirementData['description'];
            }
            $req->client_email = $requirementData['client_email'];
            $req->location = $requirementData['location'];
            $req->area = $requirementData['area'];
            $req->range = $requirementData['range'];
            $req->price = $requirementData['price'];
            $req->price_range = $requirementData['price_range'];
            $req->type = $requirementData['type'];
            $req->save(['user' => $user, 'requirement' => $req]);
            //$req->save();

            if($requirementId) {
                $data = $req;
                $message = 'Requirement updated successfully';
                $type =  'Update';
            }else {
                $data = $req;
                $message = 'Requirement added successfully';
                $type =  'save';
            }

        }
        catch (ValidationException $e) {
            DB::rollback();
            Log::info('Catch exception Validate');
            $errors = $e->getErrors()->all();
            $data = $errors;
            if($requirementId) {
                $message = 'Requirement not updated.';
            }else {
                $message = 'Requirement not Added.';
            }
            return Response::json(array('message' => $message ,'data'=> [
                'req' => $data,
                'type' => 'error'
            ]), Config::get('statuscode.validationFailCode'));

        }
        catch (Exception $e) {
            DB::rollback();
            Log::info('Catch exception General');
            if($requirementId) {
                $data = $requirementId;
                $message = 'Requirement not updated.';
            }else {
                $data = '';
                $message = 'Requirement not Added.';
            }
            return Response::json(array('message' => $message ,'data'=>$data),
                Config::get('statuscode.internalServerErrorCode'));
        }
        DB::commit();
        Log::info('final save : '.print_r($data,true));
        return Response::json(array('message' => $message ,'data'=> [
            'req' => $data,
            'type' => $type,
        ]), Config::get('statuscode.successCode'));
    }
}
