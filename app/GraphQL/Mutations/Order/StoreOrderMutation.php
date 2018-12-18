<?php

namespace App\GraphQL\Mutations\Order;

use App\Exceptions\GeneralException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Product\Product;
use App\Models\Supplier\Driver;
use App\Models\Supplier\Supplier;
use App\Models\System\Area;
use GraphQL\Type\Definition\Type;
use DB;
use GraphQL;

class StoreOrderMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'storeOrder',
        'description' => '订单创建/更新',
    ];

    public function type()
    {
        return GraphQL::type('Order');
    }

    public function args()
    {
        return [
            'data' => [
                'name'        => 'data',
                'type'        => Type::nonNull(GraphQL::type('StoreOrderMutationInput')),
                'description' => '订单信息',
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
        $user = user();

        return $user instanceof Supplier || $user instanceof Customer || $user instanceof Driver;
    }

    public function resolve($root, $args)
    {
        $data = $args['data'];
        $id   = array_get($data, 'id');

        return $id ? $this->updateOrder(Order::findOrFail($id), $data) : $this->createOrder($data);
    }

    /**
     * 创建订单.
     *
     * @param $data
     *
     * @return mixed
     *
     * @throws GeneralException
     * @throws \Throwable
     */
    private function createOrder($data)
    {
        if (!user() instanceof Customer) {
            throw new GeneralException('只有门店用户才能创建订单');
        }

        if (!array_get($data, 'supplier_id')) {
            throw new GeneralException('必须选择供应商');
        }
        //订单详情创建信息
        list($numberAmount, $priceAmount, $orderProductsForSync) = $this->getOrderProductAmountAndSync($data);
        //订单创建信息
        $orderData = $this->getOrderData($numberAmount, $priceAmount, Supplier::find($data['supplier_id']));

        return DB::transaction(function () use ($orderProductsForSync, $orderData, $data) {
            $order = Order::create($orderData);
            $order->products()->sync($orderProductsForSync);

            return $order;
        });
    }

    /**
     * 更新订单信息.
     *
     * @param Order $order
     * @param $data
     *
     * @return mixed
     *
     * @throws GeneralException
     * @throws \SM\SMException
     */
    private function updateOrder(Order $order, $data)
    {
        // 供应商能修改订单上发货商品数量
        if (!user() instanceof Supplier) {
            throw new GeneralException('只有供应商才能进行订单修改');
        }

        if ($order->status !== Order::APPROVING) {
            throw new GeneralException('只有在待审核状态才能进行订单修改');
        }

        // 计算出订单的商品数量和价格，以及订单的商品信息
        list($numberAmount, $priceAmount, $orderProductsForSync, $isChange) = $this->getOrderProductAmountAndSync($data, $order);

        // 更新订单的总额和数量
        $order->send_number = $numberAmount;
        $order->send_price  = $priceAmount;
        $order->is_edit     = false;
        if ($isChange > 0) {
            //如果数量发生变化，标记为订单已修改
            $order->is_edit     = true;
        }

        return DB::transaction(function () use ($orderProductsForSync, $data, $order) {
            $order->save();
            // 同步订单信息
            $order->products()->sync($orderProductsForSync);

            return $order;
        });
    }

    /**
     * 获取订单的商品信息和订单的总数量和总金额.
     *
     * @param $data
     * @param Order|null $order
     *
     * @return array
     *
     * @throws GeneralException
     */
    private function getOrderProductAmountAndSync($data, Order $order = null): array
    {
        //记录这个订单是否发生过变化
        $isChange             = 0;
        $orderProducts        = $data['order_products'];
        $priceAmount          = $numberAmount = 0;
        $orderProductsForSync = [];
        $customer             = $order ? $order->customer : (user() instanceof Customer ? user() : null);
        $products             = Product::whereIn('id', array_pluck($orderProducts, 'product_id'))->with('brand')->get();
        $supplierId           = $order->supplier_id ?? array_get($data, 'supplier_id');
        // 计算订单价格和数量
        foreach ($orderProducts as $item) {
            $product = $products->where('id', $item['product_id'])->first();
            if (!$product) {
                throw new GeneralException('订单中的商品有异常，不能进行提交');
            }

            // 获取对应商品的价格和商品信息
            list($price, $orderProduct) = $this->generateOrderProduct($order, $item, $product, $customer, $supplierId);

            // 将商品和订单进行关联
            $orderProductsForSync[$product->id] = $orderProduct;

            //订单的总量和金额
            $numberAmount += $orderProduct['send_number'];
            $priceAmount += $price * $orderProduct['send_number'];
            if ($order && $item['send_number'] != $item['product_number']) {
                ++$isChange;
            }
        }

        return [$numberAmount, $priceAmount, $orderProductsForSync, $isChange];
    }

    /**
     * 生成对应商品的价格和商品信息.
     *
     * @param Order $order
     * @param $item
     * @param Product $product
     * @param $customer
     * @param $supplierId
     *
     * @return array
     */
    private function generateOrderProduct($order, $item, Product $product, $customer, $supplierId): array
    {

        // 创建商品此次订货的单价
        if (!$order) {
            $price       = $product->getRealPrice($customer, now(), $supplierId, $item['product_id']);
        } else {
            //如果是修改订单，价格还就是生成订单时的
            $orderProduct = OrderProduct::find(array_get($item, 'id'));
            $price        = $orderProduct->send_price;
        }
        //创建订单时发货价格和发货数量跟订货价和数量一致（修改订单只能修改发货数量）
        $send_number = array_get($item, 'send_number') ?? $item['product_number'];
        if ($send_number > $item['product_number']) {
            throw new GeneralException('修改的订货数量不能比订货数量多，请重新修改提交');
        }
        // 关联的商品信息
        $orderProduct = [
            'send_number'      => $send_number,
            'send_price'       => $price,
            'send_total_price' => $price * $send_number,
            'updated_at'       => now(),
        ];
        // 如果是创建操作，需要额外补充几个字段
        if (!$order) {
            $orderProduct = array_merge($orderProduct, [
                'customer_id'                   => user()->id,
                'product_name'                  => $product->name,
                'product_picture'               => array_get($product->pictures, 0),
                'product_code'                  => $product->bar_code,
                'brand_name'                    => $product->brand->name,
                'product_order_unit'            => $product->order_unit,
                'product_specifications'        => $product->spec_number.$product->spec_unit.'*'.$product->single_num.$product->unit.'/'.$product->order_unit,
                'product_number'                => $item['product_number'],
                'product_price'                 => $price,
                'product_total_price'           => $price * $item['product_number'],
                'created_at'                    => now(),
            ]);
        }

        return [$price, $orderProduct];
    }

    private function getOrderData($numberAmount, $priceAmount, $supplier)
    {
        //获取详细的收货地址
        $customer = user();
        $address  = array_get($customer->store_info, 'address');
        if ($area = Area::find(array_get($customer->store_info, 'area_id'))) {
            $address =  implode('-', explode(',', $area->merger_name)).' '.$address;
        }
        // 要保存的订单数据和订单的商品数据
        $orderData = [
            'supplier_id'       => $supplier->id,
            'supplier_mobile'   => $supplier->mobile,
            'supplier_name'     => $supplier->info['company']['gsmc'],
            'customer_name'     => $customer->store_info['name'],
            'customer_mobile'   => $customer->mobile,
            'customer_id'       => $customer->id,
            'status'            => Order::APPROVING,
            'price'             => $priceAmount,
            'number'            => $numberAmount,
            'send_number'       => $numberAmount,
            'send_price'        => $priceAmount,
            'order_at'          => now(),
            'receiving_address' => $address,
        ];

        return $orderData;
    }
}
