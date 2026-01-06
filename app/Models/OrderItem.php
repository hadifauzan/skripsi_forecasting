<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'master_item_id',
        'item_name',
        'item_description',
        'unit_price',
        'quantity',
        'total_price'
    ];

    protected $casts = [
        'unit_price' => 'float',
        'quantity' => 'integer',
        'total_price' => 'float'
    ];

    /**
     * Relationship dengan Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship dengan MasterItem
     */
    public function masterItem(): BelongsTo
    {
        return $this->belongsTo(MasterItem::class, 'master_item_id', 'item_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Accessor untuk formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }

    /**
     * Accessor untuk formatted total
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    // Helper methods
    public function hasReviewByCustomer($customerId): bool
    {
        return $this->reviews()->where('customer_id', $customerId)->exists();
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute(): int
    {
        return $this->reviews()->count();
    }
}
