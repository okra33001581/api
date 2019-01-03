<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class AuthPermissionRule extends Model
{
    public static $icon = ['│', '├', '└'];
    //


    public static function getLists($where,$order){
        $lists = self::where($where)
            ->orderby('id','asc')->get();
        return $lists;
    }

    public static function getListsTmp($where,$order){
        $lists = self::orderby('id','asc')->get();
        return $lists;
    }

    /* 整合多维数组 */
    public static function cateMerge($arr, $idName, $pidName, $pid = 0)
    {
        $result = array();
        foreach ($arr as $v) {
            if ($v[$pidName] == $pid) {
                $v['children'] = self::cateMerge($arr, $idName, $pidName, $v[$idName]);
                $result[] = $v;
            }
        }
        return $result;
    }


    /* 分类数型图 */
    public static function cateTree($arr, $idName, $pidName, $pid = 0, $level = 0, $html = '&nbsp;&nbsp;|--') {
        if (empty($arr)) {
            return array();
        }
        $result = array();
        $total  = count($arr);
        $number = 1;
        foreach ($arr as $val) {
            $tmp_str = str_repeat(self::$icon[0] . '&nbsp;', $level > 0 ? $level - 1 : 0);
            if ($total == $number) {
                $tmp_str .= self::$icon[2];
            } else {
                $tmp_str .= self::$icon[1];
            }
            if ($val[$pidName] == $pid) {
                $val['level'] = $level + 1;
                $val['html'] = '&nbsp;' . ($level == 0 ? '' : '&nbsp;' . $tmp_str . "&nbsp;");
                $result[] = $val;
                $result = array_merge($result, self::cateTree($arr, $idName, $pidName, $val[$idName], $val['level'], $html));
            }
            $number++;
        }
        return $result;
    }


}
