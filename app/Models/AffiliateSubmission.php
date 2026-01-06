<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AffiliateSubmission extends Model
{
    use SoftDeletes;

    protected $table = 'affiliate_submissions';
    protected $primaryKey = 'submission_id';

    protected $fillable = [
        'user_id',
        'item_id',
        'status',
        // Alamat pengiriman
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'city',
        'province',
        'postal_code',
        'address_notes',
        // Data pengiriman
        'shipping_courier',
        'tracking_number',
        'video_link',
        'approved_at',
        'shipped_at',
        'received_at',
        'video_submitted_at',
        'admin_notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'shipped_at' => 'datetime',
        'received_at' => 'datetime',
        'video_submitted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_RECEIVED = 'received';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    // Video submission deadline (14 days)
    const VIDEO_DEADLINE_DAYS = 14;

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(MasterItem::class, 'item_id', 'item_id');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    public function scopeReceived($query)
    {
        return $query->where('status', self::STATUS_RECEIVED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_RECEIVED)
            ->whereNotNull('received_at')
            ->where('received_at', '<', now()->subDays(self::VIDEO_DEADLINE_DAYS));
    }

    /**
     * Helper Methods
     */
    
    /**
     * Cek apakah user sudah punya pengajuan aktif
     */
    public static function hasActiveSubmission($userId)
    {
        return self::where('user_id', $userId)
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_APPROVED,
                self::STATUS_SHIPPED,
                self::STATUS_RECEIVED
            ])
            ->exists();
    }

    /**
     * Hitung sisa hari untuk upload video
     */
    public function getRemainingDays()
    {
        if (!$this->received_at || $this->status !== self::STATUS_RECEIVED) {
            return null;
        }

        $deadline = $this->received_at->addDays(self::VIDEO_DEADLINE_DAYS);
        $remaining = now()->diffInDays($deadline, false);

        return max(0, ceil($remaining));
    }

    /**
     * Cek apakah sudah melewati deadline
     */
    public function isOverdue()
    {
        if (!$this->received_at || $this->status !== self::STATUS_RECEIVED) {
            return false;
        }

        return now()->greaterThan($this->received_at->addDays(self::VIDEO_DEADLINE_DAYS));
    }

    /**
     * Get deadline date
     */
    public function getDeadlineDate()
    {
        if (!$this->received_at) {
            return null;
        }

        return $this->received_at->addDays(self::VIDEO_DEADLINE_DAYS);
    }

    /**
     * Status badge color helper
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_APPROVED => 'bg-blue-100 text-blue-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            self::STATUS_SHIPPED => 'bg-purple-100 text-purple-800',
            self::STATUS_RECEIVED => 'bg-orange-100 text-orange-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            self::STATUS_FAILED => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Status label helper
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_SHIPPED => 'Dalam Pengiriman',
            self::STATUS_RECEIVED => 'Barang Diterima',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_FAILED => 'Gagal',
            default => 'Unknown',
        };
    }

    /**
     * Update status methods
     */
    public function approve($adminNotes = null)
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
            'admin_notes' => $adminNotes,
        ]);
    }

    public function reject($adminNotes)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'admin_notes' => $adminNotes,
        ]);
    }

    public function ship($courier, $trackingNumber)
    {
        $this->update([
            'status' => self::STATUS_SHIPPED,
            'shipping_courier' => $courier,
            'tracking_number' => $trackingNumber,
            'shipped_at' => now(),
        ]);
    }

    public function markAsReceived()
    {
        $this->update([
            'status' => self::STATUS_RECEIVED,
            'received_at' => now(),
        ]);
    }

    public function submitVideo($videoLink)
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'video_link' => $videoLink,
            'video_submitted_at' => now(),
        ]);
    }

    public function markAsFailed()
    {
        $this->update([
            'status' => self::STATUS_FAILED,
        ]);
    }
}
