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

        $oAdminLog = new AdminLog();
        $oAdminLog->sub_account = $sub_account;
        $oAdminLog->operate_name = $operate_name;
        $oAdminLog->log_content = $log_content;
        $oAdminLog->ip = $ip;
        $oAdminLog->cookies = $cookies;
        $oAdminLog->date = $date;
        $oAdminLog->merchant_id = $merchant_id;
        $oAdminLog->merchant_name = $merchant_name;

        return $oAdminLog->save();
    }

}
