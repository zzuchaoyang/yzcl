<?php

namespace App\GraphQL\Mutations\Customer\Auth;

use App\Exceptions\GeneralException;
use App\Fx\Message\SMS\MobileVerifyCodeSMS;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Customer\Customer;
use GraphQL\Type\Definition\Type;

class CustomerResetPasswordMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'customerResetPassword',
        'description' => '门店重置密码',
    ];

    public function type()
    {
        return Type::boolean();
    }

    public function args()
    {
        return [
            'mobile'   => [
                'name'  => 'mobile',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required'],
            ],
            'password' => [
                'name'  => 'password',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required' ],
            ],
            'code'     => [
                'name'  => 'code',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required' ],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        // 验证码 校验
        tap(( new MobileVerifyCodeSMS )->init($args['mobile'])->verifyCode($args['code']), function (array $result) {
            if (!$result['success']) throw new GeneralException($result['message']);
        });

        return tap(Customer::query()->where('mobile',$args['mobile'])->first(), function ($customer) use ($args) {
            if (!$customer) throw new GeneralException('请前往注册。');

            $customer->password = bcrypt(array_get($args, 'password'));

            return $customer->save();
        });
    }

}
