<?php

namespace App\GraphQL\Queries;

use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use MatrixLab\LaravelAdvancedSearch\ConditionsGeneratorTrait;

class BaseQuery extends Query
{
    use ConditionsGeneratorTrait;
}
