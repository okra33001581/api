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

//use App\vendor\Redis;
//use Redis;
use Illuminate\Support\Facades\Redis;

///home/ok/apidemo/vendor/predis
///

//use App\common\vo\ResultVo;

//app\common\vo

class AdminController extends Controller
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






}