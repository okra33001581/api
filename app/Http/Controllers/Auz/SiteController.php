<?php

namespace App\Http\Controllers;

use App\model\AuthAdmin;
use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\AuthRoleAdmin;
use App\common\utils\PublicFileUtils;
use App\common\utils\CommonUtils;
use App\model\Ad;
use App\model\AdSite;
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
use App\model\Event;
use App\model\Common;


/**
 * Class Event - 网站管理相关控制器
 * @author zebra
 */
class SiteController extends Controller
{

    /**
     * 浮动窗口数据列表
     * @param request
     * @return json
     */
    public function floatwindowconfigList()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';

        $iStatus = isset(request()->status) ? request()->status : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $oFloatwindowconfigList = DB::table('site_float_window');

        if ($sMerchantName != '') {
            $oFloatwindowconfigList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($iStatus !== '') {
            $oFloatwindowconfigList->where('status', $iStatus);
        }

        $iLimit = request()->get('limit', 20);
        $oFloatwindowconfigFinalList = $oFloatwindowconfigList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oFloatwindowconfigFinalList);
        $res["list"] = $oFloatwindowconfigFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'floatwindowconfigList';
        $sLogContent = 'floatwindowconfigList';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * IP限制数据列表
     * @param request
     * @return json
     */
    public function blacklist()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sType = isset(request()->type) ? request()->type : '';
        $oBlackList = DB::table('site_ip_black');
        if ($sMerchantName != '') {
            $oBlackList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        if ($sType !== '') {
            $oBlackList->where('type', $sType);
        }

        $iLimit = request()->get('limit', 20);
        $oBlackFinalList = $oBlackList->orderby('id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($oBlackFinalList);
        $res["list"] = $oBlackFinalList->toArray();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
        $sOperateName = 'blacklist';
        $sLogContent = 'blacklist';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * 系统参数设定数据列表
     * @param request
     * @return json
     */
    public function systemconfiglist()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $oSystemconfigList = DB::table('site_system_config');
        if ($sMerchantName != '') {
            $oSystemconfigList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        $iLimit = request()->get('limit', 20);
        $oSystemconfigFinalList = $oSystemconfigList->orderby('id', 'desc')->paginate($iLimit);
        $aTmp = [];
        $aFinal = [];
        $res = [];
        $res["total"] = count($oSystemconfigFinalList);
        $res["list"] = $oSystemconfigFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'systemconfiglist';
        $sLogContent = 'systemconfiglist';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * 公司简介数据列表
     * @param request
     * @return json
     */
    public function informationCompanylist()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iStatus = isset(request()->status) ? request()->status : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $oInformationCompanyList = DB::table('site_company');

        if ($sMerchantName != '') {
            $oInformationCompanyList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($iStatus !== '') {
            $oInformationCompanyList->where('status', $iStatus);
        }

        $iLimit = request()->get('limit', 20);
        $oInformationCompanyFinalList = $oInformationCompanyList->orderby('id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($oInformationCompanyFinalList);
        $res["list"] = $oInformationCompanyFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
        $sOperateName = 'informationCompanylist';
        $sLogContent = 'informationCompanylist';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * 谘询中心数据列表
     * @param request
     * @return json
     */
    public function informationList()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';
        $sType = isset(request()->type) ? request()->type : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $oInformationList = DB::table('site_information');

        if ($sMerchantName != '') {
            $oInformationList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        if ($iStatus !== '') {
            $oInformationList->where('status', $iStatus);
        }

        if ($sType !== '') {
            $oInformationList->where('type', $sType);
        }

        $iLimit = request()->get('limit', 20);
        $oInformationFinalList = $oInformationList->orderby('id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($oInformationFinalList);
        $res["list"] = $oInformationFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'informationList';
        $sLogContent = 'informationList';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * 菜单排序数据列表
     * @param request
     * @return json
     */
    public function lotterygroupSort()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $oLotterygroupSortList = DB::table('site_lotterygroup');
        if ($sMerchantName != '') {
            $oLotterygroupSortList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        if ($iStatus !== '') {
            $oLotterygroupSortList->where('status', $iStatus);
        }
        $iLimit = request()->get('limit', 20);
        $oLotterygroupSortFinalList = $oLotterygroupSortList->orderby('id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($oLotterygroupSortFinalList);
        $res["list"] = $oLotterygroupSortFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
        $sOperateName = 'lotterygroupSort';
        $sLogContent = 'lotterygroupSort';

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
    public function proxyiptablesBlackcontainlist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
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
        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);
        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'proxyiptablesBlackcontainlist';
        $sLogContent = 'proxyiptablesBlackcontainlist';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function proxyiptablesBlackSave()
    {
        $data = request()->post();

        $district = isset($data['district']) ? $data['district'] : '';
        $iId = isset($data['id']) ? $data['id'] : '';
        $sIpList = isset($data['ipList']) ? $data['ipList'] : '';
        $sMemo = isset($data['memo']) ? $data['memo'] : '';
        $sType = isset($data['type']) ? $data['type'] : '';

        if ($iId != '') {
            $oIpBlack = IpBlack::find($iId);
        } else {
            $oIpBlack = new IpBlack();
        }
        $oIpBlack->district = $district;
        $oIpBlack->ip_list = $sIpList;
        $oIpBlack->memo = $sMemo;
        $oIpBlack->type = $sType;
        $oIpBlack->created_at = now();

        $iRet = $oIpBlack->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oIpBlack;


        $sOperateName = 'proxyiptablesBlackSave';
        $sLogContent = 'proxyiptablesBlackSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function blackDelete()
    {
        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';

        if ($iId != '') {
            $oIpBlack = IpBlack::find($iId);
        } else {
//            $oIpBlack = new IpBlack();
        }
        if ($oIpBlack->delete()) {
            $sMessage = '删除成功！';
        } else {
            $sMessage = '删除文章失败！';
        }

        $aFinal['message'] = $sMessage;
        $aFinal['code'] = 0;
        $aFinal['data'] = $oIpBlack;


        $sOperateName = 'blackDelete';
        $sLogContent = 'blackDelete';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function systemConfigSave()
    {
        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $sAutoregisterUsertype = isset($data['autoregister_usertype']) ? $data['autoregister_usertype'] : '';
        $avatar = isset($data['avatar']) ? $data['avatar'] : '';
        $iBankcardBindMax = isset($data['bankcard_bind_max']) ? $data['bankcard_bind_max'] : '';
        $sCanDepositDecimalPoint = isset($data['can_deposit_decimal_point']) ? $data['can_deposit_decimal_point'] : '';
        $sCanSetRebate = isset($data['can_set_rebate']) ? $data['can_set_rebate'] : '';
        $dtCreateTime = isset($data['create_time']) ? $data['create_time'] : '';
        $dtCreatedAt = isset($data['created_at']) ? $data['created_at'] : '';
        $fDepositMax = isset($data['deposit_max']) ? $data['deposit_max'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $sFastDepositLink = isset($data['fast_deposit_link']) ? $data['fast_deposit_link'] : '';
        $sFastDepositLinkFlag = isset($data['fast_deposit_link_flag']) ? $data['fast_deposit_link_flag'] : '';
        $sFavoriteSkin = isset($data['favorite_skin']) ? $data['favorite_skin'] : '';
        $sFreePlay = isset($data['free_play']) ? $data['free_play'] : '';
        $fFreePlayRebate = isset($data['free_play_rebate']) ? $data['free_play_rebate'] : '';
        $sGoogleLoginFlag = isset($data['google_login_flag']) ? $data['google_login_flag'] : '';
        $sHelpLink = isset($data['help_link']) ? $data['help_link'] : '';
        $sHelpTel = isset($data['help_tel']) ? $data['help_tel'] : '';
        $iId = isset($data['id']) ? $data['id'] : '';
        $iIpAccountLoginCount = isset($data['ip_account_login_count']) ? $data['ip_account_login_count'] : '';
        $bIsLogin = isset($data['is_login']) ? $data['is_login'] : '';
        $sIsMaintain = isset($data['is_maintain']) ? $data['is_maintain'] : '';
        $bIsMobileRegister = isset($data['is_mobile_register']) ? $data['is_mobile_register'] : '';
        $bIsWebRegister = isset($data['is_web_register']) ? $data['is_web_register'] : '';
        $sLastLoginIp = isset($data['last_login_ip']) ? $data['last_login_ip'] : '';
        $dtLastLoginTime = isset($data['last_login_time']) ? $data['last_login_time'] : '';
        $sLoginOnetimeFlag = isset($data['login_onetime_flag']) ? $data['login_onetime_flag'] : '';
        $iLoginTimes = isset($data['login_times']) ? $data['login_times'] : '';
        $sLowerRegisterColumn = isset($data['lower_register_column']) ? $data['lower_register_column'] : '';
        $dtMaintainDate = isset($data['maintain_date']) ? $data['maintain_date'] : '';
        $sMaintainDesc = isset($data['maintain_desc']) ? $data['maintain_desc'] : '';
        $iMaxRebate = isset($data['max_rebate']) ? $data['max_rebate'] : '';
        $sMobileDefaultAgent = isset($data['mobile_default_agent']) ? $data['mobile_default_agent'] : '';
        $fMobileRegisterRebate = isset($data['mobile_register_rebate']) ? $data['mobile_register_rebate'] : '';
        $sPassword = isset($data['password']) ? $data['password'] : '';
        $sPlatformName = isset($data['platform_name']) ? $data['platform_name'] : '';
        $sQqHelpFlag = isset($data['qq_help_flag']) ? $data['qq_help_flag'] : '';
        $sQqLink = isset($data['qq_link']) ? $data['qq_link'] : '';
        $sRegisterDefaultAgent = isset($data['register_default_agent']) ? $data['register_default_agent'] : '';
        $sRegisterDefaultRebate = isset($data['register_default_rebate']) ? $data['register_default_rebate'] : '';
        $fRiskRato = isset($data['risk_rato']) ? $data['risk_rato'] : '';
        $roles = isset($data['roles']) ? $data['roles'] : '';
        $sex = isset($data['sex']) ? $data['sex'] : '';
        $fSpreadRebate = isset($data['spread_rebate']) ? $data['spread_rebate'] : '';
        $status = isset($data['status']) ? $data['status'] : '';
        $tel = isset($data['tel']) ? $data['tel'] : '';
        $sTransferType = isset($data['transfer_type']) ? $data['transfer_type'] : '';
        $dtUpdatedAt = isset($data['updated_at']) ? $data['updated_at'] : '';
        $sUserRegisterColumn = isset($data['user_register_column']) ? $data['user_register_column'] : '';
        $sUsername = isset($data['username']) ? $data['username'] : '';
        $fVvalidUserTurnover = isset($data['valid_user_turnover']) ? $data['valid_user_turnover'] : '';
        $sWebDesc = isset($data['web_desc']) ? $data['web_desc'] : '';
        $sWebKeyword = isset($data['web_keyword']) ? $data['web_keyword'] : '';
        $sWebTitle = isset($data['web_title']) ? $data['web_title'] : '';
        $sWinnerProjectRato = isset($data['winner_project_rato']) ? $data['winner_project_rato'] : '';
        $sWinnerRato = isset($data['winner_rato']) ? $data['winner_rato'] : '';
        $sWithdrawDateBegin = isset($data['withdraw_date_begin']) ? $data['withdraw_date_begin'] : '';
        $sWithdrawDateEnd = isset($data['withdraw_date_end']) ? $data['withdraw_date_end'] : '';
        $sWithdrawMax = isset($data['withdraw_max']) ? $data['withdraw_max'] : '';
        $sWithdrawMinutes = isset($data['withdraw_minutes']) ? $data['withdraw_minutes'] : '';
        $sWithdrawRiskAudit = isset($data['withdraw_risk_audit']) ? $data['withdraw_risk_audit'] : '';


        if ($iId != '') {
            $oSystemConfig = SystemConfig::find($iId);
            $oSystemConfig->updated_at = now();
        } else {
            $oSystemConfig = new SystemConfig();
            $oSystemConfig->created_at = now();
        }

        Log::info($sTransferType);

        $oSystemConfig->is_login = $bIsLogin;
        $oSystemConfig->web_title = $sWebTitle;
        $oSystemConfig->web_keyword = $sWebKeyword;
        $oSystemConfig->web_desc = $sWebDesc;
        $oSystemConfig->platform_name = $sPlatformName;
        $oSystemConfig->free_play = Event::arrTostr($sFreePlay);
        $oSystemConfig->favorite_skin = $sFavoriteSkin;
        $oSystemConfig->is_maintain = $sIsMaintain;
        $oSystemConfig->maintain_desc = $sMaintainDesc;
        $oSystemConfig->maintain_date = date('Y-m-d H:i:s');
        $oSystemConfig->is_web_register = $bIsWebRegister;
        $oSystemConfig->register_default_agent = $sRegisterDefaultAgent;
        $oSystemConfig->register_default_rebate = $sRegisterDefaultRebate;
        $oSystemConfig->max_rebate = $iMaxRebate;
        $oSystemConfig->spread_rebate = $fSpreadRebate;
        $oSystemConfig->is_mobile_register = $bIsMobileRegister;
        $oSystemConfig->mobile_default_agent = $sMobileDefaultAgent;
        $oSystemConfig->mobile_register_rebate = $fMobileRegisterRebate;
        $oSystemConfig->autoregister_usertype = $sAutoregisterUsertype;
        $oSystemConfig->can_set_rebate = $sCanSetRebate;
        $oSystemConfig->free_play_rebate = $fFreePlayRebate;


        $oSystemConfig->user_register_column = Event::arrTostr($sUserRegisterColumn);
        $oSystemConfig->lower_register_column = Event::arrTostr($sLowerRegisterColumn);
        $oSystemConfig->withdraw_max = $sWithdrawMax;
        $oSystemConfig->deposit_max = $fDepositMax;
        $oSystemConfig->can_deposit_decimal_point = $sCanDepositDecimalPoint;
        $oSystemConfig->withdraw_risk_audit = $sWithdrawRiskAudit;
        $oSystemConfig->bankcard_bind_max = $iBankcardBindMax;
        $oSystemConfig->withdraw_minutes = $sWithdrawMinutes;
        $oSystemConfig->fast_deposit_link_flag = $sFastDepositLinkFlag;
        $oSystemConfig->fast_deposit_link = $sFastDepositLink;
        $oSystemConfig->withdraw_date_begin = $sWithdrawDateBegin;
        $oSystemConfig->withdraw_date_end = $sWithdrawDateEnd;

//        $oSystemConfig->withdraw_date= $withdraw_date;
        $oSystemConfig->login_times = $iLoginTimes;
        $oSystemConfig->ip_account_login_count = $iIpAccountLoginCount;
        $oSystemConfig->google_login_flag = $sGoogleLoginFlag;
        $oSystemConfig->valid_user_turnover = $fVvalidUserTurnover;
        $oSystemConfig->login_onetime_flag = $sLoginOnetimeFlag;
        $oSystemConfig->help_link = $sHelpLink;
        $oSystemConfig->qq_link = $sQqLink;
        $oSystemConfig->help_tel = $sHelpTel;
        $oSystemConfig->qq_help_flag = $sQqHelpFlag;
        $oSystemConfig->winner_rato = $sWinnerRato;
        $oSystemConfig->winner_project_rato = $sWinnerProjectRato;
        $oSystemConfig->risk_rato = $fRiskRato;
        $oSystemConfig->transfer_type = Event::arrTostr($sTransferType);


        $iRet = $oSystemConfig->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oSystemConfig;


        $sOperateName = 'systemConfigSave';
        $sLogContent = 'systemConfigSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function webIconSave()
    {
        $data = request()->post();

        $sIcon = isset($data['icon']) ? $data['icon'] : '';
        $iId = isset($data['id']) ? $data['id'] : '';
        $sPic = isset($data['pic']) ? $data['pic'] : '';
//        $sMemo = isset($data['memo']) ? $data['memo'] : '';
//        $sType = isset($data['type']) ? $data['type'] : '';

        if ($iId != '') {
            $oWebIcon = WebIcon::find($iId);
        } else {
            $oWebIcon = new WebIcon();
        }

        $oWebIcon->icon = $sIcon;
        $oWebIcon->pic = $sPic;


        $iRet = $oWebIcon->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oWebIcon;


        $sOperateName = 'webIconSave';
        $sLogContent = 'webIconSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function qrCodeSave()
    {
        $data = request()->post();


        $iId = isset($data['id']) ? $data['id'] : '';
        $sH5Address = isset($data['h5_address']) ? $data['h5_address'] : '';
        $sAndroidAddress = isset($data['android_address']) ? $data['android_address'] : '';
        $sIosAddress = isset($data['ios_address']) ? $data['ios_address'] : '';
        $sPic = isset($data['pic']) ? $data['pic'] : '';

        if ($iId != '') {
            $oQrCode = QrCode::find($iId);
        } else {
            $oQrCode = new QrCode();
        }

        $oQrCode->h5_address = $sH5Address;
        $oQrCode->android_address = $sAndroidAddress;
        $oQrCode->ios_address = $sIosAddress;

        $oQrCode->pic = $sPic;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        $sOperateName = 'qrCodeSave';
        $sLogContent = 'qrCodeSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function rotatePlaySave()
    {
        $data = request()->post();


        $iId = isset($data['id']) ? $data['id'] : '';

        $sTitle = isset($data['title']) ? $data['title'] : '';
        $sPcPic = isset($data['pc_pic']) ? $data['pc_pic'] : '';
        $sMobilePic = isset($data['mobile_pic']) ? $data['mobile_pic'] : '';
        $sLinkType = isset($data['link_type']) ? $data['link_type'] : '';
        $sLink = isset($data['link']) ? $data['link'] : '';
        $status = isset($data['status']) ? $data['status'] : '';
        $squence = isset($data['squence']) ? $data['squence'] : '';


        if ($iId != '') {
            $oQrCode = RotatePlay::find($iId);
            $oQrCode->created_at = now();
        } else {
            $oQrCode = new RotatePlay();
            $oQrCode->updated_at = now();
        }

        $oQrCode->title = $sTitle;
        $oQrCode->pc_pic = $sPcPic;
        $oQrCode->mobile_pic = $sMobilePic;
        $oQrCode->link_type = $sLinkType;
        $oQrCode->link = $sLink;
        $oQrCode->status = $status;

        $oQrCode->sequence = $squence;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'rotatePlaySave';
        $sLogContent = 'rotatePlaySave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function floatWindowSave()
    {
        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iMerchantId = isset($data['merchant_id']) ? $data['merchant_id'] : '';
        $position = isset($data['position']) ? $data['position'] : '';
        $sTitle = isset($data['title']) ? $data['title'] : '';
        $sPic = isset($data['pic']) ? $data['pic'] : '';
        $sLinkType = isset($data['link_type']) ? $data['link_type'] : '';
        $sLink = isset($data['link']) ? $data['link'] : '';
        $width = isset($data['width']) ? $data['width'] : '';
        $sRightMargin = isset($data['right_margin']) ? $data['right_margin'] : '';
        $sExpandFlag = isset($data['expand_flag']) ? $data['expand_flag'] : '';
        $sExpandPic = isset($data['expand_pic']) ? $data['expand_pic'] : '';
        $sExpandPicDesc = isset($data['expand_pic_desc']) ? $data['expand_pic_desc'] : '';
        $iSequence = isset($data['sequence']) ? $data['sequence'] : '';
//        $status=isset($data['status'])?$data['status']:'';


        if ($iId != '') {
            $oQrCode = FloatWindow::find($iId);
        } else {
            $oQrCode = new FloatWindow();
        }

//        $oQrCode->id = $iId;
//        $oQrCode->merchant_id = $iMerchantId;
        $oQrCode->position = $position;
        $oQrCode->title = $sTitle;
        $oQrCode->pic = $sPic;
        $oQrCode->link_type = $sLinkType;
        $oQrCode->link = $sLink;
        $oQrCode->width = $width;
        $oQrCode->right_margin = $sRightMargin;
        $oQrCode->expand_flag = $sExpandFlag;
        $oQrCode->expand_pic = $sExpandPic;
        $oQrCode->expand_pic_desc = $sExpandPicDesc;
        $oQrCode->sequence = $iSequence;
//        $oQrCode->status = $status;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'floatWindowSave';
        $sLogContent = 'floatWindowSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function informationSave()
    {
        $data = request()->post();


        $iId = isset($data['id']) ? $data['id'] : '';
        $sTitle = isset($data['title']) ? $data['title'] : '';
        $iSequence = isset($data['sequence']) ? $data['sequence'] : '';
        $status = isset($data['status']) ? $data['status'] : '';
        $sType = isset($data['type']) ? $data['type'] : '';
        $sContent = isset($data['content']) ? $data['content'] : '';

        if ($iId != '') {
            $oQrCode = Information::find($iId);
            $oQrCode->updated_at = now();
        } else {
            $oQrCode = new Information();
            $oQrCode->created_at = now();
        }


//        $oQrCode->merchant_id = $iMerchantId;
        $oQrCode->title = $sTitle;
        $oQrCode->sequence = $iSequence;
        $oQrCode->status = $status;
        $oQrCode->type = $sType;

        $oQrCode->content = $sContent;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'informationSave';
        $sLogContent = 'informationSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function companySave()
    {
        $data = request()->post();


        $iId = isset($data['id']) ? $data['id'] : '';
        $sDisplayStatus = isset($data['status']) ? $data['status'] : '';
        $sDisplayStyle = isset($data['display_style']) ? $data['display_style'] : '';
        $sContent = isset($data['content']) ? $data['content'] : '';

        if ($iId != '') {
            $oQrCode = Company::find($iId);
        } else {
            $oQrCode = new Company();
        }


        $oQrCode->id = $iId;
//        $oQrCode->merchant_id = $iMerchantId;
        $oQrCode->status = $sDisplayStatus;
        $oQrCode->display_style = $sDisplayStyle;
        $oQrCode->content = $sContent;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'companySave';
        $sLogContent = 'companySave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


    /**
     * 二维码设置数据列表
     * @param request
     * @return json
     */
    public function qrconfigList()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $oQrconfigList = DB::table('site_qr_code');
        if ($sMerchantName != '') {
            $oQrconfigList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        $iLimit = request()->get('limit', 20);
        $oQrconfigFinalList = $oQrconfigList->orderby('id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($oQrconfigFinalList);
        $res["list"] = $oQrconfigFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
        $sOperateName = 'qrconfigList';
        $sLogContent = 'qrconfigList';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * 首页轮播图数据列表
     * @param request
     * @return json
     */
    public function rotationconfigList()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';

        $iStatus = isset(request()->status) ? request()->status : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $oRotationconfigList = DB::table('site_rotate_play');
        if ($sMerchantName != '') {
            $oRotationconfigList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        if ($iStatus !== '') {
            $oRotationconfigList->where('status', $iStatus);
        }
        $iLimit = request()->get('limit', 20);
        $oRotationconfigFinalList = $oRotationconfigList->orderby('id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($oRotationconfigFinalList);
        $res["list"] = $oRotationconfigFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
        $sOperateName = 'rotationconfigList';
        $sLogContent = 'rotationconfigList';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * 网站图标设置数据列表
     * @param request
     * @return json
     */
    public function systemconfigImagelist()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $oSystemconfigImageList = DB::table('site_web_icon');
        if ($sMerchantName != '') {
            $oSystemconfigImageList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        $iLimit = request()->get('limit', 20);
        $oSystemconfigImageFinalList = $oSystemconfigImageList->orderby('id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($oSystemconfigImageFinalList);
        $res["list"] = $oSystemconfigImageFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
        $sOperateName = 'systemconfigImagelist';
        $sLogContent = 'systemconfigImagelist';

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
    public function systemconfigSet()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
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
        $oAuthAdminFinalList = $oAuthAdminList->skip(($sIpage - 1) * $iLimit)->take($iLimit)->get();
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


        $sOperateName = 'systemconfigSet';
        $sLogContent = 'systemconfigSet';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function informationStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = Information::find($iId);
        if (is_object($oEvent)) {
            $iStatue = $oEvent->status;
        }
        $oEvent->status = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'informationStatusSave';
        $sLogContent = 'informationStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }

    /**
     * 数据删除
     * @param request
     * @return json
     */
    public function rotationconfigDelete()
    {
//        $iId = request()->post('id/d');
        $iId = request()->all()['id'];
        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$iId)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $iId)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        RotatePlay::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'rotationconfigDelete';
        $sLogContent = 'rotationconfigDelete';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success();

    }

    /**
     * 数据删除
     * @param request
     * @return json
     */
    public function floatwindowconfigDelete()
    {
//        $iId = request()->post('id/d');
        $iId = request()->all()['id'];
        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$iId)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $iId)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        FloatWindow::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'floatwindowconfigDelete';
        $sLogContent = 'floatwindowconfigDelete';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success();

    }

    /**
     * 数据删除
     * @param request
     * @return json
     */
    public function informationDelete()
    {
//        $iId = request()->post('id/d');
        $iId = request()->all()['id'];
        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$iId)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $iId)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        Information::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'informationDelete';
        $sLogContent = 'informationDelete';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success();

    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function updateLotterygroupSequence()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oEvent = LotteryGroup::find($iId);
                $oEvent->sequence = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'updateLotterygroupSequence';
                $sLogContent = 'updateLotterygroupSequence';
                $dt = now();
                AdminLog::adminLogSave($sOperateName);
                return response()->json($aFinal);
            }
        }
        catch (\Exception $e) {
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
            return response()->json($aFinal);
        }
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function updateInformationSequence()
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';

        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oEvent = Information::find($iId);
                $oEvent->sequence = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'updateInformationSequence';
                $sLogContent = 'updateInformationSequence';
                $dt = now();
                AdminLog::adminLogSave($sOperateName);
                return response()->json($aFinal);
            }
        }
        catch (\Exception $e) {
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
            return response()->json($aFinal);
        }
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function updateRotateSequence($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oEvent = RotatePlay::find($iId);
                $oEvent->sequence = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'updateRotateSequence';
                $sLogContent = 'updateRotateSequence';
                $dt = now();
                AdminLog::adminLogSave($sOperateName);
                return response()->json($aFinal);
            }
        }
        catch (\Exception $e) {
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
            return response()->json($aFinal);
        }
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function updatefloatwindowSequence()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';

        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {

                $oEvent = FloatWindow::find($iId);
                $oEvent->sequence = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;

                $sOperateName = 'updatefloatwindowSequence';
                $sLogContent = 'updatefloatwindowSequence';

                $dt = now();
                AdminLog::adminLogSave($sOperateName);
                return response()->json($aFinal);
            }
        }
        catch (\Exception $e) {
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
            return response()->json($aFinal);
        }
    }


    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function updateLotteryGroupPropertySave($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $hot = isset($data['hot']) ? $data['hot'] : '';
        $recommand = isset($data['recommand']) ? $data['recommand'] : '';
        $sNew = isset($data['new']) ? $data['new'] : '';

        $sFirst1 = substr($hot, 0, 1);
        $sFirst2 = substr($recommand, 0, 1);
        $sFirst3 = substr($sNew, 0, 1);
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

        try
        {
            if($this->validate(request(),Common::$propertySaveRules,Common::$propertySaveMessages)) {

                $oEvent = LotteryGroup::find($iId);
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

                if ($sNew != '') {
                    if ($bFlag3) {
                        $oEvent->new = substr($sNew, 1, strlen($sNew));
                    } else {
                        $oEvent->new = '';
                    }
                }

                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'updateLotteryGroupPropertySave';
                $sLogContent = 'updateLotteryGroupPropertySave';
                $dt = now();
                AdminLog::adminLogSave($sOperateName);
                return response()->json($aFinal);
            }
        }
        catch (\Exception $e) {
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
            return response()->json($aFinal);
        }
    }


    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function informationIsTopSave($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        $oEvent = Information::find($iId);
        if (is_object($oEvent)) {
            $iStatue = $oEvent->status;
        }
//        $iFlag = $iStatue == 0 ? 1 : 0;
        $oEvent->is_top = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'informationIsTopSave';
        $sLogContent = 'informationIsTopSave';

        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


}