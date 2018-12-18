<?php

namespace App\GraphQL\Queries\Product;


use App\GraphQL\Queries\BaseQuery;
use App\Models\Product\BaseProduct;
use GraphQL;
use GraphQL\Type\Definition\Type;

class BaseProductQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'BaseProduct',
        'description' => '获取商品基础信息',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('BaseProduct'));
    }

    public function args()
    {
        return [
            'more'  => [
                'name'        => 'more',
                'type'        => GraphQL::type('BaseProductInput'),
                'description' => '更多搜索字段',
            ],
        ];
    }

    public function resolve($root, $args, $context, $info)
    {
        $query = BaseProduct::query();
        if (isset($args['more']['name'])) {
            $query = $query->where('name', $args['more']['name']);
        }
        if (isset($args['more']['bar_code'])) {
            $query = $query->where('bar_code', $args['more']['bar_code']);
        }

        return $query->get();
    }
}