<?php

namespace App\Http\Controllers;

use App\model\Notice;
use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\AuthAdmin;
use App\model\AuthRoleAdmin;
use App\common\utils\PublicFileUtils;
use App\model\Ad;
use App\model\AdSite;
use App\model\Message;
use App\model\Marquee;
use App\model\AdminLog;


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

        $oAuthAdminList = DB::table('info_marquee');


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

        $oAuthAdminList = DB::table('info_marquee');


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
    public function marqueeList()
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

        $oAuthAdminList = DB::table('info_marquee');


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

        $oAuthAdminList = DB::table('info_marquee');


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

        $oAuthAdminList = DB::table('info_marquee');


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

        $oAuthAdminList = DB::table('info_marquee');


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

        $oAuthAdminList = DB::table('info_marquee');


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

        $oAuthAdminList = DB::table('info_marquee');


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

        $oAuthAdminList = DB::table('info_marquee');


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


}