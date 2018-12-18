<?php

namespace App\GraphQL\Types\Supplier\Driver\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class DriverPaginatorInputType extends BaseType
{
    protected $attributes = [
        'name'        => 'DriverPaginatorInput',
        'description' => '供应商司机列表输入字段类型',
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'name'        => [
                'type'        => Type::string(),
                'description' => '名称',
            ],
            'mobile'      => [
                'type'        => Type::string(),
                'description' => '手机号',
            ],
            'supplier_org_id'        => [
                'type'        => Type::id(),
                'description' => '部门名称',
            ],
            'status'      => [
                'type'        => Type::string(),
                'description' => '状态',
            ],
        ];
    }
}
