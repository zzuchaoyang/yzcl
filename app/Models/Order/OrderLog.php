<?php

namespace App\Models\Order;

use App\Models\BaseModel;

class OrderLog extends BaseModel
{
    protected $fillable = [
        'id',
        'order_id',
        'position',
        'latitude',
        'longitude',
        'content',
        'created_at',
    ];

    protected $indexs = [
        '*',
    ];

    //关联订单的商品
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected $allColumns = [
        'id',
        'order_id', // 订单id
        'position', // 当前位置
        'latitude', // 位置纬度
        'longitude', // 位置经度
        'content', // 详细内容
        'created_at',
        'updated_at',
    ];
}
