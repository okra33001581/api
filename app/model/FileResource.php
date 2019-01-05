<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class FileResource extends Model
{
    //
    // 资源的根路径
    public static $RESOURCES_PATH = 'resources' . DIRECTORY_SEPARATOR;

    /**
     * 获取上传文件的根路径
     */
    public static function getBasePath()
    {
        return Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
    }

    /*
     * 获取类型的path
     */
    public static function getTypePath($type = 0) {
        $types = [
            0 => 'image' . DIRECTORY_SEPARATOR
        ];
        return isset($types[$type]) ? $types[$type] : 'all';
    }
}
