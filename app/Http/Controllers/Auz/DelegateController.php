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
     * 代理默认配置列表数据
     * @param request
     * @return json
     */

    public function proxycommissionList()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        
        $oProxycommissionList = DB::table('delegate_quota');
        if ($sMerchantName !== '') {
            $oProxycommissionList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        $iLimit = request()->get('limit/d', 20);
        $oProxycommissionFinalList = $oProxycommissionList->orderby('id', 'desc')->paginate($iLimit);
        $res = [];
        $res["total"] = count($oProxycommissionFinalList);
        $res["list"] = $oProxycommissionFinalList->toArray();
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

    /**
     * 代理默认配置添加和修改数据
     * @param request
     * @return json
     */
    public function proxycommissionSave()
    {
        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iMerchantId = isset($data['merchant_id']) ? $data['merchant_id'] : '';
        $iDelegateLevel = isset($data['delegate_level']) ? $data['delegate_level'] : '';
        $sRebate = isset($data['rebate']) ? $data['rebate'] : '';
        $sDefaultQuota = isset($data['default_quota']) ? $data['default_quota'] : '';

        if ($iId != '') {
            $oProxyConfiguration = ProxyConfiguration::find($iId);
        }else{
            $oProxyConfiguration = new ProxyConfiguration();
        }

        $oProxyConfiguration->merchant_id = $iMerchantId;
        $oProxyConfiguration->delegate_level = $iDelegateLevel;
        $oProxyConfiguration->rebate = $sRebate;
        $oProxyConfiguration->default_quota = $sDefaultQuota;

        $iRet = $oProxyConfiguration->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oProxyConfiguration;

        $sOperateName = 'proxycommissionSave';
        $sLogContent = 'proxycommissionSave';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 代理默认配置删除数据
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
     * 代理推广链接列表
     * @param request
     * @return json
     */
   
    public function proxycommissionProxylist()
    {
        // 第一个选项
        $aStatus = ['delegate_account','bind_domain','invite_code'];
        // 第二个选项
        $aCount = ['register_people_count','visit_count'];
        // 第三个选项
        $aType = ['代理','会员'];

        $sOrder = 'id DESC';
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $iPage = isset(request()->page) ? request()->page : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $iCount = isset(request()->count) ? request()->count : '';
        $iType = isset(request()->type) ? request()->type : '';
        $oProxycommissionProxylist = DB::table('delegate_sponsor_links');
            $oProxycommissionProxylist->orderby('id','desc');
        if ($iStatus !== '') {
            $sName1 = $aStatus[$iStatus];
            $sInfo = isset(request()->info) ? request()->info : '';
            if ($sInfo) {
                $oProxycommissionProxylist->where($sName1,'like','%'.$sInfo.'%');
            }
        }
        if ($iCount !== '') {
            $sName2 = $aCount[$iCount];
            $iBeginCount = isset(request()->begin_count) ? request()->begin_count : '';
            $iEndCount = isset(request()->end_count) ? request()->end_count : '';
            $oProxycommissionProxylist->whereBetween($sName2,[$iBeginCount,$iEndCount]);
        }
        if ($iType !== '') {
            $oProxycommissionProxylist->where('open_account_type',$aType[$iType]);
        }
        $oProxycommissionProxylistCount = $oProxycommissionProxylist->get();
        $oProxycommissionProxyFinalList = $oProxycommissionProxylist->skip(($iPage - 1) * $iLimit)->take($iLimit)->get();
        $aTmp = [];
        $aFinal = [];
        $res = [];
        $res["total"] = count($oProxycommissionProxylistCount);
        $res["list"] = $oProxycommissionProxyFinalList->toArray();;
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

    /**
     * 代理推广链接数据添加和修改
     * @param request
     * @return json
     */

    public function proxycommissionProxySave()
    {
        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $sDelegateAccount = isset($data['delegate_account']) ? $data['delegate_account'] : '';
        $sBindDomain = isset($data['bind_domain']) ? $data['bind_domain'] : '';
        $sInviteCode = isset($data['invite_code']) ? $data['invite_code'] : '';
        $sOpenAccountType = isset($data['open_account_type']) ? $data['open_account_type'] : '';
        $sRebate = isset($data['rebate']) ? $data['rebate'] : '';
        $sMemo = isset($data['memo']) ? $data['memo'] : '';
        if ($iId != '') {
            $oProxyGeneralize = ProxyGeneralize::find($iId);
        }else{
            $oProxyGeneralize = new ProxyGeneralize();
        }

        $oProxyGeneralize->delegate_account = $sDelegateAccount;
        $oProxyGeneralize->bind_domain = $sBindDomain;
        $oProxyGeneralize->invite_code = $sInviteCode;
        $oProxyGeneralize->open_account_type = $sOpenAccountType;
        $oProxyGeneralize->rebate = $sRebate;
        $oProxyGeneralize->memo = $sMemo;

        $iRet = $oProxyGeneralize->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $oProxyGeneralize;

        $sOperateName = 'proxycommissionProxySave';
        $sLogContent = 'proxycommissionProxySave';

        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }

    /**
     * 代理推广链接数据删除
     * @param request
     * @return json
     */
   
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