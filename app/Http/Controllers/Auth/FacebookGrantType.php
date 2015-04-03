<?php namespace App\Http\Controllers\Auth;

use App\User;
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

    /**
     * Return the query string to tell O Auth it's a facebook grant
     *
     * @return string
     */
    public function getQuerystringIdentifier()
    {
        return 'facebook';
    }

    /**
     * Validating the user object from the token passed through mobile
     * Function will check if the user exist it will update profile
     * or else create a new user.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool
     */
    public function validateRequest(RequestInterface $request, ResponseInterface $response)
    {
        FacebookSession::setDefaultApplication('548793841817925', '7fac926e1d8429b81825b56d46a3afc6');

        $session = new FacebookSession($request->request('code'));

        try {
            $session->validate();
            $response = (new FacebookRequest($session, 'GET', '/me'))->execute();
            $object = $response->getGraphObject();

        } catch (FacebookRequestException $ex) {
            // Session not valid, Graph API returned an exception with the reason.
            Log::info('Session not valid, Graph API returned an exception with the reason.');
            return false;
        } catch (Exception $ex) {
            // Graph API returned info, but it may mismatch the current app or have expired.
            Log::info('Graph API returned info, but it may mismatch the current app or have expired.');
            return false;
        }

        if ($object) {
            // set up the user object properties
            $this->user['user_id'] = $object->getProperty('name');
            $this->user['facebook_id'] = $object->getProperty('id');
            $this->user['email'] = $object->getProperty('email');
            $this->user['first_name'] = $object->getProperty('first_name');
            $this->user['last_name'] = $object->getProperty('last_name');
            $this->user['name'] = $object->getProperty('name');
            $this->user['verified'] = $object->getProperty('verified');

            // checking if the user is already present in the system
            if ($this->checkIfUserExist()) {
                // update profile
                $this->updateProfile($object);
            } else {
                // create profile
                $this->createdUserProfile($object);
            }
        }

        return true;
    }

    public function getClientId()
    {
        return null;
    }

    public function getUserId()
    {
        return $this->user['id'];
    }

    public function getScope()
    {
        return null;
    }

    /**
     * Implementing the method from the interface to return the access token
     *
     * @param AccessTokenInterface $accessToken
     * @param $client_id
     * @param $user_id
     * @param $scope
     * @return mixed
     */
    public function createAccessToken(AccessTokenInterface $accessToken, $client_id, $user_id, $scope)
    {
        return $accessToken->createAccessToken($client_id, $user_id, $scope);
    }

    /**
     * Check if user exist in the system
     * using the email address
     *
     * @return bool
     */
    private function checkIfUserExist()
    {
        $user = User::where('email', $this->user['email'])->first();

        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    private function updateProfile($object)
    {

    }

    /**
     * This function will take the user object
     * and create a user object.
     *
     * @param $object
     */
    private function createdUserProfile($object)
    {
        $user = new User;
        $user->name = $this->user['name'];
        $user->email = $this->user['email'];
        $user->user_type = 'facebook';
        $user->status = 1;
        $user->role = 'agent';
        $user->save();

        $this->user['id'] = $user->id;
    }
}