<?php

namespace app\common\utils;

/*
 * date 封装工具类
 */
class DateUtils
{

    function get_month_start_end($timestamp)
    {
        !empty($timestamp) OR $timestamp = time();

        $last_month = date('Y-m-01', $timestamp);
        $last['first'] =date('Y-m-d 00:00:00',strtotime($last_month));
        $last['end'] =date('Y-m-d 23:59:59',strtotime("$last_month +1 month -1 seconds"));
        return $last;
    }



    /**
     * 拼接url
     * @param string $baseURL   基于的url
     * @param array  $params   参数列表数组
     * @return string           返回拼接的url
     */
    public static function getDateArray($sDataPeriod){
        $aTmp = [];
        switch ($sDataPeriod) {
            case 'today':
                $aTmp['begin_date']=mktime(0,0,0,date('m'),date('d'),date('Y'));
                $aTmp['end_date']=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
                break;
            case 'last_week':
                $aTmp['begin_date']=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
                $aTmp['end_date']=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
                break;
            case 'current_week':
                $aTmp['begin_date'] = strtotime(date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))));
                $aTmp['end_date'] = strtotime(date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))));
                break;
            case 'last_month':
                $aTmp = get_month_start_end();
                break;
            case 'current_month':
                $aTmp['begin_date'] = mktime(0,0,0,date('m'),1,date('Y'));
                $aTmp['end_date'] = mktime(23,59,59,date('m'),date('t'),date('Y'));
                break;
            case 'last_three_month':
                $aTmp['begin_date'] = strtotime(date('Y-m-01 00:00:00' ,strtotime('-2 month')));
                $aTmp['end_date'] = strtotime(date('Y-m-d H:i:s' , time()));;
                break;
        }
    }



}