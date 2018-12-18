<?php

namespace App\GraphQL\Queries\Product;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Types\PaginationType;
use App\Models\Product\ProductPriceAdjustment;
use GraphQL;
use GraphQL\Type\Definition\Type;
use MatrixLab\LaravelAdvancedSearch\ModelScope;
use MatrixLab\LaravelAdvancedSearch\When;

class ProductPriceAdjustmentQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'productPriceAdjustment',
        'description' => '商品调价单列表',
    ];

    public function type()
    {
        return new PaginationType('ProductPriceAdjustment');
    }

    public function args()
    {
        return [
            'paginator'  => [
                'name'        => 'paginator',
                'type'        => Type::nonNull(GraphQL::type('PaginatorInput')),
                'description' => '分页排序',
                'rules'       => ['required'],
            ],
            'more'  => [
                'name'        => 'more',
                'type'        => GraphQL::type('ProductPriceAdjustmentInput'),
                'description' => '更多搜索字段',
            ],
        ];
    }

    protected function wheres()
    {
        $wheres = [
            new ModelScope('product', $this->getInputArgs()),
            'code.like'           => When::make($this->getInputArgs('code'))->success('%'.$this->getInputArgs('code').'%'),
            'created_at.gt'       => carbon($this->getInputArgs('start_at'))->startOfDay(),
            'created_at.le'       => carbon($this->getInputArgs('end_at'))->endOfDay(),
            'id',
            'status',
        ];

        return $wheres;
    }

    public function resolve($root, $args, $context, $info)
    {
        return ProductPriceAdjustment::getGraphQLPaginator($this->getConditions($args), $info);
    }
}
