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
use App\model\ProxyConfiguration;
use App\model\AdminLog;
use Illuminate\Support\Facades\Redis;
class DelegateController extends Controller
{
    public function getJson()
    {
        // 从文件中读取数据到PHP变量
        $json_string = file_get_contents('/home/ok/api/app/Http/Controllers/Auz/data.json');
        return $json_string;
    }
    /**
     *代理默认配额设置
     */
    public function proxycommissionList()
    {
        // $sWhere = [];
        // $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        
        $proxycommissionList = DB::table('delegate_quota');
        if ($merchant_name !== '') {
            $proxycommissionList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }
        $iLimit = request()->get('limit/d', 20);
        $proxycommissionFinalList = $proxycommissionList->orderby('id', 'desc')->paginate($iLimit);

       /* $aTmp = [];
        $aFinal = [];
        foreach ($proxycommissionFinalList as $oAuthAdmin) {
            $aTmp['id'] = $oAuthAdmin->id;
            $aTmp['level_name'] = $oAuthAdmin->level_name;
            $aTmp['deposit_times'] = $oAuthAdmin->deposit_times;
            $aTmp['deposit_amount'] = $oAuthAdmin->deposit_amount;
            $aTmp['deposit_max'] = $oAuthAdmin->deposit_max;
            $aTmp['withdraw_times'] = $oAuthAdmin->withdraw_times;
            $aTmp['withdraw_amount'] = $oAuthAdmin->withdraw_amount;
            $aTmp['prior'] = $oAuthAdmin->prior;
            $aTmp['memo'] = $oAuthAdmin->memo;
            $aTmp['pay_setting'] = $oAuthAdmin->pay_setting;
            $aTmp['project_limit'] = $oAuthAdmin->project_limit;
            $aFinal[] = $aTmp;
        }*/

        $res = [];
        $res["total"] = count($proxycommissionFinalList);
        $res["list"] = $proxycommissionFinalList->toArray();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;


        $sOperateName = 'proxycommissionList';
        $sLogContent = 'proxycommissionList';


        $dt = now();



        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    // 添加数据
    public function proxycommissionSave()
    {
        $data = request()->post();
        $id = isset($data['id']) ? $data['id'] : '';
        $merchant_id = isset($data['merchant_id']) ? $data['merchant_id'] : '';
        $delegate_level = isset($data['delegate_level']) ? $data['delegate_level'] : '';
        $rebate = isset($data['rebate']) ? $data['rebate'] : '';
        $default_quota = isset($data['default_quota']) ? $data['default_quota'] : '';

        if ($id != '') {
            $ProxyConfiguration = ProxyConfiguration::find($id);
        }else{
            $ProxyConfiguration = new ProxyConfiguration();
        }

//      $ProxyConfiguration->id = $iId;

        $ProxyConfiguration->merchant_id = $merchant_id;
        $ProxyConfiguration->delegate_level = $delegate_level;
        $ProxyConfiguration->rebate = $rebate;
        $ProxyConfiguration->default_quota = $default_quota;

        $iRet = $ProxyConfiguration->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $ProxyConfiguration;

        $sOperateName = 'proxycommissionSave';
        $sLogContent = 'proxycommissionSave';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 数据取得
     * @param request
     * @return json
     */
    public function proxycommissionDelete()
    {
        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';

        $oIpBlack = ProxyConfiguration::where('id',$iId)->delete();
        if ($oIpBlack) {
            $sMessage = '删除成功！';
        } else {
            $sMessage = '删除失败！';
        }

        $aFinal['message'] = $sMessage;
        $aFinal['code'] = 0;
        $aFinal['data'] = $oIpBlack;

        $sOperateName = 'proxycommissionDelete';
        $sLogContent = 'proxycommissionDelete';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     *代理推广链接
     */
   
    public function proxycommissionProxylist()
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
        $oAuthAdminList = DB::table('delegate_sponsor_links');
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
        // foreach ($oAuthAdminFinalList as $oAuthAdmin) {
        //     $oAuthAdmin->avatar = PublicFileUtils::createUploadUrl($oAuthAdmin->avatar);
        //     $aTmp['id'] = $oAuthAdmin->id;
        //     $aTmp['username'] = $oAuthAdmin->username;
        //     $aTmp['password'] = $oAuthAdmin->password;
        //     $aTmp['tel'] = $oAuthAdmin->tel;
        //     $aTmp['email'] = $oAuthAdmin->email;
        //     $aTmp['avatar'] = $oAuthAdmin->avatar;
        //     $aTmp['sex'] = $oAuthAdmin->sex;
        //     $aTmp['last_login_ip'] = $oAuthAdmin->last_login_ip;
        //     $aTmp['last_login_time'] = $oAuthAdmin->last_login_time;
        //     $aTmp['create_time'] = $oAuthAdmin->create_time;
        //     $aTmp['status'] = $oAuthAdmin->status;
        //     $aTmp['updated_at'] = $oAuthAdmin->updated_at;
        //     $aTmp['created_at'] = $oAuthAdmin->created_at;
        //     $roles = AuthRoleAdmin::where('admin_id', $oAuthAdmin->id)->first();
        //     $temp_roles = [];
        //     if (is_object($roles)) {
        //         $temp_roles = $roles->toArray();
        //         $temp_roles = array_column($temp_roles, 'role_id');
        //     }
        //     $aTmp['roles'] = $temp_roles;
        //     $aFinal[] = $aTmp;
        // }
        $res = [];
        $res["total"] = count($oAuthAdminListCount);
        $res["list"] = $oAuthAdminFinalList;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;
        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function adminSave()
    {
        $data = request()->post();
        if (empty($data['username']) || empty($data['password'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $sUsername = $data['username'];
        // 模型
//        $info = AuthAdmin::where('username',$sUsername)
//            ->field('username')
//            ->find();
        $oAuthAdmin = AuthAdmin::where('username', $sUsername)
            ->first();
//        if ($oAuthAdmin){
//            return ResultVo::error(ErrorCode::DATA_REPEAT);
//        }
        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_admin = new AuthAdmin();
        $auth_admin->username = $sUsername;
        $auth_admin->password = PassWordUtils::create($data['password']);
        $auth_admin->status = $status;
        $auth_admin->create_time = date("Y-m-d H:i:s");
        $result = $auth_admin->save();
        if (!$result) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];
        //$adminInfo = $this->adminInfo; // 登录用户信息
        $admin_id = $auth_admin->id;
        if ($roles) {
            $temp = [];
            foreach ($roles as $key => $value) {
                $temp[$key]['role_id'] = $value;
                $temp[$key]['admin_id'] = $admin_id;
            }
            //添加用户的角色
            if (count($temp) > 0) {
                foreach ($temp as $k => $v) {
                    $oAuthRoleAdmin = new AuthRoleAdmin();
                    $oAuthRoleAdmin->role_id = $v['role_id'];
                    $oAuthRoleAdmin->admin_id = $v['admin_id'];
                    $iRet = $oAuthRoleAdmin->save();
                }
            }
//            $oAuthRoleAdmin->saveAll($temp);
        }
        $auth_admin['password'] = '';
        $auth_admin['roles'] = $roles;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $auth_admin;
        return response()->json($aFinal);
        return ResultVo::success($auth_admin);
    }

    public function adminEdit()
    {
        $data = request()->post();
//        Log::info($data);
        $aRoles = $data['roles'];
        if (empty($data['id']) || empty($data['username'])) {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $iId = $data['id'];
        $sUsername = strip_tags($data['username']);
        // 模型
//        $auth_admin = AuthAdmin::where('id',$iId)
//            ->field('id,username')
//            ->find();
        $oAuthAdmin = AuthAdmin::where('id', $iId)
            ->first();
        if (!$oAuthAdmin) {
            return ResultVo::error(ErrorCode::DATA_NOT, "商户不存在");
        }
        $login_info = $oAuthAdmin;
        $login_user_name = isset($login_info['username']) ? $login_info['username'] : '';
        // 如果是超级商户，判断当前登录用户是否匹配
        if ($oAuthAdmin->username == 'admin' && $login_user_name != $oAuthAdmin->username) {
            return ResultVo::error(ErrorCode::DATA_NOT, "最高权限用户，无权修改");
        }
//        $info = AuthAdmin::where('username',$sUsername)
//            ->field('id')
//            ->find();
        $info = AuthAdmin::where('username', $sUsername)
            ->first();
        // 判断username 是否重名，剔除自己
//        if (!empty($info['id']) && $info['id'] != $iId){
//            return ResultVo::error(ErrorCode::DATA_REPEAT, "商户已存在");
//        }
        $status = isset($data['status']) ? $data['status'] : 0;
        $sPassword = isset($data['password']) ? PassWordUtils::create($data['password']) : '';
        $oAuthAdmin->username = $sUsername;
        if ($sPassword) {
            $oAuthAdmin->password = $sPassword;
        }
        $oAuthAdmin->status = $status;
//        $oAuthAdmin->role_id = implode(",", $aRoles);
        $result = $oAuthAdmin->save();
        $roles = (isset($data['roles']) && is_array($data['roles'])) ? $data['roles'] : [];
        if (!$result) {
            // 没有做任何更改
            $oAuthRoleAdmin = AuthRoleAdmin::where('admin_id', $iId)->field('role_id')->select();
            if ($oAuthRoleAdmin) {
                $oAuthRoleAdmin = $oAuthRoleAdmin->toArray();
                $oAuthRoleAdmin = array_column($oAuthRoleAdmin, 'role_id');
            }
            // 没有差值，权限也没做更改
            if ($roles == $oAuthRoleAdmin) {
                return ResultVo::error(ErrorCode::DATA_CHANGE);
            }
        }
        if ($roles) {
            // 先删除
            AuthRoleAdmin::where('admin_id', $iId)->delete();
            $temp = [];
            foreach ($roles as $key => $value) {
                $temp[$key]['role_id'] = $value;
                $temp[$key]['admin_id'] = $iId;
            }
            //添加用户的角色
            $oAuthRoleAdmin = new AuthRoleAdmin();
            if (count($temp) > 0) {
                foreach ($temp as $k => $v) {
                    $oAuthPermission = new AuthRoleAdmin();
                    $oAuthPermission->role_id = $v['role_id'];
                    $oAuthPermission->admin_id = $v['admin_id'];
                    $result = $oAuthPermission->save();
                    if (!$result) {
                        return ResultVo::error(ErrorCode::NOT_NETWORK);
                    }
                }
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
            }
        }
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;
        return response()->json($aFinal);
        return ResultVo::success();
    }
    /**
     * @api {post} /api/adminDelete  删除商户
     * @apiGroup admin
     * @apiParam {string} id 编号
     * @apiParamExample {json} 请求的参数例子:
     *     {
     *       id: '111111',
     *     }
     *
     * @apiSuccessExample 新建用户成功
     * HTTP/1.1 201 OK
     * {
     * "status": "success",
     * "status_code": 201
     * }
     * @apiErrorExample 数据验证出错
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
     * }
     */
    public function adminDelete()
    {
//        $iId = request()->post('id/d');
        $iId = request()->all()['id'];
        if ($iId == '') {
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
//        $auth_admin = AuthAdmin::where('id',$iId)->field('username')->find();
        $oAuthAdmin = AuthAdmin::where('id', $iId)->first();
        if (!$oAuthAdmin || $oAuthAdmin['username'] == 'admin' || !$oAuthAdmin->delete()) {
//            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        // 删除权限
        AuthRoleAdmin::where('admin_id', $iId)->delete();
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
//        $aFinal['data'] = $res;
        return response()->json($aFinal);
        return ResultVo::success();
    }
}