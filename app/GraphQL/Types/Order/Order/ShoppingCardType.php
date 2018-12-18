<?php

namespace App\GraphQL\Types\Order\Order;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class ShoppingCardType extends BaseType
{
    protected $attributes = [
        'name'        => 'ShoppingCard',
        'description' => '购物车信息',
    ];

    public function fields()
    {
        return [
            'product_price'         => [
                'type'        => Type::float(),
                'description' => '订单价格',
            ],
            'product_name'             => [
                'type'        => Type::int(),
                'description' => '商品的名字',
            ],
            'supplier_id'             => [
                'type'        => Type::int(),
                'description' => '供应商id',
            ],
            'number'             => [
                'type'        => Type::int(),
                'description' => '商品的数量',
            ],
            'product_id'             => [
                'type'        => Type::int(),
                'description' => '供应商id',
            ],
        ];
    }
}
