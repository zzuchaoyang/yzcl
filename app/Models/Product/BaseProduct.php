<?php

namespace App\Models\Product;


use App\Models\BaseModel;

class BaseProduct extends BaseModel
{
    protected $fillable = [
        'id',
        'name',
        'bar_code',
        'unit',
        'retail_price',
        'product_category_code',
    ];
    protected $casts = [
        'retail_price'   => 'decimal',
    ];
    protected $indexs = [
        '*',
    ];
}