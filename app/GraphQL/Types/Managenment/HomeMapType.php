<?php

namespace App\GraphQL\Types\Managenment;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class HomeMapType extends BaseType
{
    protected $attributes = [
        'name'        => 'HomeMap',
        'description' => '首页地图坐标信息',
    ];

    public function fields()
    {
        return [
            'type'            => [
                'type'        => Type::string(),
                'description' => '类型',
            ],
            'name'            => [
                'type'        => Type::string(),
                'description' => '名称',
            ],
            'latitude'  => [
                'type'        => Type::float(),
                'description' => '经度',
            ],
            'longitude'  => [
                'type'        => Type::float(),
                'description' => '纬度',
            ],
        ];
    }
}
