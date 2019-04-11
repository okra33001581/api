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

    public function out()
    {
        $aData['code'] = 0;
        $aData['message'] = 'success';
        return response()->json($aData);
    }

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


        $sub_account = '123';
        $operate_name = 'loginIndex';
        $log_content = 'loginIndex';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function loginInfo()
    {
        $id = request()->header('X-Adminid');
        $token = request()->header('X-Token');

        if (!$id || !$token) {
//            return ResultVo::error(ErrorCode::LOGIN_FAILED);
        }
        $res = AuthAdmin::loginInfo($id, (string)$token);

        $res['id'] = !empty($res['id']) ? intval($res['id']) : 0;
        $res['avatar'] = !empty($res['avatar']) ? PublicFileUtils::createUploadUrl($res['avatar']) : '';
        // $res['roles'] = ['admin'];

        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sub_account = '123';
        $operate_name = 'loginInfo';
        $log_content = 'loginInfo';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function getJson()
    {
        // 从文件中读取数据到PHP变量
        $json_string = file_get_contents('/home/ok/api/app/Http/Controllers/Auz/data.json');
        return $json_string;

    }

    public function userInfolist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $merchant_name= isset(request()->merchant_name) ? request()->merchant_name : '';
        $user_type= isset(request()->user_type) ? request()->user_type : '';
        $username= isset(request()->username) ? request()->username : '';
        $group= isset(request()->group) ? request()->group : '';
        $user_level= isset(request()->user_level) ? request()->user_level : '';
        $beginDate= isset(request()->beginDate) ? request()->beginDate : '';
        $endDate= isset(request()->endDate) ? request()->endDate : '';
        $operate_type= isset(request()->operate_type) ? request()->operate_type : '';
        $days= isset(request()->days) ? request()->days : '';

        $oAuthAdminList = DB::table('user_user');

        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

      if ($user_type != '') {
          $oAuthAdminList->where('account_type', '=', $user_type);
      }

       if ($username != '') {
           $oAuthAdminList->where('username', 'like', '%' . $username . '%');
       }


        if ($group != '') {
            $oAuthAdminList->where('group', '=', $group);
        }


        if ($user_level != '') {
            $oAuthAdminList->where('user_level', '=', $user_level);
        }

        if ($beginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $beginDate);
        }

        if ($endDate != '') {
            $oAuthAdminList->where('created_at', '>=', $endDate);
        }

         if ($days != '') {
             $dtTmp = date('Y-m-d', strtotime('- '.$days.' day'));
         }

        switch ($operate_type) {
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

        if ($group == '用户名') {
            $aUserTmp = explode(",",$username);
            $oAuthAdminList->whereIn("username", $aUserTmp);
        } elseif ($group == '所属上级') {
            if ($username != '') {
                $oAuthAdminList->where('top_level', 'like', '%' . $username . '%');
            }

        }
        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

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

        $sub_account = '123';
        $operate_name = 'userInfolist';
        $log_content = 'userInfolist';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function userSave()
    {
        $data = request()->post();

        $id=isset($data['id'])?$data['id']:'';

        $icon=isset($data['icon'])?$data['icon']:'';
        $account_type=isset($data['account_type'])?$data['account_type']:'';
        $top_level=isset($data['top_level'])?$data['top_level']:'';
        $account=isset($data['account'])?$data['account']:'';
        $password=isset($data['password'])?$data['password']:'';
        $nickname=isset($data['nickname'])?$data['nickname']:'';
        $memo=isset($data['memo'])?$data['memo']:'';
        $rake_setting=isset($data['rake_setting'])?$data['rake_setting']:'';
        $user_level=isset($data['user_level'])?$data['user_level']:'';
        $email=isset($data['email'])?$data['email']:'';
        $tel=isset($data['tel'])?$data['tel']:'';
        $weixin=isset($data['weixin'])?$data['weixin']:'';
        $fund_password=isset($data['fund_password'])?$data['fund_password']:'';
        $realname=isset($data['realname'])?$data['realname']:'';


        if ($id != '') {
            $oQrCode = User::find($id);
        } else {
            $oQrCode = new User();
        }



//        $oQrCode->id = $id;

        $oQrCode->icon=$icon;
        $oQrCode->account_type=$account_type;
        $oQrCode->top_level=$top_level;
        $oQrCode->account=$account;
        $oQrCode->password=$password;
        $oQrCode->nickname=$nickname;
        $oQrCode->memo=$memo;
        $oQrCode->rake_setting=$rake_setting;
        $oQrCode->user_level=$user_level;
        $oQrCode->email=$email;
        $oQrCode->tel=$tel;
        $oQrCode->weixin=$weixin;
        $oQrCode->fund_password=$fund_password;
        $oQrCode->realname=$realname;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        $sub_account = '123';
        $operate_name = 'userSave';
        $log_content = 'userSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }


    public function userLevelSave()
    {
        $data = request()->post();


        $level_name=isset($data['level_name'])?$data['level_name']:'';
        $deposit_times=isset($data['deposit_times'])?$data['deposit_times']:'';
        $deposit_amount=isset($data['deposit_amount'])?$data['deposit_amount']:'';
        $deposit_max=isset($data['deposit_max'])?$data['deposit_max']:'';
        $withdraw_times=isset($data['withdraw_times'])?$data['withdraw_times']:'';
        $withdraw_amount=isset($data['withdraw_amount'])?$data['withdraw_amount']:'';
        $prior=isset($data['prior'])?$data['prior']:'';
        $memo=isset($data['memo'])?$data['memo']:'';
        $pay_setting=isset($data['pay_setting'])?$data['pay_setting']:'';
        $project_limit=isset($data['project_limit'])?$data['project_limit']:'';


        $oQrCode = new UserLevel();


//        $oQrCode->id = $id;


        $oQrCode->level_name=$level_name;
        $oQrCode->deposit_times=$deposit_times;
        $oQrCode->deposit_amount=$deposit_amount;
        $oQrCode->deposit_max=$deposit_max;
        $oQrCode->withdraw_times=$withdraw_times;
        $oQrCode->withdraw_amount=$withdraw_amount;
        $oQrCode->prior=$prior;
        $oQrCode->memo=$memo;
        $oQrCode->pay_setting=$pay_setting;
        $oQrCode->project_limit=$project_limit;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        $sub_account = '123';
        $operate_name = 'userLevelSave';
        $log_content = 'userLevelSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }




    public function bankCardSave()
    {
        $data = request()->post();


        $status=isset($data['status'])?$data['status']:'';
        $account=isset($data['account'])?$data['account']:'';
        $top_name=isset($data['top_name'])?$data['top_name']:'';
        $bank=isset($data['bank'])?$data['bank']:'';
        $province_city=isset($data['province_city'])?$data['province_city']:'';
        $card_number=isset($data['card_number'])?$data['card_number']:'0';
        $branch_name=isset($data['branch_name'])?$data['branch_name']:'';
        $real_name=isset($data['real_name'])?$data['real_name']:'';
        $is_black=isset($data['is_black'])?$data['is_black']:'';
        $total_amount=isset($data['total_amount'])?$data['total_amount']:'';
        $created_at=isset($data['created_at'])?$data['created_at']:'';

        $oQrCode = new UserBankCard();


//        $oQrCode->id = $id;



        $oQrCode->status=$status;
        $oQrCode->account=$account;
        $oQrCode->top_name=$top_name;
        $oQrCode->bank=$bank;
        $oQrCode->province_city=$province_city;
        $oQrCode->card_number=$card_number;
        $oQrCode->branch_name=$branch_name;
        $oQrCode->real_name=$real_name;
        $oQrCode->is_black=$is_black;
        $oQrCode->total_amount=$total_amount;
        $oQrCode->created_at=date("Y-m-d H:i:s");;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        $sub_account = '123';
        $operate_name = 'bankCardSave';
        $log_content = 'bankCardSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }

    public function userInoutcash()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $type = isset(request()->type) ? request()->type : '';
        $select_user_type = isset(request()->select_user_type) ? request()->select_user_type : '';
        $keywords = isset(request()->keywords) ? request()->keywords : '';
        $user_type = isset(request()->user_type) ? request()->user_type : '';
        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $endDate = isset(request()->endDate) ? request()->endDate : '';

        $oAuthAdminList = DB::table('user_in_out_statics');



        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($type != '') {
            $oAuthAdminList->where('method', '=', $type);
        }


        if ($select_user_type == '用户名') {
            if ($keywords != '') {
                $oAuthAdminList->where('username', 'like', '%' . $keywords . '%');
            }
        } elseif ($select_user_type == '所属上级') {
            if ($keywords != '') {
                $oAuthAdminList->where('top_agent', 'like', '%' . $keywords . '%');
            }

        }


        if ($user_type != '') {
            $oAuthAdminList->where('user_type', '=', $user_type);
        }

        if ($beginDate != '') {
            $oAuthAdminList->where('login_date', '>=', $beginDate);
        }

        if ($endDate != '') {
            $oAuthAdminList->where('login_date', '>=', $endDate);
        }

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

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

        $sub_account = '123';
        $operate_name = 'userInoutcash';
        $log_content = 'userInoutcash';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function userMainlist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $merchant_name= isset(request()->merchant_name) ? request()->merchant_name : '';
        $username= isset(request()->username) ? request()->username : '';
        $group= isset(request()->group) ? request()->group : '';
        $status= isset(request()->status) ? request()->status : '';
        $user_level= isset(request()->user_level) ? request()->user_level : '';
        $select_type_amount= isset(request()->select_type_amount) ? request()->select_type_amount : '';
        $min= isset(request()->min) ? request()->min : '';
        $max= isset(request()->max) ? request()->max : '';
        $beginDate= isset(request()->beginDate) ? request()->beginDate : '';
        $endDate= isset(request()->endDate) ? request()->endDate : '';
        $online_status= isset(request()->online_status) ? request()->online_status : '';
        $select_operate_type= isset(request()->select_operate_type) ? request()->select_operate_type : '';
        $days= isset(request()->days) ? request()->days : '';


        $oAuthAdminList = DB::table('user_user');


        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($group == '用户名') {
            if ($username != '') {
                $aUserTmp = explode(",",$username);
                $oAuthAdminList->whereIn("username", $aUserTmp);
            }
        } elseif ($group == '所属上级') {
            if ($username != '') {
                $oAuthAdminList->where('top_level', 'like', '%' . $username . '%');
            }

        }

        if ($group != '') {
            $oAuthAdminList->where('group', '=', $group);
        }


        if ($status != '') {
            $oAuthAdminList->where('status', '=', $status);
        }

        if ($user_level != '') {
            $oAuthAdminList->where('user_level', '=', $user_level);
        }


        if ($beginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $beginDate);
        }

        if ($endDate != '') {
            $oAuthAdminList->where('created_at', '>=', $endDate);
        }


        if ($online_status != '') {
            $oAuthAdminList->where('online_status', '=', $online_status);
        }


        if ($select_operate_type != '') {
            $oAuthAdminList->where('last_login_date', '=', $select_operate_type);
        }


        if ($days != '') {
            $dtTmp = date('Y-m-d', strtotime('- '.$days.' day'));
        }

        switch ($select_operate_type) {
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

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

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

        $sub_account = '123';
        $operate_name = 'userMainlist';
        $log_content = 'userMainlist';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function userMonitor()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $select_username_type = isset(request()->select_username_type) ? request()->select_username_type : '';
        $username = isset(request()->username) ? request()->username : '';

        $oAuthAdminList = DB::table('user_monitor');


        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }


        if ($select_username_type != '') {
            $oAuthAdminList->where('type', '=', $select_username_type);
        }


        if ($username != '') {
            $oAuthAdminList->where('username', 'like', '%' . $username . '%');
        }

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);


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

        $sub_account = '123';
        $operate_name = 'userMonitor';
        $log_content = 'userMonitor';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function userReviewlist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $type = isset(request()->type) ? request()->type : '';
        $status = isset(request()->status) ? request()->status : '';
        $username = isset(request()->username) ? request()->username : '';
        $select_user_type = isset(request()->select_user_type) ? request()->select_user_type : '';
        $username2 = isset(request()->username2) ? request()->username2 : '';
        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $endDate = isset(request()->endDate) ? request()->endDate : '';


        $oAuthAdminList = DB::table('user_safety_audit');



        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($type != '') {
            $oAuthAdminList->where('type', '=', $type);
        }


        if ($status != '') {
            $oAuthAdminList->where('status', '=', $status);
        }


        if ($username != '') {
            $oAuthAdminList->where('account', '=', $username);
        }

        if ($select_user_type == '提交人') {
            if ($username2 != '') {
                $oAuthAdminList->where('requestor', '=', $username2);
            }
        } elseif ($select_user_type == '审核人') {
            if ($username2 != '') {
                $oAuthAdminList->where('auditor', '=', $username2);
            }
        }

        if ($beginDate != '') {
            $oAuthAdminList->where('submit_date', '>=', $beginDate);
        }

        if ($endDate != '') {
            $oAuthAdminList->where('submit_date', '>=', $endDate);
        }

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);


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

        $sub_account = '123';
        $operate_name = 'userReviewlist';
        $log_content = 'userReviewlist';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function userUsercard()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $status=isset(request()->status)?request()->status:'';
        $merchant_name=isset(request()->merchant_name)?request()->merchant_name:'';

        $type=isset(request()->type)?request()->type:'';
        $keywords=isset(request()->keywords)?request()->keywords:'';
        $realname=isset(request()->realname)?request()->realname:'';
        $min=isset(request()->min)?request()->min:'';
        $max=isset(request()->max)?request()->max:'';
        $is_black=isset(request()->is_black)?request()->is_black:'';

        $oAuthAdminList = DB::table('user_bankcard');

        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($status != '') {
            $oAuthAdminList->where('status', '=', $status);
        }


        if ($type == '卡号') {
            if ($keywords != '') {
                $oAuthAdminList->where('account', 'like', '%' . $keywords . '%');
            }
        } elseif ($type == '用户名') {
            if ($keywords != '') {
                $oAuthAdminList->where('card_number', 'like', '%' . $keywords . '%');
            }

        }


        if ($realname != '') {
            $oAuthAdminList->where('real_name', 'like', '%' . $realname . '%');
        }

        if ($min != '') {
            $oAuthAdminList->where('total_amount', '>=', $min);
        }

        if ($max != '') {
            $oAuthAdminList->where('total_amount', '>=', $max);
        }


        if ($is_black != '') {
            $oAuthAdminList->where('is_black', '=', $is_black);
        }

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);


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

        $sub_account = '123';
        $operate_name = 'userUsercard';
        $log_content = 'userUsercard';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }






    public function bankcardStatusSave($id = null)
    {

        $data = request()->post();

        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = UserBankCard::find($id);
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

        $sub_account = '123';
        $operate_name = 'bankcardStatusSave';
        $log_content = 'bankcardStatusSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }



    public function userStatusSave($id = null)
    {

        $data = request()->post();

        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = User::find($id);
        if (is_object($oEvent)) {
            $iStatue = $oEvent->status;
        }
        $oEvent->status = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;

        $sub_account = '123';
        $operate_name = 'userStatusSave';
        $log_content = 'userStatusSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }

    public function userUserlayer()
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
        $oAuthAdminList = DB::table('user_level');

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);

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

        $sub_account = '123';
        $operate_name = 'userUserlayer';
        $log_content = 'userUserlayer';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);

    }


    public function userValiduser()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $agent_name = isset(request()->agent_name) ? request()->agent_name : '';
        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $endDate = isset(request()->endDate) ? request()->endDate : '';

        $oAuthAdminList = DB::table('user_validuser');



        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($agent_name != '') {
            $oAuthAdminList->where('top_agent', 'like', '%' . $agent_name . '%');
        }



        if ($beginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $beginDate);
        }

        if ($endDate != '') {
            $oAuthAdminList->where('created_at', '<=', $endDate);
        }

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);


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

        $sub_account = '123';
        $operate_name = 'userValiduser';
        $log_content = 'userValiduser';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

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

        $sub_account = '123';
        $operate_name = 'adminSave';
        $log_content = 'adminSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

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

        $sub_account = '123';
        $operate_name = 'adminEdit';
        $log_content = 'adminEdit';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

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

        $sub_account = '123';
        $operate_name = 'adminDelete';
        $log_content = 'adminDelete';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success();

    }





    public function usersafetyStatusSave($id = null)
    {

        $data = request()->post();

        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = UserSafetyAudit::find($id);
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

        $sub_account = '123';
        $operate_name = 'usersafetyStatusSave';
        $log_content = 'usersafetyStatusSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }



    public function userlayerDelete()
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
        UserLevel::where('id', '=', $id)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'userlayerDelete';
        $log_content = 'userlayerDelete';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success();

    }

    public function usercardDelete()
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
        UserBankCard::where('id', '=', $id)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'usercardDelete';
        $log_content = 'usercardDelete';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success();

    }

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
            $quota = $v['quota'];
            $Quota = new Quota();
            $Quota->user_id = $iUserId;
            $Quota->username = $oUser->realname;
            $Quota->rebate_level = $rebate_level;
            $Quota->rebate_level = $rebate_level;
            $Quota->topallen_valid_count = $topallen_valid_count;
            $Quota->topallen_left_count = $topallen_left_count;
            $Quota->edit_count = $left_count;
            $Quota->quota = $quota;
            $iRet = $Quota->save();

        }
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;

        $sub_account = '123';
        $operate_name = 'userQuotaSave';
        $log_content = 'userQuotaSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }




    public function userTopParentSave($id = null)
    {
        $data = request()->post();
        $id = isset($data['id']) ? $data['id'] : '';
        $top_parent_new = isset($data['top_parent_new']) ? $data['top_parent_new'] : '';
        $oEvent = User::find($id);
        $oEvent->top_level = $top_parent_new;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $oEvent;

        $sub_account = '123';
        $operate_name = 'userTopParentSave';
        $log_content = 'userTopParentSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }


    public function userRebateSave($id = null)
    {
        $data = request()->post();
        $id = isset($data['id']) ? $data['id'] : '';
        $rake_setting = isset($data['rake_setting']) ? $data['rake_setting'] : '';
        $oEvent = User::find($id);
        $oEvent->rake_setting = $rake_setting;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $oEvent;

        $sub_account = '123';
        $operate_name = 'userRebateSave';
        $log_content = 'userRebateSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }



    public function getUserQuota()
    {

        $data = request()->post();

        $iUserId = isset($data['0']) ? $data['0'] : '';

        Log::info($data);

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

        $sub_account = '123';
        $operate_name = 'getUserQuota';
        $log_content = 'getUserQuota';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return ResultVo::success($res);
    }


    public function userLockSave($id = null)
    {
        $data = request()->post();
        $user_id = isset($data['id']) ? $data['id'] : '';
        $lock_range = isset($data['lock_range']) ? $data['lock_range'] : '';
        $lock_type = isset($data['lock_type']) ? $data['lock_type'] : '';
        $online_qq = isset($data['online_qq']) ? $data['online_qq'] : '';
        $lock_reason = isset($data['lock_reason']) ? $data['lock_reason'] : '';

        $oEvent = new UserLock();

        $oEvent->user_id = $user_id;
        $oEvent->username = $user_id;
        $oEvent->lock_range = $lock_range;
        $oEvent->lock_type = $lock_type;
        $oEvent->online_qq = $online_qq;
        $oEvent->lock_reason = $lock_reason;


        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $oEvent;


        $sub_account = '123';
        $operate_name = 'userLockSave';
        $log_content = 'userLockSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
    }


}