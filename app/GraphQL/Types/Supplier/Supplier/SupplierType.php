<?php

namespace App\GraphQL\Types\Supplier\Supplier;

use App\GraphQL\Types\BaseType;
use App\Models\Supplier\Supplier;
use GraphQL;
use GraphQL\Type\Definition\Type;

class SupplierType extends BaseType
{
    protected $attributes = [
        'name'        => 'Supplier',
        'description' => '供应商用户',
    ];

    /*
    * Uncomment following line to make the type input object.
    * http://graphql.org/learn/schema/#input-types
    */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'        => [
                'type'        => Type::id(),
                'description' => '用户 ID',
            ],
            'name'      => [
                'type'        => Type::string(),
                'description' => '姓名',
            ],
            'mobile'    => [
                'type'        => Type::string(),
                'description' => '状态',
            ],
            'avatar'    => [
                'type'        => Type::string(),
                'description' => '头像',
            ],
            'info' => [
                'type'        => GraphQL::type('SupplierInfo'),
                'description' => '基本信息',
            ],
            'loan_info' => [
                'type'        => GraphQL::type('SupplierLoanInfo'),
                'description' => '贷款信息概要',
            ],
            'price_increase_ratio' => [
                'type'        => GraphQL::type('ProductPriceAdjustment'),
                'description' => '阶梯定价上浮比例配置',
            ],
            //商品
            'products'         => [
                'type'        => Type::listOf(GraphQL::type('Product')),
                'description' => '商品',
            ],
            'brands'         => [
                'type'        => Type::listOf(GraphQL::type('Brand')),
                'description' => '商品',
            ],
            'customerSupplier' => [
                'type'        => Type::listOf(GraphQL::type('CustomerSupplier')),
                'description' => '合作关系',
            ],
        ];
    }

    // If you want to resolve the field yourself, you can declare a method
    // with the following format resolve[FIELD_NAME]Field()
    protected function resolveLoanInfoField($root, $args)
    {
        $loanInfo = $root->loan_info;
        if (empty($loanInfo) && user() instanceof Supplier) {
            \Artisan::call('supplier:update-loan-info', [
                'supplier_id' => user()->id,
            ]);
        }

        return Supplier::find($root->id)->loan_info;
    }
}
