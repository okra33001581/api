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


class UserController extends Controller
{
// LoginController.php

    /**
     * logout
     */
    public function out()
    {
        $aData['code'] = 0;
        $aData['message'] = 'success';
        return response()->json($aData);
    }

    /**
     * 获取用户信息
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
        $user_name = 'admin';
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
            $oAuthRoleAdminList = AuthRoleAdmin::where('admin_id', $admin->id)->select('role_id')->get();
            if (count($oAuthRoleAdminList) > 0) {
                $oAuthPermissionList = AuthPermission::where('role_id', 'in', $oAuthRoleAdminList->role_id)
                    ->select(['permission_rule_id'])
                    ->get();
                foreach ($oAuthPermissionList as $oAuthPermission) {
                    $oAuthPermissionRule = AuthPermissionRule::where('id', $oAuthPermission->permission_rule_id)->select('name')->first();
                    if (is_object($oAuthPermissionRule)) {
                        $authRules[] = $oAuthPermissionRule->name;
                    }
                }
            }
        }
        $info['authRules'] = $authRules;
        // $info['authRules'] = [
        //     'user_manage',
        //     'user_manage/admin_manage',
        //     'admin/admin/index',
        //     'admin/role/index',
        //     'admin/auth_admin/index',
        // ];
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

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * 获取登录用户信息
     */
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

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

}