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

class FundController extends Controller
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
    public function accoundIndex()
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
        $oAuthAdminList = DB::table('accounts');

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
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['balance'] = $oAuthAdmin->balance;
            $aTmp['frozen'] = $oAuthAdmin->frozen;
            $aTmp['available'] = $oAuthAdmin->available;
            $aTmp['withdrawable'] = $oAuthAdmin->withdrawable;
            $aTmp['prohibit_amount'] = $oAuthAdmin->prohibit_amount;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['locked'] = $oAuthAdmin->locked;
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
    public function transactionIndex()
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
        $oAuthAdminList = DB::table('transactions');

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
            $aTmp['serial_number'] = $oAuthAdmin->serial_number;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['top_agent_id'] = $oAuthAdmin->top_agent_id;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['account_id'] = $oAuthAdmin->account_id;
            $aTmp['type_id'] = $oAuthAdmin->type_id;
            $aTmp['is_income'] = $oAuthAdmin->is_income;
            $aTmp['trace_id'] = $oAuthAdmin->trace_id;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
            $aTmp['issue'] = $oAuthAdmin->issue;
            $aTmp['method_id'] = $oAuthAdmin->method_id;
            $aTmp['way_id'] = $oAuthAdmin->way_id;
            $aTmp['coefficient'] = $oAuthAdmin->coefficient;
            $aTmp['description'] = $oAuthAdmin->description;
            $aTmp['project_id'] = $oAuthAdmin->project_id;
            $aTmp['project_no'] = $oAuthAdmin->project_no;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['note'] = $oAuthAdmin->note;
            $aTmp['previous_balance'] = $oAuthAdmin->previous_balance;
            $aTmp['previous_frozen'] = $oAuthAdmin->previous_frozen;
            $aTmp['previous_available'] = $oAuthAdmin->previous_available;
            $aTmp['previous_withdrawable'] = $oAuthAdmin->previous_withdrawable;
            $aTmp['previous_prohibit_amount'] = $oAuthAdmin->previous_prohibit_amount;
            $aTmp['balance'] = $oAuthAdmin->balance;
            $aTmp['frozen'] = $oAuthAdmin->frozen;
            $aTmp['available'] = $oAuthAdmin->available;
            $aTmp['withdrawable'] = $oAuthAdmin->withdrawable;
            $aTmp['prohibit_amount'] = $oAuthAdmin->prohibit_amount;
            $aTmp['balance'] = $oAuthAdmin->balance;
            $aTmp['frozen'] = $oAuthAdmin->frozen;
            $aTmp['available'] = $oAuthAdmin->available;
            $aTmp['withdrawable'] = $oAuthAdmin->withdrawable;
            $aTmp['prohibit_amount'] = $oAuthAdmin->prohibit_amount;
            $aTmp['available'] = $oAuthAdmin->available;
            $aTmp['withdrawable'] = $oAuthAdmin->withdrawable;
            $aTmp['prohibit_amount'] = $oAuthAdmin->prohibit_amount;
            $aTmp['available'] = $oAuthAdmin->available;
            $aTmp['withdrawable'] = $oAuthAdmin->withdrawable;


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
    public function dispositIndex()
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
        $oAuthAdminList = DB::table('deposits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(11)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(11)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['deposit_mode'] = $oAuthAdmin->deposit_mode;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['realname'] = $oAuthAdmin->realname;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['top_agent_id'] = $oAuthAdmin->top_agent_id;
            $aTmp['top_agent'] = $oAuthAdmin->top_agent;
            $aTmp['user_parent'] = $oAuthAdmin->user_parent;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['platform_id'] = $oAuthAdmin->platform_id;
            $aTmp['platform'] = $oAuthAdmin->platform;
            $aTmp['platform_identifier'] = $oAuthAdmin->platform_identifier;
            $aTmp['query_enabled'] = $oAuthAdmin->query_enabled;
            $aTmp['bank_id'] = $oAuthAdmin->bank_id;
            $aTmp['bank_no'] = $oAuthAdmin->bank_no;
            $aTmp['bank'] = $oAuthAdmin->bank;
            $aTmp['bank_identifier'] = $oAuthAdmin->bank_identifier;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['order_no'] = $oAuthAdmin->order_no;
            $aTmp['merchant_key'] = $oAuthAdmin->merchant_key;
            $aTmp['merchant_code'] = $oAuthAdmin->merchant_code;
            $aTmp['account_no'] = $oAuthAdmin->account_no;
            $aTmp['sign'] = $oAuthAdmin->sign;
            $aTmp['ip'] = $oAuthAdmin->ip;
            $aTmp['web_url'] = $oAuthAdmin->web_url;
            $aTmp['postscript'] = $oAuthAdmin->postscript;
            $aTmp['transaction_pic_url'] = $oAuthAdmin->transaction_pic_url;
            $aTmp['service_order_no'] = $oAuthAdmin->service_order_no;
            $aTmp['service_time'] = $oAuthAdmin->service_time;
            $aTmp['service_order_status'] = $oAuthAdmin->service_order_status;
            $aTmp['service_bank_seq_no'] = $oAuthAdmin->service_bank_seq_no;
            $aTmp['notify_type'] = $oAuthAdmin->notify_type;
            $aTmp['notify_data'] = $oAuthAdmin->notify_data;
            $aTmp['collection_bank_id'] = $oAuthAdmin->collection_bank_id;
            $aTmp['accept_card_num'] = $oAuthAdmin->accept_card_num;
            $aTmp['accept_email'] = $oAuthAdmin->accept_email;
            $aTmp['accept_acc_name'] = $oAuthAdmin->accept_acc_name;
            $aTmp['real_amount'] = $oAuthAdmin->real_amount;
            $aTmp['fee'] = $oAuthAdmin->fee;
            $aTmp['pay_time'] = $oAuthAdmin->pay_time;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['status_commission'] = $oAuthAdmin->status_commission;
            $aTmp['error_msg'] = $oAuthAdmin->error_msg;
            $aTmp['mode'] = $oAuthAdmin->mode;
            $aTmp['break_url'] = $oAuthAdmin->break_url;
            $aTmp['service_token'] = $oAuthAdmin->service_token;
            $aTmp['put_at'] = $oAuthAdmin->put_at;
            $aTmp['accepter_id'] = $oAuthAdmin->accepter_id;
            $aTmp['accepter'] = $oAuthAdmin->accepter;
            $aTmp['accepted_at'] = $oAuthAdmin->accepted_at;
            $aTmp['commission_sent_at'] = $oAuthAdmin->commission_sent_at;
            $aTmp['note'] = $oAuthAdmin->note;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['verify_accepter_id'] = $oAuthAdmin->verify_accepter_id;
            $aTmp['verify_accepter'] = $oAuthAdmin->verify_accepter;
            $aTmp['verify_accepted_at'] = $oAuthAdmin->verify_accepted_at;
            $aTmp['terminal_id'] = $oAuthAdmin->terminal_id;


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
    public function bankDepositIndex()
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
        $oAuthAdminList = DB::table('bank_deposits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(11)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(11)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['realname'] = $oAuthAdmin->realname;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['top_agent_id'] = $oAuthAdmin->top_agent_id;
            $aTmp['top_agent'] = $oAuthAdmin->top_agent;
            $aTmp['user_parent'] = $oAuthAdmin->user_parent;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['platform_id'] = $oAuthAdmin->platform_id;
            $aTmp['platform'] = $oAuthAdmin->platform;
            $aTmp['platform_identifier'] = $oAuthAdmin->platform_identifier;
            $aTmp['bank_id'] = $oAuthAdmin->bank_id;
            $aTmp['bank_no'] = $oAuthAdmin->bank_no;
            $aTmp['bank'] = $oAuthAdmin->bank;
            $aTmp['bank_identifier'] = $oAuthAdmin->bank_identifier;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['order_no'] = $oAuthAdmin->order_no;
            $aTmp['ip'] = $oAuthAdmin->ip;
            $aTmp['postscript'] = $oAuthAdmin->postscript;
            $aTmp['payer_name'] = $oAuthAdmin->payer_name;
            $aTmp['transaction_pic_url'] = $oAuthAdmin->transaction_pic_url;
            $aTmp['service_order_no'] = $oAuthAdmin->service_order_no;
            $aTmp['service_time'] = $oAuthAdmin->service_time;
            $aTmp['service_bank_seq_no'] = $oAuthAdmin->service_bank_seq_no;
            $aTmp['bankcard_no'] = $oAuthAdmin->bankcard_no;
            $aTmp['accept_card_num'] = $oAuthAdmin->accept_card_num;
            $aTmp['accept_email'] = $oAuthAdmin->accept_email;
            $aTmp['accept_acc_name'] = $oAuthAdmin->accept_acc_name;
            $aTmp['fee'] = $oAuthAdmin->fee;
            $aTmp['pay_time'] = $oAuthAdmin->pay_time;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['status_commission'] = $oAuthAdmin->status_commission;
            $aTmp['error_msg'] = $oAuthAdmin->error_msg;
            $aTmp['mode'] = $oAuthAdmin->mode;
            $aTmp['put_at'] = $oAuthAdmin->put_at;
            $aTmp['accepter_id'] = $oAuthAdmin->accepter_id;
            $aTmp['accepter'] = $oAuthAdmin->accepter;
            $aTmp['accepted_at'] = $oAuthAdmin->accepted_at;
            $aTmp['pic_submited_at'] = $oAuthAdmin->pic_submited_at;
            $aTmp['commission_sent_at'] = $oAuthAdmin->commission_sent_at;
            $aTmp['note'] = $oAuthAdmin->note;
            $aTmp['auditor_id'] = $oAuthAdmin->auditor_id;
            $aTmp['auditor'] = $oAuthAdmin->auditor;
            $aTmp['verified_at'] = $oAuthAdmin->verified_at;
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
    public function exceptionDepositIndex()
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
        $oAuthAdminList = DB::table('exception_deposits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(11)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(11)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['deposit_mode'] = $oAuthAdmin->deposit_mode;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['is_agent'] = $oAuthAdmin->is_agent;
            $aTmp['top_agent_id'] = $oAuthAdmin->top_agent_id;
            $aTmp['top_agent'] = $oAuthAdmin->top_agent;
            $aTmp['user_parent'] = $oAuthAdmin->user_parent;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['platform_id'] = $oAuthAdmin->platform_id;
            $aTmp['platform'] = $oAuthAdmin->platform;
            $aTmp['platform_identifier'] = $oAuthAdmin->platform_identifier;
            $aTmp['query_enabled'] = $oAuthAdmin->query_enabled;
            $aTmp['bank_id'] = $oAuthAdmin->bank_id;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['order_no'] = $oAuthAdmin->order_no;
            $aTmp['merchant_key'] = $oAuthAdmin->merchant_key;
            $aTmp['merchant_code'] = $oAuthAdmin->merchant_code;
            $aTmp['sign'] = $oAuthAdmin->sign;
            $aTmp['ip'] = $oAuthAdmin->ip;
            $aTmp['web_url'] = $oAuthAdmin->web_url;
            $aTmp['postscript'] = $oAuthAdmin->postscript;
            $aTmp['transaction_pic_url'] = $oAuthAdmin->transaction_pic_url;
            $aTmp['service_order_no'] = $oAuthAdmin->service_order_no;
            $aTmp['service_time'] = $oAuthAdmin->service_time;
            $aTmp['service_order_status'] = $oAuthAdmin->service_order_status;
            $aTmp['service_bank_seq_no'] = $oAuthAdmin->service_bank_seq_no;
            $aTmp['notify_type'] = $oAuthAdmin->notify_type;
            $aTmp['notify_data'] = $oAuthAdmin->notify_data;
            $aTmp['collection_bank_id'] = $oAuthAdmin->collection_bank_id;
            $aTmp['accept_card_num'] = $oAuthAdmin->accept_card_num;
            $aTmp['accept_email'] = $oAuthAdmin->accept_email;
            $aTmp['accept_acc_name'] = $oAuthAdmin->accept_acc_name;
            $aTmp['real_amount'] = $oAuthAdmin->real_amount;
            $aTmp['fee'] = $oAuthAdmin->fee;
            $aTmp['pay_time'] = $oAuthAdmin->pay_time;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['status_commission'] = $oAuthAdmin->status_commission;
            $aTmp['error_msg'] = $oAuthAdmin->error_msg;
            $aTmp['mode'] = $oAuthAdmin->mode;
            $aTmp['break_url'] = $oAuthAdmin->break_url;
            $aTmp['service_token'] = $oAuthAdmin->service_token;
            $aTmp['put_at'] = $oAuthAdmin->put_at;
            $aTmp['accepter_id'] = $oAuthAdmin->accepter_id;
            $aTmp['accepter'] = $oAuthAdmin->accepter;
            $aTmp['accepted_at'] = $oAuthAdmin->accepted_at;
            $aTmp['commission_sent_at'] = $oAuthAdmin->commission_sent_at;
            $aTmp['note'] = $oAuthAdmin->note;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['verify_accepter_id'] = $oAuthAdmin->verify_accepter_id;
            $aTmp['verify_accepter'] = $oAuthAdmin->verify_accepter;
            $aTmp['verify_accepted_at'] = $oAuthAdmin->verify_accepted_at;

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
    public function manuDepositIndex()
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
        $oAuthAdminList = DB::table('manual_deposits');

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
        $oAuthAdminListCount = $oAuthAdminList->take(11)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(11)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['transaction_type_id'] = $oAuthAdmin->transaction_type_id;
            $aTmp['transaction_description'] = $oAuthAdmin->transaction_description;
            $aTmp['note'] = $oAuthAdmin->note;
            $aTmp['creator_id'] = $oAuthAdmin->creator_id;
            $aTmp['creator'] = $oAuthAdmin->creator;
            $aTmp['auditor_id'] = $oAuthAdmin->auditor_id;
            $aTmp['auditor'] = $oAuthAdmin->auditor;
            $aTmp['role_name'] = $oAuthAdmin->role_name;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['audited_at'] = $oAuthAdmin->audited_at;
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
    public function manuWithdrawalsIndex()
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
        $oAuthAdminList = DB::table('manual_withdrawals');

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
        $oAuthAdminListCount = $oAuthAdminList->take(11)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(11)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['transaction_type_id'] = $oAuthAdmin->transaction_type_id;
            $aTmp['transaction_description'] = $oAuthAdmin->transaction_description;
            $aTmp['note'] = $oAuthAdmin->note;
            $aTmp['creator_id'] = $oAuthAdmin->creator_id;
            $aTmp['creator'] = $oAuthAdmin->creator;
            $aTmp['auditor_id'] = $oAuthAdmin->auditor_id;
            $aTmp['auditor'] = $oAuthAdmin->auditor;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['audited_at'] = $oAuthAdmin->audited_at;
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
    public function manuDividends()
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
        $oAuthAdminList = DB::table('dividends');

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
        $oAuthAdminListCount = $oAuthAdminList->take(11)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(11)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['year'] = $oAuthAdmin->year;
            $aTmp['month'] = $oAuthAdmin->month;
            $aTmp['batch'] = $oAuthAdmin->batch;
            $aTmp['begin_date'] = $oAuthAdmin->begin_date;
            $aTmp['end_date'] = $oAuthAdmin->end_date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['valid_sales'] = $oAuthAdmin->valid_sales;
            $aTmp['prize'] = $oAuthAdmin->prize;
            $aTmp['bonus'] = $oAuthAdmin->bonus;
            $aTmp['commission'] = $oAuthAdmin->commission;
            $aTmp['lose_commission'] = $oAuthAdmin->lose_commission;
            $aTmp['profit'] = $oAuthAdmin->profit;
            $aTmp['rate'] = $oAuthAdmin->rate;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['auditor_id'] = $oAuthAdmin->auditor_id;
            $aTmp['auditor'] = $oAuthAdmin->auditor;
            $aTmp['verified_at'] = $oAuthAdmin->verified_at;
            $aTmp['sent_at'] = $oAuthAdmin->sent_at;
            $aTmp['note'] = $oAuthAdmin->note;
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
    public function loseCommissionsIndex()
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
        $oAuthAdminList = DB::table('lose_commissions');

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
        $oAuthAdminListCount = $oAuthAdminList->take(11)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(11)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['user_forefathers'] = $oAuthAdmin->user_forefathers;
            $aTmp['parent_user_id'] = $oAuthAdmin->parent_user_id;
            $aTmp['parent_user'] = $oAuthAdmin->parent_user;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['locked'] = $oAuthAdmin->locked;
            $aTmp['sent_at'] = $oAuthAdmin->sent_at;
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
    public function commissionsStatisticsIndex()
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
        $oAuthAdminList = DB::table('commissions_statistics');

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
        $oAuthAdminListCount = $oAuthAdminList->take(11)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(11)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['date'] = $oAuthAdmin->date;
            $aTmp['game_type'] = $oAuthAdmin->game_type;
            $aTmp['lottery_id'] = $oAuthAdmin->lottery_id;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['account_id'] = $oAuthAdmin->account_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['user_forefather_ids'] = $oAuthAdmin->user_forefather_ids;
            $aTmp['rate'] = $oAuthAdmin->rate;
            $aTmp['turnover'] = $oAuthAdmin->turnover;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['locked'] = $oAuthAdmin->locked;
            $aTmp['sent_at'] = $oAuthAdmin->sent_at;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['source_username'] = $oAuthAdmin->source_username;
            $aTmp['source_id'] = $oAuthAdmin->source_id;





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
    public function platTransferRecordsIndex()
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
        $oAuthAdminList = DB::table('plat_transfer_records');

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
        $oAuthAdminListCount = $oAuthAdminList->take(11)->get();
//        $oAuthAdminFinalList = $oAuthAdminList->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $oAuthAdminFinalList = $oAuthAdminList->take(11)->get();
        $aTmp = [];
        $aFinal = [];
        foreach ($oAuthAdminFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['plat_id'] = $oAuthAdmin->plat_id;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['bill_no'] = $oAuthAdmin->bill_no;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['type'] = $oAuthAdmin->type;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['api_return_data'] = $oAuthAdmin->api_return_data;
            $aTmp['acceptor_id'] = $oAuthAdmin->acceptor_id;
            $aTmp['acceptor'] = $oAuthAdmin->acceptor;
            $aTmp['accepted_at'] = $oAuthAdmin->accepted_at;
            $aTmp['description'] = $oAuthAdmin->description;
            $aTmp['verify_note'] = $oAuthAdmin->verify_note;
            $aTmp['pic_url'] = $oAuthAdmin->pic_url;
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
    public function withdrawalsIndex()
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
        $oAuthAdminList = DB::table('withdrawals');

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
            $aTmp['serial_number'] = $oAuthAdmin->serial_number;
            $aTmp['user_id'] = $oAuthAdmin->user_id;
            $aTmp['username'] = $oAuthAdmin->username;
            $aTmp['is_tester'] = $oAuthAdmin->is_tester;
            $aTmp['request_time'] = $oAuthAdmin->request_time;
            $aTmp['amount'] = $oAuthAdmin->amount;
            $aTmp['is_large'] = $oAuthAdmin->is_large;
            $aTmp['bank_id'] = $oAuthAdmin->bank_id;
            $aTmp['bank_no'] = $oAuthAdmin->bank_no;
            $aTmp['bank'] = $oAuthAdmin->bank;
            $aTmp['bank_identifier'] = $oAuthAdmin->bank_identifier;
            $aTmp['account'] = $oAuthAdmin->account;
            $aTmp['account_name'] = $oAuthAdmin->account_name;
            $aTmp['province'] = $oAuthAdmin->province;
            $aTmp['branch'] = $oAuthAdmin->branch;
            $aTmp['branch_address'] = $oAuthAdmin->branch_address;
            $aTmp['error_msg'] = $oAuthAdmin->error_msg;
            $aTmp['remark'] = $oAuthAdmin->remark;
            $aTmp['status'] = $oAuthAdmin->status;
            $aTmp['auditor_id'] = $oAuthAdmin->auditor_id;
            $aTmp['auditor'] = $oAuthAdmin->auditor;
            $aTmp['verified_time'] = $oAuthAdmin->verified_time;
            $aTmp['finish_time'] = $oAuthAdmin->finish_time;
            $aTmp['transaction_charge'] = $oAuthAdmin->transaction_charge;
            $aTmp['transaction_amount'] = $oAuthAdmin->transaction_amount;
            $aTmp['ip'] = $oAuthAdmin->ip;
            $aTmp['deleted_at'] = $oAuthAdmin->deleted_at;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['updated_at'] = $oAuthAdmin->updated_at;
            $aTmp['verify_accepter_id'] = $oAuthAdmin->verify_accepter_id;
            $aTmp['verify_accepter'] = $oAuthAdmin->verify_accepter;
            $aTmp['verify_accepted_at'] = $oAuthAdmin->verify_accepted_at;
            $aTmp['withdrawal_accepter'] = $oAuthAdmin->withdrawal_accepter;
            $aTmp['withdrawal_type'] = $oAuthAdmin->withdrawal_type;
            $aTmp['withdrawal_accepter_id'] = $oAuthAdmin->withdrawal_accepter_id;
            $aTmp['withdrawal_accepted_at'] = $oAuthAdmin->withdrawal_accepted_at;
            $aTmp['remittance_submited_at'] = $oAuthAdmin->remittance_submited_at;
            $aTmp['remittance_auditor'] = $oAuthAdmin->remittance_auditor;
            $aTmp['remittance_auditor_id'] = $oAuthAdmin->remittance_auditor_id;
            $aTmp['remittance_auditor_at'] = $oAuthAdmin->remittance_auditor_at;
            $aTmp['note'] = $oAuthAdmin->note;
            $aTmp['transaction_pic_url'] = $oAuthAdmin->transaction_pic_url;
            $aTmp['put_at'] = $oAuthAdmin->put_at;
            $aTmp['fin_account_id'] = $oAuthAdmin->fin_account_id;
            $aTmp['risk_items'] = $oAuthAdmin->risk_items;
            $aTmp['risk_details'] = $oAuthAdmin->risk_details;
            $aTmp['risk_checked'] = $oAuthAdmin->risk_checked;


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