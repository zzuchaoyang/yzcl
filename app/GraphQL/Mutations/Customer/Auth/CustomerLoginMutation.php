<?php

namespace App\GraphQL\Mutations\Customer\Auth;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Customer\Customer;
use Exception;
use GraphQL\Type\Definition\Type;

class CustomerLoginMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'customerLogin',
        'description' => '门店登录',
    ];

    public function type()
    {
        return Type::string();
    }

    public function args()
    {
        return [
            'mobile'      => [
                'name'  => 'mobile',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required' ],
            ],
            'password'    => [
                'name'  => 'password',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required', 'string' ],
            ],
            'remember_me' => [
                'name'  => 'remember_me',
                'type'  => Type::boolean(),
                'rules' => [ 'boolean' ],
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     *
     * @return bool
     * @throws Exception
     */
    public function resolve($root, $args)
    {
        if (!Customer::query()->where('mobile', array_get($args,'mobile'))->exists()) {
            throw new Exception('请前往注册!');
        }

        $token = auth('customer')->attempt(array_only($args, [ 'mobile', 'password' ]));

        if (!$token) {
            throw new Exception('账号或密码错误!');
        }

        return $token;
    }
}
