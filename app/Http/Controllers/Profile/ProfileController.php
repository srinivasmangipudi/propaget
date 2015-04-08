<?php namespace App\Http\Controllers\Profile;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use App\User;
use Illuminate\Support\Facades\Route;
use Watson\Validating\ValidationException;

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
            DB::beginTransaction();
            $userData = $request->input();
            if(isset($userData['phone_number']) && $userData['phone_number'])
            {
                $clientUser = User::where('phone_number', $userData['phone_number'])->first();
            }
            if (!$clientUser) {
                $user = new User();
                $user->name = $userData['name'];
                $user->phone_number = $userData['phone_number'];
                $user->email = $userData['email'];
                $user->password = Hash::make($userData['password']);
                //$user->user_type = 'normal';
                if(isset($userData['role']) && $userData['role']) $user->role = $userData['role'];
                if(isset($userData['experience']) && $userData['experience'] ) $user->experience = $userData['experience'];
                if(isset($userData['summary']) && $userData['summary']) $user->summary = $userData['summary'];

                $user->save();
                if(isset($user->id))
                {
                    $userId = $user->id;
                    $userProfile = new UserProfile();
                    $userProfile->user_id = $userId;
                    $userProfile->user_type = 'normal';
                    $userProfile->save();

                    $data = $user;

                    $message = 'User added successfully';
                    //Log::error('in store after insert'.print_r($user,true));
                    $requestParams = array(
                        'grant_type' => 'password',
                        'client_id' => 'testclient',
                        'client_secret' => 'testpass',
                        'username' => $userData['email'],
                        'password' => $userData['password']
                    );

                    DB::commit();
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
            }
            else
            {
                $data = 'Phone Number already Exits';
                $message = 'User not added.';
                return Response::json(array('message' => $message ,'data'=> [
                    'reg' => $data,
                    'type' => 'error'
                ]), Config::get('statuscode.validationFailCode'));
            }

        }
        catch (ValidationException $e) {
            DB::rollback();
            $errors = $e->getErrors()->all();
            $data = $errors;
            $message = 'User not added.';
            return Response::json(array('message' => $message ,'data'=> [
                'reg' => $data,
                'type' => 'error'
            ]), Config::get('statuscode.validationFailCode'));
        }
        catch(Exception $e)
        {
            DB::rollback();
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
            Log::error('in put request'.print_r($request['user_id'],true));
            $user = User::find($userId);
            $userData = $request->input();

            if(isset($userData['role'])) $user->role = $userData['role'];
            if(isset($userData['experience'])) $user->experience = $userData['experience'];
            if(isset($userData['summary'])) $user->summary = $userData['summary'];

            if (!$user->save()) {
                $errors = $user->getErrors()->all();
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
