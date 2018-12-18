<?php

namespace App\GraphQL\Types\Supplier\SupplierOrg\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class StoreSupplierOrgMutationInputType extends BaseType
{

    protected $attributes = [
        'name'        => 'StoreSupplierOrgMutationInput',
        'description' => '供应商组织架构输入参数',
    ];

    /*
    * Uncomment following line to make the type input object.
    * http://graphql.org/learn/schema/#input-types
    */
    protected $inputObject = true;

    public function fields()
    {
        return [
            'id'        => [
                'type'        => Type::id(),
                'description' => 'ID',
            ],
            'parent_id' => [
                'type'        => Type::id(),
                'description' => '上级部门 ID',
            ],
            'name'      => [
                'type'        => Type::string(),
                'description' => '部门名称',
                'rules'       => ['required'],
            ],
            'type'      => [
                'type'        => Type::string(),
                'description' => '部门性质',
                'rules'       => [ 'in:部门,人员' ]
            ],
            'category'  => [
                'type'        => Type::string(),
                'description' => '部门类别',
                'rules'       => [ 'in:司机,业务员' ]
            ],
            'status'    => [
                'type'        => Type::boolean(),
                'description' => '状态',
            ],
            'rank'      => [
                'type'        => Type::string(),
                'description' => '级别',
            ],
            'is_tip'    => [
                'type'        => Type::boolean(),
                'description' => '备注',
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
