<?php

namespace App\Models\Supplier;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Kalnoy\Nestedset\NodeTrait;
use App\Models\Supplier\Traits\Repositories\SupplierOrgRepository;

/**
 * App\Models\Supplier\SupplierOrg
 *
 * @property int $id
 * @property string $name 名称
 * @property string $type 类型：部门 or 人员
 * @property int $status 状态：0 禁用 1 启用
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property int $is_tip 是否为末端：0 否 1 是
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Supplier\SupplierOrg[] $children
 * @property-read \App\Models\Supplier\SupplierOrg|null                           $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg d()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereIsTip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $category 分类
 * @property string|null $rank 级别
 * @property-read \App\Models\Supplier\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg whereSupplierId($value)
 * @property int $supplier_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\SupplierOrg byCategory($category = null)
 */
class SupplierOrg extends BaseModel
{
    /**
     * laravel-nestedset
     *
     * The nested set model is to number the nodes according to a tree traversal, which visits each node twice, assigning numbers in the order of visiting, and at both visits.
     * This leaves two numbers for each node, which are stored as two attributes.
     * Querying becomes inexpensive: hierarchy membership can be tested by comparing these numbers.
     * Updating requires renumbering and is therefore expensive.
     *
     * @link https://github.com/lazychaser/laravel-nestedset
     */
    use NodeTrait;
    use SupplierOrgRepository;

    // 状态
    const STATUS_ON = 1;
    const STATUS_OFF = 0;

    // 是否为末级
    const TIP_ON = 1;
    const TIP_OFF = 0;

    const CATEGORY_DRIVER = '司机';

    // 性质
    const TYPE_DEPARTMENT = '部门';
    const TYPE_STAFF = '人员';

    // 级别
    const RANK_1 = '一级';
    const RANK_2 = '二级';
    const RANK_3 = '三级';

    protected $fillable = [
        'supplier_id',
        'name', // 名称
        'category', // 分类
        'type', // 类型：部门 or 人员
        'status', // 状态：0 禁用 1 启用
        '_lft',
        '_rgt',
        'parent_id',
        'rank', // 级别
        'is_tip', // 是否为末端：0 否 1 是
        'mobile', // 手机号
        'id_card', // 证件号码
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (SupplierOrg $org) {
            $org->supplier_id = user() ? user()->id : 1;
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function scopeByCategory(Builder $builder, $category = null)
    {
        if (empty($category)) {
            return $builder;
        }

        return $builder->where('category', $category);
    }

    protected $allColumns = [
        'id',
        'supplier_id',
        'name', // 名称
        'category', // 分类
        'type', // 类型：部门 or 人员
        'status', // 状态：0 禁用 1 启用
        '_lft',
        '_rgt',
        'parent_id',
        'rank', // 级别
        'is_tip', // 是否为末端：0 否 1 是
        'mobile', // 手机号
        'id_card', // 证件号码
        'created_at',
        'updated_at',
    ];
}
