<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\Event;
use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\AuthAdmin;
use App\model\AuthRoleAdmin;
use App\model\AuthPermission;
use App\model\AuthPermissionRule;
use App\model\AuthRole;
use App\common\utils\PublicFileUtils;
use App\common\utils\PassWordUtils;
use App\model\Ad;
use App\model\AdSite;
use App\model\FileResource;
use App\model\FileResourceTag;

use Illuminate\Support\Facades\Redis;
use Storage;
use App\model\EventUserPrize;

/**
 * Class Event - 活动控制器
 * @author zebra
 */
class EventController extends Controller
{

    /**
     * @api {get} /api/admin 显示商户列表
     * @apiGroup admin
     *
     *
     * @apiSuccessExample 返回商户信息列表
     * HTTP/1.1 200 OK
     * {
     *  "data": [
     *     {
     *       "id": 2 // 整数型  用户标识
     *       "name": "test"  //字符型 用户昵称
     *       "email": "test@qq.com"  // 字符型 用户email，商户登录时的email
     *       "role": "admin" // 字符型 角色  可以取得值为admin或editor
     *       "avatar": "" // 字符型 用户的头像图片
     *     }
     *   ],
     * "status": "success",
     * "status_code": 200,
     * "links": {
     * "first": "http://manger.test/api/admin?page=1",
     * "last": "http://manger.test/api/admin?page=19",
     * "prev": null,
     * "next": "http://manger.test/api/admin?page=2"
     * },
     * "meta": {adminDelete
     * "current_page": 1, // 当前页
     * "from": 1, //当前页开始的记录
     * "last_page": 19, //总页数
     * "path": "http://manger.test/api/admin",
     * "per_page": 15,
     * "to": 15, //当前页结束的记录
     * "total": 271  // 总条数
     * }
     * }
     *
     */
    public function activityList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $iEventObject = isset(request()->event_object) ? request()->event_object : '';
        $sEventName = isset(request()->event_name) ? request()->event_name : '';
        $oAuthAdminList = DB::table('eventNew');

//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
        if ($iEventObject != '') {
            $oAuthAdminList->where('event_object', $iEventObject);
        }
        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }
        if ($sEventName !== '') {
            $oAuthAdminList->where('event_name', 'like', '%' . $sEventName . '%');
        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();


        $oAuthAdminList = $oAuthAdminList->where('event_id', '=','');


        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//            $oAuthAdmin->avatar = PublicFileUtils::createUploadUrl($oAuthAdmin->avatar);
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['event_id'] = $oAuthAdmin->event_id;
            $aTmp['event_name'] = $oAuthAdmin->event_name;
            $aTmp['begin_date'] = $oAuthAdmin->begin_date;
            $aTmp['end_date'] = $oAuthAdmin->end_date;
            $aTmp['event_object'] = $oAuthAdmin->event_object;
            $aReceiveTypeList = explode(",", $oAuthAdmin->receive_type);
            $aTmp['receive_type'] = $aReceiveTypeList;
            $aTmp['event_desc'] = $oAuthAdmin->event_desc;
            $aTmp['pic1'] = Event::getFileDomain($oAuthAdmin->pic1);
            $aTmp['pic2'] = Event::getFileDomain($oAuthAdmin->pic2);
            $aTmp['pic3'] = Event::getFileDomain($oAuthAdmin->pic3);
            $aTmp['pic4'] = Event::getFileDomain($oAuthAdmin->pic4);
            $aTmp['pic5'] = Event::getFileDomain($oAuthAdmin->pic5);
            $aTmp['pic6'] = Event::getFileDomain($oAuthAdmin->pic6);
            $aTerminalDisplayList = explode(",", $oAuthAdmin->terminal_display);
            $aTmp['terminal_display'] = $aTerminalDisplayList;
            $aSendTypeList = explode(",", $oAuthAdmin->send_type);
            $aTmp['send_type'] = $aSendTypeList;

            $aAuditModeList = explode(",", $oAuthAdmin->audit_mode);
            $aTmp['audit_mode'] = $aAuditModeList;
            $aTmp['frequency'] = $oAuthAdmin->frequency;
            $aTmp['times'] = $oAuthAdmin->times;

            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['benefit'] = $oAuthAdmin->benefit;
            $aTmp['benefit_ratio'] = $oAuthAdmin->benefit_ratio;
            $aTmp['benefit_min'] = $oAuthAdmin->benefit_min;
            $aTmp['benefit_max'] = $oAuthAdmin->benefit_max;

            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['deposit_request'] = $oAuthAdmin->deposit_request;
            $aTmp['range_begin'] = $oAuthAdmin->range_begin;
            $aTmp['range_end'] = $oAuthAdmin->range_end;
            $aTmpTmp = [];
            $aPlatfromWhiteList = explode(",", $oAuthAdmin->platform_whitelist);
            $aTmp['platform_whitelist'] = $aPlatfromWhiteList;
            $aPlatfromblackList = explode(",", $oAuthAdmin->platform_blacklist);
            $aTmp['platform_blacklist'] = $aPlatfromblackList;
            $aGamewhiteList = explode(",", $oAuthAdmin->game_whitelist);
            $aTmp['game_whitelist'] = $aGamewhiteList;
            $aGameblackList = explode(",", $oAuthAdmin->game_blacklist);
            $aTmp['game_blacklist'] = $aGameblackList;
            $aPayAccountList = explode(",", $oAuthAdmin->pay_account);
            $aTmp['pay_account'] = $aPayAccountList;
            $aTmp['rakeback'] = $oAuthAdmin->rakeback;
            $aTmp['rescue_gold'] = $oAuthAdmin->rescue_gold;
            $aTmp['status'] = $oAuthAdmin->status;


            $bFlag = $oAuthAdmin->bind_bankcard_flag == 1 ? true : false;
            $aTmp['bind_bankcard_flag'] = $bFlag;
            $aTmp['bind_bankcard_benefit'] = $oAuthAdmin->bind_bankcard_benefit;
            $bFlag = $oAuthAdmin->perfect_username_flag == 1 ? true : false;
            $aTmp['perfect_username_flag'] = $bFlag;
            $aTmp['perfect_username_benefit'] = $oAuthAdmin->perfect_username_benefit;
            $bFlag = $oAuthAdmin->verify_email_flag == 1 ? true : false;
            $aTmp['verify_email_flag'] = $bFlag;
            $aTmp['verify_email_benefit'] = $oAuthAdmin->verify_email_benefit;
            $bFlag = $oAuthAdmin->verify_phone_flag == 1 ? true : false;
            $aTmp['verify_phone_flag'] = $bFlag;
            $aTmp['verify_phone_benefit'] = $oAuthAdmin->verify_phone_benefit;


            $aTmp['history_deposit'] = $oAuthAdmin->history_deposit;
            $aTmp['history_deposit_begin'] = $oAuthAdmin->history_deposit_begin;
            $aTmp['history_deposit_end'] = $oAuthAdmin->history_deposit_end;

            $aTmp['withdraw_min'] = $oAuthAdmin->withdraw_min;
            $aTmp['withdraw_max'] = $oAuthAdmin->withdraw_max;


            $aTmp['user_ids'] = $oAuthAdmin->user_ids;

            $aUserLayers = explode(",", $oAuthAdmin->user_layers);
            $aTmp['user_layers'] = $aUserLayers;
            $aTmp['register_domain'] = $oAuthAdmin->register_domain;
            $aTmp['register_domain_begin'] = $oAuthAdmin->register_domain_begin;
            $aTmp['register_domain_end'] = $oAuthAdmin->register_domain_end;
            $aTmp['plus_profit'] = $oAuthAdmin->plus_profit;
            $aTmp['minus_profit'] = $oAuthAdmin->minus_profit;


            $aTmp['creator'] = $oAuthAdmin->creator;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updator'] = $oAuthAdmin->updator;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = 12;
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    public function activitySubList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';
        $iTaskId = isset(request()->taskId) ? request()->taskId : '';

        $oAuthAdminList = DB::table('auth_admins');

//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();


        $oAuthAdminFinalList = DB::table('eventNew')->where('event_id', $iTaskId)->get();


//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//            $oAuthAdmin->avatar = PublicFileUtils::createUploadUrl($oAuthAdmin->avatar);
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['event_id'] = $oAuthAdmin->event_id;
            $aTmp['event_name'] = $oAuthAdmin->event_name;
            $aTmp['begin_date'] = $oAuthAdmin->begin_date;
            $aTmp['end_date'] = $oAuthAdmin->end_date;
            $aTmp['event_object'] = $oAuthAdmin->event_object;
            $aReceiveTypeList = explode(",", $oAuthAdmin->receive_type);
            $aTmp['receive_type'] = $aReceiveTypeList;
            $aTmp['event_desc'] = $oAuthAdmin->event_desc;
            $aTmp['pic1'] = Event::getFileDomain($oAuthAdmin->pic1);
            $aTmp['pic2'] = Event::getFileDomain($oAuthAdmin->pic2);
            $aTmp['pic3'] = Event::getFileDomain($oAuthAdmin->pic3);
            $aTmp['pic4'] = Event::getFileDomain($oAuthAdmin->pic4);
            $aTmp['pic5'] = Event::getFileDomain($oAuthAdmin->pic5);
            $aTmp['pic6'] = Event::getFileDomain($oAuthAdmin->pic6);
            $aTerminalDisplayList = explode(",", $oAuthAdmin->terminal_display);
            $aTmp['terminal_display'] = $aTerminalDisplayList;
            $aSendTypeList = explode(",", $oAuthAdmin->send_type);
            $aTmp['send_type'] = $aSendTypeList;

            $aAuditModeList = explode(",", $oAuthAdmin->audit_mode);
            $aTmp['audit_mode'] = $aAuditModeList;
            $aTmp['frequency'] = $oAuthAdmin->frequency;
            $aTmp['times'] = $oAuthAdmin->times;

            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['benefit'] = $oAuthAdmin->benefit;
            $aTmp['benefit_ratio'] = $oAuthAdmin->benefit_ratio;
            $aTmp['benefit_min'] = $oAuthAdmin->benefit_min;
            $aTmp['benefit_max'] = $oAuthAdmin->benefit_max;

            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['deposit_request'] = $oAuthAdmin->deposit_request;
            $aTmp['range_begin'] = $oAuthAdmin->range_begin;
            $aTmp['range_end'] = $oAuthAdmin->range_end;
            $aTmpTmp = [];
            $aPlatfromWhiteList = explode(",", $oAuthAdmin->platform_whitelist);
            $aTmp['platform_whitelist'] = $aPlatfromWhiteList;
            $aPlatfromblackList = explode(",", $oAuthAdmin->platform_blacklist);
            $aTmp['platform_blacklist'] = $aPlatfromblackList;
            $aGamewhiteList = explode(",", $oAuthAdmin->game_whitelist);
            $aTmp['game_whitelist'] = $aGamewhiteList;
            $aGameblackList = explode(",", $oAuthAdmin->game_blacklist);
            $aTmp['game_blacklist'] = $aGameblackList;
            $aPayAccountList = explode(",", $oAuthAdmin->pay_account);
            $aTmp['pay_account'] = $aPayAccountList;
            $aTmp['rakeback'] = $oAuthAdmin->rakeback;
            $aTmp['rescue_gold'] = $oAuthAdmin->rescue_gold;
            $aTmp['status'] = $oAuthAdmin->status;


            $bFlag = $oAuthAdmin->bind_bankcard_flag == 1 ? true : false;
            $aTmp['bind_bankcard_flag'] = $bFlag;
            $aTmp['bind_bankcard_benefit'] = $oAuthAdmin->bind_bankcard_benefit;
            $bFlag = $oAuthAdmin->perfect_username_flag == 1 ? true : false;
            $aTmp['perfect_username_flag'] = $bFlag;
            $aTmp['perfect_username_benefit'] = $oAuthAdmin->perfect_username_benefit;
            $bFlag = $oAuthAdmin->verify_email_flag == 1 ? true : false;
            $aTmp['verify_email_flag'] = $bFlag;
            $aTmp['verify_email_benefit'] = $oAuthAdmin->verify_email_benefit;
            $bFlag = $oAuthAdmin->verify_phone_flag == 1 ? true : false;
            $aTmp['verify_phone_flag'] = $bFlag;
            $aTmp['verify_phone_benefit'] = $oAuthAdmin->verify_phone_benefit;


            $aTmp['history_deposit'] = $oAuthAdmin->history_deposit;
            $aTmp['history_deposit_begin'] = $oAuthAdmin->history_deposit_begin;
            $aTmp['history_deposit_end'] = $oAuthAdmin->history_deposit_end;

            $aTmp['withdraw_min'] = $oAuthAdmin->withdraw_min;
            $aTmp['withdraw_max'] = $oAuthAdmin->withdraw_max;


            $aTmp['user_ids'] = $oAuthAdmin->user_ids;

            $aUserLayers = explode(",", $oAuthAdmin->user_layers);
            $aTmp['user_layers'] = $aUserLayers;
            $aTmp['register_domain'] = $oAuthAdmin->register_domain;
            $aTmp['register_domain_begin'] = $oAuthAdmin->register_domain_begin;
            $aTmp['register_domain_end'] = $oAuthAdmin->register_domain_end;

            $aTmp['plus_profit'] = $oAuthAdmin->plus_profit;
            $aTmp['minus_profit'] = $oAuthAdmin->minus_profit;


            $aTmp['creator'] = $oAuthAdmin->creator;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updator'] = $oAuthAdmin->updator;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = 12;
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    public function eventUserPrizeList()
    {



//        username: 12121212
//status: 1
//page: 1
//limit: 20
//role_id:
//sort: +id
//beginDate: 2019-03-20T16:00:00.000Z
//endDate: 2019-03-28T16:00:00.000Z
//event_object: 9
//event_name: 12121
//eventUserPrizeList?username=12121212&status=1&page…-28T16:00:00.000Z&event_object=9&event_name=12121
//apidemo.test/api/event
//eventUserPrizeList?username=12121212&status=1&page…-28T16:00:00.000Z&event_object=9&event_name=12121
//apidemo.test/api/event



//        $iStatus = isset(request()->status) ? request()->status : '';
//
//        $oAuthAdminList = DB::table('eventNew');



//        $sWhere = [];
//        $sOrder = 'id DESC';
//        $iLimit = isset(request()->limit) ? request()->limit : '';
//        $iPage = isset(request()->page) ? request()->page : '';
//        // +id -id
//        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';
        $iEventObject = isset(request()->event_object) ? request()->event_object : '';
        $sEventName = isset(request()->event_name) ? request()->event_name : '';

        $sBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $sEndDate = isset(request()->endDate) ? request()->endDate : '';


        $oAuthAdminList = DB::table('event_user_prize');

//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }

        if ($iEventObject != '') {
            $oAuthAdminList->where('event_object', $iEventObject);
        }
        if ($sEventName !== '') {
            $oAuthAdminList->where('event_name', 'like', '%' . $sEventName . '%');
        }

        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }
        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }


        if ($sBeginDate !== '') {
            $oAuthAdminList->where("request_date", ">=", $sBeginDate);
        }

        if ($sEndDate !== '') {
            $oAuthAdminList->where("request_date", "<", $sEndDate);
        }


//        ->where("created_at", ">=", $dDateStart)
//        ->where("created_at", "<", $dDateEnd)
//
//        static::whereBetween('bought_at',[ $dBeginTime,$dEndTime ])

//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();


//        $oAuthAdminList = DB::table('event_user_prize');


//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
//        Log::info('huangqiu');
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['event_object'] = $oAuthAdmin->event_object;
            $aTmp['event_name'] = $oAuthAdmin->event_name;
            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['benefit'] = $oAuthAdmin->benefit;
            $aTmp['auditor'] = $oAuthAdmin->auditor;
            $aTmp['audit_date'] = $oAuthAdmin->audit_date;
            $aTmp['request_date'] = $oAuthAdmin->request_date;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = 12;
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    public function eventProcessList()
    {
//        $sWhere = [];
//        $sOrder = 'id DESC';
//        $iLimit = isset(request()->limit) ? request()->limit : '';
//        $iPage = isset(request()->page) ? request()->page : '';
//        // +id -id
//        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
//        $iStatus = isset(request()->status) ? request()->status : '';
//        $sUserName = isset(request()->username) ? request()->username : '';
//        $oAuthAdminList = DB::table('auth_admins');

//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();


        $oAuthAdminList = DB::table('event_user_prize');


//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        Log::info('huangqiu');


        $aTmp['bodyData1'] = '活动组';
        $aTmp['bodyData2'] = '22%';
        $aTmp['bodyData3'] = ["user0001"];
        $aTmp['bodyData4'] = ["随机红包", "存送100%", "常态存款"];
        $aTmp['bodyData5'] = ["0", "100", "100"];
        $aTmp['bodyData6'] = ["20", "100", "0"];
        $aTmp['bodyData7'] = ["20%", "11%", "2%"];
        $aTmp['bodyData8'] = ["2018-05-12 11:11:11", "2018-05-12 11:11:11", "2018-05-12 11:11:11"];
        $aTmp['bodyData9'] = ["进行中", "进行中", "进行中"];
        $aFinal[] = $aTmp;

        $bodyData = '[
                        {
                            city: "活动组#A0001",city1: "22%",food: ["user0001"],fun: ["随机红包", "存送100%", "常态存款"], fun1: ["0", "100", "100"], fun2: ["20", "100", "0"], fun3: ["20%", "11%", "2%"], fun4: ["2018-05-12 11:11:11", "2018-05-12 11:11:11", "2018-05-12 11:11:11"], fun5: ["进行中", "进行中", "进行中"]
                        },
                        {
                            city: "活动组#A0002",city1: "33%",food: ["user0002"],fun: ["随机红包", "存送100%", "常态存款"], fun1: ["10", "33", "44"], fun2: ["55", "66", "77"], fun3: ["11%", "22%", "3%"], fun4: ["2012-1-12 11:11:11", "2013-05-12 11:11:11", "2014-05-12 11:11:11"], fun5: ["进行中", "进行中", "进行中"]
                        },
                        {
                            city: "活动组#A0003",city1: "33%",food: ["user0003"],fun: ["随机红包", "存送100%", "常态存款"], fun1: ["11", "34", "44"], fun2: ["55", "66", "77"], fun3: ["22%", "33%", "44%"], fun4: ["2012-2-12 11:11:11", "2013-05-12 11:11:11", "2014-05-12 11:11:11"], fun5: ["进行中", "进行中", "进行中"]
                        },
                        {
                            city: "活动组#A0004",city1: "33%",food: ["user0004"],fun: ["随机红包", "存送100%", "常态存款"], fun1: ["13", "35", "44"], fun2: ["55", "66", "77"], fun3: ["33%", "44%", "55%"], fun4: ["2012-3-12 11:11:11", "2013-05-12 11:11:11", "2014-05-12 11:11:11"], fun5: ["进行中", "进行中", "进行中"]
                        },
                        {
                            city: "活动组#A0005",city1: "33%",food: ["user0005"],fun: ["随机红包", "存送100%", "常态存款"], fun1: ["14", "13", "44"], fun2: ["55", "66", "77"], fun3: ["44%", "55%", "66%"], fun4: ["2012-4-12 11:11:11", "2013-05-12 11:11:11", "2014-05-12 11:11:11"], fun5: ["进行中", "进行中", "进行中"]
                        },
                        {
                            city: "活动组#A0006",city1: "33%",food: ["user0006"],fun: ["随机红包", "存送100%", "常态存款"], fun1: ["15", "14", "44"], fun2: ["55", "66", "77"], fun3: ["55%", "66%", "77%"], fun4: ["2012-5-12 11:11:11", "2013-05-12 11:11:11", "2014-05-12 11:11:11"], fun5: ["进行中", "进行中", "进行中"]
                        },
                        {
                            city: "活动组#A0007",city1: "33%",food: ["user0007"],fun: ["随机红包", "存送100%", "常态存款"], fun1: ["16", "15", "44"], fun2: ["55", "66", "77"], fun3: ["66%", "77%", "88%"], fun4: ["2012-6-12 11:11:11", "2013-05-12 11:11:11", "2014-05-12 11:11:11"], fun5: ["进行中", "进行中", "进行中"]
                        },
                        {
                            city: "活动组#A0008",city1: "33%",food: ["user0008"],fun: ["随机红包", "存送100%", "常态存款"], fun1: ["17", "16", "44"], fun2: ["55", "66", "77"], fun3: ["77%", "88%", "99%"], fun4: ["2012-7-12 11:11:11", "2013-05-12 11:11:11", "2014-05-12 11:11:11"], fun5: ["进行中", "进行中", "进行中"]
                        }

                    ]';


        /*foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['event_model'] = $oAuthAdmin->event_model;
            $aTmp['event_name'] = $oAuthAdmin->event_name;
            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['benefit'] = $oAuthAdmin->benefit;
            $aTmp['auditor'] = $oAuthAdmin->auditor;
            $aTmp['audit_date'] = $oAuthAdmin->audit_date;
            $aTmp['request_date'] = $oAuthAdmin->request_date;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = 12;
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    public function eventSave()
    {


        /*  print_r('ddddddddddddddd');
          die;*/
        $data = request()->post();
        /* if (empty($data['username']) || empty($data['password'])) {
             return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
         }
         $username = $data['username'];*/
        // 模型
//        $info = AuthAdmin::where('username',$username)
//            ->field('username')
//            ->find();

//        $oAuthAdmin = AuthAdmin::where('username', $username)
//            ->first();

//        if ($oAuthAdmin){
//            return ResultVo::error(ErrorCode::DATA_REPEAT);
//        }

        $audit_mode = isset($data['audit_mode']) ? $data['audit_mode'] : '';
        $begin_date = isset($data['begin_date']) ? $data['begin_date'] : date('Y-m-d');
        $benefit = isset($data['benefit']) ? $data['benefit'] : '';
        $benefit_max = isset($data['benefit_max']) ? $data['benefit_max'] : '';
        $benefit_min = isset($data['benefit_min']) ? $data['benefit_min'] : '';
        $benefit_ratio = isset($data['benefit_ratio']) ? $data['benefit_ratio'] : '';
        $bind_bankcard_benefit = isset($data['bind_bankcard_benefit']) ? $data['bind_bankcard_benefit'] : '';
        $bind_bankcard_flag = isset($data['bind_bankcard_flag']) ? $data['bind_bankcard_flag'] : '';
        $created_at = isset($data['created_at']) ? $data['created_at'] : date('Y-m-d');
        $creator = isset($data['creator']) ? $data['creator'] : '';
        $deposit = isset($data['deposit']) ? $data['deposit'] : '';
        $deposit_request = isset($data['deposit_request']) ? $data['deposit_request'] : '';
        $end_date = isset($data['end_date']) ? $data['end_date'] : date('Y-m-d');
        $event_desc = isset($data['event_desc']) ? $data['event_desc'] : '';
        $event_id = isset($data['event_id']) ? $data['event_id'] : '';
        $event_name = isset($data['event_name']) ? $data['event_name'] : '';
        $event_object = isset($data['event_object']) ? $data['event_object'] : '';
        $frequency = isset($data['frequency']) ? $data['frequency'] : '';
        $game_blacklist = isset($data['game_blacklist']) ? $data['game_blacklist'] : '';
        $game_whitelist = isset($data['game_whitelist']) ? $data['game_whitelist'] : '';
        $history_deposit = isset($data['history_deposit']) ? $data['history_deposit'] : '';
        $history_deposit_begin = isset($data['history_deposit_begin']) ? $data['history_deposit_begin'] : date('Y-m-d');
        $history_deposit_end = isset($data['history_deposit_end']) ? $data['history_deposit_end'] : date('Y-m-d');
        $id = isset($data['id']) ? $data['id'] : '';
        $addSubFlage = isset($data['addSubFlage']) ? $data['addSubFlage'] : '';

        $merchant_name = isset($data['merchant_name']) ? $data['merchant_name'] : '';
        $pay_account = isset($data['pay_account']) ? $data['pay_account'] : '';
        $perfect_username_benefit = isset($data['perfect_username_benefit']) ? $data['perfect_username_benefit'] : '';
        $perfect_username_flag = isset($data['perfect_username_flag']) ? $data['perfect_username_flag'] : '';
        $pic1 = isset($data['pic1']) ? $data['pic1'] : '';
        $pic2 = isset($data['pic2']) ? $data['pic2'] : '';
        $pic3 = isset($data['pic3']) ? $data['pic3'] : '';
        $pic4 = isset($data['pic4']) ? $data['pic4'] : '';
        $pic5 = isset($data['pic5']) ? $data['pic5'] : '';
        $pic6 = isset($data['pic6']) ? $data['pic6'] : '';
        $platform_blacklist = isset($data['platform_blacklist']) ? $data['platform_blacklist'] : '';
        $platform_whitelist = isset($data['platform_whitelist']) ? $data['platform_whitelist'] : '';
        $rakeback = isset($data['rakeback']) ? $data['rakeback'] : '';
        $range_begin = isset($data['range_begin']) ? $data['range_begin'] : date('Y-m-d');
        $range_end = isset($data['range_end']) ? $data['range_end'] : date('Y-m-d');
        $receive_type = isset($data['receive_type']) ? $data['receive_type'] : '';
        $register_domain = isset($data['register_domain']) ? $data['register_domain'] : '';
        $register_domain_begin = isset($data['register_domain_begin']) ? $data['register_domain_begin'] : date('Y-m-d');
        $register_domain_end = isset($data['register_domain_end']) ? $data['register_domain_end'] : date('Y-m-d');
        $rescue_gold = isset($data['rescue_gold']) ? $data['rescue_gold'] : '';
        $send_type = isset($data['send_type']) ? $data['send_type'] : '';
        $status = isset($data['status']) ? $data['status'] : '';
        $terminal_display = isset($data['terminal_display']) ? $data['terminal_display'] : '';
        $times = isset($data['times']) ? $data['times'] : '';
        $turnover = isset($data['turnover']) ? $data['turnover'] : '';
        $updated_at = isset($data['updated_at']) ? $data['updated_at'] : '';
        $updator = isset($data['updator']) ? $data['updator'] : '';
        $user_ids = isset($data['user_ids']) ? $data['user_ids'] : '';
        $user_layers = isset($data['user_layers']) ? $data['user_layers'] : '';
        $verify_email_benefit = isset($data['verify_email_benefit']) ? $data['verify_email_benefit'] : '';
        $verify_email_flag = isset($data['verify_email_flag']) ? $data['verify_email_flag'] : '';
        $verify_phone_benefit = isset($data['verify_phone_benefit']) ? $data['verify_phone_benefit'] : '';
        $verify_phone_flag = isset($data['verify_phone_flag']) ? $data['verify_phone_flag'] : '';
        $withdraw_max = isset($data['withdraw_max']) ? $data['withdraw_max'] : '';
        $withdraw_min = isset($data['withdraw_min']) ? $data['withdraw_min'] : '';

        $register_domain = isset($data['register_domain']) ? $data['register_domain'] : '';
        $register_domain_begin = isset($data['register_domain_begin']) ? $data['register_domain_begin'] : date('Y-m-d');
        $register_domain_end = isset($data['register_domain_end']) ? $data['register_domain_end'] : date('Y-m-d');
        $user_layers = isset($data['user_layers']) ? $data['user_layers'] : '';
        $plus_profit = isset($data['plus_profit']) ? $data['plus_profit'] : '';
        $minus_profit = isset($data['minus_profit']) ? $data['minus_profit'] : '';


        $bFlag = false;
        if ($id != '') {
            if ($event_id != '') {
                $bFlag = true;
            } else {
                // 有下级活动
                if ($addSubFlage == 1) {
                    $event_id = $id;
//                $bFlag = true;
                } else {
                    $bFlag = true;
                }
            }
        }

        if ($bFlag) {
            $auth_role_admin = Event::find($id);
        } else{
            $auth_role_admin = new Event();
        }



//        Log::info('$bFlag====================='.$bFlag);
//        die;


       /* if ($id != '' && $event_id != '') {
            $auth_role_admin = Event::find($id);
        }

        die;*/

//        Log::info();
        // tmp
//        $auth_role_admin = new Event();

//        $auth_role_admin = new Event();
//        $auth_role_admin->id =$id;
        $auth_role_admin->merchant_name = $merchant_name;
        $auth_role_admin->event_id = $event_id;
        $auth_role_admin->event_name = $event_name;
        $auth_role_admin->begin_date = $begin_date;
        $auth_role_admin->end_date = $end_date;
        $auth_role_admin->event_object = $event_object;
        $auth_role_admin->receive_type = Event::arrTostr($receive_type);
        $auth_role_admin->event_desc = $event_desc;
        $auth_role_admin->pic1 = $pic1;
        $auth_role_admin->pic2 = $pic2;
        $auth_role_admin->pic3 = $pic3;
        $auth_role_admin->pic4 = $pic4;
        $auth_role_admin->pic5 = $pic5;
        $auth_role_admin->pic6 = $pic6;
        $auth_role_admin->terminal_display = Event::arrTostr($terminal_display);
        $auth_role_admin->send_type = Event::arrTostr($send_type);
        $auth_role_admin->audit_mode = Event::arrTostr($audit_mode);
        $auth_role_admin->frequency = $frequency;
        $auth_role_admin->times = $times;
        $auth_role_admin->deposit = $deposit;
        $auth_role_admin->benefit_ratio = $benefit_ratio;
        $auth_role_admin->benefit = $benefit;
        $auth_role_admin->benefit_min = $benefit_min;
        $auth_role_admin->benefit_max = $benefit_max;
        $auth_role_admin->turnover = $turnover;
        $auth_role_admin->deposit_request = $deposit_request;
        $auth_role_admin->range_begin = $range_begin;
        $auth_role_admin->range_end = $range_end;
        $auth_role_admin->platform_whitelist = Event::arrTostr($platform_whitelist);
        $auth_role_admin->platform_blacklist = Event::arrTostr($platform_blacklist);
        $auth_role_admin->game_whitelist = Event::arrTostr($game_whitelist);
        $auth_role_admin->game_blacklist = Event::arrTostr($game_blacklist);
        $auth_role_admin->pay_account = Event::arrTostr($pay_account);
        $auth_role_admin->rakeback = $rakeback;
        $auth_role_admin->rescue_gold = $rescue_gold;
        $auth_role_admin->status = $status;
        $auth_role_admin->bind_bankcard_flag = $bind_bankcard_flag;
        $auth_role_admin->bind_bankcard_benefit = $bind_bankcard_benefit;
        $auth_role_admin->creator = $creator;
        $auth_role_admin->created_at = $created_at;
        $auth_role_admin->updator = $updator;
        $auth_role_admin->updated_at = "2001-03-14 06:21:08";
        $auth_role_admin->perfect_username_flag = $perfect_username_flag;
        $auth_role_admin->perfect_username_benefit = $perfect_username_benefit;
        $auth_role_admin->verify_email_flag = $verify_email_flag;
        $auth_role_admin->verify_email_benefit = $verify_email_benefit;
        $auth_role_admin->verify_phone_flag = $verify_phone_flag;
        $auth_role_admin->verify_phone_benefit = $verify_phone_benefit;
        $auth_role_admin->history_deposit = $history_deposit;
        $auth_role_admin->history_deposit_begin = $history_deposit_begin;
        $auth_role_admin->history_deposit_end = $history_deposit_end;
        $auth_role_admin->withdraw_min = $withdraw_min;
        $auth_role_admin->withdraw_max = $withdraw_max;


        $fFileFlag = strstr($user_ids, 'public');
        if ($fFileFlag) {
            $auth_role_admin->user_ids = Event::getFromTxt($user_ids);
        }

        $auth_role_admin->user_layers = Event::arrTostr($user_layers);
        $auth_role_admin->register_domain = $register_domain;
        $auth_role_admin->register_domain_begin = $register_domain_begin;
        $auth_role_admin->register_domain_end = $register_domain_end;


        $auth_role_admin->plus_profit = $plus_profit;
        $auth_role_admin->minus_profit = $minus_profit;


        $iRet = $auth_role_admin->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $auth_role_admin;

        return response()->json($aFinal);
        return ResultVo::success($auth_admin);
    }


    public function fileSave()
    {


       /* $ab = '/home/ok/api/storage/app/public/2019-03-15-06-10-53.jpg';

//        $domain = 'http://apidemo.test/'. strstr($ab,'public');

        print_r(Event::getFileDomain($ab));

//        print_r(Event::getFromTxt('/home/ok/api/storage/app/public/123.txt'));
//
//
        die;*/
//        $aInputs = Input::all();
//        $data = request()->post();
        Log::info(request()->all());
        $input = request()->all();  #获取所有参数
        Log::info($input['file']);

        $fileCharater = $input['file'];

        if ($fileCharater->isValid()) { //括号里面的是必须加的哦
            //如果括号里面的不加上的话，下面的方法也无法调用的

            //获取文件的扩展名
            $ext = $fileCharater->getClientOriginalExtension();

            //获取文件的绝对路径
            $path = $fileCharater->getRealPath();

            //定义文件名
            $filename = date('Y-m-d-h-i-s') . '.' . $ext;

            //存储文件。disk里面的public。总的来说，就是调用disk模块里的public配置
            Log::info($filename);
            Log::info($path);
            // 路径保存地址：/home/ok/api/storage/app/public
            \Storage::disk('public')->put($filename, file_get_contents($path));
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = '/home/ok/api/storage/app/public/' . $filename;
            return response()->json($aFinal);

        }
    }


    public function eventStatusSave($id = null)
    {

        $data = request()->post();

//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);


        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/

        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = Event::find($id);
//        $iFlag = 0;
        if (is_object($oEvent)) {
            $iStatue = $oEvent->status;
        }
//        $iFlag = $iStatue == 0 ? 1 : 0;
        $oEvent->status = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;

        return response()->json($aFinal);
    }


    public function eventUserPrizeStatusSave($id = null)
    {
        $data = request()->post();

        $sId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);


        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }

//        $iFlag = 0;
//        if (is_object($oEvent)) {
//            $iStatue = $oEvent->status;
//        }
//        $iFlag = $iStatue == 0 ? 1 : 0;
//        $oEvent->status = $iFlag;
//        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $bSucc;
//        $aFinal['data'] = $oEvent;

        return response()->json($aFinal);
    }

    /**
     * @api {get} /api/adminRoleList 取得角色列表
     * @apiGroup admin
     * @apiParam {string} null 不需要参数
     * @apiParamExample {json} 请求的参数例子:
     *     {
     *       null: 'null',
     *     }
     *
     * @apiSuccessExample 取得角色列表成功
     * HTTP/1.1 201 OK
     * {
     * "status": "success",
     * "status_code": 201
     * }
     * @apiErrorExample 数据验证出错
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
     * }
     */
    public function adminRoleList()
    {
        $sWhere = [];
        $limit = request()->get('limit/d', 20);
        //分页配置
//        $paginate = [
//            'type' => 'bootstrap',
//            'var_page' => 'page',
//            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
//        ];
        $iTmp = ($limit <= 0 || $limit > 20) ? 20 : $limit;
        $lists = AuthRole::where($sWhere)
            ->paginate($iTmp);

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();
        return response()->json($res);
        return ResultVo::success($res);
    }


    /**
     * @api {post} /api/adminSave  建立新的商户
     * @apiGroup admin
     * @apiParam {string} name 用户昵称
     * @apiParam {string} email 用户登陆名　email格式 必须唯一
     * @apiParam {string} password 用户登陆密码
     * @apiParam {string="admin","editor"} [role="editor"] 角色 内容为空或者其他的都设置为editor
     * @apiParam {string} [avatar] 用户头像地址
     * @apiParamExample {json} 请求的参数例子:
     *     {
     *       name: 'test',
     *       email: '1111@qq.com',
     *       password: '123456',
     *       role: 'editor',
     *       avatar: 'uploads/20178989.png'
     *     }
     *
     * @apiSuccessExample 新建用户成功
     * HTTP/1.1 201 OK
     * {
     * "status": "success",
     * "status_code": 201
     * }
     * @apiErrorExample 数据验证出错
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
     * }
     */
    public function adminSave()
    {
        $data = request()->post();
        if (empty($data['username']) || empty($data['password'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $username = $data['username'];
        // 模型
//        $info = AuthAdmin::where('username',$username)
//            ->field('username')
//            ->find();

        $oAuthAdmin = AuthAdmin::where('username', $username)
            ->first();

//        if ($oAuthAdmin){
//            return ResultVo::error(ErrorCode::DATA_REPEAT);
//        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_admin = new AuthAdmin();
        $auth_admin->username = $username;
        $auth_admin->password = PassWordUtils::create($data['password']);
        $auth_admin->status = $status;
        $auth_admin->create_time = date("Y-m-d H:i:s");
        $result = $auth_admin->save();

        if (!$result) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];

        //$adminInfo = $this->adminInfo; // 登录用户信息
        $admin_id = $auth_admin->id;
        if ($roles) {
            $temp = [];
            foreach ($roles as $key => $value) {
                $temp[$key]['role_id'] = $value;
                $temp[$key]['admin_id'] = $admin_id;
            }
            //添加用户的角色

            if (count($temp) > 0) {
                foreach ($temp as $k => $v) {
                    $auth_role_admin = new AuthRoleAdmin();
                    $auth_role_admin->role_id = $v['role_id'];
                    $auth_role_admin->admin_id = $v['admin_id'];
                    $iRet = $auth_role_admin->save();
                }
            }
//            $auth_role_admin->saveAll($temp);
        }

        $auth_admin['password'] = '';
        $auth_admin['roles'] = $roles;

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $auth_admin;

        return response()->json($aFinal);
        return ResultVo::success($auth_admin);
    }

    /**
     * @api {post} /api/adminEdit  編輯管理員信息
     * @apiGroup admin
     * @apiParam {string} name 用户昵称
     * @apiParam {string} email 用户登陆名　email格式 必须唯一
     * @apiParam {string} password 用户登陆密码
     * @apiParam {string="admin","editor"} [role="editor"] 角色 内容为空或者其他的都设置为editor
     * @apiParam {string} [avatar] 用户头像地址
     * @apiParamExample {json} 请求的参数例子:
     *     {
     *       name: 'test',
     *       email: '1111@qq.com',
     *       password: '123456',
     *       role: 'editor',
     *       avatar: 'uploads/20178989.png'
     *     }
     *
     * @apiSuccessExample 新建用户成功
     * HTTP/1.1 201 OK
     * {
     * "status": "success",
     * "status_code": 201
     * }
     * @apiErrorExample 数据验证出错
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
     * }
     */
    public function adminEdit()
    {
        $data = request()->post();


//        Log::info($data);
        $aRoles = $data['roles'];

        if (empty($data['id']) || empty($data['username'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $id = $data['id'];
        $username = strip_tags($data['username']);
        // 模型
//        $auth_admin = AuthAdmin::where('id',$id)
//            ->field('id,username')
//            ->find();
        $oAuthAdmin = AuthAdmin::where('id', $id)
            ->first();

        if (!$oAuthAdmin) {
            return ResultVo::error(ErrorCode::DATA_NOT, "商户不存在");
        }
        $login_info = $oAuthAdmin;
        $login_user_name = isset($login_info['username']) ? $login_info['username'] : '';
        // 如果是超级商户，判断当前登录用户是否匹配
        if ($oAuthAdmin->username == 'admin' && $login_user_name != $oAuthAdmin->username) {
            return ResultVo::error(ErrorCode::DATA_NOT, "最高权限用户，无权修改");
        }

//        $info = AuthAdmin::where('username',$username)
//            ->field('id')
//            ->find();

        $info = AuthAdmin::where('username', $username)
            ->first();

        // 判断username 是否重名，剔除自己
//        if (!empty($info['id']) && $info['id'] != $id){
//            return ResultVo::error(ErrorCode::DATA_REPEAT, "商户已存在");
//        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $password = isset($data['password']) ? PassWordUtils::create($data['password']) : '';
        $oAuthAdmin->username = $username;
        if ($password) {
            $oAuthAdmin->password = $password;
        }
        $oAuthAdmin->status = $status;
//        $oAuthAdmin->role_id = implode(",", $aRoles);

        $result = $oAuthAdmin->save();

        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];
        if (!$result) {
            // 没有做任何更改
            $oAuthRoleAdmin = AuthRoleAdmin::where('admin_id', $id)->field('role_id')->select();
            if ($oAuthRoleAdmin) {
                $oAuthRoleAdmin = $oAuthRoleAdmin->toArray();
                $oAuthRoleAdmin = array_column($oAuthRoleAdmin, 'role_id');
            }
            // 没有差值，权限也没做更改
            if ($roles == $oAuthRoleAdmin) {
                return ResultVo::error(ErrorCode::DATA_CHANGE);
            }
        }


        if ($roles) {
            // 先删除
            AuthRoleAdmin::where('admin_id', $id)->delete();
            $temp = [];
            foreach ($roles as $key => $value) {
                $temp[$key]['role_id'] = $value;
                $temp[$key]['admin_id'] = $id;
            }


            //添加用户的角色
            $auth_role_admin = new AuthRoleAdmin();

            if (count($temp) > 0) {
                foreach ($temp as $k => $v) {
                    $oAuthPermission = new AuthRoleAdmin();
                    $oAuthPermission->role_id = $v['role_id'];
                    $oAuthPermission->admin_id = $v['admin_id'];
                    $result = $oAuthPermission->save();
                    if (!$result) {
                        return ResultVo::error(ErrorCode::NOT_NETWORK);
                    }
                }
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
            }

        }

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        return response()->json($aFinal);

        return ResultVo::success();
    }

    /**
     * @api {post} /api/adminDelete  删除商户
     * @apiGroup admin
     * @apiParam {string} id 编号
     * @apiParamExample {json} 请求的参数例子:
     *     {
     *       id: '111111',
     *     }
     *
     * @apiSuccessExample 新建用户成功
     * HTTP/1.1 201 OK
     * {
     * "status": "success",
     * "status_code": 201
     * }
     * @apiErrorExample 数据验证出错
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
     * }
     */
    public function adminDelete()
    {
//        $id = request()->post('id/d');
        $id = request()->all()['id'];
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$id)->field('username')->find();
        $oAuthAdmin = AuthAdmin::where('id', $id)->first();
        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        // 删除权限
        AuthRoleAdmin::where('admin_id', $id)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success();

    }
}