<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'customer_id',
        'transaction_sales_id',
        'user_id',
        'rating',
        'comment',
        'is_featured',
        'is_verified'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean'
    ];

    // Relationships
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function masterCustomer(): BelongsTo
    {
        return $this->belongsTo(MasterCustomers::class, 'customer_id', 'customer_id');
    }

    public function transactionSales(): BelongsTo
    {
        return $this->belongsTo(TransactionSales::class, 'transaction_sales_id', 'transaction_sales_id');
    }

    // Accessors
    public function getStarRatingAttribute(): string
    {
        return str_repeat('⭐', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getRatingTextAttribute(): string
    {
        return match($this->rating) {
            1 => 'Sangat Buruk',
            2 => 'Buruk',
            3 => 'Biasa',
            4 => 'Baik',
            5 => 'Sangat Baik',
            default => 'Tidak Ada Rating'
        };
    }

    // Scopes
    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('reviewed_at', '>=', now()->subDays($days));
    }
}
