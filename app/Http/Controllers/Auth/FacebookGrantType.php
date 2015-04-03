<?php namespace App\Http\Controllers\Auth;

use App\User;
use Exception;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Illuminate\Support\Facades\DB;
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
            Log::info(print_r($object, true));
            $facebookUserObject = [
                'id' => $object->getProperty('id'),
                'email' => $object->getProperty('email'),
                'first_name' => $object->getProperty('first_name'),
                'gender' => $object->getProperty('gender'),
                'last_name' => $object->getProperty('last_name'),
                'link' => $object->getProperty('link'),
                'locale' => $object->getProperty('locale'),
                'name' => $object->getProperty('name'),
                'timezone' => $object->getProperty('timezone'),
                'updated_time' => $object->getProperty('updated_time'),
                'verified' => $object->getProperty('verified'),
            ];
            // set up the user object properties
            $this->user['user_id'] = $facebookUserObject['name'];
            $this->user['facebook_id'] = $facebookUserObject['id'];
            $this->user['email'] = $facebookUserObject['email'];
            $this->user['first_name'] = $facebookUserObject['first_name'];
            $this->user['last_name'] = $facebookUserObject['last_name'];
            $this->user['name'] = $facebookUserObject['name'];
            $this->user['verified'] = $facebookUserObject['verified'];

            // checking if the user is already present in the system
            if ($this->checkIfUserExist()) {
                // update profile
                $this->updateProfile($facebookUserObject);
            } else {
                // create profile
                $this->createdUserProfile($facebookUserObject);
            }
        }

        return true;
    }

    public function getClientId()
    {
        return null;
    }

    /**
     * This function will return the current user's id.
     *
     * @return mixed
     */
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
            $this->user['id'] = $user->id;
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is updating the profile of the user
     *
     * @param $facebookUserObject
     */
    private function updateProfile($facebookUserObject)
    {
        DB::table('users_profile')
            ->where('user_id', $this->user['id'])
            ->update([
                'data' => serialize($facebookUserObject),
            ]);
    }

    /**
     * This function will take the user object
     * and create a user object.
     *
     */
    private function createdUserProfile($facebookUserObject)
    {
        try {
            DB::beginTransaction();

            $user = new User;
            $user->name = $this->user['name'];
            $user->email = $this->user['email'];
            $user->user_type = 'facebook';
            $user->status = 1;
            $user->role = 'agent';
            $user->save();

            DB::table('users_profile')->insert([
                'user_id' => $user->id,
                'uid' => $facebookUserObject['id'],
                'user_type' => 'facebook',
                'data' => serialize($facebookUserObject),
            ]);

            DB::commit();

            $this->user['id'] = $user->id;
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Database transaction failed. Error: ' . print_r($e->getMessage(), true));
        }
    }
}