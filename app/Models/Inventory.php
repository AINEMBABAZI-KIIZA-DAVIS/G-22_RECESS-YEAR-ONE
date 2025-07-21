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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'reserved_quantity',
        'available_quantity',
        'unit_cost',
        'total_cost',
        'manufacture_date',
        'expiry_date',
        'location',
        'shelf',
        'bin',
        'lot_number',
        'serial_number',
        'reference_type',
        'reference_id',
        'reorder_level',
        'reorder_quantity',
        'minimum_quantity',
        'maximum_quantity',
        'notes',
        'is_active',
        'last_updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'float',
        'reserved_quantity' => 'float',
        'available_quantity' => 'float',
        'unit_cost' => 'float',
        'total_cost' => 'float',
        'reorder_level' => 'float',
        'reorder_quantity' => 'float',
        'minimum_quantity' => 'float',
        'maximum_quantity' => 'float',
        'manufacture_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
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
