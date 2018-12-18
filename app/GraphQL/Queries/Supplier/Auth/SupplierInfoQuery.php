<?php

namespace App\GraphQL\Queries\Supplier\Auth;

use App\GraphQL\Queries\BaseQuery;
use GraphQL;

class SupplierInfoQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'supplierInfo',
        'description' => '用户登录信息',
    ];

    public function type()
    {
        return GraphQL::type('Supplier');
    }

    public function args()
    {
        return [];
    }

    public function authenticated($root, $args, $currentUser)
    {
        return !!$currentUser;
    }

    public function resolve($root, $args)
    {
        return auth()->user();
    }
}
