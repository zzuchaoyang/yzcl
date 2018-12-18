<?php

namespace App\GraphQL\Types\Supplier\Supplier;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class SupplierCardInfoType extends BaseType
{
    protected $attributes = [
        'name'        => 'SupplierCardInfo',
        'description' => '证件信息',
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
