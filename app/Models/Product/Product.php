<?php

namespace App\Models\Product;

use App\Exceptions\GeneralException;
use App\GraphQL\Queries\Customer\CustomerListQuery;
use App\Models\BaseModel;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSupplier;
use App\Exceptions\GeneralContinueException;
use App\Models\GlobalScopes\SupplierScope;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Supplier\Supplier;

/**
 * App\Models\Product\Product.
 *
 * @property int                             $id
 * @property int|null                        $brand_id       品牌ID
 * @property int|null                        $supplier_id    供应商ID
 * @property string|null                     $name           商品名称
 * @property string|null                     $bar_code       商品条码
 * @property string|null                     $unit           商品单位
 * @property int|null                        $spec_number    规格数量
 * @property string|null                     $spec_unit      规格单位
 * @property mixed|null                      $retail_price   建议零售价
 * @property string|null                     $make_place     产地
 * @property string|null                     $manufacturer   生产厂商
 * @property array|null                      $pictures       商品图片
 * @property array|null                      $check_pictures 商品检测图片
 * @property mixed|null                      $five_price     五级供货价
 * @property mixed|null                      $four_price     四级供货价
 * @property mixed|null                      $three_price    三级供货价
 * @property mixed|null                      $two_price      二级供货价
 * @property mixed|null                      $one_price      一级供货价
 * @property mixed|null                      $trade_price    批发含税价
 * @property int|null                        $single_num     单品数量
 * @property string|null                     $order_unit     订货单位
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\Product\Brand|null  $brand
 * @property \App\Models\Order\OrderProduct  $order_product
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword( $key )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereBarCode( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereBrandId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereCheckPictures( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereFivePrice( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereFourPrice( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereMakePlace( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereManufacturer( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereName( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereOnePrice( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereOrderUnit( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product wherePictures( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereRetailPrice( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereSingleNum( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereSpecNumber( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereSpecUnit( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereSupplierId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereThreePrice( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereTradePrice( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereTwoPrice( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereUnit( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereUpdatedAt( $value )
 * @mixin \Eloquent
 *
 * @property string                             $status          品牌状态
 * @property array|null                         $ratio_configure 阶梯定价上浮比例配置
 * @property \App\Models\Supplier\Supplier|null $supplier
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product brandName($input)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereRatioConfigure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Product whereStatus($value)
 */
class Product extends BaseModel
{
    const STEP_RATIO        = 5;
    const STATUS_ENABLED    = '正常';
    const STATUS_DISABLED   = '停用';

    protected $fillable = [
        'id',
        'supplier_id',
        'brand_id',
        'status',
        'name',
        'bar_code',
        'unit',
        'spec_number',
        'spec_unit',
        'retail_price',
        'quality_period',
        'make_place',
        'manufacturer',
        'pictures',
        'bar_code',
        'check_pictures',
        'order_unit',
        'single_num',
        'trade_price',
        'ratio_configure',
        'one_price',
        'two_price',
        'three_price',
        'four_price',
        'five_price',
    ];

    protected $casts = [
        'pictures'       => 'json',
        'check_pictures' => 'json',
        'ratio_configure'=> 'json',
        'retail_price'   => 'decimal',
        'trade_price'    => 'decimal',
        'one_price'      => 'decimal',
        'two_price'      => 'decimal',
        'three_price'    => 'decimal',
        'four_price'     => 'decimal',
        'five_price'     => 'decimal',
    ];

    protected $indexs = [
        '*',
    ];

    const ONE        = 'one';
    const TWO        = 'two';
    const THREE      = 'three';
    const FOUR       = 'four';
    const FIVE       = 'five';

    public $allStatus = [
        self::ONE        => 'one_price',
        self::TWO        => 'two_price',
        self::THREE      => 'three_price',
        self::FOUR       => 'four_price',
        self::FIVE       => 'five_price',
    ];

    protected static function boot()
    {
        //        parent::boot();
        //        static::addGlobalScope(new SupplierScope());
        static::creating(function (Product $product) {
            $product->supplier_id = user() ? user()->id : 1;
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function order_product()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function customerSupplier()
    {
        return $this->belongsTo(CustomerSupplier::class, 'supplier_id', 'supplier_id');
    }

    public static function scopeBrandName($q, $input)
    {
        if (isset($input['brand_name']) && $input['brand_name']) {
            $brands     = Brand::where('name', 'like', '%'.$input['brand_name'].'%')->get();
            $brandIdArr = [];
            if ($brands->isNotEmpty()) {
                foreach ($brands->toArray() as $brand) {
                    $newBrands   = Brand::where('path', 'like', '%'.$brand['path'].'%')->where('is_last_stage', Brand::IS_LAST_STAGE)->get();
                    $newBrandIds = $newBrands->pluck('id');
                    $newBrandIdArr = [];
                    if ($newBrandIds->isNotEmpty()) {
                        $newBrandIdArr = $newBrandIds->toArray();
                    }
                    $brandIdArr = array_merge($brandIdArr, $newBrandIdArr);
                }
            }
            $q->whereIn('brand_id', $brandIdArr);
        }

        return $q;
    }

    public static function scopeStatics($q, $args)
    {
        $product_ids = OrderProduct::selectRaw('product_id')->where(function ($q) use ($args) {
            $q->whereHas('order', function ($q) use ($args) {
                $q->where('supplier_id', array_get($args, 'supplier_id'));
                $q->whereNotIn('status', [Order::UNSUPPLY, Order::CANCELLED]);
            });
        })
            ->when(array_get($args, 'start_at'), function ($q) use ($args) {
                $q->where('created_at', '>', carbon(array_get($args, 'start_at'))->startOfDay());
            })
            ->when(array_get($args, 'end_at'), function ($q) use ($args) {
                $q->where('created_at', '<=', carbon(array_get($args, 'end_at'))->endOfDay());
            })
            ->when(array_get($args, 'city_id'), function ($q) use ($args) {
                $q->whereHas('customer', function ($q) use ($args) {
                    $city_id = [(int)array_get($args,'city_id')];
                    $ids = CustomerListQuery::getChildrenIds($city_id);
                    $ids = implode(',',$ids);
                    $q->whereRaw("`store_info` -> '$.\"area_id\"' in ($ids)");
                });
            })
            ->where(function ($q) use ($args) {
                $q->whereHas('product', function ($q) use ($args) {
                    if (array_get($args, 'name')) {
                        $q->where('name', 'like', '%'.array_get($args, 'name').'%');
                    }
                    if (array_get($args, 'bar_code')) {
                        $q->where('bar_code', array_get($args, 'bar_code'));
                    }
                    if (array_get($args, 'brand_name')) {
                        $brands = Brand::where('name', 'like', '%'.$args['brand_name'].'%')->get();
                        if ($brands) {
                            foreach ($brands->toArray() as $brand) {
                                if (Brand::IS_LAST_STAGE == $brand['is_last_stage']) {
                                    $q->where('brand_id', $brand['id']);
                                } else {
                                    $brandIds = Brand::getLastStageBrandIds($brand['id']);
                                    $q->whereIn('brand_id', $brandIds);
                                }
                            }
                        }
                    }
                });
            })
            ->groupBy('product_id')->pluck('product_id');
        $q->whereIn('id', $product_ids->toArray());

        return $q;
    }

    public static function scopeBrandId($q, $input)
    {
        if (isset($input['brand_id']) && $input['brand_id']) {
            $brand = Brand::find($input['brand_id']);
            if ($brand) {
                $brands   = Brand::where('path', 'like', '%'.$brand->path.'%')->where('is_last_stage', Brand::IS_LAST_STAGE)->get();
                $brandIds = $brands->pluck('id');
                if ($brandIds->isNotEmpty()) {
                    $brandIds = $brandIds->toArray();
                }
                $q->whereIn('brand_id', $brandIds);
            }
        }

        return $q;
    }

    public static function scopeSupplier($q, $input)
    {
        if (!array_has($input, 'supplier_id')) {
            if (user() instanceof Customer) {
                $suppliers   = CustomerSupplier::where('customer_id', user()->id)->where('status', CustomerSupplier::STATUS_NORMAL)->select('supplier_id')->get();
                $supplierIds = $suppliers->pluck('supplier_id');
                $q->whereIn('supplier_id', $supplierIds);
            }
        }

        return $q;
    }

    /**
     * 得到商品的真实价格
     *
     * @param Customer $customer
     * @param null     $orderAt
     * @param null     $supplierId
     * @param null     $productId
     *
     * @return mixed|null
     */
    public function getRealPrice(Customer $customer, $orderAt, $supplierId, $productId)
    {
        //查找客户等级，以及订单时间进行判断，找对应当前的供货价
        $relation = CustomerSupplier::where('customer_id', $customer->id)->where('supplier_id', $supplierId)->where('status', CustomerSupplier::STATUS_NORMAL)->first();
        if (!$relation) {
            throw new GeneralContinueException('商铺与供应商之间没有合作关系，请验证一下');
        }
        $grade    = array_get($this->allStatus, $relation->supply_grade);
        $priceLog = ProductPriceLog::where('supplier_id', $supplierId)->where('product_id', $productId)->where('price_adjustment_status', '已审核')->where('effective_at', '<=', $orderAt)->orderBy('effective_at', 'DESC')->orderBy('id', 'DESC')->first();
        if (!$priceLog) {
            throw new GeneralContinueException('商品的价格出现错误，请联系商家确认');
        }

        $price = array_get($priceLog->prices, $grade);
        //如果没有价钱，取一级价格
        if ($price <= 0) {
            $price = array_get($priceLog->prices, 'one_price');
        }

        return $price;
    }

    /**
     * 检查是否存在商品条码
     *
     * @param $code
     * @param $productId
     *
     * @throws GeneralException
     */
    public static function checkExistBarCode($code, $productId = null)
    {
        $query = Product::where('supplier_id', user()->id)->where('bar_code', $code);
        // 如果传递的有 ID 值，那么就要排除这个数值
        if ($productId) {
            $query->whereNotIn('id', [$productId]);
        }
        if ($query->exists()) {
            throw new GeneralException('商品条码:'.$code.'的商品已存在');
        }
    }

    protected $allColumns = [
        'id',
        'brand_id', // 品牌ID
        'supplier_id', // 供应商ID
        'status', // 品牌状态
        'name', // 商品名称
        'bar_code', // 商品条码
        'unit', // 商品单位
        'spec_number', // 规格数量
        'spec_unit', // 规格单位
        'retail_price', // 建议零售价
        'quality_period', //保质期
        'make_place', // 产地
        'manufacturer', // 生产厂商
        'pictures', // 商品图片
        'check_pictures', // 商品检测图片
        'five_price', // 五级供货价
        'four_price', // 四级供货价
        'three_price', // 三级供货价
        'two_price', // 二级供货价
        'one_price', // 一级供货价
        'trade_price', // 批发含税价
        'ratio_configure', // 阶梯定价上浮比例配置
        'single_num', // 单品数量
        'order_unit', // 订货单位
        'created_at',
        'updated_at',
    ];
}
