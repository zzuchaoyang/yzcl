<?php

namespace App\GraphQL\Types\Customer\Customer;

use App\GraphQL\Types\BaseType;
use App\Models\System\Area;
use GraphQL\Type\Definition\Type;
use GraphQL;

class CustomerStoreInfoType extends BaseType
{
    protected $attributes = [
        'name'        => 'CustomerStoreInfo',
        'description'   => '客户店铺信息',
    ];

    public function fields()
    {
        return [
            'name'      => [
                'type'        => Type::string(),
                'description' => '店铺名称',
            ],
            'area_id'   => [
                'type'        => Type::int(),
                'description' => '所在区域 ID',
            ],
            'area_name'   => [
                'type'        => Type::string(),
                'description' => '所在区域',
            ],
            'address'   => [
                'type'        => Type::string(),
                'description' => '店铺详细地址',
            ],
            'consignee' => [
                'type'        => Type::string(),
                'description' => '收货人',
            ],
            'mobile'    => [
                'type'        => Type::string(),
                'description' => '联系电话',
            ],
            'position'    => [
                'type'        => GraphQL::type('CoordinateInfo'),
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

    /**
     * area merger name
     *
     * @param $root
     * @param $args
     *
     * @return string
     */
    protected function resolveAreaNameField($root, $args)
    {
        if ($area = Area::find(array_get($root, 'area_id', 0))) {
            return implode('-',explode(',',$area->merger_name));
        }

        return '';
    }
}
