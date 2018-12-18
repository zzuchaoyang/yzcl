<?php

namespace App\GraphQL\Types\Supplier\Supplier;

use App\GraphQL\Types\BaseType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class SupplierInfoType extends BaseType
{
    protected $attributes = [
        'name'        => 'SupplierInfo',
        'description' => '基本信息',
    ];

    public function fields()
    {
        return [
            'company' => [
                'type'        => GraphQL::type('SupplierCompanyInfo'),
                'description' => '公司信息',
            ],
            'finance' => [
                'type'        => GraphQL::type('SupplierFinanceInfo'),
                'description' => '财务信息',
            ],
            'cards'    => [
                'type'        => Type::listOf(GraphQL::type('SupplierCardInfo')),
                'description' => '证件信息',
            ],
        ];
    }
}
