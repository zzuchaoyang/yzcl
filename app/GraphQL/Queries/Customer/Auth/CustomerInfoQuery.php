<?php

namespace App\GraphQL\Queries\Customer\Auth;

use App\GraphQL\Queries\BaseQuery;
use App\Models\Customer\Customer;
use GraphQL;

class CustomerInfoQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'customerInfo',
        'description' => '用户登录信息',
    ];

    public function type()
    {
        return GraphQL::type('Customer');
    }

    public function args()
    {
        return [];
    }

    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Customer;
    }

    public function resolve($root, $args)
    {
        return auth('customer')->user();
    }
}
