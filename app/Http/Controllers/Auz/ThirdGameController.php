<?php

namespace App\Http\Controllers;

use App\common\utils\CommonUtils;
use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\AdminLog;

use App\model\ThirdBall;
use App\model\ThirdGameTypes;
use App\model\ThirdMerchantGame;
use App\model\ThirdPlats;
use App\model\ThirdGameTypesDetail;
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
     * 数据取得
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

        $oAuthAdminList = DB::table('third_user_ga_turnovers');

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



    /**
     * 数据取得
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
        $name = isset(request()->name) ? request()->name : '';
        $plat_namee = isset(request()->plat_name) ? request()->plat_name : '';


        $oAuthAdminList = DB::table('third_game_types_detail');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($name !== '') {
            $oAuthAdminList->where('name', 'like', '%' . $name . '%');
        }

        if ($plat_namee !== '') {
            $oAuthAdminList->where('plat_name', 'like', '%' . $plat_namee . '%');
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
        $oEvent = ThirdBall::find($iId);
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
    public function thirdMerchantGameSequence($iId = null)
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
    }


    /**
     * 商户游戏管理列表修改排序值
     * @param request
     * @return json
     */
    public function thirdMerchantGameFee()
    {

        $data = request()->post();
        $oMerchantGame = new ThirdMerchantGame;
        $aData = $oMerchantGame->thirdMerchantGameFee($data);
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $aData;

        $sOperateName = 'thirdMerchantGameFee';
        $sLogContent = 'thirdMerchantGameFee';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
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
        $oMerchantGame = ThirdMerchantGame::find($iId);
        $oMerchantGame->sub_fee = $iFlag;
        $iRet = $oMerchantGame->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oMerchantGame;

        $sOperateName = 'thirdMerchantGameSubFee';
        $sLogContent = 'thirdMerchantGameSubFee';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
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

        $id=isset($data['id'])?$data['id']:'';
        $plat_id=isset($data['plat_id'])?$data['plat_id']:'';
        $plat_name=isset($data['plat_name'])?$data['plat_name']:'';
        $name=isset($data['name'])?$data['name']:'';
        $icon=isset($data['icon'])?$data['icon']:'';
        $desc=isset($data['desc'])?$data['desc']:'';
        $status=isset($data['status'])?$data['status']:'';


        if ($id != '') {
            $oQrCode = ThirdGameTypesDetail::find($id);
        } else {
            $oQrCode = new ThirdGameTypesDetail();
        }

        $oQrCode->plat_id=$plat_id;
        $oQrCode->plat_name=$plat_name;
        $oQrCode->name=$name;
        $oQrCode->icon=$icon;
        $oQrCode->desc=$desc;
        $oQrCode->status=$status;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


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

        $aFinal['message'] = 'success';
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

}