<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\Ad;
use App\model\AdSite;

use App\common\utils\DateUtils;
use App\common\utils\CommonUtils;
use App\model\AdminLog;

/**
 * Class Event - 报表相关控制器
 * @author zebra
 */
class ReportController extends Controller
{
    //配置es地址
    const ES_URL = 'http://192.168.36.147:9200/';

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function financeIndex()
    {
        $sWhere = '';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $sSearchType = isset(request()->search_type) ? request()->search_type : '';
        $res = [];

        
        if ($sSearchType == 'ES') {
            if ($sMerchantName != '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Merchant_name": "'.$sMerchantName.'" } },';
            }

            if ($dtBeginDate != '') {
                $dtBeginDate = str_replace(' ','T',$dtBeginDate);
                $sWhere .= '{ "range":{ "Date": {"from" : "'.$dtBeginDate.'"}  } },';
            }
            if ($dtEndDate != '') {
                $dtEndDate = str_replace(' ','T',$dtEndDate);
                $sWhere .= '{ "range":{ "Date": {"to" : "'.$dtEndDate.'"} } },';
            }
            if (substr($sWhere, -1)==',') {
                $sWhere = substr($sWhere,0,-1);
            }
            $data['where'] = $sWhere;
            $data['page'] = $sIpage;
            $data['limit'] = $iLimit;
            $data['url'] = self::ES_URL."report_finance/_search?pretty";
            $sResult = CommonUtils::getCurlFileGetContents($data);
            $oFinanceIndexFinalList = AdminLog::getEsData($sResult,$iTotal);
            $res["total"] = $iTotal;
            $res["list"] = $oFinanceIndexFinalList;
            $aFinal['message'] = 'success';

            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        } else {
        
            $oFinanceIndexList = DB::table('report_finance');

            if ($sMerchantName !== '') {
                $oFinanceIndexList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
            }

            if ($dtBeginDate !== '' && !$dtPeriod) {
                $oFinanceIndexList->where('date', '>=', $dtBeginDate);
            }

            if ($dtEndDate !== '' && !$dtPeriod) {
                $oFinanceIndexList->where('date', '<=', $dtEndDate);
            }

            $iLimit = request()->get('limit', 20);

            $oFinanceIndexFinalList = $oFinanceIndexList->orderby('id', 'desc')->paginate($iLimit);

            $res["total"] = count($oFinanceIndexFinalList);
            $res["list"] = $oFinanceIndexFinalList->toArray();
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        }
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
        $sWhere = '';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $sModel = isset(request()->model) ? request()->model : '';
        $sPlatform = isset(request()->platform) ? request()->platform : '';
        $sUserName = isset(request()->username) ? request()->username : '';
        $dtPeriod = isset(request()->datePeriod) ? request()->datePeriod : '';
        $sSearchType = isset(request()->search_type) ? request()->search_type : '';
        $res = [];

        if ($sSearchType == 'ES') {

            if ($sMerchantName !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Merchant_name": "*'.$sMerchantName.'*" } },';
            }
            if ($dtBeginDate != '' && !$dtPeriod) {
                $dtBeginDate = str_replace(' ','T',$dtBeginDate);
                $sWhere .= '{ "range":{ "Date": {"from" : "'.$dtBeginDate.'"}  } },';
            }
            if ($dtEndDate != '' && !$dtPeriod) {
                $dtEndDate = str_replace(' ','T',$dtEndDate);
                $sWhere .= '{ "range":{ "Date": {"to" : "'.$dtEndDate.'"} } },';
            }
            if ($sModel !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Model": "*'.$sModel.'*" } },';
            }
            if ($sPlatform !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Platform": "*'.$sPlatform.'*" } },';
            }
            if ($sUserName !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Username": "*'.$sUserName.'*" } },';
            }
            if ($dtPeriod != '') {
                $aTmp = DateUtils::getDateArray($dtPeriod);
                $dtBeginDate = str_replace(' ','T',date('Y-m-d H:i:s',$aTmp['begin_date']));
                $dtEndDate = str_replace(' ','T',date('Y-m-d H:i:s',$aTmp['end_date']));
                $sWhere .= '{ "range":{ "Date": {"from" : "'.$dtBeginDate.'"}  } },';
                $sWhere .= '{ "range":{ "Date": {"to" : "'.$dtEndDate.'"} } },';

            }
            if (substr($sWhere, -1)==',') {
                $sWhere = substr($sWhere,0,-1);
            }
            $data['where'] = $sWhere;
            $data['page'] = $sIpage;
            $data['limit'] = $iLimit;
            $data['url'] = self::ES_URL."report_operation_profit/_search?pretty";
            
            $sResult = CommonUtils::getCurlFileGetContents($data);
            $oOperationProfitFinalList = AdminLog::getEsData($sResult,$iTotal);
            $res["total"] = $iTotal;
            $res["list"] = $oOperationProfitFinalList;
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        } else {
            $oOperationProfitList = DB::table('report_operation_profit');
            if ($sMerchantName !== '') {
                $oOperationProfitList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
            }
            if ($dtBeginDate !== '' && !$dtPeriod) {
                $oOperationProfitList->where('date', '>=', $dtBeginDate);
            }
            if ($dtEndDate !== '' && !$dtPeriod) {
                $oOperationProfitList->where('date', '<=', $dtEndDate);
            }
            if ($sModel !== '') {
                $oOperationProfitList->where('model', 'like', '%' . $sModel . '%');
            }
            if ($sPlatform !== '') {
                $oOperationProfitList->where('platform', 'like', '%' . $sPlatform . '%');
            }
            if ($sUserName !== '') {
                $oOperationProfitList->where('username', 'like', '%' . $sUserName . '%');
            }
            if ($dtPeriod != '') {
                $aTmp = DateUtils::getDateArray($dtPeriod);
                $oOperationProfitList->where('date', '>=', date('Y-m-d 00:00:00',$aTmp['begin_date']));
                $oOperationProfitList->where('date', '<=', date('Y-m-d 23:59:59',$aTmp['end_date']));
            }
            $iLimit = request()->get('limit', 20);
            $oOperationProfitFinalList = $oOperationProfitList->orderby('id', 'desc')->paginate($iLimit);
            $res["total"] = count($oOperationProfitFinalList);
            $res["list"] = $oOperationProfitFinalList->toArray();
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        }

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
        $sWhere = '';
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $sWayType = isset(request()->way_type) ? request()->way_type : '';
        $sLottery = isset(request()->lottery) ? request()->lottery : '';
        $sWay = isset(request()->way) ? request()->way : '';
        $sPrizeStatus = isset(request()->prize_status) ? request()->prize_status : '';
        $sStatus = isset(request()->status) ? request()->status : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $sSort = isset(request()->sort) ? request()->sort : '';
        $sPrizeType = isset(request()->prize_type) ? request()->prize_type : '';
        $iMin = isset(request()->min) ? request()->min : '';
        $iMax = isset(request()->max) ? request()->max : '';
        $sSelectInfoType = isset(request()->select_info_type) ? request()->select_info_type : '';
        $sIssue = isset(request()->issue) ? request()->issue : '';
        $sSearchType = isset(request()->search_type) ? request()->search_type : '';
        $res = [];
        if ($sSearchType == 'ES') {

            if ($sWayType !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Way_type": "*'.$sWayType.'*" } },';
            }

            if ($sLottery !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Lottery": "*'.$sLottery.'*" } },';
            }

            if ($sWay !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Way": "*'.$sWay.'*" } },';
            }

            if ($sStatus !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Status": "*'.$sStatus.'*" } },';
            }

            if ($sPrizeStatus !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Prize_status": "*'.$sPrizeStatus.'*" } },';
            }

            if ($dtBeginDate != '') {
                $dtBeginDate = str_replace(' ','T',$dtBeginDate);
                $sWhere .= '{ "range":{ "Date": {"from" : "'.$dtBeginDate.'"}  } },';
            }
            if ($dtEndDate != '') {
                $dtEndDate = str_replace(' ','T',$dtEndDate);
                $sWhere .= '{ "range":{ "Date": {"to" : "'.$dtEndDate.'"} } },';
            }

            if ($sSort == '逆序') {
                $data['sort'] =',"sort" : [{"_id": "desc"}]}';
            }

            if ($sSort == '顺序') {
                $data['sort'] =',"sort" : [{"_id": "asc"}]}';
            }

            if ($sPrizeType == '奖金') {
                if ($iMin !== '') {
                    $sWhere .= '{ "range":{ "Prize_amount": {"from" : "'.$iMin.'"}  } },';
                }

                if ($iMax !== '') {
                    $sWhere .= '{ "range":{ "Prize_amount": {"to" : "'.$iMax.'"}  } },';
                }
            }

            if ($sPrizeType == '倍数') {
                if ($iMin !== '') {
                    $sWhere .= '{ "range":{ "Multiple": {"from" : "'.$iMin.'"}  } },';
                }

                if ($iMax !== '') {
                    $sWhere .= '{ "range":{ "Multiple": {"to" : "'.$iMax.'"}  } },';
                }
            }


            if ($sSelectInfoType == '用户名') {
                if ($sIssue !== '') {
                    $sWhere .= '{ "match_phrase_prefix":{ "Username": "'.$sIssue.'" } },';
                }

            }

            if ($sSelectInfoType == '注单') {
                if ($sIssue !== '') {
                    $sWhere .= '{ "match_phrase_prefix":{ "Project": "'.$sIssue.'" } },';
                }

            }

            if (substr($sWhere, -1)==',') {
                $sWhere = substr($sWhere,0,-1);
            }
            $data['where'] = $sWhere;
            $data['page'] = $sIpage;
            $data['limit'] = $iLimit;
            $data['url'] = self::ES_URL."report_pgame_playlist/_search?pretty";

            $sResult = CommonUtils::getCurlFileGetContents($data);
            $oPgamePlayFinalList = AdminLog::getEsData($sResult,$iTotal);
            $res["total"] = $iTotal;
            $res["list"] = $oPgamePlayFinalList;
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        } else {

            $oPgamePlayList = DB::table('report_pgame_playlist');

            if ($sWayType !== '') {
                $oPgamePlayList->where('way_type', $sWayType);
            }

            if ($sLottery !== '') {
                $oPgamePlayList->where('lottery', $sLottery);
            }

            if ($sWay !== '') {
                $oPgamePlayList->where('way', $sWay);
            }

            if ($sPrizeStatus !== '') {
                $oPgamePlayList->where('prize_status', $sPrizeStatus);
            }

            if ($sStatus !== '') {
                $oPgamePlayList->where('status', $sStatus);
            }

            if ($dtBeginDate !== '' && !$dtPeriod) {
                $oPgamePlayList->where('date', '>=', $dtBeginDate);
            }

            if ($dtEndDate !== '' && !$dtPeriod) {
                $oPgamePlayList->where('date', '>=', $dtEndDate);
            }

            if ($sSort == '逆序') {
                $oPgamePlayList->orderBy('id', 'DESC');
            }

            if ($sSort == '顺序') {
                $oPgamePlayList->orderBy('id', 'asc');
            }

            if ($sPrizeType == '奖金') {
                if ($iMin !== '') {
                    $oPgamePlayList->where('prize_amount', '>=', $iMin);
                }

                if ($iMax !== '') {
                    $oPgamePlayList->where('prize_amount', '>=', $iMax);
                }
            }

            if ($sPrizeType == '倍数') {
                if ($iMin !== '') {
                    $oPgamePlayList->where('multiple', '>=', $iMin);
                }

                if ($iMax !== '') {
                    $oPgamePlayList->where('multiple', '>=', $iMax);
                }
            }


            if ($sSelectInfoType == '用户名') {
                if ($sIssue !== '') {
                    $oPgamePlayList->where('username', 'like', '%' . $sIssue . '%');
                }

            }

            if ($sSelectInfoType == '注单') {
                if ($sIssue !== '') {
                    $oPgamePlayList->where('project', 'like', '%' . $sIssue . '%');
                }

            }

            $iLimit = request()->get('limit', 20);
            $oPgamePlayFinalList = $oPgamePlayList->orderby('id', 'desc')->paginate($iLimit);
            $res["total"] = count($oPgamePlayFinalList);
            $res["list"] = $oPgamePlayFinalList->toArray();
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        }
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
        $sWhere = '';
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';

        $sUserName = isset(request()->username) ? request()->username : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $dtPeriod = isset(request()->datePeriod) ? request()->datePeriod : '';
        $sSearchType = isset(request()->search_type) ? request()->search_type : '';
        $res = [];
        
        if ($sSearchType == 'ES') {
            if ($sMerchantName !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Merchant_name": "*'.$sMerchantName.'*" } },';
            }
            if ($sUserName !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Username": "*'.$sUserName.'*" } },';
            }
            if ($dtBeginDate != '' && !$dtPeriod) {
                $dtBeginDate = str_replace(' ','T',$dtBeginDate);
                $sWhere .= '{ "range":{ "Date": {"from" : "'.$dtBeginDate.'"}  } },';
            }
            if ($dtEndDate != '' && !$dtPeriod) {
                $dtEndDate = str_replace(' ','T',$dtEndDate);
                $sWhere .= '{ "range":{ "Date": {"to" : "'.$dtEndDate.'"} } },';
            }
            if ($dtPeriod != '') {
                $aTmp = DateUtils::getDateArray($dtPeriod);
                $dtBeginDate = str_replace(' ','T',date('Y-m-d H:i:s',$aTmp['begin_date']));
                $dtEndDate = str_replace(' ','T',date('Y-m-d H:i:s',$aTmp['end_date']));
                $sWhere .= '{ "range":{ "Date": {"from" : "'.$dtBeginDate.'"}  } },';
                $sWhere .= '{ "range":{ "Date": {"to" : "'.$dtEndDate.'"} } },';

            }
            if (substr($sWhere, -1)==',') {
                $sWhere = substr($sWhere,0,-1);
            }
            $data['where'] = $sWhere;
            $data['page'] = $sIpage;
            $data['limit'] = $iLimit;
            $data['url'] = self::ES_URL."report_platform/_search?pretty";
            $sResult = CommonUtils::getCurlFileGetContents($data);
            $oPreportProfitFinalList = AdminLog::getEsData($sResult,$iTotal);
            $res["total"] = $iTotal;
            $res["list"] = $oPreportProfitFinalList;
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        } else {
            $oPreportProfitList = DB::table('report_platform');
            if ($sMerchantName !== '') {
                $oPreportProfitList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
            }
            if ($sUserName !== '') {
                $oPreportProfitList->where('username', 'like', '%' . $sUserName . '%');
            }
            if ($dtBeginDate !== '' && !$dtPeriod) {
                $oPreportProfitList->where('date', '>=', $dtBeginDate);
            }
            if ($dtEndDate !== '' && !$dtPeriod) {
                $oPreportProfitList->where('date', '<=', $dtEndDate);
            }
            if ($dtPeriod != '') {
                $aTmp = DateUtils::getDateArray($dtPeriod);
                $oPreportProfitList->where('date', '>=', date('Y-m-d 00:00:00',$aTmp['begin_date']));
                $oPreportProfitList->where('date', '<=', date('Y-m-d 23:59:59',$aTmp['end_date']));
            }
            $iLimit = request()->get('limit', 20);
            $oPreportProfitFinalList = $oPreportProfitList->orderby('id', 'desc')->paginate($iLimit);
            $res["total"] = count($oPreportProfitFinalList);
            $res["list"] = $oPreportProfitFinalList->toArray();
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        }

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
        $sWhere = '';
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $dtPeriod = isset(request()->datePeriod) ? request()->datePeriod : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $sModel = isset(request()->model) ? request()->model : '';
        $sPlatform = isset(request()->platform) ? request()->platform : '';
        $sUserName = isset(request()->username) ? request()->username : '';
        $sSearchType = isset(request()->search_type) ? request()->search_type : '';
       
        if ($sSearchType == 'ES') {
            if ($sMerchantName !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Merchant_name": "*'.$sMerchantName.'*" } },';
            }
            if ($sUserName !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Username": "*'.$sUserName.'*" } },';
            }
            if ($sModel !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Model": "*'.$sModel.'*" } },';
            }
            if ($sPlatform !== '') {
                $sWhere .= '{ "match_phrase_prefix":{ "Platform": "*'.$sPlatform.'*" } },';
            }
            if ($dtBeginDate != '' && !$dtPeriod) {
                $dtBeginDate = str_replace(' ','T',$dtBeginDate);
                $sWhere .= '{ "range":{ "Date": {"from" : "'.$dtBeginDate.'"}  } },';
            }
            if ($dtEndDate != '' && !$dtPeriod) {
                $dtEndDate = str_replace(' ','T',$dtEndDate);
                $sWhere .= '{ "range":{ "Date": {"to" : "'.$dtEndDate.'"} } },';
            }
            if ($dtPeriod != '') {
                $aTmp = DateUtils::getDateArray($dtPeriod);
                $dtBeginDate = str_replace(' ','T',date('Y-m-d H:i:s',$aTmp['begin_date']));
                $dtEndDate = str_replace(' ','T',date('Y-m-d H:i:s',$aTmp['end_date']));
                $sWhere .= '{ "range":{ "Date": {"from" : "'.$dtBeginDate.'"}  } },';
                $sWhere .= '{ "range":{ "Date": {"to" : "'.$dtEndDate.'"} } },';

            }
            if (substr($sWhere, -1)==',') {
                $sWhere = substr($sWhere,0,-1);
            }
            $data['where'] = $sWhere;
            $data['page'] = $sIpage;
            $data['limit'] = $iLimit;
            $data['url'] = self::ES_URL."report_user/_search?pretty";
            $sResult = CommonUtils::getCurlFileGetContents($data);
            $oUserReportFinalList = AdminLog::getEsData($sResult,$iTotal);
            $res["total"] = $iTotal;
            $res["list"] = $oUserReportFinalList;
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        } else {
            $oUserReportList = DB::table('report_user');
            if ($sMerchantName !== '') {
                $oUserReportList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
            }
            if ($dtBeginDate !== '' && !$dtPeriod) {
                $oUserReportList->where('date', '>=', $dtBeginDate);
            }
            if ($dtEndDate !== '' && !$dtPeriod) {
                $oUserReportList->where('date', '<=', $dtEndDate);
            }
            if ($sModel !== '') {
                $oUserReportList->where('model', 'like', '%' . $sModel . '%');
            }
            if ($sPlatform !== '') {
                $oUserReportList->where('platform', 'like', '%' . $sPlatform . '%');
            }
            if ($sUserName !== '') {
                $oUserReportList->where('username', 'like', '%' . $sUserName . '%');
            }
            if ($dtPeriod != '') {
                $aTmp = DateUtils::getDateArray($dtPeriod);
                $oUserReportList->where('date', '>=', date('Y-m-d 00:00:00',$aTmp['begin_date']));
                $oUserReportList->where('date', '<=', date('Y-m-d 23:59:59',$aTmp['end_date']));

            }

            $iLimit = request()->get('limit', 20);
            $oUserReportFinalList = $oUserReportList->orderby('id', 'desc')->paginate($iLimit);
            $res = [];
            $res["total"] = count($oUserReportFinalList);
            $res["list"] = $oUserReportFinalList->toArray();
            $aFinal['message'] = 'success';
            $aFinal['code'] = 0;
            $aFinal['data'] = $res;
        }

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