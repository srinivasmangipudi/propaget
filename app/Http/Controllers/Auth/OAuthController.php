<?php namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use OAuth2\HttpFoundationBridge\Request as OAuthRequest;

/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 23/3/15
 * Time: 5:18 PM
 */

class OAuthController extends Controller {

    /**
     * This function will take the client id, client secret, username, password
     * and then return the tokens.
     * @param \Illuminate\Http\Request $request
     * @return \OAuth2\HttpFoundationBridge\Response
     */
    public function getOAuthToken(Request $request)
    {
        $bridgedRequest  = \OAuth2\HttpFoundationBridge\Request::createFromRequest($request->instance());

        $bridgedResponse = new \OAuth2\HttpFoundationBridge\Response();

        $bridgedResponse = \App::make('oauth2')->handleTokenRequest($bridgedRequest, $bridgedResponse);

        return $bridgedResponse;
    }

    public function validateAccessToken(Request $request)
    {

        $req = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $bridgedRequest  = OAuthRequest::createFromRequest($req);
        $bridgedResponse = new \OAuth2\HttpFoundationBridge\Response();

        if (\App::make('oauth2')->verifyResourceRequest($bridgedRequest, $bridgedResponse)) {

            $token = \App::make('oauth2')->getAccessTokenData($bridgedRequest);

            return \Response::json(array(
                'private' => 'stuff',
                'user_id' => $token['user_id'],
                'client'  => $token['client_id'],
                'expires' => $token['expires'],
            ));
        }
        else {
            return \Response::json(array(
                'error' => 'Unauthorized'
            ), $bridgedResponse->getStatusCode());
        }
    }

    /**
     * This function will take the client_id, client_secret, refresh_token, grant_type=refresh_token
     * and then return the token.
     * @param \Illuminate\Http\Request $request
     * @return new access token for user
     */
    public function newAccessToken(Request $request)
    {

        $req = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $bridgedRequest  = OAuthRequest::createFromRequest($req);
        $bridgedResponse = new \OAuth2\HttpFoundationBridge\Response();

        if(!$value = \App::make('oauth2')->grantAccessToken($bridgedRequest, $bridgedResponse)) {
            return abort(422, 'Invalid Refresh Token.');
        }

        return $value;
    }
    
    public function facebook(Request $request)
    {
        $bridgedRequest  = \OAuth2\HttpFoundationBridge\Request::createFromRequest($request->instance());

        $bridgedResponse = new \OAuth2\HttpFoundationBridge\Response();

        $bridgedResponse = \App::make('oauth2')->handleTokenRequest($bridgedRequest, $bridgedResponse);

        Log::info(print_r($bridgedResponse, true));

        return $bridgedResponse;
    }

    public function googlePlus(Request $request)
    {
        $bridgedRequest  = \OAuth2\HttpFoundationBridge\Request::createFromRequest($request->instance());

        $bridgedResponse = new \OAuth2\HttpFoundationBridge\Response();

        $bridgedResponse = \App::make('oauth2')->handleTokenRequest($bridgedRequest, $bridgedResponse);

        Log::info(print_r($bridgedResponse, true));

        return $bridgedResponse;
    }

    public  function webGoogleLogin(Request $request) {
        session_start();
        $api = new \Google_Client();
        $api->setApplicationName("Propaget");
        $api->setClientId('541985294273-fjicp6bjr5imjapge1sc16rbia82cqd5.apps.googleusercontent.com'); // Set Client ID
        $api->setClientSecret('pwQAcUPP3-31p-8LjBtoBWSl'); //Set client Secret
        $api->setRedirectUri('http://localhost:8000/googleLogin');
        $api->setDeveloperKey('AIzaSyCjiJzv50nAt3P3uyJU_P-NEwFtR3fKLis');
        $service = new \Google_Service_Plus($api);

        //Log::info('CODE' .print_r($request->input('code'), true));
        $api->authenticate($request->input('code'));//Error Happened here
        $token = json_decode($api->getAccessToken());

        $google_oauthV2 = new \Google_Service_Oauth2($api);
        //Log::info('Token' .print_r($token, true));

        if ($api->getAccessToken()) {
            $data = $service->people->get('me');
            $user_data = $google_oauthV2->userinfo->get();

            $requestParams = array(
                'grant_type' => 'google',
                'client_id' => 'testclient',
                'client_secret' => 'testpass',
                'code' => $token->access_token,
            );
            Log::info('RESULT' . print_r($result, true));
        }

        return 'Hi';
    }

    public  function webGoogleLoginlink()
    {
        session_start();
        $api = new \Google_Client();
        $api->setApplicationName("Propaget"); // Set Application name
        $api->setClientId('541985294273-fjicp6bjr5imjapge1sc16rbia82cqd5.apps.googleusercontent.com'); // Set Client ID
        $api->setClientSecret('pwQAcUPP3-31p-8LjBtoBWSl'); //Set client Secret
        $api->setAccessType('online'); // Access method
        $api->setScopes(array('https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/plus.me', 'https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile'));
        $api->setRedirectUri('http://localhost:8000/googleLogin'); // Enter your file path (Redirect Uri) that you have set to get client ID in API console
        $service = new \Google_Service_Plus($api);
        $URI = $api->createAuthUrl();
        echo '<a href="' . $URI. '">Link</a>';
    }
}