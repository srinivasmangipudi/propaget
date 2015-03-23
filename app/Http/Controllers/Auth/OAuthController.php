<?php namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}