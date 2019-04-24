<?php
namespace App\Http\Controllers;
use DB;
use Log;
use App\common\vo\ResultVo;
use Illuminate\Http\Request;
use App\model\AdminLog;
use App\model\ProxyGeneralize;
use App\model\ProxyConfiguration;
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
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $merchant_name = isset(request()->merchant_name) ? request()->merchant_name : '';
        
        $proxycommissionList = DB::table('delegate_quota');
        if ($merchant_name !== '') {
            $proxycommissionList->where('merchant_name', 'like', '%' . $merchant_name . '%');
        }
        $iLimit = request()->get('limit/d', 20);
        $proxycommissionFinalList = $proxycommissionList->orderby('id', 'desc')->paginate($iLimit);
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
        // 第一个选项
        $status_arr = ['delegate_account','bind_domain','invite_code'];
        // 第二个选项
        $count_arr = ['register_people_count','visit_count'];
        // 第三个选项
        $type_arr = ['代理','会员'];

        $sOrder = 'id DESC';
        $limit = isset(request()->limit) ? request()->limit : '';
        $page = isset(request()->page) ? request()->page : '';
        $status = isset(request()->status) ? request()->status : '';
        $count = isset(request()->count) ? request()->count : '';
        $type = isset(request()->type) ? request()->type : '';
        $proxycommissionProxylist = DB::table('delegate_sponsor_links');
            $proxycommissionProxylist->orderby('id','desc');
        if ($status !== '') {
            $name1 = $status_arr[$status];
            $value1 = isset(request()->info) ? request()->info : '';
            if ($value1) {
                $proxycommissionProxylist->where($name1,'like','%'.$value1.'%');
            }
        }
        if ($count !== '') {
            $name2 = $count_arr[$count];
            $begin_count = isset(request()->begin_count) ? request()->begin_count : '';
            $end_count = isset(request()->end_count) ? request()->end_count : '';
            $proxycommissionProxylist->whereBetween($name2,[$begin_count,$end_count]);
        }
        if ($type !== '') {
            $proxycommissionProxylist->where('open_account_type',$type_arr[$type]);
        }
        $proxycommissionProxylistCount = $proxycommissionProxylist->get();
        $proxycommissionProxyFinalList = $proxycommissionProxylist->skip(($page - 1) * $limit)->take($limit)->get();
        $aTmp = [];
        $aFinal = [];
        $res = [];
        $res["total"] = count($proxycommissionProxylistCount);
        $res["list"] = $proxycommissionProxyFinalList->toArray();;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'proxycommissionProxylist';
        $sLogContent = 'proxycommissionProxylist';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }

    public function proxycommissionProxySave()
    {
        $data = request()->post();
        $id = isset($data['id']) ? $data['id'] : '';
        $delegate_account = isset($data['delegate_account']) ? $data['delegate_account'] : '';
        $bind_domain = isset($data['bind_domain']) ? $data['bind_domain'] : '';
        $invite_code = isset($data['invite_code']) ? $data['invite_code'] : '';
        $open_account_type = isset($data['open_account_type']) ? $data['open_account_type'] : '';
        $rebate = isset($data['rebate']) ? $data['rebate'] : '';
        $memo = isset($data['memo']) ? $data['memo'] : '';
        if ($id != '') {
            $ProxyGeneralize = ProxyGeneralize::find($id);
        }else{
            $ProxyGeneralize = new ProxyGeneralize();
        }

        $ProxyGeneralize->delegate_account = $delegate_account;
        $ProxyGeneralize->bind_domain = $bind_domain;
        $ProxyGeneralize->invite_code = $invite_code;
        $ProxyGeneralize->open_account_type = $open_account_type;
        $ProxyGeneralize->rebate = $rebate;
        $ProxyGeneralize->memo = $memo;

        $iRet = $ProxyGeneralize->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $ProxyGeneralize;

        $sOperateName = 'proxycommissionProxySave';
        $sLogContent = 'proxycommissionProxySave';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

   
    public function proxycommissionProxyDelete()
    {
        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';

        $oIpBlack = ProxyGeneralize::where('id',$iId)->delete();
        if ($oIpBlack) {
            $sMessage = '删除成功！';
        } else {
            $sMessage = '删除失败！';
        }

        $aFinal['message'] = $sMessage;
        $aFinal['code'] = 0;
        $aFinal['data'] = $oIpBlack;

        $sOperateName = 'proxycommissionProxyDelete';
        $sLogContent = 'proxycommissionProxyDelete';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }
}