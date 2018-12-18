<?php

namespace App\GraphQL\Mutations\Customer;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Customer\Customer;
use GraphQL;
use GraphQL\Type\Definition\Type;

class UpdateCustomerMutation extends BaseMutation
{

    protected $attributes = [
        'name'        => 'updateCustomer',
        'description' => '更新客户基本信息',
    ];

    public function type()
    {
        return GraphQL::type('Customer');
    }

    public function args()
    {
        return [
            'data' => [
                'type'        => Type::nonNull(GraphQL::type('UpdateCustomerInput')),
                'description' => '更新客户基本信息 输入的信息',
                'rules'       => [ 'required' ],
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param $currentUser
     *
     * @return bool
     */
    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Customer;
    }

    public function resolve($root, $args)
    {
        $id = array_get($args, 'data.id');

        if (!$customer = Customer::find($id)){
            throw new GeneralException('数据不存在，更新失败。');
        }

        $data = deep_merge(array_only($customer->toArray(), array_keys(array_get($args, 'data'))), array_get($args, 'data'));

        $customer->update($data);

        return Customer::find($id);
    }
}
