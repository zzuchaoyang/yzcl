<?php

namespace App\GraphQL\Types\Order\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class OrderProductPaginatorInput extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'OrderProductPaginatorInput',
        'description' => '订单详情查询的字段',
    ];

    public function fields()
    {
        return [
            'id'  => [
                'name'        => 'id',
                'type'        => Type::int(),
                'description' => 'id',
            ],
            'order_id'  => [
                'name'        => 'order_id',
                'type'        => Type::int(),
                'description' => '订单id',
            ],
        ];
    }
}
