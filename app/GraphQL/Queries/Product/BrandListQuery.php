<?php

namespace App\GraphQL\Queries\Product;

use App\GraphQL\Queries\BaseQuery;
use App\Models\Product\Brand;
use GraphQL;
use GraphQL\Type\Definition\Type;

class BrandListQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'brandList',
        'description' => '获取所有品牌',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Brand'));
    }

    public function args()
    {
        return [
            'more'  => [
                'name'        => 'more',
                'type'        => GraphQL::type('BrandInput'),
                'description' => '更多搜索字段',
            ],
        ];
    }

    public function resolve($root, $args, $context, $info)
    {
        return Brand::queryBrand($args);
    }
}
