<?php

namespace App\GraphQL\Mutations\Customer\Application;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Customer\CooperationApplication;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSupplier;
use GraphQl;
use GraphQL\Type\Definition\Type;
use DB;

class CustomerCooperationApplicationMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'customerCooperationApplication',
        'description' => '客户申请与供应商建立合作关系',
    ];

    public function type()
    {
        return GraphQl::type('CustomerSupplier');
    }

    public function args()
    {
        return [
            'customer_id'   => [
                'name'  => 'customer_id',
                'type'  => Type::int(),
            ],
            'supplier_id'   => [
                'name'  => 'supplier_id',
                'type'  => Type::nonNull(Type::int()),
                'rules' => ['required'],
            ],
        ];
    }

    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Customer;
    }

    public function resolve($root, $args)
    {
        if (CustomerSupplier::where('supplier_id', $args['supplier_id'])->where('customer_id', user()->id)->exists()) {
            throw new GeneralException('与该供应商已经存在合作关系!');
        }

        return DB::transaction(function () use ($args) {
            $cooperationApplication = new CooperationApplication();

            $cooperationApplication->fill([
                'sender_id'     => user()->id,
                'sender_type'   => 'customer',
                'receiver_id'   => array_get($args, 'supplier_id'),
                'receiver_type' => 'supplier',
            ]);

            $cooperationApplication->save();

            $customerSupplier = new CustomerSupplier();
            $customerSupplier->fill([
                'supplier_id'                => array_get($args, 'supplier_id'),
                'customer_id'                => user()->id,
                'status'                     => CustomerSupplier::STATUS_WAIT,
                'cooperation_application_id' => $cooperationApplication->id,
            ]);
            $customerSupplier->save();

            return $customerSupplier;
        });
    }
}
