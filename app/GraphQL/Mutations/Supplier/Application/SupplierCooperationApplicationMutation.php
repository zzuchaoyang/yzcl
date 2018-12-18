<?php

namespace App\GraphQL\Mutations\Supplier\Application;

use App\GraphQL\Mutations\BaseMutation;
use App\Exceptions\GeneralException;
use App\Models\Customer\CooperationApplication;
use App\Models\Customer\CustomerSupplier;
use App\Models\Message\InnerMessage;
use App\Models\Supplier\Supplier;
use GraphQL\Type\Definition\Type;
use GraphQL;
use DB;

class SupplierCooperationApplicationMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'supplierCooperationApplication',
        'description' => '供应商申请合作关系',
    ];

    public function type()
    {
        return GraphQL::type('CustomerSupplier');
    }

    public function args()
    {
        return [
            'customer_id'   => [
                'name'  => 'customer_id',
                'type'  => Type::nonNull(Type::int()),
                'rules' => ['required'],
            ],
            'supplier_id'   => [
                'name'  => 'supplier_id',
                'type'  => Type::nonNull(Type::int()),
                'rules' => ['required'],
            ],
            'supply_grade'   => [
                'name'  => 'supply_grade',
                'type'  => Type::nonNull(Type::string()),
                'rules' => ['required'],
            ],
        ];
    }

    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Supplier;
    }

    public function resolve($root, $args)
    {
        if (CustomerSupplier::where('supplier_id', $args['supplier_id'])->where('customer_id',
            $args['customer_id'])->exists()) {
            throw new GeneralException('与该客户已经存在合作关系!');
        }

        return DB::transaction(function () use ($args) {
            $cooperationApplication = new CooperationApplication();

            $cooperationApplication->fill([
                'sender_id'     => array_get($args, 'supplier_id'),
                'sender_type'   => 'supplier',
                'receiver_id'   => array_get($args, 'customer_id'),
                'receiver_type' => 'customer',
            ]);

            $cooperationApplication->save();

            $customerSupplier = new CustomerSupplier();
            $customerSupplier->fill([
                'supplier_id'                => array_get($args, 'supplier_id'),
                'customer_id'                => array_get($args, 'customer_id'),
                'status'                     => CustomerSupplier::STATUS_WAIT,
                'cooperation_application_id' => $cooperationApplication->id,
                'supply_grade'               => array_get($args, 'supply_grade'),
            ]);
            $customerSupplier->save();

            $innerMessage = new InnerMessage();
            $innerMessage->fill([
                'type'          => 'application',
                'sender_id'     => array_get($args, 'supplier_id'),
                'sender_type'   => 'supplier',
                'receiver_id'   => array_get($args, 'customer_id'),
                'receiver_type' => 'customer',
                'relation_id'   => $cooperationApplication->id,
                'is_top'        => true,
                'relation_type' => 'customer_supplier',
                'content'       => '['.array_get(Supplier::find(array_get($args, 'supplier_id'))->info,'company.gsmc').']申请成为您的供应商',
            ]);
            $innerMessage->save();

            return $customerSupplier;
        });
    }
}
