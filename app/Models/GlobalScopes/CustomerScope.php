<?php

namespace App\Models\GlobalScopes;

use App\Models\Customer\Customer;
use App\Models\Supplier\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CustomerScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        if (user() instanceof Customer) {
            $builder->where($model->getTable().'.customer_id', user()->id);
        }
    }
}
