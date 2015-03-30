<?php

use App\Http\Controllers\Auth\DoLoginPdo;

Route::get('/', 'WelcomeController@index');

/*Device registration */
Route::post('register-device', 'DeviceController@registerDevice');

Route::get('home', ['uses' => 'HomeController@index', 'as' => 'userHome']);

Route::group(['middleware' => 'auth', 'prefix' => 'requirementsold'], function() {
    Route::get('view', ['uses' => 'Requirementctrl\RequirementoldController@getListRequirementPage', 'as' => 'viewRequirement']);
    Route::get('add', ['uses' => 'Requirementctrl\RequirementoldController@getAddRequirementPage', 'as' => 'addRequirement']);
    Route::post('save', ['uses' => 'Requirementctrl\RequirementoldController@postSaveRequirement', 'as' => 'saveRequirement']);
});

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::group(['middleware' => 'oauth'], function() {
    Route::resource('req-list','Requirementctrl\RequirementController');
    Route::resource('property', 'Property\PropertyController');
    Route::resource('dist-list', 'Distribution\DistListController');
});

Route::get('requirements', 'Requirementctrl\RequirementController@indexpage');
Route::get('requirements/list', 'Requirementctrl\RequirementController@listing');
Route::get('requirements/view', 'Requirementctrl\RequirementController@view');
Route::get('requirements/add', 'Requirementctrl\RequirementController@add');

Route::get('properties', 'Property\PropertyController@indexpage');
Route::get('properties/list', 'Property\PropertyController@listing');
Route::get('properties/add', 'Property\PropertyController@add');
Route::get('properties/view', 'Property\PropertyController@view');

Route::get('distribution', 'Distribution\DistListController@indexpage');
Route::get('distribution/list', 'Distribution\DistListController@listing');
Route::get('distribution/view', 'Distribution\DistListController@view');


Route::get('/fb/login', 'SocialLogin\SocialLoginController@fb_login');
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
Route::post('mobile/login', 'SocialLogin\SocialLoginController@mobile_login');
Route::post('testingAuth', ['middleware' => 'auth.token', function () {
    return 'Successfully Authenticated';
}]);

Route::post('oauth/token', 'Auth\OAuthController@getOAuthToken');
//Route::get('oauth/get-access', 'Auth\OAuthController@validateAccessToken');

App::singleton('oauth2', function() {
    $storage = new DoLoginPdo(array(
        'dsn' => 'mysql:dbname=' . env('DB_DATABASE') . ';host=' . env('DB_HOST'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD')
    ));

    $server = new OAuth2\Server($storage);

    $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
    $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
    $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
    $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage));

    return $server;
});

Route::post('get-new-token', 'Auth\OAuthController@newAccessToken');


Route::group(['middleware' => 'oauth', 'prefix' => 'oauth'], function() {
    Route::get('get-access', 'Auth\OAuthController@validateAccessToken');
});

