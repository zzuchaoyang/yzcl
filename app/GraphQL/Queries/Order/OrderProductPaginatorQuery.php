<?php

namespace App\GraphQL\Queries\Order;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Types\PaginationType;
use App\Models\Order\OrderProduct;
use GraphQL;
use GraphQL\Type\Definition\Type;

class OrderProductPaginatorQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'orderProductPaginator',
        'description' => '订单详情列表',
    ];

    public function type()
    {
        return new PaginationType('OrderProduct');
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
                'type'        => GraphQL::type('OrderProductPaginatorInput'),
                'description' => '更多搜索字段',
            ],
        ];
    }

    protected function wheres()
    {
        $wheres = [
            'order_id',
            'id',
        ];

        return $wheres;
    }

    public function resolve($root, $args, $context, $info)
    {
        //不分页，强制追加参数
        $args['paginator']['limit'] = 1000;

        return  OrderProduct::getGraphQLPaginator($this->getConditions($args), $info);
    }
}
