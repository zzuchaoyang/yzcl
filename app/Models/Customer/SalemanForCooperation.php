<?php

namespace App\Models\Customer;

use App\Models\BaseModel;

/**
 * App\Models\Customer\SalemanForCooperation
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @mixin \Eloquent
 */
class SalemanForCooperation extends BaseModel
{
    protected $fillable = [
        'cooperation_application_id',
        'salesman_id',
    ];

    protected $indexs = [
        '*',
    ];

    protected $allColumns = [
        'id',
        'cooperation_application_id', // 合作关系id
        'salesman_id', // 为合作关系服务的业务员id
    ];
}
