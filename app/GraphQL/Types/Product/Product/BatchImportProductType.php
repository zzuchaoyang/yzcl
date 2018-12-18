<?php

namespace App\GraphQL\Types\Product\Product;


use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class BatchImportProductType extends BaseType
{
    protected $attributes = [
        'name'        => 'BatchImportProduct',
        'description' => '商品基础信息',
    ];

    public function fields()
    {
        return [
            'code'            => [
                'name'        => 'code',
                'type'        => Type::string(),
                'description' => '商品条码错误行',
            ],
            'format'  => [
                'name'        => 'format',
                'type'        => Type::string(),
                'description' => '商品格式错误行',
            ],
        ];
    }
}