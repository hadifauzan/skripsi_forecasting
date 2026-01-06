<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * OTP expiration time in minutes
     */
    protected int $expiryMinutes;

    /**
     * OTP length
     */
    protected int $otpLength;

    public function __construct()
    {
        $this->expiryMinutes = config('otp.expiry_minutes', 5);
        $this->otpLength = config('otp.length', 6);
    }

    /**
     * Generate a random numeric OTP
     */
    public function generateOtpCode(): string
    {
        $min = (int) str_pad('1', $this->otpLength, '0', STR_PAD_RIGHT);
        $max = (int) str_pad('9', $this->otpLength, '9', STR_PAD_RIGHT);
        
        return (string) random_int($min, $max);
    }

    /**
     * Create and store OTP for a user
     * 
     * @param User $user
     * @param int|null $expiryMinutes Override default expiry
     * @return array{otp_code: string, expires_at: Carbon, otp_id: int}
     */
    public function createOtp(User $user, ?int $expiryMinutes = null): array
    {
        // Invalidate all previous unused OTPs for this user
        $this->invalidatePreviousOtps($user);

        // Generate OTP code
        $otpCode = $this->generateOtpCode();
        $expiryMinutes = $expiryMinutes ?? $this->expiryMinutes;
        $expiresAt = now()->addMinutes($expiryMinutes);

        // Create OTP record (hash will be auto-applied by mutator)
        $userOtp = UserOtp::create([
            'user_id' => $user->user_id,
            'otp_hash' => $otpCode, // Will be hashed by mutator
            'expires_at' => $expiresAt,
        ]);

        Log::info('OTP created', [
            'user_id' => $user->user_id,
            'email' => $user->email,
            'expires_at' => $expiresAt->toDateTimeString(),
        ]);

        return [
            'otp_code' => $otpCode, // Return plaintext for sending via email/SMS
            'expires_at' => $expiresAt,
            'otp_id' => $userOtp->id,
        ];
    }

    /**
     * Verify OTP code for a user
     * 
     * @param User $user
     * @param string $otpCode
     * @return array{success: bool, message: string, otp: ?UserOtp}
     */
    public function verifyOtp(User $user, string $otpCode): array
    {
        // Get latest valid OTP
        $userOtp = UserOtp::where('user_id', $user->user_id)
            ->valid()
            ->latest()
            ->first();

        if (!$userOtp) {
            Log::warning('No valid OTP found', [
                'user_id' => $user->user_id,
                'email' => $user->email,
            ]);

            return [
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa.',
                'otp' => null,
            ];
        }

        // Verify the OTP code
        if (!$userOtp->verify($otpCode)) {
            Log::warning('OTP verification failed - incorrect code', [
                'user_id' => $user->user_id,
                'email' => $user->email,
            ]);

            return [
                'success' => false,
                'message' => 'Kode OTP salah.',
                'otp' => null,
            ];
        }

        // Mark OTP as used
        $userOtp->markAsUsed();

        Log::info('OTP verified successfully', [
            'user_id' => $user->user_id,
            'email' => $user->email,
            'otp_id' => $userOtp->id,
        ]);

        return [
            'success' => true,
            'message' => 'Kode OTP berhasil diverifikasi.',
            'otp' => $userOtp,
        ];
    }

    /**
     * Invalidate all previous unused OTPs for a user
     * This is called when generating a new OTP to prevent old codes from working
     */
    public function invalidatePreviousOtps(User $user): void
    {
        UserOtp::where('user_id', $user->user_id)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);
    }

    /**
     * Clean up expired OTPs (can be run as scheduled task)
     * 
     * @param int $daysOld Delete OTPs older than X days
     * @return int Number of deleted records
     */
    public function cleanupExpiredOtps(int $daysOld = 7): int
    {
        $deleted = UserOtp::where('expires_at', '<', now()->subDays($daysOld))
            ->delete();

        Log::info('Expired OTPs cleaned up', ['deleted_count' => $deleted]);

        return $deleted;
    }

    /**
     * Check if user has a valid OTP
     */
    public function hasValidOtp(User $user): bool
    {
        return UserOtp::where('user_id', $user->user_id)
            ->valid()
            ->exists();
    }

    /**
     * Get remaining time for latest valid OTP
     * 
     * @return int|null Remaining seconds, null if no valid OTP
     */
    public function getRemainingTime(User $user): ?int
    {
        $userOtp = UserOtp::where('user_id', $user->user_id)
            ->valid()
            ->latest()
            ->first();

        if (!$userOtp) {
            return null;
        }

        return $userOtp->expires_at->diffInSeconds(now(), false);
    }

    /**
     * Resend OTP (create new one)
     * 
     * @param User $user
     * @param int|null $cooldownSeconds Minimum seconds between OTP requests
     * @return array{success: bool, message: string, otp_code?: string, expires_at?: Carbon, cooldown_remaining?: int}
     */
    public function resendOtp(User $user, ?int $cooldownSeconds = 60): array
    {
        // Check if there's a recent OTP (within cooldown period)
        $recentOtp = UserOtp::where('user_id', $user->user_id)
            ->where('created_at', '>', now()->subSeconds($cooldownSeconds))
            ->latest()
            ->first();

        if ($recentOtp) {
            $remainingSeconds = $cooldownSeconds - now()->diffInSeconds($recentOtp->created_at);
            
            return [
                'success' => false,
                'message' => "Mohon tunggu {$remainingSeconds} detik sebelum meminta OTP baru.",
                'cooldown_remaining' => $remainingSeconds,
            ];
        }

        // Create new OTP
        $otpData = $this->createOtp($user);

        return [
            'success' => true,
            'message' => 'Kode OTP baru telah dikirim.',
            'otp_code' => $otpData['otp_code'],
            'expires_at' => $otpData['expires_at'],
        ];
    }
}
