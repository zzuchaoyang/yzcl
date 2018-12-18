<?php

namespace App\GraphQL\Queries\Supplier\SupplierOrg;

use App\GraphQL\Queries\BaseQuery;
use App\Models\Supplier\Supplier;
use GraphQL;
use GraphQL\Type\Definition\Type;

class SupplierOrgListQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'supplierOrgList',
        'description' => '供应商部门列表',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('SupplierOrg'));
    }

    public function args()
    {
        return [
            'category'  => [
                'type'        => Type::string(),
                'description' => '类别',
                'rules'       => [ 'required' ],
            ],
            'is_inline' => [
                'type'        => Type::boolean(),
                'description' => '是否需要内嵌输出',
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
     * @param $context
     * @param $info
     *
     * @return mixed
     */
    public function resolve($root, $args, $context, $info)
    {
        $is_inline = array_get($args, 'is_inline', false);

        $supplierOrgs = \supplier()->supplierOrgs()->byCategory(array_get($args, 'category'))->get();

        if ($is_inline) {
            return $supplierOrgs->toTree();
        }

        return $supplierOrgs;
    }
}
