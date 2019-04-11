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
 * Class Event - 管理员相关控制器
 * @author zebra
 */
class AdminController extends Controller
{
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function adminIndex()
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
        if ($iStatus != '') {
            $oAuthAdminList->where('status', $iStatus);
        }
        if ($sUserName != '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }
        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

//        $aTmp = [];
//        $aFinal = [];
//        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//            $oAuthAdmin->avatar = PublicFileUtils::createUploadUrl($oAuthAdmin->avatar);
//            $aTmp['id'] = $oAuthAdmin->id;
//            $aTmp['username'] = $oAuthAdmin->username;
//            $aTmp['password'] = $oAuthAdmin->password;
//            $aTmp['tel'] = $oAuthAdmin->tel;
//            $aTmp['email'] = $oAuthAdmin->email;
//            $aTmp['avatar'] = $oAuthAdmin->avatar;
//            $aTmp['sex'] = $oAuthAdmin->sex;
//            $aTmp['last_login_ip'] = $oAuthAdmin->last_login_ip;
//            $aTmp['last_login_time'] = $oAuthAdmin->last_login_time;
//            $aTmp['create_time'] = $oAuthAdmin->create_time;
//            $aTmp['status'] = $oAuthAdmin->status;
//            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
//            $aTmp['created_at'] = $oAuthAdmin->created_at;
//            $roles = AuthRoleAdmin::where('admin_id', $oAuthAdmin->id)->first();
//            $temp_roles = [];
//            if (is_object($roles)) {
//                $temp_roles = $roles->toArray();
//                $temp_roles = array_column($temp_roles, 'role_id');
//            }
//            $aTmp['roles'] = $temp_roles;
//            $aFinal[] = $aTmp;
//        }

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;



        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


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
    public function adminRoleList()
    {
        $sWhere = [];
        $iLimit = request()->get('limit/d', 20);
        //分页配置
//        $paginate = [
//            'type' => 'bootstrap',
//            'var_page' => 'page',
//            'list_rows' => ($iLimit <= 0 || $iLimit > 20) ? 20 : $iLimit,
//        ];
        $iTmp = ($iLimit <= 0 || $iLimit > 20) ? 20 : $iLimit;
        $lists = AuthRole::where($sWhere)
            ->paginate($iTmp);

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();
        return response()->json($res);
        return ResultVo::success($res);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function adminSave()
    {
        $data = request()->post();
        if (empty($data['username']) || empty($data['password'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $sUsername = $data['username'];
        // 模型
//        $info = AuthAdmin::where('username',$sUsername)
//            ->field('username')
//            ->find();

        $oAuthAdmin = AuthAdmin::where('username', $sUsername)
            ->first();

//        if ($oAuthAdmin){
//            return ResultVo::error(ErrorCode::DATA_REPEAT);
//        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_admin = new AuthAdmin();
        $auth_admin->username = $sUsername;
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
                    $oAuthRoleAdmin = new AuthRoleAdmin();
                    $oAuthRoleAdmin->role_id = $v['role_id'];
                    $oAuthRoleAdmin->admin_id = $v['admin_id'];
                    $iRet = $oAuthRoleAdmin->save();
                }
            }
//            $oAuthRoleAdmin->saveAll($temp);
        }

        $auth_admin['password'] = '';
        $auth_admin['roles'] = $roles;

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $auth_admin;


        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($auth_admin);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function adminEdit()
    {
        $data = request()->post();


//        Log::info($data);
        $aRoles = $data['roles'];

        if (empty($data['id']) || empty($data['username'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $iId = $data['id'];
        $sUsername = strip_tags($data['username']);
        // 模型
//        $auth_admin = AuthAdmin::where('id',$iId)
//            ->field('id,username')
//            ->find();
        $oAuthAdmin = AuthAdmin::where('id', $iId)
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

//        $info = AuthAdmin::where('username',$sUsername)
//            ->field('id')
//            ->find();

        $info = AuthAdmin::where('username', $sUsername)
            ->first();

        // 判断username 是否重名，剔除自己
//        if (!empty($info['id']) && $info['id'] != $iId){
//            return ResultVo::error(ErrorCode::DATA_REPEAT, "商户已存在");
//        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $sPassword = isset($data['password']) ? PassWordUtils::create($data['password']) : '';
        $oAuthAdmin->username = $sUsername;
        if ($sPassword) {
            $oAuthAdmin->password = $sPassword;
        }
        $oAuthAdmin->status = $status;
//        $oAuthAdmin->role_id = implode(",", $aRoles);

        $result = $oAuthAdmin->save();

        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];
        if (!$result) {
            // 没有做任何更改
            $oAuthRoleAdmin = AuthRoleAdmin::where('admin_id', $iId)->field('role_id')->select();
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
            AuthRoleAdmin::where('admin_id', $iId)->delete();
            $temp = [];
            foreach ($roles as $key => $value) {
                $temp[$key]['role_id'] = $value;
                $temp[$key]['admin_id'] = $iId;
            }


            //添加用户的角色
            $oAuthRoleAdmin = new AuthRoleAdmin();

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


        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);

        return ResultVo::success();
    }

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function adminDelete()
    {
//        $iId = request()->post('id/d');
        $iId = request()->all()['id'];
        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$iId)->field('username')->find();
        $oAuthAdmin = AuthAdmin::where('id', $iId)->first();
        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        // 删除权限
        AuthRoleAdmin::where('admin_id', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


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
    public function adminStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = AuthAdmin::find($iId);
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



        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }





}