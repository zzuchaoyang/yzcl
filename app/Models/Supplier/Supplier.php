<?php

namespace App\Models\Supplier;

use App\Models\AbleTrait\ListTrait;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSupplier;
use App\Models\Finance\Loan;
use App\Models\Product\Brand;
use App\Models\Product\Product;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MatrixLab\LaravelAdvancedSearch\AdvancedSearchTrait;
use MatrixLab\LaravelAdvancedSearch\WithAndSelectForGraphQLGeneratorTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\Supplier\Supplier.
 *
 * @property int $id
 * @property string|null $name
 * @property string $mobile
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $invitation_code 邀请码
 * @property array|null $info 基本信息
 * @property array|null $loan_info 贷款信息概要
 * @property array|null $setting 供应商配置信息
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Customer\CustomerSupplier[] $customerSupplier
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Finance\Loan[] $loans
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Product\Product[] $products
 * @property \Kalnoy\Nestedset\Collection|\App\Models\Supplier\SupplierOrg[] $supplierOrgs
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier searchKeyword($key)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier supplierProductName($condition)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier whereInvitationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier whereLoanInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier whereSetting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Supplier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Supplier extends Authenticatable implements JWTSubject
{
    use ListTrait;
    use Notifiable;
    use AdvancedSearchTrait;
    use WithAndSelectForGraphQLGeneratorTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mobile',
        'invitation_code',
        'info',
        'loan_info',
        'setting',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'info'                => 'json',
        'loan_info'           => 'json',
        'price_increase_ratio'=> 'json',
        'setting'             => 'json',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function supplierOrgs()
    {
        return $this->hasMany(SupplierOrg::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class)->where('status','正常');
    }

    public function brands()
    {
        return $this->hasMany(Brand::class)->where('status','正常')->where('is_last_stage',1);
    }

    public function customerSupplier()
    {
        return $this->hasMany(CustomerSupplier::class);
    }

    public function scopeSupplierProductName($q, $condition)
    {
        $status = '';
        if (array_get($condition, 'id')) {
            $exist  = CustomerSupplier::where('supplier_id', array_get($condition, 'id'))->where('customer_id', user()->id)->exists();
            if ($exist) {
                $status = 'all';
            }
        } else {
            $exist = true;
        }

        $q->when(!array_get($condition, 'mobile') && (user() instanceof Customer) && $exist, function ($q) use ($status) {
            $q->whereHas('CustomerSupplier', function ($q) use ($status) {
                $q->where('customer_id', user()->id);
                if ($status != 'all') {
                    $q->where('status', CustomerSupplier::STATUS_NORMAL);
                }
            });
        });

        if (array_get($condition, 'supplier_product_name')) {
            $q->where('info->company->gsmc', 'like', '%'.array_get($condition, 'supplier_product_name').'%');
            $q->orWhereHas('products', function ($q) use ($condition) {
                $q->whereHas('customerSupplier',function($q){
                    $q->where('customer_id',user()->id)->where('status',CustomerSupplier::STATUS_NORMAL);
                });
                $q->where('name', 'like', '%'.array_get($condition, 'supplier_product_name').'%');
                $q->where('status', Product::STATUS_ENABLED);
            });
        }

        return $q;
    }

    protected $allColumns = [
        'id',
        'name',
        'mobile',
        'password',
        'remember_token',
        'invitation_code', // 邀请码
        'info', // 基本信息
        'loan_info', // 贷款信息概要
        'created_at',
        'updated_at',
    ];
}
