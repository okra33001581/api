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

class AdController extends Controller
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
    public function adTypeIndex()
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
        $oAuthAdminList = DB::table('ad_types');

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
            $aTmp['type_name'] = $oAuthAdmin->type_name;
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
    public function adLocationIndex()
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
        $oAuthAdminList = DB::table('ad_locations');

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
            $aTmp['name'] = $oAuthAdmin->name;
            $aTmp['type_id'] = $oAuthAdmin->type_id;
            $aTmp['type_name'] = $oAuthAdmin->type_name;
            $aTmp['description'] = $oAuthAdmin->description;
            $aTmp['text_length'] = $oAuthAdmin->text_length;
            $aTmp['pic_width'] = $oAuthAdmin->pic_width;
            $aTmp['pic_height'] = $oAuthAdmin->pic_height;
            $aTmp['is_closed'] = $oAuthAdmin->is_closed;
            $aTmp['roll_time'] = $oAuthAdmin->roll_time;
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
    public function adInfosIndex()
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
        $oAuthAdminList = DB::table('ad_infos');

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
            $aTmp['ad_location_id'] = $oAuthAdmin->ad_location_id;
            $aTmp['name'] = $oAuthAdmin->name;
            $aTmp['content'] = $oAuthAdmin->content;
            $aTmp['pic_url'] = $oAuthAdmin->pic_url;
            $aTmp['is_closed'] = $oAuthAdmin->is_closed;
            $aTmp['redirect_url'] = $oAuthAdmin->redirect_url;
            $aTmp['notice_id'] = $oAuthAdmin->notice_id;
            $aTmp['creator_id'] = $oAuthAdmin->creator_id;
            $aTmp['creator'] = $oAuthAdmin->creator;
            $aTmp['sequence'] = $oAuthAdmin->sequence;
            $aTmp['created_at'] = $oAuthAdmin->created_at;
            $aTmp['end_time'] = $oAuthAdmin->end_time;
            $aTmp['start_time'] = $oAuthAdmin->start_time;
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