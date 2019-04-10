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

use App\model\PaySetting;
use App\model\DepositAccount;

use App\model\ThirdAccount;

use App\model\PayGroup;
use App\model\CashWithdraw;
use App\model\UserBetsCheck;
use App\model\TransferOrder;
use App\model\ManualPay;
use App\model\ManualPayConfirm;

use App\model\CompanyMoney;
use App\model\FastPayMoney;
use App\model\RakeBack;

use App\model\PayAccount;
use App\model\AdminLog;

/**
 * Class Event - 资金相关控制器
 * @author zebra
 */
class FundController extends Controller
{

    public function cashOrderlist()
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
        $select_search_type = isset(request()->select_search_type) ? request()->select_search_type : '';
        $keywords = isset(request()->keywords) ? request()->keywords : '';
        $is_has_child = isset(request()->is_has_child) ? request()->is_has_child : '';
        $transaction_type = isset(request()->transaction_type) ? request()->transaction_type : '';
        $sort_type = isset(request()->sort_type) ? request()->sort_type : '';
        $min = isset(request()->min) ? request()->min : '';
        $max = isset(request()->max) ? request()->max : '';

        $oAuthAdminList = DB::table('fund_transaction');


        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($beginDate != '') {
            $oAuthAdminList->where('date', '>=', $beginDate);
        }

        if ($endDate != '') {
            $oAuthAdminList->where('date', '>=', $endDate);
        }


        if ($select_search_type == '会员账号') {
            if ($keywords != '') {
                $oAuthAdminList->where('account', 'like', '%' . $keywords . '%');
            }
        } elseif ($select_search_type == '订单号') {
            if ($keywords != '') {
                $oAuthAdminList->where('order_number', 'like', '%' . $keywords . '%');
            }

        } elseif ($select_search_type == 'IP地址') {
            if ($keywords != '') {
                $oAuthAdminList->where('ip_address', 'like', '%' . $keywords . '%');
            }

        }

        if ($is_has_child != '') {
            $oAuthAdminList->where('has_child', '=', $is_has_child);
        }


        if ($sort_type == 'DESC') {
            $oAuthAdminList->orderBy('id', 'desc');
        } elseif ($sort_type == 'ASC') {
            $oAuthAdminList->orderBy('id', 'asc');

        }

        if ($beginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $beginDate);
        }

        if ($endDate != '') {
            $oAuthAdminList->where('created_at', '>=', $endDate);
        }

        if ($min != '') {
            $oAuthAdminList->where('avaiable_amount', '>=', $min);
        }

        if ($max != '') {
            $oAuthAdminList->where('avaiable_amount', '<=', $max);
        }


        $oAuthAdminListCount = $oAuthAdminList->get();
        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['order_number'] = $oAuthAdmin->order_number;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['type'] = $oAuthAdmin->type;
            $aTmp['platform'] = $oAuthAdmin->platform;
            $aTmp['income'] = $oAuthAdmin->income;
            $aTmp['outcome'] = $oAuthAdmin->outcome;
            $aTmp['avaiable_amount'] = $oAuthAdmin->avaiable_amount;
            $aTmp['ip_address'] = $oAuthAdmin->ip_address;
            $aTmp['message'] = $oAuthAdmin->message;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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


    public function cashPaysetting()
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
        $oAuthAdminList = DB::table('fund_paysetting');


        $iStatus = isset(request()->status) ? request()->status : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

        if ($iStatus != '') {
            $oAuthAdminList->where('status', $iStatus);
        }

//        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();

        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['name'] = $oAuthAdmin->name;
            $aTmp['no_project_flag'] = $oAuthAdmin->no_project_flag;
            $aTmp['no_charge_times'] = $oAuthAdmin->no_charge_times;
            $aTmp['fee'] = $oAuthAdmin->fee;
            $aTmp['fee_type'] = $oAuthAdmin->fee_type;
            $aTmp['withdraw_times'] = $oAuthAdmin->withdraw_times;
            $aTmp['withdraw_max'] = $oAuthAdmin->withdraw_max;
            $aTmp['withdraw_min'] = $oAuthAdmin->withdraw_min;
            $aTmp['web_deposit_benefit'] = $oAuthAdmin->web_deposit_benefit;
            $aTmp['web_benefit_standard'] = $oAuthAdmin->web_benefit_standard;
            $aTmp['web_benefit_ratio'] = $oAuthAdmin->web_benefit_ratio;
            $aTmp['web_benefit_max'] = $oAuthAdmin->web_benefit_max;
            $aTmp['web_max'] = $oAuthAdmin->web_max;
            $aTmp['web_min'] = $oAuthAdmin->web_min;
            $aTmp['web_general_turnover_audit'] = $oAuthAdmin->web_general_turnover_audit;
            $aTmp['web_general_turnover_audit_flag'] = $oAuthAdmin->web_general_turnover_audit_flag;
            $aTmp['web_turnover_audit'] = $oAuthAdmin->web_turnover_audit;
            $aTmp['web_turnover_audit_flag'] = $oAuthAdmin->web_turnover_audit_flag;
            $aTmp['web_turnover_quota'] = $oAuthAdmin->web_turnover_quota;
            $aTmp['web_turnover_managefee_ratio'] = $oAuthAdmin->web_turnover_managefee_ratio;
            $aTmp['company_deposit_benefit'] = $oAuthAdmin->company_deposit_benefit;
            $aTmp['company_benefit_standard'] = $oAuthAdmin->company_benefit_standard;
            $aTmp['company_benefit_ratio'] = $oAuthAdmin->company_benefit_ratio;
            $aTmp['company_benefit_max'] = $oAuthAdmin->company_benefit_max;
            $aTmp['company_max'] = $oAuthAdmin->company_max;
            $aTmp['company_min'] = $oAuthAdmin->company_min;
            $aTmp['company_general_turnover_audit'] = $oAuthAdmin->company_general_turnover_audit;
            $aTmp['company_general_turnover_audit_flag'] = $oAuthAdmin->company_general_turnover_audit_flag;
            $aTmp['company_turnover_audit'] = $oAuthAdmin->company_turnover_audit;
            $aTmp['company_turnover_audit_flag'] = $oAuthAdmin->company_turnover_audit_flag;
            $aTmp['company_turnover_quota'] = $oAuthAdmin->company_turnover_quota;
            $aTmp['company_turnover_managefee_ratio'] = $oAuthAdmin->company_turnover_managefee_ratio;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }

        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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

    public function cashRakeback()
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
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $sEndDate = isset(request()->endDate) ? request()->endDate : '';


        $oAuthAdminList = DB::table('fund_rebate');

//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }


        if ($iStatus != '') {
            $oAuthAdminList->where('status', $iStatus);
        }
        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        if ($sUserName != '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');


        }

        if ($sBeginDate != '') {
            $oAuthAdminList->where('project_date', '>=', $sBeginDate);
        }


        if ($sEndDate != '') {
            $oAuthAdminList->where('project_date', '<=', $sEndDate);
        }


        $oAuthAdminListCount = $oAuthAdminList->get();
        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['project_date'] = $oAuthAdmin->project_date;
            $aTmp['project_amount'] = $oAuthAdmin->project_amount;
            $aTmp['rebate_ratio'] = $oAuthAdmin->rebate_ratio;
            $aTmp['rebate_amount'] = $oAuthAdmin->rebate_amount;
            $aTmp['send_date'] = $oAuthAdmin->send_date;
            $aTmp['sender'] = $oAuthAdmin->sender;
            $aTmp['audit_memo'] = $oAuthAdmin->audit_memo;
            $aTmp['memo'] = $oAuthAdmin->memo;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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

    public function cashWithdrawlist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $request_beginDate = isset(request()->request_beginDate) ? request()->request_beginDate : '';
        $request_endDate = isset(request()->request_endDate) ? request()->request_endDate : '';
        $confirm_beginDate = isset(request()->confirm_beginDate) ? request()->confirm_beginDate : '';
        $confirm_endDate = isset(request()->confirm_endDate) ? request()->confirm_endDate : '';
        $min = isset(request()->min) ? request()->min : '';
        $max = isset(request()->max) ? request()->max : '';
        $refresh_frequency = isset(request()->refresh_frequency) ? request()->refresh_frequency : '';
        $out_type = isset(request()->out_type) ? request()->out_type : '';
        $out_status = isset(request()->out_status) ? request()->out_status : '';
        $order_no = isset(request()->order_no) ? request()->order_no : '';
        $account = isset(request()->account) ? request()->account : '';

        $oAuthAdminList = DB::table('fund_cashwithdraw');


        if ($merchant_name != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }


        if ($request_beginDate !== '') {
            $oAuthAdminList->where('request_date', '>=', $request_beginDate);
        }

        if ($request_endDate !== '') {
            $oAuthAdminList->where('request_date', '>=', $request_endDate);
        }


        if ($confirm_beginDate !== '') {
            $oAuthAdminList->where('confirm_date', '>=', $confirm_beginDate);
        }

        if ($confirm_endDate !== '') {
            $oAuthAdminList->where('confirm_date', '>=', $confirm_endDate);
        }

        if ($min !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $min);
        }

        if ($max !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $max);
        }


        if ($refresh_frequency !== '') {
            $oAuthAdminList->where('refresh_frequency', '=', $refresh_frequency);
        }


        if ($out_type !== '') {
            $oAuthAdminList->where('out_type', '=', $out_type);
        }


        if ($out_status !== '') {
            $oAuthAdminList->where('status', '=', $out_status);
        }


        if ($out_status !== '') {
            $oAuthAdminList->where('status', '=', $out_status);
        }


        if ($order_no !== '') {
            $oAuthAdminList->where('order_no', '=', $order_no);
        }


        if ($account !== '') {
            $oAuthAdminList->where('account', '=', $account);
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
            $aTmp['layer'] = $oAuthAdmin->layer;
            $aTmp['order_no'] = $oAuthAdmin->order_no;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['out_type'] = $oAuthAdmin->out_type;
            $aTmp['fee'] = $oAuthAdmin->fee;
            $aTmp['final_out_amount'] = $oAuthAdmin->final_out_amount;
            $aTmp['out_status'] = $oAuthAdmin->out_status;
            $aTmp['request_date'] = $oAuthAdmin->request_date;
            $aTmp['confirm_date'] = $oAuthAdmin->confirm_date;
            $aTmp['risk'] = $oAuthAdmin->risk;
            $aTmp['risk_operator'] = $oAuthAdmin->risk_operator;
            $aTmp['out_operate'] = $oAuthAdmin->out_operate;
            $aTmp['operator'] = $oAuthAdmin->operator;
            $aTmp['front_memo'] = $oAuthAdmin->front_memo;
            $aTmp['back_memo'] = $oAuthAdmin->back_memo;

            $aFinal[] = $aTmp;
        }

        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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


    public function paysettingSave()
    {
        $data = request()->post();


        $name = isset($data['name']) ? $data['name'] : '';
        $no_project_flag = isset($data['no_project_flag']) ? $data['no_project_flag'] : '';
        $no_charge_times = isset($data['no_charge_times']) ? $data['no_charge_times'] : '';
        $fee = isset($data['fee']) ? $data['fee'] : '';
        $fee_type = isset($data['fee_type']) ? $data['fee_type'] : '';

        $withdraw_times = isset($data['withdraw_times']) ? $data['withdraw_times'] : '';
        $withdraw_max = isset($data['withdraw_max']) ? $data['withdraw_max'] : '';

        $withdraw_min = isset($data['withdraw_min']) ? $data['withdraw_min'] : '';
        $web_deposit_benefit = isset($data['web_deposit_benefit']) ? $data['web_deposit_benefit'] : '';
        $web_benefit_standard = isset($data['web_benefit_standard']) ? $data['web_benefit_standard'] : '';
        $web_benefit_ratio = isset($data['web_benefit_ratio']) ? $data['web_benefit_ratio'] : '';
        $web_benefit_max = isset($data['web_benefit_max']) ? $data['web_benefit_max'] : '';
        $web_max = isset($data['web_max']) ? $data['web_max'] : '';
        $web_min = isset($data['web_min']) ? $data['web_min'] : '';
        $web_general_turnover_audit = isset($data['web_general_turnover_audit']) ? $data['web_general_turnover_audit'] : '';
        $web_general_turnover_audit_flag = isset($data['web_general_turnover_audit_flag']) ? $data['web_general_turnover_audit_flag'] : '';
        $web_turnover_audit = isset($data['web_turnover_audit']) ? $data['web_turnover_audit'] : '';
        $web_turnover_audit_flag = isset($data['web_turnover_audit_flag']) ? $data['web_turnover_audit_flag'] : '';
        $web_turnover_quota = isset($data['web_turnover_quota']) ? $data['web_turnover_quota'] : '';
        $web_turnover_managefee_ratio = isset($data['web_turnover_managefee_ratio']) ? $data['web_turnover_managefee_ratio'] : '';
        $company_deposit_benefit = isset($data['company_deposit_benefit']) ? $data['company_deposit_benefit'] : '';
        $company_benefit_standard = isset($data['company_benefit_standard']) ? $data['company_benefit_standard'] : '';
        $company_benefit_ratio = isset($data['company_benefit_ratio']) ? $data['company_benefit_ratio'] : '';
        $company_benefit_max = isset($data['company_benefit_max']) ? $data['company_benefit_max'] : '';
        $company_max = isset($data['company_max']) ? $data['company_max'] : '';
        $company_min = isset($data['company_min']) ? $data['company_min'] : '';
        $company_general_turnover_audit = isset($data['company_general_turnover_audit']) ? $data['company_general_turnover_audit'] : '';
        $company_general_turnover_audit_flag = isset($data['company_general_turnover_audit_flag']) ? $data['company_general_turnover_audit_flag'] : '';
        $company_turnover_audit = isset($data['company_turnover_audit']) ? $data['company_turnover_audit'] : '';
        $company_turnover_audit_flag = isset($data['company_turnover_audit_flag']) ? $data['company_turnover_audit_flag'] : '';
        $company_turnover_quota = isset($data['company_turnover_quota']) ? $data['company_turnover_quota'] : '';
        $company_turnover_managefee_ratio = isset($data['company_turnover_managefee_ratio']) ? $data['company_turnover_managefee_ratio'] : '';


        $oQrCode = new PaySetting();


//        $oQrCode->id = $id;


        $oQrCode->name = $name;
        $oQrCode->no_project_flag = $no_project_flag;
        $oQrCode->no_charge_times = $no_charge_times;
        $oQrCode->fee = $fee;
        $oQrCode->fee_type = $fee_type;

        $oQrCode->withdraw_times = $withdraw_times;
        $oQrCode->withdraw_max = $withdraw_max;

        $oQrCode->withdraw_min = $withdraw_min;
        $oQrCode->web_deposit_benefit = $web_deposit_benefit;
        $oQrCode->web_benefit_standard = $web_benefit_standard;
        $oQrCode->web_benefit_ratio = $web_benefit_ratio;
        $oQrCode->web_benefit_max = $web_benefit_max;
        $oQrCode->web_max = $web_max;
        $oQrCode->web_min = $web_min;
        $oQrCode->web_general_turnover_audit = $web_general_turnover_audit;
        $oQrCode->web_general_turnover_audit_flag = $web_general_turnover_audit_flag;
        $oQrCode->web_turnover_audit = $web_turnover_audit;
        $oQrCode->web_turnover_audit_flag = $web_turnover_audit_flag;
        $oQrCode->web_turnover_quota = $web_turnover_quota;
        $oQrCode->web_turnover_managefee_ratio = $web_turnover_managefee_ratio;
        $oQrCode->company_deposit_benefit = $company_deposit_benefit;
        $oQrCode->company_benefit_standard = $company_benefit_standard;
        $oQrCode->company_benefit_ratio = $company_benefit_ratio;
        $oQrCode->company_benefit_max = $company_benefit_max;
        $oQrCode->company_max = $company_max;
        $oQrCode->company_min = $company_min;
        $oQrCode->company_general_turnover_audit = $company_general_turnover_audit;
        $oQrCode->company_general_turnover_audit_flag = $company_general_turnover_audit_flag;
        $oQrCode->company_turnover_audit = $company_turnover_audit;
        $oQrCode->company_turnover_audit_flag = $company_turnover_audit_flag;
        $oQrCode->company_turnover_quota = $company_turnover_quota;
        $oQrCode->company_turnover_managefee_ratio = $company_turnover_managefee_ratio;


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

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


    public function depositAccountSave()
    {
        $data = request()->post();


        $user_levels = isset($data['user_levels']) ? $data['user_levels'] : '';
        $pay_type = isset($data['pay_type']) ? $data['pay_type'] : '';
        $bank = isset($data['bank']) ? $data['bank'] : '';
        $account = isset($data['account']) ? $data['account'] : '';
        $min = isset($data['min']) ? $data['min'] : '';
        $max = isset($data['max']) ? $data['max'] : '';
        $account_alias = isset($data['account_alias']) ? $data['account_alias'] : '';
        $display_flag = isset($data['display_flag']) ? $data['display_flag'] : '';
        $qr_code = isset($data['qr_code']) ? $data['qr_code'] : '';
        $postscript_flag = isset($data['postscript_flag']) ? $data['postscript_flag'] : '';
        $receiver = isset($data['receiver']) ? $data['receiver'] : '';
        $alert = isset($data['alert']) ? $data['alert'] : '';
        $order_flag = isset($data['order_flag']) ? $data['order_flag'] : '';

        $oQrCode = new DepositAccount();


//        $oQrCode->id = $id;


        $oQrCode->user_levels = $user_levels;
        $oQrCode->pay_type = $pay_type;
        $oQrCode->bank = $bank;
        $oQrCode->account = $account;
        $oQrCode->min = $min;
        $oQrCode->max = $max;
        $oQrCode->account_alias = $account_alias;
        $oQrCode->display_flag = $display_flag;
        $oQrCode->qr_code = $qr_code;
        $oQrCode->postscript_flag = $postscript_flag;
        $oQrCode->receiver = $receiver;
        $oQrCode->alert = $alert;
        $oQrCode->order_flag = $order_flag;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;
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


    public function thirdAccountSave()
    {
        $data = request()->post();


        $layers = isset($data['layers']) ? $data['layers'] : '';
        $third_company = isset($data['third_company']) ? $data['third_company'] : '';
        $pay_type = isset($data['pay_type']) ? $data['pay_type'] : '';
        $mobile_display_flag = isset($data['mobile_display_flag']) ? $data['mobile_display_flag'] : '';
        $decimal_flag = isset($data['decimal_flag']) ? $data['decimal_flag'] : '';
        $deposit_type = isset($data['deposit_type']) ? $data['deposit_type'] : '';
        $min = isset($data['min']) ? $data['min'] : '';
        $max = isset($data['max']) ? $data['max'] : '';
        $quota = isset($data['quota']) ? $data['quota'] : '';
        $query_flag = isset($data['query_flag']) ? $data['query_flag'] : '';
        $merchant_code = isset($data['merchant_code']) ? $data['merchant_code'] : '';
        $merchant_id = isset($data['merchant_id']) ? $data['merchant_id'] : '';
        $private_key = isset($data['private_key']) ? $data['private_key'] : '';
        $public_key = isset($data['public_key']) ? $data['public_key'] : '';
        $pay_domain = isset($data['pay_domain']) ? $data['pay_domain'] : '';
        $gateway = isset($data['gateway']) ? $data['gateway'] : '';
        $query_url = isset($data['query_url']) ? $data['query_url'] : '';

        $oQrCode = new ThirdAccount();


//        $oQrCode->id = $id;


        $oQrCode->layers = $layers;
        $oQrCode->third_company = $third_company;
        $oQrCode->pay_type = $pay_type;
        $oQrCode->mobile_display_flag = $mobile_display_flag;
        $oQrCode->decimal_flag = $decimal_flag;
        $oQrCode->deposit_type = $deposit_type;
        $oQrCode->min = $min;
        $oQrCode->max = $max;
        $oQrCode->quota = $quota;
        $oQrCode->query_flag = $query_flag;
        $oQrCode->merchant_code = $merchant_code;
        $oQrCode->merchant_id = $merchant_id;
        $oQrCode->private_key = $private_key;
        $oQrCode->public_key = $public_key;
        $oQrCode->pay_domain = $pay_domain;
        $oQrCode->gateway = $gateway;
        $oQrCode->query_url = $query_url;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

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

    public function companymoneyList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $request_beginDate = isset(request()->request_beginDate) ? request()->request_beginDate : '';
        $request_endDate = isset(request()->request_endDate) ? request()->request_endDate : '';
        $confirm_beginDate = isset(request()->confirm_beginDate) ? request()->confirm_beginDate : '';
        $confirm_endDate = isset(request()->confirm_endDate) ? request()->confirm_endDate : '';
        $min = isset(request()->min) ? request()->min : '';
        $max = isset(request()->max) ? request()->max : '';
        $refresh_frequency = isset(request()->refresh_frequency) ? request()->refresh_frequency : '';
        $status = isset(request()->status) ? request()->status : '';
        $in_account = isset(request()->in_account) ? request()->in_account : '';
        $select_search_type = isset(request()->select_search_type) ? request()->select_search_type : '';
        $keywords = isset(request()->keywords) ? request()->keywords : '';


        $oAuthAdminList = DB::table('fund_companymoney');


        if ($merchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }


        if ($request_beginDate !== '') {
            $oAuthAdminList->where('request_date', '>=', $request_beginDate);
        }

        if ($request_endDate !== '') {
            $oAuthAdminList->where('request_date', '>=', $request_endDate);
        }


        if ($confirm_beginDate !== '') {
            $oAuthAdminList->where('confirm_date', '>=', $confirm_beginDate);
        }

        if ($confirm_endDate !== '') {
            $oAuthAdminList->where('confirm_date', '>=', $confirm_endDate);
        }

        if ($min !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $min);
        }

        if ($max !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $max);
        }


        if ($refresh_frequency !== '') {
            $oAuthAdminList->where('refresh_frequency', '=', $refresh_frequency);
        }


//        if ($out_type !== '') {
//            $oAuthAdminList->where('out_type', '=', $out_type);
//        }
//
//
//        if ($out_status !== '') {
//            $oAuthAdminList->where('status', '=', $out_status);
//        }


        if ($status !== '') {
            $oAuthAdminList->where('status', '=', $status);
        }

//
//        if ($order_no !== '') {
//            $oAuthAdminList->where('order_no', '=', $order_no);
//        }

        switch ($select_search_type) {
            case '会员账号':
                $oAuthAdminList->where('account', '=', $keywords);
                break;
            case '存款人':
                $oAuthAdminList->where('depositor_name', '=', $keywords);
                break;
            case '附言码':
                $oAuthAdminList->where('postscript', '=', $keywords);
                break;
            case '订单号':
                $oAuthAdminList->where('order_number', '=', $keywords);
                break;

        }

        $oAuthAdminListCount = $oAuthAdminList->get();
        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['order_number'] = $oAuthAdmin->order_number;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['depositor_name'] = $oAuthAdmin->depositor_name;
            $aTmp['request_amount'] = $oAuthAdmin->request_amount;
            $aTmp['in_benefit'] = $oAuthAdmin->in_benefit;
            $aTmp['postscript'] = $oAuthAdmin->postscript;
            $aTmp['deposit_order_no'] = $oAuthAdmin->deposit_order_no;
            $aTmp['in_bank_account'] = $oAuthAdmin->in_bank_account;
            $aTmp['request_date'] = $oAuthAdmin->request_date;
            $aTmp['confirm_date'] = $oAuthAdmin->confirm_date;
            $aTmp['operator'] = $oAuthAdmin->operator;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['memo'] = $oAuthAdmin->memo;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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

    public function fastpaymoneyList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $request_beginDate = isset(request()->request_beginDate) ? request()->request_beginDate : '';
        $request_endDate = isset(request()->request_endDate) ? request()->request_endDate : '';
        $confirm_beginDate = isset(request()->confirm_beginDate) ? request()->confirm_beginDate : '';
        $confirm_endDate = isset(request()->confirm_endDate) ? request()->confirm_endDate : '';
        $min = isset(request()->min) ? request()->min : '';
        $max = isset(request()->max) ? request()->max : '';
        $refresh_frequency = isset(request()->refresh_frequency) ? request()->refresh_frequency : '';
        $status = isset(request()->status) ? request()->status : '';
        $pay_type = isset(request()->pay_type) ? request()->pay_type : '';
        $in_account = isset(request()->in_account) ? request()->in_account : '';
        $select_search_type = isset(request()->select_search_type) ? request()->select_search_type : '';
        $keywords = isset(request()->keywords) ? request()->keywords : '';


        $oAuthAdminList = DB::table('fund_fastpaymoney');


        if ($merchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }


        if ($request_beginDate !== '') {
            $oAuthAdminList->where('request_date', '>=', $request_beginDate);
        }

        if ($request_endDate !== '') {
            $oAuthAdminList->where('request_date', '>=', $request_endDate);
        }


        if ($confirm_beginDate !== '') {
            $oAuthAdminList->where('confirm_date', '>=', $confirm_beginDate);
        }

        if ($confirm_endDate !== '') {
            $oAuthAdminList->where('confirm_date', '>=', $confirm_endDate);
        }

        if ($min !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $min);
        }

        if ($max !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $max);
        }


        if ($refresh_frequency !== '') {
            $oAuthAdminList->where('refresh_frequency', '=', $refresh_frequency);
        }

//
//        if ($out_type !== '') {
//            $oAuthAdminList->where('out_type', '=', $out_type);
//        }


        if ($status !== '') {
            $oAuthAdminList->where('status', '=', $status);
        }


        if ($pay_type !== '') {
            $oAuthAdminList->where('pay_type', '=', $pay_type);
        }

        if ($in_account !== '') {
            $oAuthAdminList->where('receive_account', '=', $in_account);
        }


        switch ($select_search_type) {
            case '会员账号':
                $oAuthAdminList->where('account', '=', $keywords);
                break;
            case '提交人':
                $oAuthAdminList->where('submitor', '=', $keywords);
                break;
            case '操作人':
                $oAuthAdminList->where('auditor', '=', $keywords);
                break;
        }

        $oAuthAdminListCount = $oAuthAdminList->get();
        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['layer'] = $oAuthAdmin->layer;
            $aTmp['order_number'] = $oAuthAdmin->order_number;
            $aTmp['receive_account'] = $oAuthAdmin->receive_account;
            $aTmp['pay_type'] = $oAuthAdmin->pay_type;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['desposit_amount'] = $oAuthAdmin->desposit_amount;
            $aTmp['real_in_amount'] = $oAuthAdmin->real_in_amount;
            $aTmp['request_date'] = $oAuthAdmin->request_date;
            $aTmp['confirm_date'] = $oAuthAdmin->confirm_date;
            $aTmp['operator'] = $oAuthAdmin->operator;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['memo'] = $oAuthAdmin->memo;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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

    public function layerchartIndex()
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
        $oAuthAdminList = DB::table('auth_admins');

        $sTmp = 'DESC';
        if (substr($iSort, 0, 1) == '-') {
            $sTmp = 'ASC';
        }
        $sOrder = substr($iSort, 1, strlen($iSort));
        if ($sTmp != '') {
            $oAuthAdminList->orderby($sOrder, $sTmp);
        }
        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }
        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }
        $oAuthAdminListCount = $oAuthAdminList->get();
        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $oAuthAdmin->avatar = PublicFileUtils::createUploadUrl($oAuthAdmin->avatar);
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['password'] = $oAuthAdmin->password;
            $aTmp['tel'] = $oAuthAdmin->tel;
            $aTmp['email'] = $oAuthAdmin->email;
            $aTmp['avatar'] = $oAuthAdmin->avatar;
            $aTmp['sex'] = $oAuthAdmin->sex;
            $aTmp['last_login_ip'] = $oAuthAdmin->last_login_ip;
            $aTmp['last_login_time'] = $oAuthAdmin->last_login_time;
            $aTmp['create_time'] = $oAuthAdmin->create_time;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $roles = AuthRoleAdmin::where('admin_id', $oAuthAdmin->id)->first();
            $temp_roles = [];
            if (is_object($roles)) {
                $temp_roles = $roles->toArray();
                $temp_roles = array_column($temp_roles, 'role_id');
            }
            $aTmp['roles'] = $temp_roles;
            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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

    public function manualpaySave()
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
        $oAuthAdminList = DB::table('fund_manualpay');

        $sTmp = 'DESC';
        if (substr($iSort, 0, 1) == '-') {
            $sTmp = 'ASC';
        }
        $sOrder = substr($iSort, 1, strlen($iSort));
        if ($sTmp != '') {
            $oAuthAdminList->orderby($sOrder, $sTmp);
        }
        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }
        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }
        $oAuthAdminListCount = $oAuthAdminList->get();
        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['avaible_amount'] = $oAuthAdmin->avaible_amount;
            $aTmp['in_project'] = $oAuthAdmin->in_project;
            $aTmp['in_amount'] = $oAuthAdmin->in_amount;
            $aTmp['in_benefit'] = $oAuthAdmin->in_benefit;
            $aTmp['general_project_audit'] = $oAuthAdmin->general_project_audit;
            $aTmp['common_audit'] = $oAuthAdmin->common_audit;
            $aTmp['memo'] = $oAuthAdmin->memo;
            $aTmp['user_memo'] = $oAuthAdmin->user_memo;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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

    public function manualpayconfirmList()
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
        $min = isset(request()->min) ? request()->min : '';
        $max = isset(request()->max) ? request()->max : '';
        $type = isset(request()->type) ? request()->type : '';
        $audit_status = isset(request()->audit_status) ? request()->audit_status : '';
        $operate_type = isset(request()->operate_type) ? request()->operate_type : '';
        $account = isset(request()->account) ? request()->account : '';
        $memo = isset(request()->memo) ? request()->memo : '';


        $oAuthAdminList = DB::table('fund_manualpayconfirm');


        if ($merchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }


        if ($beginDate !== '') {
            $oAuthAdminList->where('request_date', '>=', $beginDate);
        }

        if ($endDate !== '') {
            $oAuthAdminList->where('request_date', '<=', $endDate);
        }


//        if ($confirm_beginDate !== '') {
//            $oAuthAdminList->where('confirm_date', '>=', $confirm_beginDate);
//        }
//
//        if ($confirm_endDate !== '') {
//            $oAuthAdminList->where('confirm_date', '>=', $confirm_endDate);
//        }

        // 查询页面有问题
        if ($min !== '') {
            $oAuthAdminList->where('in_amount', '>=', $min);
        }

        if ($max !== '') {
            $oAuthAdminList->where('in_amount', '>=', $max);
        }


        if ($type !== '') {
            $oAuthAdminList->where('type', '=', $type);
        }


        if ($audit_status !== '') {
            $oAuthAdminList->where('status', '=', $audit_status);
        }


        switch ($operate_type) {
            case '会员账号':
                $oAuthAdminList->where('account', '=', $account);
                break;
            case '提交人':
                $oAuthAdminList->where('submitor', '=', $account);
                break;
            case '操作人':
                $oAuthAdminList->where('auditor', '=', $account);
                break;

        }

        if ($memo !== '') {
            $oAuthAdminList->where('audit_memo', '=', $memo);
        }


//        if ($out_status !== '') {
//            $oAuthAdminList->where('status', '=', $out_status);
//        }
//
//
//
//        if ($out_status !== '') {
//            $oAuthAdminList->where('status', '=', $out_status);
//        }
//
//
//        if ($order_no !== '') {
//            $oAuthAdminList->where('order_no', '=', $order_no);
//        }
//
//
//        if ($account !== '') {
//            $oAuthAdminList->where('account', '=', $account);
//        }


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
        $oAuthAdminListCount = $oAuthAdminList->get();
        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['in_amount'] = $oAuthAdmin->in_amount;
            $aTmp['out_amount'] = $oAuthAdmin->out_amount;
            $aTmp['benefit_amount'] = $oAuthAdmin->benefit_amount;
            $aTmp['out_in_amount'] = $oAuthAdmin->out_in_amount;
            $aTmp['out_bankcardnumber'] = $oAuthAdmin->out_bankcardnumber;
            $aTmp['general_project'] = $oAuthAdmin->general_project;
            $aTmp['common_audit'] = $oAuthAdmin->common_audit;
            $aTmp['request_date'] = $oAuthAdmin->request_date;
            $aTmp['confirm_date'] = $oAuthAdmin->confirm_date;
            $aTmp['submitor'] = $oAuthAdmin->submitor;
            $aTmp['deposit_type'] = $oAuthAdmin->deposit_type;
            $aTmp['auditor'] = $oAuthAdmin->auditor;
            $aTmp['audit_memo'] = $oAuthAdmin->audit_memo;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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

    public function payaccountList()
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
        $oAuthAdminList = DB::table('fund_deposit_account');


        $iStatus = isset(request()->status) ? request()->status : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $sPayType = isset(request()->pay_type) ? request()->pay_type : '';

        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }

        if ($sPayType !== '') {
            $oAuthAdminList->where('pay_type', $sPayType);
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
            $aTmp['user_levels'] = $oAuthAdmin->user_levels;
            $aTmp['pay_type'] = $oAuthAdmin->pay_type;
            $aTmp['bank'] = $oAuthAdmin->bank;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['min'] = $oAuthAdmin->min;
            $aTmp['max'] = $oAuthAdmin->max;
            $aTmp['account_alias'] = $oAuthAdmin->account_alias;
            $aTmp['display_flag'] = $oAuthAdmin->display_flag;
            $aTmp['qr_code'] = $oAuthAdmin->qr_code;
            $aTmp['postscript_flag'] = $oAuthAdmin->postscript_flag;
            $aTmp['receiver'] = $oAuthAdmin->receiver;
            $aTmp['alert'] = $oAuthAdmin->alert;
            $aTmp['order_flag'] = $oAuthAdmin->order_flag;
            $aTmp['sequence'] = $oAuthAdmin->sequence;
            $aTmp['status'] = $oAuthAdmin->status;
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

    public function paygroupList()
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
        $oAuthAdminList = DB::table('fund_paytype');


        $iStatus = isset(request()->status) ? request()->status : '';
        $sInType = isset(request()->in_type) ? request()->in_type : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';


        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sInType !== '') {
            $oAuthAdminList->where('in_type', $sInType);
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
            $aTmp['in_type'] = $oAuthAdmin->in_type;
            $aTmp['pay_type'] = $oAuthAdmin->pay_type;
            $aTmp['sequence'] = $oAuthAdmin->sequence;
            $aTmp['property'] = $oAuthAdmin->property;
            $aTmp['pay_type_alias'] = $oAuthAdmin->pay_type_alias;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }

        $res = [];
//        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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

    public function transferorderList()
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
        $min = isset(request()->min) ? request()->min : '';
        $max = isset(request()->max) ? request()->max : '';
        $select_search_type = isset(request()->select_search_type) ? request()->select_search_type : '';
        $keywords = isset(request()->keywords) ? request()->keywords : '';
        $platform = isset(request()->platform) ? request()->platform : '';
        $status = isset(request()->status) ? request()->status : '';


        $oAuthAdminList = DB::table('fund_transferorder');


        if ($merchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($beginDate !== '') {
            $oAuthAdminList->where('date', '>=', $beginDate);
        }

        if ($endDate !== '') {
            $oAuthAdminList->where('date', '<=', $endDate);
        }

        if ($min !== '') {
            $oAuthAdminList->where('transfer_amount', '>=', $min);
        }

        if ($max !== '') {
            $oAuthAdminList->where('transfer_amount', '<=', $max);
        }


        if ($select_search_type == '用户名') {
            if ($keywords !== '') {
                $oAuthAdminList->where('username', 'like', '%' . $keywords . '%');
            }
        } elseif ($select_search_type == '订单编号') {
            if ($keywords !== '') {
                $oAuthAdminList->where('order_number', 'like', '%' . $keywords . '%');
            }
        }


        if ($platform !== '') {
            $oAuthAdminList->where('transfer_platform', '=', $platform);
        }


        if ($status !== '') {
            $oAuthAdminList->where('status', '=', $status);
        }

        $oAuthAdminListCount = $oAuthAdminList->get();
        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['order_number'] = $oAuthAdmin->order_number;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['transfer_platform'] = $oAuthAdmin->transfer_platform;
            $aTmp['transfer_amount'] = $oAuthAdmin->transfer_amount;
            $aTmp['main_avaibleamount_pre'] = $oAuthAdmin->main_avaibleamount_pre;
            $aTmp['main_avaibleamount_after'] = $oAuthAdmin->main_avaibleamount_after;
            $aTmp['platform_amount_pre'] = $oAuthAdmin->platform_amount_pre;
            $aTmp['platform_amount_after'] = $oAuthAdmin->platform_amount_after;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
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

    public function tripartiteList()
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

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $iStatus = isset(request()->status) ? request()->status : '';

        $sPayType = isset(request()->pay_type) ? request()->pay_type : '';

        $sThirdCompany = isset(request()->third_company) ? request()->third_company : '';

        $sThirdType = isset(request()->third_type) ? request()->third_type : '';

        $oAuthAdminList = DB::table('fund_third_account');


        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }
        if ($sPayType !== '') {
            $oAuthAdminList->where('pay_type', $sPayType);
        }
        if ($sThirdCompany !== '') {
            $oAuthAdminList->where('third_company', 'like', '%' . $sThirdCompany . '%');
        }
        if ($sThirdType !== '') {
            $oAuthAdminList->where('third_type', $sThirdType);
        }

        $oAuthAdminFinalList = $oAuthAdminList->get();

        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['layers'] = $oAuthAdmin->layers;
            $aTmp['third_company'] = $oAuthAdmin->third_company;
            $aTmp['pay_type'] = $oAuthAdmin->pay_type;
            $aTmp['mobile_display_flag'] = $oAuthAdmin->mobile_display_flag;
            $aTmp['decimal_flag'] = $oAuthAdmin->decimal_flag;
            $aTmp['deposit_type'] = $oAuthAdmin->deposit_type;
            $aTmp['min'] = $oAuthAdmin->min;
            $aTmp['max'] = $oAuthAdmin->max;
            $aTmp['quota'] = $oAuthAdmin->quota;
            $aTmp['query_flag'] = $oAuthAdmin->query_flag;
            $aTmp['merchant_code'] = $oAuthAdmin->merchant_code;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['private_key'] = $oAuthAdmin->private_key;
            $aTmp['public_key'] = $oAuthAdmin->public_key;
            $aTmp['pay_domain'] = $oAuthAdmin->pay_domain;
            $aTmp['gateway'] = $oAuthAdmin->gateway;
            $aTmp['query_url'] = $oAuthAdmin->query_url;
            $aTmp['third_type'] = $oAuthAdmin->third_type;
            $aTmp['status'] = $oAuthAdmin->status;
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

    public function userbetscheckList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
//        $iStatus = isset(request()->status) ? request()->status : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $sUserName = isset(request()->username) ? request()->username : '';
        $oAuthAdminList = DB::table('fund_userbetscheck');

//        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }
        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        $oAuthAdminFinalList = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['deposit_date'] = $oAuthAdmin->deposit_date;
            $aTmp['depoist_amount'] = $oAuthAdmin->depoist_amount;
            $aTmp['benefit'] = $oAuthAdmin->benefit;
            $aTmp['valid_project'] = $oAuthAdmin->valid_project;
            $aTmp['general_project'] = $oAuthAdmin->general_project;
            $aTmp['general_is_obtain'] = $oAuthAdmin->general_is_obtain;
            $aTmp['common_project'] = $oAuthAdmin->common_project;
            $aTmp['quota'] = $oAuthAdmin->quota;
            $aTmp['commin_is_obtain'] = $oAuthAdmin->commin_is_obtain;
            $aTmp['no_subtraction_fee'] = $oAuthAdmin->no_subtraction_fee;
            $aTmp['subtraction_fee'] = $oAuthAdmin->subtraction_fee;
            $aTmp['memo'] = $oAuthAdmin->memo;
            $aTmp['status'] = $oAuthAdmin->status;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = 22222;
        $res["list"] = $aFinal;
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


    public function payGroupStatusSave($id = null)
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
        $oEvent = PayGroup::find($id);
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


    public function cashwithdrawStatusSave($id = null)
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
        $oEvent = CashWithdraw::find($id);
//        $iFlag = 0;
        if (is_object($oEvent)) {
            $iStatue = $oEvent->status;
        }
//        $iFlag = $iStatue == 0 ? 1 : 0;
        $oEvent->out_status = $iFlag;
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

    public function paysettingDelete()
    {
//        $id = request()->post('id/d');
        $id = request()->all()['id'];
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$id)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $id)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        PaySetting::where('id', '=', $id)->delete();

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

    public function payaccountDelete()
    {
//        $id = request()->post('id/d');
        $id = request()->all()['id'];
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$id)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $id)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        DepositAccount::where('id', '=', $id)->delete();

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


    public function tripartiteDelete()
    {
//        $id = request()->post('id/d');
        $id = request()->all()['id'];
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$id)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $id)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        ThirdAccount::where('id', '=', $id)->delete();

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


    public function userbetscheckStatusSave($id = null)
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
        $oEvent = UserBetsCheck::find($id);
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


    public function transferorderStatusSave($id = null)
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
        $oEvent = TransferOrder::find($id);
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

    public function manualpayStatusSave($id = null)
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
        $oEvent = ManualPay::find($id);
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


    public function manualpayconfirmStatusSave($id = null)
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
        $oEvent = ManualPayConfirm::find($id);
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


    public function companymoneyStatusSave($id = null)
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
        $oEvent = CompanyMoney::find($id);
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


    public function fastpaymoneyStatusSave($id = null)
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
        $oEvent = FastPayMoney::find($id);
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


    public function rakebackStatusSave($id = null)
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

        $aTmp = explode(",", $id);

        if ($bSucc = RakeBack::whereIn('id', $aTmp)->update(['status' => $iFlag]) > 0) {

        }


//
//        $oEvent = RakeBack::find($id);
////        $iFlag = 0;
//        if (is_object($oEvent)) {
//            $iStatue = $oEvent->status;
//        }
////        $iFlag = $iStatue == 0 ? 1 : 0;
//        $oEvent->status = $iFlag;
//        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
//        $aFinal['data'] = $oEvent;

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


    public function sequenceSave($id = null)
    {

        $data = request()->post();
//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);
        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/
        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = PayGroup::find($id);
        $oEvent->sequence = $iFlag;
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

    public function propertySave($id = null)
    {

        $data = request()->post();
//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);
        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/
        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['property']) ? $data['property'] : '';

        $sFirst1 = substr($iFlag, 0, 1);
        $bFlag1 = false;
        if ($sFirst1 == '+') {
            $bFlag1 = true;
        }

        $oEvent = PayGroup::find($id);

        if ($iFlag != '') {
            if ($bFlag1) {
                $oEvent->property = substr($iFlag, 1, strlen($iFlag));
            } else {
                $oEvent->property = '';
            }

        }
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

    public function paytypeAliasSave($id = null)
    {

        $data = request()->post();
//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);
        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/
        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['paytype_alias']) ? $data['paytype_alias'] : '';
        $oEvent = PayGroup::find($id);
        $oEvent->pay_type_alias = $iFlag;
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


    public function payAccountSequence($id = null)
    {

        $data = request()->post();
//        $sId = isset($data['id']) ? $data['id'] : '';
        /*$iFlag = isset($data['flag']) ? $data['flag'] : '';
        $aTmp = Event::getArrayFromString($sId);
        Log::info($aTmp);

        if ($bSucc = EventUserPrize::whereIn('id',$aTmp)->update(['status' => $iFlag]) > 0) {

        }*/
        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = DepositAccount::find($id);
        $oEvent->sequence = $iFlag;
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


    public function payAccountStatusSave($id = null)
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
        $oEvent = PayAccount::find($id);
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

}