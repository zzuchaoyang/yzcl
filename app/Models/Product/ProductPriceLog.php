<?php

namespace App\Models\Product;

use App\Models\BaseModel;
use App\Models\GlobalScopes\SupplierScope;

/**
 * App\Models\Product\ProductPriceLog.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @mixin \Eloquent
 *
 * @property int                                             $id
 * @property int|null                                        $supplier_id                 供应商ID
 * @property int|null                                        $brand_id                    商品所属品牌ID
 * @property int|null                                        $product_id                  商品ID
 * @property int|null                                        $product_price_adjustment_id 商品调价单ID
 * @property array|null                                      $prices                      商品价格
 * @property \Illuminate\Support\Carbon|null                 $effective_at                调价单生效时间
 * @property \Illuminate\Support\Carbon|null                 $created_at
 * @property \Illuminate\Support\Carbon|null                 $updated_at
 * @property \App\Models\Product\Brand|null                  $brand
 * @property \App\Models\Product\Product|null                $product
 * @property \App\Models\Product\ProductPriceAdjustment|null $productPriceAdjustment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceLog whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceLog whereEffectiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceLog wherePrices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceLog whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceLog whereProductPriceAdjustmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceLog whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceLog whereUpdatedAt($value)
 */
class ProductPriceLog extends BaseModel
{
    const NO_EXAMINE = '未审核';
    const IS_EXAMINE = '已审核';
    const IS_CANCEL  = '已作废';

    protected $fillable = [
        'id',
        'supplier_id',
        'brand_id',
        'product_id',
        'product_price_adjustment_id',
        'prices',
        'price_adjustment_status',
        'effective_at',
    ];

    protected $casts = [
        'prices'          => 'json',
        'effective_at'    => 'date',
    ];

    protected $indexs = [
        '*',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SupplierScope());

        static::creating(function (ProductPriceLog $productPriceLog) {
            $productPriceLog->supplier_id = user() ? user()->id : 1;
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productPriceAdjustment()
    {
        return $this->belongsTo(ProductPriceAdjustment::class);
    }

    //生成一个商品价格日志
    public static function syncPriceLog(Product $product)
    {
        if (isset($product->one_price) && $product->one_price) {
            $data['supplier_id']   = $product->supplier_id;
            $data['brand_id']      = $product->brand_id;
            $data['product_id']    = $product->id;
            $data['prices']        = [
                'one_price'     => $product->one_price,
                'two_price'     => $product->two_price,
                'three_price'   => $product->three_price,
                'four_price'    => $product->four_price,
                'five_price'    => $product->five_price,
                'retail_price'  => $product->retail_price,
                'trade_price'   => $product->trade_price,
            ];
            $data['price_adjustment_status']   = ProductPriceAdjustment::IS_EXAMINE;
            $data['effective_at']              = carbon(now())->startOfDay()->toDateTimeString();

            return ProductPriceLog::create($data);
        }
    }

    protected $allColumns = [
        'id',
        'supplier_id', // 供应商ID
        'brand_id', // 商品所属品牌ID
        'product_id', // 商品ID
        'product_price_adjustment_id', // 商品调价单ID
        'prices', // 商品价格
        'price_adjustment_status', // 调价单状态
        'effective_at', // 调价单生效时间
        'created_at',
        'updated_at',
    ];
}
