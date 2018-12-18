<?php

namespace App\GraphQL\Types\Customer\Customer;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;
use GraphQL;

class CustomerType extends BaseType
{
    protected $attributes = [
        'name'        => 'Customer',
        'description'   => '客户信息',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::id(),
                'description' => '客户id',
            ],
            'user_id'            => [
                'type'        => Type::id(),
                'description' => '客户id',
            ],
            'name'            => [
                'type'        => Type::string(),
                'description' => '客户姓名',
            ],
            'mobile'          => [
                'type'        => Type::string(),
                'description' => '客户手机号',
            ],
            'customerSupplier' => [
                'name'        => 'customerSupplier',
                'type'        => Type::listOf(GraphQL::type('CustomerSupplier')),
                'description' => '合作关系',
            ],
            'gender'          => [
                'type'        => Type::string(),
                'description' => '性别',
            ],
            'avatar'          => [
                'type'        => Type::string(),
                'description' => '头像',
            ],
            'birthed_at'      => [
                'type'        => Type::string(),
                'description' => '出生日期',
            ],
            'store_info'      => [
                'type'        => GraphQL::type('CustomerStoreInfo'),
                'description' => '店铺信息',
            ],
            'role' => [
                'type'        => Type::string(),
                'description' => '角色',
            ],

            // 客户销售统计附加字段
            'order_count' => [
                'type'        => Type::int(),
                'description' => '订单数量',
            ],
            'order_number' => [
                'type'        => Type::int(),
                'description' => '订货总数量',
            ],
            'order_price' => [
                'type'        => Type::float(),
                'description' => '订货总金额',
            ],
            'real_order_number' => [
                'type'        => Type::int(),
                'description' => '实际发货数量',
            ],
            'real_order_price' => [
                'type'        => Type::float(),
                'description' => '实际销售总额',
            ],
            //查看客户详情-历史成交金额
            'history_commission' => [
                'type'        => Type::float(),
                'description' => '历史成交金额',
            ],
            'city' => [
                'type'        => Type::string(),
                'description' => '所在城市',
            ],
            'time' => [
                'type'        => Type::string(),
                'description' => '所在城市',
            ],
        ];
    }

    protected function resolveUserIdField($root, $args)
    {
        return $root->id;
    }

    protected function resolveRoleField($root, $args)
    {
        return '客户';
    }

    protected function resolveGenderField($root, $args)
    {
        return (string) $root->gender ? '男' : '女';
    }

    protected function resolveBirtheAdtField($root, $args)
    {
        return (string) $root->birthed_at ? carbon($root->birthed_at)->toDateTimeString() : '';
    }
}
