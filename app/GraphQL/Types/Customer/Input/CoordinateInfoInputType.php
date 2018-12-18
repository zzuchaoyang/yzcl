<?php

namespace App\GraphQL\Types\Customer\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class CoordinateInfoInputType extends BaseType
{
    protected $attributes = [
        'name'        => 'CoordinateInfoInput',
        'description' => '位置坐标',
    ];

    protected $inputObject = true;

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
