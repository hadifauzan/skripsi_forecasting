<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $table = 'master_payment_methods';
    protected $primaryKey = 'payment_method_id';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'fee_percentage',
        'fee_amount'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'fee_percentage' => 'float',
        'fee_amount' => 'float'
    ];

    public function transactionPayments(): HasMany
    {
        return $this->hasMany(TransactionPayment::class, 'payment_method_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedFeeAttribute(): string
    {
        if ($this->fee_percentage > 0) {
            return $this->fee_percentage . '%';
        } elseif ($this->fee_amount > 0) {
            return 'Rp ' . number_format($this->fee_amount, 0, ',', '.');
        }
        return 'Gratis';
    }
}
