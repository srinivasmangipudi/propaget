<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\DistList;
use App\User;
use Illuminate\Http\Request;
use App\Device;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Route::get('/', 'WelcomeController@index');

Route::get('home', ['uses' => 'HomeController@index', 'as' => 'userHome']);

Route::group(['middleware' => 'auth', 'prefix' => 'requirements'], function() {
    Route::get('view', ['uses' => 'RequirementoldController@getListRequirementPage', 'as' => 'viewRequirement']);
    Route::get('add', ['uses' => 'RequirementoldController@getAddRequirementPage', 'as' => 'addRequirement']);
    Route::post('save', ['uses' => 'RequirementoldController@postSaveRequirement', 'as' => 'saveRequirement']);
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


Route::get('/fb/login', 'SocialLoginController@fb_login');
//Route::get('/fb/login/done', 'SocialLoginController@fbloginUser');


Route::get('get-token', function() {
    return csrf_token();
});

Route::get('see-tokens', function() {
    echo Session::getId();
    echo '<br />';
    echo Session::token();
    echo '<br />';
    echo csrf_token();
});

Route::get('mobile-logout', function() {
    Session::flush();
});

//Route::post('mobile/login', 'SocialLoginController@mobile_login', ['middleware' => 'auth.token']);
Route::post('mobile/login', 'SocialLoginController@mobile_login');
Route::post('testingAuth', ['middleware' => 'auth.token', function () {
   return 'Successfully Authenticated';
}]);


Route::resource('dist-list', 'DistListController');
Route::controller('dist-list', 'DistListController');

Route::resource('req-list','RequirementController');
Route::controller('req-list','RequirementController');

Route::post('register-device', function(Request $request) {
    $postData = $request->input();

    $device = new Device;
    $device->deviceId = $postData['deviceId'];
    $device->registraionId = $postData['registrationId'];
    $device->save();

    /*$gcm = new GcmHelper;
    $gcm->sendNotification(
        array($device->registraionId),
        array('title' => 'Device registered', 'message' => 'Congratulations, your devie has been registered with us.')
    );*/
});

Route::resource('property', 'PropertyController');
Route::get('properties', 'PropertyAppController@index');

Route::post('test-me', function(Request $request)
{
    /*$members = array('+919820098200', '+919820099583', '+919820098355', '+919820099278', '+919820099825', '+919820215537');
    print '<pre>'; print_r($members); print '</pre>';

    $userData = DB::table('users')->select(array('id', 'phoneNumber'))->whereIn('phoneNumber', $members)->get();
    print '<pre>'; print_r($userData); print '</pre>';

    $finalArray = array();

    foreach ($userData as $row)
    {
        if (in_array($row->phoneNumber, $members))
        {
            // push to the final array
            $finalArray[$row->id] = $row->phoneNumber;
            // search for the key
            $key = array_search($row->phoneNumber, $members);
            // unset the array index so that next time the search is quicker.
            unset($members[$key]);
        }
    }

    $notPresent = array_diff($members, $finalArray);

    echo '<br />';
    print '<pre>'; print_r($finalArray); print '</pre>';
    echo '<br />';
    print '<pre>'; print_r($notPresent); print '</pre>';

    dd($userData);*/

    /*Log::info('I was here: ' . time());
    // get all post data
    $postData = $request->input();
    $contacts = json_decode($postData['members']);

    $distList = new DistList;
    $distList->name = $postData['name'];
    $distList->createdBy = $postData['createdBy'];
        $distList->save();

    $distListId = $distList->id;
    $arrUserIds = array();

    // sanitise
    foreach ($contacts as $key => $member)
    {
        $contacts[$key] = str_replace(' ', '', $member);
    }

    $existingContacts = DB::table('users')->whereIn('phoneNumber', $contacts)->get();

    if (count($existingContacts) == 0)
    {
        // all new contacts
        foreach ($contacts as $c)
        {
            $user = new User;
            $user->name = "propagate_" . $c;
            $user->phoneNumber = $c;
            $user->email = $c . '@propagate.com';
            $user->password = 'pass';
            $user->userType = 'normal';
            $user->userId = '0';
                $user->save();
            $arrUserIds[] = $user->id;
        }
    }
    else
    {
        // some users needs to be created
        Log::info('some users needs to be created');

        $arrDBUsers = array();
        foreach ($existingContacts as $dbUsers)
        {
            $arrDBUsers[] = $dbUsers->phoneNumber;
            $arrUserIds[] = $dbUsers->id;
        }

        $uniqueUsers = array_diff($contacts, $arrDBUsers);
        Log::info('unique users');
//        Log::info(print_r($uniqueUsers, true));

        foreach ($uniqueUsers as $c)
        {
            $user = new User;
            $user->name = "propagate_" . $c;
            $user->phoneNumber = $c;
            $user->email = $c . '@propagate.com';
            $user->password = 'pass';
            $user->userType = 'normal';
            $user->userId = '0';
            $user->save();
            $arrUserIds[] = $user->id;
        }
    }

    foreach ($arrUserIds as $ref)
    {
        $distListMem = new App\DistListMembers;
        $distListMem->distListId = $distListId;
        $distListMem->userId = $ref;
            $distListMem->save();
    }

    return array();*/
});