<?php

namespace App\GraphQL\Queries\Managenment;

use App\GraphQL\Queries\BaseQuery;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSupplier;
use App\Models\Supplier\Supplier;
use GraphQL\Type\Definition\Type;
use GraphQL;

class HomeMapQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'homeMap',
        'description' => '首页地图统计',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('HomeMap'));
    }

    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Supplier;
    }

    public function args()
    {
        return [
            'customer_name'  => [
                'name'        => 'customer_name',
                'type'        => Type::string(),
                'description' => '客户名称',
            ],
        ];
    }

    public function resolve($root, $args, $context, $info)
    {
        //第一阶段只统计客户的位置
        $customers = Customer::where(function ($q) {
            $q->whereHas('CustomerSupplier', function ($q) {
                $q->where('status', CustomerSupplier::STATUS_NORMAL);
            });
        })->when(array_get($args, 'customer_name'), function ($q) use ($args) {
            $q->where('store_info->name', 'like', '%'.array_get($args, 'customer_name').'%');
        })->get();

        $customersInfo = collect();
        foreach ($customers as $customer) {
            if (isset($customer->store_info) && isset($customer->store_info['position']) && isset($customer->store_info['position']['latitude']) && isset($customer->store_info['position']['longitude'])) {
                $latitude  = $customer->store_info['position']['latitude'];
                $longitude = $customer->store_info['position']['longitude'];
                $customersInfo->push([
                    'type'       => '客户',
                    'name'       => isset($customer->store_info['name']) ? $customer->store_info['name'] : null,
                    'latitude'   => $latitude,
                    'longitude'  => $longitude,
                ]);
            }
        }

        return collect($customersInfo);
    }
}
