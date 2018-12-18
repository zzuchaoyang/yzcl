<?php

namespace App\GraphQL\Queries\Order;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Types\PaginationType;
use App\Models\Customer\Customer;
use App\Models\Order\OrderLog;
use App\Models\Supplier\Driver;
use App\Models\Supplier\Supplier;
use GraphQL;
use GraphQL\Type\Definition\Type;

class OrderLogPaginatorQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'orderLogPaginator',
        'description' => '订单日志列表',
    ];

    public function type()
    {
        return new PaginationType('OrderLog');
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
            'order_id'  => [
                'name'        => 'order_id',
                'type'        => Type::int(),
                'description' => '订单id',
            ],
        ];
    }

    public function authenticated($root, $args, $currentUser)
    {
        $user = user();

        return $user instanceof Supplier || $user instanceof Customer || $user instanceof Driver;
    }

    protected function wheres()
    {
        $wheres = [
            'order_id',
        ];

        return $wheres;
    }

    public function resolve($root, $args, $context, $info)
    {
        return  OrderLog::getGraphQLPaginator($this->getConditions($args), $info);
    }
}
