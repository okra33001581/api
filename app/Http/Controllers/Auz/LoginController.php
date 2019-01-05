<?php

namespace App\Http\Controllers\Auz;

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


class LoginController extends Controller
{


    const List = [];
    const count = 100;

    const baseContent = '<p>我是测试数据我是测试数据</p><p><img src="https://wpimg.wallstcn.com/4c69009c-0fd4-4153-b112-6cb53d1cf943"></p>';
    const image_uri = 'https://wpimg.wallstcn.com/e4558086-631c-425c-9430-56ffb46e70b3';


    public function index()
    {
        print_r('aaaaaaaaaaaaaaaaaaaaaaaaaaa');
        die;
        return Article::all();
    }

    public function show($id)
    {
        print_r('2222222');
        die;
        return Article::find($id);
    }

    public function store(Request $request)
    {
        return Article::create($request->all());
    }

    public function update()
    {

        header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Methods", "POST, GET, OPTIONS,DELETE,PUT");
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, X-CSRF-TOKEN');
//header("Access-Control-Allow-Credentials", "true");
//header("Allow", "GET, HEAD, POST");


        print_r('aaaaaaaaaaaaaaa');

        Log::info(request()->all);
        Log::info('123===============');
//        Log::info($id);

//        print_r(request()->all);
//        print_r('===============');
////        print_r($id);
//        die;


        die;
        $article = Article::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

//    public function delete(Request $request, $id)
//    {
//        $article = Article::findOrFail($id);
//        $article->delete();
//
//        return 204;
//    }


    public function login()
    {
//
//        print_r('dddddddddddddddddddddddddddddd');
//

        $dataTmp['roles'] = ['admin'];
        $dataTmp['token'] = 'admin';
        $dataTmp['introduction'] = '我是超级管理员';
        $dataTmp['avatar'] = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $dataTmp['name'] = 'Super Admin';
        $data['admin'] = $dataTmp;

        $dataTmp['roles'] = ['editor'];
        $dataTmp['token'] = 'editor';
        $dataTmp['introduction'] = '我是编辑';
        $dataTmp['avatar'] = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $dataTmp['name'] = 'Normal Editor';
        $data['editor'] = $dataTmp;

        return response()->json($data);
        return Article::find($id);
    }


    public function info()
    {

        $dataTmp['roles'] = ['admin'];
        $dataTmp['token'] = 'admin';
        $dataTmp['introduction'] = '我是超级管理员';
        $dataTmp['avatar'] = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $dataTmp['name'] = 'Super Admin';
        $data['admin'] = $dataTmp;


        return response()->json($data['admin']);

        return $data['admin'];

        $obj = (object)array('1' => 'foo');

        return $obj;

        $data['roles'] = 'admin';
        $data['token'] = 'admin';
        $data['introduction'] = '我是超级管理员';
        $data['avatar'] = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $data['name'] = 'Super Admin';

        $object = (object)$data;
        return $object;
        return Article::find($id);
    }


    public function list()
    {


//        print_r(request()->all());

//        print_r(request()->limit);


        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


//        print_r(substr($iSort,0,1));
//        print_r(substr($iSort,strlen($iSort)-2,strlen($iSort)));
        $sTmp = ' DESC';
        if (substr($iSort, 0, 1) == '-') {
            $sTmp = ' ASC';
        }


        $sDesc = substr($iSort, strlen($iSort) - 2, strlen($iSort)) . $sTmp;
//        die;
////        print_r(request()->all()['limit']);
////        print_r(request::get(limit'limit', null));
//        die;


//        Model::offset(0)->limit(10)->get()

        $oEventList = DB::table('events')->orderByRaw($sDesc)->offset(($iPage - 1) * $iLimit)->limit($iLimit)->get();


//        $oEventList = DB::table('events')->orderByRaw($sDesc)->take($iPage)->paginate($iLimit);

//        orderByRaw('updated_at - created_at DESC')


//        $oEventList = Event::get()->paginate($iLimit);

//        print_r($oEventList);
//paginate
//        die;


//        foreach ($oEventList as $oEvent) {
//
//            print_r($oEvent->id);
//
//        }
//
//
//        die;


        $aTmp = [];
        $aFinal = [];
        $iCount = 100;
        $i = 0;
        foreach ($oEventList as $oEvent) {
            $i++;
            $aTmp['id'] = $oEvent->id;
            $aTmp['identifier'] = $oEvent->identifier;
            $aTmp['title'] = $oEvent->title;
            $aTmp['description'] = $oEvent->description;
            $aTmp['calculate_cycle'] = $oEvent->calculate_cycle;
            $aTmp['view_type'] = $oEvent->view_type;
            $aTmp['is_team_event'] = $oEvent->is_team_event;
            $aTmp['is_show_team_leader'] = $oEvent->is_show_team_leader;
            $aTmp['is_show_team_member'] = $oEvent->is_show_team_member;
            $aTmp['is_receive'] = $oEvent->is_receive;
            $aTmp['after_receive_day_limit'] = $oEvent->after_receive_day_limit;
            $aTmp['status'] = $oEvent->status;
            $aTmp['is_get_mulite_prize'] = $oEvent->is_get_mulite_prize;
            $aTmp['start_time'] = $oEvent->start_time;
            $aTmp['end_time'] = $oEvent->end_time;
            $aTmp['created_at'] = $oEvent->created_at;
            $aTmp['updated_at'] = $oEvent->updated_at;
            $aTmp['expired_at'] = $oEvent->expired_at;
            $aTmp['is_single_condition'] = $oEvent->is_single_condition;
            $aTmp['url'] = $oEvent->url;
            $aTmp['terminal_id'] = $oEvent->terminal_id;
            $aTmp['icon'] = $oEvent->icon;
            $aTmp['large_icon'] = $oEvent->large_icon;
            $aTmp['color'] = $oEvent->color;
            $aTmp['mobile_title'] = $oEvent->mobile_title;


//            $aTmp['id'] = $i;
//            $aTmp['timestamp'] =strtotime('now');   // 1
//            $aTmp['author'] = $i;   // 1
//            $aTmp['reviewer'] = $i;
////            $aTmp['title'] = $i;   // 1
//            $aTmp['content_short'] = $i;
//            $aTmp['content'] = $i;
//            $aTmp['forecast'] = $i;
//            $aTmp['importance'] = 2;   // 1
////            $aTmp['type'] = ['CN', 'US', 'JP', 'EU'];
//
//            $aTmp['type'] = 'CN';   // 1
//            $aTmp['status'] = 'published';   // 1
////            $aTmp['status'] = ['published', 'draft', 'deleted'];
//            $aTmp['display_time'] = $i;
//            $aTmp['comment_disabled'] = true;
//            $aTmp['pageviews'] = $i;
//            $aTmp['image_uri'] = $i;
//            $aTmp['platforms'] = ['platforms01'];

            $aFinal[] = $aTmp;
        }
//        for ($i = 0; $i < $iCount; $i++) {
//
//        }
        $aFinal['items'] = $aFinal;


        $oEventList = DB::table('events')->orderByRaw($sDesc)->get();


        $aFinal['total'] = count($oEventList);


        return response()->json($aFinal);

//        $object = (object)$aFinal;
//        return $object;

        return response()->json($aFinal);

    }


    public function login1()
    {


        $aData['id'] = 1;
        $aData['token'] = 'eyJpZHNzIjoiJDJ5JDEwJGNmMVpVb3BxM2lEUUk0bllVZXkxenUzajM0QVJlYmEuS3B4aDZ1MkNkY1J4clF6SE10MTRLIn0=_2018-04-27';
        return response()->json($aData);
//
//        data: {
//        status: "1",
//id: 1,
//username: "admin",
//avatar: "",
//authRules: ["admin"],
//token:
//"eyJpZHNzIjoiJDJ5JDEwJGNmMVpVb3BxM2lEUUk0bllVZXkxenUzajM0QVJlYmEuS3B4aDZ1MkNkY1J4clF6SE10MTRLIn0=_2018-04-27"
//}


        die;
//        $aFinal = ['123'];
//
//
//        return response()->json($aFinal);


//        if (!request()->isPost()){
//            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
//        }

        $user_name = request()->post('userName');
        $pwd = request()->post('pwd');
//        if (!$user_name || !$pwd){
//            return ResultVo::error(ErrorCode::VALIDATION_FAILED, "username 不能为空。 password 不能为空。");
//        }


//        print_r('dfadfad');
//        die;


        $oAuthAdmin = AuthAdmin::find(1);


//        $admin = AuthAdmin::where('username',$user_name)
//            ->field('id,username,avatar,password,status')
//            ->find();

//        if (empty($admin) ||  PassWordUtils::create($pwd) != $admin->password){
//            return ResultVo::error(ErrorCode::USER_AUTH_FAIL);
//        }
//        if ($admin->status != 1){
//            return ResultVo::error(ErrorCode::USER_NOT_PERMISSION);
//        }

        $info = $oAuthAdmin->toArray();

        unset($info['password']);


        // 权限信息
        $authRules = [];
        if ($user_name == 'admin') {
            $authRules = ['admin'];
        } else {


//            $role_ids = AuthRoleAdmin::where('admin_id','=',1)->column('role_id');
            $oAuthRoleAdmin = AuthRoleAdmin::where('admin_id', '=', 1)->first();
            if (is_object($oAuthRoleAdmin)) {
                $oAuthPermissionList = AuthPermission::where('role_id', 'in', $oAuthRoleAdmin->role_id)
                    ->get();
                foreach ($oAuthPermissionList as $oAuthPermission) {
                    $oAuthPermissionRule = AuthPermissionRule::where('id', '=', $oAuthPermission->permission_rule_id)->first();
                    if (count($oAuthPermissionRule) > 0) {
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
//        $loginInfo = AuthAdmin::loginInfo($info['id'],$info);


        $loginInfo = AuthAdmin::find(1);

//        $admin->last_login_ip = request()->ip();
//        $admin->last_login_time = date("Y-m-d H:i:s");
//        $admin->save();
        $res = [];
        $res['id'] = !empty($loginInfo['id']) ? intval($loginInfo['id']) : 0;
        $res['token'] = !empty($loginInfo['token']) ? $loginInfo['token'] : '';

        return response()->json($res);


//        return ResultVo::success($res);

        die;

    }

    public function out()
    {
        $aData['code'] = 0;
        $aData['message'] = 'success';
        return response()->json($aData);
    }


    public function password()
    {
        $aData['code'] = 0;
        $aData['message'] = 'success';
        return response()->json($aData);
    }


    public function userinfo()
    {
        $aData['status'] = 1;
        $aData['id'] = 1;
        $aData['username'] = 'admin';
        $aData['avatar'] = '';
        $aData['authRules'] = ["admin"];
        $aData['token'] = 'eyJpZHNzIjoiJDJ5JDEwJGNmMVpVb3BxM2lEUUk0bllVZXkxenUzajM0QVJlYmEuS3B4aDZ1MkNkY1J4clF6SE10MTRLIn0=_2018-04-27';
        return response()->json($aData);
    }



    // authAdmin.js
//    public function adminindex()
//    {
//
//        $aTmp = [];
//        $aFinal = [];
//        $aTmp['id']=1;
//        $aTmp['username']="admin";
//        $aTmp['avatar']=null;
//        $aTmp['tel']='admin';
//        $aTmp['email']='lmxdawn@gmail.com';
//        $aTmp['status']=1;
//        $aTmp['last_login_ip']="127.0.0.1";
//        $aTmp['last_login_time']=1493103488;
//        $aTmp['create_time']=1487868050;
//        $aTmp['roles']=[];
//
//        $aData['total'] = 1;
//        $aData['list'] = $aTmp;
//        return response()->json($aData);
//
//    }

//    public function adminroleList()
//    {
//
//        $aTmp = [];
//        $aFinal = [];
//        $aTmp['id']=1;
//        $aTmp['name']="超级管理员";
//
//        $aData['total'] = 1;
//        $aData['list'] = $aTmp;
//        return response()->json($aData);
//
//    }


//    public function adminsave()
//    {
//        $aTmp['id']=2;
//        $aTmp['username']="test";
//        $aData['password'] = 1;
//        $aData['status'] = $aTmp;
//        $aData['roles'] = [1];
//        return response()->json($aData);
//
//    }
//
//    public function adminedit()
//    {
//        $aTmp['code']=2;
//        $aTmp['message']="success";
//        return response()->json($aData);
//    }
//
//
//    public function admindel()
//    {
//        $aTmp['code']=2;
//        $aTmp['message']="success";
//        return response()->json($aTmp);
//
//    }


// PermissionRuleController.php


    /**
     * 列表
     */
    public function permissionRuleIndex()
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
//        $lists = AuthPermissionRule::getLists($where,$order);

        $lists = AuthPermissionRule::getListsTmp($where, $order);


        $merge_list = AuthPermissionRule::cateMerge($lists, 'id', 'pid', 0);
        $res['list'] = $merge_list;


        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        return response()->json($aFinal);
        return ResultVo::success($res);

    }


    /*
    * 获取树形结构
    */
    public function permissionRuleTree()
    {
        $where = [];
        $order = 'id ASC';

        $lists = AuthPermissionRule::getLists($where, $order);
        $tree_list = AuthPermissionRule::cateTree($lists, 'id', 'pid', 0);
        $res = [];
        $res['list'] = $tree_list;

        return response()->json($res);
        return ResultVo::success($res);
    }


    /**
     * 添加
     */
    public function permissionRuleSave()
    {

        $data = request()->post();

//        $data = $this->request->post();
        if (empty($data['name']) || empty($data['status'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $name = strtolower(strip_tags($data['name']));
        // 菜单模型
//        $info = AuthPermissionRule::where('name',$name)
//            ->field('name')
//            ->find();

        $info = AuthPermissionRule::where('name', $name)
            ->first();

        if ($info) {
            return ResultVo::error(ErrorCode::DATA_REPEAT, "权限已经存在");
        }

        $now_time = date("Y-m-d H:i:s");
        $status = !empty($data['status']) ? $data['status'] : 0;
        $pid = !empty($data['pid']) ? $data['pid'] : 0;
        if ($pid) {
//            $info = AuthPermissionRule::where('id',$pid)
//                ->field('id')
//                ->find();

            $info = AuthPermissionRule::where('id', $pid)
                ->first();


            if (!$info) {
                return ResultVo::error(ErrorCode::NOT_NETWORK);
            }
        }
        $auth_permission_rule = new AuthPermissionRule();
        $auth_permission_rule->pid = $pid;
        $auth_permission_rule->name = $name;
        $auth_permission_rule->title = !empty($data['title']) ? $data['title'] : ' ';
        $auth_permission_rule->status = $status;
        $auth_permission_rule->condition = !empty($data['condition']) ? $data['condition'] : ' ';
        $auth_permission_rule->listorder = !empty($data['listorder']) ? strip_tags($data['listorder']) : 0;
        $auth_permission_rule->create_time = $now_time;
        $auth_permission_rule->update_time = $now_time;
        $result = $auth_permission_rule->save();

        if (!$result) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }


        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $auth_permission_rule;

        return response()->json($aFinal);

        return ResultVo::success($auth_permission_rule);
    }

    /**
     * 编辑
     */
    public function permissionRuleEdit()
    {


        $data = request()->all();


//        $data = $this->request->post();
        if (empty($data['id']) || empty($data['name'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $id = $data['id'];
        $name = strtolower(strip_tags($data['name']));
        // 模型
//        $auth_permission_rule = AuthPermissionRule::where('id',$id)
//            ->field('id')
//            ->find();

        $auth_permission_rule = AuthPermissionRule::where('id', $id)
            ->first();

        if (!$auth_permission_rule) {
            return ResultVo::error(ErrorCode::DATA_NOT, "角色不存在");
        }

//        $idInfo = AuthPermissionRule::where('name',$name)
//            ->field('id')
//            ->find();

        $idInfo = AuthPermissionRule::where('name', $name)
            ->first();


        // 判断名称 是否重名，剔除自己
        if (!empty($idInfo['id']) && $idInfo['id'] != $id) {
            return ResultVo::error(ErrorCode::DATA_REPEAT, "权限名称已存在");
        }

        $pid = isset($data['pid']) ? $data['pid'] : 0;
        // 判断父级是否存在
        if ($pid) {
//            $info = AuthPermissionRule::where('id',$pid)
//                ->field('id')
//                ->find();

            $info = AuthPermissionRule::where('id', $pid)
                ->first();

            if (!$info) {
                return ResultVo::error(ErrorCode::NOT_NETWORK);
            }
        }
        $AuthRuleList = AuthPermissionRule::all();
        // 查找当前选择的父级的所有上级
        $parents = AuthPermissionRule::queryParentAll($AuthRuleList, 'id', 'pid', $pid);
        if (in_array($id, $parents)) {
            return ResultVo::error(ErrorCode::NOT_NETWORK, "不能把自身/子级作为父级");
        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_permission_rule->pid = $pid;
        $auth_permission_rule->name = $name;
        $auth_permission_rule->title = !empty($data['title']) ? $data['title'] : ' ';
        $auth_permission_rule->status = $status;
        $auth_permission_rule->condition = !empty($data['condition']) ? $data['condition'] : ' ';
        $auth_permission_rule->listorder = !empty($data['listorder']) ? strip_tags($data['listorder']) : 0;
        $auth_permission_rule->update_time = date("Y-m-d H:i:s");
        $result = $auth_permission_rule->save();

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
     * 删除
     */
    public function permissionRuleDelete()
    {
//        $id = request()->post('id/d');
//        if (empty($id)){
        $id = request()->all()['id'];

        Log::info('++++++++++++');
        Log::info($id);
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }

        // 下面有子节点，不能删除
//        $sub = AuthPermissionRule::where('pid',$id)->field('id')->find();
        $sub = AuthPermissionRule::where('pid', $id)->get();
        if (count($sub) > 0) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        if (!AuthPermissionRule::where('id', $id)->delete()) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        // 删除授权的权限
        AuthPermission::where('permission_rule_id', $id)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        return response()->json($aFinal);

        return ResultVo::success();

    }

// RoleController.php

    /**
     * 列表
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
        die;
        return ResultVo::success($res);

    }


    /*
     * 获取授权列表
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


    /*
     * 授权
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
     * 添加
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
     * 编辑
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
        if (!empty($info['id']) && $info['id'] != $id) {
            return ResultVo::error(ErrorCode::DATA_REPEAT);
        }

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
     * 删除
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


// AdminController.php


    /**
     * 列表
     */
    public function adminIndex()
    {

        $where = [];
        $order = 'id DESC';
        $status = request()->get('status', '');
        if ($status !== '') {
            $where[] = ['status', '=', intval($status)];
            $order = '';
        }
        $username = request()->get('username', '');
        if (!empty($username)) {
            $where[] = ['username', 'like', $username . '%'];
            $order = '';
        }
        $role_id = request()->get('role_id/id', '');
        if ($role_id !== '') {
            $admin_ids = AuthRoleAdmin::where('role_id', $role_id)->column('admin_id');
            $where[] = ['id', 'in', $admin_ids];
            $order = '';
        }
        $limit = request()->get('limit/d', 20);
        //分页配置
        $paginate = [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
        ];
//        $lists = AuthAdmin::where($where)
////            ->field('id,username,avatar,tel,email,status,last_login_ip,last_login_time,create_time')
//            ->orderby('id', 'DESC')->get();
////            ->paginate($paginate);


        $lists = AuthAdmin::get();
//            ->paginate($paginate);


//        print_r($where);

        foreach ($lists as $k => $v) {
            $v['avatar'] = PublicFileUtils::createUploadUrl($v['avatar']);
            $roles = AuthRoleAdmin::where('admin_id', $v['id'])->select('role_id')->get();
            $temp_roles = [];
            if ($roles) {
                $temp_roles = $roles->toArray();
                $temp_roles = array_column($temp_roles, 'role_id');
            }
            $v['roles'] = $temp_roles;
            $lists[$k] = $v;
        }

        $res = [];
        $res["total"] = count($lists);
        $res["list"] = $lists->toArray();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        return response()->json($aFinal);
        return ResultVo::success($res);

    }

    /*
     * 角色列表
     */
    public function adminRoleList()
    {
        $where = [];
        $limit = request()->get('limit/d', 20);
        //分页配置
//        $paginate = [
//            'type' => 'bootstrap',
//            'var_page' => 'page',
//            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
//        ];
        $iTmp = ($limit <= 0 || $limit > 20) ? 20 : $limit;
        $lists = AuthRole::where($where)
            ->paginate($iTmp);

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();
        return response()->json($res);
        return ResultVo::success($res);


    }


    /**
     * 添加
     */
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

        $info = AuthAdmin::where('username', $username)
            ->first();


//        if ($info){
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

    /**
     * 编辑
     */
    public function adminEdit()
    {
        $data = request()->post();
        if (empty($data['id']) || empty($data['username'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $id = $data['id'];
        $username = strip_tags($data['username']);
        // 模型
//        $auth_admin = AuthAdmin::where('id',$id)
//            ->field('id,username')
//            ->find();


        $auth_admin = AuthAdmin::where('id', $id)
            ->first();


        if (!$auth_admin) {
            return ResultVo::error(ErrorCode::DATA_NOT, "管理员不存在");
        }
        $login_info = $auth_admin;
        $login_user_name = isset($login_info['username']) ? $login_info['username'] : '';
        // 如果是超级管理员，判断当前登录用户是否匹配
        if ($auth_admin->username == 'admin' && $login_user_name != $auth_admin->username) {
            return ResultVo::error(ErrorCode::DATA_NOT, "最高权限用户，无权修改");
        }

//        $info = AuthAdmin::where('username',$username)
//            ->field('id')
//            ->find();

        $info = AuthAdmin::where('username', $username)
            ->first();

        // 判断username 是否重名，剔除自己
//        if (!empty($info['id']) && $info['id'] != $id){
//            return ResultVo::error(ErrorCode::DATA_REPEAT, "管理员已存在");
//        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $password = isset($data['password']) ? PassWordUtils::create($data['password']) : '';
        $auth_admin->username = $username;
        if ($password) {
            $auth_admin->password = $password;
        }
        $auth_admin->status = $status;
        $result = $auth_admin->save();

        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];
        if (!$result) {
            // 没有做任何更改
            $temp_roles = AuthRoleAdmin::where('admin_id', $id)->field('role_id')->select();
            if ($temp_roles) {
                $temp_roles = $temp_roles->toArray();
                $temp_roles = array_column($temp_roles, 'role_id');
            }
            // 没有差值，权限也没做更改
            if ($roles == $temp_roles) {
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


//            dddd
//            $auth_role_admin->saveAll($temp);

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

    /**
     * 删除
     */
    public function adminDelete()
    {


//        Log::info('++++++++++++');
//        Log::info(request()->all()['id']);
//        die;
//        $id = request()->post('id/d');
        $id = request()->all()['id'];

        Log::info('++++++++++++');
        Log::info($id);
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$id)->field('username')->find();
        $auth_admin = AuthAdmin::where('id', $id)->first();
        if (!$auth_admin || $auth_admin['username'] == 'admin' || !$auth_admin->delete()) {
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


// LoginController.php


    /**
     * 获取用户信息
     */
    public function loginIndex()
    {



        print_r('aaaaaaa');
        die;
//        $aFinal = [];
//
//        $aData["id"] = 1;
//        $aData["token"] = 'eyJpZHNzIjoiJDJ5JDEwJGNmMVpVb3BxM2lEUUk0bllVZXkxenUzajM0QVJlYmEuS3B4aDZ1MkNkY1J4clF6SE10MTRLIn0=_2018-04-27';

//        $aFinal['message'] = 'success';
//        $aFinal['code'] = 0;
//        $aFinal['data'] = $aData;
//        code
//        success

//        return ResultVo::success($aData);


//        return response()->json($aFinal);


//        Redis::set('user1','3333');
//        $user = Redis::get('user1');
//
//
//        return response()->json($user);


//        var_dump($user);
//
//
//        die;

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

        Log::info('++++++++++++++++++++++++++++++++++++++++++++++++');
        Log::info($info);
        Log::info('------------------------------------------------');

        $loginInfo = AuthAdmin::loginInfo($info['id'], $info);
        $admin->last_login_ip = request()->ip();
        $admin->last_login_time = date("Y-m-d H:i:s");
        $admin->save();
        $res = [];
        $res['id'] = !empty($loginInfo['id']) ? intval($loginInfo['id']) : 0;
        $res['token'] = !empty($loginInfo['token']) ? $loginInfo['token'] : '';


//        [2019-01-02 12:03:36] local.INFO: array (
//        'id' => 1,
//        'token' => 'tZgF3xxjxNDn_YXp8WM_wIJ1Pgk',
//        )

//        Log::info($res);

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


//        header('Access-Control-Allow-Origin:*');
//
//        header('Access-Control-Allow-Headers:cache-control,x-adminid,x-token');


        Log::info('huangqiu');

        $id = request()->header('X-Adminid');
        $token = request()->header('X-Token');


//        Log::info('id==================='.$id);
//        Log::info('token==================='.$token);
        if (!$id || !$token) {
//            return ResultVo::error(ErrorCode::LOGIN_FAILED);
        }


//        Log::info('abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');


        $res = AuthAdmin::loginInfo($id, (string)$token);


        Log::info('ligang');

        Log::info($res);

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


//AdController.php

    /**
     * 列表
     */
    public function adIndex()
    {

        $where = [];
        $title = request()->get('title', '');
        if ($title !== '') {
            $where[] = ['title', '=', $title];
        }
        $limit = request()->get('limit/d', 20);
        //分页配置
        $paginate = [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
        ];
//        $lists = Ad::where($where)
//            ->field('ad_id,title,describe,jump_type,link_url,pic,wxa_appid,wxa_path,extra_data,env_version,status')
//            ->paginate($paginate);

        $lists = Ad::where($where)
            ->paginate($limit);


        foreach ($lists as $k => $v) {
            $temp = $v;
            $temp['pic_url'] = PublicFileUtils::createUploadUrl($v['pic']);
            $temp['jump_type'] = !empty($v['jump_type']) ? $v['jump_type'] : '';
            $temp['link_url'] = !empty($v['link_url']) ? $v['link_url'] : '';
            $temp['wxa_appid'] = !empty($v['wxa_appid']) ? $v['wxa_appid'] : '';
            $temp['wxa_path'] = !empty($v['wxa_path']) ? $v['wxa_path'] : '';
            $temp['extra_data'] = !empty($v['extra_data']) ? $v['extra_data'] : '';
            $temp['env_version'] = !empty($v['env_version']) ? $v['env_version'] : '';
        }

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();

        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        return response()->json($aFinal);

        return ResultVo::success($res);

    }

    /**
     * 添加
     */
    public function adSave()
    {
        $data = request()->post();
        if (empty($data['title']) || empty($data['jump_type']) || empty($data['pic'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $status = isset($data['status']) ? $data['status'] : 0;
        $ad = new Ad();
        $ad->title = $data['title'];
        $ad->describe = !empty($data['describe']) ? $data['describe'] : '0';
        $ad->jump_type = $data['jump_type'];
        $ad->link_url = !empty($data['link_url']) ? $data['link_url'] : '0';
        $ad->pic = $data['pic'];
        $ad->wxa_appid = !empty($data['wxa_appid']) ? $data['wxa_appid'] : '0';
        $ad->wxa_path = !empty($data['wxa_path']) ? $data['wxa_path'] : '0';
        $ad->extra_data = !empty($data['extra_data']) ? $data['extra_data'] : '0';
        $ad->env_version = !empty($data['env_version']) ? $data['env_version'] : '0';
        $ad->status = $status;
        $ad->create_time = date("Y-m-d H:i:s");
        $ad->update_time = date("Y-m-d H:i:s");
        $result = $ad->save();

        if (!$result) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        return ResultVo::success($ad);
    }

    /**
     * 编辑
     */
    public function adEdit()
    {
        $data = request()->post();
        if (empty($data['ad_id']) || empty($data['title']) || empty($data['jump_type']) || empty($data['pic'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $ad_id = $data['ad_id'];
        // 模型
        $ad = Ad::where('ad_id', $ad_id)
            ->field('ad_id')
            ->find();
        if (!$ad) {
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        $status = isset($data['status']) ? $data['status'] : 0;
        $ad->title = $data['title'];
        $ad->describe = !empty($data['describe']) ? $data['describe'] : '0';
        $ad->jump_type = $data['jump_type'];
        $ad->link_url = !empty($data['link_url']) ? $data['link_url'] : '0';
        $ad->pic = $data['pic'];
        $ad->wxa_appid = !empty($data['wxa_appid']) ? $data['wxa_appid'] : '0';
        $ad->wxa_path = !empty($data['wxa_path']) ? $data['wxa_path'] : '0';
        $ad->extra_data = !empty($data['extra_data']) ? $data['extra_data'] : '0';
        $ad->env_version = !empty($data['env_version']) ? $data['env_version'] : '0';
        $ad->status = $status;
        $result = $ad->save();
        if (!$result) {
            return ResultVo::error(ErrorCode::DATA_CHANGE);
        }

        return ResultVo::success();
    }

    /**
     * 删除
     */
    public function adDelete()
    {
        $ad_id = request()->post('ad_id/d');
        if (empty($ad_id)) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        if (!Ad::where('ad_id', $ad_id)->delete()) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }



//SiteController.php


    /**
     * 列表
     */
    public function siteIndex()
    {

        $where = [];
        $site_id = request()->get('site_id/d', '');
        if ($site_id !== '') {
            $where[] = ['site_id', '=', intval($site_id)];
        }
        $limit = request()->get('limit/d', 20);
        //分页配置
        $paginate = [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
        ];
//        $lists = AdSite::where($where)
//            ->field('site_id,site_name,describe,ad_ids,update_time')
//            ->paginate($paginate);

        $lists = AdSite::where($where)
            ->paginate($limit);


        foreach ($lists as $v) {
            $ad_ids = !empty($v['ad_ids']) ? explode(",", $v['ad_ids']) : [];
            foreach ($ad_ids as $key => $val) {
                $ad_ids[$key] = intval($val);
            }
            $v['ad_ids'] = $ad_ids;
        }
        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();


        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        return response()->json($aFinal);
        return ResultVo::success($res);

    }

    /**
     * 给广告位选择广告时调用
     */
    public function siteAdList()
    {
        $where = [];
        $limit = request()->get('adLimit/d', 20);
        //分页配置
        $paginate = [
            'type' => 'bootstrap',
            'var_page' => 'adPage',
            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
        ];
        // 查询当前广告位的广告id
        $ad_ids = request()->get('ad_ids');
        $ad_ids = !empty($ad_ids) ? explode(",", $ad_ids) : [];
//        $lists = Ad::where($where)
//            ->field('ad_id,title,describe,status')
//            ->paginate($paginate);

        $lists = Ad::where($where)
//            ->field('ad_id,title,describe,status')
            ->paginate($limit);
        $data = [];
        foreach ($lists as $k => $v) {
            $temp = [];
            $temp['key'] = $v['ad_id'];
            $temp['label'] = $v['ad_id'] . '-' . $v['title'] . '-' . $v['describe'];
            $temp['disabled'] = $v['status'] !== 1;
            $temp['describe'] = $v['describe'];
            $data[] = $temp;
            foreach ($ad_ids as $key => $val) {
                if ($v['ad_id'] == $val) {
                    unset($ad_ids[$key]);
                }
            }
        }
        // 查询该页没有的广告
        if (count($lists) > 0 && $ad_ids) {
            $temp_data = Ad::whereIn('ad_id', $ad_ids)
                ->field('ad_id,title,describe,status')
                ->select();
            foreach ($temp_data as $k => $v) {
                $temp = [];
                $temp['key'] = $v['ad_id'];
                $temp['label'] = $v['ad_id'] . '-' . $v['title'] . '-' . $v['describe'];
                $temp['disabled'] = $v['status'] !== 1;
                $temp['describe'] = $v['describe'];
                $data[] = $temp;
            }
        }


        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $data;


        return response()->json($aFinal);

        return ResultVo::success($data);
    }

    /**
     * 添加
     */
    public function siteSave()
    {
        $data = request()->post();
        if (empty($data['site_name'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $ad_site = new AdSite();
        $ad_site->site_name = $data['site_name'];
        $ad_site->describe = !empty($data['describe']) ? $data['describe'] : ' ';
        $ad_site->ad_ids = !empty($data['ad_ids']) ? implode(",", $data['ad_ids']) : '0';
        $ad_site->create_time = date("Y-m-d H:i:s");
        $ad_site->update_time = date("Y-m-d H:i:s");
        $result = $ad_site->save();

        if (!$result) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        $ad_site->ad_ids = !empty($data['ad_ids']) ? $data['ad_ids'] : [];


        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $ad_site;


        return response()->json($aFinal);


        return ResultVo::success($ad_site);
    }

    /**
     * 编辑
     */
    public function siteEdit()
    {


        $data = request()->all();

//        $data = request()->post();
//        if (empty($data['site_id']) || empty($data['site_name'])) {
//            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
//        }


//        if ($data['site_id'] == '' || $data['site_name'] == '') {
//            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
//        }

        $site_id = $data['site_id'];
        // 模型
//        $ad_site = AdSite::where('site_id', $site_id)
//            ->field('site_id')
//            ->find();


        $ad_site = AdSite::where('site_id', $site_id)
            ->first();



//        if (is_object($ad_site)) {
//            return ResultVo::error(ErrorCode::DATA_NOT);
//        }
        $ad_site->site_name = $data['site_name'];
        $ad_site->describe = !empty($data['describe']) ? $data['describe'] : ' ';
        $ad_site->ad_ids = !empty($data['ad_ids']) ? implode(",", $data['ad_ids']) : '0';
        $result = $ad_site->save();
        if (!$result) {
            return ResultVo::error(ErrorCode::DATA_CHANGE);
        }

        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $ad_site;


        return response()->json($aFinal);



        return ResultVo::success();
    }

    /**
     * 删除
     */
    public function siteDelete()
    {
        $site_id = request()->post('site_id/d');
        if (empty($site_id)) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        // 这里广告位不让删除
        return ResultVo::error(ErrorCode::NOT_NETWORK, "此功能目前不开放");
        if (!AdSite::where('site_id', $site_id)->delete()) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();
    }

//ResourceController.php
    /**
     * 列表
     */
    public function resourceIndex()
    {
        $where = [];
        $type = request()->get('type/d', 0);
        $where[] = ['type', '=', $type];
        $tagId = request()->get('tagId/d', 0);
        if (!empty($tagId)) {
            $where[] = ['tag_id', '=', $tagId];
        }
        $size = request()->get('size/d', 20);
        //分页配置
        $paginate = [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'list_rows' => ($size <= 0 || $size > 20) ? 20 : $size,
        ];
        $file_resource = new FileResource();
//        $lists = $file_resource->where($where)
//            ->field('id,type,filename,path,size,ext,create_time')
//            ->paginate($paginate);

        $lists = $file_resource->where($where)
            ->paginate($size);

        foreach ($lists as $k => $v) {
            $v['url'] = PublicFileUtils::createUploadUrl($v['path']);
            $v['create_time'] = strtotime($v['create_time']);
            $lists[$k] = $v;
        }

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();


        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        return response()->json($aFinal);

        return ResultVo::success($res);
    }

    /**
     * 添加
     */
    public function add()
    {
        $type = request()->param('type/d',0);
        $tag_id = request()->post('tagId/d',0);
        $filename = request()->post("filename");
        $path = request()->post("path");
        $path = $path ? $path : request()->post("key");
        if (!$path) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL, "文件路径不存在");
        }

        $size = request()->post("size/d");
        $ext = request()->post("ext");
        $file_resource = new FileResource();
        $file_resource->tag_id = $tag_id;
        $file_resource->type = $type;
        $file_resource->filename = $filename;
        $file_resource->path = $path;
        $file_resource->size = $size;
        $file_resource->ext = $ext;
        $file_resource->create_time = date("Y-m-d H:i:s");
        $file_resource->save();
        $file_resource->create_time = date("Y-m-d H:i:s");
        $file_resource->url = PublicFileUtils::createUploadUrl($path);
        $file_resource->id = intval($file_resource->id);
        return ResultVo::success($file_resource);
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        /**
         * @var File $uploadFile
         */
        if (!request()->isPost()) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }

        // 上传文件
        $uploadName = request()->param('uploadName');
        $uploadFile = request()->file($uploadName);
        if (empty($uploadFile)) {
            return ResultVo::error(ErrorCode::DATA_NOT, "没有文件上传");
        }

        $type = request()->param("type/d", 0);
        $exts = request()->param("exts");
        $size = request()->param("size/d");
        $config = [];
        if ($size > 0) {
            $config['size'] = $size;
        }
        if ($exts) {
            $config['ext'] = $exts;
        }
        $basepath = FileResource::getBasePath();
        $resource_path = FileResource::$RESOURCES_PATH . FileResource::getTypePath($type);
        $filepath = $basepath . $resource_path ;
        $info = $uploadFile->validate($config)->move($filepath);
        if (!$info) {
            return ResultVo::error(ErrorCode::DATA_NOT, $uploadFile->getError());
        }

        $saveName = $info->getSaveName();
        $path = $resource_path . $saveName;
        $path = str_replace("\\", "/", $path);

        $res = [];
        $res["path"] = $path;
        return ResultVo::success($res);
    }


//ResourceTagController.php
    /**
     * 列表
     */
    public function resourceTagIndex()
    {
        $limit = request()->get('limit/d', 20);
        //分页配置
        $paginate = [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
        ];
        $where = [];
//        $lists = FileResourceTag::where($where)
//            ->field('id,tag')
//            ->paginate($paginate);

        $lists = FileResourceTag::where($where)
            ->paginate($limit);


        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();


        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        return response()->json($aFinal);

        return ResultVo::success($res);
    }

    /**
     * 添加
     */
    public function resourceTagAdd() {

        $tag = request()->post('tag');
        if (empty($tag)){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }

        $file_resource_tag = new FileResourceTag();
        $file_resource_tag->tag = $tag;
        $file_resource_tag->create_time = date("Y-m-d H:i:s");
        $file_resource_tag->save();
        $file_resource_tag->id = intval($file_resource_tag->id);
        $file_resource_tag->create_time = date("Y-m-d H:i:s");

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        return response()->json($aFinal);

        return ResultVo::success($file_resource_tag);
    }

//UploadController.php

    /**
     * 文件对应的class name
     * @var array
     */
    private static $extClaseArr = [
        'html' => 'web',
        'js' => 'fileicon-sys-s-code',
        'css' => 'fileicon-sys-s-code',
        'txt' => 'fileicon-small-txt',
        'gif' => 'fileicon-small-pic',
        'png' => 'fileicon-small-pic',
        'jpg' => 'fileicon-small-pic',
        'zip' => 'fileicon-small-zip',
    ];

    public static $RESOURCES_PATH = 'resources' . DIRECTORY_SEPARATOR;

    /**
     * 获取上传文件的根路径
     */
    private static function getBasePath()
    {
        return Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
    }

    public function newDir()
    {
        // 创建目录
        $pathName = request()->post("pathName");
        $basePath = self::getBasePath();
        $pathName = self::$RESOURCES_PATH . $pathName;
        $pathName = trim($pathName, '/'); // 去掉最前或者最后的 /
        $pathName = trim($pathName, '\\'); // 去掉最前或者最后的 \
        $dirname = $basePath . $pathName;
        $dirname = str_replace(' ', '', $dirname);
        if (!file_exists($dirname)) {
            // 目录不存在
            return ResultVo::error(ErrorCode::DATA_NOT, "目录不存在");
        }
        $filename = request()->post('filename');
        $filename = trim($filename, DIRECTORY_SEPARATOR); // 去掉最后一个 / 并且加上一个 /
        $dirname = $dirname . DIRECTORY_SEPARATOR . $filename;
        $dirname = str_replace(' ', '', $dirname);
        if (file_exists($dirname)) {
            // 目录已存在
            return ResultVo::error(ErrorCode::DATA_NOT, "文件夹已存在");
        }
        try {
            // 如果含有中文
            if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $dirname) > 0) {
                return ResultVo::error(ErrorCode::DATA_NOT, "不能含有中文");
            }
            $dirname = str_replace(' ', '', $dirname);
            if (!mkdir($dirname, 0755, true)) {
                // 目录不存在
                return ResultVo::error(ErrorCode::DATA_NOT, "无权限创建目录");
            }
        } catch (\Exception $exception) {
            return ResultVo::error(ErrorCode::DATA_NOT, "无权限创建");
        }
        $path = $pathName . '/' . $filename;
        $path = str_replace("\\", "/", $path);
        $res = array(
            "path" => $path,
            "filename" => $filename,
            "className" => '',
            'url' => PublicFileUtils::createUploadUrl($path),
            'mtime' => time(),
            "is_dir" => 1,
            "fileExt" => '',
            "size" => 0
        );

        return ResultVo::success($res);
    }

    /**
     * 上传图片
     * @return \think\response\Json
     * @throws JsonException
     */
    public function newFile()
    {
        /**
         * @var File $uploadFile
         */
        // 上传文件
        $uploadName = request()->param('uploadName');
        $uploadFile = request()->file($uploadName);
        if (empty($uploadFile)) {
            return ResultVo::error(ErrorCode::DATA_NOT, "没有文件上传");
        }
        $pinYinName = request()->param('pinYinName', '');
        // 如果没有拼音的名称并且含有中文
        if (!$pinYinName && preg_match('/[\x{4e00}-\x{9fa5}]/u', $uploadFile->getInfo('name')) > 0) {
            return ResultVo::error(ErrorCode::DATA_NOT, "不能含有中文");
        }
        $pathName = request()->param("pathName");
        $pathName = trim($pathName, '/'); // 去掉最前或者最后的 /
        $pathName = trim($pathName, '\\'); // 去掉最前或者最后的 \
        $pathName = self::$RESOURCES_PATH . $pathName;
        $basePath = self::getBasePath();
        $dirname = $basePath . $pathName;
        if (!is_dir(dirname($dirname))) {
            // 目录不存在
            return ResultVo::error(ErrorCode::DATA_NOT, "目录不存在");
        }
        $exts = request()->param("exts");
        $size = request()->param("size/d");
        $path = $pathName ? $pathName . DIRECTORY_SEPARATOR : $pathName;
        $config = [];
        if ($size > 0) {
            $config['size'] = $size;
        }
        if ($exts) {
            $config['ext'] = $exts;
        }
        // 如果拼音的名称为空，则用户本来的名称
        $savename = !$pinYinName ? $uploadFile->getInfo('name') : $pinYinName;
        $savename = str_replace(' ', '', $savename);
        //dump($file);exit;
        // 移动到框架应用根目录/public/uploads/ 目录下
        $filepath = self::getBasePath() . $path;
        $info = $uploadFile->validate($config)->move($filepath, $savename, false);
        if (!$info) {
            return ResultVo::error(ErrorCode::DATA_NOT, $uploadFile->getError());
        }
        $filename = $info->getSaveName();
        $path = $path . $filename;
        $path = str_replace("\\", "/", $path);
        $fileExt = $info->getExtension();
        $className = isset(self::$extClaseArr[$fileExt]) ? self::$extClaseArr[$fileExt] : 'default-small';
        $res = array(
            "path" => $path,
            "filename" => $info->getFilename(),
            "className" => $className,
            'url' => PublicFileUtils::createUploadUrl($path),
            'mtime' => time(),
            "is_dir" => 0,
            "fileExt" => $fileExt,
            "size" => $info->getSize()
        );
        return ResultVo::success($res);
    }

    /*
     * 获取上传图片的列表
     */
    public function imageList()
    {
        $pathName = request()->get("pathName", "");
        $pathName = urldecode($pathName);
        $pathName = trim($pathName, '/'); // 去掉最前或者最后的 /
        $pathName = trim($pathName, '\\'); // 去掉最前或者最后的 \
        $pathName = self::$RESOURCES_PATH . $pathName;
        $pathName = trim($pathName, '/'); // 去掉最前或者最后的 /
        $pathName = trim($pathName, '\\'); // 去掉最前或者最后的 \
        $baseUrl = PublicFileUtils::createUploadUrl();
        /* 获取参数 */
        $size = request()->get('size/d', 20);
        $page = request()->get('page/d', 1);
        $basePath = self::getBasePath();

        // 检查资源文件是否存在
        if (!self::checkPath($basePath)) {
            return ResultVo::error(ErrorCode::DATA_NOT, "目录不存在");
        }

        /* 获取文件列表 */
        $files = self::getFiles($basePath, $pathName, $baseUrl);
        /* 获取指定范围的列表 */
        $len = count($files);
        $page = $page <= 0 ? 1 : $page;
        $countpage = ceil($len / $size); // 计算总页面数
        $page = $page > $countpage ? $countpage : $page;
        $start = $page * $size - $size;
        $end = $start + $size;
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--) {
            $list[] = $files[$i];
        }
        $res = [];
        $res['total'] = $len;
        $res['list'] = $list;
        $res['pathName'] = $pathName;
        return ResultVo::success($res);
    }


    /**
     * 获取目录下的文件/文件夹
     * @param $basePath
     * @param $pathName
     * @param $baseUrl
     * @return array|null
     */
    private static function uploadGetFiles($basePath, $pathName, $baseUrl)
    {
        $path = $basePath . $pathName;
        if (!is_dir($path)) return null;
        if (substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        $files = [];
        while (false !== ($filename = readdir($handle))) {
            if ($filename != '.' && $filename != '..') {
                $path2 = $path . $filename;
                $is_dir = is_dir($path2);
                $className = "dir-small";
                $fileExt = '';
                $mtime = file_exists($path2) ? filemtime($path2) : 0;
                $size = !$is_dir && file_exists($path2) ? filesize($path2) : 0;
                if (!$is_dir) {
                    $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $className = isset(self::$extClaseArr[$fileExt]) ? self::$extClaseArr[$fileExt] : 'default-small';
                }
                $path3 = $pathName . '/' . $filename;
                $path3 = trim($path3, DIRECTORY_SEPARATOR);
                $path3 = str_replace('\\', "/", $path3);
                $url = $baseUrl . $path3;
                $files[] = array(
                    "path" => $path3,
                    "filename" => $filename,
                    "className" => $className,
                    'url' => $url,
                    'mtime' => $mtime,
                    "is_dir" => $is_dir ? 1 : 0,
                    "fileExt" => $fileExt,
                    "size" => $size
                );
            }
        }
        return $files;
    }

    /**
     * 检查目录是否可写
     * @access protected
     * @param  string $path 目录
     * @return boolean
     */
    private static function checkPath($path)
    {
        if (is_dir($path)) {
            return true;
        }
        if (mkdir($path, 0755, true)) {
            return true;
        }
        return false;
    }




}