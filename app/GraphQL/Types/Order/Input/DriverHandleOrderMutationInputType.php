<?php

namespace App\GraphQL\Types\Order\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class DriverHandleOrderMutationInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'DriverHandleOrderMutationInput',
        'description' => '司机操作订单创参数',
    ];

    public function fields()
    {
        return [
            'id'          => [
                'name'        => 'id',
                'type'        => Type::nonNull(Type::int()),
                'description' => '订单id',
                'rules'       => ['exists:orders,id'],
            ],
            'transition' => [
                'name'        => 'transition',
                'type'        => Type::string(),
                'description' => '此订单需要执行的操作',
                'rules'       => ['required'],
            ],
            'position' => [
                'name'        => 'position',
                'type'        => Type::string(),
                'description' => '当前处于的地理位置',
            ],
            'current_latitude' => [
                'name'        => 'current_latitude',
                'type'        => Type::string(),
                'description' => '当前处于的位置的纬度',
            ],
            'current_longitude' => [
                'name'        => 'current_longitude',
                'type'        => Type::string(),
                'description' => '当前处于的位置的经度',
            ],
        ];
    }
}
