<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity_on_hand',
        'quantity_allocated',
        'quantity_available',
        'reorder_point',
        'reorder_quantity',
        'last_counted_at',
        'last_received_at',
        'last_sold_at',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity_on_hand' => 'float',
        'quantity_allocated' => 'float',
        'quantity_available' => 'float',
        'reorder_point' => 'float',
        'reorder_quantity' => 'float',
        'last_counted_at' => 'datetime',
        'last_received_at' => 'datetime',
        'last_sold_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_below_reorder_point',
    ];

    /**
     * Get the product that owns the inventory record.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the warehouse that owns the inventory record.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Scope a query to only include active inventory.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inventory that needs reordering.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeedsReorder($query)
    {
        return $query->where('quantity_available', '<=', $this->reorder_point);
    }

    /**
     * Get the is_below_reorder_point attribute.
     *
     * @return bool
     */
    public function getIsBelowReorderPointAttribute()
    {
        return $this->quantity_available <= $this->reorder_point;
    }

    /**
     * Update the quantity on hand and recalculate available quantity.
     *
     * @param  float  $quantity
     * @param  string  $action  'add' or 'subtract'
     * @return void
     */
    public function updateQuantity($quantity, $action = 'add')
    {
        if ($action === 'add') {
            $this->quantity_on_hand += $quantity;
        } else {
            $this->quantity_on_hand = max(0, $this->quantity_on_hand - $quantity);
        }

        $this->recalculateAvailableQuantity();
        $this->save();
    }

    /**
     * Recalculate the available quantity.
     *
     * @return void
     */
    public function recalculateAvailableQuantity()
    {
        $this->quantity_available = $this->quantity_on_hand - $this->quantity_allocated;
    }

    /**
     * Allocate quantity from available inventory.
     *
     * @param  float  $quantity
     * @return bool
     */
    public function allocate($quantity)
    {
        if ($this->quantity_available < $quantity) {
            return false;
        }

        $this->quantity_allocated += $quantity;
        $this->recalculateAvailableQuantity();
        $this->save();

        return true;
    }

    /**
     * Release allocated quantity back to available inventory.
     *
     * @param  float  $quantity
     * @return bool
     */
    public function release($quantity)
    {
        if ($this->quantity_allocated < $quantity) {
            return false;
        }

        $this->quantity_allocated -= $quantity;
        $this->recalculateAvailableQuantity();
        $this->save();

        return true;
    }

    /**
     * Mark inventory as counted.
     *
     * @param  float  $quantity
     * @return void
     */
    public function markAsCounted($quantity)
    {
        $this->quantity_on_hand = $quantity;
        $this->last_counted_at = now();
        $this->recalculateAvailableQuantity();
        $this->save();
    }
}
