<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ProductionBatch extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'batch_number',
        'production_date',
        'expiry_date',
        'quantity',
        'good_quantity',
        'waste_quantity',
        'status',
        'notes',
        'produced_by',
        'production_cost',
        'labor_cost',
        'material_cost',
        'other_costs',
        'total_cost',
        'cost_per_unit',
        'quality_check_passed',
        'quality_notes',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'production_date' => 'date',
        'expiry_date' => 'date',
        'production_cost' => 'float',
        'labor_cost' => 'float',
        'material_cost' => 'float',
        'other_costs' => 'float',
        'total_cost' => 'float',
        'cost_per_unit' => 'float',
        'quality_check_passed' => 'boolean',
        'approved_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'production_efficiency',
        'is_expired',
        'days_until_expiry',
    ];

    /**
     * Get the product that owns the production batch.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who produced the batch.
     */
    public function producer()
    {
        return $this->belongsTo(User::class, 'produced_by');
    }

    /**
     * Get the user who approved the batch.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the batch's production efficiency percentage.
     *
     * @return float
     */
    public function getProductionEfficiencyAttribute()
    {
        if ($this->quantity <= 0) {
            return 0;
        }
        
        return round(($this->good_quantity / $this->quantity) * 100, 2);
    }

    /**
     * Check if the batch is expired.
     *
     * @return bool
     */
    public function getIsExpiredAttribute()
    {
        if (is_null($this->expiry_date)) {
            return false;
        }
        
        $expiryDate = $this->expiry_date;
        if (is_string($expiryDate)) {
            $expiryDate = Carbon::parse($expiryDate);
        }
        
        return $expiryDate->isPast();
    }

    /**
     * Get the number of days until the batch expires.
     *
     * @return int|null
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (is_null($this->expiry_date)) {
            return null;
        }
        
        $expiryDate = $this->expiry_date;
        if (is_string($expiryDate)) {
            $expiryDate = Carbon::parse($expiryDate);
        }
        
        return Carbon::now()->diffInDays($expiryDate, false);
    }

    /**
     * Scope a query to only include batches with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include batches that are expired or expiring soon.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>=', now());
    }

    /**
     * Scope a query to only include batches that need quality check.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeedsQualityCheck($query)
    {
        return $query->where('status', 'completed')
            ->whereNull('quality_check_passed');
    }

    /**
     * Scope a query to only include batches that need approval.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeedsApproval($query)
    {
        return $query->where('status', 'completed')
            ->where('quality_check_passed', true)
            ->whereNull('approved_at');
    }
}
