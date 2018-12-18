<?php

namespace App\GraphQL\Queries\Product;

use App\GraphQL\Queries\BaseQuery;
use App\GraphQL\Types\PaginationType;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\GraphQL\Queries\Customer\CustomerListQuery;
use App\Models\Product\Product;
use App\Models\Supplier\Driver;
use App\Models\Supplier\Supplier;
use GraphQL;
use GraphQL\Type\Definition\Type;
use MatrixLab\LaravelAdvancedSearch\ModelScope;
use MatrixLab\LaravelAdvancedSearch\When;

class ProductPaginatorQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'productPaginator',
        'description' => '商品列表',
    ];

    public function type()
    {
        return new PaginationType('Product');
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
        $user = user();

        return $user instanceof Supplier || $user instanceof Customer || $user instanceof Driver;
    }

    public function args()
    {
        return [
            'paginator' => [
                'name'        => 'paginator',
                'type'        => Type::nonNull(GraphQL::type('PaginatorInput')),
                'description' => '分页排序',
                'rules'       => ['required'],
            ],
            'more'      => [
                'name'        => 'more',
                'type'        => GraphQL::type('ProductInput'),
                'description' => '更多搜索字段',
            ],
        ];
    }

    protected function wheres()
    {
        if (!$this->getInputArgs('statics')) {
            $wheres = [
                new ModelScope('brandName', $this->getInputArgs()),
                new ModelScope('supplier', $this->getInputArgs()),
                new ModelScope('brandId', $this->getInputArgs()),
                //'bar_code.like' => When::make($this->getInputArgs('bar_code'))->success('%'.$this->getInputArgs('bar_code').'%'),
                'name.like'     => When::make($this->getInputArgs('name'))->success('%'.$this->getInputArgs('name').'%'),
                'bar_code',
                'id',
                'status',
                'supplier_id',
            ];
        } else {
            $wheres = [
                new ModelScope('statics', $this->getInputArgs()),
            ];
        }


        return $wheres;
    }

    public function resolve($root, $args, $context, $info)
    {
        $productPaginator = Product::getGraphQLPaginator($this->getConditions($args), $info);
        // 对商品进行统计
        if ($this->getInputArgs('statics')) {
            $this->getProductStatics($productPaginator, $args, $info);
        }

        return $productPaginator;
    }

    /**
     * @param $productPaginator
     */
    public function getProductStatics($productPaginator, $args, $info)
    {
        $products = OrderProduct::selectRaw('sum(product_total_price) as order_price,sum(product_number) as order_number,sum(send_number) as real_order_number,sum(send_total_price) as real_order_price,product_id')
                    ->when(array_get($args, 'more.start_at'), function ($q) use ($args) {
                        $q->where('created_at', '>', carbon(array_get($args, 'more.start_at'))->startOfDay());
                    })
                    ->when(array_get($args, 'more.end_at'), function ($q) use ($args) {
                        $q->where('created_at', '<=', carbon(array_get($args, 'more.end_at'))->endOfDay());
                    })
                    ->when(array_get($args, 'more.city_id'), function ($q) use ($args) {
                        $q->whereHas('customer', function ($q) use ($args) {
                            $city_id = [(int)array_get($args,'more.city_id')];
                            $ids = CustomerListQuery::getChildrenIds($city_id);
                            $ids = implode(',',$ids);
                            $q->whereRaw("`store_info` -> '$.\"area_id\"' in ($ids)");
                        });
                    })
                    ->where(function($q){
                        $q->whereHas('order', function ($q) {
                            $q->whereNotIn('status', [Order::UNSUPPLY,Order::CANCELLED]);
                        });
                    })
                    ->groupBy('product_id')
                    ->groupBy('customer_id')
                    ->get();

        $productsForPaginator = $productPaginator->getCollection();

        $fields               = $info->getFieldSelection(5);

        $productsForPaginator = $productsForPaginator->map(function ($item) use ($products,$fields) {
            if (array_has($fields, 'items.order_number')) {
                $item->order_number      = sprintf('%.2f',$products->where('product_id', $item->id)->sum('order_number'));
            }
            if (array_has($fields, 'items.order_price')) {
                $item->order_price       = sprintf('%.2f',$products->where('product_id', $item->id)->sum('order_price'));
            }
            if (array_has($fields, 'items.real_order_number')) {
                $item->real_order_number = sprintf('%.2f',$products->where('product_id', $item->id)->sum('real_order_number'));
            }
            if (array_has($fields, 'items.real_order_price')) {
                $item->real_order_price  = sprintf('%.2f',$products->where('product_id', $item->id)->sum('real_order_price'));
            }
            if (array_has($fields, 'items.customer_count')) {
                $item->customer_count    = count($products->where('product_id', $item->id));
            }

            return $item;
        });

        $productPaginator->setCollection($productsForPaginator);
    }
}
