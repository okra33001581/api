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
Route::get('event/userIndex', 'UserController@userIndex');
Route::get('event/userLoginIpsIndex', 'UserController@userLoginIpsIndex');
Route::get('event/userPrizeSetsIndex', 'UserController@userPrizeSetsIndex');



Route::get('event/profitIndex', 'ReportController@profitIndex');
Route::get('event/issueProfitsIndex', 'ReportController@issueProfitsIndex');
Route::get('event/lotteryProfitsIndex', 'ReportController@lotteryProfitsIndex');
Route::get('event/monthProfitsIndex', 'ReportController@monthProfitsIndex');
Route::get('event/lotteryMonthProfitsIndex', 'ReportController@lotteryMonthProfitsIndex');
Route::get('event/teamProfitsIndex', 'ReportController@teamProfitsIndex');
Route::get('event/userProfitsIndex', 'ReportController@userProfitsIndex');
Route::get('event/userMonthProfitsIndex', 'ReportController@userMonthProfitsIndex');
Route::get('event/teamMonthProfitsIndex', 'ReportController@teamMonthProfitsIndex');
Route::get('event/userLotteryProfitsIndex', 'ReportController@userLotteryProfitsIndex');
Route::get('event/teamLotteryProfitsIndex', 'ReportController@teamLotteryProfitsIndex');
Route::get('event/wayProfitsIndex', 'ReportController@wayProfitsIndex');
Route::get('event/lotteryWayProfitsIndex', 'ReportController@lotteryWayProfitsIndex');
Route::get('event/userLotteryWayProfitsIndex', 'ReportController@userLotteryWayProfitsIndex');
Route::get('event/teamLotteryWayProfitsIndex', 'ReportController@teamLotteryWayProfitsIndex');
Route::get('event/terminalProfitsIndex', 'ReportController@terminalProfitsIndex');







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




Route::get('event/eventIndex', 'EventsController@eventIndex');
Route::get('event/eventUserPrizeIndex', 'EventsController@eventUserPrizeIndex');
Route::get('event/eventPrizeIndex', 'EventsController@eventPrizeIndex');
Route::get('event/eventConditonsIndex', 'EventsController@eventConditonsIndex');


Route::get('event/adTypeIndex', 'AdController@adTypeIndex');
Route::get('event/adLocationIndex', 'AdController@adLocationIndex');
Route::get('event/adInfosIndex', 'AdController@adInfosIndex');

Route::get('event/adminLogIndex', 'LogController@adminLogIndex');
Route::get('event/userLogIndex', 'LogController@userLogIndex');



Route::get('event/sysConfigIndex', 'DevelopController@sysConfigIndex');




Route::get('event/accoundIndex', 'FundController@accoundIndex');

Route::get('event/transactionIndex', 'FundController@transactionIndex');
Route::get('event/dispositIndex', 'FundController@dispositIndex');
Route::get('event/bankDepositIndex', 'FundController@bankDepositIndex');
Route::get('event/exceptionDepositIndex', 'FundController@exceptionDepositIndex');
Route::get('event/menuDepositIndex', 'FundController@manuDepositIndex');
Route::get('event/manuWithdrawalsIndex', 'FundController@manuWithdrawalsIndex');
Route::get('event/manuDividends', 'FundController@manuDividends');
Route::get('event/loseCommissionsIndex', 'FundController@loseCommissionsIndex');
Route::get('event/commissionsStatisticsIndex', 'FundController@commissionsStatisticsIndex');
Route::get('event/platTransferRecordsIndex', 'FundController@platTransferRecordsIndex');
Route::get('event/withdrawalsIndex', 'FundController@withdrawalsIndex');











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




