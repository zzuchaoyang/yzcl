<?php

namespace App\GraphQL\Mutations\Customer;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Customer\Customer;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Hash;

class UpdateCustomerPasswordMutation extends BaseMutation
{

    protected $attributes = [
        'name'        => 'updateCustomerPassword',
        'description' => '客户修改密码',
    ];

    public function type()
    {
        return Type::boolean();
    }

    public function args()
    {
        return [
            'mobile'       => [
                'name'  => 'mobile',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required' ],
            ],
            'password'     => [
                'name'  => 'password',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required' ],
            ],
            'new_password' => [
                'name'  => 'new_password',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required' ],
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param $currentUser
     *
     * @return bool
     */
    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Customer;
    }

    public function resolve($root, $args)
    {
        if (!$customer = Customer::query()->where('mobile', $args['mobile'])->first()) {
            throw new GeneralException('数据不存在,修改密码失败。');
        }

        if (!Hash::check($args['password'], $customer->password)) {
            throw new GeneralException('原密码错误');
        }

        $customer->password = bcrypt(array_get($args, 'new_password'));

        if (!$bool = $customer->save()) {
            throw new GeneralException('修改密码失败');
        }

        return $bool;
    }
}
