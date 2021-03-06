<?php

namespace App\Http\Controllers;

use App\common\utils\CommonUtils;
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
use App\model\Common;


/**
 * Class Event - 公告相关控制器
 * @author zebra
 */
class NoticeController extends Controller
{
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

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);

//        $aTmp = [];
//        $aFinal = [];
//        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
//            $aTmp['id'] = $oAuthAdmin->id;
//            $aTmp['title'] = $oAuthAdmin->title;
//            $aTmp['terminal'] = $oAuthAdmin->terminal;
//            $aTmp['sequence'] = $oAuthAdmin->sequence;
//            $aTmp['content'] = $oAuthAdmin->content;
//            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
//            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
//            $aTmp['status'] = $oAuthAdmin->status;
//
//            $aFinal[] = $aTmp;
//        }

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
    public function messageList()
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

        $sCategory = isset(request()->category) ? request()->category : '';

        $sUserName = isset(request()->username) ? request()->username : '';
        $sTitle = isset(request()->title) ? request()->title : '';
        $sReceiveFlag = isset(request()->receive_flag) ? request()->receive_flag : '';
        $dtBeginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $dtEndDate = isset(request()->endDate) ? request()->endDate : '';
        $sReceivers = isset(request()->receivers) ? request()->receivers : '';


        $sType = isset(request()->type) ? request()->type : '';

        $oAuthAdminList = DB::table('info_message');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }


        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }



        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }


        if ($sReceiveFlag !== '') {
            $oAuthAdminList->where('receive_flag', $sReceiveFlag);
        }


        if ($sType !== '') {
            $oAuthAdminList->where('type', $sType);
        }


        if ($dtBeginDate !== '') {
            $oAuthAdminList->where('created_at', '>=', $dtBeginDate);
        }


        if ($dtEndDate !== '') {
            $oAuthAdminList->where('created_at', '<=', $dtEndDate);
        }

        if ($sReceivers !== '') {
            $oAuthAdminList->where('receivers', $sReceivers);
        }

        if ($sCategory !== '') {
            $oAuthAdminList->where('category', $sCategory);
        }

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


       /* $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['type'] = $oAuthAdmin->type;
            $aTmp['title'] = $oAuthAdmin->title;
            $aTmp['receive_flag'] = $oAuthAdmin->receive_flag;
            $aTmp['receivers'] = $oAuthAdmin->receivers;
            $aTmp['content'] = $oAuthAdmin->content;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['sender'] = $oAuthAdmin->sender;
            $aTmp['category'] = $oAuthAdmin->category;
            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'messageList';
        $sLogContent = 'messageList';


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
    public function noticeList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';

        $iStatus = isset(request()->status) ? request()->status : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('info_notice');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }


        if ($sMerchantName !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $iLimit = request()->get('limit', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($iLimit);


        /*$aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['title'] = $oAuthAdmin->title;
            $aTmp['type'] = $oAuthAdmin->type;
            $aTmp['sequence'] = $oAuthAdmin->sequence;
            $aTmp['pop_flag'] = $oAuthAdmin->pop_flag;
            $aTmp['send_terminal'] = $oAuthAdmin->send_terminal;
            $aTmp['send_range'] = $oAuthAdmin->send_range;
            $aTmp['layers'] = $oAuthAdmin->layers;
            $aTmp['content'] = $oAuthAdmin->content;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['is_top'] = $oAuthAdmin->is_top;
            $aTmp['merchant_name'] = $oAuthAdmin->merchant_name;
            $aTmp['merchant_id'] = $oAuthAdmin->merchant_id;

            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($oAuthAdminFinalList);
        $res["list"] = $oAuthAdminFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'noticeList';
        $sLogContent = 'noticeList';


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
    public function pushList()
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


        $sOperateName = 'pushList';
        $sLogContent = 'pushList';


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
    public function messageSave()
    {
        $data = request()->post();
        $sType=isset($data['type'])?$data['type']:'';
        $sTitle=isset($data['title'])?$data['title']:'';
        $sReceiveFlag=isset($data['receive_flag'])?$data['receive_flag']:'';
        $sReceivers=isset($data['receivers'])?$data['receivers']:'';
        $sContent=isset($data['content'])?$data['content']:'';
        $sCategory=isset($data['category'])?$data['category']:'';

        $oQrCode = new Message();

        $oQrCode->type=$sType;
        $oQrCode->title=$sTitle;
        $oQrCode->receive_flag=$sReceiveFlag;
        $oQrCode->receivers=$sReceivers;
        $oQrCode->content=$sContent;
        $oQrCode->category=$sCategory;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'messageSave';
        $sLogContent = 'messageSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }



    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function marqueeSave()
    {
        $data = request()->post();

        $sTitle=isset($data['title'])?$data['title']:'';
        $sTerminal=isset($data['terminal'])?$data['terminal']:'';
        $iSequence=isset($data['sequence'])?$data['sequence']:'';
        $sContent=isset($data['content'])?$data['content']:'';

        $oQrCode = new Marquee();

        $oQrCode->title=$sTitle;
        $oQrCode->terminal=$sTerminal;
        $oQrCode->sequence=$iSequence;
        $oQrCode->content=$sContent;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'marqueeSave';
        $sLogContent = 'marqueeSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }
    /**
     * 数据保存
     * @param request
     * @return json
     */
    public function noticeSave()
    {
        $data = request()->post();


        $sTitle=isset($data['title'])?$data['title']:'';
        $sType=isset($data['type'])?$data['type']:'';
        $iSequence=isset($data['sequence'])?$data['sequence']:'';
        $sPopFlag=isset($data['pop_flag'])?$data['pop_flag']:'';
        $sSendTerminal=isset($data['send_terminal'])?$data['send_terminal']:'';
        $sSendRange=isset($data['send_range'])?$data['send_range']:'';
        $sLayers=isset($data['layers'])?$data['layers']:'';
        $sContent=isset($data['content'])?$data['content']:'';


        $oQrCode = new Notice();

        $oQrCode->title=$sTitle;
        $oQrCode->type=$sType;
        $oQrCode->sequence=$iSequence;
        $oQrCode->pop_flag=$sPopFlag;
        $oQrCode->send_terminal=$sSendTerminal;
        $oQrCode->send_range=$sSendRange;
        $oQrCode->layers=$sLayers;
        $oQrCode->content=$sContent;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;


        $sOperateName = 'noticeSave';
        $sLogContent = 'noticeSave';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }
    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function adminDelete()
    {
        $iId = request()->all()['id'];
        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $oAuthAdmin = AuthAdmin::where('id', $iId)->first();
        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        // 删除权限
        AuthRoleAdmin::where('admin_id', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'adminDelete';
        $sLogContent = 'adminDelete';


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
    public function noticeTopSave($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        try
        {
            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {
                $oEvent = Notice::find($iId);
                if (is_object($oEvent)) {
                    $iStatue = $oEvent->status;
                }
                $oEvent->is_top = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'noticeTopSave';
                $sLogContent = 'noticeTopSave';
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
    public function noticeStatusSave($iId = null)
    {

        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        
        try
        {

            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {
                $oEvent = Notice::find($iId);
                if (is_object($oEvent)) {
                    $iStatue = $oEvent->status;
                }
                $oEvent->status = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;

                $sOperateName = 'noticeStatusSave';
                $sLogContent = 'noticeStatusSave';

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
     * 数据取得
     * @param request
     * @return json
     */
    public function messageDelete()
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
        Message::where('id', '=', $iId)->delete();

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
    public function noticeDelete()
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
        Notice::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'noticeDelete';
        $sLogContent = 'noticeDelete';


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
    public function marqueeDelete()
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
        Marquee::where('id', '=', $iId)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;


        $sOperateName = 'marqueeDelete';
        $sLogContent = 'marqueeDelete';


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
    public function noticeSequence()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oEvent = Notice::find($iId);
                $oEvent->sequence = $iFlag;
                $iRet = $oEvent->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oEvent;
                $sOperateName = 'noticeSequence';
                $sLogContent = 'noticeSequence';
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
    public function marqueeSequence($iId = null)
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oEvent = Marquee::find($iId);
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


}