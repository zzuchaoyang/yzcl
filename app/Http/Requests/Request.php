<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use MatrixLab\LaravelAdvancedSearch\ConditionsGeneratorTrait;

abstract class Request extends FormRequest
{
    use ConditionsGeneratorTrait;
}
