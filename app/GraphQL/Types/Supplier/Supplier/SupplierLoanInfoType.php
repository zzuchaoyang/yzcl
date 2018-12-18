<?php

namespace App\GraphQL\Types\Supplier\Supplier;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class SupplierLoanInfoType extends BaseType
{
    protected $attributes = [
        'name'        => 'SupplierLoanInfo',
        'description' => '贷款信息概要',
    ];

    public function fields()
    {
        return [
            'min'              => [
                'type'        => Type::int(),
                'description' => '最小贷款额度',
            ],
            'max'            => [
                'type'        => Type::int(),
                'description' => '最大贷款额度',
            ],
        ];
    }
}
