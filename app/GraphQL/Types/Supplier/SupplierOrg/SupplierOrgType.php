<?php

namespace App\GraphQL\Types\Supplier\SupplierOrg;

use App\GraphQL\Types\BaseType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class SupplierOrgType extends BaseType
{

    protected $attributes = [
        'name'        => 'SupplierOrg',
        'description' => '供应商组织架构',
    ];

    /*
    * Uncomment following line to make the type input object.
    * http://graphql.org/learn/schema/#input-types
    */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'        => [
                'type'        => Type::id(),
                'description' => 'ID',
            ],
            'name'      => [
                'type'        => Type::string(),
                'description' => '部门名称',
            ],
            'type'      => [
                'type'        => Type::string(),
                'description' => '部门性质',
            ],
            'category'  => [
                'type'        => Type::string(),
                'description' => '部门类别',
            ],
            'status'    => [
                'type'        => Type::boolean(),
                'description' => '状态',
            ],
            'parent_id' => [
                'type'        => Type::int(),
                'description' => '父 ID',
            ],
            'parent'    => [
                'type'        => GraphQL::type('SupplierOrg'),
                'description' => '子集组织架构',
            ],
            'children'  => [
                'type'        => Type::listOf(GraphQL::type('SupplierOrg')),
                'description' => '子集组织架构',
            ],
            'rank'      => [
                'type'        => Type::string(),
                'description' => '级别',
            ],
            'is_tip'    => [
                'type'        => Type::boolean(),
                'description' => '是否为末端',
            ],
            'mobile'    => [
                'type'        => Type::string(),
                'description' => '手机号',
            ],
            'id_card'   => [
                'type'        => Type::string(),
                'description' => '身份证号',
            ],
        ];
    }

}
