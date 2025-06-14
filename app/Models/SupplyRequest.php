<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_name',
        'quantity',
        'notes',
        'status',
        'confirmed_at',
        'fulfilled_at',
        'manufacturer_notes',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'fulfilled_at' => 'datetime',
    ];

    /**
     * Get the user (supplier) that made the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
