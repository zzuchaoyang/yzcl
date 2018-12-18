<?php

namespace App\GraphQL\Types\Supplier\Supplier\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class SupplierInputQuery extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'SupplierInputQuery',
        'description' => '供应商列表查询的字段',
    ];

    public function fields()
    {
        return [
            'id'  => [
                'name'        => 'id',
                'type'        => Type::string(),
                'description' => '供应商id',
            ],
            'mobile'  => [
                'name'        => 'mobile',
                'type'        => Type::string(),
                'description' => '手机号',
            ],
            'supplier_product_name' => [
                'name'        => 'supplier_product_name',
                'type'        => Type::string(),
                'description' => '供应商或商品名称',
            ],
            //站内信跳转时传参
            'inner_message_id' => [
                'name'        => 'inner_message_id',
                'type'        => Type::int(),
                'rules'       => ['exists:inner_messages,id'],
                'description' => '站内信id',
            ],
            //查询末级品牌
            'status' => [
                'name'        => 'status',
                'type'        => Type::string(),
                'description' => '品牌状态',
            ],
            'is_last_stage' => [
                'name'        => 'is_last_stage',
                'type'        => Type::BOOLEAN(),
                'description' => '是否是末级品牌',
            ],
        ];
    }
}
