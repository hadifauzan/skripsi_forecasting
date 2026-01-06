<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_type',
        'gross_amount',
        'transaction_status',
        'midtrans_response',
        'qr_code_url',
        'expired_at',
        'customer_name',
        'customer_email',
        'customer_phone'
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'expired_at' => 'datetime',
        'gross_amount' => 'decimal:2'
    ];

    /**
     * Relationship dengan Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}