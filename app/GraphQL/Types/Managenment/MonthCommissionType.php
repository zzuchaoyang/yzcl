<?php

namespace App\GraphQL\Types\Managenment;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class MonthCommissionType extends BaseType
{
    protected $attributes = [
        'name'        => 'MonthCommission',
        'description' => '月业绩统计',
    ];

    public function fields()
    {
        return [
            'data'            => [
                'type'        => GraphQL::Type('MonthData'),
                'description' => '月业绩统计',
            ]
        ];
    }
}
