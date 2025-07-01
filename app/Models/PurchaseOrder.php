<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'po_number',
        'vendor_id',
        'order_date',
        'expected_delivery_date',
        'delivery_date',
        'status',
        'notes',
        'terms_and_conditions',
        'shipping_method',
        'shipping_terms',
        'shipping_address',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'discount_amount',
        'total_amount',
        'currency',
        'approval_notes',
        'receiving_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'order_date' => 'datetime',
        'expected_delivery_date' => 'datetime',
        'delivery_date' => 'datetime',
        'subtotal' => 'float',
        'tax_amount' => 'float',
        'shipping_cost' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
        'approved_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_overdue',
        'days_until_due',
        'status_label',
    ];

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PARTIALLY_RECEIVED = 'partially_received';
    const STATUS_RECEIVED = 'received';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the vendor that owns the purchase order.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the items for the purchase order.
     */
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Get the user who created the purchase order.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the purchase order.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who approved the purchase order.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who received the purchase order.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Check if the purchase order is overdue.
     *
     * @return bool
     */
    public function getIsOverdueAttribute()
    {
        if (in_array($this->status, [self::STATUS_RECEIVED, self::STATUS_CANCELLED])) {
            return false;
        }

        if ($this->expected_delivery_date) {
            $deliveryDate = $this->expected_delivery_date;
            if (is_string($deliveryDate)) {
                $deliveryDate = Carbon::parse($deliveryDate);
            }
            
            return $deliveryDate->isPast();
        }

        return false;
    }

    /**
     * Get the number of days until the purchase order is due.
     *
     * @return int|null
     */
    public function getDaysUntilDueAttribute()
    {
        if (!$this->expected_delivery_date) {
            return null;
        }

        $deliveryDate = $this->expected_delivery_date;
        if (is_string($deliveryDate)) {
            $deliveryDate = Carbon::parse($deliveryDate);
        }
        
        return Carbon::now()->diffInDays($deliveryDate, false);
    }

    /**
     * Get the human-readable status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SENT => 'Sent',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_RECEIVED => 'Received',
            self::STATUS_CANCELLED => 'Cancelled',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Scope a query to only include purchase orders with a specific status.
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
     * Scope a query to only include overdue purchase orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotIn('status', [self::STATUS_RECEIVED, self::STATUS_CANCELLED])
            ->whereDate('expected_delivery_date', '<', now())
            ->whereNull('delivery_date');
    }

    /**
     * Scope a query to only include purchase orders that need approval.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeedsApproval($query)
    {
        return $query->where('status', self::STATUS_SENT)
            ->whereNull('approved_at');
    }

    /**
     * Scope a query to only include purchase orders that need receiving.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeedsReceiving($query)
    {
        return $query->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_PARTIALLY_RECEIVED])
            ->whereNull('received_at');
    }

    /**
     * Calculate and update the purchase order totals.
     *
     * @return $this
     */
    public function calculateTotals()
    {
        $subtotal = $this->items()->sum(DB::raw('quantity * unit_price'));
        $this->subtotal = $subtotal;
        $this->total_amount = $this->subtotal + $this->tax_amount + $this->shipping_cost - $this->discount_amount;
        $this->save();

        return $this;
    }

    /**
     * Mark the purchase order as sent.
     *
     * @param  string  $notes
     * @return $this
     */
    public function markAsSent($notes = null)
    {
        if ($this->status !== self::STATUS_DRAFT) {
            throw new \LogicException('Only draft purchase orders can be marked as sent.');
        }

        $this->status = self::STATUS_SENT;
        $this->notes = $notes ?: $this->notes;
        $this->save();

        return $this;
    }

    /**
     * Mark the purchase order as confirmed.
     *
     * @param  string  $notes
     * @return $this
     */
    public function markAsConfirmed($notes = null)
    {
        if ($this->status !== self::STATUS_SENT) {
            throw new \LogicException('Only sent purchase orders can be confirmed.');
        }

        $this->status = self::STATUS_CONFIRMED;
        $this->notes = $notes ?: $this->notes;
        $this->save();

        return $this;
    }

    /**
     * Mark the purchase order as received.
     *
     * @param  int  $userId
     * @param  string  $notes
     * @return $this
     */
    public function markAsReceived($userId, $notes = null)
    {
        if (!in_array($this->status, [self::STATUS_CONFIRMED, self::STATUS_PARTIALLY_RECEIVED])) {
            throw new \LogicException('Only confirmed or partially received purchase orders can be marked as received.');
        }

        $this->status = self::STATUS_RECEIVED;
        $this->received_by = $userId;
        $this->received_at = now();
        $this->delivery_date = now();
        $this->receiving_notes = $notes ?: $this->receiving_notes;
        $this->save();

        // Update inventory
        $this->items->each(function ($item) {
            if ($item->product) {
                $item->product->increment('quantity', $item->quantity_received);
            }
        });

        return $this;
    }

    /**
     * Cancel the purchase order.
     *
     * @param  string  $reason
     * @return $this
     */
    public function cancel($reason = null)
    {
        if (in_array($this->status, [self::STATUS_RECEIVED, self::STATUS_CANCELLED])) {
            throw new \LogicException('Received or already cancelled purchase orders cannot be cancelled.');
        }

        $this->status = self::STATUS_CANCELLED;
        $this->notes = $reason ?: $this->notes;
        $this->save();

        return $this;
    }
}
