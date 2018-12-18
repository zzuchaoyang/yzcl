<?php

namespace App\GraphQL\Types\Customer\CustomerSupplier;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;
use GraphQL;

class CustomerSupplierType extends BaseType
{
    protected $attributes = [
        'name'        => 'CustomerSupplier',
        'description' => '供应商与用户的合作关系',
    ];

    public function fields()
    {
        return [
            'supplier_id'            => [
                'type'        => Type::int(),
                'description' => '供应商id',
            ],
            'customer_id'          => [
                'type'        => Type::int(),
                'description' => '客户id',
            ],
            'status'          => [
                'type'        => Type::string(),
                'description' => '合作状态',
            ],
            'supply_grade'    => [
                'type'        => Type::string(),
                'description' => '供货价级别',
            ],
            'cooperationApplication' => [
                'name'        => 'cooperationApplication',
                'type'        => GraphQL::type('CooperationApplication'),
                'description' => '合作关系',
            ],
            'cooperation_application_id'   => [
                'type'        => Type::int(),
                'description' => '合作关系id',
            ],
            'status_alias'          => [
                'type'        => Type::string(),
                'description' => '合作状态',
            ],
            'created_at'          => [
                'type'        => Type::string(),
                'description' => '创建时间',
            ],
        ];
    }

    protected function resolveStatusAliasField($root, $args)
    {
        $status   = $root->status;

        return  $root->status_alias = array_get($root->allStatus, $status);
    }


    protected function resolveCreatedAtField($root, $args)
    {
        return (string) $root->created_at ? carbon($root->created_at)->toDateTimeString() : '';
    }

    protected function resolveSupplyGradeField($root, $args)
    {
        $supply_grade = $root->supply_grade;

        return  $supply_grade ? array_get($root->allSupplyGrade, $supply_grade) : null;
    }
}
