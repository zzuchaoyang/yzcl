<?php

namespace App\GraphQL\Types\Product\Input;

use App\GraphQL\Types\BaseType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class BrandInput extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'BrandInput',
        'description' => '品牌表查询的字段',
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
                'description' => '品牌名称',
            ],
            'supplier_id'  => [
                'name'        => 'supplier_id',
                'type'        => Type::int(),
                'description' => '供应商id',
            ],
            'pid'  => [
                'name'        => 'pid',
                'type'        => Type::int(),
                'description' => '父级id',
            ],
            'level'  => [
                'name'        => 'level',
                'type'        => Type::int(),
                'description' => '品牌层级',
            ],
            'is_last_stage'  => [
                'name'        => 'is_last_stage',
                'type'        => Type::boolean(),
                'description' => '是否末级品牌',
            ],
            'quantity'  => [
                'name'        => 'quantity',
                'type'        => Type::int(),
                'description' => '品牌下的商品数量',
            ],
            'manufacturer'  => [
                'name'        => 'manufacturer',
                'type'        => Type::string(),
                'description' => '生产厂商',
            ],
            'status'  => [
                'name'        => 'status',
                'type'        => Type::string(),
                'description' => '品牌状态',
            ],
            'logo_pic'  => [
                'name'        => 'logo_pic',
                'type'        => Type::string(),
                'description' => '品牌图片',
            ],
            'price_increase_ratio'    => [
                'type'        => GraphQL::type('BrandPriceIncreaseRatioInput'),
                'description' => '阶梯上浮比例配置',
            ],

        ];
    }
}
