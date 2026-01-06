<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoConfirmationLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'order_number',
        'action',
        'shipped_at',
        'action_at',
        'days_elapsed',
        'notes',
        'order_snapshot',
        'performed_by',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'action_at' => 'datetime',
        'order_snapshot' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function performer()
    {
        return $this->belongsTo(\App\Models\User::class, 'performed_by', 'user_id');
    }

    /**
     * Log new action
     */
    public static function logAction($order, $action, $performedBy = null, $notes = null)
    {
        $log = self::create([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'action' => $action,
            'shipped_at' => $order->shipped_at,
            'action_at' => now(),
            'days_elapsed' => $order->shipped_at ? $order->shipped_at->diffInDays(now()) : null,
            'notes' => $notes,
            'order_snapshot' => $order->toArray(),
            'performed_by' => $performedBy,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return $log;
    }
}
