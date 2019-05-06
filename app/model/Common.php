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

    public static $thirdMerchantGameStatusSaveRules=[
                        'flag' => 'required|string|in:启用1,禁用1',
//                        'flagab' => 'required|integer|in:0,1',
                    ];
    public static $thirdMerchantGameStatusSaveMessages=[
                        'flag'=>'名称必须aaa',
//                        'flagab'=>'名称必须bbb',
                    ];

}
