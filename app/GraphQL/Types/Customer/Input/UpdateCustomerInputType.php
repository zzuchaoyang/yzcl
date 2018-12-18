<?php

namespace App\GraphQL\Types\Customer\Input;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class UpdateCustomerInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'UpdateCustomerInput',
        'description' => '更新客户信息输入字段类型',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::int(),
                'description' => 'id',
            ],
            'name'            => [
                'type'        => Type::string(),
                'description' => '客户姓名',
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
            'store_info'   => [
                'type'        => GraphQL::type('UpdateCustomerStoreInfoInput'),
                'description' => '证件信息',
            ],
        ];
    }
}
