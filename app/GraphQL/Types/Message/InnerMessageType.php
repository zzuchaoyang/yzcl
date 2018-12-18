<?php

namespace App\GraphQL\Types\Message;

use GraphQL;
use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class InnerMessageType extends BaseType
{
    protected $attributes = [
        'name'        => 'InnerMessage',
        'description' => '站内信',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::id(),
                'description' => 'id',
            ],
            'type'            => [
                'type'        => Type::string(),
                'description' => '类型',
            ],
            'sender_id'            => [
                'type'        => Type::int(),
                'description' => '发送人id',
            ],
            'sender_type'  => [
                'type'        => Type::string(),
                'description' => '发送人类型',
            ],
            'receiver_id'            => [
                'type'        => Type::int(),
                'description' => '接收人id',
            ],
            'receiver_type'  => [
                'type'        => Type::string(),
                'description' => '接收人类型',
            ],
            'relation_type'  => [
                'type'        => Type::string(),
                'description' => '关联类型',
            ],
            'relation_id'  => [
                'type'        => Type::int(),
                'description' => '关联id',
            ],
            'content'  => [
                'type'        => Type::string(),
                'description' => '信息',
            ],
            'is_read'  => [
                'type'        => Type::BOOLEAN(),
                'description' => '是否已读',
            ],
            'is_top'  => [
                'type'        => Type::BOOLEAN(),
                'description' => '是否已读',
            ],
            'created_at'  => [
                'type'        => Type::string(),
                'description' => '创建时间',
            ],
        ];
    }

    protected function resolveCreatedAtField($root, $args)
    {
        return $root->created_at ? carbon($root->created_at)->toDateTimeString() : '';
    }

}
