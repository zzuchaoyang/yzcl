<?php

namespace App\GraphQL\Types\Supplier\Supplier\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class StoreSupplierMutationInfoCompanyInputType extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'StoreSupplierMutationInfoCompanyInput',
        'description' => '供应商公司信息',
    ];

    public function fields()
    {
        return [
            'gsmc'   => [
                'type'        => Type::string(),
                'description' => '公司名称',
            ],
            'gsxz'   => [
                'type'        => Type::string(),
                'description' => '工商性质',
            ],
            'frdb'   => [
                'type'        => Type::string(),
                'description' => '法人代表',
            ],
            'frlxdh' => [
                'type'        => Type::string(),
                'description' => '法人联系电话',
            ],
            'gscz'   => [
                'type'        => Type::string(),
                'description' => '公司传真',
            ],
            'gsyx'   => [
                'type'        => Type::string(),
                'description' => '公司邮箱',
            ],
            'ywlxr'  => [
                'type'        => Type::string(),
                'description' => '业务联系人',
            ],
            'ywlxdh' => [
                'type'        => Type::string(),
                'description' => '业务联系电话',
            ],
            'area_id'   => [
                'type'        => Type::id(),
                'description' => '地区 ID',
            ],
            'gsdz'   => [
                'type'        => Type::string(),
                'description' => '公司地址',
            ],
            'xxzs'   => [
                'type'        => Type::string(),
                'description' => '形象展示',
            ],
            'gsjs'   => [
                'type'        => Type::string(),
                'description' => '公司介绍',
            ],
        ];
    }
}
