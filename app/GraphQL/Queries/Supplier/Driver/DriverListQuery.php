<?php

namespace App\GraphQL\Queries\Supplier\Driver;

use App\GraphQL\Queries\BaseQuery;
use App\Models\Supplier\Driver;
use App\Models\Supplier\Supplier;
use GraphQL;
use GraphQL\Type\Definition\Type;
use MatrixLab\LaravelAdvancedSearch\ModelScope;
use MatrixLab\LaravelAdvancedSearch\When;

class DriverListQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'driverList',
        'description' => '供应商司机列表',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Driver'));
    }

    public function args()
    {
        return [
            'more'      => [
                'name'        => 'more',
                'type'        => GraphQL::type('DriverPaginatorInput'),
                'description' => '更多搜索参数',
            ],
        ];
    }

    protected function wheres()
    {
        return [
            // 状态
            function ($q) {
                $statusArray = [ '已开启' => true, '已暂停' => false ];
                $status    = $this->getInputArgs('status');

                if (array_has($statusArray, $status)) {
                    $q->where('status', array_get($statusArray, $status, true));
                }
            },
            // 组织架构
            new ModelScope('bySupplierOrg', $this->getInputArgs('supplier_org_id', '')),
            // 名称
            When::make($this->getInputArgs('name'))->success(function ($q) {
                $q->where('name', 'like', "%{$this->getInputArgs('name')}%");
            }),
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
        return user() instanceof Supplier;
    }

    public function resolve($root, $args, $context, $info)
    {
        return Driver::getList($this->getConditions($args));
    }
}
