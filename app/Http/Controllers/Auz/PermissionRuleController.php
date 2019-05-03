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
use App\model\Department;
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
 * Class Event - 权限列表相关控制器
 * @author zebra
 */
class PermissionRuleController extends Controller
{
// PermissionRuleController.php


    public function permissionRuleDeptIndex()
    {

        $where = [];
        $order = 'id ASC';
        $status = request()->get('status', '');
        if ($status != '') {
            $where[] = ['status', '=', intval($status)];
            $order = '';
        }
        $sName = request()->get('name', '');
        if (!empty($sName)) {
            $where[] = ['name', 'like', $sName . '%'];
            $order = '';
        }
//        $lists = AuthPermissionRule::getLists($where,$order);

        $lists = Department::getListsTmp($where, $order);

        $merge_list = Department::cateMerge($lists, 'id', 'pid', 0);
        $res['list'] = $merge_list;

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

    public function permissionRuleIndex()
    {

        $where = [];
        $order = 'id ASC';
        $status = request()->get('status', '');
        if ($status != '') {
            $where[] = ['status', '=', intval($status)];
            $order = '';
        }
        $sName = request()->get('name', '');
        if (!empty($sName)) {
            $where[] = ['name', 'like', $sName . '%'];
            $order = '';
        }
//        $lists = AuthPermissionRule::getLists($where,$order);

        $lists = AuthPermissionRule::getListsTmp($where, $order);

        $merge_list = AuthPermissionRule::cateMerge($lists, 'id', 'pid', 0);
        $res['list'] = $merge_list;

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

    public function ruleStatusSave($iId = null)
    {

        $data = request()->post();

//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);


        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = AuthPermissionRule::find($iId);
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


    public function permissionRuleDeptTree()
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

    public function permissionRuleSave()
    {

        $data = request()->post();

//        $data = $this->request->post();
        if (empty($data['name']) || empty($data['status'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $sName = strtolower(strip_tags($data['name']));
        // 菜单模型
//        $info = AuthPermissionRule::where('name',$sName)
//            ->field('name')
//            ->find();

        $info = AuthPermissionRule::where('name', $sName)
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
        $auth_permission_rule->name = $sName;
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


        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);

        return ResultVo::success($auth_permission_rule);
    }


    public function permissionRuleEdit()
    {
        $data = request()->all();

//        $data = $this->request->post();
        if (empty($data['id']) || empty($data['name'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $iId = $data['id'];
        $sName = strtolower(strip_tags($data['name']));
        // 模型
//        $auth_permission_rule = AuthPermissionRule::where('id',$iId)
//            ->field('id')
//            ->find();

        $auth_permission_rule = AuthPermissionRule::where('id', $iId)
            ->first();

        if (!$auth_permission_rule) {
            return ResultVo::error(ErrorCode::DATA_NOT, "角色不存在");
        }

//        $iIdInfo = AuthPermissionRule::where('name',$sName)
//            ->field('id')
//            ->find();

        $iIdInfo = AuthPermissionRule::where('name', $sName)
            ->first();


        // 判断名称 是否重名，剔除自己
        if (!empty($iIdInfo['id']) && $iIdInfo['id'] != $iId) {
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
        if (in_array($iId, $parents)) {
            return ResultVo::error(ErrorCode::NOT_NETWORK, "不能把自身/子级作为父级");
        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_permission_rule->pid = $pid;
        $auth_permission_rule->name = $sName;
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


        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);

        return ResultVo::success();
    }


    public function permissionRuleDelete()
    {
//        $iId = request()->post('id/d');
//        if (empty($iId)){
        $iId = request()->all()['id'];

        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }

        // 下面有子节点，不能删除
//        $sub = AuthPermissionRule::where('pid',$iId)->field('id')->find();
        $sub = AuthPermissionRule::where('pid', $iId)->get();
        if (count($sub) > 0) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        if (!AuthPermissionRule::where('id', $iId)->delete()) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        // 删除授权的权限
        AuthPermission::where('permission_rule_id', $iId)->delete();

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

    public function permissionRuleDeptSave()
    {

        $data = request()->post();

//        $data = $this->request->post();
        /*if (empty($data['name']) || empty($data['status'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }*/
        $sName = strtolower(strip_tags($data['name']));
        // 菜单模型
//        $info = AuthPermissionRule::where('name',$sName)
//            ->field('name')
//            ->find();

        /*$info = Department::where('name', $sName)
            ->first();

        if ($info) {
            return ResultVo::error(ErrorCode::DATA_REPEAT, "权限已经存在");
        }*/

        $now_time = date("Y-m-d H:i:s");
        $status = !empty($data['status']) ? $data['status'] : 0;
        $pid = !empty($data['pid']) ? $data['pid'] : 0;
        if ($pid) {
//            $info = AuthPermissionRule::where('id',$pid)
//                ->field('id')
//                ->find();

            $info = Department::where('id', $pid)
                ->first();

            if (!$info) {
                return ResultVo::error(ErrorCode::NOT_NETWORK);
            }
        }
        $auth_permission_rule = new Department();
        $auth_permission_rule->pid = $pid;
        $auth_permission_rule->name = !empty($data['title']) ? $data['title'] : ' ';
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


        $sOperateName = 'floatwindowconfigList';
        $sLogContent = '查询';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);

        return ResultVo::success($auth_permission_rule);
    }



    public function permissionRuleDeptDelete()
    {
//        $iId = request()->post('id/d');
//        if (empty($iId)){
        $iId = request()->all()['id'];

        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }

        // 下面有子节点，不能删除
//        $sub = AuthPermissionRule::where('pid',$iId)->field('id')->find();
        $sub = Department::where('pid', $iId)->get();
        if (count($sub) > 0) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        if (!Department::where('id', $iId)->delete()) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        // 删除授权的权限   tmp
//        AuthPermission::where('permission_rule_id', $iId)->delete();

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


    public function ruleDeptStatusSave($iId = null)
    {

        $data = request()->post();

//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);


        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = Department::find($iId);
//        $iFlag = 0;
        if (is_object($oEvent)) {
            $iStatue = $oEvent->status;
        }
//        $iFlag = $iStatue == 0 ? 1 : 0;permissionRuleDeptIndex
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