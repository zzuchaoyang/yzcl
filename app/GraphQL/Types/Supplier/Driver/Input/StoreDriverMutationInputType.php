<?php

namespace App\GraphQL\Types\Supplier\Driver\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class StoreDriverMutationInputType extends BaseType
{
    protected $attributes = [
        'name'        => 'StoreDriverMutationInput',
        'description' => '供应商创建司机输入字段类型',
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'id'              => [
                'type'        => Type::id(),
                'description' => '名称',
            ],
            'name'            => [
                'type'        => Type::string(),
                'description' => '名称',
            ],
            'mobile'          => [
                'type'        => Type::string(),
                'description' => '手机号',
            ],
            'password'        => [
                'type'        => Type::string(),
                'description' => '初始密码',
            ],
            'supplier_org_id' => [
                'type'        => Type::id(),
                'description' => '部门名称',
            ],
            'status'          => [
                'type'        => Type::boolean(),
                'description' => '状态',
            ],
            'gender'          => [
                'type'        => Type::boolean(),
                'description' => '性别',
            ],
            'avatar'          => [
                'type'        => Type::string(),
                'description' => '头像',
            ],
            'birthed_at'      => [
                'type'        => Type::string(),
                'description' => '出生日期',
            ],
            'id_card'         => [
                'type'        => Type::string(),
                'description' => '证件号',
            ],
            'license'         => [
                'type'        => Type::string(),
                'description' => '车牌号',
            ],
            'hiredate_on'     => [
                'type'        => Type::string(),
                'description' => '入职时间',
            ],
        ];
    }
}
