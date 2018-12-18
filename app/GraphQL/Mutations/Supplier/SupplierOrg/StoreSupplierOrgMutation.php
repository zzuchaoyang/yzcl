<?php

namespace App\GraphQL\Mutations\Supplier\SupplierOrg;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Supplier\Supplier;
use Exception;
use GraphQL;
use GraphQL\Type\Definition\Type;
use App\Models\Supplier\SupplierOrg;

class StoreSupplierOrgMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'storeSupplierOrg',
        'description' => '创建/更新 - 供应商部门',
    ];

    public function type()
    {
        return GraphQL::type('SupplierOrg');
    }

    public function args()
    {
        return [
            'data' => [
                'type'        => Type::nonNull(GraphQL::type('StoreSupplierOrgMutationInput')),
                'description' => '创建/更新 供应商部门 输入的信息',
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
        return \supplier() instanceof Supplier;
    }

    /**
     * @param $root
     * @param $args
     *
     * @return mixed
     * @throws \Throwable
     */
    public function resolve($root, $args)
    {
        try {
            // update
            if (array_has($args, 'data.id') && !empty(array_get($args, 'data.id'))) {

                $supplierOrg = SupplierOrg::findOrFail(array_get($args, 'data.id'));

                if (!$supplierOrg = $supplierOrg->updateOrg($args)) {
                    throw new GeneralException("更新 {$supplierOrg->name} 失败");
                }

                return $supplierOrg;
            }
            // create
            return SupplierOrg::store($args);
        } catch (Exception $exception) {
            throw new GeneralException($exception->getMessage());
        }
    }
}
