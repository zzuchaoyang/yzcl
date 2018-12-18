<?php

namespace App\GraphQL\Queries\Message;

use App\GraphQL\Queries\BaseQuery;
use App\Models\Customer\Customer;
use App\Models\Message\InnerMessage;
use DB;
use GraphQL\Type\Definition\Type;
use GraphQL;
use App\GraphQL\Types\PaginationType;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Supplier\Driver;

class InnerMessageListQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'innerMessage',
        'description' => '站内信列表',
    ];

    public function type()
    {
        return new PaginationType('InnerMessage');
    }

    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Customer || user() instanceof Driver;
    }

    public function args()
    {
        return [
            'paginator'  => [
                'name'        => 'paginator',
                'type'        => Type::nonNull(GraphQL::type('PaginatorInput')),
                'description' => '分页排序',
                'rules'       => ['required'],
            ],
            'is_read'  => [
                'name'        => 'is_read',
                'type'        => Type::BOOLEAN(),
                'description' => '是否已读',
            ],
        ];
    }

    protected function wheres()
    {
        $wheres = [
            function(Builder $q){
                $q->when(user() instanceof Customer,function($q){
                   $q->where('receiver_type','customer')->where('receiver_id',user()->id);
                });
                $q->when(user() instanceof Driver,function($q){
                    $q->where('receiver_type','driver')->where('receiver_id',user()->id);
                });
                $q->when($this->getInputArgs('is_read'),function($q){
                    $q->where('is_read',0);
                });
            }
        ];

        return $wheres;
    }

    protected function order()
    {
        //订单规定排序
        return [
            DB::raw("FIELD(`is_top`, 1) DESC"),
        ];
    }

    public function resolve($root, $args, $context, $info)
    {
        return InnerMessage::getGraphQLPaginator($this->getConditions($args), $info);
    }
}
