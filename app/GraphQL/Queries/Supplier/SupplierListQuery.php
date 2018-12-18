<?php

namespace App\GraphQL\Queries\Supplier;

use App\GraphQL\Queries\BaseQuery;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSupplier;
use App\Models\Message\InnerMessage;
use App\Models\Supplier\Supplier;
use GraphQL;
use GraphQL\Type\Definition\Type;
use MatrixLab\LaravelAdvancedSearch\ModelScope;
use Illuminate\Database\Eloquent\Builder;

class SupplierListQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'supplierList',
        'description' => '供应商列表',
    ];

    public function type()
    {
        return GraphQL::pagination(GraphQL::type('Supplier'));
    }

    public function args()
    {
        return [
            'paginator'  => [
                'name'        => 'paginator',
                'type'        => Type::nonNull(GraphQL::type('PaginatorInput')),
                'description' => 'Display a specific page',
                'rules'       => ['required'],
            ],
            'more'  => [
                'name'        => 'more',
                'type'        => GraphQL::type('SupplierInputQuery'),
                'description' => '更多搜索字段',
            ],
        ];
    }

    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Customer;
    }

    protected function wheres()
    {
        $wheres = [
            'id',
            'mobile',
            new ModelScope('supplierProductName', $this->getInputArgs()),
        ];

        return $wheres;
    }

    /**
     * @param $root
     * @param $args
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function resolve($root, $args, $context, $info)
    {
        if (isset($args['more']['inner_message_id']) && $args['more']['inner_message_id']) {
            InnerMessage::read($args['more']['inner_message_id']);
        }

        $relation = [
            'products' => function($q)use($args){
                if(isset($args['more']['supplier_product_name']) && $args['more']['supplier_product_name']){
                    $q->where('name','like','%'.$args['more']['supplier_product_name'].'%');
                }
            },
            'brands',
            'customerSupplier',
        ];

        return Supplier::getList($this->getConditions($args),$relation);
    }
}
