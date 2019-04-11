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
class NoticeController extends Controller
{

    public function marqueeList()
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

        $title = isset(request()->title) ? request()->title : '';
        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        $title = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('info_marquee');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }


        if ($merchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($title !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $title . '%');
        }

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);


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

        $sub_account = '123';
        $operate_name = 'marqueeList';
        $log_content = 'marqueeList';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);


        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function messageList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $iRoleId = isset(request()->role_id) ? request()->role_id : '';

        $iStatus = isset(request()->status) ? request()->status : '';

        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';

        $category = isset(request()->category) ? request()->category : '';

        $sUserName = isset(request()->username) ? request()->username : '';
        $title = isset(request()->title) ? request()->title : '';
        $receive_flag = isset(request()->receive_flag) ? request()->receive_flag : '';
        $beginDate = isset(request()->beginDate) ? request()->beginDate : '';
        $endDate = isset(request()->endDate) ? request()->endDate : '';
        $receivers = isset(request()->receivers) ? request()->receivers : '';

        $oAuthAdminList = DB::table('info_message');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }


        if ($sUserName !== '') {
            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
        }



        if ($merchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }

        if ($title !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $title . '%');
        }


        if ($receive_flag !== '') {
            $oAuthAdminList->where('receive_flag', $receive_flag);
        }

        if ($beginDate !== '') {
            $oAuthAdminList->where('created_at', '>=', $beginDate);
        }


        if ($endDate !== '') {
            $oAuthAdminList->where('created_at', '<=', $endDate);
        }

        if ($receivers !== '') {
            $oAuthAdminList->where('receivers', $receivers);
        }

        if ($category !== '') {
            $oAuthAdminList->where('category', $category);
        }

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);


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

        $sub_account = '123';
        $operate_name = 'messageList';
        $log_content = 'messageList';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);


        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function noticeList()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        // +id -id
        $iSort = isset(request()->sort) ? request()->sort : '';
        $sMerchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';

        $iStatus = isset(request()->status) ? request()->status : '';
        $sTitle = isset(request()->title) ? request()->title : '';

        $oAuthAdminList = DB::table('info_notice');


        if ($iStatus !== '') {
            $oAuthAdminList->where('status', $iStatus);
        }


        if ($sMerchant_name !== '') {
            $oAuthAdminList->where('merchant_name', 'like', '%' . $sMerchant_name . '%');
        }

        if ($sTitle !== '') {
            $oAuthAdminList->where('title', 'like', '%' . $sTitle . '%');
        }

        $limit = request()->get('limit/d', 20);
        $oAuthAdminFinalList = $oAuthAdminList->orderby('id', 'desc')->paginate($limit);


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

        $sub_account = '123';
        $operate_name = 'noticeList';
        $log_content = 'noticeList';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    public function pushList()
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
        $operate_name = 'pushList';
        $log_content = 'pushList';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function messageSave()
    {
        $data = request()->post();
        $type=isset($data['type'])?$data['type']:'';
        $title=isset($data['title'])?$data['title']:'';
        $receive_flag=isset($data['receive_flag'])?$data['receive_flag']:'';
        $receivers=isset($data['receivers'])?$data['receivers']:'';
        $content=isset($data['content'])?$data['content']:'';
        $category=isset($data['category'])?$data['category']:'';

        $oQrCode = new Message();

        $oQrCode->type=$type;
        $oQrCode->title=$title;
        $oQrCode->receive_flag=$receive_flag;
        $oQrCode->receivers=$receivers;
        $oQrCode->content=$content;
        $oQrCode->category=$category;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        $sub_account = '123';
        $operate_name = 'messageSave';
        $log_content = 'messageSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
    }




    public function marqueeSave()
    {
        $data = request()->post();

        $title=isset($data['title'])?$data['title']:'';
        $terminal=isset($data['terminal'])?$data['terminal']:'';
        $sequence=isset($data['sequence'])?$data['sequence']:'';
        $content=isset($data['content'])?$data['content']:'';

        $oQrCode = new Marquee();

        $oQrCode->title=$title;
        $oQrCode->terminal=$terminal;
        $oQrCode->sequence=$sequence;
        $oQrCode->content=$content;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        $sub_account = '123';
        $operate_name = 'marqueeSave';
        $log_content = 'marqueeSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
    }

    public function noticeSave()
    {
        $data = request()->post();


        $title=isset($data['title'])?$data['title']:'';
        $type=isset($data['type'])?$data['type']:'';
        $sequence=isset($data['sequence'])?$data['sequence']:'';
        $pop_flag=isset($data['pop_flag'])?$data['pop_flag']:'';
        $send_terminal=isset($data['send_terminal'])?$data['send_terminal']:'';
        $send_range=isset($data['send_range'])?$data['send_range']:'';
        $layers=isset($data['layers'])?$data['layers']:'';
        $content=isset($data['content'])?$data['content']:'';


        $oQrCode = new Notice();

        $oQrCode->title=$title;
        $oQrCode->type=$type;
        $oQrCode->sequence=$sequence;
        $oQrCode->pop_flag=$pop_flag;
        $oQrCode->send_terminal=$send_terminal;
        $oQrCode->send_range=$send_range;
        $oQrCode->layers=$layers;
        $oQrCode->content=$content;

        $iRet = $oQrCode->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oQrCode;

        $sub_account = '123';
        $operate_name = 'noticeSave';
        $log_content = 'noticeSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
    }

    public function adminDelete()
    {
        $id = request()->all()['id'];
        if ($id == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
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
        $operate_name = 'adminDelete';
        $log_content = 'adminDelete';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
        return ResultVo::success();

    }

    public function noticeTopSave($id = null)
    {

        $data = request()->post();

        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
//
        $oEvent = Notice::find($id);
        if (is_object($oEvent)) {
            $iStatue = $oEvent->status;
        }
        $oEvent->is_top = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;

        $sub_account = '123';
        $operate_name = 'noticeTopSave';
        $log_content = 'noticeTopSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
    }

    public function noticeStatusSave($id = null)
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
        $oEvent = Notice::find($id);
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
        $operate_name = 'noticeStatusSave';
        $log_content = 'noticeStatusSave';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
    }

    public function messageDelete()
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
        Message::where('id', '=', $id)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'messageDelete';
        $log_content = 'messageDelete';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
        return ResultVo::success();

    }


    public function noticeDelete()
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
        Notice::where('id', '=', $id)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'noticeDelete';
        $log_content = 'noticeDelete';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
        return ResultVo::success();

    }

    public function marqueeDelete()
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
        Marquee::where('id', '=', $id)->delete();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;

        $sub_account = '123';
        $operate_name = 'marqueeDelete';
        $log_content = 'marqueeDelete';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
        return ResultVo::success();

    }


    public function noticeSequence($id = null)
    {

        $data = request()->post();

        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = Notice::find($id);
        $oEvent->sequence = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;

        $sub_account = '123';
        $operate_name = 'noticeSequence';
        $log_content = 'noticeSequence';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
    }


    public function marqueeSequence($id = null)
    {

        $data = request()->post();
        $id = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        $oEvent = Marquee::find($id);
        $oEvent->sequence = $iFlag;
        $iRet = $oEvent->save();
        $aFinal['message'] = 'success';
        $aFinal['code'] = $iFlag;
        $aFinal['data'] = $oEvent;

        $sub_account = '123';
        $operate_name = 'marqueeSequence';
        $log_content = 'marqueeSequence';
        $ip = '123';
        $cookies = '123';
        $date = now();
        $merchant_id = '123';
        $merchant_name = '123';

        AdminLog::adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name);
        return response()->json($aFinal);
    }


}