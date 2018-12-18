<?php

namespace App\GraphQL\Mutations\Customer\Auth;

use App\Exceptions\GeneralException;
use App\Fx\Avatar\AvatarService;
use App\Fx\Message\SMS\MobileVerifyCodeSMS;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Customer\Customer;
use Exception;
use GraphQL\Type\Definition\Type;

class CustomerRegisterMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'customerRegister',
        'description' => '门店注册',
    ];

    public function type()
    {
        return Type::string();
    }

    public function args()
    {
        return [
            'mobile'   => [
                'name'  => 'mobile',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required', 'unique:customers' ],
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
            'invitation_code'     => [
                'name'  => 'invitation_code',
                'type'  => Type::string(),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        // 验证码 校验
        tap(( new MobileVerifyCodeSMS )->init($args['mobile'])->verifyCode($args['code']), function (array $result) {
            if (!$result['success']) throw new GeneralException($result['message']);
        });

        $customer = new Customer();

        $customer->fill([
            'name'            => array_get($args, 'mobile'),
            'mobile'          => array_get($args, 'mobile'),
            'avatar'          => ( new AvatarService() )->put(array_get($args, 'mobile', '头像')),
            'invitation_code' => array_get($args, 'invitation_code')
        ]);

        $customer->password = bcrypt(array_get($args, 'password'));

        if (!$customer->save()){
            throw new GeneralException('注册失败。');
        }

        $token = auth('customer')->attempt(array_only($args, [ 'mobile', 'password' ]));

        if (!$token) {
            throw new Exception('账号或密码错误!');
        }

        return $token;

    }

}
