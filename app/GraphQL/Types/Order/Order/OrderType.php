<?php

namespace App\GraphQL\Types\Order\Order;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class OrderType extends BaseType
{
    protected $attributes = [
        'name'        => 'Order',
        'description' => '订单',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'id',
            ],
            'supplier_id'      => [
                'type'        => Type::int(),
                'description' => '供应商id',
            ],
            'supplier'      => [
                'type'        => GraphQL::type('Supplier'),
                'description' => '供应商',
            ],
            'order_number'  => [
                'type'        => Type::string(),
                'description' => '订单编号',
            ],
            'number'  => [
                'type'        => Type::int(),
                'description' => '订单总数量',
            ],
            'send_number'  => [
                'type'        => Type::int(),
                'description' => '订单发出总数量',
            ],
            'price'         => [
                'type'        => Type::float(),
                'description' => '订单价格',
            ],
            'send_price'         => [
                'type'        => Type::float(),
                'description' => '发出订单价格',
            ],
            'orderProducts'    => [
                'type'        => Type::listOf(GraphQL::type('OrderProduct')),
                'description' => '订单商品详情',
            ],
            'customer'   => [
                'type'        => GraphQL::type('Customer'),
                'description' => '客户',
            ],
            'customer_id'      => [
                'type'        => Type::int(),
                'description' => '客户id',
            ],
            'order_at'      => [
                'type'        => Type::string(),
                'description' => '订单时间',
            ],
            'supplier_name'      => [
                'type'        => Type::string(),
                'description' => '供应商的名称',
            ],
            'supplier_mobile'      => [
                'type'        => Type::string(),
                'description' => '供应商手机号',
            ],
            'customer_name'      => [
                'type'        => Type::string(),
                'description' => '零售店名称',
            ],
            'customer_mobile'      => [
                'type'        => Type::string(),
                'description' => '零售店手机号',
            ],
            'status'        => [
                'type'        => Type::string(),
                'description' => '订单状态',
            ],
            'send_status'        => [
                'type'        => Type::string(),
                'description' => '订单发货状态',
            ],
            'status_alias'        => [
                'type'        => Type::string(),
                'description' => '订单状态',
            ],
            'send_status_alias'        => [
                'type'        => Type::string(),
                'description' => '订单发货状态',
            ],
            'car_number'    => [
                'type'        => Type::string(),
                'description' => '车牌号',
            ],
            'driver_id'        => [
                'type'        => Type::int(),
                'description' => '司机id',
            ],
            'driver'        => [
                'type'        => GraphQL::type('Driver'),
                'description' => '司机信息',
            ],
            'send_at'        => [
                'type'        => Type::string(),
                'description' => '订单送达时间',
            ],
            'send_start_at'        => [
                'type'        => Type::string(),
                'description' => '订单开始配送时间',
            ],
            'position_info'        => [
                'type'        => GraphQL::type('PositionInfo'),
                'description' => '订单配送信息',
            ],
            'can_force_signed'        => [
                'type'        => Type::boolean(),
                'description' => '订单是否能强制签收',
            ],
            'is_edit'        => [
                'type'        => Type::boolean(),
                'description' => '订单是否修改过',
            ],
            'signed_at'        => [
                'type'        => Type::string(),
                'description' => '订单签收时间',
            ],
            'receiving_address' => [
                'type'        => Type::string(),
                'description' => '订单的收货地址',
            ],
        ];
    }

    protected function resolvePriceField($root, $args)
    {
        return  sprintf('%.2f', $root->price);
    }

    protected function resolveSendPriceField($root, $args)
    {
        return  sprintf('%.2f', $root->send_price);
    }

    protected function resolveStatusAliasField($root, $args)
    {
        if ($status = $root->status) {
            return  $root->status_alias = array_get($root->allStatus, $status);
        }
    }

    protected function resolveSendStatusAliasField($root, $args)
    {
        if ($sendStatus = $root->send_status) {
            return  $root->send_status_alias = array_get($root->sendStatus, $sendStatus);
        }
    }

    protected function resolveOrderAtField($root, $args)
    {
        if ($root->order_at) {
            return carbon($root->order_at)->toDateTimeString();
        }
    }

    protected function resolveSendStartAtField($root, $args)
    {
        if ($root->send_start_at) {
            return carbon($root->send_start_at)->toDateTimeString();
        }
    }

    protected function resolveSendAtField($root, $args)
    {
        if ($root->send_at) {
            return carbon($root->send_at)->toDateTimeString();
        }
    }

    protected function resolveSignedAtField($root, $args)
    {
        if ($root->signed_at) {
            return carbon($root->signed_at)->toDateTimeString();
        }
    }

    protected function resolveCanForceSignedField($root, $args)
    {
        return $root->canForceSigned();
    }
}
