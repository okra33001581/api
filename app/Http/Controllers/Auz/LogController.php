<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\Ad;
use App\model\AdSite;
use App\model\AdminLog;

/**
 * Class Event - 日志相关控制器
 * @author zebra
 */
class LogController extends Controller
{

    public function logAdminlog()
    {

        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';


        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';

        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';

        $endDate = isset(request()->endDate) ? request()->endDate : '';

        $type = isset(request()->type) ? request()->type : '';

        $sub_account = isset(request()->sub_account) ? request()->sub_account : '';

        $ip = isset(request()->ip) ? request()->ip : '';

        $cookies = isset(request()->cookie_content) ? request()->cookie_content : '';

        $keywords = isset(request()->keywords) ? request()->keywords : '';

        $oAuthAdminList = DB::table('log_admin');

        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', $merchant_name);
        }
        if ($dtBeginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtBeginDate);
        }
        if ($endDate != '') {
            $oAuthAdminList->where('created_at', '<=', $endDate);
        }
        if ($type != '') {
            $oAuthAdminList->where('type', $type);
        }
        if ($sub_account != '') {
            $oAuthAdminList->where('sub_account', 'like', '%' . $sub_account . '%');
        }
        if ($ip != '') {
            $oAuthAdminList->where('ip', $ip);
        }
        if ($cookies != '') {
            $oAuthAdminList->where('cookies', $cookies);
        }
        if ($keywords != '') {
            $oAuthAdminList->where('log_content', $keywords);
        }


        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);
//
//
//        $aTmp = [];
//        $aFinal = [];
//        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//            $aTmp['id'] = $oAuthAdmin->id;
//            $aTmp['sub_account'] = $oAuthAdmin->sub_account;
//            $aTmp['operate_name'] = $oAuthAdmin->operate_name;
//            $aTmp['log_content'] = $oAuthAdmin->log_content;
//            $aTmp['ip'] = $oAuthAdmin->ip;
//            $aTmp['cookies'] = $oAuthAdmin->cookies;
//            $aTmp['date'] = $oAuthAdmin->date;
//            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
//            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
//
//            $aFinal[] = $aTmp;
//        }
        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();;
        $aFinal['message'] = 'success';

        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sub_account = '123';
        $operate_name = 'logAdminlog';
        $log_content = 'logAdminlog';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    public function logDomainlog()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $endDate = isset(request()->endDate) ? request()->endDate : '';

        $oAuthAdminList = DB::table('log_domain');


        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($dtBeginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtBeginDate);
        }


        if ($endDate != '') {
            $oAuthAdminList->where('created_at', '<=', $endDate);
        }

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

//        $aTmp = [];
//        $aFinal = [];
//        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//            $aTmp['id'] = $oAuthAdmin->id;
//            $aTmp['domain'] = $oAuthAdmin->domain;
//            $aTmp['total_visit_people_count'] = $oAuthAdmin->total_visit_people_count;
//            $aTmp['tatal_visit_count'] = $oAuthAdmin->tatal_visit_count;
//            $aTmp['created_at'] = $oAuthAdmin->created_at;
//            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
//            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
//
//            $aFinal[] = $aTmp;
//        }

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'logDomainlog';
        $log_content = 'logDomainlog';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function logLoginlog()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $endDate = isset(request()->endDate) ? request()->endDate : '';
        $type = isset(request()->type) ? request()->type : '';
        $sub_title = isset(request()->sub_title) ? request()->sub_title : '';
        $keywords = isset(request()->keywords) ? request()->keywords : '';
        $is_check = isset(request()->is_check) ? request()->is_check : '';

        $oAuthAdminList = DB::table('log_login');


        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($dtBeginDate != '') {
            $oAuthAdminList->where('login_date', '>=', $dtBeginDate);
        }


        if ($endDate != '') {
            $oAuthAdminList->where('login_date', '<=', $endDate);
        }

        if ($type != '') {
            $oAuthAdminList->where('type', '=', $type);
        }

        $bFlag = 0;
        if ($is_check) {
            $bFlag = 1;
        }

        $oAuthAdminList->where('is_check', '=', $bFlag);

        if ($sub_title == '用户名') {
            $oAuthAdminList->where('username', 'like', '%' . $keywords . '%');
        } elseif ($sub_title == 'IP地址') {
            $oAuthAdminList->where('ip_address', 'like', '%' . $keywords . '%');
        }

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

//        $aTmp = [];
//        $aFinal = [];
//        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//            $aTmp['id'] = $oAuthAdmin->id;
//            $aTmp['user_id'] = $oAuthAdmin->user_id;
//            $aTmp['username'] = $oAuthAdmin->username;
//            $aTmp['login_info'] = $oAuthAdmin->login_info;
//            $aTmp['ip_address'] = $oAuthAdmin->ip_address;
//            $aTmp['district'] = $oAuthAdmin->district;
//            $aTmp['request_url'] = $oAuthAdmin->request_url;
//            $aTmp['login_date'] = $oAuthAdmin->login_date;
//            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
//            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
//            $aTmp['is_check'] = $oAuthAdmin->is_check;
//
//            $aFinal[] = $aTmp;
//        }
        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'logLoginlog';
        $log_content = 'logLoginlog';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

}