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

Route::get('/', 'Auth\AuthController@getLogin');

Route::get('home', 'HomeController@index');
Route::resource('prices', 'PricesController');
Route::post('prices/updateMethod', ['as' => 'prices.updateMethod', 'uses' => 'PricesController@updateMethod']);
Route::post('prices/updateFixed', ['as' => 'prices.updateFixed', 'uses' => 'PricesController@updateFixed']);

Route::get('profile', ['as' => 'profile.show', 'uses' => 'UserController@getProfile']);
Route::post('profile', ['as' => 'profile.update', 'uses' => 'UserController@postProfile']);

Route::get('settings', ['as' => 'settings.index', 'uses' => 'SettingsController@index']);
Route::post('settings', ['as' => 'settings.update', 'uses' => 'SettingsController@update']);
Route::post('settings/ACStest', ['as' => 'settings.acstest', 'uses' => 'SettingsController@testACS']);
Route::post('settings/syncACS', ['as' => 'settings.syncacs', 'uses' => 'SettingsController@syncACS']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::resource('reseller', 'ResellerController');
Route::resource('firewall', 'FirewallController');
Route::resource('storagetypes', 'StorageTypeController', ['except' => ['show', 'create', 'store']]);

// API Routes
Route::get('api/getRecords/domainid/{domainid}/apiKey/{apiKey}/secretKey/{secretKey}/lastDate/{lastDate}', 'ApiController@getRecords');
Route::get('api/getPricing', 'ApiController@getPricing');
Route::get('api/getResourceLimits', 'ApiController@getResourceLimits');