<?php

namespace App\GraphQL\Types\Product\Input;


use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class BrandPriceIncreaseRatioTypeInput extends BaseType
{
    protected $inputObject = true;
    protected $attributes = [
        'name'        => 'BrandPriceIncreaseRatioInput',
        'description' => '阶梯定价上浮比例配置信息',
    ];

    public function fields()
    {
        return [
            'one_increase_ratio'       => [
                'type'        => Type::float(),
                'description' => '一级供货价上浮比例',
            ],
            'two_increase_ratio'       => [
                'type'        => Type::float(),
                'description' => '二级供货价上浮比例',
            ],
            'three_increase_ratio'       => [
                'type'        => Type::float(),
                'description' => '三级供货价上浮比例',
            ],
            'four_increase_ratio'       => [
                'type'        => Type::float(),
                'description' => '四级供货价上浮比例',
            ],
            'five_increase_ratio'       => [
                'type'        => Type::float(),
                'description' => '五级供货价上浮比例',
            ],

        ];
    }
}