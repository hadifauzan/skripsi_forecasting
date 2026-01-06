<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionPayment extends Model
{
    protected $table = 'transaction_payments';
    protected $primaryKey = 'transaction_payment_id';

    protected $fillable = [
        'transaction_purchase_id',
        'transaction_sales_id',
        'payment_method_id',
        'amount',
        'received_amount',
        'change_amount',
        'payment_type',
        'payment_status',
        'payment_date',
        'notes'
    ];

    protected $casts = [
        'amount' => 'float',
        'received_amount' => 'float',
        'change_amount' => 'float',
        'payment_date' => 'datetime'
    ];

    public function transactionSales(): BelongsTo
    {
        return $this->belongsTo(TransactionSales::class, 'transaction_sales_id');
    }

    public function transactionPurchase(): BelongsTo
    {
        return $this->belongsTo(TransactionPurchase::class, 'transaction_purchase_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'Menunggu Pembayaran',
            'partial' => 'Dibayar Sebagian',
            'paid' => 'Lunas',
            'overpaid' => 'Kelebihan Bayar',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->payment_status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'partial' => 'bg-blue-100 text-blue-800',
            'paid' => 'bg-green-100 text-green-800',
            'overpaid' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}