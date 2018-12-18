<?php

namespace App\GraphQL\Types\Order\Order;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class CoordinateInfoType extends BaseType
{
    protected $attributes = [
        'name'        => 'CoordinateInfo',
        'description' => '位置坐标',
    ];

    public function fields()
    {
        return [
            'latitude'             => [
                'type'        => Type::string(),
                'description' => '位置的纬度',
            ],
            'longitude'    => [
                'type'        => Type::string(),
                'description' => '位置的经度',
            ],
        ];
    }
}
