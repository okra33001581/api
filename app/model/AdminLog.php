<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

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
    public static function adminLogSave($sub_account, $operate_name, $log_content, $ip, $cookies, $date, $merchant_id, $merchant_name)
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
            $oAdminLog->log_content = $log_content;
            $oAdminLog->ip = $_SERVER["REMOTE_ADDR"];
            $oAdminLog->cookies = $token;
            $oAdminLog->date = $date;
            $oAdminLog->merchant_id = $merchant_id;
            $oAdminLog->merchant_name = $sMerchantName;
            $oAdminLog->origin = $sOrigin;
            $oAdminLog->referer = $sReferer;
            $oAdminLog->user_agent = $sUserAgent;
            return $oAdminLog->save();
        }
    }

}
