<?php

namespace App\GraphQL\Types\Product\Product;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class RatioConfigureType extends BaseType
{
    protected $attributes = [
        'name'        => 'ratioConfigure',
        'description' => '价格阶段上浮比例配置',
    ];

    public function fields()
    {
        return [
            'one_radio'            => [
                'name'        => 'one_radio',
                'type'        => Type::float(),
                'description' => '第一阶段上浮比例',
            ],
            'two_radio'            => [
                'name'        => 'two_radio',
                'type'        => Type::float(),
                'description' => '第二阶段上浮比例',
            ],
            'three_radio'            => [
                'name'        => 'one_radio',
                'type'        => Type::float(),
                'description' => '第三阶段上浮比例',
            ],
            'four_radio'            => [
                'name'        => 'one_radio',
                'type'        => Type::float(),
                'description' => '第四阶段上浮比例',
            ],
            'five_radio'            => [
                'name'        => 'one_radio',
                'type'        => Type::float(),
                'description' => '第五阶段上浮比例',
            ],
        ];
    }
}
