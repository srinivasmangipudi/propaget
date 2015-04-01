<?php namespace App\Http\Controllers\Profile;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use Aws\CloudFront\Exception\Exception;
use App\User;
use Illuminate\Support\Facades\Route;

class ProfileController extends Controller {

    public function __construct()
    {
        $this->middleware('oauth', ['except' => ['store', 'index','indexpage','adddetailpage']]);
    }

    public function indexpage()
    {
        return view('profile/index');
    }

    public function adddetailpage()
    {
        return view('profile/adddetailpage');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //Log::error('i m in index');
        echo "hello";
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
        try
        {
            $userData = $request->input();
            Log::error('in store userdata'.print_r($userData,true));
            $user = new User();
            $user->name = $userData['name'];
            $user->phoneNumber = $userData['phoneNumber'];
            $user->email = $userData['email'];
            $user->password = Hash::make($userData['password']);
            $user->userType = 'normal';
            if(isset($userData['role']) && $userData['role']) $user->role = $userData['role'];
            if(isset($userData['experience']) && $userData['experience'] ) $user->experience = $userData['experience'];
            if(isset($userData['summary']) && $userData['summary']) $user->summary = $userData['summary'];
            $user->userId = '0';

            if (!$user->save()) {
                //$errors = $user->getErrors()->all();
                $errors = 'Errors';
                $data = $errors;
                $message = 'User not added.';
                return Response::json(array('message' => $message ,'data'=> [
                    'reg' => $data,
                    'type' => 'error'
                ]), Config::get('statuscode.validationFailCode'));
            }

            $data = $user;
            $message = 'User added successfully';
            //Log::error('in store after insert'.print_r($user,true));
            $requestParams = array(
                                'grant_type' => 'password',
                                'client_id' => 'testclient',
                                'client_secret' => 'testpass',
                                'username' => $user->email,
                                'password' => $userData['password']
                                );

            // Call get token route to get access token after user logs in
            $tokenRequest = Request::create('oauth/token', 'POST', $requestParams);
            $request->replace($tokenRequest->input()); // To replace the request parameters with new one
            $OauthTokenData = json_decode(Route::dispatch($tokenRequest)->getContent());

            return Response::json(array('message' => $message ,'data'=> [
                'reg' => $data,
                'token' => $OauthTokenData,
                'type' => 'save'
            ]), Config::get('statuscode.successCode'));

        }
        catch(Exception $e)
        {
            $data = 'Exception';
            $message = 'User not Added.';
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
    public function update($id , Request $request)
    {
        try
        {
            $userId = $request['user_id'];
            $user = User::find($userId);
            $userData = $request->input();
            Log::error('in put userdata'.print_r($userData,true));

            if(isset($userData['role'])) $user->role = $userData['role'];
            if(isset($userData['experience'])) $user->experience = $userData['experience'];
            if(isset($userData['summary'])) $user->summary = $userData['summary'];

            //print_r($userData);


            if (!$user->save()) {
                //$errors = $user->getErrors()->all();
                $errors = 'Errors';
                $data = $errors;
                $message = 'User not updated.';
                return Response::json(array('message' => $message ,'data'=> [
                    'reg' => $data,
                    'type' => 'error'
                ]), Config::get('statuscode.validationFailCode'));
            }

            $data = $user;
            $message = 'User updated successfully';
            return Response::json(array('message' => $message ,'data'=> [
                'reg' => $data,
                'type' => 'save'
            ]), Config::get('statuscode.successCode'));

        }
        catch(Exception $e)
        {
            $data = 'Exception';
            $message = 'User not updated.';
            return Response::json(array('message' => $message ,'data'=>$data), Config::get('statuscode.internalServerErrorCode'));
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
        //
    }

}
