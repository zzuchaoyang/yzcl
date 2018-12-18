<?php

namespace App\GraphQL\Types\Product\ProductPriceAdjustment;

use App\GraphQL\Types\BaseType;
use App\Models\Product\Brand;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Carbon\Carbon;

class ProductPriceAdjustmentType extends BaseType
{
    protected $attributes = [
        'name'        => 'ProductPriceAdjustment',
        'description' => '商品价格调整',
    ];
    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::int(),
                'description' => 'id',
            ],
            'supplier_id'  => [
                'name'        => 'supplier_id',
                'type'        => Type::int(),
                'description' => '供应商id',
            ],
            'products'  => [
                'name'        => 'products',
                'type'        => Type::listOf(GraphQL::type('Product')),
                'description' => '商品信息',
            ],
            'code'      => [
                'type'        => Type::string(),
                'description' => '调价单编码',
            ],
            'effective_at'         => [
                'type'        => Type::string(),
                'description' => '生效时间',
            ],
            'examine_at'  => [
                'name'        => 'examine_at',
                'type'        => Type::string(),
                'description' => '审核时间',
            ],
            'author_id'  => [
                'name'        => 'author_id',
                'type'        => Type::int(),
                'description' => '审核人id',
            ],
            'number'    => [
                'type'        => Type::int(),
                'description' => '调价商品数量',
            ],
            'status'      => [
                'type'        => Type::string(),
                'description' => '调价单状态',
            ],
            'producer_id'  => [
                'name'        => 'producer_id',
                'type'        => Type::int(),
                'description' => '制单人id',
            ],
            'spec_unit'    => [
                'type'        => Type::string(),
                'description' => '规格单位',
            ],
            'retail_price'    => [
                'type'        => Type::float(),
                'description' => '建议零售价',
            ],
            'make_place'    => [
                'type'        => Type::string(),
                'description' => '产地',
            ],
            'manufacturer'    => [
                'type'        => Type::string(),
                'description' => '生产厂商',
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
            'trade_price'    => [
                'type'        => Type::float(),
                'description' => '批发含税价',
            ],
            'one_price'    => [
                'type'        => Type::float(),
                'description' => '一级供货价',
            ],
            'two_price'    => [
                'type'        => Type::float(),
                'description' => '二级供货价',
            ],
            'three_price'    => [
                'type'        => Type::float(),
                'description' => '三级供货价',
            ],
            'four_price'    => [
                'type'        => Type::float(),
                'description' => '四级供货价',
            ],
            'five_price'    => [
                'type'        => Type::float(),
                'description' => '五级供货价',
            ],
            'single_num'    => [
                'type'        => Type::int(),
                'description' => '单品数量',
            ],
            'order_unit'    => [
                'type'        => Type::string(),
                'description' => '订货单位',
            ],
            'created_at'    => [
                'type'        => Type::string(),
                'description' => '制单日期',
            ],

        ];
    }

    protected function resolveCreatedAtField($root, $args)
    {
        return (string) $root->created_at ? Carbon::parse($root->created_at)->toDateTimeString() : '';
    }

    protected function resolveExamineAtField($root, $args)
    {
        return (string) $root->examine_at ? Carbon::parse($root->examine_at)->toDateTimeString() : '';
    }

    protected function resolveeFfectiveAtField($root, $args)
    {
        return (string) $root->effective_at ? Carbon::parse($root->effective_at)->toDateTimeString() : '';
    }

    protected function resolveProductsField($root, $args)
    {
        if(is_array($root->products) && count($root->products)){
            $products = $root->products;
            foreach ($root->products as $key=>$val){
                $products[$key]['brand'] = Brand::find($val['brand_id'])->toArray();
            }
            return $products;
        }
    }

}
