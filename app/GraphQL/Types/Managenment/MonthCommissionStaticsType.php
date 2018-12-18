<?php

namespace App\GraphQL\Types\Managenment;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class MonthCommissionStaticsType extends BaseType
{
    protected $attributes = [
        'name'        => 'MonthCommissionStatics',
        'description' => '月业绩统计',
    ];

    public function fields()
    {
        return [
            'day'            => [
                'type'        => Type::string(),
                'description' => '天',
            ],
            'num'      => [
                'type'        => Type::float(),
                'description' => '数量',
            ],
        ];
    }

    //protected function resolveCreatedAtField($root, $args)
    //{
    //    return (string)$root->created_at->toDateTimeString();
    //}
}
