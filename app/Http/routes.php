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

Route::get('/', 'WelcomeController@index');

Route::get('home', ['uses' => 'HomeController@index', 'as' => 'userHome']);

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




