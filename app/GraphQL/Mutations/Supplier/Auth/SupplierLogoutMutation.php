<?php

namespace App\GraphQL\Mutations\Supplier\Auth;

use App\GraphQL\Mutations\BaseMutation;
use GraphQL\Type\Definition\Type;
use JWTAuth;

class SupplierLogoutMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'supplierLogout',
        'description' => '供应商登出',
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
