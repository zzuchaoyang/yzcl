<?php

namespace App\GraphQL\Mutations\Supplier\Driver\Auth;

use App\GraphQL\Mutations\BaseMutation;
use GraphQL\Type\Definition\Type;
use JWTAuth;

class DriverLogoutMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'driverLogout',
        'description' => '供应商司机登出',
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
            JWTAuth::invalidate($token);

            return true;
        }

        return false;
    }
}
