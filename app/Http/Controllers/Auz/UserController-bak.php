<?php
//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//use App\model\Event;
//use DB;
//use Log;
//use App\common\vo\ResultVo;
//use App\model\AuthAdmin;
//use App\model\AuthRoleAdmin;
//use App\model\AuthPermission;
//use App\model\AuthPermissionRule;
//use App\model\AuthRole;
//use App\common\utils\PublicFileUtils;
//use App\common\utils\PassWordUtils;
//use App\model\Ad;
//use App\model\AdSite;
//use App\model\FileResource;
//use App\model\FileResourceTag;
//
//use Illuminate\Support\Facades\Redis;
//
//
//class UserController extends Controller
//{
//// LoginController.php
//
//    /**
//     * @api {post} /api/out  登录注销
//     * @apiGroup user
//     * @apiParam {string} name 用户昵称
//     * @apiParam {string} email 用户登陆名　email格式 必须唯一
//     * @apiParam {string} password 用户登陆密码
//     * @apiParam {string="admin","editor"} [role="editor"] 角色 内容为空或者其他的都设置为editor
//     * @apiParam {string} [avatar] 用户头像地址
//     * @apiParamExample {json} 请求的参数例子:
//     *     {
//     *       name: 'test',
//     *       email: '1111@qq.com',
//     *       password: '123456',
//     *       role: 'editor',
//     *       avatar: 'uploads/20178989.png'
//     *     }
//     *
//     * @apiSuccessExample 新建用户成功
//     * HTTP/1.1 201 OK
//     * {
//     * "status": "success",
//     * "status_code": 201
//     * }
//     * @apiErrorExample 数据验证出错
//     * HTTP/1.1 404 Not Found
//     * {
//     * "status": "error",
//     * "status_code": 404,
//     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
//     * }
//     */
//    public function out()
//    {
//        $aData['code'] = 0;
//        $aData['message'] = 'success';
//        return response()->json($aData);
//    }
//
//    /**
//     * @api {post} /api/loginIndex  登录
//     * @apiGroup user
//     * @apiParam {string} name 用户昵称
//     * @apiParam {string} email 用户登陆名　email格式 必须唯一
//     * @apiParam {string} password 用户登陆密码
//     * @apiParam {string="admin","editor"} [role="editor"] 角色 内容为空或者其他的都设置为editor
//     * @apiParam {string} [avatar] 用户头像地址
//     * @apiParamExample {json} 请求的参数例子:
//     *     {
//     *       name: 'test',
//     *       email: '1111@qq.com',
//     *       password: '123456',
//     *       role: 'editor',
//     *       avatar: 'uploads/20178989.png'
//     *     }
//     *
//     * @apiSuccessExample 新建用户成功
//     * HTTP/1.1 201 OK
//     * {
//     * "status": "success",
//     * "status_code": 201
//     * }
//     * @apiErrorExample 数据验证出错
//     * HTTP/1.1 404 Not Found
//     * {
//     * "status": "error",
//     * "status_code": 404,
//     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
//     * }
//     */
//    public function loginIndex()
//    {
////        if(!request()->isMethod('post')){
////            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
////        }
//        $user_name = request()->post('userName');
//        $pwd = request()->post('pwd');
//        if (!$user_name || !$pwd) {
////            return ResultVo::error(ErrorCode::VALIDATION_FAILED, "username 不能为空。 password 不能为空。");
//        }
//
////        Log::info(request()->all());
////        $user_name = 'admin';
//        $user_name = request()->all()['userName'];
//        $admin = AuthAdmin::where('username', $user_name)
//            ->first();
//        if (empty($admin) || PassWordUtils::create($pwd) != $admin->password) {
////            return ResultVo::error(ErrorCode::USER_AUTH_FAIL);
//        }
//        if ($admin->status != 1) {
//            return ResultVo::error(ErrorCode::USER_NOT_PERMISSION);
//        }
//        $info = $admin->toArray();
//        unset($info['password']);
//        // 权限信息
//        $authRules = [];
//        if ($user_name == 'admin') {
//            $authRules = ['admin'];
//        } else {
//
//            Log::info($admin);
//
////            $oAuthRoleAdminList = AuthRoleAdmin::where('admin_id', $admin->id)->select('role_id')->get();
//            $oAuthRoleAdminList = AuthRoleAdmin::where('admin_id', $admin->id)->first();
//            if (is_object($oAuthRoleAdminList)) {
////                $oAuthPermissionList = AuthPermission::where('role_id', 'in', $oAuthRoleAdminList->role_id)
////                    ->select(['permission_rule_id'])
////                    ->get();
//
//                Log::info('111111111111111111======='.$oAuthRoleAdminList->role_id);
////                $aTmp =
//                $oAuthPermissionList = AuthPermission::where('role_id', '=', $oAuthRoleAdminList->role_id)
//                    ->get();
//                foreach ($oAuthPermissionList as $oAuthPermission) {
//                    Log::info('222222222222222');
////                    $oAuthPermissionRule = AuthPermissionRule::where('id', $oAuthPermission->permission_rule_id)->select('name')->first();
//                    $oAuthPermissionRule = AuthPermissionRule::where('id', $oAuthPermission->permission_rule_id)->first();
//                    if (is_object($oAuthPermissionRule)) {
//                        Log::info('33333333333333333333333333333');
//                        $authRules[] = $oAuthPermissionRule->name;
//                    }
//                }
//            }
//        }
//
//        Log::info($authRules);
//        $info['authRules'] = $authRules;
//        // $info['authRules'] = [
//        //     'user_manage',
//        //     'user_manage/admin_manage',
//        //     'admin/admin/index',
//        //     'admin/role/index',
//        //     'admin/auth_admin/index',
//        // ];
//        // 保存用户信息
//
//        $loginInfo = AuthAdmin::loginInfo($info['id'], $info);
//        $admin->last_login_ip = request()->ip();
//        $admin->last_login_time = date("Y-m-d H:i:s");
//        $admin->save();
//        $res = [];
//        $res['id'] = !empty($loginInfo['id']) ? intval($loginInfo['id']) : 0;
//        $res['token'] = !empty($loginInfo['token']) ? $loginInfo['token'] : '';
//
//        $aFinal['message'] = 'success';
//        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;
//
//        return response()->json($aFinal);
//        return ResultVo::success($res);
//    }
//
//
//    /**
//     * @api {get} /api/loginInfo  取得登录信息
//     * @apiGroup user
//     * @apiParam {string} name 用户昵称
//     * @apiParam {string} email 用户登陆名　email格式 必须唯一
//     * @apiParam {string} password 用户登陆密码
//     * @apiParam {string="admin","editor"} [role="editor"] 角色 内容为空或者其他的都设置为editor
//     * @apiParam {string} [avatar] 用户头像地址
//     * @apiParamExample {json} 请求的参数例子:
//     *     {
//     *       name: 'test',
//     *       email: '1111@qq.com',
//     *       password: '123456',
//     *       role: 'editor',
//     *       avatar: 'uploads/20178989.png'
//     *     }
//     *
//     * @apiSuccessExample 新建用户成功
//     * HTTP/1.1 201 OK
//     * {
//     * "status": "success",
//     * "status_code": 201
//     * }
//     * @apiErrorExample 数据验证出错
//     * HTTP/1.1 404 Not Found
//     * {
//     * "status": "error",
//     * "status_code": 404,
//     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
//     * }
//     */
//    public function loginInfo()
//    {
//        $id = request()->header('X-Adminid');
//        $token = request()->header('X-Token');
//
//        if (!$id || !$token) {
////            return ResultVo::error(ErrorCode::LOGIN_FAILED);
//        }
//        $res = AuthAdmin::loginInfo($id, (string)$token);
//
//        $res['id'] = !empty($res['id']) ? intval($res['id']) : 0;
//        $res['avatar'] = !empty($res['avatar']) ? PublicFileUtils::createUploadUrl($res['avatar']) : '';
//        // $res['roles'] = ['admin'];
//
//        $aFinal = [];
//        $aFinal['message'] = 'success';
//        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;
//
//        return response()->json($aFinal);
//        return ResultVo::success($res);
//    }
//
//
//
//
//
//    /**
//     * @api {get} /api/admin 显示商户列表
//     * @apiGroup admin
//     *
//     *
//     * @apiSuccessExample 返回商户信息列表
//     * HTTP/1.1 200 OK
//     * {
//     *  "data": [
//     *     {
//     *       "id": 2 // 整数型  用户标识
//     *       "name": "test"  //字符型 用户昵称
//     *       "email": "test@qq.com"  // 字符型 用户email，商户登录时的email
//     *       "role": "admin" // 字符型 角色  可以取得值为admin或editor
//     *       "avatar": "" // 字符型 用户的头像图片
//     *     }
//     *   ],
//     * "status": "success",
//     * "status_code": 200,
//     * "links": {
//     * "first": "http://manger.test/api/admin?page=1",
//     * "last": "http://manger.test/api/admin?page=19",
//     * "prev": null,
//     * "next": "http://manger.test/api/admin?page=2"
//     * },
//     * "meta": {adminDelete
//     * "current_page": 1, // 当前页
//     * "from": 1, //当前页开始的记录
//     * "last_page": 19, //总页数
//     * "path": "http://manger.test/api/admin",
//     * "per_page": 15,
//     * "to": 15, //当前页结束的记录
//     * "total": 271  // 总条数
//     * }
//     * }
//     *
//     */
//    public function userIndex()
//    {
//        $sWhere = [];
//        $sOrder = 'id DESC';
////        $iLimit = isset(request()->limit) ? request()->limit : '';
////        $iPage = isset(request()->page) ? request()->page : '';
////        // +id -id
////        $iSort = isset(request()->sort) ? request()->sort : '';
////        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
////        $iStatus = isset(request()->status) ? request()->status : '';
////        $sUserName = isset(request()->username) ? request()->username : '';
//        $oAuthAdminList = DB::table('users');
//
//        $sTmp = 'DESC';
////        if (substr($iSort, 0, 1) == '-') {
////            $sTmp = 'ASC';
////        }
////        $sOrder = substr($iSort, 1, strlen($iSort));
////        if ($sTmp != '') {
////            $oAuthAdminList->orderby($sOrder, $sTmp);
////        }
////        if ($iStatus !== '') {
////            $oAuthAdminList->where('status', $iStatus);
////        }
////        if ($sUserName !== '') {
////            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
////        }
//        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
////        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
//        $aTmp = [];
//        $aFinal = [];
//        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//
//            $aTmp['id'] = $oAuthAdmin->id;
//            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
//            $aTmp['username'] = $oAuthAdmin->username;
//            $aTmp['password'] = $oAuthAdmin->password;
//            $aTmp['fund_password'] = $oAuthAdmin->fund_password;
//            $aTmp['parent_id'] = $oAuthAdmin->parent_id;
//            $aTmp['forefather_ids'] = $oAuthAdmin->forefather_ids;
//            $aTmp['parent'] = $oAuthAdmin->parent;
//            $aTmp['forefathers'] = $oAuthAdmin->forefathers;
//            $aTmp['user_level'] = $oAuthAdmin->user_level;
//            $aTmp['vip_level'] = $oAuthAdmin->vip_level;
//            $aTmp['account_id'] = $oAuthAdmin->account_id;
//            $aTmp['prize_group'] = $oAuthAdmin->prize_group;
//            $aTmp['role_id'] = $oAuthAdmin->role_id;
//            $aTmp['role_ids'] = $oAuthAdmin->role_ids;
//            $aTmp['blocked'] = $oAuthAdmin->blocked;
//            $aTmp['portrait_code'] = $oAuthAdmin->portrait_code;
//            $aTmp['name'] = $oAuthAdmin->name;
//            $aTmp['nickname'] = $oAuthAdmin->nickname;
//            $aTmp['email'] = $oAuthAdmin->email;
//            $aTmp['mobile'] = $oAuthAdmin->mobile;
//            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
//            $aTmp['qq'] = $oAuthAdmin->qq;
//            $aTmp['skype'] = $oAuthAdmin->skype;
//            $aTmp['bet_multiple'] = $oAuthAdmin->bet_multiple;
//            $aTmp['bet_coefficient'] = $oAuthAdmin->bet_coefficient;
//            $aTmp['is_from_link'] = $oAuthAdmin->is_from_link;
//            $aTmp['has_ag_account'] = $oAuthAdmin->has_ag_account;
//            $aTmp['login_ip'] = $oAuthAdmin->login_ip;
//            $aTmp['register_ip'] = $oAuthAdmin->register_ip;
//            $aTmp['remember_token'] = $oAuthAdmin->remember_token;
//            $aTmp['signin_at'] = $oAuthAdmin->signin_at;
//            $aTmp['activated_at'] = $oAuthAdmin->activated_at;
//            $aTmp['register_at'] = $oAuthAdmin->register_at;
//            $aTmp['deleted_at'] = $oAuthAdmin->deleted_at;
//            $aTmp['created_at'] = $oAuthAdmin->created_at;
//            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
//
//
//
//            $aFinal[] = $aTmp;
//        }
//
//        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
//        $res["list"] = $aFinal;
//        $aFinal['message'] = 'success';
//        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;
//
//        return response()->json($aFinal);
//        return ResultVo::success($res);
//    }
//
//
//
//
//    /**
//     * @api {get} /api/admin 显示商户列表
//     * @apiGroup admin
//     *
//     *
//     * @apiSuccessExample 返回商户信息列表
//     * HTTP/1.1 200 OK
//     * {
//     *  "data": [
//     *     {
//     *       "id": 2 // 整数型  用户标识
//     *       "name": "test"  //字符型 用户昵称
//     *       "email": "test@qq.com"  // 字符型 用户email，商户登录时的email
//     *       "role": "admin" // 字符型 角色  可以取得值为admin或editor
//     *       "avatar": "" // 字符型 用户的头像图片
//     *     }
//     *   ],
//     * "status": "success",
//     * "status_code": 200,
//     * "links": {
//     * "first": "http://manger.test/api/admin?page=1",
//     * "last": "http://manger.test/api/admin?page=19",
//     * "prev": null,
//     * "next": "http://manger.test/api/admin?page=2"
//     * },
//     * "meta": {adminDelete
//     * "current_page": 1, // 当前页
//     * "from": 1, //当前页开始的记录
//     * "last_page": 19, //总页数
//     * "path": "http://manger.test/api/admin",
//     * "per_page": 15,
//     * "to": 15, //当前页结束的记录
//     * "total": 271  // 总条数
//     * }
//     * }
//     *
//     */
//    public function userLoginIpsIndex()
//    {
//        $sWhere = [];
//        $sOrder = 'id DESC';
////        $iLimit = isset(request()->limit) ? request()->limit : '';
////        $iPage = isset(request()->page) ? request()->page : '';
////        // +id -id
////        $iSort = isset(request()->sort) ? request()->sort : '';
////        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
////        $iStatus = isset(request()->status) ? request()->status : '';
////        $sUserName = isset(request()->username) ? request()->username : '';
//        $oAuthAdminList = DB::table('user_login_ips');
//
//        $sTmp = 'DESC';
////        if (substr($iSort, 0, 1) == '-') {
////            $sTmp = 'ASC';
////        }
////        $sOrder = substr($iSort, 1, strlen($iSort));
////        if ($sTmp != '') {
////            $oAuthAdminList->orderby($sOrder, $sTmp);
////        }
////        if ($iStatus !== '') {
////            $oAuthAdminList->where('status', $iStatus);
////        }
////        if ($sUserName !== '') {
////            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
////        }
//        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
////        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
//        $aTmp = [];
//        $aFinal = [];
//        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//
//            $aTmp['id'] = $oAuthAdmin->id;
//            $aTmp['user_id'] = $oAuthAdmin->user_id;
//            $aTmp['username'] = $oAuthAdmin->username;
//            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
//            $aTmp['parent_user'] = $oAuthAdmin->parent_user;
//            $aTmp['parent_user_id'] = $oAuthAdmin->parent_user_id;
//            $aTmp['top_agent_id'] = $oAuthAdmin->top_agent_id;
//            $aTmp['top_agent'] = $oAuthAdmin->top_agent;
//            $aTmp['forefather_ids'] = $oAuthAdmin->forefather_ids;
//            $aTmp['forefathers'] = $oAuthAdmin->forefathers;
//            $aTmp['nickname'] = $oAuthAdmin->nickname;
//            $aTmp['ip'] = $oAuthAdmin->ip;
//            $aTmp['created_at'] = $oAuthAdmin->created_at;
//            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
//
//
//
//
//            $aFinal[] = $aTmp;
//        }
//
//        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
//        $res["list"] = $aFinal;
//        $aFinal['message'] = 'success';
//        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;
//
//        return response()->json($aFinal);
//        return ResultVo::success($res);
//    }
//
//
//
//
//
//    /**
//     * @api {get} /api/admin 显示商户列表
//     * @apiGroup admin
//     *
//     *
//     * @apiSuccessExample 返回商户信息列表
//     * HTTP/1.1 200 OK
//     * {
//     *  "data": [
//     *     {
//     *       "id": 2 // 整数型  用户标识
//     *       "name": "test"  //字符型 用户昵称
//     *       "email": "test@qq.com"  // 字符型 用户email，商户登录时的email
//     *       "role": "admin" // 字符型 角色  可以取得值为admin或editor
//     *       "avatar": "" // 字符型 用户的头像图片
//     *     }
//     *   ],
//     * "status": "success",
//     * "status_code": 200,
//     * "links": {
//     * "first": "http://manger.test/api/admin?page=1",
//     * "last": "http://manger.test/api/admin?page=19",
//     * "prev": null,
//     * "next": "http://manger.test/api/admin?page=2"
//     * },
//     * "meta": {adminDelete
//     * "current_page": 1, // 当前页
//     * "from": 1, //当前页开始的记录
//     * "last_page": 19, //总页数
//     * "path": "http://manger.test/api/admin",
//     * "per_page": 15,
//     * "to": 15, //当前页结束的记录
//     * "total": 271  // 总条数
//     * }
//     * }
//     *
//     */
//    public function userPrizeSetsIndex()
//    {
//        $sWhere = [];
//        $sOrder = 'id DESC';
////        $iLimit = isset(request()->limit) ? request()->limit : '';
////        $iPage = isset(request()->page) ? request()->page : '';
////        // +id -id
////        $iSort = isset(request()->sort) ? request()->sort : '';
////        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
////        $iStatus = isset(request()->status) ? request()->status : '';
////        $sUserName = isset(request()->username) ? request()->username : '';
//        $oAuthAdminList = DB::table('user_prize_sets');
//
//        $sTmp = 'DESC';
////        if (substr($iSort, 0, 1) == '-') {
////            $sTmp = 'ASC';
////        }
////        $sOrder = substr($iSort, 1, strlen($iSort));
////        if ($sTmp != '') {
////            $oAuthAdminList->orderby($sOrder, $sTmp);
////        }
////        if ($iStatus !== '') {
////            $oAuthAdminList->where('status', $iStatus);
////        }
////        if ($sUserName !== '') {
////            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
////        }
//        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
////        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
//        $aTmp = [];
//        $aFinal = [];
//        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//
//            $aTmp['id'] = $oAuthAdmin->id;
//            $aTmp['user_id'] = $oAuthAdmin->user_id;
//            $aTmp['user_parent_id'] = $oAuthAdmin->user_parent_id;
//            $aTmp['user_parent'] = $oAuthAdmin->user_parent;
//            $aTmp['username'] = $oAuthAdmin->username;
//            $aTmp['series_id'] = $oAuthAdmin->series_id;
//            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
//            $aTmp['group_id'] = $oAuthAdmin->group_id;
//            $aTmp['prize_group'] = $oAuthAdmin->prize_group;
//            $aTmp['classic_prize'] = $oAuthAdmin->classic_prize;
//            $aTmp['valid'] = $oAuthAdmin->valid;
//            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
//            $aTmp['created_at'] = $oAuthAdmin->created_at;
//            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
//
//
//
//
//
//            $aFinal[] = $aTmp;
//        }
//
//        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
//        $res["list"] = $aFinal;
//        $aFinal['message'] = 'success';
//        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;
//
//        return response()->json($aFinal);
//        return ResultVo::success($res);
//    }
//
//
//
//
//
//}