<?php

namespace App\Models\Finance;

use App\Models\BaseModel;
use App\Models\GlobalScopes\SupplierScope;
use App\Models\Order\Order;
use App\Models\Supplier\Supplier;

/**
 * App\Models\Finance\Loan
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @mixin \Eloquent
 * @property int $id
 * @property int $supplier_id 供应商id
 * @property string|null $amount 贷款金额
 * @property string|null $period 贷款周期
 * @property array $credential_info 证件信息
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Supplier\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Loan whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Loan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Loan whereCredentialInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Loan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Loan wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Loan whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Loan whereUpdatedAt($value)
 */
class Loan extends BaseModel
{
    protected $fillable = [
        'supplier_id',
        'amount',
        'period',
        'credential_info',
    ];

    protected $casts = [
        'credential_info' => 'json',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SupplierScope());
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public static function getSupplierAmount(Supplier $supplier)
    {
        /***
         * 额度计算公式：
         * 最低    已签收（有误、无误、强制）的收货金额*0.6
         * 最高    已签收（有误、无误、强制）的收货金额*1.5
         *
         * 最低额度为 20000元
         */

        $amount = Order::where('created_at', now()->subYear())
            ->where('supplier_id', $supplier->id)
            ->whereIn('status', [Order::SIGNED, Order::FORCE_SIGNED, Order::INVALID_SIGNED])
            ->sum('price');

        $min = $amount * 0.6;
        $max = $amount * 1.5;
        $min = $min < 20000 ? 20000 : $min;
        $max = $max < 20000 ? 20000 : $max;

        return compact('min', 'max');
    }

    protected $allColumns = [
        'id',
        'supplier_id', // 供应商id
        'amount', // 贷款金额
        'period', // 贷款周期
        'credential_info', // 证件信息
        'created_at',
        'updated_at',
    ];
}
