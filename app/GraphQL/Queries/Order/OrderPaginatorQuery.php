<?php

namespace App\GraphQL\Queries\Order;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Types\PaginationType;
use App\Models\Customer\Customer;
use App\Models\Message\InnerMessage;
use App\Models\Order\Order;
use App\Models\Supplier\Driver;
use DB;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use MatrixLab\LaravelAdvancedSearch\When;

class OrderPaginatorQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'orderPaginator',
        'description' => '订单列表',
    ];

    public function type()
    {
        return new PaginationType('Order');
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
            'more'  => [
                'name'        => 'more',
                'type'        => GraphQL::type('orderPaginatorInput'),
                'description' => '更多搜索字段',
            ],
        ];
    }

    protected function wheres()
    {
        $wheres = [
            'id',
            'order_number',
            'send_status',
            'driver_id',
            'customer_id',
            'supplier_id',
            'salesman_id',
            'status.in'        => $this->getInputArgs('status'),
            'car_number.like'  => When::make($this->getInputArgs('car_number'))->success('%'.$this->getInputArgs('car_number').'%'),
             when::make($this->getInputArgs('order_at_start') && $this->getInputArgs('order_at_end'))->success(function (Builder $q) {
                 $q->whereBetween('order_at', [
                     carbon($this->getInputArgs('order_at_start'))->startOfDay(),
                     carbon($this->getInputArgs('order_at_end'))->endOfDay(),
                 ]);
             }),
            //搜索关联查询
            $this->search(),
        ];

        return $wheres;
    }

    public function resolve($root, $args, $context, $info)
    {
        //如果时候从站内信过来，标识已读
        if ($innerMessageId = array_get($args, 'more.inner_message_id')) {
            InnerMessage::read($innerMessageId);
        }

        return  Order::getGraphQLPaginator($this->getConditions($args), $info);
    }

    /**
     * @return \Closure
     */
    protected function search()
    {
        return function (Builder $q) {
            //零售商手机号和名称
            $mobile        = $this->getInputArgs('mobile');
            $customerName  = $this->getInputArgs('customer_name');
            if ($mobile || $customerName) {
                $q->whereHas('customer', function ($q) use ($mobile, $customerName) {
                    if ($mobile) {
                        $q->where('mobile', $mobile);
                    }
                    if ($customerName) {
                        $q->where('store_info->name', 'like', '%'.$customerName.'%');
                    }
                });
            }

            //业务员姓名
            if ($salesmanName = $this->getInputArgs('salesman_name')) {
                $q->whereHas('salesman', function ($q) use ($salesmanName) {
                    $q->where('name', 'like', '%'.$salesmanName.'%');
                });
            }

            //关键字搜索（订单编号或者供应商名称） //零售商的搜索
            if ($keyWord = $this->getInputArgs('key_word')) {
                //订单编号
                $q->where(function ($q) use ($keyWord) {
                    $q->where('order_number', $keyWord);

                    if (user() instanceof Customer) {
                        $q->orWhereHas('supplier', function ($q) use ($keyWord) {
                            //供应商名称
                            $q->where('info->company->gsmc', 'like', '%'.$keyWord.'%');
                        });
                    } elseif (user() instanceof Driver) {
                        $q->orWhereHas('customer', function ($q) use ($keyWord) {
                            //零售店名称
                            $q->where('store_info->name', 'like', '%'.$keyWord.'%');
                        });
                    }
                });
            }

            //限制客户 和 司机都只能看关于自己的订单
            if (user() instanceof Customer) {
                $q->where('customer_id', user()->id);
            } elseif (user() instanceof Driver) {
                $q->where('driver_id', user()->id);
            }
        };
    }

    protected function order()
    {
        //订单规定排序
        return [
          DB::raw("FIELD(`status`, 'approving') DESC"),
        ];
    }
}
