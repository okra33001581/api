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

/**
 * Class Event - 权限列表相关控制器
 * @author zebra
 */
class PermissionRuleController extends Controller
{
// PermissionRuleController.php

    public function permissionRuleIndex()
    {

        $where = [];
        $order = 'id ASC';
        $status = request()->get('status', '');
        if ($status != '') {
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

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success($res);

    }

    public function ruleStatusSave($id = null)
    {

        $data = request()->post();

//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);


        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/

        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = AuthPermissionRule::find($id);
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

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

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

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

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

        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);

        return ResultVo::success();
    }


    public function permissionRuleDelete()
    {
//        $id = request()->post('id/d');
//        if (empty($id)){
        $id = request()->all()['id'];

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


        $sub_account = '123';
        $operate_name = 'floatwindowconfigList';
        $log_content = '查询';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);

        return response()->json($aFinal);
        return ResultVo::success();
    }
}