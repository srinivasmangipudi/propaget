<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use OAuth2\HttpFoundationBridge\Request as OAuthRequest;

class OAuthTokenVerification {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // check if the access token is present
        if (!$request->input('access_token'))
        {
            return abort(422, 'Token not found');
        }

        $req = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $bridgedRequest  = OAuthRequest::createFromRequest($req);
        $bridgedResponse = new \OAuth2\HttpFoundationBridge\Response();

        /*if (!\App::make('oauth2')->verifyResourceRequest($bridgedRequest, $bridgedResponse)) {
            return abort(422, 'Token validation failed.');
        } else {
            $token = \App::make('oauth2')->getAccessTokenData($bridgedRequest);
            $request['user_id'] = $token['user_id'];
        }*/



        /** To Return error codes for access token expired **/
        if (!$token = App::make('oauth2')->getAccessTokenData($bridgedRequest, $bridgedResponse)) {
            $response = App::make('oauth2')->getResponse();

            if ($response->isClientError() && $response->getParameter('error')) {
                if($response->getParameter('error') == 'expired_token') {
                    return abort(401, 'The access token provided has expired');
                }
                return abort(422, 'Invalid Token.');
            }
            /*Log::info('RESPONSE === '. print_r($response, true));*/
        }
        else {
            $request['user_id'] = $token['user_id'];

        }

        return $next($request);
    }

}
