<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'notes',
        'rating',
        'is_active',
        'tax_id',
        'website',
        'payment_terms',
        'account_number',
        'bank_name',
        'bank_branch',
        'bank_address',
        'swift_code',
        'iban',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rating' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Get the products associated with the vendor.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the purchase orders for the vendor.
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Get the vendor's performance rating.
     */
    public function getPerformanceRatingAttribute()
    {
        // Calculate performance based on on-time delivery, quality, etc.
        return $this->rating;
    }

    /**
     * Scope a query to only include active vendors.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
