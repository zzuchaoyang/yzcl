<?php

namespace App\GraphQL\Types\Managenment;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class MonthDataType extends BaseType
{
    protected $attributes = [
        'name'        => 'MonthData',
        'description' => '月业绩统计',
    ];

    public function fields()
    {
        return [
            'current_month'            => [
                'type'        =>  Type::listOf(GraphQL::Type('MonthCommissionStatics')),
                'description' => '当前月份',
            ],
            'last_month'      => [
                'type'        => Type::listOf(GraphQL::Type('MonthCommissionStatics')),
                'description' => '上月份',
            ],
            'being_approve_order' => [
                'type'        => Type::int(),
                'description' => '待审核订单',
            ],
            'being_send_order' => [
                'type'        => Type::int(),
                'description' => '待发货订单',
            ],
            'being_approve_application' => [
                'type'        => Type::int(),
                'description' => '待审核合作申请',
            ],
            'today_commission_total' => [
                'type'        => Type::float(),
                'description' => '今日签收金额',
            ],
            'today_order_info' => [
                'type'        => GraphQL::type('OrderInfo'),
                'description' => '今日订单信息',
            ],
            'last_order_info' => [
                'type'        => GraphQL::type('OrderInfo'),
                'description' => '昨日订单信息',
            ],
            'month_order_info' => [
                'type'        => GraphQL::type('OrderInfo'),
                'description' => '本月订单信息',
            ],
            'last_month_order_info' => [
                'type'        => GraphQL::type('OrderInfo'),
                'description' => '上月订单信息',
            ],
        ];
    }

    //protected function resolveCreatedAtField($root, $args)
    //{
    //    return (string)$root->created_at->toDateTimeString();
    //}
}
