<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event - 共同
 * @author zebra
 */
class Common extends Model
{
    // table name
    protected $table = 'Common';

    public $timestamps = false;
    
    //状态
    public static $statusSaveRules=[
                        'flag' => 'required|string|in:启用,禁用,同意,拒绝,销售中,停止销售,置顶,解除置顶',
//                        'flagab' => 'required|integer|in:0,1',
                    ];
    public static $statusSaveMessages=[
                        'flag'=>'名称必须为启用、禁用、同意或拒绝',
                    ];


    //排序值
    public static $sequenceSaveRules=[
                        'sequence' => 'required|integer',
                    ];
    public static $sequenceSaveMessages=[
                        'sequence'=>'数据错误',
                    ];


    //支付平台选项设定
    public static $paySettingSaveRules=[
                        'pay_setting' => 'required|string',
                    ];
    public static $paySettingSaveMessages=[
                        'pay_setting'=>'数据错误',
                    ];


    //投注限额
    public static $projectLimitSaveRules=[
                        'project_limit' => 'required|string',
                    ];
    public static $projectLimitSaveMessages=[
                        'project_limit'=>'数据错误',
                    ];


    //属性
    public static $propertySaveRules=[
                        'hot' => 'present|string|nullable',
                        'recommand' => 'sometimes|present|string|nullable',
                        'new' => 'sometimes|present|string|nullable',
                    ];
    public static $propertySaveMessages=[
                        'hot'=>'数据错误',
                        'recommand'=>'数据错误',
                        'new'=>'数据错误',
                    ];

    //属性
    public static $newPropertySaveRules=[
                        'property_name' => 'required|string',
                        'property_value' => 'required|boolean',
                    ];
    public static $newPropertySaveMessages=[
                        'property_name'=>'数据错误',
                        'property_value'=>'数据错误',
                    ];

    //支付类型别名
    public static $paytypeAliasSaveRules=[
                        'paytype_alias' => 'required|string',
                    ];
    public static $paytypeAliasSaveMessages=[
                        'paytype_alias'=>'数据错误',
                    ];

    //投注限额
    public static $betlimitSaveRules=[
                        'name' => 'filled|string',
                        'prize_limit' => 'filled|string',
                    ];
    public static $betlimitSaveMessages=[
                        'name'=>'数据错误',
                        'prize_limit'=>'数据错误',
                    ];


}
