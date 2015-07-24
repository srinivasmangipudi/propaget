<?php
/**
 * Created by PhpStorm.
 * User: komal
 * Date: 3/4/15
 * Time: 2:39 PM
 */

namespace App\Http\Controllers\Auth;

use App\User;
use Exception;
use Facebook\FacebookRequest;
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
class GooglePlusGrantType implements GrantTypeInterface {

    protected $user;

    /**
     * Return the query string to tell O Auth it's a facebook grant
     *
     * @return string
     */
    public function getQuerystringIdentifier()
    {
        return 'google';
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
        session_start();
        $api = new \Google_Client();
        $api->setApplicationName("Propaget");
        $api->setClientId('541985294273-fjicp6bjr5imjapge1sc16rbia82cqd5.apps.googleusercontent.com'); // Set Client ID
        $api->setClientSecret('pwQAcUPP3-31p-8LjBtoBWSl'); //Set client Secret
        $api->setRedirectUri('http://192.168.7.205/RND/laravel_rnd/propaget/public/googleLogin');
        $api->setDeveloperKey('AIzaSyCjiJzv50nAt3P3uyJU_P-NEwFtR3fKLis');
        //$api->setScopes(array('https://www.googleapis.com/auth/plus.profile.emails.read', 'https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/userinfo.profile', 'https://www.googleapis.com/auth/userinfo.email'));



        try {
            $service = new \Google_Service_Plus($api);

            //$api->authenticate($request->request('code'));
            // Verify the token
            $reqUrl = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' .
                $request->request('code');
            $req = new \Google_Http_Request($reqUrl);

            $tokenInfo = json_decode(
                $api->getAuth()->authenticatedRequest($req)->getResponseBody());

            $object = $service->people->get($tokenInfo->user_id);

        }
        catch (Exception $ex) {
            // Graph API returned info, but it may mismatch the current app or have expired.
            Log::info('Graph API returned info, but it may mismatch the current app or have expired.');
            Log::info('ex' . print_r($ex->getMessage(), true));

            return false;
        }

        if ($object) {

            $googleUserObject = [
                'id' => $tokenInfo->user_id,
                'email' => $tokenInfo->email,
                'name' => $object->getDisplayName(),
                'verified' => $object->getVerified(),
            ];

            // set up the user object properties
            $this->user['user_id'] = $googleUserObject['id'];
            $this->user['email'] = $googleUserObject['email'];
            $this->user['name'] = $googleUserObject['name'];
            $this->user['verified'] = $googleUserObject['verified'];

            // checking if the user is already present in the system
            if ($this->checkIfUserExist()) {
                // update profile
                $this->updateProfile($googleUserObject);
            } else {
                // create profile
                $this->createdUserProfile($googleUserObject);
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
            $this->user['id'] = $user->id;
            return true;
        } else {
            return false;
        }
    }

    private function updateProfile($googleUserObject)
    {
        DB::table('users_profile')
            ->where('user_id', $this->user['id'])
            ->update([
                'data' => serialize($googleUserObject),
            ]);
    }

    /**
     * This function will take the user object
     * and create a user object.
     *
     * @param $object
     */
    private function createdUserProfile($googleUserObject)
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
                'uid' => $googleUserObject['id'],
                'user_type' => 'google',
                'data' => serialize($googleUserObject),
            ]);

            DB::commit();

            $this->user['id'] = $user->id;
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Database transaction failed. Error: ' . print_r($e->getMessage(), true));
        }
    }
}