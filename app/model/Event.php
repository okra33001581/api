<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $table                   = 'eventNew';

    public $timestamps = false;

    /*public function fromDateTime($value){
        return strtotime(parent::fromDateTime($value));
    }*/


    static function arrTostr ($array)
    {
        // 定义存储所有字符串的数组
        static $r_arr = array();

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

}
