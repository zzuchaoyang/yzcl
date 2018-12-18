<?php

namespace App\Models\System;

use App\Models\AbleTrait\ListTrait;
use App\Models\System\Traits\Repositories\AuditRepository;
use MatrixLab\LaravelAdvancedSearch\AdvancedSearchTrait;
use OwenIt\Auditing\Models\Audit as BaseAudit;

/**
 * App\Models\System\Audit
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $auditable
 * @property-read \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection $audit_event_info
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Audit searchKeyword($key)
 * @mixin \Eloquent
 */
class Audit extends BaseAudit
{
    use ListTrait;
    use AdvancedSearchTrait;
    use AuditRepository;

    const EVENT_CREATED  = 'created';
    const EVENT_UPDATED  = 'updated';
    const EVENT_DELETED  = 'deleted';
    const EVENT_RESTORED = 'restored';

    const EVENTS = [
        self::EVENT_CREATED  => '创建',
        self::EVENT_UPDATED  => '更新',
        self::EVENT_DELETED  => '删除',
        self::EVENT_RESTORED => '访问',
    ];

    protected $appends = ['audit_event_info'];

    /**
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     * @throws \Exception
     */
    public function getAuditEventInfoAttribute()
    {
        return collect([
            'user'         => $this->user ? $this->user->name : '',
            'event_detail' => $this->generateEventDetail(),
            'created_at'   => $this->created_at->toDateTimeString(),
        ]);
    }
}
