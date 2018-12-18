<?php

namespace App\GraphQL\Types\Supplier\Supplier;


use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class PriceIncreaseRatioType extends BaseType
{
    protected $attributes = [
        'name'        => 'PriceIncreaseRatio',
        'description' => '价格上浮比例配置',
    ];

    public function fields()
    {
        return [
            'one_ratio'       => [
                'type'        => Type::float(),
                'description' => '一级供货价上浮比例',
            ],
            'two_ratio'       => [
                'type'        => Type::float(),
                'description' => '二级供货价上浮比例',
            ],
            'three_ratio' => [
                'type'        => Type::float(),
                'description' => '三级供货价上浮比例',
            ],
            'four_ratio' => [
                'type'        => Type::float(),
                'description' => '四级供货价上浮比例',
            ],
            'five_ratio' => [
                'type'        => Type::float(),
                'description' => '五级供货价上浮比例',
            ],
        ];
    }
}