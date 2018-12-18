<?php

namespace App\GraphQL\Mutations\Customer\Auth;

use App\GraphQL\Mutations\BaseMutation;
use GraphQL\Type\Definition\Type;
use JWTAuth;

class CustomerLogoutMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'customerLogout',
        'description' => '门店登出',
    ];

    public function type()
    {
        return Type::boolean();
    }

    public function args()
    {
        return [
            'token' => [
                'name'  => 'token',
                'type'  => Type::nonNull(Type::string()),
                'rules' => ['required'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        if ($token = JWTAuth::getToken()) {
            JWTAuth::invalidate(true);

            return true;
        }

        return false;
    }
}
