<?php

namespace App\GraphQL\Types\Customer\CooperationApplication;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

class CooperationApplicationType extends BaseType
{
    protected $attributes = [
        'name'        => 'CooperationApplication',
        'description' => '供应商与用户的合作申请',
    ];

    /*
    * Uncomment following line to make the type input object.
    * http://graphql.org/learn/schema/#input-types
    */

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::id(),
                'description' => '合作申请id',
            ],
            'sender_id'            => [
                'type'        => Type::int(),
                'description' => '申请合作人id',
            ],
            'sender_type'          => [
                'type'        => Type::string(),
                'description' => '申请合作人类型',
            ],
            'receiver_id'          => [
                'type'        => Type::int(),
                'description' => '接受合作人id',
            ],
            'receiver_type'          => [
                'type'        => Type::string(),
                'description' => '接受合作人类型',
            ],
            'rejected_at'          => [
                'type'        => Type::string(),
                'description' => '拒绝合作日期',
            ],
            'accepted_at'          => [
                'type'        => Type::string(),
                'description' => '接受合作日期',
            ],
        ];
    }

    protected function resolveAcceptedAtField($root, $args)
    {
        return (string) $root->accepted_at ? carbon($root->accepted_at)->toDateTimeString() : '';
    }

    protected function resolveRejectedAtField($root, $args)
    {
        return carbon($root->rejected_at)->toDateTimeString();
    }

}
