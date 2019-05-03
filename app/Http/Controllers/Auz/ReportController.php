<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\Ad;
use App\model\AdSite;

use App\common\utils\DateUtils;
use App\model\AdminLog;

/**
 * Class Event - 报表相关控制器
 * @author zebra
 */
class ReportController extends Controller
{

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function financeIndex()
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


        $oAuthAdminList = DB::table('report_finance');



        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($dtBeginDate !== '') {
            $oAuthAdminList->where('date', '>=', $dtBeginDate);
        }

        if ($dtEndDate !== '') {
            $oAuthAdminList->where('date', '<=', $dtEndDate);
        }

        $iLimit = request()->get('limit', 20);

        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


       /* $aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'financeIndex';
        $sLogContent = 'financeIndex';


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
    public function operationProfit()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';


        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';

        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';

        $model = isset(request()->model) ? request()->model : '';

        $platform = isset(request()->platform) ? request()->platform : '';

        $sUserName = isset(request()->username) ? request()->username : '';


        $dtPeriod = isset(request()->datePeriod) ? request()->datePeriod : '';

        $oAuthAdminList = DB::table('report_operation_profit');



        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($dtBeginDate !== '') {
            $oAuthAdminList->where('date', '>=', $dtBeginDate);
        }

        if ($dtEndDate !== '') {
            $oAuthAdminList->where('date', '<=', $dtEndDate);
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


        if ($dtPeriod != '') {
            $aTmp = DateUtils::getDateArray($dtPeriod);
            $oAuthAdminList->where('date', '>=', date('Y-m-d 00:00:00',$aTmp['begin_date']));
            $oAuthAdminList->where('date', '<=', date('Y-m-d 23:59:59',$aTmp['end_date']));

        }

        $iLimit = request()->get('limit', 20);

        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'operationProfit';
        $sLogContent = 'operationProfit';


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
    public function pgamePlaylist()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $way_type = isset(request()->way_type) ? request()->way_type : '';
        $lottery = isset(request()->lottery) ? request()->lottery : '';
        $way = isset(request()->way) ? request()->way : '';
        $prize_status = isset(request()->prize_status) ? request()->prize_status : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $sort = isset(request()->sort) ? request()->sort : '';
        $prize_type = isset(request()->prize_type) ? request()->prize_type : '';
        $iMin = isset(request()->min) ? request()->min : '';
        $iMax = isset(request()->max) ? request()->max : '';
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

        if ($dtBeginDate !== '') {
            $oAuthAdminList->where('date', '>=', $dtBeginDate);
        }

        if ($dtEndDate !== '') {
            $oAuthAdminList->where('date', '>=', $dtEndDate);
        }

        if ($sort == '逆序') {
            $oAuthAdminList->orderBy('id', 'DESC');
        }

        if ($sort == '顺序') {
            $oAuthAdminList->orderBy('id', 'asc');
        }

        if ($prize_type == '奖金') {
            if ($iMin !== '') {
                $oAuthAdminList->where('prize_amount', '>=', $iMin);
            }

            if ($iMax !== '') {
                $oAuthAdminList->where('prize_amount', '>=', $iMax);
            }
        }

        if ($prize_type == '倍数') {
            if ($iMin !== '') {
                $oAuthAdminList->where('multiple', '>=', $iMin);
            }

            if ($iMax !== '') {
                $oAuthAdminList->where('multiple', '>=', $iMax);
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

        $iLimit = request()->get('limit', 20);

        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'pgamePlaylist';
        $sLogContent = 'pgamePlaylist';


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
    public function preportProfit()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $sUserName = isset(request()->username) ? request()->username : '';

        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';

        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';

        $dtPeriod = isset(request()->datePeriod) ? request()->datePeriod : '';


        $oAuthAdminList = DB::table('report_platform');



        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }


        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }

        if ($dtBeginDate !== '') {
            $oAuthAdminList->where('date', '>=', $dtBeginDate);
        }

        if ($dtEndDate !== '') {
            $oAuthAdminList->where('date', '<=', $dtEndDate);
        }


        if ($dtPeriod != '') {
            $aTmp = DateUtils::getDateArray($dtPeriod);
            $oAuthAdminList->where('date', '>=', date('Y-m-d 00:00:00',$aTmp['begin_date']));
            $oAuthAdminList->where('date', '<=', date('Y-m-d 23:59:59',$aTmp['end_date']));

        }

        $iLimit = request()->get('limit', 20);

        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'preportProfit';
        $sLogContent = 'preportProfit';


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
    public function userReport()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $dtPeriod = isset(request()->datePeriod) ? request()->datePeriod : '';

        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';

        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';

        $model = isset(request()->model) ? request()->model : '';

        $platform = isset(request()->platform) ? request()->platform : '';

        $sUserName = isset(request()->username) ? request()->username : '';

        $oAuthAdminList = DB::table('report_user');



        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($dtBeginDate !== '') {
            $oAuthAdminList->where('date', '>=', $dtBeginDate);
        }

        if ($dtEndDate !== '') {
            $oAuthAdminList->where('date', '<=', $dtEndDate);
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

        if ($dtPeriod != '') {
            $aTmp = DateUtils::getDateArray($dtPeriod);
            $oAuthAdminList->where('date', '>=', date('Y-m-d 00:00:00',$aTmp['begin_date']));
            $oAuthAdminList->where('date', '<=', date('Y-m-d 23:59:59',$aTmp['end_date']));

        }

        $iLimit = request()->get('limit', 20);

        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /*$aTmp = [];
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
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;




        $sOperateName = 'userReport';
        $sLogContent = 'userReport';


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
    public function getDayBetween()
    {
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['begin_date'] = '2011-11-11';
        $aFinal['end_date'] = '2011-11-11';


        $sOperateName = 'getDayBetween';
        $sLogContent = 'getDayBetween';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


}