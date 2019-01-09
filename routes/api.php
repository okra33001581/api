<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auz\UserController;

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
Route::post('event/login1', 'UserController@login1');
Route::post('event/out', 'UserController@out');
Route::post('event/password', 'UserController@password');
Route::post('event/userinfo', 'UserController@userinfo');

Route::post('event/loginIndex', 'UserController@loginIndex');
Route::get('event/loginInfo', 'UserController@loginInfo');


// PermissionRuleController.php
Route::get('event/permissionRuleIndex', 'PermissionRuleController@permissionRuleIndex');
Route::get('event/permissionRuleTree', 'PermissionRuleController@permissionRuleTree');
Route::post('event/permissionRuleSave', 'PermissionRuleController@permissionRuleSave');
Route::post('event/permissionRuleEdit', 'PermissionRuleController@permissionRuleEdit');
Route::post('event/permissionRuleDelete', 'PermissionRuleController@permissionRuleDelete');


// RoleController.php
Route::get('event/roleIndex', 'RoleController@roleIndex');
Route::get('event/roleAuthList', 'RoleController@roleAuthList');
Route::get('event/roleAuthListByUser', 'RoleController@roleAuthListByUser');
Route::post('event/roleAuth', 'RoleController@roleAuth');
Route::post('event/roleSave', 'RoleController@roleSave');
Route::post('event/roleEdit', 'RoleController@roleEdit');
Route::post('event/roleDelete', 'RoleController@roleDelete');


// AdminController.php
Route::get('event/adminIndex', 'AdminController@adminIndex');
Route::get('event/getJson', 'AdminController@getJson');
Route::get('event/adminList', 'AdminController@adminList');
Route::post('event/adminSave', 'AdminController@adminSave');
Route::post('event/adminEdit', 'AdminController@adminEdit');
Route::post('event/adminDelete', 'AdminController@adminDelete');
Route::get('event/adminRoleList', 'AdminController@adminRoleList');



//AdController.php
//siteDelete siteEdit siteSave siteList siteIndex
Route::get('event/siteIndex', 'EventsController@siteIndex');
Route::get('event/siteAdList', 'EventsController@siteAdList');
Route::post('event/siteEdit', 'EventsController@siteEdit');
Route::post('event/siteSave', 'EventsController@siteSave');
Route::post('event/siteDelete', 'EventsController@siteDelete');


//AdController.php
//adIndex adSave  adEdit adDelete
Route::get('event/adIndex', 'EventsController@adIndex');
Route::post('event/adSave', 'EventsController@adSave');
Route::post('event/adEdit', 'EventsController@adEdit');
Route::post('event/adDelete', 'EventsController@adDelete');


//ResourceController.php
Route::get('event/resourceIndex', 'EventsController@resourceIndex');
Route::get('event/resourceTagIndex', 'EventsController@resourceTagIndex');
Route::post('event/resourceTagAdd', 'EventsController@resourceTagAdd');

