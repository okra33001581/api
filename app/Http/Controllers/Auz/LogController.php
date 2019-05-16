<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\Ad;
use App\model\AdSite;
use App\model\AdminLog;
use App\common\utils\CommonUtils;

/**
 * Class Event - 日志相关控制器
 * @author zebra
 */
class LogController extends Controller
{
    const ES_URL = 'http://192.168.36.147:9200/';
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function logAdminlog()
    {

        $sWhere = '';
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $iStatus = isset(request()->status) ? request()->status : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';

        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';

        $sLogContent = isset(request()->log_content) ? request()->log_content : '';

        $sSubAccount = isset(request()->sub_account) ? request()->sub_account : '';

        $sIp = isset(request()->ip) ? request()->ip : '';

        $sCookies = isset(request()->cookie_content) ? request()->cookie_content : '';

        $sKeywords = isset(request()->keywords) ? request()->keywords : '';

        $sSearchType = isset(request()->search_type) ? request()->search_type : '';

        $res = [];
        if ($sSearchType == 'ES') {

            if ($sMerchantName != '') {
                $sWhere .= '{ "wildcard":{ "Merchant_name": "*'.$sMerchantName.'*" } },';
            }

            if ($dtBeginDate != '') {
                $dtBeginDate = str_replace(' ','T',$dtBeginDate);
                $sWhere .= '{ "range":{ "Date": {"from" : "'.$dtBeginDate.'"}  } },';
            }
            if ($dtEndDate != '') {
                $dtEndDate = str_replace(' ','T',$dtEndDate);
                $sWhere .= '{ "range":{ "Date": {"to" : "'.$dtEndDate.'"} } },';
            }
            if ($sLogContent != '') {
                $sWhere .= '{ "wildcard":{ "Log_content": "*'.$sLogContent.'*" } },';
            }
            if ($sSubAccount != '') {
                $sWhere .= '{ "wildcard":{ "Sub_account": "*'.$sSubAccount.'*" } },';
            }
            if ($sIp != '') {
                $sWhere .= '{ "wildcard":{ "Ip": "*'.$sIp.'*" } },';
            }
            if ($sCookies != '') {
                $sWhere .= '{ "wildcard":{ "Cookies": "*'.$sCookies.'*" } },';
            }
            if ($sKeywords != '') {
                $sWhere .= '{ "wildcard":{ "Keywords": "*'.$sKeywords.'*" } },';
            }
            if (substr($sWhere, -1)==',') {
                $sWhere = substr($sWhere,0,-1);
            }
            $data['where'] = $sWhere;
            $data['page'] = $sIpage;
            $data['url'] = self::ES_URL."log_admin/_search?pretty";
            $sResult = CommonUtils::getCurlFileGetContents($data);
            $oLogAdminlogFinalList = AdminLog::getEsData($sResult,$iTotal);
            $res["total"] = $iTotal;
            $res["list"] = $oLogAdminlogFinalList;
            $aFinal['message'] = 'success';

            $aFinal['code'] = 0;
            $aFinal['data'] = $res;

        } else {

            $oLogAdminlogList = DB::table('log_admin');

            if ($sMerchantName != '') {
                $oLogAdminlogList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
            }
            if ($dtBeginDate != '') {
                $oLogAdminlogList->where('date', '>=', $dtBeginDate);
            }
            if ($dtEndDate != '') {
                $oLogAdminlogList->where('date', '<=', $dtEndDate);
            }
            if ($sLogContent != '') {
                $oLogAdminlogList->where('log_content', 'like', '%' . $sLogContent . '%');
            }
            if ($sSubAccount != '') {
                $oLogAdminlogList->where('sub_account', 'like', '%' . $sSubAccount . '%');
            }
            if ($sIp != '') {
                $oLogAdminlogList->where('ip', $sIp);
            }
            if ($sCookies != '') {
                $oLogAdminlogList->where('cookies', $sCookies);
            }
            if ($sKeywords != '') {
                $oLogAdminlogList->where('log_content', $sKeywords);
            }

            $iLimit = request()->get('limit', 20);
            $oLogAdminlogFinalList = $oLogAdminlogList->orderby('id', 'desc')->paginate($iLimit);
            $res["total"] = count($oLogAdminlogFinalList);
            $res["list"] = $oLogAdminlogFinalList->toArray();;
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        }
        $sOperateName = 'logAdminlog';
        $sLogContent = 'logAdminlog';
        $dt = now();
        AdminLog::adminLogSave($sOperateName);
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

        $iLimit = request()->get('limit', 20);
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


        $sOperateName = 'logDomainlog';
        $sLogContent = 'logDomainlog';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

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
        $sub_title = isset(request()->sub_type) ? request()->sub_type : '';
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

        $bFlag = '';
        if ($is_check) {
            $bFlag = 1;
        } else {
            $bFlag = 0;
        }

        if ($bFlag != '') {
            $oAuthAdminList->where('is_check', '=', $bFlag);
        }

        if ($sub_title == '用户名') {
            $oAuthAdminList->where('username', 'like', '%' . $sKeywords . '%');
        } elseif ($sub_title == 'IP地址') {
            $oAuthAdminList->where('ip_address', 'like', '%' . $sKeywords . '%');
        }

        $iLimit = request()->get('limit', 20);
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


        $sOperateName = 'logLoginlog';
        $sLogContent = 'logLoginlog';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

}