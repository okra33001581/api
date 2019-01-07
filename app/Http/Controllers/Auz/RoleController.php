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

class RoleController extends Controller
{


// RoleController.php

    /**
     * @api {get} /api/roleIndex 取得角色信息列表
     * @apiGroup role
     *
     *
     * @apiSuccessExample 返回角色信息列表
     * HTTP/1.1 200 OK
     * {
     *  "data": [
     *     {
     *       "id": 2 // 整数型  用户标识
     *       "name": "test"  //字符型 用户昵称
     *       "email": "test@qq.com"  // 字符型 用户email，管理员登录时的email
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
     * "meta": {
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
    public function roleIndex()
    {

        $where = [];
        $order = 'id ASC';
        $status = request()->get('status', '');
        if ($status !== '') {
            $where[] = ['status', '=', intval($status)];
            $order = '';
        }
        $name = request()->get('name', '');
        if (!empty($name)) {
            $where[] = ['name', 'like', $name . '%'];
            $order = '';
        }
        $limit = request()->get('limit/d', 20);
        //分页配置
//        $paginate = [
//            'type' => 'bootstrap',
//            'var_page' => 'page',
//            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
//        ];
//        $lists = AuthRole::where($where)->orderby('id','asc')->get();
//            ->paginate($paginate);

        $lists = AuthRole::orderby('id', 'asc')->get();

        $res = [];
        $res["total"] = count($lists);
        $res["list"] = $lists->toArray();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * @api {post} /api/roleAuthList  取得角色信息相关
     * @apiGroup role
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
    public function roleAuthList()
    {
        $id = request()->get('id/d', '');
        $checked_keys = [];
        $auth_permission = AuthPermission::where('role_id', $id)
            ->select(['permission_rule_id'])
            ->get();
        foreach ($auth_permission as $k => $v) {
            $checked_keys[] = $v['permission_rule_id'];
        }

        $rule_list = AuthPermissionRule::getLists([], 'id ASC');

        $merge_list = AuthPermissionRule::cateMerge($rule_list, 'id', 'pid', 0);
        $res['auth_list'] = $merge_list;
        $res['checked_keys'] = $checked_keys;

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * @api {post} /api/roleAuth  角色赋值
     * @apiGroup role
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
    public function roleAuth()
    {
        $data = request()->post();
        $role_id = isset($data['role_id']) ? $data['role_id'] : '';
        if (!$role_id) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        $auth_rules = isset($data['auth_rules']) ? $data['auth_rules'] : [];
        $rule_access = [];
        foreach ($auth_rules as $key => $val) {
            $rule_access[$key]['role_id'] = $role_id;
            $rule_access[$key]['permission_rule_id'] = $val;
            $rule_access[$key]['type'] = 'admin';
        }

        //先删除
        $auth_permission = new AuthPermission();
        $auth_permission->where(['role_id' => $role_id])->delete();


        if (!$rule_access) {
            if (count($rule_access) > 0) {

                foreach ($rule_access as $k => $v) {
                    $auth_permission = new AuthPermission();
                    $auth_permission['role_id'] = $v['role_id'];
                    $auth_permission['permission_rule_id'] = $v['permission_rule_id'];
                    $auth_permission['type'] = $v['type'];
                    $iRet = $auth_permission->save();
                }
            }
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }


//        if (!$rule_access || !$auth_permission->saveAll($rule_access)){
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }

        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        return response()->json($aFinal);

        return ResultVo::success();

    }

    /**
     * @api {post} /api/roleSave  角色信息保存
     * @apiGroup role
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
    public function roleSave()
    {
        $data = request()->post();
        if (empty($data['name']) || empty($data['status'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $name = $data['name'];
        // 菜单模型
//        $info = AuthRole::where('name',$name)
//            ->field('name')
//            ->find();

        $info = AuthRole::where('name', $name)
            ->first();

//        if ($info){
//            return ResultVo::error(ErrorCode::DATA_REPEAT);
//        }

        $now_time = date("Y-m-d H:i:s");
        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_role = new AuthRole();
        $auth_role->name = $name;
        $auth_role->status = $status;
        $auth_role->remark = isset($data['remark']) ? strip_tags($data['remark']) : '';
        $auth_role->create_time = $now_time;
        $auth_role->update_time = $now_time;
        $result = $auth_role->save();

        if (!$result) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        return response()->json($aFinal);

        return ResultVo::success($auth_role);
    }

    /**
     * @api {post} /api/roleEdit  角色信息编辑
     * @apiGroup role
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
    public function roleEdit()
    {
        $data = request()->post();
        if (empty($data['id']) || empty($data['name'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $id = $data['id'];
        $name = strip_tags($data['name']);
        // 模型
//        $auth_role = AuthRole::where('id',$id)
//            ->field('id')
//            ->find();

        $auth_role = AuthRole::where('id', $id)
            ->first();

        if (!$auth_role) {
            return ResultVo::error(ErrorCode::DATA_NOT, "角色不存在");
        }

//        $info = AuthRole::where('name',$name)
//            ->field('id')
//            ->find();

        $info = AuthRole::where('name', $name)
            ->first();

        // 判断角色名称 是否重名，剔除自己
//        if (!empty($info['id']) && $info['id'] != $id) {
//            return ResultVo::error(ErrorCode::DATA_REPEAT);
//        }
        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_role->name = $name;
        $auth_role->status = $status;
        $auth_role->remark = isset($data['remark']) ? strip_tags($data['remark']) : '';
        $auth_role->update_time = date("Y-m-d H:i:s");
        $auth_role->listorder = isset($data['listorder']) ? intval($data['listorder']) : 999;
        $result = $auth_role->save();

        if (!$result) {
            return ResultVo::error(ErrorCode::DATA_CHANGE);
        }

        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success();
    }

    /**
     * @api {post} /api/roleDelete  角色信息删除
     * @apiGroup role
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
    public function roleDelete()
    {
//        $id = request()->post('id/d');
        $id = request()->all()['id'];
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        if (!AuthRole::where('id', $id)->delete()) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;
        return response()->json($aFinal);

        return ResultVo::success();
    }
}