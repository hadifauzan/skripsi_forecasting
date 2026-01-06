<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'notes',
        'order_date',
        'shipping_cost',
        'shipping_courier',
        'shipping_service',
        'shipping_etd',
        'tracking_number',
        'shipping_city_id',
        'shipping_province_id',
        'shipping_status',
        'shipping_notes',
        // Auto confirmation fields
        'shipped_at',
        'delivered_at',
        'confirmed_at',
        'auto_confirmed',
        'confirmation_type',
        'auto_confirmation_notes',
        'shipped_by',
        'admin_notes'
    ];

    protected $casts = [
        'total_amount' => 'float',
        'shipping_cost' => 'float',
        'order_date' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'auto_confirmed' => 'boolean'
    ];

    /**
     * Relationship dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relationship dengan OrderItem
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relationship dengan Payment
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Generate order number
     */
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())->count();
        $number = str_pad($lastOrder + 1, 4, '0', STR_PAD_LEFT);
        
        return "GL-{$date}-{$number}";
    }

    /**
     * Accessor untuk formatted total amount
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'shipped' => 'bg-indigo-100 text-indigo-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800'
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Auto Confirmation Methods
     */
    public function canBeAutoConfirmed(): bool
    {
        $days = config('services.auto_confirmation.days', 2);
        
        return $this->status === 'shipped' 
            && $this->shipped_at 
            && $this->shipped_at->diffInDays(now()) >= $days
            && !$this->auto_confirmed
            && is_null($this->delivered_at);
    }

    public function getRemainingAutoConfirmDays(): int
    {
        if (!$this->shipped_at || $this->status !== 'shipped') {
            return 0;
        }

        $days = config('services.auto_confirmation.days', 2);
        $elapsed = $this->shipped_at->diffInDays(now());
        
        return max(0, $days - $elapsed);
    }

    /**
     * Scope untuk orders yang perlu auto confirmation
     */
    public function scopeNeedsAutoConfirmation($query)
    {
        $days = config('services.auto_confirmation.days', 2);
        
        return $query->where('status', 'shipped')
            ->where('auto_confirmed', false)
            ->whereNotNull('shipped_at')
            ->whereNull('delivered_at')
            ->where('shipped_at', '<=', now()->subDays($days));
    }

    /**
     * Relationship dengan AutoConfirmationLog
     */
    public function autoConfirmationLogs(): HasMany
    {
        return $this->hasMany(AutoConfirmationLog::class);
    }

    /**
     * Relationship dengan RajaOngkir City
     */
    public function shippingCity()
    {
        return $this->belongsTo(RajaOngkirCity::class, 'shipping_city_id', 'city_id');
    }

    /**
     * Relationship dengan RajaOngkir Province  
     */
    public function shippingProvince()
    {
        return $this->belongsTo(RajaOngkirProvince::class, 'shipping_province_id', 'province_id');
    }
}
