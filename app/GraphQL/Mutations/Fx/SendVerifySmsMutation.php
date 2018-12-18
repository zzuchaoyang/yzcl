<?php

namespace App\GraphQL\Mutations\Fx;

use App\Fx\Message\SMS\MobileVerifyCodeSMS;
use App\GraphQL\Mutations\BaseMutation;
use GraphQL\Type\Definition\Type;
use SMSSender;

class SendVerifySmsMutation extends BaseMutation
{

    protected $attributes = [
        'name'        => 'sendVerifySms',
        'description' => '发送验证短信',
    ];

    public function type()
    {
        return Type::boolean();
    }

    public function args()
    {
        return [
            'mobile' => [
                'name'  => 'mobile',
                'type'  => Type::nonNull(Type::string()),
                'rules' => [ 'required' ],
            ]
        ];
    }

    /**
     * @param                     $root
     * @param                     $args
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function resolve($root, $args)
    {
        $mobile = $args['mobile'];

        $result = SMSSender::send(( new MobileVerifyCodeSMS )->init($mobile));

        return $result['success'];
    }
}
