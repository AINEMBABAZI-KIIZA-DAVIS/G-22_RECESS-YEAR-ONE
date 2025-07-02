<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sku',
        'name',
        'category',
        'description',
        'price',
        'cost_price',
        'sale_price',
        'quantity_in_stock',
        'low_stock_threshold',
        'reorder_quantity',
        'image_url',
        'barcode',
        'weight',
        'weight_unit',
        'dimensions',
        'is_active',
        'is_featured',
        'tax_rate',
        'supplier_id',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'cost_price' => 'float',
        'sale_price' => 'float',
        'quantity_in_stock' => 'float',
        'low_stock_threshold' => 'float',
        'reorder_quantity' => 'float',
        'weight' => 'float',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'tax_rate' => 'float',
    ];
    
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'low_stock_threshold' => 5,
        'is_active' => true,
        'is_featured' => false,
        'tax_rate' => 0.0,
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = static::generateSku($product->name);
            }
        });
    }

    /**
     * Generate a unique SKU for the product.
     *
     * @param  string  $name
     * @return string
     */
    protected static function generateSku($name)
    {
        $sku = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));
        $random = strtoupper(Str::random(3));
        $timestamp = time();
        
        return "{$sku}-{$random}-{$timestamp}";
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the purchase order items for the product.
     */
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Get the inventories for the product.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the production batches for the product.
     */
    public function productionBatches()
    {
        return $this->hasMany(ProductionBatch::class);
    }

    /**
     * Get the supplier of the product.
     */
    public function supplier()
    {
        return $this->belongsTo(Vendor::class, 'supplier_id');
    }

    /**
     * Scope a query to only include active products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include low stock products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock($query)
    {
        return $query->where('quantity_in_stock', '<=', $this->low_stock_threshold);
    }

    /**
     * Check if the product is low in stock.
     *
     * @return bool
     */
    public function isLowStock()
    {
        return $this->quantity_in_stock <= $this->low_stock_threshold;
    }

    /**
     * Update the product's stock quantity.
     *
     * @param  float  $quantity
     * @param  string  $action  'add' or 'subtract'
     * @return void
     */
    public function updateStock($quantity, $action = 'add')
    {
        if ($action === 'add') {
            $this->quantity_in_stock += $quantity;
        } else {
            $this->quantity_in_stock = max(0, $this->quantity_in_stock - $quantity);
        }
        
        $this->save();
    }

    /**
     * Get the product's current stock status.
     *
     * @return string
     */
    public function getStockStatusAttribute()
    {
        if ($this->quantity_in_stock <= 0) {
            return 'out_of_stock';
        }
        
        if ($this->isLowStock()) {
            return 'low_stock';
        }
        
        return 'in_stock';
    }

    /**
     * Get the product's profit margin percentage.
     *
     * @return float|null
     */
    public function getProfitMarginAttribute()
    {
        if ($this->cost_price <= 0 || $this->price <= 0) {
            return null;
        }
        
        return (($this->price - $this->cost_price) / $this->price) * 100;
    }
}
