<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'member_id',
        'status',
        'nif',
        'total_items',
        'shipping_cost',
        'total',
        'delivery_address',
        'cancel_reason',
        'date',
        'pdf_receipt',
    ];

    protected $casts = [
        'date' => 'date',
        'total_items' => 'float',
        'shipping_costs' => 'float',
        'total' => 'float',
    ];

    /**
     * The member (user) who placed the order.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    /**
     * The items associated with this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the formatted total amount.
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 2, ',', '.') . ' €';
    }

    /**
     * Get the formatted shipping cost.
     */
    public function getFormattedShippingCostAttribute(): string
    {
        return number_format($this->shipping_costs, 2, ',', '.') . ' €';
    }

    /**
     * Get the formatted subtotal (total_items).
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->total_items, 2, ',', '.') . ' €';
    }
}
