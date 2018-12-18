<?php

namespace App\Models\Product;

use App\Models\BaseModel;
use App\Models\GlobalScopes\SupplierScope;
use App\Models\Product\Traits\Respositores\ProductPriceAdjustmentRepository;

/**
 * App\Models\Product\ProductPriceAdjustment.
 *
 * @property int                             $id
 * @property string|null                     $code         调价单编码
 * @property \Illuminate\Support\Carbon|null $effective_at 生效时间
 * @property int|null                        $number       调价商品数量
 * @property string|null                     $status       调价单状态
 * @property int|null                        $author_id    制单人ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereEffectiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property int|null    $supplier_id 供应商ID
 * @property array|null  $products    调价单商品信息
 * @property int|null    $producer_id 制单人ID
 * @property string|null $examine_at  审核时间
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment barCode($barCode)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment productName($productName)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereExamineAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereProducerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\ProductPriceAdjustment whereSupplierId($value)
 */
class ProductPriceAdjustment extends BaseModel
{
    const NO_EXAMINE = '未审核';
    const IS_EXAMINE = '已审核';
    const IS_CANCEL  = '已作废';
    const IS_SYNC    = 1;
    const NO_SYNC    = 0;
    protected $fillable = [
        'id',
        'supplier_id',
        'products',
        'code',
        'effective_at',
        'number',
        'status',
        'author_id',
        'producer_id',
        'canceler_id',
        'is_sync',
        'sync_at',
        'examine_at',
        'cancel_at',
    ];

    protected $dates = [
        'effective_at',
    ];

    protected $casts = [
        'products'  => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SupplierScope());

        static::creating(function (ProductPriceAdjustment $productPriceAdjustment) {
            $productPriceAdjustment->supplier_id = user() ? user()->id : 1;
        });
    }

    public static function scopeBarCode($q, $barCode)
    {
        if ($barCode) {
            $ids     = [];
            $product = Product::where('supplier_id', user()->id)->where('bar_code', $barCode)->first();
            if ($product) {
                $productPriceLogs = ProductPriceLog::where('supplier_id', $product->supplier_id)->where('product_id', $product->id)->get();
                $ids              = $productPriceLogs->pluck('product_price_adjustment_id')->toArray();

                $q->whereIn('id', $ids);
            }
        }

        return $q;
    }

    public function scopeProduct($q, $args)
    {
        $adjustmentIds = [];

        $productName   = isset($args['product_name']) ? $args['product_name'] : null;
        $barCode       = isset($args['bar_code']) ? $args['bar_code'] : null;


        if ($productName || $barCode) {
            $query         = Product::where('supplier_id', user()->id);
            if ($productName) {
                $query = $query->where('name', 'like', '%'.$productName.'%');
            }
            if ($barCode) {
                $query = $query->where('bar_code', $barCode);
            }


            $products = $query->get();
            if ($products->isNotEmpty()) {
                $products->each(function ($product) use (&$adjustmentIds) {
                    $productPriceLogs = ProductPriceLog::where('supplier_id', $product->supplier_id)->where('product_id', $product->id)->whereNotNull('product_price_adjustment_id')->get();
                    $ids = $productPriceLogs->pluck('product_price_adjustment_id')->toArray();
                    $adjustmentIds = array_merge($adjustmentIds, $ids);
                });
                $adjustmentIds = array_unique($adjustmentIds);
            }
            $q->whereIn('id', $adjustmentIds);
        }

        return $q;
    }

    protected $allColumns = [
        'id',
        'supplier_id', // 供应商ID
        'products', // 调价单商品信息
        'code', // 调价单编码
        'effective_at', // 生效时间
        'number', // 调价商品数量
        'status', // 调价单状态
        'author_id', // 制单人ID
        'producer_id', // 制单人ID
        'canceler_id', // 作废者ID
        'is_sync', // 是否同步商品价格 0:否；1:是
        'examine_at', // 审核时间
        'cancel_at', // 作废时间
        'sync_at', // 调价单同步商品价格时间
        'created_at',
        'updated_at',
    ];
}
