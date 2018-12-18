<?php

namespace App\GraphQL\Mutations\Supplier\Driver;

use App\Exceptions\GeneralException;
use App\Fx\Message\SMS\MobileVerifyCodeSMS;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Supplier\Driver;
use GraphQL;
use GraphQL\Type\Definition\Type;

class UpdateDriverMobileMutation extends BaseMutation
{

    protected $attributes = [
        'name'        => 'updateDriverMobile',
        'description' => '司机修改手机号',
    ];

    public function type()
    {
        return GraphQL::type('Driver');
    }

    public function args()
    {
        return [
            'mobile'     => [
                'name'  => 'mobile',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required' ],
            ],
            'new_mobile' => [
                'name'  => 'new_mobile',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required' ],
            ],
            'code'       => [
                'name'  => 'code',
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
        return user() instanceof Driver;
    }

    public function resolve($root, $args)
    {
        // 验证码 校验
        tap(( new MobileVerifyCodeSMS )->init($args['mobile'])->verifyCode($args['code']), function (array $result) {
            if (!$result['success']) throw new GeneralException($result['message']);
        });

        if (!$driver = Driver::query()->where('mobile', $args['mobile'])->first()) {
            throw new GeneralException('数据不存在,修改手机号失败。');
        }

        if (Driver::query()->where('mobile', $args['new_mobile'])->exists()) {
            throw new GeneralException('新手机号已被注册,修改手机号失败。');
        }

        if (!$bool = $driver->update([ 'mobile' => $args['new_mobile'] ])) {
            throw new GeneralException('修改手机号失败。');
        }

        return Driver::where('mobile', $args['new_mobile'])->first();
    }
}
