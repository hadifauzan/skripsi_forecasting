<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionSales extends Model
{
    protected $table = 'transaction_sales';
    protected $primaryKey = 'transaction_sales_id';

    protected $fillable = [
        'branch_id',
        'user_id',
        'customer_id',
        'sales_type_id',
        'expedition_id',
        'number',
        'date',
        'notes',
        'subtotal',
        'discount_amount',
        'discount_percentage',
        'total_amount',
        'whatsapp',
        'shipping_address',
        'shipping_cost',
        'shipping_courier',
        'shipping_service',
        'shipping_etd',
        'tracking_number',
        'shipping_status',
        'shipping_notes',
        'shipping_city_id',
        'shipping_province_id'
    ];

    protected $casts = [
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'discount_percentage' => 'float',
        'total_amount' => 'float',
        'shipping_cost' => 'float',
        'date' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(MasterCustomers::class, 'customer_id', 'customer_id');
    }

    public function transactionSalesDetails(): HasMany
    {
        return $this->hasMany(TransactionSalesDetails::class, 'transaction_sales_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(TransactionPayment::class, 'transaction_sales_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'transaction_sales_id', 'transaction_sales_id');
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedShippingCostAttribute(): string
    {
        return 'Rp ' . number_format($this->shipping_cost ?? 0, 0, ',', '.');
    }

    public function getGrandTotalAttribute(): float
    {
        return $this->total_amount + ($this->shipping_cost ?? 0);
    }

    public function getFormattedGrandTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    public function getPaymentStatusAttribute(): string
    {
        $totalPaid = $this->payments()->where('payment_status', 'paid')->sum('amount');
        
        if ($totalPaid == 0) {
            return 'belum-bayar';
        } elseif ($totalPaid < $this->grand_total) {
            return 'sebagian';
        } else {
            return 'lunas';
        }
    }

    public function getShippingStatusAttribute(): string
    {
        // Menggunakan shipping_status kolom jika ada, fallback ke logic lama
        if (!empty($this->attributes['shipping_status'])) {
            return $this->attributes['shipping_status'];
        }
        
        // Berdasarkan tracking number dan status lainnya (logic lama)
        if (empty($this->tracking_number)) {
            if ($this->payment_status === 'lunas') {
                return 'processing';
            } else {
                return 'pending';
            }
        } else {
            return 'shipped';
        }
    }

    public function getShippingStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Terkirim',
            'cancelled' => 'Dibatalkan'
        ];

        return $labels[$this->shipping_status] ?? 'Tidak Diketahui';
    }

    public function getShippingStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800'
        ];

        return $colors[$this->shipping_status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getOverallStatusAttribute(): string
    {
        // Check if order is cancelled first
        if ($this->shipping_status === 'cancelled') {
            return 'dibatalkan';
        }
        
        // Check shipping status set by admin
        if ($this->shipping_status === 'delivered') {
            return 'selesai';
        } elseif ($this->shipping_status === 'shipped' || $this->shipping_status === 'dikirim') {
            return 'dikirim';
        } elseif ($this->shipping_status === 'processing' || $this->shipping_status === 'sedang-dikemas') {
            return 'sedang-dikemas';
        }
        
        // Fallback to payment status if shipping status not set
        if ($this->payment_status === 'belum-bayar') {
            return 'belum-bayar';
        } elseif ($this->payment_status === 'lunas' && empty($this->tracking_number)) {
            return 'sedang-dikemas';
        } elseif (!empty($this->tracking_number)) {
            return 'dikirim';
        } else {
            return 'selesai';
        }
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->overall_status) {
            'belum-bayar' => 'Belum Bayar',
            'sedang-dikemas' => 'Sedang Dikemas',
            'dikirim' => 'Sedang Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->overall_status) {
            'belum-bayar' => 'bg-yellow-100 text-yellow-800',
            'sedang-dikemas' => 'bg-blue-100 text-blue-800',
            'dikirim' => 'bg-purple-100 text-purple-800',
            'selesai' => 'bg-green-100 text-green-800',
            'dibatalkan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Check if the order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        // Order cannot be cancelled if it has one of these shipping statuses:
        $nonCancellableShippingStatuses = [
            'shipped',      // Sudah dikirim
            'delivered',    // Sudah diterima
            'dikirim',      // Sedang dikirim (dalam bahasa Indonesia)
            'cancelled',    // Sudah dibatalkan
            'on_delivery',  // Sedang dalam pengiriman
            'in_transit'    // Dalam perjalanan
        ];
        
        // Order cannot be cancelled if it has one of these overall statuses:
        $nonCancellableOverallStatuses = [
            'dikirim',          // Sedang dikirim
            'selesai',          // Sudah selesai
            'dibatalkan',       // Sudah dibatalkan
            'delivered',        // Sudah diterima
            'completed'         // Completed
        ];
        
        // Check overall status first (more reliable)
        $overallStatus = $this->overall_status;
        if (!empty($overallStatus) && in_array(strtolower($overallStatus), array_map('strtolower', $nonCancellableOverallStatuses))) {
            return false;
        }
        
        // Check shipping status
        if (!empty($this->shipping_status) && in_array(strtolower($this->shipping_status), array_map('strtolower', $nonCancellableShippingStatuses))) {
            return false;
        }
        
        // If both overall_status and shipping_status are null/empty, or contain cancellable statuses, allow cancellation
        return true;
    }

    /**
     * Cancel the order
     */
    public function cancelOrder(): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->update([
            'shipping_status' => 'cancelled',
            'shipping_notes' => ($this->shipping_notes ? $this->shipping_notes . "\n" : '') . 
                               'Pesanan dibatalkan oleh customer pada ' . now()->format('d-m-Y H:i:s')
        ]);

        return true;
    }
}
