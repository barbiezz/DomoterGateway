<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//HOME & USERS
Route::any('/home', 'HomeController@index')->name('home');

//GATEWAYS
Route::any('/gateway', 'GatewayDataController@index')->name('gatewayIndex');

Route::any('/addGateway', 'GatewayDataController@create')->name('gatewayCreate');
Route::post('/gateway', 'GatewayDataController@insert')->name('gatewayInsert');

Route::any('/gateway/delete/{id}', 'GatewayDataController@remove')->name('gatewayRemove');
Route::any('/gateway/{id}', 'GatewayDataController@delete')->name('gatewayDelete');

Route::get('/gateway/edit/{id}', 'GatewayDataController@edit')->name('gatewayEdit');
Route::post('/gateway/edited/{id}', 'GatewayDataController@update')->name('gatewayUpdate');

Route::any('/gateway/{name}/info', 'GatewayDataController@info')->name('gatewayInfo');

//APPS
Route::any('/applications', 'AppDataController@index')->name('appIndex');

Route::any('/addApp', 'AppDataController@create')->name('appCreate');
Route::post('/applications/inserted', 'AppDataController@insert');

Route::get('/applications/edit/{id}', 'AppDataController@edit')->name('appEdit');
Route::post('/applications/edited/{id}', 'AppDataController@update')->name('appUpdate');

Route::any('/applications/delete/{id}', 'AppDataController@remove')->name('appRemove');
Route::any('/applications/{id}', 'AppDataController@delete')->name('appDelete');

Route::any('/applications/{name}/info', 'AppDataController@info')->name('appInfo');

//DEVICE-PROFILE
Route::any('/deviceProfiles', 'DeviceProfileDataController@index')->name('deviceProfile');

Route::any('/addDeviceProfile', 'DeviceProfileDataController@create')->name('devProfileCreate');
Route::post('/deviceProfiles/inserted', 'DeviceProfileDataController@store')->name('devProfileInsert');

Route::get('/deviceProfiles/edit/{id}', 'DeviceProfileDataController@edit')->name('devProfileEdit');
Route::post('/deviceProfiles/edited/{id}', 'DeviceProfileDataController@update')->name('devProfileUpdate');

Route::any('/deviceProfiles/delete/{id}', 'DeviceProfileDataController@remove')->name('devProfileRemove');
Route::any('/deviceProfiles/{id}', 'DeviceProfileDataController@delete')->name('devProfileDelete');

//DEVICE
Route::get('/applications/{name}/devices', 'DeviceDataController@index')->name('devIndex');
Route::post('/applications/{name}/devices', 'MqttController@func');
Route::any('/send/{command}', 'MqttController2@func');
Route::any('/connect/request', 'MqttController@sendRequest');
Route::any('/connect/request', 'MqttController2@sendRequest');
Route::any('/applications/{name}/addDevice', 'DeviceDataController@create')->name('devCreate');
Route::post('/applications/{name}/device/inserted', 'DeviceDataController@insert')->name('devInsert');

Route::get('/devices/edit/{id}', 'DeviceDataController@edit')->name('devEdit');
Route::post('/applications/{name}/devices/edited/{id}', 'DeviceDataController@update')->name('devUpdate');

Route::any('/devices/delete/{id}', 'DeviceDataController@remove')->name('devRemove');
Route::any('/applications/{name}/devices/deleted/{id}', 'DeviceDataController@delete')->name('devDelete');

//CLOUD
// Authentication Routes...
//Route::get('cloud/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('cloud/login', 'Auth\LoginController@sendRequest');
Route::post('cloud/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/activate/{id}', 'GatewayDataController@activate');
