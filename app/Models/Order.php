<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'contact_number',
        'shipping_address',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'total_amount',
    ];

    protected $dates = ['deleted_at'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
