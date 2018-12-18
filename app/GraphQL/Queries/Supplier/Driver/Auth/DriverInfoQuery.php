<?php

namespace App\GraphQL\Queries\Supplier\Driver\Auth;

use App\GraphQL\Queries\BaseQuery;
use App\Models\Supplier\Driver;
use GraphQL;

class DriverInfoQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'driverInfo',
        'description' => '用户登录信息',
    ];

    public function type()
    {
        return GraphQL::type('Driver');
    }

    public function args()
    {
        return [];
    }

    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Driver;
    }

    public function resolve($root, $args)
    {
        return auth('supplier_driver')->user();
    }
}
