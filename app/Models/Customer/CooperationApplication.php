<?php

namespace App\Models\Customer;

use App\Models\BaseModel;

/**
 * App\Models\Customer\CooperationApplication
 *
 * @property int $id
 * @property int $sender_id 申请合作人id
 * @property string $sender_type 申请合作人类型
 * @property int $receiver_id 接受合作人id
 * @property string $receiver_type 接受合作人类型
 * @property \Illuminate\Support\Carbon $rejected_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CooperationApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CooperationApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CooperationApplication whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CooperationApplication whereReceiverType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CooperationApplication whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CooperationApplication whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CooperationApplication whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CooperationApplication whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $accepted_at 合作关系建立时间
 * @property-read \App\Models\Customer\CustomerSupplier $customerSupplier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Customer\CooperationApplication whereAcceptedAt($value)
 */
class CooperationApplication extends BaseModel
{
    protected $fillable = [
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'rejected_at',
        'accepted_at',
    ];

    protected $indexs = [
        '*',
    ];

    protected $dates = [
        'rejected_at',
        'accepted_at',
    ];

    public function customerSupplier()
    {
        return $this->hasOne(CustomerSupplier::class);
    }

    protected $allColumns = [
        'id',
        'sender_id', // 申请合作人id
        'sender_type', // 申请合作人类型
        'receiver_id', // 接受合作人id
        'receiver_type', // 接受合作人类型
        'rejected_at',
        'created_at',
        'updated_at',
        'accepted_at', // 合作关系建立时间
    ];
}
