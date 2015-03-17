<?php namespace App\Http\Controllers\SocialLogin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Auth;
use Str;
use App\User;
use App\Tokens;


class SocialLoginController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	/*public function __construct()
	{
		$this->middleware('guest');
	}*/

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function fb_login()
	{
        // Get the provider instance
        $provider = \Socialize::with('facebook');

        // Check, if the user authorised previously.
        // If so, get the User instance with all data,
        // else redirect to the provider auth screen.
        if ( !empty( \Input::get( 'code' )))
        {
            $user = $provider->user();

            if($user && $user->getId()) {
                $userId = $user->getId();
                // Find the user using the user id
                $userExists = \DB::table('users')->where('userId', '=', $userId)->where('userType', 'facebook')->get();
                if(!$userExists) {
                    // create a new user
                    $userObj = new User;
                    $userObj->name = $user->getName();
                    $userObj->email = $user->getEmail();
                    $userObj->password = \Hash::make('password');
                    $userObj->userType = 'facebook';
                    $userObj->userId = $userId;
                    $userObj->save();
                    $id =$userObj->id;

                    $token = hash('sha256',\Str::random(10),false);
                    $userObj->token = $token;
                    $userObj->save();
                    \Auth::login($userObj);

                }else {
                    $id = $userExists[0]->id;
                }


                if (\Auth::loginUsingId($id))
                {
                    $token = hash('sha256',\Str::random(10),false);
                   \DB::table('users')
                       ->where('id', $id)
                      ->update(['token' => $token]);
                }


            }
            return redirect('/');
        } else {
            return $provider->redirect();
        }

	}

    public function mobile_login() {
        $tokens = new Tokens;

        if($user = \Input::all()) {
            if (\Auth::attempt(['email' => $user['email'], 'password' => $user['password']])) {
                $userData = \DB::table('users')->where('email', $user['email'])->first();
                return $this->updateToken($userData->id);
                print_r('Authenticated successfully');
            } else {
                // create a new user
                $userObj = new User;
                $userObj->email = $user['email'];
                $userObj->password = \Hash::make($user['password']);
                $userObj->save();
                $uid =$userObj->id;
                return $this->addToken($uid);

                print_r('Not Authenticated successfully');
            }

        }
    }

    function addToken($uid) {
        $tokens = new Tokens;
        $tokens['api_token'] = hash('sha256',Str::random(10),false);
        $tokens['user_id'] = $uid;
        //$tokens['client'] = \BrowserDetect::toString();

        $Carbon = new Carbon;
        $tokens['expires_on'] = $Carbon->now()->addSeconds(30)->toDateTimeString();
        $tokens->save();
        return $tokens['api_token'];
    }

    function updateToken($uid) {

        $data = \DB::table('tokens')->where('user_id', $uid)->first();

        $token = Tokens::find($data->id);
        $token->api_token = hash('sha256',Str::random(10),false);
        $token->user_id = $uid;
        //$tokens['client'] = \BrowserDetect::toString();

        $Carbon = new Carbon;
        $token->expires_on = $Carbon->now()->addSeconds(30)->toDateTimeString();
        $token->save();
        return $token->api_token;
    }



}
