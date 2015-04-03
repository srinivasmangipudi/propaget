<?php namespace App\Http\Controllers\Auth;

use Exception;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Illuminate\Support\Facades\Log;
use OAuth2\GrantType\GrantTypeInterface;
use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;
use OAuth2\ResponseType\AccessTokenInterface;

/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 2/4/15
 * Time: 4:50 PM
 */
class FacebookGrantType implements GrantTypeInterface {

    protected $user;

    public function getQuerystringIdentifier()
    {
        return 'facebook';
    }

    public function validateRequest(RequestInterface $request, ResponseInterface $response)
    {
        FacebookSession::setDefaultApplication('548793841817925', '7fac926e1d8429b81825b56d46a3afc6');
        $session = new FacebookSession($request->request('code'));
        try {
            $session->validate();
            $response = (new FacebookRequest($session, 'GET', '/me'))->execute();
            $object = $response->getGraphObject();

            $this->user->user_id = $object->getProperty('name');
            $this->user->facebook_id = $object->getProperty('id');
            $this->user->email = $object->getProperty('email');
            $this->user->first_name = $object->getProperty('first_name');
            $this->user->last_name = $object->getProperty('last_name');
            $this->user->verified = $object->getProperty('verified');

            if ($this->checkIfUserExist()) {
                // update profile
            } else {
                // create profile
            }

        } catch (FacebookRequestException $ex) {
            // Session not valid, Graph API returned an exception with the reason.
            Log::info('Session not valid, Graph API returned an exception with the reason.' . print_r($ex, true));
            return false;
        } catch (Exception $ex) {
            // Graph API returned info, but it may mismatch the current app or have expired.
            Log::info('Graph API returned info, but it may mismatch the current app or have expired.' . print_r($ex, true));
            return false;
        }

        return true;
    }

    public function getClientId()
    {
        return null;
    }

    public function getUserId()
    {
        return $this->user->user_id;
    }

    public function getScope()
    {
        return null;
    }

    public function createAccessToken(AccessTokenInterface $accessToken, $client_id, $user_id, $scope)
    {
        return $accessToken->createAccessToken($client_id, $user_id, $scope);
    }

    private function checkIfUserExist()
    {
        $user = User::where('email', $this->user->email)->first();

        if ($user) {
            return true;
        } else {
            return false;
        }
    }
}