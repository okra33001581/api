<?php

use Illuminate\Http\Request;

///
///
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Route::get('articles', 'ArticleController@index');
Route::get('articles', 'ArticleController@index');
Route::get('articles/{id}', 'ArticleController@show');
Route::post('articles', 'ArticleController@store');
Route::put('articles/{id}', 'ArticleController@update');
Route::delete('articles/{id}', 'ArticleController@delete');


Route::get('login', 'ArticleController@login');
Route::get('user/info', 'ArticleController@info');
Route::get('article/list', 'ArticleController@list');


// events
Route::get('event/list', 'EventsController@list');

Route::post('event/update', 'EventsController@update');


// LoginController.php
Route::post('event/login1', 'EventsController@login1');
Route::post('event/out', 'EventsController@out');
Route::post('event/password', 'EventsController@password');
Route::post('event/userinfo', 'EventsController@userinfo');

Route::post('event/loginIndex', 'EventsController@loginIndex');
Route::get('event/loginInfoa', 'EventsController@loginInfoa');


// PermissionRuleController.php
Route::get('event/permissionRuleIndex', 'EventsController@permissionRuleIndex');
Route::get('event/permissionRuleTree', 'EventsController@permissionRuleTree');

// RoleController.php
Route::get('event/roleIndex', 'EventsController@roleIndex');
Route::get('event/roleAuthList', 'EventsController@roleAuthList');
Route::post('event/roleSave', 'EventsController@roleSave');
Route::post('event/roleEdit', 'EventsController@roleEdit');
Route::post('event/roleAuth', 'EventsController@roleAuth');



// AdminController.php
Route::get('event/adminIndex', 'EventsController@adminIndex');
Route::get('event/adminRoleList', 'EventsController@adminRoleList');
Route::post('event/adminsave', 'EventsController@adminsave');
Route::post('event/adminedit', 'EventsController@adminedit');


//AdController.php
//siteDelete siteEdit siteSave siteList siteIndex
Route::get('event/siteIndex', 'EventsController@siteIndex');
Route::get('event/siteList', 'EventsController@siteList');
Route::post('event/siteEdit', 'EventsController@siteEdit');
Route::post('event/siteSave', 'EventsController@siteSave');
Route::post('event/siteDelete', 'EventsController@siteDelete');


//AdController.php
//adIndex adSave  adEdit adDelete
Route::get('event/adIndex', 'EventsController@adIndex');
Route::post('event/adSave', 'EventsController@adSave');
Route::post('event/adEdit', 'EventsController@adEdit');
Route::post('event/adDelete', 'EventsController@adDelete');


