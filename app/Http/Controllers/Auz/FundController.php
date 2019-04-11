<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\AuthRoleAdmin;
use App\common\utils\PublicFileUtils;
use App\model\Ad;
use App\model\AdSite;
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
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function cashOrderlist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $select_search_type = isset(request()->select_search_type) ? request()->select_search_type : '';
        $sKeywords = isset(request()->keywords) ? request()->keywords : '';
        $is_has_child = isset(request()->is_has_child) ? request()->is_has_child : '';
        $transaction_type = isset(request()->transaction_type) ? request()->transaction_type : '';
        $sort_type = isset(request()->sort_type) ? request()->sort_type : '';
        $iMin = isset(request()->min) ? request()->min : '';
        $iMax = isset(request()->max) ? request()->max : '';

        $oAuthAdminList = DB::table('fund_transaction');


        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($dtBeginDate != '') {
            $oAuthAdminList->where('date', '>=', $dtBeginDate);
        }

        if ($dtEndDate != '') {
            $oAuthAdminList->where('date', '>=', $dtEndDate);
        }


        if ($select_search_type == '会员账号') {
            if ($sKeywords != '') {
                $oAuthAdminList->where('account', 'like', '%' . $sKeywords . '%');
            }
        } elseif ($select_search_type == '订单号') {
            if ($sKeywords != '') {
                $oAuthAdminList->where('order_number', 'like', '%' . $sKeywords . '%');
            }

        } elseif ($select_search_type == 'IP地址') {
            if ($sKeywords != '') {
                $oAuthAdminList->where('ip_address', 'like', '%' . $sKeywords . '%');
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

        if ($dtBeginDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtBeginDate);
        }

        if ($dtEndDate != '') {
            $oAuthAdminList->where('created_at', '>=', $dtEndDate);
        }

        if ($iMin != '') {
            $oAuthAdminList->where('avaiable_amount', '>=', $iMin);
        }

        if ($iMax != '') {
            $oAuthAdminList->where('avaiable_amount', '<=', $iMax);
        }


        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'cashOrderlist';
        $sLogContent = 'cashOrderlist';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function cashPaysetting()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $oAuthAdminList = DB::table('fund_paysetting');

        $iStatus = isset(request()->status) ? request()->status : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', $sMerchantName);
        }

        if ($iStatus != '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


       /* $aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;



        $sOperateName = 'cashPaysetting';
        $sLogContent = 'cashPaysetting';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function cashRakeback()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
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


        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'cashRakeback';
        $sLogContent = 'cashRakeback';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function cashWithdrawlist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $request_beginDate = isset(request()->request_beginDate) ? request()->request_beginDate : '';
        $request_endDate = isset(request()->request_endDate) ? request()->request_endDate : '';
        $confirm_beginDate = isset(request()->confirm_beginDate) ? request()->confirm_beginDate : '';
        $confirm_endDate = isset(request()->confirm_endDate) ? request()->confirm_endDate : '';
        $iMin = isset(request()->min) ? request()->min : '';
        $iMax = isset(request()->max) ? request()->max : '';
        $refresh_frequency = isset(request()->refresh_frequency) ? request()->refresh_frequency : '';
        $out_type = isset(request()->out_type) ? request()->out_type : '';
        $out_status = isset(request()->out_status) ? request()->out_status : '';
        $order_no = isset(request()->order_no) ? request()->order_no : '';
        $sAccount = isset(request()->account) ? request()->account : '';

        $oAuthAdminList = DB::table('fund_cashwithdraw');


        if ($sMerchantName != '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
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

        if ($iMin !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $iMin);
        }

        if ($iMax !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $iMax);
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


        if ($sAccount !== '') {
            $oAuthAdminList->where('account', '=', $sAccount);
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'cashWithdrawlist';
        $sLogContent = 'cashWithdrawlist';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function paysettingSave()
    {
        $data = request()->post();


        $sName = isset($data['name']) ? $data['name'] : '';
        $no_project_flag = isset($data['no_project_flag']) ? $data['no_project_flag'] : '';
        $no_charge_times = isset($data['no_charge_times']) ? $data['no_charge_times'] : '';
        $fee = isset($data['fee']) ? $data['fee'] : '';
        $fee_type = isset($data['fee_type']) ? $data['fee_type'] : '';

        $iWithdrawTimes = isset($data['withdraw_times']) ? $data['withdraw_times'] : '';
        $sWithdrawMax = isset($data['withdraw_max']) ? $data['withdraw_max'] : '';

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


//        $oQrCode->id = $iId;


        $oQrCode->name = $sName;
        $oQrCode->no_project_flag = $no_project_flag;
        $oQrCode->no_charge_times = $no_charge_times;
        $oQrCode->fee = $fee;
        $oQrCode->fee_type = $fee_type;

        $oQrCode->withdraw_times = $iWithdrawTimes;
        $oQrCode->withdraw_max = $sWithdrawMax;

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


        $sOperateName = 'paysettingSave';
        $sLogContent = 'paysettingSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function depositAccountSave()
    {
        $data = request()->post();


        $sUserLevels = isset($data['user_levels']) ? $data['user_levels'] : '';
        $pay_type = isset($data['pay_type']) ? $data['pay_type'] : '';
        $bank = isset($data['bank']) ? $data['bank'] : '';
        $sAccount = isset($data['account']) ? $data['account'] : '';
        $iMin = isset($data['min']) ? $data['min'] : '';
        $iMax = isset($data['max']) ? $data['max'] : '';
        $sAccount_alias = isset($data['account_alias']) ? $data['account_alias'] : '';
        $display_flag = isset($data['display_flag']) ? $data['display_flag'] : '';
        $qr_code = isset($data['qr_code']) ? $data['qr_code'] : '';
        $postscript_flag = isset($data['postscript_flag']) ? $data['postscript_flag'] : '';
        $receiver = isset($data['receiver']) ? $data['receiver'] : '';
        $alert = isset($data['alert']) ? $data['alert'] : '';
        $order_flag = isset($data['order_flag']) ? $data['order_flag'] : '';

        $oQrCode = new DepositAccount();


//        $oQrCode->id = $iId;


        $oQrCode->user_levels = $sUserLevels;
        $oQrCode->pay_type = $pay_type;
        $oQrCode->bank = $bank;
        $oQrCode->account = $sAccount;
        $oQrCode->min = $iMin;
        $oQrCode->max = $iMax;
        $oQrCode->account_alias = $sAccount_alias;
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

        $sOperateName = 'depositAccountSave';
        $sLogContent = 'depositAccountSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdAccountSave()
    {
        $data = request()->post();


        $sLayers = isset($data['layers']) ? $data['layers'] : '';
        $third_company = isset($data['third_company']) ? $data['third_company'] : '';
        $pay_type = isset($data['pay_type']) ? $data['pay_type'] : '';
        $mobile_display_flag = isset($data['mobile_display_flag']) ? $data['mobile_display_flag'] : '';
        $decimal_flag = isset($data['decimal_flag']) ? $data['decimal_flag'] : '';
        $deposit_type = isset($data['deposit_type']) ? $data['deposit_type'] : '';
        $iMin = isset($data['min']) ? $data['min'] : '';
        $iMax = isset($data['max']) ? $data['max'] : '';
        $quota = isset($data['quota']) ? $data['quota'] : '';
        $query_flag = isset($data['query_flag']) ? $data['query_flag'] : '';
        $merchant_code = isset($data['merchant_code']) ? $data['merchant_code'] : '';
        $iMerchantId = isset($data['merchant_id']) ? $data['merchant_id'] : '';
        $private_key = isset($data['private_key']) ? $data['private_key'] : '';
        $public_key = isset($data['public_key']) ? $data['public_key'] : '';
        $pay_domain = isset($data['pay_domain']) ? $data['pay_domain'] : '';
        $gateway = isset($data['gateway']) ? $data['gateway'] : '';
        $query_url = isset($data['query_url']) ? $data['query_url'] : '';

        $oQrCode = new ThirdAccount();
//        $oQrCode->id = $iId;
        $oQrCode->layers = $sLayers;
        $oQrCode->third_company = $third_company;
        $oQrCode->pay_type = $pay_type;
        $oQrCode->mobile_display_flag = $mobile_display_flag;
        $oQrCode->decimal_flag = $decimal_flag;
        $oQrCode->deposit_type = $deposit_type;
        $oQrCode->min = $iMin;
        $oQrCode->max = $iMax;
        $oQrCode->quota = $quota;
        $oQrCode->query_flag = $query_flag;
        $oQrCode->merchant_code = $merchant_code;
        $oQrCode->merchant_id = $iMerchantId;
        $oQrCode->private_key = $private_key;
        $oQrCode->public_key = $public_key;
        $oQrCode->pay_domain = $pay_domain;
        $oQrCode->gateway = $gateway;
        $oQrCode->query_url = $query_url;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'thirdAccountSave';
        $sLogContent = 'thirdAccountSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function companymoneyList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $request_beginDate = isset(request()->request_beginDate) ? request()->request_beginDate : '';
        $request_endDate = isset(request()->request_endDate) ? request()->request_endDate : '';
        $confirm_beginDate = isset(request()->confirm_beginDate) ? request()->confirm_beginDate : '';
        $confirm_endDate = isset(request()->confirm_endDate) ? request()->confirm_endDate : '';
        $iMin = isset(request()->min) ? request()->min : '';
        $iMax = isset(request()->max) ? request()->max : '';
        $refresh_frequency = isset(request()->refresh_frequency) ? request()->refresh_frequency : '';
        $status = isset(request()->status) ? request()->status : '';
        $in_account = isset(request()->in_account) ? request()->in_account : '';
        $select_search_type = isset(request()->select_search_type) ? request()->select_search_type : '';
        $sKeywords = isset(request()->keywords) ? request()->keywords : '';


        $oAuthAdminList = DB::table('fund_companymoney');


        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
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

        if ($iMin !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $iMin);
        }

        if ($iMax !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $iMax);
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
                $oAuthAdminList->where('account', '=', $sKeywords);
                break;
            case '存款人':
                $oAuthAdminList->where('depositor_name', '=', $sKeywords);
                break;
            case '附言码':
                $oAuthAdminList->where('postscript', '=', $sKeywords);
                break;
            case '订单号':
                $oAuthAdminList->where('order_number', '=', $sKeywords);
                break;

        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);
        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'companymoneyList';
        $sLogContent = 'companymoneyList';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function fastpaymoneyList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $request_beginDate = isset(request()->request_beginDate) ? request()->request_beginDate : '';
        $request_endDate = isset(request()->request_endDate) ? request()->request_endDate : '';
        $confirm_beginDate = isset(request()->confirm_beginDate) ? request()->confirm_beginDate : '';
        $confirm_endDate = isset(request()->confirm_endDate) ? request()->confirm_endDate : '';
        $iMin = isset(request()->min) ? request()->min : '';
        $iMax = isset(request()->max) ? request()->max : '';
        $refresh_frequency = isset(request()->refresh_frequency) ? request()->refresh_frequency : '';
        $status = isset(request()->status) ? request()->status : '';
        $pay_type = isset(request()->pay_type) ? request()->pay_type : '';
        $in_account = isset(request()->in_account) ? request()->in_account : '';
        $select_search_type = isset(request()->select_search_type) ? request()->select_search_type : '';
        $sKeywords = isset(request()->keywords) ? request()->keywords : '';


        $oAuthAdminList = DB::table('fund_fastpaymoney');


        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
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

        if ($iMin !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $iMin);
        }

        if ($iMax !== '') {
            $oAuthAdminList->where('final_out_amount', '>=', $iMax);
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
                $oAuthAdminList->where('account', '=', $sKeywords);
                break;
            case '提交人':
                $oAuthAdminList->where('submitor', '=', $sKeywords);
                break;
            case '操作人':
                $oAuthAdminList->where('auditor', '=', $sKeywords);
                break;
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'fastpaymoneyList';
        $sLogContent = 'fastpaymoneyList';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function layerchartIndex()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
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
        $oAuthAdminFinalList = $oAuthAdminList->skip(($sIpage - 1) * $iLimit)->take($iLimit)->get();
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


        $sOperateName = 'floatwindowconfigList';
        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function manualpaySave()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
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
        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'manualpaySave';
        $sLogContent = 'manualpaySave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function manualpayconfirmList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $iMin = isset(request()->min) ? request()->min : '';
        $iMax = isset(request()->max) ? request()->max : '';
        $sType = isset(request()->type) ? request()->type : '';
        $audit_status = isset(request()->audit_status) ? request()->audit_status : '';
        $sOperateType = isset(request()->operate_type) ? request()->operate_type : '';
        $sAccount = isset(request()->account) ? request()->account : '';
        $sMemo = isset(request()->memo) ? request()->memo : '';


        $oAuthAdminList = DB::table('fund_manualpayconfirm');


        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }


        if ($dtBeginDate !== '') {
            $oAuthAdminList->where('request_date', '>=', $dtBeginDate);
        }

        if ($dtEndDate !== '') {
            $oAuthAdminList->where('request_date', '<=', $dtEndDate);
        }


//        if ($confirm_beginDate !== '') {
//            $oAuthAdminList->where('confirm_date', '>=', $confirm_beginDate);
//        }
//
//        if ($confirm_endDate !== '') {
//            $oAuthAdminList->where('confirm_date', '>=', $confirm_endDate);
//        }

        // 查询页面有问题
        if ($iMin !== '') {
            $oAuthAdminList->where('in_amount', '>=', $iMin);
        }

        if ($iMax !== '') {
            $oAuthAdminList->where('in_amount', '>=', $iMax);
        }


        if ($sType !== '') {
            $oAuthAdminList->where('type', '=', $sType);
        }


        if ($audit_status !== '') {
            $oAuthAdminList->where('status', '=', $audit_status);
        }


        switch ($sOperateType) {
            case '会员账号':
                $oAuthAdminList->where('account', '=', $sAccount);
                break;
            case '提交人':
                $oAuthAdminList->where('submitor', '=', $sAccount);
                break;
            case '操作人':
                $oAuthAdminList->where('auditor', '=', $sAccount);
                break;

        }

        if ($sMemo !== '') {
            $oAuthAdminList->where('audit_memo', '=', $sMemo);
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
//        if ($sAccount !== '') {
//            $oAuthAdminList->where('account', '=', $sAccount);
//        }
        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'manualpayconfirmList';
        $sLogContent = 'manualpayconfirmList';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function payaccountList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
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

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'payaccountList';
        $sLogContent = 'payaccountList';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function paygroupList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
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

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'paygroupList';
        $sLogContent = 'paygroupList';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function transferorderList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $iMin = isset(request()->min) ? request()->min : '';
        $iMax = isset(request()->max) ? request()->max : '';
        $select_search_type = isset(request()->select_search_type) ? request()->select_search_type : '';
        $sKeywords = isset(request()->keywords) ? request()->keywords : '';
        $platform = isset(request()->platform) ? request()->platform : '';
        $status = isset(request()->status) ? request()->status : '';


        $oAuthAdminList = DB::table('fund_transferorder');


        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($dtBeginDate !== '') {
            $oAuthAdminList->where('date', '>=', $dtBeginDate);
        }

        if ($dtEndDate !== '') {
            $oAuthAdminList->where('date', '<=', $dtEndDate);
        }

        if ($iMin !== '') {
            $oAuthAdminList->where('transfer_amount', '>=', $iMin);
        }

        if ($iMax !== '') {
            $oAuthAdminList->where('transfer_amount', '<=', $iMax);
        }


        if ($select_search_type == '用户名') {
            if ($sKeywords !== '') {
                $oAuthAdminList->where('username', 'like', '%' . $sKeywords . '%');
            }
        } elseif ($select_search_type == '订单编号') {
            if ($sKeywords !== '') {
                $oAuthAdminList->where('order_number', 'like', '%' . $sKeywords . '%');
            }
        }


        if ($platform !== '') {
            $oAuthAdminList->where('transfer_platform', '=', $platform);
        }


        if ($status !== '') {
            $oAuthAdminList->where('status', '=', $status);
        }

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'transferorderList';
        $sLogContent = 'transferorderList';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function tripartiteList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
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

        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'tripartiteList';
        $sLogContent = 'tripartiteList';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function userbetscheckList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

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
        $iLimit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

       /* $aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'userbetscheckList';
        $sLogContent = 'userbetscheckList';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function payGroupStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = PayGroup::find($iId);
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


        $sOperateName = 'payGroupStatusSave';
        $sLogContent = 'payGroupStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function cashwithdrawStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = CashWithdraw::find($iId);
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



        $sOperateName = 'cashwithdrawStatusSave';
        $sLogContent = 'cashwithdrawStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function paysettingDelete()
    {
//        $iId = request()->post('id/d');
        $iId = request()->all()['id'];
        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$iId)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $iId)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        PaySetting::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'paysettingDelete';
        $sLogContent = 'paysettingDelete';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success();

    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function payaccountDelete()
    {
//        $iId = request()->post('id/d');
        $iId = request()->all()['id'];
        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$iId)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $iId)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        DepositAccount::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'payaccountDelete';
        $sLogContent = 'payaccountDelete';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success();

    }

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function tripartiteDelete()
    {
//        $iId = request()->post('id/d');
        $iId = request()->all()['id'];
        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$iId)->field('username')->find();
//        $oAuthAdmin = AuthAdmin::where('id', $iId)->first();
//        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
////            return ResultVo::error(ErrorCode::NOT_NETWORK);
//        }
        // 删除权限
        ThirdAccount::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'tripartiteDelete';
        $sLogContent = 'tripartiteDelete';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success();

    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function userbetscheckStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = UserBetsCheck::find($iId);
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


        $sOperateName = 'userbetscheckStatusSave';
        $sLogContent = 'userbetscheckStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function transferorderStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = TransferOrder::find($iId);
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


        $sOperateName = 'transferorderStatusSave';
        $sLogContent = 'transferorderStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }
    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function manualpayStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = ManualPay::find($iId);
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


        $sOperateName = 'manualpayStatusSave';
        $sLogContent = 'manualpayStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function manualpayconfirmStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = ManualPayConfirm::find($iId);
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


        $sOperateName = 'manualpayconfirmStatusSave';
        $sLogContent = 'manualpayconfirmStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function companymoneyStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = CompanyMoney::find($iId);
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


        $sOperateName = 'companymoneyStatusSave';
        $sLogContent = 'companymoneyStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function fastpaymoneyStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = FastPayMoney::find($iId);
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


        $sOperateName = 'fastpaymoneyStatusSave';
        $sLogContent = 'fastpaymoneyStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function rakebackStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//

        $aTmp = explode(",", $iId);

        if ($bSucc = RakeBack::whereIn('id', $aTmp)->update(['status' => $iFlag]) > 0) {

        }

        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
//        $aFinal['data'] = $oEvent;


        $sOperateName = 'rakebackStatusSave';
        $sLogContent = 'rakebackStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function sequenceSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = PayGroup::find($iId);
        $oEvent->sequence = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'sequenceSave';
        $sLogContent = 'sequenceSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }
    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function propertySave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['property']) ? $data['property'] : '';

        $sFirst1 = substr($iFlag, 0, 1);
        $bFlag1 = false;
        if ($sFirst1 == '+') {
            $bFlag1 = true;
        }

        $oEvent = PayGroup::find($iId);

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


        $sOperateName = 'propertySave';
        $sLogContent = 'propertySave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }
    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function paytypeAliasSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['paytype_alias']) ? $data['paytype_alias'] : '';
        $oEvent = PayGroup::find($iId);
        $oEvent->pay_type_alias = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'paytypeAliasSave';
        $sLogContent = 'paytypeAliasSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function payAccountSequence($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = DepositAccount::find($iId);
        $oEvent->sequence = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'payAccountSequence';
        $sLogContent = 'payAccountSequence';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function payAccountStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        $oEvent = PayAccount::find($iId);
        $oEvent->status = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'payAccountStatusSave';
        $sLogContent = 'payAccountStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdAccountStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        $oEvent = ThirdAccount::find($iId);
        $oEvent->status = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'thirdAccountStatusSave';
        $sLogContent = 'thirdAccountStatusSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }
    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdAccountIsTopSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        $oEvent = ThirdAccount::find($iId);
        $oEvent->is_top = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'thirdAccountIsTopSave';
        $sLogContent = 'thirdAccountIsTopSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

}