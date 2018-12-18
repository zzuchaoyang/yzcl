<?php

namespace App\GraphQL\Mutations\Supplier\Driver;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Supplier\Driver;
use App\Models\Supplier\Supplier;
use GraphQL;
use GraphQL\Type\Definition\Type;

class UpdateDriverMutation extends BaseMutation
{

    protected $attributes = [
        'name'        => 'updateDriver',
        'description' => '更新供应商司机基本信息',
    ];

    public function type()
    {
        return GraphQL::type('Driver');
    }

    public function args()
    {
        return [
            'data' => [
                'type'        => Type::nonNull(GraphQL::type('StoreDriverMutationInput')),
                'description' => '更新 供应商司机 输入的信息',
                'rules'       => [ 'required' ],
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
        return user() instanceof Driver || user() instanceof Supplier;
    }

    public function resolve($root, $args)
    {
        $id = array_get($args, 'data.id');

        if (!$driver = Driver::find($id)){
            throw new GeneralException('数据不存在，更新失败。');
        }

        if (user() instanceof Driver && user()->id != $id) {
            throw new GeneralException('信息异常，更新失败。');
        }

        $driver->update(array_get($args, 'data'));

        return Driver::find($id);
    }
}
