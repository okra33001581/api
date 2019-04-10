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

        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';

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
        if ($beginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $beginDate);
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


        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['sub_account'] = $oAuthAdmin->sub_account;
            $aTmp['operate_name'] = $oAuthAdmin->operate_name;
            $aTmp['log_content'] = $oAuthAdmin->log_content;
            $aTmp['ip'] = $oAuthAdmin->ip;
            $aTmp['cookies'] = $oAuthAdmin->cookies;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;

            $aFinal[] = $aTmp;
        }





        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
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


    public function logDomainlog()
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

        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $endDate = isset(request()->endDate) ? request()->endDate : '';

        $oAuthAdminList = DB::table('log_domain');


        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($beginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $beginDate);
        }


        if ($endDate != '') {
            $oAuthAdminList->where('created_at', '<=', $endDate);
        }




//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus != '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName != '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();

        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['domain'] = $oAuthAdmin->domain;
            $aTmp['total_visit_people_count'] = $oAuthAdmin->total_visit_people_count;
            $aTmp['tatal_visit_count'] = $oAuthAdmin->tatal_visit_count;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;

            $aFinal[] = $aTmp;
        }

        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
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

    public function logLoginlog()
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



        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $endDate = isset(request()->endDate) ? request()->endDate : '';
        $type = isset(request()->type) ? request()->type : '';
        $sub_title = isset(request()->sub_title) ? request()->sub_title : '';
        $keywords = isset(request()->keywords) ? request()->keywords : '';
        $is_check = isset(request()->is_check) ? request()->is_check : '';

        $oAuthAdminList = DB::table('log_login');


        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($beginDate != '') {
            $oAuthAdminList->where('login_date', '>=', $beginDate);
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


//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus != '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName != '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();

        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['login_info'] = $oAuthAdmin->login_info;
            $aTmp['ip_address'] = $oAuthAdmin->ip_address;
            $aTmp['district'] = $oAuthAdmin->district;
            $aTmp['request_url'] = $oAuthAdmin->request_url;
            $aTmp['login_date'] = $oAuthAdmin->login_date;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['is_check'] = $oAuthAdmin->is_check;

            $aFinal[] = $aTmp;
        }

        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
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
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
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
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
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
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success();

    }
}