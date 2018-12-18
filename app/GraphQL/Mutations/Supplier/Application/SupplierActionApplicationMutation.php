<?php

namespace App\GraphQL\Mutations\Supplier\Application;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Customer\CooperationApplication;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSupplier;
use App\Models\Message\InnerMessage;
use App\Models\Supplier\Supplier;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use GraphQL;

class SupplierActionApplicationMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'supplierActionApplication',
        'description' => '供应商操作合作关系',
    ];

    public function type()
    {
        return GraphQL::type('CooperationApplication');
    }

    public function args()
    {
        return [
            'action'   => [
                'name'  => 'action',
                'type'  => Type::nonNull(Type::string()),
                'rules' => ['required'],
            ],
            'cooperation_application_id'   => [
                'name'  => 'cooperation_application_id',
                'type'  => Type::nonNull(Type::int()),
                'rules' => ['required'],
            ],
            'supply_grade'   => [
                'name'  => 'supply_grade',
                'type'  => Type::string(),
            ],
        ];
    }

    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Customer || user() instanceof Supplier;
    }

    public function resolve($root, $args)
    {
        if (array_get($args, 'action') == '审核') {
            CooperationApplication::where('id', array_get($args, 'cooperation_application_id'))->update([
                'accepted_at' => Carbon::now(),
            ]);
            CustomerSupplier::where('cooperation_application_id', array_get($args, 'cooperation_application_id'))->update([
                'status'       => CustomerSupplier::STATUS_NORMAL,
                'supply_grade' => array_get($args, 'supply_grade'),
            ]);
        } elseif (array_get($args, 'action') == '暂停') {
            CustomerSupplier::where('cooperation_application_id', array_get($args, 'cooperation_application_id'))->update([
                'status' => CustomerSupplier::STATUS_PAUSE,
            ]);
        } elseif (array_get($args, 'action') == '拒绝') {
            CooperationApplication::where('id', array_get($args, 'cooperation_application_id'))->update([
                'rejected_at' => Carbon::now(),
            ]);
            CustomerSupplier::where('cooperation_application_id', array_get($args, 'cooperation_application_id'))->update([
                'status' => CustomerSupplier::STATUS_REJECT,
            ]);
            $cooperationApplication = CustomerSupplier::where('cooperation_application_id', array_get($args, 'cooperation_application_id'))->first();
            if (user() instanceof Supplier) {
                $innerMessage = new InnerMessage();
                $innerMessage->fill([
                    'type'          => 'application',
                    'sender_id'     => $cooperationApplication->supplier_id,
                    'sender_type'   => 'supplier',
                    'receiver_id'   => $cooperationApplication->customer_id,
                    'receiver_type' => 'customer',
                    'relation_id'   => array_get($args, 'cooperation_application_id'),
                    'relation_type' => 'customer_supplier',
                    'content'       => '['.array_get(Supplier::find($cooperationApplication->supplier_id)->info,'company.gsmc').']已拒绝您的合作申请',
                ]);
                $innerMessage->save();
            } else if (user() instanceof Customer){
                InnerMessage::where('relation_type','customer_supplier')->where('relation_id',array_get($args, 'cooperation_application_id'))->update([
                    'is_top' => 0,
                ]);
            }
        } elseif (array_get($args, 'action') == '启用') {
            CooperationApplication::where('id', array_get($args, 'cooperation_application_id'))->update([
                'rejected_at' => null,
            ]);
            CustomerSupplier::where('cooperation_application_id', array_get($args, 'cooperation_application_id'))->update([
                'status' => CustomerSupplier::STATUS_NORMAL,
            ]);
        } elseif (array_get($args, 'action') == '修改供货价') {
            CustomerSupplier::where('cooperation_application_id', array_get($args, 'cooperation_application_id'))->update([
                'supply_grade' => array_get($args, 'supply_grade'),
            ]);
        } elseif (array_get($args, 'action') == '同意') {
            CooperationApplication::where('id', array_get($args, 'cooperation_application_id'))->update([
                'accepted_at' => Carbon::now(),
            ]);
            CustomerSupplier::where('cooperation_application_id', array_get($args, 'cooperation_application_id'))->update([
                'status' => CustomerSupplier::STATUS_NORMAL,
            ]);
            if (user() instanceof Customer){
                InnerMessage::where('relation_type','customer_supplier')->where('relation_id',array_get($args, 'cooperation_application_id'))->update([
                    'is_top' => 0,
                ]);
            }
        } elseif (array_get($args, 'action') == '重新申请') {
            $cooperationApplication = CustomerSupplier::where('cooperation_application_id', array_get($args, 'cooperation_application_id'))->first();

            CooperationApplication::where('id', array_get($args, 'cooperation_application_id'))->update([
                'rejected_at'   => null,
                'sender_type'   => 'customer',
                'sender_id'     => $cooperationApplication->customer_id,
                'receiver_type' => 'supplier',
                'receiver_id'   => $cooperationApplication->supplier_id,
            ]);

            CustomerSupplier::where('cooperation_application_id', array_get($args, 'cooperation_application_id'))->update([
                'status'       => CustomerSupplier::STATUS_WAIT,
                'supply_grade' => null,
            ]);
        }

        return CooperationApplication::find(array_get($args, 'cooperation_application_id'))->first();
    }
}
