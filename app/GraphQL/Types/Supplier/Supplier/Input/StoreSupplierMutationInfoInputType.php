<?php

namespace App\GraphQL\Types\Supplier\Supplier\Input;

use App\GraphQL\Types\BaseType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class StoreSupplierMutationInfoInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'StoreSupplierMutationInfoInput',
        'description' => '供应商基本信息',
    ];

    public function fields()
    {
        return [
            'id'      => [
                'type'        => Type::id(),
                'description' => 'ID',
            ],
            'company' => [
                'type'        => GraphQL::type('StoreSupplierMutationInfoCompanyInput'),
                'description' => '公司信息',
            ],
            'finance' => [
                'type'        => GraphQL::type('StoreSupplierMutationInfoFinanceInput'),
                'description' => '财务信息',
            ],
            'cards'    => [
                'type'        => Type::listOf(GraphQL::type('StoreSupplierMutationInfoCardInput')),
                'description' => '证件信息',
            ],
        ];
    }
}
