<?php

namespace App\Models;

use App\Models\AbleTrait\ListTrait;
use Illuminate\Database\Eloquent\Model;
use MatrixLab\LaravelAdvancedSearch\AdvancedSearchTrait;
use MatrixLab\LaravelAdvancedSearch\WithAndSelectForGraphQLGeneratorTrait;

/**
 * App\Models\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    use ListTrait;
    use AdvancedSearchTrait;
    use WithAndSelectForGraphQLGeneratorTrait;
}
