<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\common\vo\ResultVo;
use App\common\utils\PublicFileUtils;
use App\model\AdminLog;

use App\model\ThirdAgFtpGetLogs;
use App\model\ThirdAgProjectRecord;
use App\model\ThirdBall;
use App\model\ThirdGameTypes;
use App\model\ThirdMerchantGame;
use App\model\ThirdPlats;
use App\model\ThirdUserGaTurnovers;
use App\model\ThirdGameTypesDetail;

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
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';

        $sTitle = isset(request()->title) ? request()->title : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('third_ag_ftp_get_logs');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $iLimit = request()->get('limit/d', 20);
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
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';

        $sTitle = isset(request()->title) ? request()->title : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('third_ag_project_record');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $iLimit = request()->get('limit/d', 20);
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
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';

        $sTitle = isset(request()->title) ? request()->title : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('third_ball');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $iLimit = request()->get('limit/d', 20);
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
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';

        $sTitle = isset(request()->title) ? request()->title : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('third_ball');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $iLimit = request()->get('limit/d', 20);
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
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';

        $sTitle = isset(request()->title) ? request()->title : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('third_user_ga_turnovers');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $iLimit = request()->get('limit/d', 20);
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
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sUserName = isset(request()->username) ? request()->username : '';

        $sTitle = isset(request()->title) ? request()->title : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('third_game_types');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $iLimit = request()->get('limit/d', 20);
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
        $sUserName = isset(request()->username) ? request()->username : '';

        $sTitle = isset(request()->title) ? request()->title : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('third_game_types_detail');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $iLimit = request()->get('limit/d', 20);
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
    public function merchantGameList()
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

        $sTitle = isset(request()->title) ? request()->title : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('third_merchant_game');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $iLimit = request()->get('limit/d', 20);
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
    public function thirdPlatList()
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

        $sTitle = isset(request()->title) ? request()->title : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('third_plats');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }

        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $iLimit = request()->get('limit/d', 20);
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
    public function thirdGameTypesSequence($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = ThirdGameTypes::find($iId);
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
     * 数据保存
     * @param request
     * @return json
     */
    public function thirdPlatsSequence($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = ThirdPlats::find($iId);
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
    public function thirdBallStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = ThirdBall::find($iId);
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
    public function thirdGameTypesStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = ThirdGameTypes::find($iId);
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
    public function thirdGameTypesSubStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = ThirdGameTypesDetail::find($iId);
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
    public function thirdMerchantGameStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = ThirdMerchantGame::find($iId);
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
    public function thirdPlatsStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = ThirdPlats::find($iId);
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


/*        $merchant_id=isset($data['merchant_id'])?$data['merchant_id']:'';
        $merchant_name=isset($data['merchant_name'])?$data['merchant_name']:'';
        $type=isset($data['type'])?$data['type']:'';
        $plat_id=isset($data['plat_id'])?$data['plat_id']:'';
        $plat_name=isset($data['plat_name'])?$data['plat_name']:'';
        $plat_icon=isset($data['plat_icon'])?$data['plat_icon']:'';
        $sub_game_id=isset($data['sub_game_id'])?$data['sub_game_id']:'';
        $sub_game_name=isset($data['sub_game_name'])?$data['sub_game_name']:'';
        $sub_game_icon=isset($data['sub_game_icon'])?$data['sub_game_icon']:'';
        $sequence=isset($data['sequence'])?$data['sequence']:'';
        $status=isset($data['status'])?$data['status']:'';


        if ($id != '') {
            $oQrCode = ThirdMerchantGame::find($id);
        } else {
            $oQrCode = new ThirdMerchantGame();
        }

        $oQrCode->merchant_id=$merchant_id;
        $oQrCode->merchant_name=$merchant_name;
        $oQrCode->type=$type;
        $oQrCode->plat_id=$plat_id;
        $oQrCode->plat_name=$plat_name;
        $oQrCode->plat_icon=$plat_icon;
        $oQrCode->sub_game_id=$sub_game_id;
        $oQrCode->sub_game_name=$sub_game_name;
        $oQrCode->sub_game_icon=$sub_game_icon;
        $oQrCode->sequence=$sequence;
        $oQrCode->status=$status;*/

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
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



}