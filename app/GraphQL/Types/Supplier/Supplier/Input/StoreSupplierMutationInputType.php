<?php

namespace App\GraphQL\Types\Supplier\Supplier\Input;

use App\GraphQL\Types\BaseType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class StoreSupplierMutationInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'StoreSupplierMutationInput',
        'description' => '供应商基本信息',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type'        => Type::id(),
                'description' => 'ID',
            ],

            'info' => [
                'type'        => GraphQL::type('StoreSupplierMutationInfoInput'),
                'description' => '公司信息',
            ],
        ];
    }
}
