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
    Route::get('view', ['uses' => 'RequirementController@getListRequirementPage', 'as' => 'viewRequirement']);
    Route::get('add', ['uses' => 'RequirementController@getAddRequirementPage', 'as' => 'addRequirement']);
    Route::post('save', ['uses' => 'RequirementController@postSaveRequirement', 'as' => 'saveRequirement']);
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('get-token', function () {
    $token = csrf_token();
    \Log::info('I was here for token: ' . $token);
    echo $token;
});

Route::post('register-device', function(Request $request) {
    \Log::info('Token send: ' . print_r($_SERVER, true));
    $postData = $request->input();

    $device = new Device;
    $device->deviceId = $postData['deviceId'];
    $device->registraionId = $postData['registrationId'];
    $device->save();

    print_r($_SERVER['HTTP_X_CSRF_TOKEN']);
});