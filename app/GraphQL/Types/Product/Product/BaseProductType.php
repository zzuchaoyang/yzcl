<?php

namespace App\GraphQL\Types\Product\Product;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class BaseProductType extends BaseType
{
    protected $attributes = [
        'name'        => 'BaseProduct',
        'description' => '商品基础信息',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::int(),
                'description' => 'id',
            ],
            'name'  => [
                'name'        => 'name',
                'type'        => Type::string(),
                'description' => '商品名称',
            ],
            'bar_code'         => [
                'name'        => 'bar_code',
                'type'        => Type::string(),
                'description' => '商品条码',
            ],
            'unit'         => [
                'name'        => 'unit',
                'type'        => Type::string(),
                'description' => '商品单位',
            ],
            'retail_price'         => [
                'name'        => 'retail_price',
                'type'        => Type::float(),
                'description' => '建议零售价',
            ],
            'product_category_code'         => [
                'name'        => 'product_category_code',
                'type'        => Type::string(),
                'description' => '商品分类编号',
            ],
        ];
    }
}
