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

    public static $statusSaveRules=[
                        'flag' => 'required|string|in:启用,禁用,同意,拒绝',
//                        'flagab' => 'required|integer|in:0,1',
                    ];
    public static $statusSaveMessages=[
                        'flag'=>'名称必须为启用、禁用、同意或拒绝',
//                        'flagab'=>'名称必须bbb',
                    ];

}
