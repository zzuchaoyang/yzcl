<?php

namespace App\GraphQL\Mutations\Supplier\Driver\Auth;

use App\Exceptions\GeneralException;
use App\Fx\Message\SMS\MobileVerifyCodeSMS;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Supplier\Driver;
use App\Models\Supplier\Supplier;
use GraphQL\Type\Definition\Type;

class DriverResetPasswordMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'driverResetPassword',
        'description' => '供应商司机重置密码',
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

        return tap(Driver::query()->where('mobile',$args['mobile'])->first(), function ($driver) use ($args) {
            if (!$driver) throw new GeneralException('请提醒供应商开户。');

            $driver->password = bcrypt(array_get($args, 'password'));

            return $driver->save();
        });
    }

}
