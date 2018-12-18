<?php

namespace App\GraphQL\Types\Customer\Input;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class CustomerPaginationInputQuery extends BaseType
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'CustomerPaginationInputQuery',
        'description' => '客户列表查询的字段',
    ];

    public function fields()
    {
        return [
            'id'  => [
                'name'        => 'id',
                'type'        => Type::int(),
                'description' => '用户id',
            ],
            'mobile'  => [
                'name'        => 'mobile',
                'type'        => Type::string(),
                'description' => '用户手机号/客户编号',
            ],
            'status'  => [
                'name'        => 'status',
                'type'        => Type::string(),
                'description' => '合作状态',
            ],
            'supply_grade'  => [
                'name'        => 'supply_grade',
                'type'        => Type::string(),
                'description' => '供货价级别',
            ],
            'salesman_name'  => [
                'name'        => 'salesman_name',
                'type'        => Type::string(),
                'description' => '业务员姓名',
            ],
            'name'  => [
                'name'        => 'name',
                'type'        => Type::string(),
                'description' => '客户姓名',
            ],
            'statics'  => [
                'name'        => 'statics',
                'type'        => Type::boolean(),
                'description' => '客户统计查询',
            ],
            'start_at'  => [
                'name'        => 'start_at',
                'type'        => Type::string(),
                'description' => '开始时间',
            ],
            'end_at'  => [
                'name'        => 'end_at',
                'type'        => Type::string(),
                'description' => '结束时间',
            ],
            'city_id'            => [
                'type'        => Type::string(),
                'description' => '城市id',
            ],
            'is_add'            => [
                'type'        => Type::boolean(),
                'description' => '是否是添加客户',
            ],
            //首页查询
            'from_home'            => [
                'type'        => Type::boolean(),
                'description' => '是否是从首页进来的',
            ],
        ];
    }
}
