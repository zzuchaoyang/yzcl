<?php

namespace App\GraphQL\Types\Product\Brand;

use App\GraphQL\Types\BaseType;
use App\Models\Product\Brand;
use GraphQL\Type\Definition\Type;
use GraphQL;

class BrandType extends BaseType
{
    protected $attributes = [
        'name'        => 'Brand',
        'description' => '品牌',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::int(),
                'description' => 'id',
            ],
            'supplier_id'  => [
                'type'        => Type::int(),
                'description' => '供应商id',
            ],
            'supplier'      => [
                'type'        => GraphQL::type('Supplier'),
                'description' => '供应商',
            ],
            'products'      => [
                'type'        => Type::listOf(GraphQL::type('Product')),
                'description' => '供应商',
            ],
            'name'  => [
                'type'        => Type::string(),
                'description' => '名牌名称',
            ],
            'parent_brand'         => [
                'type'        => GraphQL::type('Brand'),
                'description' => '父级品牌',
            ],
            'level'    => [
                'type'        => Type::int(),
                'description' => '分类层级',
            ],
            'is_last_stage'      => [
                'type'        => Type::boolean(),
                'description' => '是否末级品牌',
            ],
            'quantity'        => [
                'type'        => Type::int(),
                'description' => '品牌下的商品数量',
            ],
            'manufacturer'    => [
                'type'        => Type::string(),
                'description' => '生产厂商',
            ],
            'status'    => [
                'type'        => Type::string(),
                'description' => '品牌状态',
            ],
            'logo_pic'    => [
                'type'        => Type::string(),
                'description' => '品牌图片',
            ],
            'price_increase_ratio'    => [
                'type'        => GraphQL::type('BrandPriceIncreaseRatio'),
                'description' => '阶梯上浮比例配置',
            ],
        ];
    }

    protected function resolveparentBrandField($root, $args)
    {
        return Brand::find($root->pid);
    }
}
