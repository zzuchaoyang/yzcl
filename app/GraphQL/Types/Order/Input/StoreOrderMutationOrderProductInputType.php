<?php

namespace App\GraphQL\Types\Order\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class StoreOrderMutationOrderProductInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'StoreOrderMutationOrderProductInput',
        'description' => '订单详情创建请求参数',
    ];

    public function fields()
    {
        return [
            'id'                 => [
                'type'        => Type::int(),
                'description' => 'ID',
                'rules'       => ['exists:order_product,id'],
            ],
            'product_id'                 => [
                'type'        => Type::int(),
                'description' => '商品ID',
                'rules'       => ['required'],
            ],
            'product_number'              => [
                'type'        => Type::int(),
                'description' => '商品数量',
                'rules'       => ['required'],
            ],
            'send_number'              => [
                'type'        => Type::int(),
                'description' => '发出商品数量',
            ],
        ];
    }
}
