<?php

namespace App\GraphQL\Types\Product\Product;

use App\Exceptions\GeneralContinueException;
use App\GraphQL\Types\BaseType;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSupplier;
use App\Models\Product\Brand;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ProductType extends BaseType
{
    protected $attributes = [
        'name'        => 'Product',
        'description' => '商品',
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
            'brand_id'         => [
                'name'        => 'brand_id',
                'type'        => Type::int(),
                'description' => '品牌id',
            ],
            'brand'      => [
                'type'        => GraphQL::type('Brand'),
                'description' => '品牌',
            ],
            'productPriceAdjustments'      => [
                'type'        => Type::listOf(GraphQL::type('ProductPriceAdjustment')),
                'description' => '商品调价单',
            ],
            'status'         => [
                'type'        => Type::string(),
                'description' => '商品状态',
            ],
            'name'         => [
                'type'        => Type::string(),
                'description' => '商品名称',
            ],
            'bar_code'    => [
                'type'        => Type::string(),
                'description' => '商品条码',
            ],
            'unit'      => [
                'type'        => Type::string(),
                'description' => '商品单位',
            ],
            'spec_number'        => [
                'type'        => Type::float(),
                'description' => '规格数量',
            ],
            'spec_unit'    => [
                'type'        => Type::string(),
                'description' => '规格单位',
            ],
            'retail_price'    => [
                'type'        => Type::float(),
                'description' => '建议零售价',
            ],
            'quality_period'    => [
                'type'        => Type::string(),
                'description' => '保质期',
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
            // 客户销售统计附加字段
            'customer_count' => [
                'type'        => Type::int(),
                'description' => '采购商数量',
            ],
            'order_number' => [
                'type'        => Type::int(),
                'description' => '订货总数量',
            ],
            'order_price' => [
                'type'        => Type::float(),
                'description' => '订货总金额',
            ],
            'real_order_number' => [
                'type'        => Type::int(),
                'description' => '实际发货数量',
            ],
            'real_order_price' => [
                'type'        => Type::float(),
                'description' => '实际销售总额',
            ],
            'product_specifications'    => [
                'type'        => Type::string(),
                'description' => '订货规格',
            ],
            'product_price'    => [
                'type'        => Type::float(),
                'description' => '商品价格',
            ],
            'check_base'  => [
                'type'        => Type::boolean(),
                'description' => '检查商品基础表',
            ]
        ];
    }

    protected function resolveProductPriceField($root, $args)
    {
        if (user() instanceof Customer) {
            $customerSupplier = CustomerSupplier::where('customer_id', user()->id)->where('supplier_id', $root->supplier_id)->where('status', CustomerSupplier::STATUS_NORMAL)->first();
            if ($customerSupplier) {
                $key = $customerSupplier->supply_grade.'_price';
                if(isset($root->$key) && $root->$key){
                    return $root->$key;
                }else{
                    return $root->one_price;
                }
            }
        } else {
            throw new GeneralContinueException('商铺与供应商之间没有合作关系，请验证一下');
        }
    }

    protected function resolveProductSpecificationsField($root, $args)
    {
        return $root->spec_number.$root->spec_unit.'*'.$root->single_num.$root->unit.'/'.$root->order_unit;
    }
}
