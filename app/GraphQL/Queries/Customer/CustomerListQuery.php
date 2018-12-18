<?php

namespace App\GraphQL\Queries\Customer;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Types\PaginationType;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSupplier;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\System\Area;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use MatrixLab\LaravelAdvancedSearch\ModelScope;
use MatrixLab\LaravelAdvancedSearch\When;
use DB;
use Carbon\Carbon;

class CustomerListQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'customerList',
        'description' => '客户列表',
    ];

    public function type()
    {
        return new PaginationType('Customer');
        //return Type::listOf(GraphQL::type('Customer'));
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
                'type'        => GraphQL::type('CustomerPaginationInputQuery'),
                'description' => '更多搜索字段',
            ],
        ];
    }

    protected function wheres()
    {
        if (!$this->getInputArgs('statics')){
            $wheres = [
                'id',
                'mobile',
                new ModelScope('customerSupplier', $this->getInputArgs()),
                function(Builder $q){
                    $q->when($this->getInputArgs('name'),function($q){
                        $q->where('store_info->name','like','%'.$this->getInputArgs('name').'%');
                    });
                    $q->when($this->getInputArgs('city_id'), function ($q) {
                        $city_id = [(int)$this->getInputArgs('city_id')];
                        $ids = self::getChildrenIds($city_id);
                        $ids = implode(',',$ids);
                        $q->whereRaw("`store_info` -> '$.\"area_id\"' in ($ids)");
                    });
                },
            ];
        } else {
            $wheres = [
                new ModelScope('customerStatics', $this->getInputArgs()),
            ];
        }


        return $wheres;
    }

    public static function getChildrenIds($sort_id)
    {
        $result = Area::whereIn('parent_id',$sort_id)->pluck('id')->toArray();
        $ids = [$sort_id];
        if (isset($result[0]))
        {
            $ids[] = $result;
            $ids[] = self::getChildrenIds($result);
        }

        return array_unique(array_collapse(array_merge($ids,$sort_id)));
    }

    /**
     * @param $root
     * @param $args
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function resolve($root, $args, $context, $info)
    {
        $customerPaginator     = Customer::getGraphQLPaginator($this->getConditions($args), $info);
        $fields                = $info->getFieldSelection(5);

        $customersForPaginator = $customerPaginator->getCollection();
        $customersForPaginator->map(function ($item) use ($args, $fields) {
            if (array_has($fields, 'items.history_commission')) {
                $historyCommmission = Order::where('customer_id', array_get($args, 'more.id'))->whereIn('status',[Order::SIGNED,Order::INVALID_SIGNED,Order::FORCE_SIGNED])->sum('send_price');
                $item->offsetSet('history_commission', sprintf('%.2f',$historyCommmission));
            }

            if (array_has($fields, 'items.city')) {
                $city = (isset($item->store_info) && isset($item->store_info['area_id'])) ? $this->getCityName($item->store_info['area_id'],Area::find($item->store_info['area_id'])->name,true) : null;
                $address = (isset($item->store_info) && isset($item->store_info['address'])) ? $item->store_info['address'] : null;
                $cityName = $city.$address;
                $cityName = str_replace('市辖区','',$cityName);
                $cityName = str_replace('直辖区','',$cityName);
                $item->offsetSet('city', $cityName);
            }
            if (!$this->getInputArgs('statics') && !$this->getInputArgs('is_add') && isset($item->customerSupplier()->where('supplier_id',user()->id)->where('customer_id',$item->id)->get()[0])) {
                $time = $item->customerSupplier->where('supplier_id',user()->id)->where('customer_id',$item->id)->first()->created_at;
                $item->offsetSet('time',$time->toDateTimeString());
            }

            return $item;
        });
        if (!$this->getInputArgs('statics')  && !$this->getInputArgs('is_add')) {
            $customersForPaginator = collect($customerPaginator->items())->sortByDesc('time');
        }

        $customerPaginator->setCollection($customersForPaginator);

        // 对客户销售进行统计
        if ($this->getInputArgs('statics')) {
            $this->getCustomerStatics($customerPaginator, $args, $fields);
        }

        return $customerPaginator;
    }

    /**
     * @param $productPaginator
     */
    public function getCustomerStatics($customerPaginator, $args, $fields)
    {
        $products = OrderProduct::selectRaw('sum(product_total_price) as order_price,sum(product_number) as order_number,sum(send_number) as real_order_number,sum(send_total_price) as real_order_price,customer_id')
            ->when(array_get($args, 'more.start_at'), function ($q) use ($args) {
                $q->where('created_at', '>', carbon(array_get($args, 'more.start_at'))->startOfDay());
            })
            ->when(array_get($args, 'more.start_at'), function ($q) use ($args) {
                $q->where('created_at', '<=', carbon(array_get($args, 'more.end_at'))->endOfDay());
            })
            ->where(function($q){
                $q->whereHas('order', function ($q) {
                    $q->whereNotIn('status', [Order::UNSUPPLY,Order::CANCELLED]);
                });
            })
            ->when(array_get($args, 'more.city_id'), function ($q) use ($args) {
                $q->whereHas('customer', function ($q) use ($args) {
                    $city_id = [(int)array_get($args,'more.city_id')];
                    $ids = CustomerListQuery::getChildrenIds($city_id);
                    $ids = implode(',',$ids);
                    $q->whereRaw("`store_info` -> '$.\"area_id\"' in ($ids)");
                });
            })
            ->groupBy('customer_id')
            ->groupBy('order_id')
            ->get();
        $customersForPaginator = $customerPaginator->getCollection();

        $customersForPaginator = $customersForPaginator->map(function ($item) use ($products,$fields) {
            if (array_has($fields, 'items.order_count')) {
                $item->order_count       = count($products->where('customer_id', $item->id));
            }
            if (array_has($fields, 'items.order_number')) {
                $item->order_number      = sprintf('%.2f',$products->where('customer_id', $item->id)->sum('order_number'));
            }
            if (array_has($fields, 'items.order_price')) {
                $item->order_price       = sprintf('%.2f',$products->where('customer_id', $item->id)->sum('order_price'));
            }
            if (array_has($fields, 'items.real_order_number')) {
                $item->real_order_number = sprintf('%.2f',$products->where('customer_id', $item->id)->sum('real_order_number'));
            }
            if (array_has($fields, 'items.real_order_price')) {
                $item->real_order_price  = sprintf('%.2f',$products->where('customer_id', $item->id)->sum('real_order_price'));
            }

            return $item;
        });

        $customerPaginator->setCollection($customersForPaginator);
    }

    public function getCityName($area_id,$name,$first)
    {
        $area = Area::find($area_id);

        if ($area->parent_id != 0) {
            if (!$first) {
                $name .= ('/'.$area->name);
            }
            return $this->getCityName($area->parent_id,$name,false);
        } else {
            if ($first) {
                $all_name = $area->name;
            } else {
                $reverse_name = implode('',array_reverse(explode('/',$name)));
                $all_name =  $area->name.$reverse_name;
            }
            return $all_name;
        }
    }
}
