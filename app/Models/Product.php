<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'quantity_in_stock',
        'tax_rate',
        'is_active',
        'is_featured',
        'low_stock_threshold',
        'category',
        'image',
    ];

    protected $dates = ['deleted_at'];
}
