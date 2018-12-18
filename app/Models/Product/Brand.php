<?php

namespace App\Models\Product;

use App\Models\BaseModel;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSupplier;
use App\Models\GlobalScopes\SupplierScope;
use App\Models\Supplier\Supplier;

/**
 * App\Models\Product\Brand.
 *
 * @property int                                $id
 * @property string|null                        $name          品牌名称
 * @property int|null                           $supplier_id   供应商ID
 * @property int|null                           $pid           父级品牌ID
 * @property int|null                           $level         分类层级
 * @property string                             $is_last_stage 是否末级品牌
 * @property int                                $quantity      品牌下的商品数量
 * @property string|null                        $manufacturer  生产厂商
 * @property string|null                        $status        品牌状态
 * @property \Illuminate\Support\Carbon|null    $created_at
 * @property \Illuminate\Support\Carbon|null    $updated_at
 * @property \App\Models\Supplier\Supplier|null $supplier
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand whereIsLastStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product\Brand whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Product\Product[] $products
 */
class Brand extends BaseModel
{
    // 状态
    const STATUS_ENABLED    = '正常';
    const STATUS_DISABLED   = '停用';

    const IS_LAST_STAGE    = 1;
    const NO_LAST_STAGE    = 0;

    protected $fillable = [
        'name',
        'supplier_id',
        'pid',
        'path',
        'level',
        'is_last_stage',
        'quantity',
        'manufacturer',
        'status',
        'logo_pic',
        'price_increase_ratio',
    ];

    protected $casts = [
        'price_increase_ratio'  => 'json',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SupplierScope());
        if (user() instanceof Supplier) {
            static::creating(function (Brand $brand) {
                $brand->supplier_id = user() ? user()->id : 1;
            });
        }
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function scopeSupplier($q, $input)
    {
        if (isset($input['supplier_id'])) {
            $q->whereHas('supplier', function ($q) use ($input) {
                $q->find($input['supplier_id']);
            });
        }

        return $q;
    }

    public function syncChildrenBrandStatus($brand, $status)
    {
        $brands = Brand::where('supplier_id', $brand->supplier_id)->where('path', 'like', $brand->path.'%');
        $brands->update(['status'=>$status]);

        return true;
    }

    public function syncBrandOfProductStatus($brand, $status)
    {
        $brands = Brand::where('supplier_id', $brand->supplier_id)->where('path', 'like', $brand->path.'%')->select('id', 'pid', 'is_last_stage')->get();
        if ($brands->isNotEmpty()) {
            $brandIds = [];
            $brands->each(function ($brand) use (&$brandIds) {
                if (Brand::IS_LAST_STAGE == $brand->is_last_stage) {
                    array_push($brandIds, $brand->id);
                }
            });
            Product::whereIn('brand_id', $brandIds)->update(['status'=>$status]);
        }
        return true;
    }

    public static function queryBrand($args)
    {
        $query = Brand::query();
        if (isset($args['more']['id'])) {
            $query = $query->where('id', $args['more']['id']);
        }
        if (isset($args['more']['pid'])) {
            $query = $query->where('pid', $args['more']['pid']);
        }
        if (isset($args['more']['level'])) {
            $query = $query->where('level', $args['more']['level']);
        }
        if (isset($args['more']['name'])) {
            $query = $query->where('name', 'like', '%'.$args['more']['name'].'%');
        }
        if (isset($args['more']['status'])) {
            $query = $query->where('status', $args['more']['status']);
        }
        if (isset($args['more']['supplier_id'])) {
            $query = $query->where('supplier_id', $args['more']['supplier_id']);
        }
        if (isset($args['more']['is_last_stage'])) {
            $query = $query->where('is_last_stage', $args['more']['is_last_stage']);
        }

        if (!array_has($args, 'supplier_id')) {
            if (user() instanceof Customer) {
                $suppliers   = CustomerSupplier::where('customer_id', user()->id)->where('status', CustomerSupplier::STATUS_NORMAL)->select('supplier_id')->get();
                $supplierIds = $suppliers->pluck('supplier_id');
                $query->whereIn('supplier_id', $supplierIds);
            }
        }

        return $query->get();
    }

    /**
     * 更新品牌的商品统计
     *
     * @param Brand $brand
     */
    public function syncBrandOfProducts(Brand $brand)
    {
        $brand->increment('quantity');
        if ($brand->pid) {
            $parent_brand = Brand::find($brand->pid);
            $this->syncBrandOfProducts($parent_brand);
        }
    }

    protected $allColumns = [
        'id',
        'name', // 品牌名称
        'supplier_id', // 供应商ID
        'pid', // 父级品牌ID
        'level', // 分类层级
        'is_last_stage', // 是否末级品牌
        'quantity', // 品牌下的商品数量
        'manufacturer', // 生产厂商
        'status', // 品牌状态
        'logo_pic', // 品牌LOGO图片
        'price_increase_ratio', // 阶梯定价上浮比例配置
        'created_at',
        'updated_at',
    ];
}
