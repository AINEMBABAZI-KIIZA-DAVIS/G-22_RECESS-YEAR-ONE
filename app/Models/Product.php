<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'imageUrl',
        'category',
        'description',
        'price',
        'quantity_in_stock',
        'low_stock_threshold',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
