<?php

namespace App\GraphQL\Types\Managenment;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class OrderInfoType extends BaseType
{
    protected $attributes = [
        'name'        => 'OrderInfo',
        'description' => '首页业绩环比信息',
    ];

    public function fields()
    {
        return [
            'count'            => [
                'type'        => Type::int(),
                'description' => '订单总数',
            ],
            'commission'       => [
                'type'        => Type::float(),
                'description' => '订单总金额',
            ],
            'ratio'            => [
                'type'        => Type::float(),
                'description' => '环比比例',
            ],
            'is_grow'            => [
                'type'        => Type::boolean(),
                'description' => '是否增长',
            ],
        ];
    }
}
