<?php

namespace App\GraphQL\Types\Supplier\Supplier\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class StoreSupplierMutationInfoCardInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'StoreSupplierMutationInfoCardInput',
        'description' => '供应商证件信息',
    ];

    public function fields()
    {
        return [
            'name'       => [
                'type'        => Type::string(),
                'description' => '证件名称',
            ],
            'link'       => [
                'type'        => Type::string(),
                'description' => '证件地址',
            ],
            'created_at' => [
                'type'        => Type::string(),
                'description' => '证件上传时间',
            ],
        ];
    }
}
