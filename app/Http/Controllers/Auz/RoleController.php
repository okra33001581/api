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
 * Class Event - 角色相关控制器
 * @author zebra
 */
class RoleController extends Controller
{
    public function roleIndex()
    {

        $where = [];
        $order = 'id ASC';
        $status = request()->get('status', '');

        $paginate = isset(request()->page) ? request()->page : '';


        if ($status != '') {
            $where[] = ['status', '=', intval($status)];
            $order = '';
        }
        $sName = request()->get('name', '');
        if (!empty($sName)) {
            $where[] = ['name', 'like', $sName . '%'];
            $order = '';
        }
        $iLimit = request()->get('limit', 20);
        $lists = AuthRole::orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($lists);
        $res["list"] = $lists->toArray();

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


    public function roleAuthList()
    {
//        $iId = request()->get('id/d', '');
//        Log::info(request()->all());
        $iId1 = isset(request()->id) ? request()->id : '';

        $checked_keys = [];
        Log::info('id==========' . $iId1);
//        $auth_permission = AuthPermission::where('role_id', $iId)
//            ->select(['permission_rule_id'])
//            ->get();

        $oAuthPermissionList = AuthPermission::where('role_id', $iId1)
            ->get();

        foreach ($oAuthPermissionList as $oAuthPermission) {
            $checked_keys[] = $oAuthPermission->permission_rule_id;
        }

        $rule_list = AuthPermissionRule::getLists([], 'id ASC');

        $merge_list = AuthPermissionRule::cateMerge($rule_list, 'id', 'pid', 0);
        $res['auth_list'] = $merge_list;
        $res['checked_keys'] = $checked_keys;

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

    public function roleAuthListByUser()
    {
        $iId = request()->get('id/d', '');
        Log::info(request()->all());
        $checked_keys = [];
        $auth_permission = AuthPermission::where('role_id', 16)
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


        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

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


//        print_r('aaaaaaaaaaaaaaa');
//        if (!$rule_access) {
        if (count($rule_access) > 0) {
//                print_r('bbbbbbbbbbbbbbbbbbbb');
            foreach ($rule_access as $k => $v) {
                $oAuthPermission = new AuthPermission();
                $oAuthPermission->role_id = $v['role_id'];
                $oAuthPermission->permission_rule_id = $v['permission_rule_id'];
                $oAuthPermission->type = $v['type'];
                $iRet = $oAuthPermission->save();
                print_r('ret=============' . $iRet);

            }
        }
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }


//        if (!$rule_access || !$auth_permission->saveAll($rule_access)){
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }

        $aFinal = [];
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

    public function roleSave()
    {
        $data = request()->post();
        if (empty($data['name']) || empty($data['status'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $sName = $data['name'];
        // 菜单模型
//        $info = AuthRole::where('name',$sName)
//            ->field('name')
//            ->find();

        $info = AuthRole::where('name', $sName)
            ->first();

//        if ($info){
//            return ResultVo::error(ErrorCode::DATA_REPEAT);
//        }

        $now_time = date("Y-m-d H:i:s");
        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_role = new AuthRole();
        $auth_role->name = $sName;
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


        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);

        return ResultVo::success($auth_role);
    }

    public function roleEdit()
    {
        $data = request()->post();
        if (empty($data['id']) || empty($data['name'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $iId = $data['id'];
        $sName = strip_tags($data['name']);
        // 模型
//        $auth_role = AuthRole::where('id',$iId)
//            ->field('id')
//            ->find();

        $auth_role = AuthRole::where('id', $iId)
            ->first();

        if (!$auth_role) {
            return ResultVo::error(ErrorCode::DATA_NOT, "角色不存在");
        }

//        $info = AuthRole::where('name',$sName)
//            ->field('id')
//            ->find();

        $info = AuthRole::where('name', $sName)
            ->first();

        // 判断角色名称 是否重名，剔除自己
//        if (!empty($info['id']) && $info['id'] != $iId) {
//            return ResultVo::error(ErrorCode::DATA_REPEAT);
//        }
        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_role->name = $sName;
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


        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success();
    }

    public function roleDelete()
    {
//        $iId = request()->post('id/d');
        $iId = request()->all()['id'];
        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        if (!AuthRole::where('id', $iId)->delete()) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
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


    public function copyGroup()
    {
//        $iId = request()->post('id/d');
        $iId = request()->all()['id'];
//        if ($iId == '') {
//            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
//        }
//        if (!AuthRole::where('id', $iId)->delete()) {
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }



        $oAuthRole = AuthRole::find($iId);

        $oAuthRoleTmp = new $oAuthRole();
        $oAuthRoleTmp->name = $oAuthRole->name.'copy';
        $oAuthRoleTmp->pid = $oAuthRole->pid;
        $oAuthRoleTmp->status = $oAuthRole->status;
        $oAuthRoleTmp->remark = $oAuthRole->remark.'copy';
        $oAuthRoleTmp->create_time = $oAuthRole->create_time;
        $oAuthRoleTmp->update_time = $oAuthRole->update_time;
        $oAuthRoleTmp->listorder = $oAuthRole->listorder;
        $oAuthRoleTmp->updated_at = $oAuthRole->updated_at;
        $oAuthRoleTmp->created_at = $oAuthRole->created_at;


        $oAuthRoleTmp->save();

        $iIdTmp = $oAuthRoleTmp->id;


        $sSql = "INSERT INTO auth_permissions (role_id, permission_rule_id, type, updated_at, created_at) 
                    select ".$iIdTmp.", permission_rule_id, type, updated_at, created_at
                    from auth_permissions
                    where role_id = ".$iId;


        Log::info($sSql);
//        die;

        DB::insert($sSql);

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

}