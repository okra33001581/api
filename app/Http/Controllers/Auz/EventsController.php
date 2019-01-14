<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\model\Event;
use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\AuthAdmin;
use App\model\AuthRoleAdmin;
use App\model\AuthPermission;
use App\model\AuthPermissionRule;
use App\model\AuthRole;
use App\common\utils\PublicFileUtils;
use App\common\utils\PassWordUtils;
use App\model\Ad;
use App\model\AdSite;
use App\model\FileResource;
use App\model\FileResourceTag;

use Illuminate\Support\Facades\Redis;

class EventsController extends Controller
{
    /**
     * @api {get} /api/admin 显示商户列表
     * @apiGroup admin
     *
     *
     * @apiSuccessExample 返回商户信息列表
     * HTTP/1.1 200 OK
     * {
     *  "data": [
     *     {
     *       "id": 2 // 整数型  用户标识
     *       "name": "test"  //字符型 用户昵称
     *       "email": "test@qq.com"  // 字符型 用户email，商户登录时的email
     *       "role": "admin" // 字符型 角色  可以取得值为admin或editor
     *       "avatar": "" // 字符型 用户的头像图片
     *     }
     *   ],
     * "status": "success",
     * "status_code": 200,
     * "links": {
     * "first": "http://manger.test/api/admin?page=1",
     * "last": "http://manger.test/api/admin?page=19",
     * "prev": null,
     * "next": "http://manger.test/api/admin?page=2"
     * },
     * "meta": {adminDelete
     * "current_page": 1, // 当前页
     * "from": 1, //当前页开始的记录
     * "last_page": 19, //总页数
     * "path": "http://manger.test/api/admin",
     * "per_page": 15,
     * "to": 15, //当前页结束的记录
     * "total": 271  // 总条数
     * }
     * }
     *
     */
    public function eventIndex()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
//        $iLimit = isset(request()->limit) ? request()->limit : '';
//        $iPage = isset(request()->page) ? request()->page : '';
//        // +id -id
//        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
//        $iStatus = isset(request()->status) ? request()->status : '';
//        $sUserName = isset(request()->username) ? request()->username : '';
        $oAuthAdminList = DB::table('events');

        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['identifier'] = $oAuthAdmin->identifier;
            $aTmp['title'] = $oAuthAdmin->title;
            $aTmp['description'] = $oAuthAdmin->description;
            $aTmp['calculate_cycle'] = $oAuthAdmin->calculate_cycle;
            $aTmp['view_type'] = $oAuthAdmin->view_type;
            $aTmp['is_team_event'] = $oAuthAdmin->is_team_event;
            $aTmp['is_show_team_leader'] = $oAuthAdmin->is_show_team_leader;
            $aTmp['is_show_team_member'] = $oAuthAdmin->is_show_team_member;
            $aTmp['is_receive'] = $oAuthAdmin->is_receive;
            $aTmp['after_receive_day_limit'] = $oAuthAdmin->after_receive_day_limit;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['is_get_mulite_prize'] = $oAuthAdmin->is_get_mulite_prize;
            $aTmp['start_time'] = $oAuthAdmin->start_time;
            $aTmp['end_time'] = $oAuthAdmin->end_time;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['expired_at'] = $oAuthAdmin->expired_at;
            $aTmp['is_single_condition'] = $oAuthAdmin->is_single_condition;
            $aTmp['url'] = $oAuthAdmin->url;
            $aTmp['terminal_id'] = $oAuthAdmin->terminal_id;
            $aTmp['icon'] = $oAuthAdmin->icon;
            $aTmp['large_icon'] = $oAuthAdmin->large_icon;
            $aTmp['color'] = $oAuthAdmin->color;
            $aTmp['mobile_title'] = $oAuthAdmin->mobile_title;

            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function eventConditonsIndex()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
//        $iLimit = isset(request()->limit) ? request()->limit : '';
//        $iPage = isset(request()->page) ? request()->page : '';
//        // +id -id
//        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
//        $iStatus = isset(request()->status) ? request()->status : '';
//        $sUserName = isset(request()->username) ? request()->username : '';
        $oAuthAdminList = DB::table('event_conditions');

        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['event_id'] = $oAuthAdmin->event_id;
            $aTmp['level'] = $oAuthAdmin->level;
            $aTmp['type'] = $oAuthAdmin->type;
            $aTmp['target_value'] = $oAuthAdmin->target_value;
            $aTmp['start_value'] = $oAuthAdmin->start_value;
            $aTmp['attendance_number_limit'] = $oAuthAdmin->attendance_number_limit;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;


            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    public function eventPrizeIndex()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
//        $iLimit = isset(request()->limit) ? request()->limit : '';
//        $iPage = isset(request()->page) ? request()->page : '';
//        // +id -id
//        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
//        $iStatus = isset(request()->status) ? request()->status : '';
//        $sUserName = isset(request()->username) ? request()->username : '';
        $oAuthAdminList = DB::table('event_prizes');

        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['event_id'] = $oAuthAdmin->event_id;
            $aTmp['level'] = $oAuthAdmin->level;
            $aTmp['type'] = $oAuthAdmin->type;
            $aTmp['gift_type'] = $oAuthAdmin->gift_type;
            $aTmp['gift_value'] = $oAuthAdmin->gift_value;
            $aTmp['probability'] = $oAuthAdmin->probability;
            $aTmp['condition_1'] = $oAuthAdmin->condition_1;
            $aTmp['condition_2'] = $oAuthAdmin->condition_2;
            $aTmp['send_people_type'] = $oAuthAdmin->send_people_type;
            $aTmp['send_limit_cycle'] = $oAuthAdmin->send_limit_cycle;
            $aTmp['send_limit_count'] = $oAuthAdmin->send_limit_count;
            $aTmp['need_verify'] = $oAuthAdmin->need_verify;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['send_type'] = $oAuthAdmin->send_type;
            $aTmp['personal_limit_count'] = $oAuthAdmin->personal_limit_count;
            $aTmp['trigger_date'] = $oAuthAdmin->trigger_date;


            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function eventUserPrizeIndex()
    {
        $sWhere = [];
        $sOrder = 'id DESC';
//        $iLimit = isset(request()->limit) ? request()->limit : '';
//        $iPage = isset(request()->page) ? request()->page : '';
//        // +id -id
//        $iSort = isset(request()->sort) ? request()->sort : '';
//        $iRoleId = isset(request()->role_id) ? request()->role_id : '';
//        $iStatus = isset(request()->status) ? request()->status : '';
//        $sUserName = isset(request()->username) ? request()->username : '';
        $oAuthAdminList = DB::table('event_user_prizes');

        $sTmp = 'DESC';
//        if (substr($iSort, 0, 1) == '-') {
//            $sTmp = 'ASC';
//        }
//        $sOrder = substr($iSort, 1, strlen($iSort));
//        if ($sTmp != '') {
//            $oAuthAdminList->orderby($sOrder, $sTmp);
//        }
//        if ($iStatus !== '') {
//            $oAuthAdminList->where('status', $iStatus);
//        }
//        if ($sUserName !== '') {
//            $oAuthAdminList->where('username', 'like', '%' . $sUserName . '%');
//        }
        $oAuthAdminListCount = $oAuthAdminList->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['event_id'] = $oAuthAdmin->event_id;
            $aTmp['event_user_id'] = $oAuthAdmin->event_user_id;
            $aTmp['event_prize_id'] = $oAuthAdmin->event_prize_id;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['level'] = $oAuthAdmin->level;
            $aTmp['type'] = $oAuthAdmin->type;
            $aTmp['gift_type'] = $oAuthAdmin->gift_type;
            $aTmp['gift_value'] = $oAuthAdmin->gift_value;
            $aTmp['is_captain'] = $oAuthAdmin->is_captain;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['sended_prize_at'] = $oAuthAdmin->sended_prize_at;
            $aTmp['verified_at'] = $oAuthAdmin->verified_at;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['gift_value_text'] = $oAuthAdmin->gift_value_text;
            $aTmp['already_received_num'] = $oAuthAdmin->already_received_num;


            $aFinal[] = $aTmp;
        }

        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $aFinal;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        return response()->json($aFinal);
        return ResultVo::success($res);
    }
}