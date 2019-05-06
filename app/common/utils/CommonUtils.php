<?php

namespace App\common\utils;
/*
 * CommonUtils 封装工具类
 */
class CommonUtils
{

    public static function getMessage($sMessageKey)
    {
        return __('message.'.$sMessageKey);
    }


}