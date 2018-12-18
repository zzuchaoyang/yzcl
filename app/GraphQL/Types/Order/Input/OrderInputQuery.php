<?php

namespace App\GraphQL\Types\Order\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class OrderInputQuery extends BaseType
{
    /*
    * Uncomment following line to make the type input object.
    * http://graphql.org/learn/schema/#input-types
    */
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'OrderInputQuery',
        'description' => '订单列表查询的字段',
    ];

    public function fields()
    {
        return [
            'id'  => [
                'name'        => 'id',
                'type'        => Type::int(),
                'description' => 'id',
            ],
            'supplier_id'  => [
                'name'        => 'supplier_id',
                'type'        => Type::int(),
                'description' => '供货商id',
            ],
            'order_number'  => [
                'name'        => 'order_number',
                'type'        => Type::string(),
                'description' => '订单编号',
            ],
            'customer_id'  => [
                'name'        => 'customer_id',
                'type'        => Type::int(),
                'description' => '客户id',
            ],
            'salesman_id'  => [
                'name'        => 'salesman_id',
                'type'        => Type::int(),
                'description' => '业务员id',
            ],
            'status'  => [
                'name'        => 'status',
                'type'        => Type::listOf(Type::string()),
                'description' => '订单状态',
            ],
            'send_status'     => [
                'name'        => 'send_status',
                'type'        => Type::string(),
                'description' => '订货配货状态',
            ],
            'order_at_start'  => [
                'name'        => 'order_at_start',
                'type'        => Type::string(),
                'description' => '订货起始时间',
            ],
            'order_at_end'  => [
                'name'        => 'order_at_end',
                'type'        => Type::string(),
                'description' => '订货结束时间',
            ],
            'mobile'  => [
                'name'        => 'mobile',
                'type'        => Type::string(),
                'description' => '联系人手机号',
            ],
            'salesman_name'  => [
                'name'        => 'salesman_name',
                'type'        => Type::string(),
                'description' => '业务员姓名',
            ],
            'customer_name'  => [
                'name'        => 'customer_name',
                'type'        => Type::string(),
                'description' => '客户名称',
            ],
            'driver_id'  => [
                'name'        => 'driver_id',
                'type'        => Type::int(),
                'description' => '司机id',
            ],
            'car_number'  => [
                'name'        => 'car_number',
                'type'        => Type::string(),
                'description' => '车牌号',
            ],
            'key_word'  => [
                'name'        => 'key_word',
                'type'        => Type::string(),
                'description' => '订单编号或者供货商名称',
            ],
            'inner_message_id' => [
                'name'        => 'inner_message_id',
                'type'        => Type::int(),
                'description' => '站内信id',
                'rules'       => ['exists:inner_messages,id'],
            ],
        ];
    }
}
