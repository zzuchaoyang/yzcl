<?php

namespace App\GraphQL\Types\Order\OrderProduct;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class OrderProductType extends BaseType
{
    protected $attributes = [
        'name'        => 'OrderProduct',
        'description' => '订单的商品信息',
    ];

    public function fields()
    {
        return [
            'id'                => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'id',
            ],
            'order'             => [
                'type'        => GraphQL::type('Order'),
                'description' => '订单信息',
            ],
            'order_id'             => [
                'type'        => Type::int(),
                'description' => '订单id',
            ],
            'product_id'             => [
                'type'        => Type::int(),
                'description' => '商品id',
            ],
            'product'  => [
                'type'        => GraphQL::type('Product'),
                'description' => '商品信息',
            ],
            'product_number'     => [
                'type'        => Type::int(),
                'description' => '商品数量',
            ],
            'product_price'      => [
                'type'        => Type::float(),
                'description' => '商品单价',
            ],
            'product_total_price'      => [
                'type'        => Type::float(),
                'description' => '商品总价格',
            ],
            'send_number'        => [
                'type'        => Type::int(),
                'description' => '发出商品数量',
            ],
            'send_price'    => [
                'type'        => Type::float(),
                'description' => '发出商品单价',
            ],
            'send_total_price'        => [
                'type'        => Type::float(),
                'description' => '发出商品的总价格',
            ],
            'product_name'        => [
                'type'        => Type::string(),
                'description' => '商品名字',
            ],
            'product_picture'        => [
                'type'        => Type::string(),
                'description' => '商品封面图',
            ],
            'product_code'        => [
                'type'        => Type::string(),
                'description' => '商品条码',
            ],
            'brand_name'        => [
                'type'        => Type::string(),
                'description' => '商品品牌名字',
            ],
            'product_order_unit'        => [
                'type'        => Type::string(),
                'description' => '商品订货单位',
            ],
            'product_specifications'        => [
                'type'        => Type::string(),
                'description' => '商品订货规格',
            ],
        ];
    }

    protected function resolveProductPriceField($root, $args)
    {
        return  sprintf('%.2f', $root->product_price);
    }

    protected function resolveSendPriceField($root, $args)
    {
        return  sprintf('%.2f', $root->send_price);
    }

    protected function resolveSendTotalPriceField($root, $args)
    {
        return  sprintf('%.2f', $root->send_total_price);
    }

    protected function resolveProductTotalPriceField($root, $args)
    {
        return  sprintf('%.2f', $root->product_total_price);
    }
}
