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
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function logAdminlog()
    {

        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';

        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';

        $sType = isset(request()->type) ? request()->type : '';

        $sSubAccount = isset(request()->sub_account) ? request()->sub_account : '';

        $sIp = isset(request()->ip) ? request()->ip : '';

        $sCookies = isset(request()->cookie_content) ? request()->cookie_content : '';

        $sKeywords = isset(request()->keywords) ? request()->keywords : '';

        $oAuthAdminList = DB::table('log_admin');

        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }
        if ($dtBeginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtBeginDate);
        }
        if ($dtEndDate != '') {
            $oAuthAdminList->where('created_at', '<=', $dtEndDate);
        }
        if ($sType != '') {
            $oAuthAdminList->where('type', $sType);
        }
        if ($sSubAccount != '') {
            $oAuthAdminList->where('sub_account', 'like', '%' . $sSubAccount . '%');
        }
        if ($sIp != '') {
            $oAuthAdminList->where('ip', $sIp);
        }
        if ($sCookies != '') {
            $oAuthAdminList->where('cookies', $sCookies);
        }
        if ($sKeywords != '') {
            $oAuthAdminList->where('log_content', $sKeywords);
        }


        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);
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


        $sSubAccount = '123';
        $sOperateName = 'logAdminlog';
        $sLogContent = 'logAdminlog';
        $sIp = '123';
        $sCookies = '123';
        $dt = now();
        $iMerchantId = '123';
        $sMerchantName = '123';

        AdminLog::adminLogSave($sSubAccount, $sOperateName, $sLogContent, $sIp, $sCookies, $dt, $iMerchantId, $sMerchantName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function logDomainlog()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';

        $oAuthAdminList = DB::table('log_domain');


        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($dtBeginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtBeginDate);
        }


        if ($dtEndDate != '') {
            $oAuthAdminList->where('created_at', '<=', $dtEndDate);
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

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

        $sSubAccount = '123';
        $sOperateName = 'logDomainlog';
        $sLogContent = 'logDomainlog';
        $sIp = '123';
        $sCookies = '123';
        $dt = now();
        $iMerchantId = '123';
        $sMerchantName = '123';

        AdminLog::adminLogSave($sSubAccount, $sOperateName, $sLogContent, $sIp, $sCookies, $dt, $iMerchantId, $sMerchantName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function logLoginlog()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $sType = isset(request()->type) ? request()->type : '';
        $sub_title = isset(request()->sub_title) ? request()->sub_title : '';
        $sKeywords = isset(request()->keywords) ? request()->keywords : '';
        $is_check = isset(request()->is_check) ? request()->is_check : '';

        $oAuthAdminList = DB::table('log_login');


        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($dtBeginDate != '') {
            $oAuthAdminList->where('login_date', '>=', $dtBeginDate);
        }


        if ($dtEndDate != '') {
            $oAuthAdminList->where('login_date', '<=', $dtEndDate);
        }

        if ($sType != '') {
            $oAuthAdminList->where('type', '=', $sType);
        }

        $bFlag = 0;
        if ($is_check) {
            $bFlag = 1;
        }

        $oAuthAdminList->where('is_check', '=', $bFlag);

        if ($sub_title == '用户名') {
            $oAuthAdminList->where('username', 'like', '%' . $sKeywords . '%');
        } elseif ($sub_title == 'IP地址') {
            $oAuthAdminList->where('ip_address', 'like', '%' . $sKeywords . '%');
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

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

        $sSubAccount = '123';
        $sOperateName = 'logLoginlog';
        $sLogContent = 'logLoginlog';
        $sIp = '123';
        $sCookies = '123';
        $dt = now();
        $iMerchantId = '123';
        $sMerchantName = '123';

        AdminLog::adminLogSave($sSubAccount, $sOperateName, $sLogContent, $sIp, $sCookies, $dt, $iMerchantId, $sMerchantName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

}