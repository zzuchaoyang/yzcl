<?php

namespace App\GraphQL\Mutations\Supplier\Supplier;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Supplier\Supplier;
use App\Models\System\Area;
use GraphQL;
use GraphQL\Type\Definition\Type;

class StoreSupplierMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'storeSupplier',
        'description' => '创建、修改供应商基本信息',
    ];

    public function type()
    {
        return GraphQL::type('Supplier');
    }

    public function args()
    {
        return [
            'data' => [
                'name'        => 'data',
                'type'        => Type::nonNull(GraphQL::type('StoreSupplierMutationInput')),
                'description' => '基本信息',
                'rules'       => ['required'],
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
        return \user() instanceof Supplier;
    }

    public function resolve($root, $args)
    {
        if ($area = Area::find(array_get($args, 'data.info.company.area_id',0))){
            $this->provinceCityArea($args, $area);
        }

        $user = user();

        $user->update($args['data']);

        return $user;
    }

    /**
     * 用于后期做地区统计
     *
     * @param      $args
     * @param Area $area
     *
     * @return mixed
     */
    private function provinceCityArea(&$args, Area $area)
    {
        // 省
        if ($area->level == 0) {
            $args['data']['info']['company']['province_id'] = $area->id;
        }

        // 市
        if ($area->level == 1) {
            $args['data']['info']['company']['city_id'] = $area->id;
        }

        if ($area = $area->parent) {
            $this->provinceCityArea($args, $area);
        }
    }
}
