<?php

namespace App\Models\Order;

use App\Models\BaseModel;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSupplier;
use App\Models\Product\Product;

class OrderProduct extends BaseModel
{
    protected $table = 'order_product';

    protected $fillable = [
        'id',
        'order_id',
        'customer_id',
        'product_id',
        'product_number',
        'product_price',
        'product_total_price',
        'send_price',
        'send_number',
        'send_total_price',
        'product_name',
        'product_picture',
        'product_code',
        'brand_name',
        'product_order_unit',
        'product_specifications',
    ];

    protected $indexs = [
        '*',
    ];

    //订单
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    //商品详情
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerSupplier()
    {
        return $this->hasMany(CustomerSupplier::class, 'customer_id', 'customer_id');
    }

    protected $allColumns = [
        'id',
        'order_id', // 订单id
        'customer_id', // 客户id
        'product_id', // 商品id
        'product_number', // 商品数量
        'product_price', // 商品单价
        'product_total_price', // 商品总价格
        'send_number', // 发出商品数量
        'send_price', // 发出商品单价
        'send_total_price', // 发出商品的总价格
        'product_name', //商品名字
        'product_picture', //商品封面图
        'product_code', //商品条码
        'brand_name', //商品品牌名字
        'product_order_unit', //商品订货单位
        'product_specifications', //商品订货规格
        'created_at',
        'updated_at',
    ];
}
