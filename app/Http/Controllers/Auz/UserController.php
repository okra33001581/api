<?php

namespace App\Http\Controllers;

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

use App\model\UserLevel;
use App\model\User;
use App\model\UserBankCard;
use App\model\UserSafetyAudit;
use App\model\Quota;

use App\model\UserLock;
use App\model\AdminLog;

/**
 * Class Event - 用户登录相关控制器
 * @author zebra
 */
class UserController extends Controller
{

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function out()
    {
        $aData['code'] = 0;
        $aData['message'] = 'success';
        return response()->json($aData);
    }

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function loginIndex()
    {
//        if(!request()->isMethod('post')){
//            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
//        }
        $user_name = request()->post('userName');
        $pwd = request()->post('pwd');
        if (!$user_name || !$pwd) {
//            return ResultVo::error(ErrorCode::VALIDATION_FAILED, "username 不能为空。 password 不能为空。");
        }

//        $user_name = 'admin';
        $user_name = request()->all()['userName'];
        $admin = AuthAdmin::where('username', $user_name)
            ->first();
        if (empty($admin) || PassWordUtils::create($pwd) != $admin->password) {
//            return ResultVo::error(ErrorCode::USER_AUTH_FAIL);
        }
        if ($admin->status != 1) {
            return ResultVo::error(ErrorCode::USER_NOT_PERMISSION);
        }
        $info = $admin->toArray();
        unset($info['password']);
        // 权限信息
        $authRules = [];
        if ($user_name == 'admin') {
            $authRules = ['admin'];
        } else {

            Log::info($admin);

//            $oAuthRoleAdminList = AuthRoleAdmin::where('admin_id', $admin->id)->select('role_id')->get();
            $oAuthRoleAdminList = AuthRoleAdmin::where('admin_id', $admin->id)->first();
            if (is_object($oAuthRoleAdminList)) {
//                $oAuthPermissionList = AuthPermission::where('role_id', 'in', $oAuthRoleAdminList->role_id)
//                    ->select(['permission_rule_id'])
//                    ->get();
//                $aTmp =
                $oAuthPermissionList = AuthPermission::where('role_id', '=', $oAuthRoleAdminList->role_id)
                    ->get();
                foreach ($oAuthPermissionList as $oAuthPermission) {
//                    $oAuthPermissionRule = AuthPermissionRule::where('id', $oAuthPermission->permission_rule_id)->select('name')->first();
                    $oAuthPermissionRule = AuthPermissionRule::where('id', $oAuthPermission->permission_rule_id)->first();
                    if (is_object($oAuthPermissionRule)) {
                        $authRules[] = $oAuthPermissionRule->name;
                    }
                }
            }
        }

        Log::info($authRules);
        $info['authRules'] = $authRules;
        // 保存用户信息
        $loginInfo = AuthAdmin::loginInfo($info['id'], $info);
        $admin->last_login_ip = request()->ip();
        $admin->last_login_time = date("Y-m-d H:i:s");
        $admin->save();
        $res = [];
        $res['id'] = !empty($loginInfo['id']) ? intval($loginInfo['id']) : 0;
        $res['token'] = !empty($loginInfo['token']) ? $loginInfo['token'] : '';

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
//
//
//
//        $sOperateName = 'loginIndex';
//        $sLogContent = 'loginIndex';
//
//
//        $dt = now();
//
//
//
//        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function loginInfo()
    {
        $iId = request()->header('X-Adminid');
        $token = request()->header('X-Token');

        if (!$iId || !$token) {
//            return ResultVo::error(ErrorCode::LOGIN_FAILED);
        }
        $res = AuthAdmin::loginInfo($iId, (string)$token);

        $res['id'] = !empty($res['id']) ? intval($res['id']) : 0;
        $res['avatar'] = !empty($res['avatar']) ? PublicFileUtils::createUploadUrl($res['avatar']) : '';
        // $res['roles'] = ['admin'];

        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


//
//        $sOperateName = 'loginInfo';
//        $sLogContent = 'loginInfo';
//
//
//        $dt = now();
//
//
//
//        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function userInfolist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $sMerchantName= isset(request()->merchant_name) ? request()->merchant_name : '';
        $sUserType= isset(request()->user_type) ? request()->user_type : '';
        $sUsername= isset(request()->username) ? request()->username : '';
        $sGroup= isset(request()->group) ? request()->group : '';
        $sUserLevel= isset(request()->user_level) ? request()->user_level : '';
        $dtBeginDate= isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate= isset(request()->endDate) ? request()->endDate : '';
        $sOperateType= isset(request()->operate_type) ? request()->operate_type : '';
        $iIdays= isset(request()->days) ? request()->days : '';

        $oAuthAdminList = DB::table('user_user');

        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

      if ($sUserType != '') {
          $oAuthAdminList->where('account_type', '=', $sUserType);
      }

       if ($sUsername != '') {
           $oAuthAdminList->where('username', 'like', '%' . $sUsername . '%');
       }


        if ($sGroup != '') {
            $oAuthAdminList->where('group', '=', $sGroup);
        }


        if ($sUserLevel != '') {
            $oAuthAdminList->where('user_level', '=', $sUserLevel);
        }

        if ($dtBeginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtBeginDate);
        }

        if ($dtEndDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtEndDate);
        }

         if ($iIdays != '') {
             $dtTmp = date('Y-m-d', strtotime('- '.$iIdays.' day'));
         }

        switch ($sOperateType) {
            case '小于':
                $oAuthAdminList->where('last_login_date', '<=', $dtTmp);
                break;
            case '等于':
                $oAuthAdminList->where('last_login_date', '=', $dtTmp);
                break;
            case '大于':
                $oAuthAdminList->where('last_login_date', '>=', $dtTmp);
                break;
        }

        if ($sGroup == '用户名') {
            $aUserTmp = explode(",",$sUsername);
            $oAuthAdminList->whereIn("username", $aUserTmp);
        } elseif ($sGroup == '所属上级') {
            if ($sUsername != '') {
                $oAuthAdminList->where('top_level', 'like', '%' . $sUsername . '%');
            }

        }
        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['icon'] = $oAuthAdmin->icon;
            $aTmp['account_type'] = $oAuthAdmin->account_type;
            $aTmp['top_level'] = $oAuthAdmin->top_level;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['password'] = $oAuthAdmin->password;
            $aTmp['nickname'] = $oAuthAdmin->nickname;
            $aTmp['memo'] = $oAuthAdmin->memo;
            $aTmp['rake_setting'] = $oAuthAdmin->rake_setting;
            $aTmp['user_level'] = $oAuthAdmin->user_level;
            $aTmp['email'] = $oAuthAdmin->email;
            $aTmp['tel'] = $oAuthAdmin->tel;
            $aTmp['weixin'] = $oAuthAdmin->weixin;
            $aTmp['fund_password'] = $oAuthAdmin->fund_password;
            $aTmp['realname'] = $oAuthAdmin->realname;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'userInfolist';
        $sLogContent = 'userInfolist';


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
    public function userSave()
    {
        $data = request()->post();

        $iId=isset($data['id'])?$data['id']:'';

        $sIcon=isset($data['icon'])?$data['icon']:'';
        $sAccountType=isset($data['account_type'])?$data['account_type']:'';
        $sTopLevel=isset($data['top_level'])?$data['top_level']:'';
        $sAccount=isset($data['account'])?$data['account']:'';
        $sPassword=isset($data['password'])?$data['password']:'';
        $sNickname=isset($data['nickname'])?$data['nickname']:'';
        $sMemo=isset($data['memo'])?$data['memo']:'';
        $sRakeSetting=isset($data['rake_setting'])?$data['rake_setting']:'';
        $sUserLevel=isset($data['user_level'])?$data['user_level']:'';
        $email=isset($data['email'])?$data['email']:'';
        $tel=isset($data['tel'])?$data['tel']:'';
        $weixin=isset($data['weixin'])?$data['weixin']:'';
        $sFundPassword=isset($data['fund_password'])?$data['fund_password']:'';
        $sRealname=isset($data['realname'])?$data['realname']:'';


        if ($iId != '') {
            $oQrCode = User::find($iId);
        } else {
            $oQrCode = new User();
        }



//        $oQrCode->id = $iId;

        $oQrCode->icon=$sIcon;
        $oQrCode->account_type=$sAccountType;
        $oQrCode->top_level=$sTopLevel;
        $oQrCode->account=$sAccount;
        $oQrCode->password=$sPassword;
        $oQrCode->nickname=$sNickname;
        $oQrCode->memo=$sMemo;
        $oQrCode->rake_setting=$sRakeSetting;
        $oQrCode->user_level=$sUserLevel;
        $oQrCode->email=$email;
        $oQrCode->tel=$tel;
        $oQrCode->weixin=$weixin;
        $oQrCode->fund_password=$sFundPassword;
        $oQrCode->realname=$sRealname;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'userSave';
        $sLogContent = 'userSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function userLevelSave()
    {
        $data = request()->post();


        $sLevelName=isset($data['level_name'])?$data['level_name']:'';
        $sDepositTimes=isset($data['deposit_times'])?$data['deposit_times']:'';
        $iIdepositAmount=isset($data['deposit_amount'])?$data['deposit_amount']:'';
        $fDepositMax=isset($data['deposit_max'])?$data['deposit_max']:'';
        $iWithdrawTimes=isset($data['withdraw_times'])?$data['withdraw_times']:'';
        $fWithdrawAmount=isset($data['withdraw_amount'])?$data['withdraw_amount']:'';
        $sPrior=isset($data['prior'])?$data['prior']:'';
        $sMemo=isset($data['memo'])?$data['memo']:'';
        $sPaySetting=isset($data['pay_setting'])?$data['pay_setting']:'';
        $sIprojectLimit=isset($data['project_limit'])?$data['project_limit']:'';


        $oQrCode = new UserLevel();


//        $oQrCode->id = $iId;


        $oQrCode->level_name=$sLevelName;
        $oQrCode->deposit_times=$sDepositTimes;
        $oQrCode->deposit_amount=$iIdepositAmount;
        $oQrCode->deposit_max=$fDepositMax;
        $oQrCode->withdraw_times=$iWithdrawTimes;
        $oQrCode->withdraw_amount=$fWithdrawAmount;
        $oQrCode->prior=$sPrior;
        $oQrCode->memo=$sMemo;
        $oQrCode->pay_setting=$sPaySetting;
        $oQrCode->project_limit=$sIprojectLimit;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'userLevelSave';
        $sLogContent = 'userLevelSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }



    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function bankCardSave()
    {
        $data = request()->post();


        $status=isset($data['status'])?$data['status']:'';
        $sAccount=isset($data['account'])?$data['account']:'';
        $sTopName=isset($data['top_name'])?$data['top_name']:'';
        $bank=isset($data['bank'])?$data['bank']:'';
        $sProvinceCity=isset($data['province_city'])?$data['province_city']:'';
        $iCardNumber=isset($data['card_number'])?$data['card_number']:'0';
        $sBranchName=isset($data['branch_name'])?$data['branch_name']:'';
        $sRealName=isset($data['real_name'])?$data['real_name']:'';
        $sIsBlack=isset($data['is_black'])?$data['is_black']:'';
        $fTotalAmount=isset($data['total_amount'])?$data['total_amount']:'';
        $dtCreatedAt=isset($data['created_at'])?$data['created_at']:'';

        $oQrCode = new UserBankCard();


//        $oQrCode->id = $iId;



        $oQrCode->status=$status;
        $oQrCode->account=$sAccount;
        $oQrCode->top_name=$sTopName;
        $oQrCode->bank=$bank;
        $oQrCode->province_city=$sProvinceCity;
        $oQrCode->card_number=$iCardNumber;
        $oQrCode->branch_name=$sBranchName;
        $oQrCode->real_name=$sRealName;
        $oQrCode->is_black=$sIsBlack;
        $oQrCode->total_amount=$fTotalAmount;
        $oQrCode->created_at=date("Y-m-d H:i:s");;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'bankCardSave';
        $sLogContent = 'bankCardSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function userInoutcash()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sType = isset(request()->type) ? request()->type : '';
        $sSelectUserType = isset(request()->select_user_type) ? request()->select_user_type : '';
        $sKeywords = isset(request()->keywords) ? request()->keywords : '';
        $sUserType = isset(request()->user_type) ? request()->user_type : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';

        $oAuthAdminList = DB::table('user_in_out_statics');



        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sType != '') {
            $oAuthAdminList->where('method', '=', $sType);
        }


        if ($sSelectUserType == '用户名') {
            if ($sKeywords != '') {
                $oAuthAdminList->where('username', 'like', '%' . $sKeywords . '%');
            }
        } elseif ($sSelectUserType == '所属上级') {
            if ($sKeywords != '') {
                $oAuthAdminList->where('top_agent', 'like', '%' . $sKeywords . '%');
            }

        }


        if ($sUserType != '') {
            $oAuthAdminList->where('user_type', '=', $sUserType);
        }

        if ($dtBeginDate != '') {
            $oAuthAdminList->where('login_date', '>=', $dtBeginDate);
        }

        if ($dtEndDate != '') {
            $oAuthAdminList->where('login_date', '>=', $dtEndDate);
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['register_date'] = $oAuthAdmin->register_date;
            $aTmp['login_date'] = $oAuthAdmin->login_date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['user_type'] = $oAuthAdmin->user_type;
            $aTmp['top_agent'] = $oAuthAdmin->top_agent;
            $aTmp['realname'] = $oAuthAdmin->realname;
            $aTmp['final_amount'] = $oAuthAdmin->final_amount;
            $aTmp['deposit_count'] = $oAuthAdmin->deposit_count;
            $aTmp['withdraw_count'] = $oAuthAdmin->withdraw_count;
            $aTmp['deposit_amount'] = $oAuthAdmin->deposit_amount;
            $aTmp['withdraw_amount'] = $oAuthAdmin->withdraw_amount;
            $aTmp['benefit_amount'] = $oAuthAdmin->benefit_amount;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'userInoutcash';
        $sLogContent = 'userInoutcash';


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
    public function userMainlist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $sMerchantName= isset(request()->merchant_name) ? request()->merchant_name : '';
        $sUsername= isset(request()->username) ? request()->username : '';
        $sGroup= isset(request()->group) ? request()->group : '';
        $status= isset(request()->status) ? request()->status : '';
        $sUserLevel= isset(request()->user_level) ? request()->user_level : '';
        $fSelectTypeAmount= isset(request()->select_type_amount) ? request()->select_type_amount : '';
        $iMin= isset(request()->min) ? request()->min : '';
        $iMax= isset(request()->max) ? request()->max : '';
        $dtBeginDate= isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate= isset(request()->endDate) ? request()->endDate : '';
        $sOnlineStatus= isset(request()->online_status) ? request()->online_status : '';
        $sSelectOperateType= isset(request()->select_operate_type) ? request()->select_operate_type : '';
        $iIdays= isset(request()->days) ? request()->days : '';


        $oAuthAdminList = DB::table('user_user');


        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sGroup == '用户名') {
            if ($sUsername != '') {
                $aUserTmp = explode(",",$sUsername);
                $oAuthAdminList->whereIn("username", $aUserTmp);
            }
        } elseif ($sGroup == '所属上级') {
            if ($sUsername != '') {
                $oAuthAdminList->where('top_level', 'like', '%' . $sUsername . '%');
            }

        }

        if ($sGroup != '') {
            $oAuthAdminList->where('group', '=', $sGroup);
        }


        if ($status != '') {
            $oAuthAdminList->where('status', '=', $status);
        }

        if ($sUserLevel != '') {
            $oAuthAdminList->where('user_level', '=', $sUserLevel);
        }


        if ($dtBeginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtBeginDate);
        }

        if ($dtEndDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtEndDate);
        }


        if ($sOnlineStatus != '') {
            $oAuthAdminList->where('online_status', '=', $sOnlineStatus);
        }


        if ($sSelectOperateType != '') {
            $oAuthAdminList->where('last_login_date', '=', $sSelectOperateType);
        }


        if ($iIdays != '') {
            $dtTmp = date('Y-m-d', strtotime('- '.$iIdays.' day'));
        }

        switch ($sSelectOperateType) {
            case '小于':
                $oAuthAdminList->where('last_login_date', '<=', $dtTmp);
                break;
            case '等于':
                $oAuthAdminList->where('last_login_date', '=', $dtTmp);
                break;
            case '大于':
                $oAuthAdminList->where('last_login_date', '>=', $dtTmp);
                break;
        }
        if($iLimit!=9999){
            $iLimit = request()->get('limit/d', 20);
        }
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['icon'] = $oAuthAdmin->icon;
            $aTmp['account_type'] = $oAuthAdmin->account_type;
            $aTmp['top_level'] = $oAuthAdmin->top_level;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['password'] = $oAuthAdmin->password;
            $aTmp['nickname'] = $oAuthAdmin->nickname;
            $aTmp['memo'] = $oAuthAdmin->memo;
            $aTmp['rake_setting'] = $oAuthAdmin->rake_setting;
            $aTmp['user_level'] = $oAuthAdmin->user_level;
            $aTmp['email'] = $oAuthAdmin->email;
            $aTmp['tel'] = $oAuthAdmin->tel;
            $aTmp['weixin'] = $oAuthAdmin->weixin;
            $aTmp['fund_password'] = $oAuthAdmin->fund_password;
            $aTmp['realname'] = $oAuthAdmin->realname;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'userMainlist';
        $sLogContent = 'userMainlist';


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
    public function userMonitor()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sSelectUsernameType = isset(request()->select_username_type) ? request()->select_username_type : '';
        $sUsername = isset(request()->username) ? request()->username : '';

        $oAuthAdminList = DB::table('user_monitor');


        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }


        if ($sSelectUsernameType != '') {
            $oAuthAdminList->where('type', '=', $sSelectUsernameType);
        }


        if ($sUsername != '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUsername . '%');
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['duplicate_project'] = $oAuthAdmin->duplicate_project;
            $aTmp['duplicate_value'] = $oAuthAdmin->duplicate_value;
            $aTmp['duplicate_people_count'] = $oAuthAdmin->duplicate_people_count;
            $aTmp['duplicate_user'] = $oAuthAdmin->duplicate_user;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'userMonitor';
        $sLogContent = 'userMonitor';


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
    public function userReviewlist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sType = isset(request()->type) ? request()->type : '';
        $status = isset(request()->status) ? request()->status : '';
        $sUsername = isset(request()->username) ? request()->username : '';
        $sSelectUserType = isset(request()->select_user_type) ? request()->select_user_type : '';
        $sUsername2 = isset(request()->username2) ? request()->username2 : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';


        $oAuthAdminList = DB::table('user_safety_audit');



        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sType != '') {
            $oAuthAdminList->where('type', '=', $sType);
        }


        if ($status != '') {
            $oAuthAdminList->where('status', '=', $status);
        }


        if ($sUsername != '') {
            $oAuthAdminList->where('account', '=', $sUsername);
        }

        if ($sSelectUserType == '提交人') {
            if ($sUsername2 != '') {
                $oAuthAdminList->where('requestor', '=', $sUsername2);
            }
        } elseif ($sSelectUserType == '审核人') {
            if ($sUsername2 != '') {
                $oAuthAdminList->where('auditor', '=', $sUsername2);
            }
        }

        if ($dtBeginDate != '') {
            $oAuthAdminList->where('submit_date', '>=', $dtBeginDate);
        }

        if ($dtEndDate != '') {
            $oAuthAdminList->where('submit_date', '>=', $dtEndDate);
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['submit_date'] = $oAuthAdmin->submit_date;
            $aTmp['audit_date'] = $oAuthAdmin->audit_date;
            $aTmp['ip'] = $oAuthAdmin->ip;
            $aTmp['type'] = $oAuthAdmin->type;
            $aTmp['edit_info'] = $oAuthAdmin->edit_info;
            $aTmp['requestor'] = $oAuthAdmin->requestor;
            $aTmp['auditor'] = $oAuthAdmin->auditor;
            $aTmp['memo'] = $oAuthAdmin->memo;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'userReviewlist';
        $sLogContent = 'userReviewlist';


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
    public function userUsercard()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $status=isset(request()->status)?request()->status:'';
        $sMerchantName=isset(request()->merchant_name)?request()->merchant_name:'';

        $sType=isset(request()->type)?request()->type:'';
        $sKeywords=isset(request()->keywords)?request()->keywords:'';
        $sRealname=isset(request()->realname)?request()->realname:'';
        $iMin=isset(request()->min)?request()->min:'';
        $iMax=isset(request()->max)?request()->max:'';
        $sIsBlack=isset(request()->is_black)?request()->is_black:'';

        $oAuthAdminList = DB::table('user_bankcard');

        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($status != '') {
            $oAuthAdminList->where('status', '=', $status);
        }


        if ($sType == '卡号') {
            if ($sKeywords != '') {
                $oAuthAdminList->where('account', 'like', '%' . $sKeywords . '%');
            }
        } elseif ($sType == '用户名') {
            if ($sKeywords != '') {
                $oAuthAdminList->where('card_number', 'like', '%' . $sKeywords . '%');
            }

        }


        if ($sRealname != '') {
            $oAuthAdminList->where('real_name', 'like', '%' . $sRealname . '%');
        }

        if ($iMin != '') {
            $oAuthAdminList->where('total_amount', '>=', $iMin);
        }

        if ($iMax != '') {
            $oAuthAdminList->where('total_amount', '>=', $iMax);
        }


        if ($sIsBlack != '') {
            $oAuthAdminList->where('is_black', '=', $sIsBlack);
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


       /* $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['top_name'] = $oAuthAdmin->top_name;
            $aTmp['bank'] = $oAuthAdmin->bank;
            $aTmp['province_city'] = $oAuthAdmin->province_city;
            $aTmp['card_number'] = $oAuthAdmin->card_number;
            $aTmp['branch_name'] = $oAuthAdmin->branch_name;
            $aTmp['real_name'] = $oAuthAdmin->real_name;
            $aTmp['is_black'] = $oAuthAdmin->is_black;
            $aTmp['total_amount'] = $oAuthAdmin->total_amount;
            $aTmp['created_at'] = $oAuthAdmin->created_at;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'userUsercard';
        $sLogContent = 'userUsercard';


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
    public function bankcardStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = UserBankCard::find($iId);
//        $iFlag = 0;
        if (is_object($oEvent)) {
            $iStatue = $oEvent->status;
        }
//        $iFlag = $iStatue == 0 ? 1 : 0;
        $oEvent->is_black = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'bankcardStatusSave';
        $sLogContent = 'bankcardStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function userStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = User::find($iId);
        if (is_object($oEvent)) {
            $iStatue = $oEvent->status;
        }
        $oEvent->status = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'userStatusSave';
        $sLogContent = 'userStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function userUserlayer()
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
        $oAuthAdminList = DB::table('user_level');

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

       /* $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['level_name'] = $oAuthAdmin->level_name;
            $aTmp['deposit_times'] = $oAuthAdmin->deposit_times;
            $aTmp['deposit_amount'] = $oAuthAdmin->deposit_amount;
            $aTmp['deposit_max'] = $oAuthAdmin->deposit_max;
            $aTmp['withdraw_times'] = $oAuthAdmin->withdraw_times;
            $aTmp['withdraw_amount'] = $oAuthAdmin->withdraw_amount;
            $aTmp['prior'] = $oAuthAdmin->prior;
            $aTmp['memo'] = $oAuthAdmin->memo;
            $aTmp['pay_setting'] = $oAuthAdmin->pay_setting;
            $aTmp['project_limit'] = $oAuthAdmin->project_limit;
            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'userUserlayer';
        $sLogContent = 'userUserlayer';


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
    public function userValiduser()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sAgentName = isset(request()->agent_name) ? request()->agent_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';

        $oAuthAdminList = DB::table('user_validuser');



        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sAgentName != '') {
            $oAuthAdminList->where('top_agent', 'like', '%' . $sAgentName . '%');
        }



        if ($dtBeginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtBeginDate);
        }

        if ($dtEndDate != '') {
            $oAuthAdminList->where('created_at', '<=', $dtEndDate);
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /* $aTmp = [];
         $aFinal = [];
         foreach ($oAuthAdminFinalList as $oAuthAdmin) {
             $aTmp['id'] = $oAuthAdmin->id;
             $aTmp['top_agent'] = $oAuthAdmin->top_agent;
             $aTmp['valid_user_count'] = $oAuthAdmin->valid_user_count;
             $aTmp['new_user_count'] = $oAuthAdmin->new_user_count;
             $aTmp['total_deposit_amount'] = $oAuthAdmin->total_deposit_amount;
             $aTmp['total_withdraw_amount'] = $oAuthAdmin->total_withdraw_amount;

             $aFinal[] = $aTmp;
         }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'userValiduser';
        $sLogContent = 'userValiduser';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

//    /*/**
//     * 数据取得
//     * @param request
//     * @return json
//     */
//    public function adminRoleList()
//    {
//        $sWhere = [];
//        $iLimit = request()->get('limit/d', 20);
//        //分页配置
////        $paginate = [
////            'type' => 'bootstrap',
////            'var_page' => 'page',
////            'list_rows' => ($iLimit <= 0 || $iLimit > 20) ? 20 : $iLimit,
////        ];
//        $iTmp = ($iLimit <= 0 || $iLimit > 20) ? 20 : $iLimit;
//        $lists = AuthRole::where($sWhere)
//            ->paginate($iTmp);
//
//        $res = [];
//        $res["total"] = $lists->total();
//        $res["list"] = $lists->items();
//        return response()->json($res);
//        return ResultVo::success($res);
//    }
//
//    /**
//     * 数据取得
//     * @param request
//     * @return json
//     */
//    public function adminSave()
//    {
//        $data = request()->post();
//        if (empty($data['username']) || empty($data['password'])) {
//            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
//        }
//        $sUsername = $data['username'];
//        // 模型
////        $info = AuthAdmin::where('username',$sUsername)
////            ->field('username')
////            ->find();
//
//        $oAuthAdmin = AuthAdmin::where('username', $sUsername)
//            ->first();
//
////        if ($oAuthAdmin){
////            return ResultVo::error(ErrorCode::DATA_REPEAT);
////        }
//
//        $status = isset($data['status']) ? $data['status'] : 0;
//        $auth_admin = new AuthAdmin();
//        $auth_admin->username = $sUsername;
//        $auth_admin->password = PassWordUtils::create($data['password']);
//        $auth_admin->status = $status;
//        $auth_admin->create_time = date("Y-m-d H:i:s");
//        $result = $auth_admin->save();
//
//        if (!$result) {
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
//
//        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];
//
//        //$adminInfo = $this->adminInfo; // 登录用户信息
//        $admin_id = $auth_admin->id;
//        if ($roles) {
//            $temp = [];
//            foreach ($roles as $key => $value) {
//                $temp[$key]['role_id'] = $value;
//                $temp[$key]['admin_id'] = $admin_id;
//            }
//            //添加用户的角色
//
//            if (count($temp) > 0) {
//                foreach ($temp as $k => $v) {
//                    $oAuthRoleAdmin = new AuthRoleAdmin();
//                    $oAuthRoleAdmin->role_id = $v['role_id'];
//                    $oAuthRoleAdmin->admin_id = $v['admin_id'];
//                    $iRet = $oAuthRoleAdmin->save();
//                }
//            }
////            $oAuthRoleAdmin->saveAll($temp);
//        }
//
//        $auth_admin['password'] = '';
//        $auth_admin['roles'] = $roles;
//
//        $aFinal['message'] = 'success';
//        $aFinal['code'] = 0;
//        $aFinal['data'] = $auth_admin;
//
//
//        $sOperateName = 'adminSave';
//        $sLogContent = 'adminSave';
//
//
//        $dt = now();
//
//
//
//        AdminLog::adminLogSave($sOperateName);
//
//        return response()->json($aFinal);
//        return ResultVo::success($auth_admin);
//    }
//
//    /**
//     * 数据取得
//     * @param request
//     * @return json
//     */
//    public function adminEdit()
//    {
//        $data = request()->post();
//
//
////        Log::info($data);
//        $aRoles = $data['roles'];
//
//        if (empty($data['id']) || empty($data['username'])) {
//            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
//        }
//        $iId = $data['id'];
//        $sUsername = strip_tags($data['username']);
//        // 模型
////        $auth_admin = AuthAdmin::where('id',$iId)
////            ->field('id,username')
////            ->find();
//        $oAuthAdmin = AuthAdmin::where('id', $iId)
//            ->first();
//
//        if (!$oAuthAdmin) {
//            return ResultVo::error(ErrorCode::DATA_NOT, "商户不存在");
//        }
//        $login_info = $oAuthAdmin;
//        $login_user_name = isset($login_info['username']) ? $login_info['username'] : '';
//        // 如果是超级商户，判断当前登录用户是否匹配
//        if ($oAuthAdmin->username == 'admin' && $login_user_name != $oAuthAdmin->username) {
//            return ResultVo::error(ErrorCode::DATA_NOT, "最高权限用户，无权修改");
//        }
//
////        $info = AuthAdmin::where('username',$sUsername)
////            ->field('id')
////            ->find();
//
//        $info = AuthAdmin::where('username', $sUsername)
//            ->first();
//
//        // 判断username 是否重名，剔除自己
////        if (!empty($info['id']) && $info['id'] != $iId){
////            return ResultVo::error(ErrorCode::DATA_REPEAT, "商户已存在");
////        }
//
//        $status = isset($data['status']) ? $data['status'] : 0;
//        $sPassword = isset($data['password']) ? PassWordUtils::create($data['password']) : '';
//        $oAuthAdmin->username = $sUsername;
//        if ($sPassword) {
//            $oAuthAdmin->password = $sPassword;
//        }
//        $oAuthAdmin->status = $status;
////        $oAuthAdmin->role_id = implode(",", $aRoles);
//
//        $result = $oAuthAdmin->save();
//
//        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];
//        if (!$result) {
//            // 没有做任何更改
//            $oAuthRoleAdmin = AuthRoleAdmin::where('admin_id', $iId)->field('role_id')->select();
//            if ($oAuthRoleAdmin) {
//                $oAuthRoleAdmin = $oAuthRoleAdmin->toArray();
//                $oAuthRoleAdmin = array_column($oAuthRoleAdmin, 'role_id');
//            }
//            // 没有差值，权限也没做更改
//            if ($roles == $oAuthRoleAdmin) {
//                return ResultVo::error(ErrorCode::DATA_CHANGE);
//            }
//        }
//
//
//        if ($roles) {
//            // 先删除
//            AuthRoleAdmin::where('admin_id', $iId)->delete();
//            $temp = [];
//            foreach ($roles as $key => $value) {
//                $temp[$key]['role_id'] = $value;
//                $temp[$key]['admin_id'] = $iId;
//            }
//
//
//            //添加用户的角色
//            $oAuthRoleAdmin = new AuthRoleAdmin();
//
//            if (count($temp) > 0) {
//                foreach ($temp as $k => $v) {
//                    $oAuthPermission = new AuthRoleAdmin();
//                    $oAuthPermission->role_id = $v['role_id'];
//                    $oAuthPermission->admin_id = $v['admin_id'];
//                    $result = $oAuthPermission->save();
//                    if (!$result) {
//                        return ResultVo::error(ErrorCode::NOT_NETWORK);
//                    }
//                }
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//            }
//
//        }
//
//        $aFinal['message'] = 'success';
//        $aFinal['code'] = 0;
////        $aFinal['data'] = $res;
//
//
//        $sOperateName = 'adminEdit';
//        $sLogContent = 'adminEdit';
//
//
//        $dt = now();
//
//
//
//        AdminLog::adminLogSave($sOperateName);
//
//        return response()->json($aFinal);
//
//        return ResultVo::success();
//    }
//
//    /**
//     * 数据取得
//     * @param request
//     * @return json
//     */
//    public function adminDelete()
//    {
////        $iId = request()->post('id/d');
//        $iId = request()->all()['id'];
//        if ($iId == '') {
//            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
//        }
////        $auth_admin = AuthAdmin::where('id',$iId)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $iId)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
//        // 删除权限
//        AuthRoleAdmin::where('admin_id', $iId)->delete();
//
//        $aFinal['message'] = 'success';
//        $aFinal['code'] = 0;
////        $aFinal['data'] = $res;
//
//
//        $sOperateName = 'adminDelete';
//        $sLogContent = 'adminDelete';
//
//
//        $dt = now();
//
//
//
//        AdminLog::adminLogSave($sOperateName);
//
//        return response()->json($aFinal);
//        return ResultVo::success();
//
//    }*/

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function usersafetyStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = UserSafetyAudit::find($iId);
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


        $sOperateName = 'usersafetyStatusSave';
        $sLogContent = 'usersafetyStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 数据删除
     * @param request
     * @return json
     */
    public function userlayerDelete()
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
        UserLevel::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'userlayerDelete';
        $sLogContent = 'userlayerDelete';


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
    public function usercardDelete()
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
        UserBankCard::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'usercardDelete';
        $sLogContent = 'usercardDelete';


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
    public function userQuotaSave()
    {

        $data = request()->post();

//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);

//        {"rules":{"name":{"type":"string","required":true,"message":"必填字段","trigger":"change"},"input":{"type":"string","required":true,"message":"必填字段","trigger":"change"},"supplier":{"type":"string","required":true,"message":"必填字段","trigger":"change"},"goodstatus":{"type":"string","required":true,"message":"必填字段","trigger":"change"},"producedate.start":{"type":"string","required":true,"message":"必填字段","trigger":"change"},"expireddate.start":{"type":"string","required":true,"message":"必填字段","trigger":"change"}},"tableData":[{"rebate_level":"00","topallen_valid_count":"11","topallen_left_count":"22","left_count":"33","quota":"44"},{"rebate_level":"55","topallen_valid_count":"66","topallen_left_count":"77","left_count":"88","quota":"99"}]}

        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/

        $iUserId = isset($data['user_id']) ? $data['user_id'] : '';
        $aTableData = isset($data['tableData']) ? $data['tableData'] : '';


        $oUser = User::find($iUserId);

        foreach ($aTableData as $k=>$v) {
            $rebate_level = $v['rebate_level'];
            $topallen_valid_count = $v['topallen_valid_count'];
            $topallen_left_count = $v['topallen_left_count'];
            $left_count = $v['left_count'];
            $oQuotaValue = $v['quota'];
            $oQuota = Quota::where('user_id', '=', $iUserId)->first();
            if (is_object($oQuota)) {
            } else {
                $oQuota = new Quota();
                $oQuota->user_id = $iUserId;
            }
            $oQuota->username = $oUser->realname;
            $oQuota->rebate_level = $rebate_level;
            $oQuota->rebate_level = $rebate_level;
            $oQuota->topallen_valid_count = $topallen_valid_count;
            $oQuota->topallen_left_count = $topallen_left_count;
            $oQuota->edit_count = $left_count;
            $oQuota->quota = $oQuotaValue;

            $iRet = $oQuota->save();

        }
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;


        $sOperateName = 'userQuotaSave';
        $sLogContent = 'userQuotaSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }



    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function userTopParentSave($iId = null)
    {
        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $sTopParentNew = isset($data['top_parent_new']) ? $data['top_parent_new'] : '';
        $oEvent = User::find($iId);
        $oEvent->top_level = $sTopParentNew;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $oEvent;


        $sOperateName = 'userTopParentSave';
        $sLogContent = 'userTopParentSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function userRebateSave($iId = null)
    {
        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $sRakeSetting = isset($data['rake_setting']) ? $data['rake_setting'] : '';
        $oEvent = User::find($iId);
        $oEvent->rake_setting = $sRakeSetting;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $oEvent;


        $sOperateName = 'userRebateSave';
        $sLogContent = 'userRebateSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function getUserQuota()
    {

        $data = request()->post();

        $iUserId = isset($data['0']) ? $data['0'] : '';

        $oAuthAdminList = DB::table('user_quota');

        if ($iUserId != '') {
            $oAuthAdminList->where('user_id','=', $iUserId);
        }
        $oAuthAdminFinalList = $oAuthAdminList->get();


        if (count($oAuthAdminFinalList) > 0) {
            foreach ($oAuthAdminFinalList as $oAuthAdmin) {
                $aTmp['id']=$oAuthAdmin->id;
                $aTmp['user_id']=$oAuthAdmin->user_id;
                $aTmp['username']=$oAuthAdmin->username;
                $aTmp['rebate_level']=$oAuthAdmin->rebate_level;
                $aTmp['topallen_valid_count']=$oAuthAdmin->topallen_valid_count;
                $aTmp['topallen_left_count']=$oAuthAdmin->topallen_left_count;
                $aTmp['edit_count']=$oAuthAdmin->edit_count;
                $aTmp['quota']=$oAuthAdmin->quota;

                $aFinal[] = $aTmp;
            }
        } else {

            $aTmp['rebate_level'] = "00";
            $aTmp['topallen_valid_count'] = "11";
            $aTmp['topallen_left_count'] = "22";
            $aTmp['left_count'] = "33";
            $aTmp['quota'] = "44";


            $aFinal[] = $aTmp;

            $aTmp['rebate_level'] = "22";
            $aTmp['topallen_valid_count'] = "33";
            $aTmp['topallen_left_count'] = "44";
            $aTmp['left_count'] = "55";
            $aTmp['quota'] = "66";


            $aFinal[] = $aTmp;
        }


        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'getUserQuota';
        $sLogContent = 'getUserQuota';


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
    public function userLockSave($iId = null)
    {
        $data = request()->post();
        $iUserId = isset($data['id']) ? $data['id'] : '';
        $sLockRange = isset($data['lock_range']) ? $data['lock_range'] : '';
        $sLockType = isset($data['lock_type']) ? $data['lock_type'] : '';
        $sOnlineQq = isset($data['online_qq']) ? $data['online_qq'] : '';
        $sLockReason = isset($data['lock_reason']) ? $data['lock_reason'] : '';

        $oEvent = new UserLock();
        $oEvent->user_id = $iUserId;
        $oEvent->username = $iUserId;
        $oEvent->lock_range = $sLockRange;
        $oEvent->lock_type = $sLockType;
        $oEvent->online_qq = $sOnlineQq;
        $oEvent->lock_reason = $sLockReason;

        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $oEvent;

        $sOperateName = 'userLockSave';
        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function updateUserlayerPaySetting($iId = null)
    {
        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $sRakeSetting = isset($data['pay_setting']) ? $data['pay_setting'] : '';
        $oEvent = UserLevel::find($iId);
        $oEvent->pay_setting = $sRakeSetting;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $oEvent;


        $sOperateName = 'updateUserlayerPaySetting';
        $sLogContent = 'updateUserlayerPaySetting';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function updateUserlayerProjectLimit($iId = null)
    {
        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $sRakeSetting = isset($data['project_limit']) ? $data['project_limit'] : '';
        $oEvent = UserLevel::find($iId);
        $oEvent->project_limit = $sRakeSetting;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $oEvent;


        $sOperateName = 'updateUserlayerProjectLimit';
        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


}