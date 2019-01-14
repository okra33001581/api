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

class ReportController extends Controller
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
    public function profitIndex()
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
        $oAuthAdminList = DB::table('profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['tester_deposit'] = $oAuthAdmin->tester_deposit;
            $aTmp['net_deposit'] = $oAuthAdmin->net_deposit;
            $aTmp['first_deposit'] = $oAuthAdmin->first_deposit;
            $aTmp['first_deposit_count'] = $oAuthAdmin->first_deposit_count;
            $aTmp['withdrawal'] = $oAuthAdmin->withdrawal;
            $aTmp['tester_withdrawal'] = $oAuthAdmin->tester_withdrawal;
            $aTmp['net_withdrawal'] = $oAuthAdmin->net_withdrawal;
            $aTmp['prj_count'] = $oAuthAdmin->prj_count;
            $aTmp['tester_prj_count'] = $oAuthAdmin->tester_prj_count;
            $aTmp['net_prj_count'] = $oAuthAdmin->net_prj_count;
            $aTmp['share'] = $oAuthAdmin->share;
            $aTmp['tester_share'] = $oAuthAdmin->tester_share;
            $aTmp['net_share'] = $oAuthAdmin->net_share;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['tester_turnover'] = $oAuthAdmin->tester_turnover;
            $aTmp['tester_prize'] = $oAuthAdmin->tester_prize;
            $aTmp['tester_bonus'] = $oAuthAdmin->tester_bonus;
            $aTmp['tester_commission'] = $oAuthAdmin->tester_commission;
            $aTmp['tester_lose_commission'] = $oAuthAdmin->tester_lose_commission;
            $aTmp['tester_profit'] = $oAuthAdmin->tester_profit;
            $aTmp['net_turnover'] = $oAuthAdmin->net_turnover;
            $aTmp['net_prize'] = $oAuthAdmin->net_prize;
            $aTmp['net_bonus'] = $oAuthAdmin->net_bonus;
            $aTmp['net_commission'] = $oAuthAdmin->net_commission;
            $aTmp['net_lose_commission'] = $oAuthAdmin->net_lose_commission;
            $aTmp['net_profit'] = $oAuthAdmin->net_profit;
            $aTmp['profit_margin'] = $oAuthAdmin->profit_margin;
            $aTmp['registered_count'] = $oAuthAdmin->registered_count;
            $aTmp['registered_top_agent_count'] = $oAuthAdmin->registered_top_agent_count;
            $aTmp['signed_count'] = $oAuthAdmin->signed_count;
            $aTmp['bought_count'] = $oAuthAdmin->bought_count;
            $aTmp['signed_users'] = $oAuthAdmin->signed_users;
            $aTmp['bought_users'] = $oAuthAdmin->bought_users;
            $aTmp['user_avg_turnover'] = $oAuthAdmin->user_avg_turnover;
            $aTmp['prj_avg_turnover'] = $oAuthAdmin->prj_avg_turnover;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;

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
    public function issueProfitsIndex()
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
        $oAuthAdminList = DB::table('issue_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
            $aTmp['issue'] = $oAuthAdmin->issue;
            $aTmp['end_time'] = $oAuthAdmin->end_time;
            $aTmp['prj_count'] = $oAuthAdmin->prj_count;
            $aTmp['tester_prj_count'] = $oAuthAdmin->tester_prj_count;
            $aTmp['net_prj_count'] = $oAuthAdmin->net_prj_count;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['tester_turnover'] = $oAuthAdmin->tester_turnover;
            $aTmp['tester_prize'] = $oAuthAdmin->tester_prize;
            $aTmp['tester_commission'] = $oAuthAdmin->tester_commission;
            $aTmp['tester_profit'] = $oAuthAdmin->tester_profit;
            $aTmp['net_turnover'] = $oAuthAdmin->net_turnover;
            $aTmp['net_prize'] = $oAuthAdmin->net_prize;
            $aTmp['net_commission'] = $oAuthAdmin->net_commission;
            $aTmp['net_profit'] = $oAuthAdmin->net_profit;
            $aTmp['profit_margin'] = $oAuthAdmin->profit_margin;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['end_time2'] = $oAuthAdmin->end_time2;
            $aTmp['end_time2'] = $oAuthAdmin->end_time2;


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
    public function lotteryProfitsIndex()
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
        $oAuthAdminList = DB::table('lottery_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['series_id'] = $oAuthAdmin->series_id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
            $aTmp['prj_count'] = $oAuthAdmin->prj_count;
            $aTmp['tester_prj_count'] = $oAuthAdmin->tester_prj_count;
            $aTmp['net_prj_count'] = $oAuthAdmin->net_prj_count;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['tester_turnover'] = $oAuthAdmin->tester_turnover;
            $aTmp['tester_prize'] = $oAuthAdmin->tester_prize;
            $aTmp['tester_commission'] = $oAuthAdmin->tester_commission;
            $aTmp['tester_profit'] = $oAuthAdmin->tester_profit;
            $aTmp['net_turnover'] = $oAuthAdmin->net_turnover;
            $aTmp['net_prize'] = $oAuthAdmin->net_prize;
            $aTmp['net_commission'] = $oAuthAdmin->net_commission;
            $aTmp['net_profit'] = $oAuthAdmin->net_profit;
            $aTmp['profit_margin'] = $oAuthAdmin->profit_margin;
            $aTmp['turnover_ratio'] = $oAuthAdmin->turnover_ratio;
            $aTmp['bought_users'] = $oAuthAdmin->bought_users;
            $aTmp['bought_count'] = $oAuthAdmin->bought_count;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;



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
    public function monthProfitsIndex()
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
        $oAuthAdminList = DB::table('month_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['year'] = $oAuthAdmin->year;
            $aTmp['month'] = $oAuthAdmin->month;
            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['tester_deposit'] = $oAuthAdmin->tester_deposit;
            $aTmp['net_deposit'] = $oAuthAdmin->net_deposit;
            $aTmp['withdrawal'] = $oAuthAdmin->withdrawal;
            $aTmp['tester_withdrawal'] = $oAuthAdmin->tester_withdrawal;
            $aTmp['net_withdrawal'] = $oAuthAdmin->net_withdrawal;
            $aTmp['prj_count'] = $oAuthAdmin->prj_count;
            $aTmp['tester_prj_count'] = $oAuthAdmin->tester_prj_count;
            $aTmp['net_prj_count'] = $oAuthAdmin->net_prj_count;
            $aTmp['share'] = $oAuthAdmin->share;
            $aTmp['tester_share'] = $oAuthAdmin->tester_share;
            $aTmp['net_share'] = $oAuthAdmin->net_share;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['tester_turnover'] = $oAuthAdmin->tester_turnover;
            $aTmp['tester_prize'] = $oAuthAdmin->tester_prize;
            $aTmp['tester_bonus'] = $oAuthAdmin->tester_bonus;
            $aTmp['tester_commission'] = $oAuthAdmin->tester_commission;
            $aTmp['tester_lose_commission'] = $oAuthAdmin->tester_lose_commission;
            $aTmp['tester_profit'] = $oAuthAdmin->tester_profit;
            $aTmp['net_turnover'] = $oAuthAdmin->net_turnover;
            $aTmp['net_prize'] = $oAuthAdmin->net_prize;
            $aTmp['net_bonus'] = $oAuthAdmin->net_bonus;
            $aTmp['net_commission'] = $oAuthAdmin->net_commission;
            $aTmp['net_lose_commission'] = $oAuthAdmin->net_lose_commission;
            $aTmp['net_profit'] = $oAuthAdmin->net_profit;
            $aTmp['profit_margin'] = $oAuthAdmin->profit_margin;
            $aTmp['days'] = $oAuthAdmin->days;
            $aTmp['counted_days'] = $oAuthAdmin->counted_days;
            $aTmp['day_avg_turnover'] = $oAuthAdmin->day_avg_turnover;
            $aTmp['registered_count'] = $oAuthAdmin->registered_count;
            $aTmp['registered_top_agent_count'] = $oAuthAdmin->registered_top_agent_count;
            $aTmp['signed_count'] = $oAuthAdmin->signed_count;
            $aTmp['bought_count'] = $oAuthAdmin->bought_count;
            $aTmp['user_avg_turnover'] = $oAuthAdmin->user_avg_turnover;
            $aTmp['prj_avg_turnover'] = $oAuthAdmin->prj_avg_turnover;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;




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
    public function lotteryMonthProfitsIndex()
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
        $oAuthAdminList = DB::table('lottery_month_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['year'] = $oAuthAdmin->year;
            $aTmp['month'] = $oAuthAdmin->month;
            $aTmp['series_id'] = $oAuthAdmin->series_id;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
            $aTmp['prj_count'] = $oAuthAdmin->prj_count;
            $aTmp['tester_prj_count'] = $oAuthAdmin->tester_prj_count;
            $aTmp['net_prj_count'] = $oAuthAdmin->net_prj_count;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['tester_turnover'] = $oAuthAdmin->tester_turnover;
            $aTmp['tester_prize'] = $oAuthAdmin->tester_prize;
            $aTmp['tester_commission'] = $oAuthAdmin->tester_commission;
            $aTmp['tester_profit'] = $oAuthAdmin->tester_profit;
            $aTmp['net_turnover'] = $oAuthAdmin->net_turnover;
            $aTmp['net_prize'] = $oAuthAdmin->net_prize;
            $aTmp['net_commission'] = $oAuthAdmin->net_commission;
            $aTmp['net_profit'] = $oAuthAdmin->net_profit;
            $aTmp['profit_margin'] = $oAuthAdmin->profit_margin;
            $aTmp['turnover_ratio'] = $oAuthAdmin->turnover_ratio;
            $aTmp['bought_count'] = $oAuthAdmin->bought_count;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;





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
    public function teamProfitsIndex()
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
        $oAuthAdminList = DB::table('team_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['prize_group'] = $oAuthAdmin->prize_group;
            $aTmp['user_level'] = $oAuthAdmin->user_level;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['parent_user_id'] = $oAuthAdmin->parent_user_id;
            $aTmp['parent_user'] = $oAuthAdmin->parent_user;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['user_forefathers'] = $oAuthAdmin->user_forefathers;
            $aTmp['registered_count'] = $oAuthAdmin->registered_count;
            $aTmp['first_deposit'] = $oAuthAdmin->first_deposit;
            $aTmp['first_deposit_count'] = $oAuthAdmin->first_deposit_count;
            $aTmp['team_registered_count'] = $oAuthAdmin->team_registered_count;
            $aTmp['team_first_deposit'] = $oAuthAdmin->team_first_deposit;
            $aTmp['team_first_deposit_count'] = $oAuthAdmin->team_first_deposit_count;
            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['deposit_times'] = $oAuthAdmin->deposit_times;
            $aTmp['withdrawal'] = $oAuthAdmin->withdrawal;
            $aTmp['withdraw_times'] = $oAuthAdmin->withdraw_times;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;






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
    public function userProfitsIndex()
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
        $oAuthAdminList = DB::table('user_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['prize_group'] = $oAuthAdmin->prize_group;
            $aTmp['user_level'] = $oAuthAdmin->user_level;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['parent_user_id'] = $oAuthAdmin->parent_user_id;
            $aTmp['parent_user'] = $oAuthAdmin->parent_user;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['user_forefathers'] = $oAuthAdmin->user_forefathers;
            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['deposit_times'] = $oAuthAdmin->deposit_times;
            $aTmp['withdrawal'] = $oAuthAdmin->withdrawal;
            $aTmp['withdraw_times'] = $oAuthAdmin->withdraw_times;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;







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
    public function userMonthProfitsIndex()
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
        $oAuthAdminList = DB::table('user_month_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['year'] = $oAuthAdmin->year;
            $aTmp['month'] = $oAuthAdmin->month;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['prize_group'] = $oAuthAdmin->prize_group;
            $aTmp['user_level'] = $oAuthAdmin->user_level;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['parent_user_id'] = $oAuthAdmin->parent_user_id;
            $aTmp['parent_user'] = $oAuthAdmin->parent_user;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['user_forefathers'] = $oAuthAdmin->user_forefathers;
            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['deposit_times'] = $oAuthAdmin->deposit_times;
            $aTmp['withdrawal'] = $oAuthAdmin->withdrawal;
            $aTmp['withdraw_times'] = $oAuthAdmin->withdraw_times;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;








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
    public function teamMonthProfitsIndex()
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
        $oAuthAdminList = DB::table('team_month_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['year'] = $oAuthAdmin->year;
            $aTmp['month'] = $oAuthAdmin->month;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['prize_group'] = $oAuthAdmin->prize_group;
            $aTmp['user_level'] = $oAuthAdmin->user_level;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['parent_user_id'] = $oAuthAdmin->parent_user_id;
            $aTmp['parent_user'] = $oAuthAdmin->parent_user;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['user_forefathers'] = $oAuthAdmin->user_forefathers;
            $aTmp['deposit'] = $oAuthAdmin->deposit;
            $aTmp['deposit_times'] = $oAuthAdmin->deposit_times;
            $aTmp['withdrawal'] = $oAuthAdmin->withdrawal;
            $aTmp['withdraw_times'] = $oAuthAdmin->withdraw_times;
            $aTmp['team_registered_count'] = $oAuthAdmin->team_registered_count;
            $aTmp['team_first_deposit'] = $oAuthAdmin->team_first_deposit;
            $aTmp['team_first_deposit_count'] = $oAuthAdmin->team_first_deposit_count;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;









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
    public function userLotteryProfitsIndex()
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
        $oAuthAdminList = DB::table('user_lottery_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['game_type'] = $oAuthAdmin->game_type;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['prize_group'] = $oAuthAdmin->prize_group;
            $aTmp['user_level'] = $oAuthAdmin->user_level;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['parent_user_id'] = $oAuthAdmin->parent_user_id;
            $aTmp['parent_user'] = $oAuthAdmin->parent_user;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['user_forefathers'] = $oAuthAdmin->user_forefathers;
            $aTmp['series_id'] = $oAuthAdmin->series_id;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;










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
    public function teamLotteryProfitsIndex()
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
        $oAuthAdminList = DB::table('team_lottery_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['game_type'] = $oAuthAdmin->game_type;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['prize_group'] = $oAuthAdmin->prize_group;
            $aTmp['user_level'] = $oAuthAdmin->user_level;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['parent_user_id'] = $oAuthAdmin->parent_user_id;
            $aTmp['parent_user'] = $oAuthAdmin->parent_user;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['user_forefathers'] = $oAuthAdmin->user_forefathers;
            $aTmp['series_id'] = $oAuthAdmin->series_id;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;











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
    public function wayProfitsIndex()
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
        $oAuthAdminList = DB::table('way_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['series_id'] = $oAuthAdmin->series_id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['way_id'] = $oAuthAdmin->way_id;
            $aTmp['way'] = $oAuthAdmin->way;
            $aTmp['prj_count'] = $oAuthAdmin->prj_count;
            $aTmp['tester_prj_count'] = $oAuthAdmin->tester_prj_count;
            $aTmp['net_prj_count'] = $oAuthAdmin->net_prj_count;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['tester_turnover'] = $oAuthAdmin->tester_turnover;
            $aTmp['tester_prize'] = $oAuthAdmin->tester_prize;
            $aTmp['tester_commission'] = $oAuthAdmin->tester_commission;
            $aTmp['tester_profit'] = $oAuthAdmin->tester_profit;
            $aTmp['net_turnover'] = $oAuthAdmin->net_turnover;
            $aTmp['net_prize'] = $oAuthAdmin->net_prize;
            $aTmp['net_commission'] = $oAuthAdmin->net_commission;
            $aTmp['net_profit'] = $oAuthAdmin->net_profit;
            $aTmp['profit_margin'] = $oAuthAdmin->profit_margin;
            $aTmp['turnover_ratio'] = $oAuthAdmin->turnover_ratio;
            $aTmp['bought_users'] = $oAuthAdmin->bought_users;
            $aTmp['bought_count'] = $oAuthAdmin->bought_count;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;












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
    public function lotteryWayProfitsIndex()
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
        $oAuthAdminList = DB::table('lottery_way_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['series_id'] = $oAuthAdmin->series_id;
            $aTmp['game_type'] = $oAuthAdmin->game_type;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
            $aTmp['way_id'] = $oAuthAdmin->way_id;
            $aTmp['way'] = $oAuthAdmin->way;
            $aTmp['prj_count'] = $oAuthAdmin->prj_count;
            $aTmp['tester_prj_count'] = $oAuthAdmin->tester_prj_count;
            $aTmp['net_prj_count'] = $oAuthAdmin->net_prj_count;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['tester_turnover'] = $oAuthAdmin->tester_turnover;
            $aTmp['tester_prize'] = $oAuthAdmin->tester_prize;
            $aTmp['tester_commission'] = $oAuthAdmin->tester_commission;
            $aTmp['tester_profit'] = $oAuthAdmin->tester_profit;
            $aTmp['net_turnover'] = $oAuthAdmin->net_turnover;
            $aTmp['net_prize'] = $oAuthAdmin->net_prize;
            $aTmp['net_commission'] = $oAuthAdmin->net_commission;
            $aTmp['net_profit'] = $oAuthAdmin->net_profit;
            $aTmp['profit_margin'] = $oAuthAdmin->profit_margin;
            $aTmp['turnover_ratio'] = $oAuthAdmin->turnover_ratio;
            $aTmp['bought_users'] = $oAuthAdmin->bought_users;
            $aTmp['bought_count'] = $oAuthAdmin->bought_count;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;













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
    public function userLotteryWayProfitsIndex()
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
        $oAuthAdminList = DB::table('user_lottery_way_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['game_type'] = $oAuthAdmin->game_type;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['prize_group'] = $oAuthAdmin->prize_group;
            $aTmp['user_level'] = $oAuthAdmin->user_level;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['parent_user_id'] = $oAuthAdmin->parent_user_id;
            $aTmp['parent_user'] = $oAuthAdmin->parent_user;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['user_forefathers'] = $oAuthAdmin->user_forefathers;
            $aTmp['series_id'] = $oAuthAdmin->series_id;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
            $aTmp['way_id'] = $oAuthAdmin->way_id;
            $aTmp['way'] = $oAuthAdmin->way;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;






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
    public function teamLotteryWayProfitsIndex()
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
        $oAuthAdminList = DB::table('team_lottery_way_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['game_type'] = $oAuthAdmin->game_type;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['prize_group'] = $oAuthAdmin->prize_group;
            $aTmp['user_level'] = $oAuthAdmin->user_level;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['parent_user_id'] = $oAuthAdmin->parent_user_id;
            $aTmp['parent_user'] = $oAuthAdmin->parent_user;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['user_forefathers'] = $oAuthAdmin->user_forefathers;
            $aTmp['series_id'] = $oAuthAdmin->series_id;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
            $aTmp['way_id'] = $oAuthAdmin->way_id;
            $aTmp['way'] = $oAuthAdmin->way;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;







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
    public function terminalProfitsIndex()
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
        $oAuthAdminList = DB::table('terminal_profits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(2)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(2)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {

            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['terminal_id'] = $oAuthAdmin->terminal_id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['prj_count'] = $oAuthAdmin->prj_count;
            $aTmp['tester_prj_count'] = $oAuthAdmin->tester_prj_count;
            $aTmp['net_prj_count'] = $oAuthAdmin->net_prj_count;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['tester_turnover'] = $oAuthAdmin->tester_turnover;
            $aTmp['tester_prize'] = $oAuthAdmin->tester_prize;
            $aTmp['tester_commission'] = $oAuthAdmin->tester_commission;
            $aTmp['tester_profit'] = $oAuthAdmin->tester_profit;
            $aTmp['net_turnover'] = $oAuthAdmin->net_turnover;
            $aTmp['net_prize'] = $oAuthAdmin->net_prize;
            $aTmp['net_commission'] = $oAuthAdmin->net_commission;
            $aTmp['net_profit'] = $oAuthAdmin->net_profit;
            $aTmp['profit_margin'] = $oAuthAdmin->profit_margin;
            $aTmp['turnover_ratio'] = $oAuthAdmin->turnover_ratio;
            $aTmp['bought_user_ratio'] = $oAuthAdmin->bought_user_ratio;
            $aTmp['signed_count'] = $oAuthAdmin->signed_count;
            $aTmp['bought_count'] = $oAuthAdmin->bought_count;
            $aTmp['signed_users'] = $oAuthAdmin->signed_users;
            $aTmp['bought_users'] = $oAuthAdmin->bought_users;
            $aTmp['user_avg_turnover'] = $oAuthAdmin->user_avg_turnover;
            $aTmp['prj_avg_turnover'] = $oAuthAdmin->prj_avg_turnover;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;








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