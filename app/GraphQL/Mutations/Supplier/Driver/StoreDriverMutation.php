<?php

namespace App\GraphQL\Mutations\Supplier\Driver;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Supplier\Driver;
use App\Models\Supplier\Supplier;
use Exception;
use GraphQL;
use GraphQL\Type\Definition\Type;

class StoreDriverMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'storeDriver',
        'description' => '新增 / 更新 供应商司机信息',
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
                'description' => '新增 / 更新  供应商司机 输入的信息',
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
        try {
            // update
            if (array_has($args, 'data.id') && !empty(array_get($args, 'data.id'))) {

                $supplierOrg = Driver::findOrFail(array_get($args, 'data.id'));

                if (!$supplierOrg = $supplierOrg->updateDriver($args)) {
                    throw new GeneralException("更新 {$supplierOrg->name} 失败");
                }

                return $supplierOrg;
            }
            // create
            return Driver::store($args);
        } catch (Exception $exception) {
            throw new GeneralException($exception->getMessage());
        }
    }

}
