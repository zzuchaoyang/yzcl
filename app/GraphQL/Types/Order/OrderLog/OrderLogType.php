<?php

namespace App\GraphQL\Types\Order\OrderLog;

use App\GraphQL\Types\BaseType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class OrderLogType extends BaseType
{
    protected $attributes = [
        'name'        => 'OrderLog',
        'description' => '订单的日志',
    ];

    public function fields()
    {
        return [
            'id'                => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'id',
            ],
            'order_id'         => [
                'type'        => Type::int(),
                'description' => '订单id',
            ],
            'order'              => [
                'type'        => GraphQL::type('Order'),
                'description' => '订单',
            ],
            'position'             => [
                'type'        => Type::string(),
                'description' => '位置',
            ],
            'latitude'     => [
                'type'        => Type::float(),
                'description' => '位置纬度',
            ],
            'longitude'      => [
                'type'        => Type::float(),
                'description' => '位置纬度',
            ],
            'content'      => [
                'type'        => Type::string(),
                'description' => '详细内容',
            ],
            'created_at'        => [
                'type'        => Type::string(),
                'description' => '创建时间',
            ],
        ];
    }

    protected function resolveCreatedAtField($root, $args)
    {
        return carbon($root->created_at)->toDateTimeString();
    }
}
