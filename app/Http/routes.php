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
use Illuminate\Http\Request;
use App\Device;

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