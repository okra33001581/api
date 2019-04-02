<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auz\UserController;

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


//event-management
//activity_list.vue
Route::get('event/activityList', 'EventController@activityList');
//activity_list.vue
Route::get('event/activitySubList', 'EventController@activitySubList');
//activity_list.vue
Route::get('event/eventUserPrizeList', 'EventController@eventUserPrizeList');
//activity_list.vue
Route::get('event/eventProcessList', 'EventController@eventProcessList');
//activity_list.vue
Route::post('event/eventSave', 'EventController@eventSave');
//activity_list.vue
Route::post('event/fileSave', 'EventController@fileSave');
//activity_list.vue
Route::post('event/eventDelete', 'EventController@eventDelete');

//activity_list.vue
Route::post('event/eventStatusSave', 'EventController@eventStatusSave');

//activity_list.vue
Route::post('event/eventUserPrizeStatusSave', 'EventController@eventUserPrizeStatusSave');

Route::get('event/getJson', 'AdminController@getJson');
Route::get('event/adminList', 'AdminController@adminList');
Route::post('event/adminSave', 'AdminController@adminSave');
Route::post('event/adminEdit', 'AdminController@adminEdit');
Route::post('event/adminDelete', 'AdminController@adminDelete');
Route::get('event/adminRoleList', 'AdminController@adminRoleList');




//delegate-management
//proxycommission_list.vue
Route::get('event/proxycommissionList', 'DelegateController@proxycommissionList');
//proxycommission_proxylist.vue
Route::get('event/proxycommissionProxylist', 'DelegateController@proxycommissionProxylist');
//event-management
//activity_list.vue
Route::get('event/activityList', 'EventController@activityList');
//fund-management
//cash_orderlist.vue
Route::get('event/cashOrderlist', 'FundController@cashOrderlist');
//cash_paysetting.vue
Route::get('event/cashPaysetting', 'FundController@cashPaysetting');
//cash_rakeback.vue
Route::get('event/cashRakeback', 'FundController@cashRakeback');
//cash_withdrawlist.vue
Route::get('event/cashWithdrawlist', 'FundController@cashWithdrawlist');
//companymoney_list.vue
Route::get('event/companymoneyList', 'FundController@companymoneyList');
//fastpaymoney_list.vue
Route::get('event/fastpaymoneyList', 'FundController@fastpaymoneyList');
//layerchart_index.vue
Route::get('event/layerchartIndex', 'FundController@layerchartIndex');
//manualpay_save.vue
Route::get('event/manualpaySave', 'FundController@manualpaySave');
//manualpayconfirm_list.vue
Route::get('event/manualpayconfirmList', 'FundController@manualpayconfirmList');
//payaccount_list.vue
Route::get('event/payaccountList', 'FundController@payaccountList');
//paygroup_list.vue
Route::get('event/paygroupList', 'FundController@paygroupList');
//transferorder_list.vue
Route::get('event/transferorderList', 'FundController@transferorderList');
//tripartite_list.vue
Route::get('event/tripartiteList', 'FundController@tripartiteList');
//userbetscheck_list.vue
Route::get('event/userbetscheckList', 'FundController@userbetscheckList');

Route::post('event/paysettingSave', 'FundController@paysettingSave');
Route::post('event/depositAccountSave', 'FundController@depositAccountSave');

Route::post('event/thirdAccountSave', 'FundController@thirdAccountSave');

Route::post('event/payGroupStatusSave', 'FundController@payGroupStatusSave');

Route::post('event/cashwithdrawStatusSave', 'FundController@cashwithdrawStatusSave');

//log-management
//log_adminlog.vue
Route::get('event/logAdminlog', 'LogController@logAdminlog');
//log_domainlog.vue
Route::get('event/logDomainlog', 'LogController@logDomainlog');
//log_loginlog.vue
Route::get('event/logLoginlog', 'LogController@logLoginlog');
//merchant-management
//proxy_grouplist.vue
Route::get('event/proxyGrouplist', 'MerchantController@proxyGrouplist');
//proxy_memberlist.vue
Route::get('event/proxyMemberlist', 'MerchantController@proxyMemberlist');
//notice-management
//marquee_list.vue
Route::get('event/marqueeList', 'NoticeController@marqueeList');
//message_list.vue
Route::get('event/messageList', 'NoticeController@messageList');
//notice_list.vue
Route::get('event/noticeList', 'NoticeController@noticeList');
//push_list.vue
Route::get('event/pushList', 'NoticeController@pushList');

Route::post('event/messageSave', 'NoticeController@messageSave');

Route::post('event/noticeSave', 'NoticeController@noticeSave');

Route::post('event/marqueeSave', 'NoticeController@marqueeSave');

Route::post('event/noticeStatusSave', 'NoticeController@noticeStatusSave');

Route::post('event/noticeTopSave', 'NoticeController@noticeTopSave');

//play-management
//betlimit_list.vue
Route::get('event/betlimitList', 'PlayController@betlimitList');
//lotteryrisk_list.vue
Route::get('event/lotteryriskList', 'PlayController@lotteryriskList');
//pgame_list.vue
Route::get('event/pgameList', 'PlayController@pgameList');
//proxygames_list.vue
Route::get('event/proxygamesList', 'PlayController@proxygamesList');
//report-management
//finance_index.vue
Route::get('event/financeIndex', 'ReportController@financeIndex');
//operation_profit.vue
Route::get('event/operationProfit', 'ReportController@operationProfit');
//pgame_playlist.vue
Route::get('event/pgamePlaylist', 'ReportController@pgamePlaylist');
//preport_profit.vue
Route::get('event/preportProfit', 'ReportController@preportProfit');
//user_report.vue
Route::get('event/userReport', 'ReportController@userReport');
//site-management
//floatwindowconfig_list.vue
Route::get('event/floatwindowconfigList', 'SiteController@floatwindowconfigList');
//information_companylist.vue
Route::get('event/informationCompanylist', 'SiteController@informationCompanylist');
//information_list.vue
Route::get('event/informationList', 'SiteController@informationList');
//lotterygroup_sort.vue
Route::get('event/lotterygroupSort', 'SiteController@lotterygroupSort');
//proxyiptables_blackcontainlist.vue
Route::get('event/proxyiptablesBlackcontainlist', 'SiteController@proxyiptablesBlackcontainlist');

Route::post('event/proxyiptablesBlackSave', 'SiteController@proxyiptablesBlackSave');

Route::post('event/systemConfigSave', 'SiteController@systemConfigSave');

Route::post('event/webIconSave', 'SiteController@webIconSave');

Route::post('event/qrCodeSave', 'SiteController@qrCodeSave');


Route::post('event/rotatePlaySave', 'SiteController@rotatePlaySave');

Route::post('event/floatWindowSave', 'SiteController@floatWindowSave');

Route::post('event/informationSave', 'SiteController@informationSave');

Route::post('event/companySave', 'SiteController@companySave');

Route::get('event/blacklist', 'SiteController@blacklist');

Route::post('event/blackDelete', 'SiteController@blackDelete');

Route::get('event/systemconfiglist', 'SiteController@systemconfiglist');

Route::post('event/informationStatusSave', 'SiteController@informationStatusSave');

//qrconfig_list.vue
Route::get('event/qrconfigList', 'SiteController@qrconfigList');
//rotationconfig_list.vue
Route::get('event/rotationconfigList', 'SiteController@rotationconfigList');
//systemconfig_imagelist.vue
Route::get('event/systemconfigImagelist', 'SiteController@systemconfigImagelist');
//systemconfig_set.vue
Route::get('event/systemconfigSet', 'SiteController@systemconfigSet');
//user-management
//user_infolist.vue
Route::get('event/userInfolist', 'UserController@userInfolist');
//user_inoutcash.vue
Route::get('event/userInoutcash', 'UserController@userInoutcash');
//user_mainlist.vue
Route::get('event/userMainlist', 'UserController@userMainlist');
//user_monitor.vue
Route::get('event/userMonitor', 'UserController@userMonitor');
//user_reviewlist.vue
Route::get('event/userReviewlist', 'UserController@userReviewlist');
//user_usercard.vue
Route::get('event/userUsercard', 'UserController@userUsercard');
//user_userlayer.vue
Route::get('event/userUserlayer', 'UserController@userUserlayer');
//user_validuser.vue
Route::get('event/userValiduser', 'UserController@userValiduser');


Route::post('event/userSave', 'UserController@userSave');
Route::post('event/userLevelSave', 'UserController@userLevelSave');
Route::post('event/bankCardSave', 'UserController@bankCardSave');
Route::post('event/bankcardStatusSave', 'UserController@bankcardStatusSave');

Route::post('event/userStatusSave', 'UserController@userStatusSave');
Route::post('event/usersafetyStatusSave', 'UserController@usersafetyStatusSave');


