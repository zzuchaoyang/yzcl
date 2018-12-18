<?php

namespace App\Models\Customer;

use App\Models\BaseModel;
use App\Models\GlobalScopes\SupplierScope;
use App\Models\GlobalScopes\CustomerScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Customer\CustomerSupplier.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @mixin \Eloquent
 * @property int $supplier_id 供应商id
 * @property int $customer_id 客户id
 * @property string $status 合作状态
 * @property string|null $supply_grade 供货价级别
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $cooperation_application_id 合作关系id
 * @property-read \App\Models\Customer\CooperationApplication|null $cooperationApplication
 * @property-read \App\Models\Customer\Customer $customer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CustomerSupplier whereCooperationApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CustomerSupplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CustomerSupplier whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CustomerSupplier whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CustomerSupplier whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CustomerSupplier whereSupplyGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CustomerSupplier whereUpdatedAt($value)
 */
class CustomerSupplier extends BaseModel
{
    const STATUS_WAIT    = 'wait';
    const STATUS_NORMAL  = 'normal';
    const STATUS_REJECT  = 'reject';
    const STATUS_PAUSE   = 'pause';

    const GRADE_ONE      = 'one';
    const GRADE_TWO      = 'two';
    const GRADE_THREE    = 'three';
    const GRADE_FOUR     = 'four';
    const GRADE_FIVE     = 'five';

    public $allStatus = [
        self::STATUS_WAIT    => '等待合作',
        self::STATUS_NORMAL  => '正常合作',
        self::STATUS_REJECT  => '拒绝合作',
        self::STATUS_PAUSE   => '暂停合作',
    ];

    public $allSupplyGrade = [
        self::GRADE_ONE    => '一级',
        self::GRADE_TWO    => '二级',
        self::GRADE_THREE  => '三级',
        self::GRADE_FOUR   => '四级',
        self::GRADE_FIVE   => '五级',
    ];

    protected $table = 'customer_supplier';

    protected $fillable = [
        'supplier_id',
        'customer_id',
        'status',
        'supply_grade',
        'cooperation_application_id',
    ];

    protected $indexs = [
        '*',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function cooperationApplication()
    {
        return $this->belongsTo(CooperationApplication::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SupplierScope());

        static::addGlobalScope(new CustomerScope());
    }

    protected $allColumns = [
        'supplier_id', // 供应商id
        'customer_id', // 客户id
        'status', // 合作状态
        'supply_grade', // 供货价级别
        'created_at',
        'updated_at',
        'cooperation_application_id', // 合作关系id
    ];
}
