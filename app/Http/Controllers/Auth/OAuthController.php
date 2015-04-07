<?php namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Facebook\FacebookRedirectLoginHelper;
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
     * @param Request $request
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
     * @param Request $request
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
        return $bridgedResponse;
    }

    public function handleFacebookLogin(Request $request)
    {
        session_start();
        FacebookSession::setDefaultApplication('298422880260349', 'f312d7913a0acb866223483eb216c8f3');
        $helper = new FacebookRedirectLoginHelper('http://localhost:8000/fb-test');

        try {
            $session = $helper->getSessionFromRedirect();
        } catch(\Facebook\FacebookRequestException $ex) {
            // When Facebook returns an error
            dd('When Facebook returns an error');
        } catch(\Exception $ex) {
            // When validation fails or other local issues
            dd('When validation fails or other local issues');
        }
        return view('auth.facebook-confirm')
            ->with('token', $session->getAccessToken())
            ->with('access_token', $session->getAccessToken());
    }

    public function handleFbPost(Request $request)
    {
        $params = [
            'grant_type' => 'facebook',
            'code' => $request->input('code'),
            'client_id' => 'testclient',
            'client_secret' => 'testpass',
            'client' => 'web',
        ];

        /* Call get token route to get access token after user logs in */
        $tokenRequest = Request::create('mobilefb', 'POST', $params);
        $request->replace($tokenRequest->input()); /* To replace the request parameters with new one */
        $OauthTokenData = json_decode(Route::dispatch($tokenRequest)->getContent());

        /* Stored access token in the cookie */
        setcookie('access_token', $OauthTokenData->access_token, 0, '/', null, false, false);

        return redirect('/home');
    }
}