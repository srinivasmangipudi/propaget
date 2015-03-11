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

Route::group(['middleware' => 'auth', 'prefix' => 'requirements'], function() {
    Route::get('add', ['uses' => 'RequirementController@getAddRequirementPage', 'as' => 'addRequirement']);
    Route::post('save', ['uses' => 'RequirementController@postSaveRequirement', 'as' => 'saveRequirement']);
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);