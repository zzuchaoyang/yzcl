<?php

namespace App\GraphQL\Types\Product\Input;

use App\GraphQL\Types\BaseType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ProductInput extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'ProductInput',
        'description' => '商品表查询的字段',
    ];

    public function fields()
    {
        return [
            'id'  => [
                'name'        => 'id',
                'type'        => Type::int(),
                'description' => '商品id',
            ],
            'supplier_id'  => [
                'name'        => 'supplier_id',
                'type'        => Type::int(),
                'description' => '供应商id',
            ],
            'brand_id'  => [
                'name'        => 'brand_id',
                'type'        => Type::int(),
                'description' => '品牌id',
            ],
            'brand_name'  => [
                'name'        => 'brand_name',
                'type'        => Type::string(),
                'description' => '品牌名称',
            ],
            'status'  => [
                'name'        => 'status',
                'type'        => Type::string(),
                'description' => '商品状态',
            ],
            'name'  => [
                'name'        => 'name',
                'type'        => Type::string(),
                'description' => '商品名称',
            ],
            'bar_code'  => [
                'name'        => 'bar_code',
                'type'        => Type::string(),
                'description' => '商品条码',
            ],
            'unit'  => [
                'name'        => 'unit',
                'type'        => Type::string(),
                'description' => '商品单位',
            ],
            'spec_number'  => [
                'name'        => 'spec_number',
                'type'        => Type::float(),
                'description' => '规格数量',
            ],
            'spec_unit'  => [
                'name'        => 'spec_unit',
                'type'        => Type::string(),
                'description' => '规格单位',
            ],
            'retail_price'  => [
                'name'        => 'retail_price',
                'type'        => Type::float(),
                'description' => '建议零售价',
            ],
            'quality_period'    => [
                'type'        => Type::string(),
                'description' => '保质期',
            ],
            'make_place'  => [
                'name'        => 'make_place',
                'type'        => Type::string(),
                'description' => '产地',
            ],
            'manufacturer'  => [
                'name'        => 'manufacturer',
                'type'        => Type::string(),
                'description' => '生产厂商',
            ],
            'check_base'  => [
                'name'        => 'check_base',
                'type'        => Type::boolean(),
                'description' => '检查商品基础表',
            ],
            'pictures'  => [
                'name'        => 'pictures',
                'type'        => Type::listOf(Type::string()),
                'description' => '商品图片',
            ],
            'check_pictures'  => [
                'name'        => 'check_pictures',
                'type'        => Type::listOf(Type::string()),
                'description' => '商品检测图片',
            ],
            'trade_price'  => [
                'name'        => 'trade_price',
                'type'        => Type::float(),
                'description' => '批发含税价',
            ],
            'one_price'  => [
                'name'        => 'one_price',
                'type'        => Type::float(),
                'description' => '一级供货价',
            ],
            'two_price'  => [
                'name'        => 'two_price',
                'type'        => Type::float(),
                'description' => '二级供货价',
            ],
            'three_price'  => [
                'name'        => 'three_price',
                'type'        => Type::float(),
                'description' => '三级供货价',
            ],
            'four_price'  => [
                'name'        => 'four_price',
                'type'        => Type::float(),
                'description' => '四级供货价',
            ],
            'five_price'  => [
                'name'        => 'five_price',
                'type'        => Type::float(),
                'description' => '五级供货价',
            ],
            'single_num'  => [
                'name'        => 'single_num',
                'type'        => Type::int(),
                'description' => '单品数量',
            ],
            'order_unit'  => [
                'name'        => 'order_unit',
                'type'        => Type::string(),
                'description' => '订货单位',
            ],
            //商品统计查询
            'start_at'            => [
                'type'        => Type::string(),
                'description' => '开始时间',
            ],
            'end_at'            => [
                'type'        => Type::string(),
                'description' => '结束时间',
            ],
            'statics'            => [
                'type'        => Type::boolean(),
                'description' => '商品统计查询',
            ],
            'city_id'            => [
                'type'        => Type::string(),
                'description' => '城市id',
            ],
        ];
    }
}
