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
     * @api {post} /api/out  登录注销
     * @apiGroup user
     * @apiParam {string} name 用户昵称
     * @apiParam {string} email 用户登陆名　email格式 必须唯一
     * @apiParam {string} password 用户登陆密码
     * @apiParam {string="admin","editor"} [role="editor"] 角色 内容为空或者其他的都设置为editor
     * @apiParam {string} [avatar] 用户头像地址
     * @apiParamExample {json} 请求的参数例子:
     *     {
     *       name: 'test',
     *       email: '1111@qq.com',
     *       password: '123456',
     *       role: 'editor',
     *       avatar: 'uploads/20178989.png'
     *     }
     *
     * @apiSuccessExample 新建用户成功
     * HTTP/1.1 201 OK
     * {
     * "status": "success",
     * "status_code": 201
     * }
     * @apiErrorExample 数据验证出错
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
     * }
     */
    public function out()
    {
        $aData['code'] = 0;
        $aData['message'] = 'success';
        return response()->json($aData);
    }

    /**
     * @api {post} /api/loginIndex  登录
     * @apiGroup user
     * @apiParam {string} name 用户昵称
     * @apiParam {string} email 用户登陆名　email格式 必须唯一
     * @apiParam {string} password 用户登陆密码
     * @apiParam {string="admin","editor"} [role="editor"] 角色 内容为空或者其他的都设置为editor
     * @apiParam {string} [avatar] 用户头像地址
     * @apiParamExample {json} 请求的参数例子:
     *     {
     *       name: 'test',
     *       email: '1111@qq.com',
     *       password: '123456',
     *       role: 'editor',
     *       avatar: 'uploads/20178989.png'
     *     }
     *
     * @apiSuccessExample 新建用户成功
     * HTTP/1.1 201 OK
     * {
     * "status": "success",
     * "status_code": 201
     * }
     * @apiErrorExample 数据验证出错
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
     * }
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
     * @api {get} /api/loginInfo  取得登录信息
     * @apiGroup user
     * @apiParam {string} name 用户昵称
     * @apiParam {string} email 用户登陆名　email格式 必须唯一
     * @apiParam {string} password 用户登陆密码
     * @apiParam {string="admin","editor"} [role="editor"] 角色 内容为空或者其他的都设置为editor
     * @apiParam {string} [avatar] 用户头像地址
     * @apiParamExample {json} 请求的参数例子:
     *     {
     *       name: 'test',
     *       email: '1111@qq.com',
     *       password: '123456',
     *       role: 'editor',
     *       avatar: 'uploads/20178989.png'
     *     }
     *
     * @apiSuccessExample 新建用户成功
     * HTTP/1.1 201 OK
     * {
     * "status": "success",
     * "status_code": 201
     * }
     * @apiErrorExample 数据验证出错
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
     * }
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