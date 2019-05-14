<?php

namespace App\Http\Controllers;

use App\common\utils\CommonUtils;
use App\model\MerchantsDomains;
use App\model\MerchantsIp;
use App\model\TransactionTypes;
use DB;
use Log;
use App\common\vo\ResultVo;
use App\model\AdminLog;

use App\model\ThirdBall;
use App\model\ThirdGameTypes;
use App\model\ThirdMerchantGame;
use App\model\ThirdPlats;
use App\model\ThirdGameTypesDetail;
use App\model\ThirdGameSet;
use App\model\SysConfigs;
use App\model\SystemMonitor;
use App\model\Common;

/**
 * Class Event - 公告相关控制器
 * @author zebra
 */
class SystemController extends Controller
{

	/**
     * 系统参数设置列表
     * @param request
     * @return json
     */
	public function sysConfigsList()
	{
		$iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $iParentId = isset(request()->parent_id) ? request()->parent_id : null;
        $sMerchantName = isset(request()->merchant_name) ? request()->merchant_name : '';
        $sysConfigsList = DB::table('sys_configs');

        $sysConfigsList->orderby('id', 'desc');
        if ($iStatus !== '') {
            $sysConfigsList->where('status', $iStatus);
        }
        if ($sMerchantName !== '') {
            $sysConfigsList->where('merchant_name', 'like', '%' . $sMerchantName . '%');
        }
        $sysConfigsList->where('parent_id',$iParentId);
        $iLimit = request()->get('limit', 20);
        $sysConfigsList = $sysConfigsList->orderby('id', 'desc')->paginate($iLimit);
        $aTmp = [];
        $aFinal = [];
        $res = [];
        $res["total"] = count($sysConfigsList);
        $res["list"] = $sysConfigsList;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'sysConfigsList';
        $sLogContent = 'sysConfigsList';
        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
	}


	/**
     * 系统参数设置数据保存
     * @param request
     * @return json
     */
    public function sysConfigsSave()
    {	
        $data = request()->post();
        $id=isset($data['id'])?$data['id']:'';
        $iParentId=isset($data['parent_id'])?$data['parent_id']:null;
        $sParent=isset($data['parent'])?$data['parent']:'';
        $sItem=isset($data['item'])?$data['item']:'';
        $sValue=isset($data['value'])?$data['value']:'';
        $sDefaultValue=isset($data['default_value'])?$data['default_value']:'';
        $sTitle=isset($data['title'])?$data['title']:'';
        $sDescription=isset($data['description'])?$data['description']:'';
        $sStatus=isset($data['status'])?$data['status']:'';
        $sSequence=isset($data['sequence'])?$data['sequence']:'';

        if ($id != '') {
            $sysConfigs = SysConfigs::find($id);
        	$sysConfigs->updated_at=now();
        } else {
            $sysConfigs = new SysConfigs();
        }

        $sysConfigs->parent_id=$iParentId;
        $sysConfigs->parent=$sParent;
        $sysConfigs->item=$sItem;
        $sysConfigs->value=$sValue;
        $sysConfigs->default_value=$sDefaultValue;
        $sysConfigs->title=$sTitle;
        $sysConfigs->description=$sDescription;
        $sysConfigs->status=$sStatus;
        $sysConfigs->sequence=$sSequence;

        $iRet = $sysConfigs->save();

        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $sysConfigs;

        $sOperateName = 'thirdGameTypesSave';
        $sLogContent = 'thirdGameTypesSave';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);
        return response()->json($aFinal);
    }


    /**
     * 系统参数设置数据状态修改
     * @param request
     * @return json
     */
    public function sysConfigsStatusSave()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['flag']) ? $data['flag'] : '';
        try
        {

            if($this->validate(request(),Common::$statusSaveRules,Common::$statusSaveMessages)) {
                $oSysConfigs = SysConfigs::find($iId);
                $oSysConfigs->status = $iFlag;
                $iRet = $oSysConfigs->save();
                $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oSysConfigs;

                $sOperateName = 'sysConfigsStatusSave';
                $sLogContent = 'sysConfigsStatusSave';

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
     * 系统参数设置修改排序
     * @param request
     * @return json
     */
    public function sysConfigsSequenceSave()
    {

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';
        $iFlag = isset($data['sequence']) ? $data['sequence'] : '';
        try
        {
            if($this->validate(request(),Common::$sequenceSaveRules,Common::$sequenceSaveMessages)) {
                $oSysConfigs = SysConfigs::find($iId);
                $oSysConfigs->sequence = $iFlag;
                $iRet = $oSysConfigs->save();
                $aFinal['message'] = CommonUtils::getMessage('updateSave_success');
                $aFinal['code'] = 1;
                $aFinal['data'] = $oSysConfigs;
                $sOperateName = 'sysConfigsSequenceSave';
                $sLogContent = 'sysConfigsSequenceSave';
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
     * 系统参数设置数据删除
     * @param request
     * @return json
     */
    public function sysConfigsDel()
    {
        $data = request()->post();

        $iId = isset($data['id']) ? $data['id'] : '';

        $oSysConfigs = SysConfigs::where('id',$iId)->delete();
        if ($oSysConfigs) {
            $sMessage = '删除成功！';
        } else {
            $sMessage = '删除失败！';
        }

        $aFinal['message'] = $sMessage;
        $aFinal['code'] = 0;
        $aFinal['data'] = $oSysConfigs;

        $sOperateName = 'sysConfigsDel';
        $sLogContent = 'sysConfigsDel';

        $dt = now();

        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
    }


    /**
     * 系统监控设置列表
     * @param request
     * @return json
     */
    public function systemMonitorList()
    {
        $iLimit = isset(request()->limit) ? request()->limit : '';
        $sIpage = isset(request()->page) ? request()->page : '';
        $iStatus = isset(request()->status) ? request()->status : '';
        $sTableName = isset(request()->table_name) ? request()->table_name : '';
        $sEsName = isset(request()->es_name) ? request()->es_name : '';
        $systemMonitorList = DB::table('system_monitor');

        $systemMonitorList->orderby('id', 'desc');
        if ($iStatus !== '') {
            $systemMonitorList->where('status', $iStatus);
        }
        if ($sTableName !== '') {
            $systemMonitorList->where('table_name', 'like', '%' . $sTableName . '%');
        }
        if ($sEsName !== '') {
            $systemMonitorList->where('es_name', 'like', '%' . $sEsName . '%');
        }
        $iLimit = request()->get('limit', 20);
        $systemMonitorList = $systemMonitorList->orderby('id', 'desc')->paginate($iLimit);
        $aTmp = [];
        $aFinal = [];
        $res = [];
        $res["total"] = count($systemMonitorList);
        $res["list"] = $systemMonitorList;
        $aFinal['message'] = 'success';
        $aFinal['code'] = 0;
        $aFinal['data'] = $res;

        $sOperateName = 'systemMonitorList';
        $sLogContent = 'systemMonitorList';
        $dt = now();
        AdminLog::adminLogSave($sOperateName);

        return response()->json($aFinal);
        return ResultVo::success($res);
    }


    /**
     * 系统监控清除ES
     * @param request
     * @return json
     */
    public function systemMonitorClear()
    {   

        $data = request()->post();
        $iId = isset($data['id']) ? $data['id'] : '';

        $oSystemMonitor = SystemMonitor::find($iId);
        $oSystemMonitor->es_record_count = 0;
        $iRet = $oSystemMonitor->save();
        if($iRet){
            $aFinal['message'] = CommonUtils::getMessage('statusSave_success');
            $aFinal['code'] = 1;
            $aFinal['data'] = $oSystemMonitor;
            $sOperateName = 'sysConfigsStatusSave';
            $sLogContent = 'sysConfigsStatusSave';
            $dt = now();
            AdminLog::adminLogSave($sOperateName);
        }else{
            $aFinal['message'] = '非法数据请求';
            $aFinal['code'] = 0;
            $aFinal['data'] = '';
        }

        return response()->json($aFinal);
    }




}