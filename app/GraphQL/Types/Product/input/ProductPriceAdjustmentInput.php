<?php

namespace App\GraphQL\Types\Product\Input;

use App\GraphQL\Types\BaseType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ProductPriceAdjustmentInput extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'ProductPriceAdjustmentInput',
        'description' => '商品调整表查询的字段',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::int(),
                'description' => 'id',
            ],
           'supplier_id'  => [
                'name'        => 'supplier_id',
                'type'        => Type::int(),
                'description' => '供应商id',
             ],
            'products'  => [
                'name'        => 'products',
                'type'        => Type::listOf(GraphQL::type('ProductInput')),
                'description' => '商品信息',
            ],
            'start_at'  => [
                'name'        => 'start_at',
                'type'        => Type::string(),
                'description' => '制单开始日期',
            ],
            'end_at'  => [
                'name'        => 'end_at',
                'type'        => Type::string(),
                'description' => '制单结束日期',
            ],
            'bar_code'  => [
                'name'        => 'bar_code',
                'type'        => Type::string(),
                'description' => '商品条码',
            ],
            'product_name'  => [
                'name'        => 'product_name',
                'type'        => Type::string(),
                'description' => '商品名称',
            ],
            'code'  => [
                'name'        => 'code',
                'type'        => Type::string(),
                'description' => '调价单编码',
            ],
            'effective_at'  => [
                'name'        => 'effective_at',
                'type'        => Type::string(),
                'description' => '生效时间',
            ],
            'examine_at'  => [
                'name'        => 'examine_at',
                'type'        => Type::string(),
                'description' => '审核时间',
            ],
            'author_id'  => [
                'name'        => 'author_id',
                'type'        => Type::int(),
                'description' => '审核人id',
            ],
            'number'  => [
                'name'        => 'number',
                'type'        => Type::int(),
                'description' => '调价商品数量',
            ],
            'status'  => [
                'name'        => 'status',
                'type'        => Type::string(),
                'description' => '调价单状态',
            ],
            'producer_id'  => [
                'name'        => 'producer_id',
                'type'        => Type::int(),
                'description' => '制单人id',
            ],
            'created_at'  => [
                'name'        => 'created_at',
                'type'        => Type::string(),
                'description' => '制单时间',
            ],
        ];
    }
}
