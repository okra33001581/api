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
use App\model\AdSite;
use App\model\Ad;

use App\common\utils\PublicFileUtils;
use App\common\utils\PassWordUtils;
//use App\vendor\Redis;
//use Redis;
use Illuminate\Support\Facades\Redis;

///home/ok/apidemo/vendor/predis
///

//use App\common\vo\ResultVo;

//app\common\vo

class EventsController extends Controller
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

//        Log::info(request()->all);
//        Log::info('123===============');
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
        if (substr($iSort,0,1) == '-') {
            $sTmp = ' ASC';
        }


        $sDesc = substr($iSort,strlen($iSort)-2,strlen($iSort)).$sTmp;
//        die;
////        print_r(request()->all()['limit']);
////        print_r(request::get(limit'limit', null));
//        die;



//        Model::offset(0)->limit(10)->get()

        $oEventList = DB::table('events')->orderByRaw($sDesc)->offset(($iPage-1)*$iLimit)->limit($iLimit)->get();



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
        if ($user_name == 'admin'){
            $authRules = ['admin'];
        }else{




//            $role_ids = AuthRoleAdmin::where('admin_id','=',1)->column('role_id');
            $oAuthRoleAdmin = AuthRoleAdmin::where('admin_id','=',1)->first();
            if (is_object($oAuthRoleAdmin)){
                $oAuthPermissionList = AuthPermission::where('role_id','in',$oAuthRoleAdmin->role_id)
                    ->get();
                foreach ($oAuthPermissionList as $oAuthPermission){
                    $oAuthPermissionRule = AuthPermissionRule::where('id','=',$oAuthPermission->permission_rule_id)->first();
                    if (count($oAuthPermissionRule) > 0){
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

//    public function adminedit()
//    {
//        $aTmp['code']=2;
//        $aTmp['message']="success";
//        return response()->json($aData);
//    }


    public function admindel()
    {
        $aTmp['code']=2;
        $aTmp['message']="success";
        return response()->json($aTmp);

    }


// PermissionRuleController.php


    /**
     * 列表
     */
    public function permissionRuleIndex()
    {

        $where = [];
        $order = 'id ASC';
        $status = request()->get('status', '');
        if ($status !== ''){
            $where[] = ['status','=',intval($status)];
            $order = '';
        }
        $name = request()->get('name', '');
        if (!empty($name)){
            $where[] = ['name','like',$name . '%'];
            $order = '';
        }
//        $lists = AuthPermissionRule::getLists($where,$order);

        $lists = AuthPermissionRule::getListsTmp($where,$order);



        $merge_list = AuthPermissionRule::cateMerge($lists,'id','pid',0);
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

        $lists = AuthPermissionRule::getLists($where,$order);
        $tree_list = AuthPermissionRule::cateTree($lists,'id','pid',0);
        $res = [];
        $res['list'] = $tree_list;

        return response()->json($res);
        return ResultVo::success($res);
    }


    /**
     * 添加
     */
    public function save(){
        $data = $this->request->post();
        if (empty($data['name']) || empty($data['status'])){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $name = strtolower(strip_tags($data['name']));
        // 菜单模型
        $info = AuthPermissionRule::where('name',$name)
            ->field('name')
            ->find();
        if ($info){
            return ResultVo::error(ErrorCode::DATA_REPEAT, "权限已经存在");
        }

        $now_time = date("Y-m-d H:i:s");
        $status = !empty($data['status']) ? $data['status'] : 0;
        $pid = !empty($data['pid']) ? $data['pid'] : 0;
        if ($pid){
            $info = AuthPermissionRule::where('id',$pid)
                ->field('id')
                ->find();
            if (!$info){
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

        if (!$result){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        return ResultVo::success($auth_permission_rule);
    }

    /**
     * 编辑
     */
    public function edit(){
        $data = $this->request->post();
        if (empty($data['id']) || empty($data['name'])){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $id = $data['id'];
        $name = strtolower(strip_tags($data['name']));
        // 模型
        $auth_permission_rule = AuthPermissionRule::where('id',$id)
            ->field('id')
            ->find();
        if (!$auth_permission_rule){
            return ResultVo::error(ErrorCode::DATA_NOT, "角色不存在");
        }

        $idInfo = AuthPermissionRule::where('name',$name)
            ->field('id')
            ->find();
        // 判断名称 是否重名，剔除自己
        if (!empty($idInfo['id']) && $idInfo['id'] != $id){
            return ResultVo::error(ErrorCode::DATA_REPEAT, "权限名称已存在");
        }

        $pid = isset($data['pid']) ? $data['pid'] : 0;
        // 判断父级是否存在
        if ($pid){
            $info = AuthPermissionRule::where('id',$pid)
                ->field('id')
                ->find();
            if (!$info){
                return ResultVo::error(ErrorCode::NOT_NETWORK);
            }
        }
        $AuthRuleList = AuthPermissionRule::all();
        // 查找当前选择的父级的所有上级
        $parents = AuthPermissionRule::queryParentAll($AuthRuleList,'id','pid',$pid);
        if (in_array($id,$parents)){
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

        if (!$result){
            return ResultVo::error(ErrorCode::DATA_CHANGE);
        }

        return ResultVo::success();
    }


    /**
     * 删除
     */
    public function delete(){
        $id = request()->post('id/d');
        if (empty($id)){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }

        // 下面有子节点，不能删除
        $sub = AuthPermissionRule::where('pid',$id)->field('id')->find();
        if ($sub){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        if (!AuthPermissionRule::where('id',$id)->delete()){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        // 删除授权的权限
        AuthPermission::where('permission_rule_id',$id)->delete();

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
        if ($status !== ''){
            $where[] = ['status','=',intval($status)];
            $order = '';
        }
        $name = request()->get('name', '');
        if (!empty($name)){
            $where[] = ['name','like',$name . '%'];
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



        $lists = AuthRole::orderby('id','asc')->get();


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
        $id = request()->get('id/d','');
        $checked_keys = [];
        $auth_permission = AuthPermission::where('role_id',$id)
            ->select(['permission_rule_id'])
            ->get();
        foreach ($auth_permission as $k=>$v){
            $checked_keys[] = $v['permission_rule_id'];
        }

        $rule_list = AuthPermissionRule::getLists([],'id ASC');

        $merge_list = AuthPermissionRule::cateMerge($rule_list,'id','pid',0);
        $res['auth_list'] = $merge_list;
        $res['checked_keys'] = $checked_keys;




        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;




        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * 添加
     */
    public function roleSave(){
        $data = request()->post();
        if (empty($data['name']) || empty($data['status'])){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $name = $data['name'];
        // 菜单模型
//        $info = AuthRole::where('name',$name)
//            ->field('name')
//            ->find();

        $info = AuthRole::where('name',$name)
            ->first();

        if ($info){
            return ResultVo::error(ErrorCode::DATA_REPEAT);
        }

        $now_time = date("Y-m-d H:i:s");
        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_role = new AuthRole();
        $auth_role->name = $name;
        $auth_role->status = $status;
        $auth_role->remark = isset($data['remark']) ? strip_tags($data['remark']) : '';
        $auth_role->create_time = $now_time;
        $auth_role->update_time = $now_time;
        $result = $auth_role->save();

        if (!$result){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }


        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $auth_role;

        return response()->json($aFinal);

        return ResultVo::success($auth_role);
    }


    /**
     * 编辑
     */
    public function roleEdit(){
        $data = request()->post();
        if (empty($data['id']) || empty($data['name'])){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $id = $data['id'];
        $name = strip_tags($data['name']);
        // 模型
//        $auth_role = AuthRole::where('id',$id)
//            ->field('id')
//            ->find();

        $auth_role = AuthRole::where('id',$id)
            ->first();
        if (!$auth_role){
            return ResultVo::error(ErrorCode::DATA_NOT, "角色不存在");
        }

//        $info = AuthRole::where('name',$name)
//            ->field('id')
//            ->find();
        $info = AuthRole::where('name',$name)
            ->first();
        // 判断角色名称 是否重名，剔除自己
        if (!empty($info['id']) && $info['id'] != $id){
            return ResultVo::error(ErrorCode::DATA_REPEAT);
        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_role->name = $name;
        $auth_role->status = $status;
        $auth_role->remark = isset($data['remark']) ? strip_tags($data['remark']) : '';
        $auth_role->update_time = date("Y-m-d H:i:s");
        $auth_role->listorder = isset($data['listorder']) ? intval($data['listorder']) : 999;
        $result = $auth_role->save();

        if (!$result){
            return ResultVo::error(ErrorCode::DATA_CHANGE);
        }



        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $auth_role;

        return response()->json($auth_role);

        return ResultVo::success();
    }


    /*
     * 授权
     */
    public function roleAuth(){
        $data = request()->post();
        $role_id = isset($data['role_id']) ? $data['role_id'] : '';
        if (!$role_id){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        $auth_rules = isset($data['auth_rules']) ? $data['auth_rules'] : [];
        $rule_access = [];
        foreach ($auth_rules as $key=>$val){
            $rule_access[$key]['role_id'] = $role_id;
            $rule_access[$key]['permission_rule_id'] = $val;
            $rule_access[$key]['type'] = 'admin';
        }



//        Log::info('role_id======================================================================'.$role_id);
//        Log::info('+++++++++++++++++++++++++');
//        Log::info($rule_access);

        //先删除
        $auth_permission = new AuthPermission();
        $auth_permission->where(['role_id' => $role_id])->delete();
//        if (!$rule_access || !$auth_permission->saveAll($rule_access)){
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        if (count($rule_access) > 0){
            foreach ($rule_access as $k=>$v) {
                $oAuthPermission = new AuthPermission();
                $oAuthPermission->role_id = $v['role_id'];
                $oAuthPermission->permission_rule_id = $v['permission_rule_id'];
                $oAuthPermission->type = $v['type'];
                $result = $oAuthPermission->save();
                if (!$result){
                    return ResultVo::error(ErrorCode::NOT_NETWORK);
                }
            }

//            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }


        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $auth_role;

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
        if ($status !== ''){
            $where[] = ['status','=',intval($status)];
            $order = '';
        }
        $username = request()->get('username', '');
        if (!empty($username)){
            $where[] = ['username','like',$username . '%'];
            $order = '';
        }
        $role_id = request()->get('role_id/id', '');
        if ($role_id !== ''){
            $admin_ids = AuthRoleAdmin::where('role_id',$role_id)->column('admin_id');
            $where[] = ['id','in',$admin_ids];
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
            $roles = AuthRoleAdmin::where('admin_id',$v['id'])->select('role_id')->get();
            $temp_roles = [];
            if ($roles){
                $temp_roles = $roles->toArray();
                $temp_roles = array_column($temp_roles,'role_id');
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


        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);


    }


    /**
     * 添加
     */
    public function adminSave(){
        $data = request()->post();
        if (empty($data['username']) || empty($data['password'])){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $username = $data['username'];
        // 模型
//        $info = AuthAdmin::where('username',$username)
//            ->field('username')
//            ->find();
        $info = AuthAdmin::where('username',$username)
            ->first();
        if ($info){
            return ResultVo::error(ErrorCode::DATA_REPEAT);
        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_admin = new AuthAdmin();
        $auth_admin->username = $username;
        $auth_admin->password = PassWordUtils::create($data['password']);
        $auth_admin->status = $status;
        $auth_admin->create_time = date("Y-m-d H:i:s");
        $result = $auth_admin->save();

        if (!$result){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];

        //$adminInfo = $this->adminInfo; // 登录用户信息
        $admin_id = $auth_admin->id;
        if ($roles){
            $temp = [];
            foreach ($roles as $key => $value){
                $temp[$key]['role_id'] = $value;
                $temp[$key]['admin_id'] = $admin_id;
            }
            //添加用户的角色
            $auth_role_admin = new AuthRoleAdmin();
            $auth_role_admin->saveAll($temp);
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
    public function adminEdit(){
        $data = request()->post();
        if (empty($data['id']) || empty($data['username'])){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $id = $data['id'];
        $username = strip_tags($data['username']);
        // 模型
//        $auth_admin = AuthAdmin::where('id',$id)
//            ->field('id,username')
//            ->find();


        $auth_admin = AuthAdmin::where('id',$id)
            ->first();


        if (!$auth_admin){
            return ResultVo::error(ErrorCode::DATA_NOT, "管理员不存在");
        }
        $login_info = $auth_admin->toArray();

        $login_user_name = isset($login_info['username']) ? $login_info['username'] : '';
        // 如果是超级管理员，判断当前登录用户是否匹配
        if ($auth_admin->username == 'admin' && $login_user_name != $auth_admin->username){
            return ResultVo::error(ErrorCode::DATA_NOT, "最高权限用户，无权修改");
        }

//        $info = AuthAdmin::where('username',$username)
//            ->field('id')
//            ->find();

        $info = AuthAdmin::where('username',$username)
            ->first();

        // 判断username 是否重名，剔除自己
        if (!empty($info['id']) && $info['id'] != $id){
            return ResultVo::error(ErrorCode::DATA_REPEAT, "管理员已存在");
        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $password = isset($data['password']) ? PassWordUtils::create($data['password']) : '';
        $auth_admin->username = $username;
        if ($password){
            $auth_admin->password = $password;
        }
        $auth_admin->status = $status;
        $result = $auth_admin->save();

        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];
        if (!$result){
            // 没有做任何更改
            $temp_roles = AuthRoleAdmin::where('admin_id',$id)->field('role_id')->select();
            if ($temp_roles){
                $temp_roles = $temp_roles->toArray();
                $temp_roles = array_column($temp_roles,'role_id');
            }
            // 没有差值，权限也没做更改
            if ($roles == $temp_roles){
                return ResultVo::error(ErrorCode::DATA_CHANGE);
            }
        }


        if ($roles){
            // 先删除
            AuthRoleAdmin::where('admin_id',$id)->delete();
            $temp = [];
            foreach ($roles as $key => $value){
                $temp[$key]['role_id'] = $value;
                $temp[$key]['admin_id'] = $id;
            }
            //添加用户的角色
            $auth_role_admin = new AuthRoleAdmin();


            if (count($temp) > 0) {
                foreach ($temp as $k=>$v) {
                    $oAuthRoleAdmin = new AuthRoleAdmin();
                    $oAuthRoleAdmin->role_id = $v['role_id'];
                    $oAuthRoleAdmin->admin_id = $v['admin_id'];
                    $iRet = $oAuthRoleAdmin->save();


                }
            }



//            Log::info('=================================================');
//            Log::info($temp);




//            array (
//                0 =>
//                    array (
//                        'role_id' => 20,
//                        'admin_id' => 2,
//                    ),
//            )


//            $auth_role_admin->saveAll($temp);
        }

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $auth_admin;

        return response()->json($aFinal);

        return ResultVo::success();
    }



// LoginController.php


    /**
     * 获取用户信息
     */
    public function loginIndex()
    {
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
        if (!$user_name || !$pwd){
//            return ResultVo::error(ErrorCode::VALIDATION_FAILED, "username 不能为空。 password 不能为空。");
        }
//        $user_name = 'admin';
        $admin = AuthAdmin::where('username',$user_name)
            ->first();




        if (empty($admin) ||  PassWordUtils::create($pwd) != $admin->password){
//            return ResultVo::error(ErrorCode::USER_AUTH_FAIL);
        }
        if ($admin->status != 1){
            return ResultVo::error(ErrorCode::USER_NOT_PERMISSION);
        }
        $info = $admin->toArray();
        unset($info['password']);
        // 权限信息
        $authRules = [];
        if ($user_name == 'admin'){
            $authRules = ['admin'];
        }else{
//            $oAuthRoleAdminList = AuthRoleAdmin::where('admin_id',$admin->id)->select('role_id')->get();
            $oAuthRoleAdminList = AuthRoleAdmin::where('admin_id','=',$admin->id)->first();




            if (is_object($oAuthRoleAdminList)){
//                $oAuthPermissionList = AuthPermission::where('role_id','in',$oAuthRoleAdminList->role_id)
//                    ->select(['permission_rule_id'])
//                    ->get();

                $oAuthPermissionList = AuthPermission::where('role_id','=',$oAuthRoleAdminList->role_id)
                    ->get();
                foreach ($oAuthPermissionList as $oAuthPermission){
//                    $oAuthPermissionRule = AuthPermissionRule::where('id',$oAuthPermission->permission_rule_id)->select('name')->first();
                    $oAuthPermissionRule = AuthPermissionRule::where('id',$oAuthPermission->permission_rule_id)->first();
                    if (is_object($oAuthPermissionRule)){
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

//        Log::info('++++++++++++++++++++++++++++++++++++++++++++++++');
//        Log::info($info);
//        Log::info('------------------------------------------------');

        $loginInfo = AuthAdmin::loginInfo($info['id'],$info);
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

        Log::info('================================================');
        Log::info($info);


        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * 获取登录用户信息
     */
    public function loginInfoa()
    {



//        header('Access-Control-Allow-Origin:*');
//
//        header('Access-Control-Allow-Headers:cache-control,x-adminid,x-token');



//        Log::info('huangqiu');

        $id = request()->header('X-Adminid');
        $token = request()->header('X-Token');


//        Log::info('id==================='.$id);
//        Log::info('token==================='.$token);
        if (!$id || !$token) {
//            return ResultVo::error(ErrorCode::LOGIN_FAILED);
        }


//        Log::info('abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');



        $res = AuthAdmin::loginInfo($id, (string)$token);


//        Log::info('ligang');
//
//        Log::info($res);

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




//SiteController.php

    /**
     * 列表
     */
    public function siteIndex()
    {

        $where = [];
        $site_id = request()->get('site_id/d', '');
        if ($site_id !== ''){
            $where[] = ['site_id','=',intval($site_id)];
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
    public function siteList() {
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
    public function siteSave(){
        $data = request()->post();
        if (empty($data['site_name'])){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $ad_site = new AdSite();
        $ad_site->site_name = $data['site_name'];
        $ad_site->describe = !empty($data['describe']) ? $data['describe'] : ' ';
        $ad_site->ad_ids = !empty($data['ad_ids']) ? implode(",", $data['ad_ids']) : '0';
        $ad_site->create_time = date("Y-m-d H:i:s");
        $ad_site->update_time = date("Y-m-d H:i:s");
        $result = $ad_site->save();

        if (!$result){
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
    public function siteEdit(){
        $data = request()->post();
        if (empty($data['site_id']) || empty($data['site_name'])){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $site_id = $data['site_id'];
        // 模型
//        $ad_site = AdSite::where('site_id',$site_id)
//            ->field('site_id')
//            ->find();

        $ad_site = AdSite::where('site_id',$site_id)
            ->first();
        if (!$ad_site){
            return ResultVo::error(ErrorCode::DATA_NOT);
        }
        $ad_site->site_id = $data['site_id'];
        $ad_site->site_name = $data['site_name'];
        $ad_site->describe = !empty($data['describe']) ? $data['describe'] : ' ';
        $ad_site->ad_ids = !empty($data['ad_ids']) ? implode(",", $data['ad_ids']) : '0';
        $result = $ad_site->save();
        if (!$result){
            return ResultVo::error(ErrorCode::DATA_CHANGE);
        }


        $aFinal = [];
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $data;


        return response()->json($aFinal);


        return ResultVo::success();
    }

    /**
     * 删除
     */
    public function siteDelete(){
        $site_id = request()->post('site_id/d');
        if (empty($site_id)){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        // 这里广告位不让删除
        return ResultVo::error(ErrorCode::NOT_NETWORK, "此功能目前不开放");
        if (!AdSite::where('site_id',$site_id)->delete()){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }

//AdController.php

    /**
     * 列表
     */
    public function adIndex()
    {

        $where = [];
        $title = request()->get('title', '');
        if ($title !== ''){
            $where[] = ['title','=',$title];
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

//        if (!empty($where)) {
//            $lists = Ad::where($where)
//                ->paginate($limit);
//        } else {
//            $lists = Ad::paginate($limit);
//        }


//        $lists = Ad::get()->toArray();

                    $lists = Ad::paginate($limit);


        print_r($lists);

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

//        $res = [];
//        $res["total"] = count($lists);
//        $res["list"] = $lists;


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
    public function adSave(){
        $data = request()->post();
        if (empty($data['title']) || empty($data['jump_type']) || empty($data['pic'])){
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

        if (!$result){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        return ResultVo::success($ad);
    }

    /**
     * 编辑
     */
    public function adEdit(){
        $data = request()->post();
        if (empty($data['ad_id']) || empty($data['title']) || empty($data['jump_type']) || empty($data['pic'])){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $ad_id = $data['ad_id'];
        // 模型
        $ad = Ad::where('ad_id',$ad_id)
            ->field('ad_id')
            ->find();
        if (!$ad){
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
        if (!$result){
            return ResultVo::error(ErrorCode::DATA_CHANGE);
        }

        return ResultVo::success();
    }

    /**
     * 删除
     */
    public function adDelete(){
        $ad_id = request()->post('ad_id/d');
        if (empty($ad_id)){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        if (!Ad::where('ad_id',$ad_id)->delete()){
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }



}
