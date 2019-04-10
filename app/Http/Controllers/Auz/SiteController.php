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
use App\model\IpBlack;
use App\model\SystemConfig;
use App\model\WebIcon;
use App\model\QrCode;
use App\model\RotatePlay;
use App\model\FloatWindow;
use App\model\Information;
use App\model\Company;
use App\model\LotteryGroup;

use App\model\AdminLog;


/**
 * Class Event - 网站管理相关控制器
 * @author zebra
 */
class SiteController extends Controller
{
    public function floatwindowconfigList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
//        $iStatus = isset(request()->status) ? request()->status : '';
//        $sUserName = isset(request()->username) ? request()->username : '';


        $iStatus = isset(request()->status) ? request()->status : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';



        $oAuthAdminList = DB::table('site_float_window');



        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }


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

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['position'] = $oAuthAdmin->position;
            $aTmp['title'] = $oAuthAdmin->title;
            $aTmp['pic'] = $oAuthAdmin->pic;
            $aTmp['link_type'] = $oAuthAdmin->link_type;
            $aTmp['link'] = $oAuthAdmin->link;
            $aTmp['width'] = $oAuthAdmin->width;
            $aTmp['right_margin'] = $oAuthAdmin->right_margin;
            $aTmp['expand_flag'] = $oAuthAdmin->expand_flag;
            $aTmp['expand_pic'] = $oAuthAdmin->expand_pic;
            $aTmp['expand_pic_desc'] = $oAuthAdmin->expand_pic_desc;
            $aTmp['sequence'] = $oAuthAdmin->sequence;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);


        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function blacklist()
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




        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sType = isset(request()->type) ? request()->type : '';

        $oAuthAdminList = DB::table('site_ip_black');

//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }
        if ($sType !== '') {
            $oAuthAdminList->where('type', $sType);
        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//            $oAuthAdmin->avatar = PublicFileUtils::createUploadUrl($oAuthAdmin->avatar);
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['ip_list'] = $oAuthAdmin->ip_list;
            $aTmp['district'] = $oAuthAdmin->district;
            $aTmp['memo'] = $oAuthAdmin->memo;
            $aTmp['type'] = $oAuthAdmin->type;
            $aTmp['creator'] = $oAuthAdmin->creator;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updator'] = $oAuthAdmin->updator;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function systemconfiglist()
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

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $oAuthAdminList = DB::table('site_system_config');

//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }


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

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

        $aTmp = [];
        $aFinal = [];
        /*foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//            $oAuthAdmin->avatar = PublicFileUtils::createUploadUrl($oAuthAdmin->avatar);
            $aTmp['id'] = $oAuthAdmin->id;
            if ($oAuthAdmin->is_login == '1') {
                $aTmp['is_login'] = true;
            } else {
                $aTmp['is_login'] = false;
            }

            $aTmp['web_title'] = $oAuthAdmin->web_title;
            $aTmp['web_keyword'] = $oAuthAdmin->web_keyword;
            $aTmp['web_desc'] = $oAuthAdmin->web_desc;
            $aTmp['platform_name'] = $oAuthAdmin->platform_name;
            $aTmp['free_play'] = json_decode($oAuthAdmin->free_play);
            $aTmp['favorite_skin'] = $oAuthAdmin->favorite_skin;

            if ($oAuthAdmin->is_maintain == '1') {
                $aTmp['is_maintain'] = true;
            } else {
                $aTmp['is_maintain'] = false;
            }

            $aTmp['maintain_desc'] = $oAuthAdmin->maintain_desc;
            $aTmp['maintain_date'] = $oAuthAdmin->maintain_date;

            if ($oAuthAdmin->is_web_register == '1') {
                $aTmp['is_web_register'] = true;
            } else {
                $aTmp['is_web_register'] = false;
            }

            $aTmp['register_default_agent'] = $oAuthAdmin->register_default_agent;
            $aTmp['register_default_rebate'] = $oAuthAdmin->register_default_rebate;
            $aTmp['max_rebate'] = $oAuthAdmin->max_rebate;
            $aTmp['spread_rebate'] = $oAuthAdmin->spread_rebate;

            if ($oAuthAdmin->is_mobile_register == '1') {
                $aTmp['is_mobile_register'] = true;
            } else {
                $aTmp['is_mobile_register'] = false;
            }

            $aTmp['mobile_default_agent'] = $oAuthAdmin->mobile_default_agent;
            $aTmp['mobile_register_rebate'] = $oAuthAdmin->mobile_register_rebate;

            if ($oAuthAdmin->autoregister_usertype == '1') {
                $aTmp['autoregister_usertype'] = true;
            } else {
                $aTmp['autoregister_usertype'] = false;
            }


            if ($oAuthAdmin->can_set_rebate == '1') {
                $aTmp['can_set_rebate'] = true;
            } else {
                $aTmp['can_set_rebate'] = false;
            }

            $aTmp['free_play_rebate'] = $oAuthAdmin->free_play_rebate;



//            $aTmp['user_register_column'] = [];

            $aTmp['user_register_column'] = json_decode($oAuthAdmin->user_register_column);
            $aTmp['lower_register_column'] = json_decode($oAuthAdmin->lower_register_column);
            $aTmp['withdraw_max'] = $oAuthAdmin->withdraw_max;
            $aTmp['deposit_max'] = $oAuthAdmin->deposit_max;

            if ($oAuthAdmin->can_deposit_decimal_point == '1') {
                $aTmp['can_deposit_decimal_point'] = true;
            } else {
                $aTmp['can_deposit_decimal_point'] = false;
            }

            $aTmp['withdraw_risk_audit'] = $oAuthAdmin->withdraw_risk_audit;
            $aTmp['bankcard_bind_max'] = $oAuthAdmin->bankcard_bind_max;
            $aTmp['withdraw_minutes'] = $oAuthAdmin->withdraw_minutes;


            if ($oAuthAdmin->fast_deposit_link_flag == '1') {
                $aTmp['fast_deposit_link_flag'] = true;
            } else {
                $aTmp['fast_deposit_link_flag'] = false;
            }

            $aTmp['fast_deposit_link'] = $oAuthAdmin->fast_deposit_link;
            $aTmp['withdraw_date_begin'] = $oAuthAdmin->withdraw_date_begin;
            $aTmp['withdraw_date_end'] = $oAuthAdmin->withdraw_date_end;
            $aTmp['login_times'] = $oAuthAdmin->login_times;
            $aTmp['ip_account_login_count'] = $oAuthAdmin->ip_account_login_count;

            if ($oAuthAdmin->google_login_flag == '1') {
                $aTmp['google_login_flag'] = true;
            } else {
                $aTmp['google_login_flag'] = false;
            }
            $aTmp['valid_user_turnover'] = $oAuthAdmin->valid_user_turnover;

            if ($oAuthAdmin->login_onetime_flag == '1') {
                $aTmp['login_onetime_flag'] = true;
            } else {
                $aTmp['login_onetime_flag'] = false;
            }

            $aTmp['help_link'] = $oAuthAdmin->help_link;
            $aTmp['qq_link'] = $oAuthAdmin->qq_link;
            $aTmp['help_tel'] = $oAuthAdmin->help_tel;

            if ($oAuthAdmin->qq_help_flag == '1') {
                $aTmp['qq_help_flag'] = true;
            } else {
                $aTmp['qq_help_flag'] = false;
            }

            $aTmp['winner_rato'] = $oAuthAdmin->winner_rato;
            $aTmp['winner_project_rato'] = $oAuthAdmin->winner_project_rato;
            $aTmp['risk_rato'] = $oAuthAdmin->risk_rato;
            $aTmp['transfer_type'] = json_decode($oAuthAdmin->transfer_type);
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }




    public function informationCompanylist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
//        $iStatus = isset(request()->status) ? request()->status : '';
//        $sUserName = isset(request()->username) ? request()->username : '';


        $iStatus = isset(request()->status) ? request()->status : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';


        $oAuthAdminList = DB::table('site_company');


        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }


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

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

/*        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['display_style'] = $oAuthAdmin->display_style;
            $aTmp['content'] = $oAuthAdmin->content;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function informationList()
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
        $oAuthAdminList = DB::table('site_information');

        $iStatus = isset(request()->status) ? request()->status : '';

        $sType = isset(request()->type) ? request()->type : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sType !== '') {
            $oAuthAdminList->where('type', $sType);
        }



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

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);


        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['title'] = $oAuthAdmin->title;
            $aTmp['sequence'] = $oAuthAdmin->sequence;
            $aTmp['status'] = $oAuthAdmin->status;
            $sTmp = '';
            if ($oAuthAdmin->type == 0) {
                $sTmp = '新闻';
            } else {
                $sTmp = '技巧';
            }
            $aTmp['type'] = $sTmp;

            $aTmp['content'] = $oAuthAdmin->content;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;

            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['creator'] = $oAuthAdmin->creator;
            $aTmp['updater'] = $oAuthAdmin->updater;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sub_account = '123';
        $operate_name = 'informationList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    public function lotterygroupSort()
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
        $oAuthAdminList = DB::table('site_lotterygroup');



        $iStatus = isset(request()->status) ? request()->status : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';



        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }


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

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);


        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['type'] = $oAuthAdmin->type;
            $aTmp['name'] = $oAuthAdmin->name;
            $aTmp['sequence'] = $oAuthAdmin->sequence;
            $aTmp['hot'] = $oAuthAdmin->hot;
            $aTmp['recommand'] = $oAuthAdmin->recommand;
            $aTmp['new'] = $oAuthAdmin->new;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }



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
    public function proxyiptablesBlackcontainlist()
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
        $oAuthAdminList = DB::table('auth_admins');

        $sTmp = 'DESC';
        if (substr($iSort, 0, 1) == '-') {
            $sTmp = 'ASC';
        }
        $sOrder = substr($iSort, 1, strlen($iSort));
        if ($sTmp != '') {
            $oAuthAdminList->orderby($sOrder, $sTmp);
        }
        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }
        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }
        $oAuthAdminListCount = $oAuthAdminList->get();
        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $oAuthAdmin->avatar = PublicFileUtils::createUploadUrl($oAuthAdmin->avatar);
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['password'] = $oAuthAdmin->password;
            $aTmp['tel'] = $oAuthAdmin->tel;
            $aTmp['email'] = $oAuthAdmin->email;
            $aTmp['avatar'] = $oAuthAdmin->avatar;
            $aTmp['sex'] = $oAuthAdmin->sex;
            $aTmp['last_login_ip'] = $oAuthAdmin->last_login_ip;
            $aTmp['last_login_time'] = $oAuthAdmin->last_login_time;
            $aTmp['create_time'] = $oAuthAdmin->create_time;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $roles = AuthRoleAdmin::where('admin_id', $oAuthAdmin->id)->first();
            $temp_roles = [];
            if (is_object($roles)) {
                $temp_roles = $roles->toArray();
                $temp_roles = array_column($temp_roles, 'role_id');
            }
            $aTmp['roles'] = $temp_roles;
            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    public function proxyiptablesBlackSave()
    {
        $data = request()->post();

        $district = isset($data['district']) ? $data['district'] : '';
        $id = isset($data['id']) ? $data['id'] : '';
        $ipList = isset($data['ipList']) ? $data['ipList'] : '';
        $memo = isset($data['memo']) ? $data['memo'] : '';
        $type = isset($data['type']) ? $data['type'] : '';

        if ($id != '') {
            $oIpBlack = IpBlack::find($id);
        } else {
            $oIpBlack = new IpBlack();
        }
        $oIpBlack->district = $district;
        $oIpBlack->ip_list = $ipList;
        $oIpBlack->memo = $memo;
        $oIpBlack->type = $type;

        $iRet = $oIpBlack->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oIpBlack;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }

    public function blackDelete()
    {
        $data = request()->post();

        $id = isset($data['id']) ? $data['id'] : '';

        if ($id != '') {
            $oIpBlack = IpBlack::find($id);
        } else {
//            $oIpBlack = new IpBlack();
        }
        if($oIpBlack->delete()){
            $sMessage = '删除成功！';
        }else{
            $sMessage = '删除文章失败！';
        }

        $aFinal['message'] = $sMessage;
        $aFinal['code'] = 0;
        $aFinal['data'] = $oIpBlack;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }





    public function systemConfigSave()
    {
        $data = request()->post();




        $id= isset($data['id']) ? $data['id'] : '';
        $autoregister_usertype= isset($data['autoregister_usertype']) ? $data['autoregister_usertype'] : '';
        $avatar= isset($data['avatar']) ? $data['avatar'] : '';
        $bankcard_bind_max= isset($data['bankcard_bind_max']) ? $data['bankcard_bind_max'] : '';
        $can_deposit_decimal_point= isset($data['can_deposit_decimal_point']) ? $data['can_deposit_decimal_point'] : '';
        $can_set_rebate= isset($data['can_set_rebate']) ? $data['can_set_rebate'] : '';
        $create_time= isset($data['create_time']) ? $data['create_time'] : '';
        $created_at= isset($data['created_at']) ? $data['created_at'] : '';
        $deposit_max= isset($data['deposit_max']) ? $data['deposit_max'] : '';
        $email= isset($data['email']) ? $data['email'] : '';
        $fast_deposit_link= isset($data['fast_deposit_link']) ? $data['fast_deposit_link'] : '';
        $fast_deposit_link_flag= isset($data['fast_deposit_link_flag']) ? $data['fast_deposit_link_flag'] : '';
        $favorite_skin= isset($data['favorite_skin']) ? $data['favorite_skin'] : '';
        $free_play= isset($data['free_play']) ? $data['free_play'] : '';
        $free_play_rebate= isset($data['free_play_rebate']) ? $data['free_play_rebate'] : '';
        $google_login_flag= isset($data['google_login_flag']) ? $data['google_login_flag'] : '';
        $help_link= isset($data['help_link']) ? $data['help_link'] : '';
        $help_tel= isset($data['help_tel']) ? $data['help_tel'] : '';
        $id= isset($data['id']) ? $data['id'] : '';
        $ip_account_login_count= isset($data['ip_account_login_count']) ? $data['ip_account_login_count'] : '';
        $is_login= isset($data['is_login']) ? $data['is_login'] : '';
        $is_maintain= isset($data['is_maintain']) ? $data['is_maintain'] : '';
        $is_mobile_register= isset($data['is_mobile_register']) ? $data['is_mobile_register'] : '';
        $is_web_register= isset($data['is_web_register']) ? $data['is_web_register'] : '';
        $last_login_ip= isset($data['last_login_ip']) ? $data['last_login_ip'] : '';
        $last_login_time= isset($data['last_login_time']) ? $data['last_login_time'] : '';
        $login_onetime_flag= isset($data['login_onetime_flag']) ? $data['login_onetime_flag'] : '';
        $login_times= isset($data['login_times']) ? $data['login_times'] : '';
        $lower_register_column= isset($data['lower_register_column']) ? $data['lower_register_column'] : '';
        $maintain_date= isset($data['maintain_date']) ? $data['maintain_date'] : '';
        $maintain_desc = isset($data['maintain_desc']) ? $data['maintain_desc'] : '';
        $max_rebate= isset($data['max_rebate']) ? $data['max_rebate'] : '';
        $mobile_default_agent= isset($data['mobile_default_agent']) ? $data['mobile_default_agent'] : '';
        $mobile_register_rebate= isset($data['mobile_register_rebate']) ? $data['mobile_register_rebate'] : '';
        $password= isset($data['password']) ? $data['password'] : '';
        $platform_name= isset($data['platform_name']) ? $data['platform_name'] : '';
        $qq_help_flag= isset($data['qq_help_flag']) ? $data['qq_help_flag'] : '';
        $qq_link= isset($data['qq_link']) ? $data['qq_link'] : '';
        $register_default_agent= isset($data['register_default_agent']) ? $data['register_default_agent'] : '';
        $register_default_rebate= isset($data['register_default_rebate']) ? $data['register_default_rebate'] : '';
        $risk_rato= isset($data['risk_rato']) ? $data['risk_rato'] : '';
        $roles= isset($data['roles']) ? $data['roles'] : '';
        $sex= isset($data['sex']) ? $data['sex'] : '';
        $spread_rebate= isset($data['spread_rebate']) ? $data['spread_rebate'] : '';
        $status= isset($data['status']) ? $data['status'] : '';
        $tel= isset($data['tel']) ? $data['tel'] : '';
        $transfer_type= isset($data['transfer_type']) ? $data['transfer_type'] : '';
        $updated_at= isset($data['updated_at']) ? $data['updated_at'] : '';
        $user_register_column= isset($data['user_register_column']) ? $data['user_register_column'] : '';
        $username= isset($data['username']) ? $data['username'] : '';
        $valid_user_turnover= isset($data['valid_user_turnover']) ? $data['valid_user_turnover'] : '';
        $web_desc= isset($data['web_desc']) ? $data['web_desc'] : '';
        $web_keyword= isset($data['web_keyword']) ? $data['web_keyword'] : '';
        $web_title= isset($data['web_title']) ? $data['web_title'] : '';
        $winner_project_rato= isset($data['winner_project_rato']) ? $data['winner_project_rato'] : '';
        $winner_rato= isset($data['winner_rato']) ? $data['winner_rato'] : '';
        $withdraw_date_begin= isset($data['withdraw_date_begin']) ? $data['withdraw_date_begin'] : '';
        $withdraw_date_end= isset($data['withdraw_date_end']) ? $data['withdraw_date_end'] : '';
        $withdraw_max= isset($data['withdraw_max']) ? $data['withdraw_max'] : '';
        $withdraw_minutes= isset($data['withdraw_minutes']) ? $data['withdraw_minutes'] : '';
        $withdraw_risk_audit= isset($data['withdraw_risk_audit']) ? $data['withdraw_risk_audit'] : '';




        if ($id != '') {
            $oSystemConfig = SystemConfig::find($id);
            $oSystemConfig->updated_at= now();
        } else {
            $oSystemConfig = new SystemConfig();
            $oSystemConfig->created_at= now();
        }

        $oSystemConfig->is_login= $is_login;
        $oSystemConfig->web_title= $web_title;
        $oSystemConfig->web_keyword= $web_keyword;
        $oSystemConfig->web_desc= $web_desc;
        $oSystemConfig->platform_name= $platform_name;
        $oSystemConfig->free_play= json_encode($free_play);
        $oSystemConfig->favorite_skin= $favorite_skin;
        $oSystemConfig->is_maintain= $is_maintain;
        $oSystemConfig->maintain_desc= $maintain_desc;
        $oSystemConfig->maintain_date= date('Y-m-d H:i:s');
        $oSystemConfig->is_web_register= $is_web_register;
        $oSystemConfig->register_default_agent= $register_default_agent;
        $oSystemConfig->register_default_rebate= $register_default_rebate;
        $oSystemConfig->max_rebate= $max_rebate;
        $oSystemConfig->spread_rebate= $spread_rebate;
        $oSystemConfig->is_mobile_register= $is_mobile_register;
        $oSystemConfig->mobile_default_agent= $mobile_default_agent;
        $oSystemConfig->mobile_register_rebate= $mobile_register_rebate;
        $oSystemConfig->autoregister_usertype= $autoregister_usertype;
        $oSystemConfig->can_set_rebate= $can_set_rebate;
        $oSystemConfig->free_play_rebate= $free_play_rebate;



        $oSystemConfig->user_register_column= json_encode($user_register_column);
        $oSystemConfig->lower_register_column= json_encode($lower_register_column);
        $oSystemConfig->withdraw_max= $withdraw_max;
        $oSystemConfig->deposit_max= $deposit_max;
        $oSystemConfig->can_deposit_decimal_point= $can_deposit_decimal_point;
        $oSystemConfig->withdraw_risk_audit= $withdraw_risk_audit;
        $oSystemConfig->bankcard_bind_max= $bankcard_bind_max;
        $oSystemConfig->withdraw_minutes= $withdraw_minutes;
        $oSystemConfig->fast_deposit_link_flag= $fast_deposit_link_flag;
        $oSystemConfig->fast_deposit_link= $fast_deposit_link;
        $oSystemConfig->withdraw_date_begin= $withdraw_date_begin;
        $oSystemConfig->withdraw_date_end= $withdraw_date_end;

//        $oSystemConfig->withdraw_date= $withdraw_date;
        $oSystemConfig->login_times= $login_times;
        $oSystemConfig->ip_account_login_count= $ip_account_login_count;
        $oSystemConfig->google_login_flag= $google_login_flag;
        $oSystemConfig->valid_user_turnover= $valid_user_turnover;
        $oSystemConfig->login_onetime_flag= $login_onetime_flag;
        $oSystemConfig->help_link= $help_link;
        $oSystemConfig->qq_link= $qq_link;
        $oSystemConfig->help_tel= $help_tel;
        $oSystemConfig->qq_help_flag= $qq_help_flag;
        $oSystemConfig->winner_rato= $winner_rato;
        $oSystemConfig->winner_project_rato= $winner_project_rato;
        $oSystemConfig->risk_rato= $risk_rato;
        $oSystemConfig->transfer_type= json_encode($transfer_type);


        $iRet = $oSystemConfig->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oSystemConfig;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }





    public function webIconSave()
    {
        $data = request()->post();

        $icon = isset($data['icon']) ? $data['icon'] : '';
        $id = isset($data['id']) ? $data['id'] : '';
        $pic = isset($data['pic']) ? $data['pic'] : '';
//        $memo = isset($data['memo']) ? $data['memo'] : '';
//        $type = isset($data['type']) ? $data['type'] : '';

        if ($id != '') {
            $oWebIcon = WebIcon::find($id);
        } else {
            $oWebIcon = new WebIcon();
        }

        $oWebIcon->icon = $icon;
        $oWebIcon->pic = $pic;


        $iRet = $oWebIcon->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oWebIcon;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }



    public function qrCodeSave()
    {
        $data = request()->post();


        $id = isset($data['id']) ? $data['id'] : '';
        $h5_address = isset($data['h5_address']) ? $data['h5_address'] : '';
        $android_address = isset($data['android_address']) ? $data['android_address'] : '';
        $ios_address = isset($data['ios_address']) ? $data['ios_address'] : '';
        $pic = isset($data['pic']) ? $data['pic'] : '';

        if ($id != '') {
            $oQrCode = QrCode::find($id);
        } else {
            $oQrCode = new QrCode();
        }

        $oQrCode->h5_address = $h5_address;
        $oQrCode->android_address = $android_address;
        $oQrCode->ios_address = $ios_address;

        $oQrCode->pic = $pic;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;
        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }



    public function rotatePlaySave()
    {
        $data = request()->post();


        $id = isset($data['id']) ? $data['id'] : '';

        $title = isset($data['title']) ? $data['title'] : '';
        $pc_pic = isset($data['pc_pic']) ? $data['pc_pic'] : '';
        $mobile_pic = isset($data['mobile_pic']) ? $data['mobile_pic'] : '';
        $link_type = isset($data['link_type']) ? $data['link_type'] : '';
        $link = isset($data['link']) ? $data['link'] : '';
        $status = isset($data['status']) ? $data['status'] : '';
        $squence = isset($data['squence']) ? $data['squence'] : '';


        if ($id != '') {
            $oQrCode = RotatePlay::find($id);
            $oQrCode->created_at = now();
        } else {
            $oQrCode = new RotatePlay();
            $oQrCode->updated_at = now();
        }

        $oQrCode->title = $title;
        $oQrCode->pc_pic = $pc_pic;
        $oQrCode->mobile_pic = $mobile_pic;
        $oQrCode->link_type = $link_type;
        $oQrCode->link = $link;
        $oQrCode->status = $status;

        $oQrCode->squence = $squence;



        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }

    public function floatWindowSave()
    {
        $data = request()->post();


        $id=isset($data['id'])?$data['id']:'';
        $merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';
        $position=isset($data['position'])?$data['position']:'';
        $title=isset($data['title'])?$data['title']:'';
        $pic=isset($data['pic'])?$data['pic']:'';
        $link_type=isset($data['link_type'])?$data['link_type']:'';
        $link=isset($data['link'])?$data['link']:'';
        $width=isset($data['width'])?$data['width']:'';
        $right_margin=isset($data['right_margin'])?$data['right_margin']:'';
        $expand_flag=isset($data['expand_flag'])?$data['expand_flag']:'';
        $expand_pic=isset($data['expand_pic'])?$data['expand_pic']:'';
        $expand_pic_desc=isset($data['expand_pic_desc'])?$data['expand_pic_desc']:'';
        $sequence=isset($data['sequence'])?$data['sequence']:'';
        $status=isset($data['status'])?$data['status']:'';


        if ($id != '') {
            $oQrCode = FloatWindow::find($id);
        } else {
            $oQrCode = new FloatWindow();
        }

//        $oQrCode->id = $id;
//        $oQrCode->merchant_id = $merchant_id;
        $oQrCode->position = $position;
        $oQrCode->title = $title;
        $oQrCode->pic = $pic;
        $oQrCode->link_type = $link_type;
        $oQrCode->link = $link;
        $oQrCode->width = $width;
        $oQrCode->right_margin = $right_margin;
        $oQrCode->expand_flag = $expand_flag;
        $oQrCode->expand_pic = $expand_pic;
        $oQrCode->expand_pic_desc = $expand_pic_desc;
        $oQrCode->sequence = $sequence;
        $oQrCode->status = $status;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        return response()->json($aFinal);
    }

    public function informationSave()
    {
        $data = request()->post();


        $id=isset($data['id'])?$data['id']:'';
        $title=isset($data['title'])?$data['title']:'';
        $sequence=isset($data['sequence'])?$data['sequence']:'';
        $status=isset($data['status'])?$data['status']:'';
        $type=isset($data['type'])?$data['type']:'';
        $content=isset($data['content'])?$data['content']:'';

        if ($id != '') {
            $oQrCode = Information::find($id);
            $oQrCode->updated_at = now();
        } else {
            $oQrCode = new Information();
            $oQrCode->created_at = now();
        }


//        $oQrCode->merchant_id = $merchant_id;
        $oQrCode->title = $title;
        $oQrCode->sequence = $sequence;
        $oQrCode->status = $status;
        $oQrCode->type = $type;

        $oQrCode->content = $content;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        return response()->json($aFinal);
    }

    public function companySave()
    {
        $data = request()->post();


        $id=isset($data['id'])?$data['id']:'';
        $display_status=isset($data['display_status'])?$data['display_status']:'';
        $display_style=isset($data['display_style'])?$data['display_style']:'';
        $content=isset($data['content'])?$data['content']:'';

        if ($id != '') {
            $oQrCode = Company::find($id);
        } else {
            $oQrCode = new Company();
        }


        $oQrCode->id = $id;
//        $oQrCode->merchant_id = $merchant_id;
        $oQrCode->status = $display_status;
        $oQrCode->display_style = $display_style;
        $oQrCode->content = $content;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        return response()->json($aFinal);
    }



    public function qrconfigList()
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

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $oAuthAdminList = DB::table('site_qr_code');


        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

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

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);
/*        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['h5_address'] = $oAuthAdmin->h5_address;
            $aTmp['android_address'] = $oAuthAdmin->android_address;
            $aTmp['ios_address'] = $oAuthAdmin->ios_address;

            $aTmp['pic'] = Event::getFileDomain($oAuthAdmin->pic);
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }



    public function rotationconfigList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
//
//        $sUserName = isset(request()->username) ? request()->username : '';


        $iStatus = isset(request()->status) ? request()->status : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';


        $oAuthAdminList = DB::table('site_rotate_play');


        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }


//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }

//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();


        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['title'] = $oAuthAdmin->title;
            $aTmp['pc_pic'] = $oAuthAdmin->pc_pic;
            $aTmp['mobile_pic'] = $oAuthAdmin->mobile_pic;
            $aTmp['link_type'] = $oAuthAdmin->link_type;
            $aTmp['link'] = $oAuthAdmin->link;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['squence'] = $oAuthAdmin->squence;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;

            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['creator'] = $oAuthAdmin->creator;
            $aTmp['updater'] = $oAuthAdmin->updater;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function systemconfigImagelist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
//        $iStatus = isset(request()->status) ? request()->status : '';
//        $sUserName = isset(request()->username) ? request()->username : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $oAuthAdminList = DB::table('site_web_icon');

        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

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

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

       /* $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['icon'] = $oAuthAdmin->icon;
            $aTmp['pic'] = Event::getFileDomain($oAuthAdmin->pic);
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function systemconfigSet()
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
        $oAuthAdminList = DB::table('auth_admins');

        $sTmp = 'DESC';
        if (substr($iSort, 0, 1) == '-') {
            $sTmp = 'ASC';
        }
        $sOrder = substr($iSort, 1, strlen($iSort));
        if ($sTmp != '') {
            $oAuthAdminList->orderby($sOrder, $sTmp);
        }
        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }
        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }
        $oAuthAdminListCount = $oAuthAdminList->get();
        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $oAuthAdmin->avatar = PublicFileUtils::createUploadUrl($oAuthAdmin->avatar);
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['password'] = $oAuthAdmin->password;
            $aTmp['tel'] = $oAuthAdmin->tel;
            $aTmp['email'] = $oAuthAdmin->email;
            $aTmp['avatar'] = $oAuthAdmin->avatar;
            $aTmp['sex'] = $oAuthAdmin->sex;
            $aTmp['last_login_ip'] = $oAuthAdmin->last_login_ip;
            $aTmp['last_login_time'] = $oAuthAdmin->last_login_time;
            $aTmp['create_time'] = $oAuthAdmin->create_time;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $roles = AuthRoleAdmin::where('admin_id', $oAuthAdmin->id)->first();
            $temp_roles = [];
            if (is_object($roles)) {
                $temp_roles = $roles->toArray();
                $temp_roles = array_column($temp_roles, 'role_id');
            }
            $aTmp['roles'] = $temp_roles;
            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

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



    public function informationStatusSave($id = null)
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
        $oEvent = Information::find($id);
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


    public function rotationconfigDelete()
    {
//        $id = request()->post('id/d');
        $id = request()->all()['id'];
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$id)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $id)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        RotatePlay::where('id', '=', $id)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success();

    }

    public function floatwindowconfigDelete()
    {
//        $id = request()->post('id/d');
        $id = request()->all()['id'];
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$id)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $id)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        FloatWindow::where('id', '=', $id)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success();

    }

    public function informationDelete()
    {
//        $id = request()->post('id/d');
        $id = request()->all()['id'];
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$id)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $id)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        Information::where('id', '=', $id)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success();

    }


    public function updateLotterygroupSequence($id = null)
    {

        $data = request()->post();
//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);
        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/
        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = LotteryGroup::find($id);
        $oEvent->sequence = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;

        return response()->json($aFinal);
    }

    public function updateInformationSequence($id = null)
    {

        $data = request()->post();
//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);
        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/
        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = Information::find($id);
        $oEvent->sequence = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;

        return response()->json($aFinal);
    }

    public function updatefloatwindowSequence($id = null)
    {

        $data = request()->post();
//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);
        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/
        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = FloatWindow::find($id);
        $oEvent->sequence = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;

        return response()->json($aFinal);
    }




    public function updateLotteryGroupPropertySave($id = null)
    {

        $data = request()->post();
//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);
        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/
        $id = isset($data['id']) ? $data['id'] : '';
        $hot = isset($data['hot']) ? $data['hot'] : '';
        $recommand = isset($data['recommand']) ? $data['recommand'] : '';
        $new = isset($data['new']) ? $data['new'] : '';

//        Log::info($data);
        $sFirst1 = substr($hot, 0, 1);
        $sFirst2 = substr($recommand, 0, 1);
        $sFirst3 = substr($new, 0, 1);
        $bFlag1 = false;
        $bFlag2 = false;
        $bFlag3 = false;
        if ($sFirst1 == '+') {
            $bFlag1 = true;
        }
        if ($sFirst2 == '+') {
            $bFlag2 = true;
        }
        if ($sFirst3 == '+') {
            $bFlag3 = true;
        }

        $oEvent = LotteryGroup::find($id);

        if ($hot != '') {
            if ($bFlag1) {
                $oEvent->hot = substr($hot, 1, strlen($hot));
            } else {
                $oEvent->hot = '';
            }

        }

        if ($recommand != '') {
            if ($bFlag2) {
                $oEvent->recommand = substr($recommand, 1, strlen($recommand));
            } else {
                $oEvent->recommand = '';
            }
        }

        if ($new != '') {
            if ($bFlag3) {
                $oEvent->new = substr($new, 1, strlen($new));
            } else {
                $oEvent->new = '';
            }
        }

        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 1;
        $aFinal['data'] = $oEvent;

        return response()->json($aFinal);
    }

}