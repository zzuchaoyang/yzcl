<?php

namespace App\GraphQL\Queries\Finance;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Types\PaginationType;
use App\Models\Finance\Loan;
use App\Models\Order\Order;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;

class LoanPaginatorQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'loanPaginator',
        'description' => '金融列表',
    ];

    public function type()
    {
        return new PaginationType('Loan');
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
        ];
    }

    public function resolve($root, $args, $context, $info)
    {
        return  Loan::getGraphQLPaginator($this->getConditions($args), $info);
    }
}
