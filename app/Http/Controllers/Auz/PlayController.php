<?php

namespace App\Http\Controllers;

use App\common\utils\CommonUtils;
use Illuminate\Http\Request;
use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\AdminLog;
use App\model\Game;
use App\model\GameRisk;
use App\model\BettingLimit;
use App\model\Common;

class PlayController extends Controller
{
    /**
     * 投注限额列表
     * @param request
     * @return json
     */

    public function betlimitList()
    {
        config(['database.connections.mysql.strict' =>  false]);
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iStatus = isset(request()->status) ? request()->status : '';
        $sName = isset(request()->name) ? request()->name : '';
        $sLotteryName = isset(request()->lottery_name) ? request()->lottery_name : '';
        $sTable = isset(request()->table) ? request()->table : '';
        $oBetlimitList = DB::table('game_limit');
        if ($iStatus !== '') {
            $oBetlimitList->where('status', $iStatus);
        }
        if ($sName !== '') {
            $oBetlimitList->where('name', 'like', '%' . $sName . '%');
        }
        $oBetlimitList->orderby('id','desc');
        if($sLotteryName){
            $oBetlimitList->where('lottery_name',$sLotteryName);
        }
        if($sTable){
            $oBetlimitList->where('name',$sTable);
            $oBetlimitFinalList = $oBetlimitList->orderby('id', 'desc')->get();
        }else{
            $oBetlimitList->groupBy('name');
            $iLimit = request()->get('limit', 20);
            $oBetlimitFinalList = $oBetlimitList->orderby('id', 'desc')->paginate($iLimit);
        }
        // $oBetlimitListCount = $oBetlimitList->get();
        // $oBetlimitFinalList = $oBetlimitList->skip(($sIpage - 1) * $iLimit)->take($iLimit)->get();
        $res = [];

       
        
        $res["total"] = count($oBetlimitFinalList);
        $res["list"] = $oBetlimitFinalList;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'betlimitList';
        $sLogContent = 'betlimitList';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    /**
     * 获取页面下拉框数据
     * @param request
     * @return json
     */
    public function betlimitOptions()
    {
        config(['database.connections.mysql.strict' =>  false]);
        $name = isset(request()->name) ? request()->name : '';
        $betlimitOptions = DB::table('game_limit')->select('lottery_name')->where('name',$name);
        $data = $betlimitOptions->groupBy('lottery_name')->get()->toArray();
        $res = [];
        $res["total"] = count($data);
        $res["list"] = $data;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'betlimitOptions';
        $sLogContent = 'betlimitOptions';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }



     /**
     * 列表页修改名称
     * @param request
     * @return json
     */
    public function betlimitNameSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $sName = isset($data['name']) ? $data['name'] : '';
        try
        {
            if($this->validate(request(),Common::$betlimitSaveRules,Common::$betlimitSaveMessages)) {
                $oBettingLimit = BettingLimit::find($iId);
                $oBettingLimit->name = $sName;
                $iRet = $oBettingLimit->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oBettingLimit;
                $sOperateName = 'betlimitNameSave';
                $sLogContent = 'betlimitNameSave';
                $dt = now();
                AdminLog::adminLogSave($sOperateName);
                return response()->json($aFinal);
            }
        }
        catch (\Exception $e) {
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
            return response()->json($aFinal);
        }
    }

    /**
     * 单一用户数据修改保存
     * @param request
     * @return json
     */
    public function betlimitPrizeSave($iId = null)
    {

        $data = request()->post();

        
        if(count($data)==count($data,1)){
            try
            {
                if($this->validate(request(),Common::$betlimitSaveRules,Common::$betlimitSaveMessages)) {
                    $iId = isset($data['id']) ? $data['id'] : '';
                    $sPrizeLimit = isset($data['prize_limit']) ? $data['prize_limit'] : '';
                    $oBettingLimit = BettingLimit::find($iId);
                    $oBettingLimit->prize_limit = $sPrizeLimit;
                    $iRet = $oBettingLimit->save();

                }
            }
            catch (\Exception $e) {
                $aFinal['message'] = '非法数据请求';
                $aFinal['code'] = 0;
                $aFinal['data'] = '';
                return response()->json($aFinal);
            }
        }else{
            foreach ($data as $k => $v) {
                $iId = isset($v['id']) ? $v['id'] : '';
                $sPrizeLimit = isset($v['prize_limit']) ? $v['prize_limit'] : '';
                $oBettingLimit = BettingLimit::find($iId);
                $oBettingLimit->prize_limit = $sPrizeLimit;
                $iRet = $oBettingLimit->save();
            }
        }
        $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
        $aFinal['code'] = 1;
        $aFinal['data'] = $oBettingLimit;
        $sOperateName = 'betlimitPrizeSave';
        $sLogContent = 'betlimitPrizeSave';
        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        

    }


    /**
     * 投注限额列表数据删除
     * @param request
     * @return json
     */
    public function betlimitDelete()
    {
        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';

        $oIpBlack = BettingLimit::where('id',$iId)->delete();
        if ($oIpBlack) {
            $sMessage = '删除成功！';
        } else {
            $sMessage = '删除失败！';
        }

        $aFinal['message'] = $sMessage;
        $aFinal['code'] = 0;
        $aFinal['data'] = $oIpBlack;

        $sOperateName = 'betlimitDelete';
        $sLogContent = 'betlimitDelete';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 游戏风控数据列表
     * @param request
     * @return json
     */
    public function lotteryriskList()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $oLotteryriskList = DB::table('game_risk');

        $oLotteryriskList->orderby('id', 'desc');
        if ($iStatus !== '') {
            $oLotteryriskList->where('status', $iStatus);
        }
        if ($sMerchantName !== '') {
            $oLotteryriskList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        $oLotteryriskListCount = $oLotteryriskList->get();
        $oLotteryriskFinalList = $oLotteryriskList->skip(($sIpage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        $res = [];
        $res["total"] = count($oLotteryriskListCount);
        $res["list"] = $oLotteryriskFinalList;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'lotteryriskList';
        $sLogContent = 'lotteryriskList';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * 游戏风控列表数据状态修改
     * @param request
     * @return json
     */
    public function lotteryriskStatusSave($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        try
        {

            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {
                $oGameRisk = GameRisk::find($iId);
                $oGameRisk->status = $iFlag;
                $iRet = $oGameRisk->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oGameRisk;

                $sOperateName = 'usersafetyStatusSave';
                $sLogContent = 'usersafetyStatusSave';

                $dt = now();

                AdminLog::adminLogSave($sOperateName);
                return response()->json($aFinal);
            }
        }
        catch (\Exception $e) {
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
            return response()->json($aFinal);
        }
    }


    /**
     * 游戏风控列表数据修改和添加
     * @param request
     * @return json
     */
    public function lotteryriskSave()
    {
        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iGameId = isset($data['game_id']) ? $data['game_id'] : '';
        $sGame = isset($data['game']) ? $data['game'] : '';
        $sDate = isset($data['date']) ? $data['date'] : '';
        $sIssue = isset($data['issue']) ? $data['issue'] : '';
        $iProjectPeopleCount = isset($data['project_people_count']) ? $data['project_people_count'] : '';
        $iWinnerPeopleCount = isset($data['winner_people_count']) ? $data['winner_people_count'] : '';
        $iWinnerPeopleCountRatio = isset($data['winner_people_count_ratio']) ? $data['winner_people_count_ratio'] : '';
        $iProjectCount = isset($data['project_count']) ? $data['project_count'] : '';
        $iWinnerProjectCount = isset($data['winner_project_count']) ? $data['winner_project_count'] : '';
        $iWinnerProjectCountRatio = isset($data['winner_project_count_ratio']) ? $data['winner_project_count_ratio'] : '';
        $sProjectAmount = isset($data['project_amount']) ? $data['project_amount'] : '';
        $sBackAwardAmount = isset($data['back_award_amount']) ? $data['back_award_amount'] : '';
        $sLossRatio = isset($data['loss_ratio']) ? $data['loss_ratio'] : '';
        $sStatus = isset($data['status']) ? $data['status'] : '';

        if ($iId != '') {
            $oGameRisk = GameRisk::find($iId);
            $oGameRisk->updated_at = date("Y-m-d H:i:s",time());
        }else{
            $oGameRisk = new GameRisk();
        }

        $oGameRisk->game_id = $iGameId;
        $oGameRisk->game = $sGame;
        $oGameRisk->date = $sDate;
        $oGameRisk->issue = $sIssue;
        $oGameRisk->project_people_count = $iProjectPeopleCount;
        $oGameRisk->winner_people_count = $iWinnerPeopleCount;
        $oGameRisk->winner_people_count_ratio = $iWinnerPeopleCountRatio;
        $oGameRisk->project_count = $iProjectCount;
        $oGameRisk->winner_project_count = $iWinnerProjectCount;
        $oGameRisk->winner_project_count_ratio = $iWinnerProjectCountRatio;
        $oGameRisk->project_amount = $sProjectAmount;
        $oGameRisk->back_award_amount = $sBackAwardAmount;
        $oGameRisk->loss_ratio = $sLossRatio;
        $oGameRisk->status = $sStatus;

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oGameRisk;

        $sOperateName = 'lotteryriskSave';
        $sLogContent = 'lotteryriskSave';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 游戏风控列表数据删除
     * @param request
     * @return json
     */
    public function lotteryriskDelete()
    {
        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';

        $oIpBlack = GameRisk::where('id',$iId)->delete();
        if ($oIpBlack) {
            $sMessage = '删除成功！';
        } else {
            $sMessage = '删除失败！';
        }

        $aFinal['message'] = $sMessage;
        $aFinal['code'] = 0;
        $aFinal['data'] = $oIpBlack;

        $sOperateName = 'lotteryriskDelete';
        $sLogContent = 'lotteryriskDelete';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }



    /**
     * 彩票游戏列表
     * @param request
     * @return json
     */
    public function pgameList()
    {
        config(['database.connections.mysql.strict' =>  false]);
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $sLotteryName = isset(request()->lottery_name) ? request()->lottery_name : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sWayType = isset(request()->way_type) ? request()->way_type : '';
        $sAct = isset(request()->act) ? request()->act : '';
        $oPgameList = DB::table('game');

        if(empty($sAct)){
            $oPgameList->groupBy('lottery_name');
        }
        if ($sLotteryName !== '') {
            $oPgameList->where('lottery_name', $sLotteryName);
        }
        if ($sWayType !== '') {
            $oPgameList->where('way_type', $sWayType);
        }
        if ($sMerchantName !== '') {
            $oPgameList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        //判断是否只查询子数据
        if(empty($sAct)){
            $oPgameFinalList = request()->get('limit/d', 20);
            $oPgameFinalList = $oPgameList->orderby('id', 'asc')->paginate($iLimit);
            foreach ($oPgameFinalList as $k => &$v) {
                $aTemp = DB::table('game')->select()->where('lottery_name',$v->lottery_name)->get()->toArray();
                
                foreach ($aTemp as $key => &$val) {
                    $val->properties = [];
                    if($val->is_hot){
                        $val->properties[] = $val->is_hot;
                    }
                    if($val->is_recommand){
                        $val->properties[] = $val->is_recommand;
                    }
                    if($val->is_new){
                        $val->properties[] = $val->is_new;
                    }
                }

                $v->children = $aTemp;


            }
        }else{
            $oPgameFinalList = $oPgameList->orderby('id', 'asc')->get();

            foreach ($oPgameFinalList as $key => &$val) {
                $val->properties = [];
                if($val->is_hot){
                    $val->properties[] = $val->is_hot;
                }
                if($val->is_recommand){
                    $val->properties[] = $val->is_recommand;
                }
                if($val->is_new){
                    $val->properties[] = $val->is_new;
                }
            }


        }
        $aTmp = [];
        $aFinal = [];
        $res = [];
        $res["list"] = $oPgameFinalList;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'pgameList';
        $sLogContent = 'pgameList';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * 获取玩法列表
     * @param request
     * @return json
     */
    public function pgameSearchList()
    {
        config(['database.connections.mysql.strict' =>  false]);
        $pgameWayTypeList = DB::table('game')->select()->groupBy('way_type')->get()->toArray();
        $pgameLotteryNameList = DB::table('game')->select()->groupBy('lottery_name')->get()->toArray();
        $aFinal = [];
        $res = [];
        $res["wayTypeList"] = $pgameWayTypeList;
        $res["lotteryNameList"] = $pgameLotteryNameList;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'pgameSearchList';
        $sLogContent = 'pgameSearchList';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


    /**
     * 列表页新增数据
     * @param request
     * @return json
     */
    public function pgameSave()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $sKillRate = isset($data['kill_rate']) ? $data['kill_rate'] : '';
        $iLotteryId = isset($data['lottery_id']) ? $data['lottery_id'] : '';
        $sLotteryName = isset($data['lottery_name']) ? $data['lottery_name'] : '';
        $sType = isset($data['type']) ? $data['type'] : '';
        $sPeriodDay = isset($data['period_day']) ? $data['period_day'] : '';
        $sPeriodSecond = isset($data['period_second']) ? $data['period_second'] : '';
        $sSalesBegin = isset($data['sales_begin']) ? $data['sales_begin'] : '';
        $sSalesEnd = isset($data['sales_end']) ? $data['sales_end'] : '';
        $sSequence = isset($data['sequence']) ? $data['sequence'] : '';
        $sStatus = isset($data['status']) ? $data['status'] : '';
        $oGame = new Game;
        $oGame->kill_rate = $sKillRate;
        $oGame->lottery_id = $iLotteryId;
        $oGame->lottery_name = $sLotteryName;
        $oGame->type = $sType;
        $oGame->period_day = $sPeriodDay;
        $oGame->period_second = $sPeriodSecond;
        $oGame->sales_begin = date('Y-m-d',time());
        $oGame->sales_end = date('Y-m-d',time());
        $oGame->sequence = $sSequence;
        $iRet = $oGame->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oGame;

        $sOperateName = 'pgameKillRateSave';
        $sLogContent = 'pgameKillRateSave';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


    /**
     * 彩票游戏属性数据保存
     * @param request
     * @return json
     */
    public function pgamePropertySave()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $sPropertyName = isset($data['property_name']) ? $data['property_name'] : '';
        $sPropertyValue = isset($data['property_value']) ? $data['property_value'] : '';

        $aProperty = ['热门'=>'is_hot','推荐'=>'is_recommand','新上'=>'is_new'];
        $sPropertyField = $aProperty[$sPropertyName];
        try
        {
            if($this->validate(request(),Common::$newPropertySaveRules,Common::$newPropertySaveMessages)) {
                $oGame = Game::find($iId);
                if ($sPropertyValue) {
                    $oGame->$sPropertyField='';
                }else{
                    $oGame->$sPropertyField=$sPropertyName;
                }
                $iRet = $oGame->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oGame;
                $sOperateName = 'updatePgamePropertySave';
                $sLogContent = 'updatePgamePropertySave';
                $dt = now();
                AdminLog::adminLogSave($sOperateName);
                return response()->json($aFinal);
            }
        }
        catch (\Exception $e) {
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
            return response()->json($aFinal);
        }
    }


     /**
     * 列表页修改排序
     * @param request
     * @return json
     */
    public function pgameSequenceSave()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oGame = Game::find($iId);
                $oGame->sequence = $iFlag;
                $iRet = $oGame->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oGame;
                $sOperateName = 'pgameSequenceSave';
                $sLogContent = 'pgameSequenceSave';
                $dt = now();
                AdminLog::adminLogSave($sOperateName);
                return response()->json($aFinal);
            }
        }
        catch (\Exception $e) {
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
            return response()->json($aFinal);
        }
    }

    /**
     * 列表页修改游戏杀率
     * @param request
     * @return json
     */
    public function pgameKillRateSave($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['kill_rate']) ? $data['kill_rate'] : '';
        $oGame = Game::find($iId);
        $oGame->kill_rate = $iFlag;
        $iRet = $oGame->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oGame;

        $sOperateName = 'pgameKillRateSave';
        $sLogContent = 'pgameKillRateSave';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }

    

    /**
     * 列表页修改状态
     * @param request
     * @return json
     */
    public function pgameStatusSave($iId = null)
    {

        $data = request()->post();
        
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        try
        {

            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {
                $oGame = Game::find($iId);
                $oGame->status = $iFlag;
                $iRet = $oGame->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oGame;

                $sOperateName = 'pgameStatusSave';
                $sLogContent = 'pgameStatusSave';

                $dt = now();

                AdminLog::adminLogSave($sOperateName);
                return response()->json($aFinal);

            }
        }
        catch (\Exception $e) {
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
            return response()->json($aFinal);
        }

    }


    /**
     * 三方游戏列表
     * @param request
     * @return json
     */
    public function proxygamesList()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $oLotteryriskList = DB::table('game_risk');

        $oLotteryriskList->orderby('id', 'desc');
        if ($iStatus !== '') {
            $oLotteryriskList->where('status', $iStatus);
        }
        if ($sMerchantName !== '') {
            $oLotteryriskList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        $oLotteryriskListCount = $oLotteryriskList->get();
        $oLotteryriskFinalList = $oLotteryriskList->skip(($sIpage - 1) * $iLimit)->take($iLimit)->get();
        
        $aTmp = [];
        $aFinal = [];
        $res = [];
        $res["total"] = count($oLotteryriskListCount);
        $res["list"] = $oLotteryriskFinalList;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'proxygamesList';
        $sLogContent = 'proxygamesList';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    

}