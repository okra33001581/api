<?php

namespace App\common\utils;
/*
 * CommonUtils 封装工具类
 */

class CommonUtils
{

    public static function getMessage($sMessageKey)
    {
        return __('message.' . $sMessageKey);
    }

    /*
        *  php访问url路径，get请求
        */
    public static function getCurlFileGetContents($data)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $data['url']);
        //设置头文件的信息作为数据流输出
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $sTmp = '{"query": 
                    {"bool":
                        {"filter": [
                                '.$data['where'].'
                            ]
                        }
                    },
                "from":'.(($data['page']-1)*20).',"size":'.$data['limit'].'}';
        if (isset($data['sort'])) {
            $sTmp = substr($sTmp,0,-1);
            $sTmp = $sTmp.$data['sort'];
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $sTmp);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return($data);
        die;


        // header传送格式
        $headers = array(
            "token:1111111111111",
            "over_time:22222222222",
        );
        // 初始化
        $curl = curl_init();
        // 设置url路径
        curl_setopt($curl, CURLOPT_URL, $sUrl);
        // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        // 添加头信息
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // CURLINFO_HEADER_OUT选项可以拿到请求头信息
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        // 不验证SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 执行
        $data = curl_exec($curl);
        // 打印请求头信息
//        echo curl_getinfo($curl, CURLINFO_HEADER_OUT);
        // 关闭连接
        curl_close($curl);
        // 返回数据
        return $data;
    }

    public static function objectToArray($e)
    {
        $e = (array)$e;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'resource') return;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $e[$k] = (array)CommonUtils::objectToArray($v);
        }
        return $e;
    }


}