<?php

namespace App\GraphQL\Types\Order\Input;

use App\GraphQL\Types\BaseType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class StoreOrderMutationInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'StoreOrderMutationInput',
        'description' => '订单创建请求参数',
    ];

    public function fields()
    {
        return [
            'id'             => [
                'type'        => Type::int(),
                'description' => '订单ID',
            ],
            'supplier_id'    => [
                'type'        => Type::int(),
                'description' => '供应商ID',
            ],
            'order_products' => [
                'type'        => Type::listOf(GraphQL::type('StoreOrderMutationOrderProductInput')),
                'description' => '订单详情中的产品',
                'rules'       => ['required'],
            ],
        ];
    }
}
