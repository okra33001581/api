<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event - 活动表
 * @author zebra
 */
class FastPayMoney extends Model
{
    // table name
    protected $table = 'fund_fastpaymoney';

    public $timestamps = false;

    /**
     * 根据数据返回字符串
     * @date 2019-3-15
     * @param string $sPath
     *
     * @return string,逗号间隔
     */
    static function arrTostr($array)
    {
        // 定义存储所有字符串的数组
//        static $r_arr = array();
        $r_arr = [];

        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    // 递归遍历
                    arrToStr($value);
                } else {
                    $r_arr[] = $value;
                }
            }
        } else if (is_string($array)) {
            $r_arr[] = $array;
        }

        //数组去重
        $r_arr = array_unique($r_arr);
        $string = implode(",", $r_arr);

        return $string;
    }

    /**
     * 根据文件名称取得对应的数据数组
     * @date 2019-3-15
     * @param string $sPath
     *
     * @return string
     */
    static function getFromTxt($sPath)
    {
        $aFinal = [];
        foreach (file($sPath) as $line) {
            $str = str_replace(array("/r/n", "/r", "/n"), "", $line);
            $aFinal[] = $str;
        }
        return static::arrTostr($aFinal);
    }


    /**
     * 根据相对路径取得含有域名完整路径
     * @date 2019-3-15
     * @param string $sPath
     *
     * @return string
     */
    static function getFileDomain($sPath)
    {
        $sDomainUrl = 'http://apidemo.test/' . strstr($sPath, 'public');
        return $sDomainUrl;
    }



    /**
     * 数组按指定方式分割为字符串
     * @date 2019-3-15
     * @param string $sTmp
     *
     * @return array
     */
    static function getArrayFromString($sTmp)
    {
        //数组按指定方式分割为字符串
        return explode(",",$sTmp);
    }





}
