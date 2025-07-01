<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'quantity_received',
        'unit_price',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'float',
        'quantity_received' => 'float',
        'unit_price' => 'float',
        'tax_rate' => 'float',
        'tax_amount' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
    ];

    /**
     * Get the purchase order that owns the item.
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Get the product associated with the item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the total amount for the item.
     *
     * @return $this
     */
    public function calculateTotal()
    {
        $subtotal = $this->quantity * $this->unit_price;
        $this->tax_amount = $subtotal * ($this->tax_rate / 100);
        $this->total_amount = $subtotal + $this->tax_amount - $this->discount_amount;

        return $this;
    }

    /**
     * Get the remaining quantity to be received.
     *
     * @return float
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->quantity - $this->quantity_received;
    }

    /**
     * Check if the item is fully received.
     *
     * @return bool
     */
    public function isFullyReceived()
    {
        return $this->quantity_received >= $this->quantity;
    }

    /**
     * Check if the item is partially received.
     *
     * @return bool
     */
    public function isPartiallyReceived()
    {
        return $this->quantity_received > 0 && !$this->isFullyReceived();
    }

    /**
     * Check if the item is not received at all.
     *
     * @return bool
     */
    public function isNotReceived()
    {
        return $this->quantity_received == 0;
    }
}
