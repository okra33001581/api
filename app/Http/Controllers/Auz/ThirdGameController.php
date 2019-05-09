<?php

namespace App\Http\Controllers;

use App\common\utils\CommonUtils;
use App\model\MerchantsDomains;
use App\model\MerchantsIp;
use App\model\TransactionTypes;
use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\AdminLog;

use App\model\ThirdBall;
use App\model\ThirdGameTypes;
use App\model\ThirdMerchantGame;
use App\model\ThirdPlats;
use App\model\ThirdGameTypesDetail;
use App\model\ThirdGameSet;
use App\model\Common;

/**
 * Class Event - 公告相关控制器
 * @author zebra
 */
class ThirdGameController extends Controller
{

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function agLogList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $is_parse = isset(request()->is_parse) ? request()->is_parse : '';

        $oAuthAdminList = DB::table('third_ag_ftp_get_logs');


        if ($is_parse !== '') {
            $oAuthAdminList->where('is_parse', $is_parse);
        }

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'marqueeList';
        $sLogContent = 'marqueeList';

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
    public function agUserTurnoverList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $sUserName = isset(request()->username) ? request()->username : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $oAuthAdminList = DB::table('third_ag_project_record');


        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'marqueeList';
        $sLogContent = 'marqueeList';

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
    public function basketballList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $type = isset(request()->type) ? request()->type : '';
        $district = isset(request()->district) ? request()->district : '';
        $nationality = isset(request()->nationality) ? request()->nationality : '';
        $name = isset(request()->name) ? request()->name : '';
        $status = isset(request()->status) ? request()->status : '';

        $oAuthAdminList = DB::table('third_ball');


        if ($type !== '') {
            $oAuthAdminList->where('type', 'like', $type);
        }

        if ($district !== '') {
            $oAuthAdminList->where('district', 'like', '%' . $district . '%');
        }

        if ($nationality !== '') {
            $oAuthAdminList->where('nationality', 'like', '%' . $nationality . '%');
        }
        if ($name !== '') {
            $oAuthAdminList->where('name', 'like', '%' . $name . '%');
        }


        if ($status !== '') {
            $oAuthAdminList->where('status', 'like', '%' . $status . '%');
        }

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'marqueeList';
        $sLogContent = 'marqueeList';

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
    public function footballList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';


        $type = isset(request()->type) ? request()->type : '';
        $district = isset(request()->district) ? request()->district : '';
        $nationality = isset(request()->nationality) ? request()->nationality : '';
        $name = isset(request()->name) ? request()->name : '';
        $status = isset(request()->status) ? request()->status : '';

        $oAuthAdminList = DB::table('third_ball');


        if ($type !== '') {
            $oAuthAdminList->where('type', 'like', $type);
        }

        if ($district !== '') {
            $oAuthAdminList->where('district', 'like', '%' . $district . '%');
        }

        if ($nationality !== '') {
            $oAuthAdminList->where('nationality', 'like', '%' . $nationality . '%');
        }
        if ($name !== '') {
            $oAuthAdminList->where('name', 'like', '%' . $name . '%');
        }


        if ($status !== '') {
            $oAuthAdminList->where('status', 'like', '%' . $status . '%');
        }


        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'marqueeList';
        $sLogContent = 'marqueeList';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * GA流水列表
     * @param request
     * @return json
     */
    public function gaUserTurnoverList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $sUserName = isset(request()->username) ? request()->username : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $gaUserTurnoverList = DB::table('third_user_ga_turnovers as tugt');
        $gaUserTurnoverList->select('tugt.*','tgs.ext_column1','tgs.ext_column2','tgs.ext_column3','tgs.ext_column4','tgs.ext_column5','tgs.ext_column6','tgs.ext_column7','tgs.ext_column8','tgs.ext_column9','tgs.ext_column10','tgs.ext_column11','tgs.ext_column12','tgs.ext_column13','tgs.ext_column14','tgs.ext_column15','tgs.ext_column16','tgs.ext_column17','tgs.ext_column18','tgs.ext_column19','tgs.ext_column20');
        $gaUserTurnoverList->leftJoin('third_game_set as tgs', 'tugt.set_id', '=', 'tgs.id');
        if ($sMerchantName !== '') {
            $gaUserTurnoverList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sUserName !== '') {
            $gaUserTurnoverList->where('username', 'like', '%' . $sUserName . '%');
        }

        $iLimit = request()->get('limit', 20);
        $gaUserTurnoverFinalList = $gaUserTurnoverList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($gaUserTurnoverFinalList);
        $res["list"] = $gaUserTurnoverFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'marqueeList';
        $sLogContent = 'marqueeList';

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
    public function gameTypeList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $iStatus = isset(request()->status) ? request()->status : '';
        $type = isset(request()->type) ? request()->type : '';
        $name = isset(request()->name) ? request()->name : '';

        $oAuthAdminList = DB::table('third_game_types');

        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($type !== '') {
            $oAuthAdminList->where('type', 'like', '%' . $type . '%');
        }

        if ($name !== '') {
            $oAuthAdminList->where('name', 'like', '%' . $name . '%');
        }

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'marqueeList';
        $sLogContent = 'marqueeList';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function objectToArray($e){
        $e=(array)$e;
        foreach($e as $k=>$v){
            if( gettype($v)=='resource' ) return;
            if( gettype($v)=='object' || gettype($v)=='array' )
                $e[$k]=(array)$this->objectToArray($v);
        }
        return $e;
    }


    /**
     * 游戏类别明细管理
     * @param request
     * @return json
     */
    public function gameTypeDetailList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';

        $iStatus = isset(request()->status) ? request()->status : '';
        $sName = isset(request()->name) ? request()->name : '';
        $sPlatNamee = isset(request()->plat_name) ? request()->plat_name : '';


        $gameTypeDetailList = DB::table('third_game_types_detail as tgtd');
        $gameTypeDetailList->select('tgtd.id','tgtd.plat_id','tgtd.plat_name','tgtd.name','tgtd.icon','tgtd.desc','tgtd.status','tgtd.set_id','tgtd.ext_field1','tgtd.ext_field2','tgtd.ext_field3','tgtd.ext_field4','tgtd.ext_field5','tgtd.ext_field6','tgtd.ext_field7','tgtd.ext_field8','tgtd.ext_field9','tgtd.ext_field10','tgtd.ext_field11','tgtd.ext_field12','tgtd.ext_field13','tgtd.ext_field14','tgtd.ext_field15','tgtd.ext_field16','tgtd.ext_field17','tgtd.ext_field18','tgtd.ext_field19','tgtd.ext_field20','tgs.ext_column1','tgs.ext_column2','tgs.ext_column3','tgs.ext_column4','tgs.ext_column5','tgs.ext_column6','tgs.ext_column7','tgs.ext_column8','tgs.ext_column9','tgs.ext_column10','tgs.ext_column11','tgs.ext_column12','tgs.ext_column13','tgs.ext_column14','tgs.ext_column15','tgs.ext_column16','tgs.ext_column17','tgs.ext_column18','tgs.ext_column19','tgs.ext_column20');
        $gameTypeDetailList->leftJoin('third_game_set as tgs', 'tgtd.set_id', '=', 'tgs.id');
        // $sql = "select tgtd.id,tgtd.plat_id,tgtd.plat_name,tgtd.name,tgtd.icon,tgtd.desc,tgtd.status,tgtd.set_id,tgtd.ext_field1,tgtd.ext_field2,tgtd.ext_field3,tgtd.ext_field4,tgtd.ext_field5,tgtd.ext_field6,tgtd.ext_field7,tgtd.ext_field8,tgtd.ext_field9,tgtd.ext_field10,tgtd.ext_field11,tgtd.ext_field12,tgtd.ext_field13,tgtd.ext_field14,tgtd.ext_field15,tgtd.ext_field16,tgtd.ext_field17,tgtd.ext_field18,tgtd.ext_field19,tgtd.ext_field20,tgs.ext_column1,tgs.ext_column2,tgs.ext_column3,tgs.ext_column4,tgs.ext_column5,tgs.ext_column6,tgs.ext_column7,tgs.ext_column8,tgs.ext_column9,tgs.ext_column10,tgs.ext_column11,tgs.ext_column12,tgs.ext_column13,tgs.ext_column14,tgs.ext_column15,tgs.ext_column16,tgs.ext_column17,tgs.ext_column18,tgs.ext_column19,tgs.ext_column20 from third_game_types_detail as a left join third_game_set as b on tgtd.set_id=tgs.id limit 10,10";
          // $gameTypeDetailList = DB::select($sql);
        // $gameTypeDetailFinalList = $this->objectToArray($gameTypeDetailList);



        if ($iStatus !== '') {
            $gameTypeDetailList->where('tgtd.status', $iStatus);
        }
        if ($sName !== '') {
            $gameTypeDetailList->where('tgtd.name', 'like', '%' . $sName . '%');
        }
        if ($sPlatNamee !== '') {
            $gameTypeDetailList->where('tgtd.plat_name', 'like', '%' . $sPlatNamee . '%');
        }

        $iLimit = request()->get('limit', 20);
        $gameTypeDetailFinalList = $gameTypeDetailList->orderby('tgtd.id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($gameTypeDetailFinalList);
        $res["list"] = $gameTypeDetailFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
        $sOperateName = 'marqueeList';
        $sLogContent = 'marqueeList';
        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * 游戏类别设置
     * @param request
     * @return json
     */
    public function gameTypeSetList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $is_parse = isset(request()->is_parse) ? request()->is_parse : '';
        $oAuthAdminList = DB::table('third_game_set');
        if ($is_parse !== '') {
            $oAuthAdminList->where('is_parse', $is_parse);
        }
        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
        $sOperateName = 'marqueeList';
        $sLogContent = 'marqueeList';
        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }
    /**
     * 游戏盈亏
     * @param request
     * @return json
     */
    public function gameProfitList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $is_parse = isset(request()->is_parse) ? request()->is_parse : '';
        $oAuthAdminList = DB::table('third_profits');
        if ($is_parse !== '') {
            $oAuthAdminList->where('is_parse', $is_parse);
        }
        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
        $sOperateName = 'marqueeList';
        $sLogContent = 'marqueeList';
        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * 商户游戏管理列表
     * @param request
     * @return json
     */
    public function merchantGameList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sType = isset(request()->type) ? request()->type : '';
        $sPlatName = isset(request()->plat_name) ? request()->plat_name : '';
        $sSubGameName = isset(request()->sub_game_name) ? request()->sub_game_name : '';


        $oAuthAdminList = DB::table('third_merchant_game');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sType !== '') {
            $oAuthAdminList->where('type', 'like', '%' . $sType . '%');
        }

        if ($sPlatName !== '') {
            $oAuthAdminList->where('plat_name', 'like', '%' . $sPlatName . '%');
        }

        if ($sSubGameName !== '') {
            $oAuthAdminList->where('sub_game_name', 'like', '%' . $sSubGameName . '%');
        }


        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'merchantGameList';
        $sLogContent = 'merchantGameList';

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
    public function thirdPlatList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $iStatus = isset(request()->status) ? request()->status : '';
        $name = isset(request()->name) ? request()->name : '';

        $oAuthAdminList = DB::table('third_plats');

        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($name !== '') {
            $oAuthAdminList->where('name', 'like', '%' . $name . '%');
        }

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'marqueeList';
        $sLogContent = 'marqueeList';

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
    public function thirdBallSequence($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oEvent = ThirdBall::find($iId);
                $oEvent->sequence = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'marqueeSequence';
                $sLogContent = 'marqueeSequence';
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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdGameTypesSequence()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oEvent = ThirdGameTypes::find($iId);
                $oEvent->sequence = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'marqueeSequence';
                $sLogContent = 'marqueeSequence';
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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdMerchantGameSequence()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oEvent = ThirdMerchantGame::find($iId);
                $oEvent->sequence = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'marqueeSequence';
                $sLogContent = 'marqueeSequence';
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
     * 商户游戏管理列表修改排序值
     * @param request
     * @return json
     */
    public function thirdMerchantGameFee()
    {

        $data = request()->post();
        try
        {
            if($this->validate(request(),Common::$feeSaveRules,Common::$feeSaveMessages)) {
                $oMerchantGame = new ThirdMerchantGame;
                $aData = $oMerchantGame->thirdMerchantGameFee($data);
                if ($aData) {
                    // commit trans
                } else {
                    // rollback trans
                }
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $aData;
                $sOperateName = 'thirdMerchantGameFee';
                $sLogContent = 'thirdMerchantGameFee';
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
     * 商户游戏管理列表修改排序值
     * @param request
     * @return json
     */
    public function thirdMerchantGameSubFee()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sub_fee']) ? $data['sub_fee'] : '';
        try
        {
            if($this->validate(request(),Common::$feeSaveRules,Common::$feeSaveMessages)) {
                $oMerchantGame = ThirdMerchantGame::find($iId);
                $oMerchantGame->sub_fee = $iFlag;
                $iRet = $oMerchantGame->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oMerchantGame;
                $sOperateName = 'thirdMerchantGameSubFee';
                $sLogContent = 'thirdMerchantGameSubFee';
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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdPlatsSequence()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oEvent = ThirdPlats::find($iId);
                $oEvent->sequence = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'marqueeSequence';
                $sLogContent = 'marqueeSequence';
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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdBallStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';

        try
        {
            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {

                $oEvent = ThirdBall::find($iId);
                if (is_object($oEvent)) {
                    $iStatue = $oEvent->status;
                }
                $oEvent->status = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'payGroupStatusSave';
                $sLogContent = 'payGroupStatusSave';

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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdGameTypesStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        try
        {

            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {
                $oEvent = ThirdGameTypes::find($iId);
                if (is_object($oEvent)) {
                    $iStatue = $oEvent->status;
                }
                $oEvent->status = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;

                $sOperateName = 'payGroupStatusSave';
                $sLogContent = 'payGroupStatusSave';

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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdGameTypesSubStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        try
        {

            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {
                $oEvent = ThirdGameTypesDetail::find($iId);
                if (is_object($oEvent)) {
                    $iStatue = $oEvent->status;
                }
                $oEvent->status = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;

                $sOperateName = 'payGroupStatusSave';
                $sLogContent = 'payGroupStatusSave';

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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdMerchantGameStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';


        try
        {

            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {
                $oEvent = ThirdMerchantGame::find($iId);
                if (is_object($oEvent)) {
                    $iStatue = $oEvent->status;
                }
                $oEvent->status = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'thirdMerchantGameStatusSave';
                $sLogContent = 'thirdMerchantGameStatusSave';
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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdPlatsStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';

        try
        {

            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {
                $oEvent = ThirdPlats::find($iId);
                if (is_object($oEvent)) {
                    $iStatue = $oEvent->status;
                }
                $oEvent->status = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;

                $sOperateName = 'payGroupStatusSave';
                $sLogContent = 'payGroupStatusSave';

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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdBallSave()
    {
        $data = request()->post();

        $id=isset($data['id'])?$data['id']:'';
        $type=isset($data['type'])?$data['type']:'';
        $district=isset($data['district'])?$data['district']:'';
        $nationality=isset($data['nationality'])?$data['nationality']:'';
        $icon=isset($data['icon'])?$data['icon']:'';
        $name=isset($data['name'])?$data['name']:'';
        $sequence=isset($data['sequence'])?$data['sequence']:'';
        $status=isset($data['status'])?$data['status']:'';


        if ($id != '') {
            $oQrCode = ThirdBall::find($id);
        } else {
            $oQrCode = new ThirdBall();
        }

        $oQrCode->type=$type;
        $oQrCode->district=$district;
        $oQrCode->nationality=$nationality;
        $oQrCode->icon=$icon;
        $oQrCode->name=$name;
        $oQrCode->sequence=$sequence;
        $oQrCode->status=$status;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'thirdBallSave';
        $sLogContent = 'thirdBallSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdGameTypesSave()
    {
        $data = request()->post();

        $id=isset($data['id'])?$data['id']:'';
        $type=isset($data['type'])?$data['type']:'';
        $name=isset($data['name'])?$data['name']:'';
        $identifier=isset($data['identifier'])?$data['identifier']:'';
        $plat_id=isset($data['plat_id'])?$data['plat_id']:'';
        $rate_basis=isset($data['rate_basis'])?$data['rate_basis']:'';
        $created_at=isset($data['created_at'])?$data['created_at']:'';
        $updated_at=isset($data['updated_at'])?$data['updated_at']:'';
        $status=isset($data['status'])?$data['status']:'';
        $sequence=isset($data['sequence'])?$data['sequence']:'';


        if ($id != '') {
            $oQrCode = ThirdGameTypes::find($id);
        } else {
            $oQrCode = new ThirdGameTypes();
        }

        $oQrCode->type=$type;
        $oQrCode->name=$name;
        $oQrCode->identifier=$identifier;
        $oQrCode->plat_id=$plat_id;
        $oQrCode->rate_basis=$rate_basis;
        $oQrCode->created_at=now();
        $oQrCode->updated_at=now();
        $oQrCode->status=$status;
        $oQrCode->sequence=$sequence;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'thirdGameTypesSave';
        $sLogContent = 'thirdGameTypesSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdGameTypesDetailSave()
    {
        $data = request()->post();
        $iId=isset($data['id'])?$data['id']:'';
        $iPlatId=isset($data['plat_id'])?$data['plat_id']:'';
        $iSetId=isset($data['set_id'])?$data['set_id']:'';
        $sPlatName=isset($data['plat_name'])?$data['plat_name']:'';
        $sName=isset($data['name'])?$data['name']:'';
        $sIcon=isset($data['icon'])?$data['icon']:'';
        $sDesc=isset($data['desc'])?$data['desc']:'';
        //$sStatus=isset($data['status'])?$data['status']:'';
        $sExtField1=isset($data['ext_field1'])?$data['ext_field1']:'';
        $sExtField2=isset($data['ext_field2'])?$data['ext_field2']:'';
        $sExtField3=isset($data['ext_field3'])?$data['ext_field3']:'';
        $sExtField4=isset($data['ext_field4'])?$data['ext_field4']:'';
        $sExtField5=isset($data['ext_field5'])?$data['ext_field5']:'';
        $sExtField6=isset($data['ext_field6'])?$data['ext_field6']:'';
        $sExtField7=isset($data['ext_field7'])?$data['ext_field7']:'';
        $sExtField8=isset($data['ext_field8'])?$data['ext_field8']:'';
        $sExtField9=isset($data['ext_field9'])?$data['ext_field9']:'';
        $sExtField10=isset($data['ext_field10'])?$data['ext_field10']:'';
        $sExtField11=isset($data['ext_field11'])?$data['ext_field11']:'';
        $sExtField12=isset($data['ext_field12'])?$data['ext_field12']:'';
        $sExtField13=isset($data['ext_field13'])?$data['ext_field13']:'';
        $sExtField14=isset($data['ext_field14'])?$data['ext_field14']:'';
        $sExtField15=isset($data['ext_field15'])?$data['ext_field15']:'';
        $sExtField16=isset($data['ext_field16'])?$data['ext_field16']:'';
        $sExtField17=isset($data['ext_field17'])?$data['ext_field17']:'';
        $sExtField18=isset($data['ext_field18'])?$data['ext_field18']:'';
        $sExtField19=isset($data['ext_field19'])?$data['ext_field19']:'';
        $sExtField20=isset($data['ext_field20'])?$data['ext_field20']:'';

        if ($iId != '' && empty($iSetId)) {
            $oThirdGameTypesDetail = ThirdGameTypesDetail::find($iId);
        } else {
            $oThirdGameTypesDetail = new ThirdGameTypesDetail();
        }
        $oThirdGameTypesDetail->plat_id=$iPlatId;
        if($iSetId){
            $oThirdGameTypesDetail->set_id=$iSetId;
        }
        $oThirdGameTypesDetail->plat_name=$sPlatName;
        $oThirdGameTypesDetail->name=$sName;
        $oThirdGameTypesDetail->icon=$sIcon;
        $oThirdGameTypesDetail->desc=$sDesc;
        //$oThirdGameTypesDetail->status=$sStatus;

        // for ($i=1; $i < 20; $i++) { 
        //     $oThirdGameTypesDetail->ext_field.$i=$data['ext_field'.$i];
        // }

        $oThirdGameTypesDetail->ext_field1=$sExtField1;
        $oThirdGameTypesDetail->ext_field2=$sExtField2;
        $oThirdGameTypesDetail->ext_field3=$sExtField3;
        $oThirdGameTypesDetail->ext_field4=$sExtField4;
        $oThirdGameTypesDetail->ext_field5=$sExtField5;
        $oThirdGameTypesDetail->ext_field6=$sExtField6;
        $oThirdGameTypesDetail->ext_field7=$sExtField7;
        $oThirdGameTypesDetail->ext_field8=$sExtField8;
        $oThirdGameTypesDetail->ext_field9=$sExtField9;
        $oThirdGameTypesDetail->ext_field10=$sExtField10;
        $oThirdGameTypesDetail->ext_field11=$sExtField11;
        $oThirdGameTypesDetail->ext_field12=$sExtField12;
        $oThirdGameTypesDetail->ext_field13=$sExtField13;
        $oThirdGameTypesDetail->ext_field14=$sExtField14;
        $oThirdGameTypesDetail->ext_field15=$sExtField15;
        $oThirdGameTypesDetail->ext_field16=$sExtField16;
        $oThirdGameTypesDetail->ext_field17=$sExtField17;
        $oThirdGameTypesDetail->ext_field18=$sExtField18;
        $oThirdGameTypesDetail->ext_field19=$sExtField19;
        $oThirdGameTypesDetail->ext_field20=$sExtField20;

        $iRet = $oThirdGameTypesDetail->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oThirdGameTypesDetail;

        $sOperateName = 'thirdGameTypesDetailSave';
        $sLogContent = 'thirdGameTypesDetailSave';
        $dt = now();
        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdMerchantgameSave()
    {
        $data = request()->post();
        $type=isset($data['type'])?$data['type']:'';
        $id=isset($data['id'])?$data['id']:'';

        if ($type == 'game_type_list_detail') {
            $oThirdGameTypesDetail = ThirdGameTypesDetail::find($id);

            $oThirdMerchantGame = ThirdMerchantGame::where('sub_game_name', $oThirdGameTypesDetail->name)->first();

            if (is_object($oThirdMerchantGame)) {

                $aFinal['message'] = '游戏已经添加';
                $aFinal['code'] = '0';

                return response()->json($aFinal);

            }

            $oQrCode = new ThirdMerchantGame();
            $oQrCode->merchant_id='1010';
            $oQrCode->merchant_name='admin';
            $oQrCode->type=$oThirdGameTypesDetail->name;
            $oQrCode->plat_id=$oThirdGameTypesDetail->plat_id;
            $oQrCode->plat_name=$oThirdGameTypesDetail->plat_name;
            $oQrCode->plat_icon=$oThirdGameTypesDetail->icon;;
            $oQrCode->sub_game_id=$oThirdGameTypesDetail->name;
            $oQrCode->sub_game_name=$oThirdGameTypesDetail->name;
            $oQrCode->sub_game_icon=$oThirdGameTypesDetail->icon;
        } else {
            $oThirdGameTypesDetail = ThirdBall::find($id);


            $oThirdMerchantGame = ThirdMerchantGame::where('sub_game_name', $oThirdGameTypesDetail->name)->first();

            if (is_object($oThirdMerchantGame)) {

                $aFinal['message'] = '游戏已经添加';
                $aFinal['code'] = '0';

                return response()->json($aFinal);

            }

            $oQrCode = new ThirdMerchantGame();
            $oQrCode->merchant_id='1010';
            $oQrCode->merchant_name='admin';
            $oQrCode->type=$oThirdGameTypesDetail->type;
            $oQrCode->plat_id=$oThirdGameTypesDetail->district;
            $oQrCode->plat_name=$oThirdGameTypesDetail->nationality;
            $oQrCode->plat_icon=$oThirdGameTypesDetail->icon;;
            $oQrCode->sub_game_id=$oThirdGameTypesDetail->name;
            $oQrCode->sub_game_name=$oThirdGameTypesDetail->name;
            $oQrCode->sub_game_icon=$oThirdGameTypesDetail->icon;
        }


        $iRet = $oQrCode->save();

        $aFinal['message'] = '添加成功';
        $aFinal['code'] = '1';
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'thirdMerchantgameSave';
        $sLogContent = 'thirdMerchantgameSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }



    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdPlatsSave()
    {
        $data = request()->post();

        $id=isset($data['id'])?$data['id']:'';
        $identity=isset($data['identity'])?$data['identity']:'';
        $name=isset($data['name'])?$data['name']:'';
        $plat_identity=isset($data['plat_identity'])?$data['plat_identity']:'';
        $params_key=isset($data['params_key'])?$data['params_key']:'';
        $key=isset($data['key'])?$data['key']:'';
        $iframe_url=isset($data['iframe_url'])?$data['iframe_url']:'';
        $data_url=isset($data['data_url'])?$data['data_url']:'';
        $status=isset($data['status'])?$data['status']:'';
        $query_enabled=isset($data['query_enabled'])?$data['query_enabled']:'';
        $free_data_url=isset($data['free_data_url'])?$data['free_data_url']:'';
        $sequence=isset($data['sequence'])?$data['sequence']:'';


        if ($id != '') {
            $oQrCode = ThirdPlats::find($id);
        } else {
            $oQrCode = new ThirdPlats();
        }

        $oQrCode->identity=$identity;
        $oQrCode->name=$name;
        $oQrCode->plat_identity=$plat_identity;
        $oQrCode->params_key=$params_key;
        $oQrCode->key=$key;
        $oQrCode->iframe_url=$iframe_url;
        $oQrCode->data_url=$data_url;
        $oQrCode->status=$status;
        $oQrCode->query_enabled=$query_enabled;
        $oQrCode->free_data_url=$free_data_url;
        $oQrCode->sequence=$sequence;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'thirdPlatsSave';
        $sLogContent = 'thirdPlatsSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }

    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function thirdBallDel()
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
        ThirdBall::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'messageDelete';
        $sLogContent = 'messageDelete';


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
    public function thirdGameTypesDel()
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
        ThirdGameTypes::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'messageDelete';
        $sLogContent = 'messageDelete';


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
    public function thirdGameTypesDetailDel()
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
        ThirdGameTypesDetail::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'messageDelete';
        $sLogContent = 'messageDelete';


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
    public function thirdMerchantgameDel()
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
        ThirdMerchantGame::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'messageDelete';
        $sLogContent = 'messageDelete';


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
    public function thirdPlatsDel()
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
        ThirdPlats::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'messageDelete';
        $sLogContent = 'messageDelete';


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
    /*public function merchantGameSequence($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = ThirdMerchantGame::find($iId);
        $oEvent->sequence = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;


        $sOperateName = 'marqueeSequence';
        $sLogContent = 'marqueeSequence';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }*/


    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function merchantsDomains()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $is_parse = isset(request()->is_parse) ? request()->is_parse : '';

        $oAuthAdminList = DB::table('merchants_domains');


        if ($is_parse !== '') {
            $oAuthAdminList->where('is_parse', $is_parse);
        }

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'merchantsDomains';

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
    public function merchantsIp()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $is_parse = isset(request()->is_parse) ? request()->is_parse : '';

        $oAuthAdminList = DB::table('merchants_ip');


        if ($is_parse !== '') {
            $oAuthAdminList->where('is_parse', $is_parse);
        }

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'merchantsIp';

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
    public function transactionTypeList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';

        $is_parse = isset(request()->is_parse) ? request()->is_parse : '';

        $oAuthAdminList = DB::table('transaction_types');


        if ($is_parse !== '') {
            $oAuthAdminList->where('is_parse', $is_parse);
        }

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'merchantsIp';

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
    public function merchantsIpSave()
    {
        $data = request()->post();

        $id=isset($data['id'])?$data['id']:'';
        $merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';
        $ip=isset($data['ip'])?$data['ip']:'';
        $parent_id=isset($data['parent_id'])?$data['parent_id']:0;
        $parent_level=isset($data['parent_level'])?$data['parent_level']:'';
        $parent_path=isset($data['parent_path'])?$data['parent_path']:'';
        $created_at=isset($data['created_at'])?$data['created_at']:'';
        $updated_at=isset($data['updated_at'])?$data['updated_at']:'';


        if ($id != '') {
            $oQrCode = MerchantsIp::find($id);
        } else {
            $oQrCode = new MerchantsIp();
        }

//        $oQrCode->id=$id;
        $oQrCode->merchant_id=$merchant_id;
        $oQrCode->ip=$ip;
        $oQrCode->parent_id=$parent_id;
        $oQrCode->parent_level=$parent_level;
        $oQrCode->parent_path=$parent_path;
        $oQrCode->created_at=now();
        $oQrCode->updated_at=now();

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'thirdPlatsSave';
        $sLogContent = 'thirdPlatsSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }

    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function transactionTypeSave()
    {
        $data = request()->post();

        $id=isset($data['id'])?$data['id']:'';
        $merchant_id=isset($data['merchant_id'])?$data['merchant_id']:0;
        $merchant_name=isset($data['merchant_name'])?$data['merchant_name']:'';
        $parent_id=isset($data['parent_id'])?$data['parent_id']:0;
        $parent=isset($data['parent'])?$data['parent']:'';
        $fund_flow_id=isset($data['fund_flow_id'])?$data['fund_flow_id']:0;
        $description=isset($data['description'])?$data['description']:'';
        $cn_title=isset($data['cn_title'])?$data['cn_title']:'';
        $balance=isset($data['balance'])?$data['balance']:'';
        $available=isset($data['available'])?$data['available']:'';
        $frozen=isset($data['frozen'])?$data['frozen']:'';
        $withdrawable=isset($data['withdrawable'])?$data['withdrawable']:'';
        $prohibit_amount=isset($data['prohibit_amount'])?$data['prohibit_amount']:'';
        $credit=isset($data['credit'])?$data['credit']:'';
        $debit=isset($data['debit'])?$data['debit']:'';
        $project_linked=isset($data['project_linked'])?$data['project_linked']:'';
        $trace_linked=isset($data['trace_linked'])?$data['trace_linked']:'';
        $reverse_type=isset($data['reverse_type'])?$data['reverse_type']:'';
        $created_at=isset($data['created_at'])?$data['created_at']:'';
        $updated_at=isset($data['updated_at'])?$data['updated_at']:'';

        if ($id != '') {
            $oQrCode = TransactionTypes::find($id);
        } else {
            $oQrCode = new TransactionTypes();
        }

//        $oQrCode->id=$id;
        $oQrCode->merchant_id=$merchant_id;
        $oQrCode->merchant_name=$merchant_name;
        $oQrCode->parent_id=$parent_id;
        $oQrCode->parent=$parent;
        $oQrCode->fund_flow_id=$fund_flow_id;
        $oQrCode->description=$description;
        $oQrCode->cn_title=$cn_title;
        $oQrCode->balance=$balance;
        $oQrCode->available=$available;
        $oQrCode->frozen=$frozen;
        $oQrCode->withdrawable=$withdrawable;
        $oQrCode->prohibit_amount=$prohibit_amount;
        $oQrCode->credit=$credit;
        $oQrCode->debit=$debit;
        $oQrCode->project_linked=$project_linked;
        $oQrCode->trace_linked=$trace_linked;
        $oQrCode->reverse_type=$reverse_type;
        $oQrCode->created_at=now();
        $oQrCode->updated_at=now();

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'thirdPlatsSave';
        $sLogContent = 'thirdPlatsSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function merchantsDomainsSave()
    {
        $data = request()->post();

        $id=isset($data['id'])?$data['id']:'';
        $merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';
        $domain=isset($data['domain'])?$data['domain']:'';
        $status=isset($data['status'])?$data['status']:'';
        $created_at=isset($data['created_at'])?$data['created_at']:'';
        $updated_at=isset($data['updated_at'])?$data['updated_at']:'';



        if ($id != '') {
            $oQrCode = MerchantsDomains::find($id);
        } else {
            $oQrCode = new MerchantsDomains();
        }

//        $oQrCode->id=$id;
        $oQrCode->merchant_id=$merchant_id;
        $oQrCode->domain=$domain;
        $oQrCode->status=$status;
        $oQrCode->created_at=now();
        $oQrCode->updated_at=now();


        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'thirdPlatsSave';
        $sLogContent = 'thirdPlatsSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function merchantsIpDel()
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
        MerchantsIp::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'messageDelete';
        $sLogContent = 'messageDelete';


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
    public function transactionTypeDel()
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
        TransactionTypes::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'messageDelete';
        $sLogContent = 'messageDelete';


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
    public function merchantsDomainsDel()
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
        MerchantsDomains::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'messageDelete';
        $sLogContent = 'messageDelete';


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
    public function merchantsIpStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';

        try
        {
            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {

                $oEvent = MerchantsIp::find($iId);
                if (is_object($oEvent)) {
                    $iStatue = $oEvent->status;
                }
                $oEvent->status = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'payGroupStatusSave';
                $sLogContent = 'payGroupStatusSave';

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
     * 数据保存
     * @param request
     * @return json
     */
    public function transactionTypeStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';

        try
        {
            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {

                $oEvent = TransactionTypes::find($iId);
                if (is_object($oEvent)) {
                    $iStatue = $oEvent->status;
                }
                $oEvent->status = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'payGroupStatusSave';
                $sLogContent = 'payGroupStatusSave';

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
     * 数据保存
     * @param request
     * @return json
     */
    public function merchantsDomainsStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';

        try
        {
            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {

                $oEvent = MerchantsDomains::find($iId);
                if (is_object($oEvent)) {
                    $iStatue = $oEvent->status;
                }
                $oEvent->status = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'payGroupStatusSave';
                $sLogContent = 'payGroupStatusSave';

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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdGameSetSave()
    {
        $data = request()->post();
        var_dump($data);die;
        $id=isset($data['id'])?$data['id']:'';
        $domains=isset($data['domains'])?$data['domains']:'';
        $aData = [];
        foreach ($data['domains'] as $k => $v) {
            $index = $k+1;
            $aData["ext_column".$index] = $v['value'];
        }
        // Log::info($this->objectToArray($domains));
        $ext_column1=isset($aData['ext_column1'])?$aData['ext_column1']:'';
        $ext_column2=isset($aData['ext_column2'])?$aData['ext_column2']:'';
        $ext_column3=isset($aData['ext_column3'])?$aData['ext_column3']:'';
        $ext_column4=isset($aData['ext_column4'])?$aData['ext_column4']:'';
        $ext_column5=isset($aData['ext_column5'])?$aData['ext_column5']:'';
        $ext_column6=isset($aData['ext_column6'])?$aData['ext_column6']:'';
        $ext_column7=isset($aData['ext_column7'])?$aData['ext_column7']:'';
        $ext_column8=isset($aData['ext_column8'])?$aData['ext_column8']:'';
        $ext_column9=isset($aData['ext_column9'])?$aData['ext_column9']:'';
        $ext_column10=isset($aData['ext_column10'])?$aData['ext_column10']:'';
        $ext_column11=isset($aData['ext_column11'])?$aData['ext_column11']:'';
        $ext_column12=isset($aData['ext_column12'])?$aData['ext_column12']:'';
        $ext_column13=isset($aData['ext_column13'])?$aData['ext_column13']:'';
        $ext_column14=isset($aData['ext_column14'])?$aData['ext_column14']:'';
        $ext_column15=isset($aData['ext_column15'])?$aData['ext_column15']:'';
        $ext_column16=isset($aData['ext_column16'])?$aData['ext_column16']:'';
        $ext_column17=isset($aData['ext_column17'])?$aData['ext_column17']:'';
        $ext_column18=isset($aData['ext_column18'])?$aData['ext_column18']:'';
        $ext_column19=isset($aData['ext_column19'])?$aData['ext_column19']:'';
        $ext_column20=isset($aData['ext_column20'])?$aData['ext_column20']:'';


        if ($id != '') {
            $oQrCode = ThirdGameSet::find($id);
        } else {
            $oQrCode = new ThirdGameSet();
        }

//        $oQrCode->id=$id;
//        $oQrCode->id=$id;
        $oQrCode->ext_column1=$ext_column1;
        $oQrCode->ext_column2=$ext_column2;
        $oQrCode->ext_column3=$ext_column3;
        $oQrCode->ext_column4=$ext_column4;
        $oQrCode->ext_column5=$ext_column5;
        $oQrCode->ext_column6=$ext_column6;
        $oQrCode->ext_column7=$ext_column7;
        $oQrCode->ext_column8=$ext_column8;
        $oQrCode->ext_column9=$ext_column9;
        $oQrCode->ext_column10=$ext_column10;
        $oQrCode->ext_column11=$ext_column11;
        $oQrCode->ext_column12=$ext_column12;
        $oQrCode->ext_column13=$ext_column13;
        $oQrCode->ext_column14=$ext_column14;
        $oQrCode->ext_column15=$ext_column15;
        $oQrCode->ext_column16=$ext_column16;
        $oQrCode->ext_column17=$ext_column17;
        $oQrCode->ext_column18=$ext_column18;
        $oQrCode->ext_column19=$ext_column19;
        $oQrCode->ext_column20=$ext_column20;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'thirdPlatsSave';
        $sLogContent = 'thirdPlatsSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


}