<?php

namespace App\Models\Order;

use App\Models\BaseModel;
use App\Models\Customer\Customer;
use App\Models\GlobalScopes\SupplierScope;
use App\Models\Product\Brand;
use App\Models\Product\Product;
use App\Models\Supplier\Salesman;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\Driver;

class Order extends BaseModel
{
    protected $fillable = [
        'id',
        'supplier_id',
        'order_number',
        'price',
        'number',
        'send_price',
        'send_number',
        'customer_id',
        'supplier_name',
        'supplier_mobile',
        'customer_name',
        'customer_mobile',
        'salesman_id',
        'order_at',
        'status',
        'car_number',
        'driver_id',
        'send_start_at',
        'send_at',
        'send_status',
        'position_info',
        'is_edit',
        'signed_at',
        'receiving_address',
    ];

    //强制签收时间
    const Day = 3;

    const APPROVING      = 'approving';
    const UNSUPPLY       = 'unsupply';
    const CONFIRMING     = 'confirming';
    const CANCELLED      = 'cancelled';
    const SHIPPING       = 'shipping';
    const SIGNING        = 'signing';
    const FORCE_SIGNED   = 'force_signed';
    const SIGNED         = 'signed';
    const INVALID_SIGNED = 'invalid_signed';

    public $allStatus = [
        self::APPROVING      => '待审核',
        self::UNSUPPLY       => '无法供货',
        self::CONFIRMING     => '待确认',
        self::CANCELLED      => '取消订货',
        self::SHIPPING       => '待发货',
        self::SIGNING        => '待签收',
        self::FORCE_SIGNED   => '强制签收',
        self::SIGNED         => '已签收（无误）',
        self::INVALID_SIGNED => '已签收（有误）',
    ];

    const WAITING    = 'waiting';
    const DELIVERING = 'delivering';
    const DELIVERED  = 'delivered';

    public $sendStatus = [
        self::WAITING    => '待配送',
        self::DELIVERING => '待送达',
        self::DELIVERED  => '已完成',
    ];

    protected $indexs = [
        '*',
    ];

    protected $casts = [
        'position_info' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SupplierScope());
    }

    //关联订单的商品
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    // 订单管理的产品
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    //供应商
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    //客户
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    //业务员
    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }

    //司机
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    //订单日志
    public function orderLogs()
    {
        return $this->hasMany(OrderLog::class);
    }

    //是否可以强制签收
    public function canForceSigned()
    {
        $can = false;
        if ($this->send_at && $this->status == self::SIGNING) {
            $can = strtotime(now()) - strtotime($this->send_at) >= 86400 * self::Day;
        }

        return $can;
    }

    //订单编码规则
    public function orderNumberRule()
    {
        $orderNumber = 'POP'.str_replace('-', '', date('Y-m-d'));
        if (strlen($this->id) < 3) {
            $Suffix      = substr('000', 0, 3 - strlen($this->id)).$this->id;
            $orderNumber .= $Suffix;
        } else {
            $orderNumber .= $this->id;
        }

        return $orderNumber;
    }

    protected $allColumns = [
        'id',
        'supplier_id', // 供应商id
        'order_number', // 订单编号
        'price', // 订单金额
        'number', // 订单数量
        'send_price', // 发货订单金额
        'send_number', // 发出订单数量
        'customer_id', // 客户id
        'supplier_name', // 供应商的名称
        'supplier_mobile', // 供应商手机号
        'customer_name', // 零售店名称
        'customer_mobile', // 零售店手机号
        'salesman_id', // 业务员id
        'order_at', // 订单产生时间
        'status', // 订单状态
        'car_number', // 车牌号
        'driver_id', // 司机id
        'send_start_at', // 订单开始配送时间
        'position_info', // 订单送货信息
        'send_status', // 订单送货状态
        'send_at', // 订单的送达时间
        'is_edit', // 订单是否被修改过
        'signed_at', //订单的签收时间
        'receiving_address', //收货地址
        'created_at',
        'updated_at',
    ];
}
