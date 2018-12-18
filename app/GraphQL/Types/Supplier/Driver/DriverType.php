<?php

namespace App\GraphQL\Types\Supplier\Driver;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class DriverType extends BaseType
{

    protected $attributes = [
        'name'        => 'Driver',
        'description' => '司机',
    ];

    public function fields()
    {
        return [
            'id'              => [
                'type'        => Type::id(),
                'description' => '用户 ID',
            ],
            'user_id'              => [
                'type'        => Type::id(),
                'description' => '用户 ID',
            ],
            'supplier_org_id' => [
                'type'        => Type::id(),
                'description' => '组织架构 ID',
            ],
            'name'            => [
                'type'        => Type::string(),
                'description' => '姓名',
            ],
            'mobile'          => [
                'type'        => Type::string(),
                'description' => '状态',
            ],
            'status'          => [
                'type'        => Type::boolean(),
                'description' => '状态',
            ],
            'gender'          => [
                'type'        => Type::string(),
                'description' => '性别',
            ],
            'avatar'          => [
                'type'        => Type::string(),
                'description' => '头像',
            ],
            'birthed_at'      => [
                'type'        => Type::string(),
                'description' => '出生日期',
            ],
            'id_card'         => [
                'type'        => Type::string(),
                'description' => '证件号',
            ],
            'license'         => [
                'type'        => Type::string(),
                'description' => '车牌号',
            ],
            'hiredate_on'     => [
                'type'        => Type::string(),
                'description' => '入职时间',
            ],
            'role'            => [
                'type'        => Type::string(),
                'description' => '角色',
            ],
        ];
    }

    protected function resolveUserIdField($root, $args)
    {
        return $root->id;
    }

    protected function resolveGenderField($root, $args)
    {
        return (string) $root->gender ? '男' : '女';
    }

    protected function resolveHiredateOnField($root, $args)
    {
        return (string) $root->hiredate_on ? carbon($root->hiredate_on)->toDateTimeString() : '';
    }

    protected function resolveBirtheAdtField($root, $args)
    {
        return (string) $root->birthed_at ? carbon($root->birthed_at)->toDateTimeString() : '';
    }

    protected function resolveRoleField($root, $args)
    {
        return '司机';
    }
}
