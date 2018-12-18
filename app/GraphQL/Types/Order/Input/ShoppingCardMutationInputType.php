<?php

namespace App\GraphQL\Types\Order\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class ShoppingCardMutationInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'ShoppingCardMutationInput',
        'description' => '购物车储存请求字段',
    ];

    public function fields()
    {
        return [
            'product_id'             => [
                'name'        => 'product_id',
                'type'        => Type::int(),
                'description' => '商品的id',
            ],
            'product_price'             => [
                'name'        => 'product_price',
                'type'        => Type::float(),
                'description' => '商品的价格',
            ],
            'product_name'             => [
                'name'        => 'product_name',
                'type'        => Type::string(),
                'description' => '商品的名字',
            ],
            'supplier_id'             => [
                'name'        => 'supplier_id',
                'type'        => Type::int(),
                'description' => '供应商id',
            ],
            'number'             => [
                'name'        => 'number',
                'type'        => Type::int(),
                'description' => '商品的数量',
            ],
        ];
    }
}
