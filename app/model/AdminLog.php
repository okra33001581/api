<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

use App\common\utils\CommonUtils;

/**
 * Class Event - 活动表
 * @author zebra
 */
class AdminLog extends Model
{
    // table name
    protected $table = 'log_admin';

    public $timestamps = false;

    /**
     * 写log
     * @param $iUserId
     * @param $sUserName
     * @param $iPostId
     * @param $fPrice
     * @param $iFinalCount
     * @param $iFinalAmount
     * @return static
     */
    public static function adminLogSave($operate_name)
    {
        $sAdminUserId = request()->header('X-Adminid');
        $token = request()->header('X-Token');
        $sOrigin = request()->header('Origin');
        $sReferer = request()->header('Referer');
        $sUserAgent = request()->header('User-Agent');
        $oAuthAdmin = AuthAdmin::getMerchant($sAdminUserId);
        if (is_object($oAuthAdmin)) {
            $sMerchantName = $oAuthAdmin->merchant_name;
            $oAdminLog = new AdminLog();
            $oAdminLog->sub_account = $oAuthAdmin->username;
            $oAdminLog->operate_name = $operate_name;
            $oAdminLog->log_content = config('function.'.$operate_name);
            $oAdminLog->ip = $_SERVER["REMOTE_ADDR"];
            $oAdminLog->cookies = $sOrigin;
            $oAdminLog->date = now();
            $oAdminLog->merchant_id = $sAdminUserId;
            $oAdminLog->merchant_name = $sMerchantName;
            $oAdminLog->origin = $sOrigin;
            $oAdminLog->referer = $sReferer;
            $oAdminLog->user_agent = $sUserAgent;
            return $oAdminLog->save();
        }
    }




    public static function getEsData($data, & $iTotal)
    {
        $datas = json_decode($data);
        $iTotal = $datas->hits->total;
        $data = $datas->hits->hits;
        $res = array();
        $resFinal = array();
        foreach ($data as $item) {
            $aTmp = CommonUtils::objectToArray($item);
            $res['id'] = $aTmp['_source']['Id'];
            $res['sub_account'] = $aTmp['_source']['Sub_account'];
            $res['operate_name'] = $aTmp['_source']['Operate_name'];
            $res['log_content'] = $aTmp['_source']['Log_content'];
            $res['ip'] = $aTmp['_source']['Ip'];
            $res['cookies'] = $aTmp['_source']['Cookies'];
            $res['date'] = $aTmp['_source']['Date'];
            $res['merchant_id'] = $aTmp['_source']['Merchant_id'];
            $res['merchant_name'] = $aTmp['_source']['Merchant_name'];
            $res['created_at'] = $aTmp['_source']['Created_at'];
            $res['origin'] = $aTmp['_source']['Origin'];
            $res['referer'] = $aTmp['_source']['Referer'];
            $res['user_agent'] = $aTmp['_source']['User_agent'];
            $res['type'] = $aTmp['_source']['Type'];
            $resFinal[] = $res;
        }
        return $resFinal;
    }

}
