<?php

namespace App\GraphQL\Types\Order\Order;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class PositionInfoType extends BaseType
{
    protected $attributes = [
        'name'        => 'PositionInfo',
        'description' => '订单配送信息',
    ];

    public function fields()
    {
        return [
            'current_position'             => [
                'type'        => Type::string(),
                'description' => '当前位置地址',
            ],
            'position'    => [
                'type'        => GraphQL::type('CoordinateInfo'),
                'description' => '位置的坐标',
            ],
        ];
    }
}
