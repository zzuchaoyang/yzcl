<?php

namespace App\GraphQL\Types\Customer\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;
use GraphQL;

class UpdateCustomerStoreInfoInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'UpdateCustomerStoreInfoInput',
        'description' => '客户店铺信息',
    ];

    public function fields()
    {
        return [
            'name'          => [
                'type'        => Type::string(),
                'description' => '店铺名称',
            ],
            'area_id'       => [
                'type'        => Type::int(),
                'description' => '所在区域 ID',
            ],
            'address'       => [
                'type'        => Type::string(),
                'description' => '店铺详细地址',
            ],
            'consignee'     => [
                'type'        => Type::string(),
                'description' => '收货人',
            ],
            'mobile'        => [
                'type'        => Type::string(),
                'description' => '联系电话',
            ],
            'position'      => [
                'type'        => GraphQL::type('CoordinateInfoInput'),
                'description' => '位置的坐标',
            ],
            'year_turnover' => [
                'type'        => Type::string(),
                'description' => '年营业额',
            ],
            'opened_at'     => [
                'type'        => Type::string(),
                'description' => '开业时间',
            ],
            'usable_area'  => [
                'type'        => Type::string(),
                'description' => '营业面积',
            ],
        ];
    }
}
