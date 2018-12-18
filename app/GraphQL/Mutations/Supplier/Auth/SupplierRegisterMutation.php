<?php

namespace App\GraphQL\Mutations\Supplier\Auth;

use App\Exceptions\GeneralException;
use App\Fx\Message\SMS\MobileVerifyCodeSMS;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Supplier\Supplier;
use GraphQL\Type\Definition\Type;

class SupplierRegisterMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'supplierRegister',
        'description' => '供应商注册',
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
                'rules' => [ 'required', 'unique:suppliers' ],
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
            // 邀请码是记录惠金所自己 销售的业绩的，只需要给供应商注册表里加一个邀请码字段，记录下来后期给他们导出表格，由他们人工来核对销售业绩
            'invitation_code'     => [ // 邀请码?
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

        $supplier = new Supplier();

        $supplier->fill([
            'name'            => array_get($args, 'mobile'),
            'mobile'          => array_get($args, 'mobile'),
            'invitation_code' => array_get($args, 'invitation_code')
        ]);

        $supplier->password = bcrypt(array_get($args, 'password'));

        return $supplier->save();
    }

}
