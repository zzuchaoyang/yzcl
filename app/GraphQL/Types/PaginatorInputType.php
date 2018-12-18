<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;

class PaginatorInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'PaginatorInput',
        'description' => '分页信息对象',
    ];

    public function fields()
    {
        return [
            'page'  => [
                'name'        => 'page',
                'type'        => Type::int(),
                'description' => 'Display a specific page',
                'rules'       => ['required'],
            ],
            'limit' => [
                'name'        => 'limit',
                'type'        => Type::int(),
                'description' => 'Limit the items per page',
                'rules'       => ['required'],
            ],
            'sort'  => [
                'name'        => 'sort',
                'type'        => Type::string(),
                'description' => '排序',
                'rules'       => ['string'],
            ],
            'sorts' => [
                'name'        => 'sorts',
                'type'        => Type::listOf(Type::string()),
                'description' => '排序',
                'rules'       => ['array'],
            ],
        ];
    }
}