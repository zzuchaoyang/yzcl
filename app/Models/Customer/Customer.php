<?php

namespace App\Models\Customer;

use App\GraphQL\Queries\Customer\CustomerListQuery;
use App\Models\Order\OrderProduct;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\AbleTrait\ListTrait;
use Illuminate\Notifications\Notifiable;
use MatrixLab\LaravelAdvancedSearch\AdvancedSearchTrait;
use MatrixLab\LaravelAdvancedSearch\WithAndSelectForGraphQLGeneratorTrait;

/**
 * App\Models\Customer\Customer
 *
 * @property string password
 * @property int $id
 * @property string|null $name 客户姓名
 * @property string $mobile 客户手机号
 * @property string|null $remember_token
 * @property string|null $invitation_code 邀请码
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereInvitationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property array|null $store_info 店铺信息
 * @property string|null $avatar 头像
 * @property int|null $gender 性别
 * @property string|null $birthed_at 出生日期
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Customer\CustomerSupplier[] $customerSupplier
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereBirthedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\Customer whereStoreInfo($value)
 */
class Customer extends Authenticatable implements JWTSubject
{
    use ListTrait;
    use Notifiable;
    use AdvancedSearchTrait;
    use WithAndSelectForGraphQLGeneratorTrait;

    protected $fillable = [
        'name',
        'mobile',
        'gender',
        'avatar',
        'store_info',
        'birthed_at',
        'invitation_code'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $indexs = [
        '*',
    ];

    protected $casts = [
        'store_info'      => 'json',
    ];

    public function customerSupplier()
    {
        return $this->hasMany(CustomerSupplier::class);
    }

    public static function scopeCustomerSupplier($q, $condition)
    {
        if (isset($condition['id'])) {
            $relation = CustomerSupplier::where('supplier_id',user()->id)->where('customer_id',$condition['id'])->exists();
        } else {
            $relation = true;
        }
        $customer = [];
        $exist    = false;
        if (isset($condition['mobile'])) {
            $customer = Customer::where('mobile',$condition['mobile'])->first();
            if ($customer){
                $exist = CustomerSupplier::where('supplier_id',user()->id)->where('customer_id',$customer->id)->exists();
            } else {
                $exist = false;
            }
        }
        if($relation) {
            if (isset($condition['status']) || isset($condition['supply_grade']) || !isset($condition['is_add']) || isset($condition['statics']) || (isset($condition['mobile']) && $exist)) {
                $q->whereHas('customerSupplier', function ($q) use ($condition,$customer,$exist) {
                    $q->where('supplier_id',user()->id);
                    if (isset($condition['mobile']) && $exist) {
                        $q->where('customer_id', $customer->id);
                    }
                    if (isset($condition['status'])) {
                        $q->where('status', $condition['status']);
                    }
                    if (isset($condition['supply_grade'])) {
                        $q->where('supply_grade', $condition['supply_grade']);
                    }
                    if (isset($condition['from_home']) && $condition['from_home']) {
                        $q->whereHas('cooperationApplication',function($q){
                            $q->where('sender_type','customer');
                        });
                    }
                });
            } else if (isset($condition['is_add'])) {
                if (!$condition['mobile']) {
                    $q->whereRaw('0 = 1');
                }
            }
        }

        return $q;
    }

    public static function scopeCustomerStatics($q, $args)
    {
        $customer_ids = OrderProduct::selectRaw('customer_id')
            ->when(array_get($args, 'start_at'), function ($q) use ($args) {
                $q->where('created_at', '>', carbon(array_get($args, 'start_at'))->startOfDay());
            })
            ->when(array_get($args, 'end_at'), function ($q) use ($args) {
                $q->where('created_at', '<=', carbon(array_get($args, 'end_at'))->endOfDay());
            })
            ->where(function ($q) use ($args) {
                $q->whereHas('customer', function ($q) use ($args) {
                    $q->when(array_get($args,'city_id'), function ($q)use($args) {
                        $city_id = [(int)array_get($args,'city_id')];
                        $ids = CustomerListQuery::getChildrenIds($city_id);
                        $ids = implode(',',$ids);
                        $q->whereRaw("`store_info` -> '$.\"area_id\"' in ($ids)");
                    });
                    if(array_get($args,'name')) {
                        $q->where('store_info->name','like','%'.array_get($args,'name').'%');
                    }
                    if (array_get($args,'mobile')) {
                        $q->where('mobile', array_get($args, 'mobile'));
                    }
                });
            })
            ->where(function ($q)use($args){
                $q->whereHas('customerSupplier',function($q)use($args){
                    $q->where('status',CustomerSupplier::STATUS_NORMAL);
                    $q->where('supplier_id',user()->id);
                    $q->when(array_get($args,'supply_grade'),function($q)use($args){
                        $q->where('supply_grade',array_get($args,'supply_grade'));
                    });
                });
            })
            ->where(function($q){
                $q->whereHas('order',function($q){
                    $q->where('supplier_id',user()->id);
                });
            })
            ->groupBy('customer_id')->pluck('customer_id')->toArray();

            $q->whereIn('id',$customer_ids);
    }

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

    protected $allColumns = [
        'id',
        'name', // 客户姓名
        'mobile', // 客户手机号
        'password',
        'remember_token',
        'store_info', // 店铺信息
        'avatar', // 头像
        'gender', // 性别
        'invitation_code', // 邀请码
        'birthed_at', // 出生日期
        'created_at',
        'updated_at',
    ];
}
