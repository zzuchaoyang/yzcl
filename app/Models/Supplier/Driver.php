<?php

namespace App\Models\Supplier;

use App\Models\AbleTrait\ListTrait;
use App\Models\GlobalScopes\SupplierScope;
use App\Models\Order\Order;
use App\Models\Supplier\Traits\Repositories\DriverRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MatrixLab\LaravelAdvancedSearch\AdvancedSearchTrait;
use MatrixLab\LaravelAdvancedSearch\WithAndSelectForGraphQLGeneratorTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\Supplier\Driver
 *
 * @property int $id
 * @property string|null $name 员工姓名
 * @property string $mobile
 * @property string $password
 * @property string|null $remember_token
 * @property int $supplier_org_id
 * @property int $status 状态：0 禁用 1 启用
 * @property string|null $id_card 证件号码
 * @property string|null $license 常用车牌
 * @property string|null $hiredate_on 入职日期
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver searchKeyword($key)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereHiredateOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereIdCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereSupplierOrgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $supplier_id
 * @property string|null $avatar 头像
 * @property int|null $gender 性别
 * @property string|null $birthed_at 出生日期
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order\Order[] $orders
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver bySupplierOrg($supplierOrgId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereBirthedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Supplier\Driver whereSupplierId($value)
 */
class Driver extends Authenticatable implements JWTSubject
{
    use ListTrait;
    use Notifiable;
    use AdvancedSearchTrait;
    use WithAndSelectForGraphQLGeneratorTrait;
    use DriverRepository;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mobile',
        'supplier_id',
        'supplier_org_id',
        'status',
        'gender',
        'avatar',
        'birthed_at',
        'id_card',
        'license',
        'hiredate_on',
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * 部门查询
     *
     * @param Builder $builder
     * @param         $supplierOrgId
     *
     * @return Builder
     */
    public function scopeBySupplierOrg(Builder $builder, $supplierOrgId)
    {
        $builder = $builder->where('supplier_id', user()->id);

        if(empty($supplierOrgId)){
            return $builder;
        }

        return $builder->whereIn('supplier_org_id', SupplierOrg::descendantsAndSelf($supplierOrgId)->pluck('id'));
    }

    protected $allColumns = [
        'id',
        'supplier_id',
        'name', // 员工姓名
        'mobile',
        'password',
        'remember_token',
        'supplier_org_id',
        'status', // 状态：0 禁用 1 启用
        'avatar', // 头像
        'gender', // 性别
        'id_card', // 证件号码
        'license', // 常用车牌
        'hiredate_on', // 入职日期
        'birthed_at', // 出生日期
        'created_at',
        'updated_at',
    ];
}
