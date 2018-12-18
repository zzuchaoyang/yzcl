<?php

namespace App\GraphQL\Mutations\Supplier\Driver;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Supplier\Driver;
use GraphQL\Type\Definition\Type;
use Hash;

class UpdateDriverPasswordMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'updateDriverPassword',
        'description' => '司机修改密码',
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
        return user() instanceof Driver;
    }

    public function resolve($root, $args)
    {
        if (!$driver = Driver::query()->where('mobile', $args['mobile'])->first()) {
            throw new GeneralException('数据不存在,修改密码失败。');
        }

        if (!Hash::check($args['password'], $driver->password)) {
            throw new GeneralException('原密码错误');
        }

        $driver->password = bcrypt(array_get($args, 'new_password'));

        if (!$bool = $driver->save()) {
            throw new GeneralException('修改密码失败');
        }

        return $bool;
    }
}
