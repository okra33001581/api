<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use App\common\utils\TokenUtils;
use Illuminate\Support\Facades\Redis;
use Log;

//Redis::set('user1','3333');
//$user = Redis::get('user1');
//
//
//return response()->json($user);

class AuthAdmin extends Model
{
    //


    public static function objectToArray(&$object) {
        $object = json_decode( json_encode( $object),true);
        return $object;
    }

    /**
     * 获取登录信息
     * @param int $id 用户ID
     * @param array|string $values 如果这个值为数组则为设置用户信息，否则为 token
     * @param bool $is_login 是否验证用户是否登录
     * @return array|bool 成功返回用户信息，否则返回 false
     */
    public static function loginInfo($id, $values,$is_login = true){
//        $redis = RedisUtils::init();
        $key = 'admin:login:' . $id;
//        $key = "456";
        // 判断缓存类是否为 redis
        $redis = false;
        if ($redis){
            if ($values && is_array($values)){
                $values['id'] = $id;
                $values['token'] = TokenUtils::create("admin" . $id);
                $values['authRules'] = isset($values['authRules']) ? json_encode($values['authRules']) : '';
                $res = $redis->hMset($key, $values);
                $values = $values['token'];
            }
            $info = $redis->hGetAll($key);
            if ($is_login === false){
                if (isset($info['token']))  unset($info['token']);
                return $info;
            }
            if (!empty($info['id']) && !empty($info['token']) && $info['token'] == $values){
                $info['authRules'] = isset($info['authRules']) ? json_decode($info['authRules']) : '';
                return $info;
            }
        }else{

            Log::info('1111111111111111111111111111111111111');

            if ($values && is_array($values)){

//                return response()->json(123);
//
//                echo 'aaa';
//                die;
                $values['id'] = $id;
                $values['token'] = TokenUtils::create("admin" . $id);



//                Log::info('789');
//                $res = Cache::set($key, $values);
//                $key = 'user1';
//                $key = 'admin:login:' . $id;
//                Log::info('44444======'.$key);
//                Redis::set($key,'34343');
                Redis::set($key,json_encode($values));
//                $res = Redis::set($key,$values);

                $values = $values['token'];

                Log::info('2222222222222222222222222222222222222222');
            }
            $info = Redis::get($key);

            Log::info('key==============='.$key);
            Log::info($info);

            if ($info != '') {
                $aFinal = get_object_vars(json_decode($info));
                if (count($aFinal) > 0) {
                    if ($is_login === false){
                        if (isset($aFinal['token']))  unset($aFinal['token']);
                        return $aFinal;
                    }
                    if (!empty($aFinal['id']) && !empty($aFinal['token']) && $aFinal['token'] == $values){
                        return $aFinal;
                    }
                }
            }

        }


        return false;
    }
}
