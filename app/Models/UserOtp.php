<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserOtp extends Model
{
    protected $table = 'user_otps';

    protected $fillable = [
        'user_id',
        'otp_hash',
        'expires_at',
        'used_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Mutator: Auto-hash OTP when setting otp_hash
     * Usage: $userOtp->otp_hash = '123456'; // Will automatically hash
     */
    public function setOtpHashAttribute($value)
    {
        // Only hash if it's not already hashed
        if (!Hash::needsRehash($value) && strlen($value) === 60) {
            $this->attributes['otp_hash'] = $value;
        } else {
            $this->attributes['otp_hash'] = Hash::make($value);
        }
    }

    /**
     * Check if OTP is still valid (not expired and not used)
     */
    public function isValid(): bool
    {
        return $this->expires_at->isFuture() && is_null($this->used_at);
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if OTP has been used
     */
    public function isUsed(): bool
    {
        return !is_null($this->used_at);
    }

    /**
     * Mark OTP as used
     */
    public function markAsUsed(): void
    {
        $this->used_at = now();
        $this->save();
    }

    /**
     * Verify OTP code against hash
     */
    public function verify(string $code): bool
    {
        return Hash::check($code, $this->otp_hash);
    }

    /**
     * Scope: Get only valid OTPs
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
                    ->whereNull('used_at');
    }

    /**
     * Scope: Get only unused OTPs
     */
    public function scopeUnused($query)
    {
        return $query->whereNull('used_at');
    }

    /**
     * Scope: Get only unexpired OTPs
     */
    public function scopeUnexpired($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
