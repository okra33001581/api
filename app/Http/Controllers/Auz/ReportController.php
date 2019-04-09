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
 * Class Event - 报表相关控制器
 * @author zebra
 */
class ReportController extends Controller
{


    public function financeIndex()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';

        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';

        $endDate = isset(request()->endDate) ? request()->endDate : '';


        $oAuthAdminList = DB::table('report_finance');



        if ($merchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($beginDate !== '') {
            $oAuthAdminList->where('date', '>=', $beginDate);
        }

        if ($endDate !== '') {
            $oAuthAdminList->where('date', '<=', $endDate);
        }

//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();

        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['company_in'] = $oAuthAdmin->company_in;
            $aTmp['third_in'] = $oAuthAdmin->third_in;
            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['common_deposit'] = $oAuthAdmin->common_deposit;
            $aTmp['benefit'] = $oAuthAdmin->benefit;
            $aTmp['total_rebate'] = $oAuthAdmin->total_rebate;
            $aTmp['day_salary'] = $oAuthAdmin->day_salary;
            $aTmp['bankcard_out'] = $oAuthAdmin->bankcard_out;
            $aTmp['third_out'] = $oAuthAdmin->third_out;
            $aTmp['user_subtraction'] = $oAuthAdmin->user_subtraction;
            $aTmp['artifical_withdraw'] = $oAuthAdmin->artifical_withdraw;
            $aTmp['total'] = $oAuthAdmin->total;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;

            $aFinal[] = $aTmp;
        }

        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function operationProfit()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';


        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';

        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';

        $endDate = isset(request()->endDate) ? request()->endDate : '';

        $model = isset(request()->model) ? request()->model : '';

        $platform = isset(request()->platform) ? request()->platform : '';

        $sUserName = isset(request()->username) ? request()->username : '';

        $oAuthAdminList = DB::table('report_operation_profit');



        if ($merchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($beginDate !== '') {
            $oAuthAdminList->where('date', '>=', $beginDate);
        }

        if ($endDate !== '') {
            $oAuthAdminList->where('date', '<=', $endDate);
        }

        if ($model !== '') {
            $oAuthAdminList->where('model', 'like', '%' . $model . '%');
        }

        if ($platform !== '') {
            $oAuthAdminList->where('platform', 'like', '%' . $platform . '%');
        }


        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }

        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['group'] = $oAuthAdmin->group;
            $aTmp['in_total_amount'] = $oAuthAdmin->in_total_amount;
            $aTmp['total_out_amount'] = $oAuthAdmin->total_out_amount;
            $aTmp['valid_profit'] = $oAuthAdmin->valid_profit;
            $aTmp['sum_turnover'] = $oAuthAdmin->sum_turnover;
            $aTmp['prize_amount'] = $oAuthAdmin->prize_amount;
            $aTmp['rebate_amount'] = $oAuthAdmin->rebate_amount;
            $aTmp['game_profit_loss'] = $oAuthAdmin->game_profit_loss;
            $aTmp['benefit_amount'] = $oAuthAdmin->benefit_amount;
            $aTmp['day_salary'] = $oAuthAdmin->day_salary;
            $aTmp['system_subtraction'] = $oAuthAdmin->system_subtraction;
            $aTmp['final_amount'] = $oAuthAdmin->final_amount;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['platform'] = $oAuthAdmin->platform;
            $aTmp['model'] = $oAuthAdmin->model;


            $aFinal[] = $aTmp;
        }

        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function pgamePlaylist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $way_type = isset(request()->way_type) ? request()->way_type : '';
        $lottery = isset(request()->lottery) ? request()->lottery : '';
        $way = isset(request()->way) ? request()->way : '';
        $prize_status = isset(request()->prize_status) ? request()->prize_status : '';
        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $endDate = isset(request()->endDate) ? request()->endDate : '';
        $sort = isset(request()->sort) ? request()->sort : '';
        $prize_type = isset(request()->prize_type) ? request()->prize_type : '';
        $min = isset(request()->min) ? request()->min : '';
        $max = isset(request()->max) ? request()->max : '';
        $select_info_type = isset(request()->select_info_type) ? request()->select_info_type : '';
        $issue = isset(request()->issue) ? request()->issue : '';


        $oAuthAdminList = DB::table('report_pgame_playlist');


        if ($way_type !== '') {
            $oAuthAdminList->where('way_type', $way_type);
        }


        if ($lottery !== '') {
            $oAuthAdminList->where('lottery', $lottery);
        }


        if ($way !== '') {
            $oAuthAdminList->where('way', $way);
        }



        if ($prize_status !== '') {
            $oAuthAdminList->where('prize_status', $prize_status);
        }

        if ($beginDate !== '') {
            $oAuthAdminList->where('date', '>=', $beginDate);
        }

        if ($endDate !== '') {
            $oAuthAdminList->where('date', '>=', $endDate);
        }

        if ($sort == '逆序') {
            $oAuthAdminList->orderBy('id', 'DESC');
        }

        if ($sort == '顺序') {
            $oAuthAdminList->orderBy('id', 'asc');
        }

        if ($prize_type == '奖金') {
            if ($min !== '') {
                $oAuthAdminList->where('prize_amount', '>=', $min);
            }

            if ($max !== '') {
                $oAuthAdminList->where('prize_amount', '>=', $max);
            }
        }

        if ($prize_type == '倍数') {
            if ($min !== '') {
                $oAuthAdminList->where('multiple', '>=', $min);
            }

            if ($max !== '') {
                $oAuthAdminList->where('multiple', '>=', $max);
            }
        }


        if ($select_info_type == '用户名') {
            if ($issue !== '') {
                $oAuthAdminList->where('username', 'like', '%' . $issue . '%');
            }

        }

        if ($select_info_type == '注单') {
            if ($issue !== '') {
                $oAuthAdminList->where('project', 'like', '%' . $issue . '%');
            }

        }

//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();

        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['project'] = $oAuthAdmin->project;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['uername'] = $oAuthAdmin->uername;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['lottery'] = $oAuthAdmin->lottery;
            $aTmp['issue_count'] = $oAuthAdmin->issue_count;
            $aTmp['prize_number'] = $oAuthAdmin->prize_number;
            $aTmp['way'] = $oAuthAdmin->way;
            $aTmp['dynamic_prize'] = $oAuthAdmin->dynamic_prize;
            $aTmp['project_content'] = $oAuthAdmin->project_content;
            $aTmp['multiple'] = $oAuthAdmin->multiple;
            $aTmp['total_amount'] = $oAuthAdmin->total_amount;
            $aTmp['mode'] = $oAuthAdmin->mode;
            $aTmp['prize_amount'] = $oAuthAdmin->prize_amount;
            $aTmp['prize_status'] = $oAuthAdmin->prize_status;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }

        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function preportProfit()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';


        $sUserName = isset(request()->username) ? request()->username : '';

        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';

        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';

        $endDate = isset(request()->endDate) ? request()->endDate : '';


        $oAuthAdminList = DB::table('report_platform');



        if ($merchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }


        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }

        if ($beginDate !== '') {
            $oAuthAdminList->where('date', '>=', $beginDate);
        }

        if ($endDate !== '') {
            $oAuthAdminList->where('date', '<=', $endDate);
        }


//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();

        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['group'] = $oAuthAdmin->group;
            $aTmp['total_project'] = $oAuthAdmin->total_project;
            $aTmp['valid_project'] = $oAuthAdmin->valid_project;
            $aTmp['prize_total_amount'] = $oAuthAdmin->prize_total_amount;
            $aTmp['rebate_amount'] = $oAuthAdmin->rebate_amount;
            $aTmp['game_profit_loss'] = $oAuthAdmin->game_profit_loss;
            $aTmp['profit_ratio'] = $oAuthAdmin->profit_ratio;
            $aTmp['project_count'] = $oAuthAdmin->project_count;
            $aTmp['active_count'] = $oAuthAdmin->active_count;
            $aTmp['date'] = $oAuthAdmin->date;

            $aFinal[] = $aTmp;
        }

        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function userReport()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';


        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';

        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';

        $endDate = isset(request()->endDate) ? request()->endDate : '';

        $model = isset(request()->model) ? request()->model : '';

        $platform = isset(request()->platform) ? request()->platform : '';

        $sUserName = isset(request()->username) ? request()->username : '';

        $oAuthAdminList = DB::table('report_user');



        if ($merchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($beginDate !== '') {
            $oAuthAdminList->where('date', '>=', $beginDate);
        }

        if ($endDate !== '') {
            $oAuthAdminList->where('date', '<=', $endDate);
        }

        if ($model !== '') {
            $oAuthAdminList->where('model', 'like', '%' . $model . '%');
        }

        if ($platform !== '') {
            $oAuthAdminList->where('platform', 'like', '%' . $platform . '%');
        }


        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }


//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();

        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['ip_count'] = $oAuthAdmin->ip_count;
            $aTmp['register_count'] = $oAuthAdmin->register_count;
            $aTmp['active_count'] = $oAuthAdmin->active_count;
            $aTmp['first_deposit_count'] = $oAuthAdmin->first_deposit_count;
            $aTmp['first_deposit_amount'] = $oAuthAdmin->first_deposit_amount;
            $aTmp['in_people_count'] = $oAuthAdmin->in_people_count;
            $aTmp['in_times'] = $oAuthAdmin->in_times;
            $aTmp['out_times'] = $oAuthAdmin->out_times;

            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;


            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['platform'] = $oAuthAdmin->platform;
            $aTmp['model'] = $oAuthAdmin->model;

            $aFinal[] = $aTmp;
        }

        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function adminRoleList()
    {
        $sWhere = [];
        $limit = request()->get('limit/d', 20);
        //分页配置
//        $paginate = [
//            'type' => 'bootstrap',
//            'var_page' => 'page',
//            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
//        ];
        $iTmp = ($limit <= 0 || $limit > 20) ? 20 : $limit;
        $lists = AuthRole::where($sWhere)
            ->paginate($iTmp);

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();
        return response()->json($res);
        return ResultVo::success($res);
    }


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

        $oAuthAdmin = AuthAdmin::where('username', $username)
            ->first();

//        if ($oAuthAdmin){
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

    public function adminEdit()
    {
        $data = request()->post();


//        Log::info($data);
        $aRoles = $data['roles'];

        if (empty($data['id']) || empty($data['username'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $id = $data['id'];
        $username = strip_tags($data['username']);
        // 模型
//        $auth_admin = AuthAdmin::where('id',$id)
//            ->field('id,username')
//            ->find();
        $oAuthAdmin = AuthAdmin::where('id', $id)
            ->first();

        if (!$oAuthAdmin) {
            return ResultVo::error(ErrorCode::DATA_NOT, "商户不存在");
        }
        $login_info = $oAuthAdmin;
        $login_user_name = isset($login_info['username']) ? $login_info['username'] : '';
        // 如果是超级商户，判断当前登录用户是否匹配
        if ($oAuthAdmin->username == 'admin' && $login_user_name != $oAuthAdmin->username) {
            return ResultVo::error(ErrorCode::DATA_NOT, "最高权限用户，无权修改");
        }

//        $info = AuthAdmin::where('username',$username)
//            ->field('id')
//            ->find();

        $info = AuthAdmin::where('username', $username)
            ->first();

        // 判断username 是否重名，剔除自己
//        if (!empty($info['id']) && $info['id'] != $id){
//            return ResultVo::error(ErrorCode::DATA_REPEAT, "商户已存在");
//        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $password = isset($data['password']) ? PassWordUtils::create($data['password']) : '';
        $oAuthAdmin->username = $username;
        if ($password) {
            $oAuthAdmin->password = $password;
        }
        $oAuthAdmin->status = $status;
//        $oAuthAdmin->role_id = implode(",", $aRoles);

        $result = $oAuthAdmin->save();

        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];
        if (!$result) {
            // 没有做任何更改
            $oAuthRoleAdmin = AuthRoleAdmin::where('admin_id', $id)->field('role_id')->select();
            if ($oAuthRoleAdmin) {
                $oAuthRoleAdmin = $oAuthRoleAdmin->toArray();
                $oAuthRoleAdmin = array_column($oAuthRoleAdmin, 'role_id');
            }
            // 没有差值，权限也没做更改
            if ($roles == $oAuthRoleAdmin) {
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

    public function adminDelete()
    {
//        $id = request()->post('id/d');
        $id = request()->all()['id'];
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$id)->field('username')->find();
        $oAuthAdmin = AuthAdmin::where('id', $id)->first();
        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
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