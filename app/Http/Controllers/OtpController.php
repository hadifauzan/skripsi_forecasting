<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use App\Mail\AffiliateOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Generate and send OTP to user
     * 
     * POST /api/otp/generate
     * Body: { "email": "user@example.com" }
     */
    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:master_users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak ditemukan.'
                ], 404);
            }

            // Check user status
            if ($user->status !== 'Aktif') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
                ], 403);
            }

            // Generate OTP
            $otpData = $this->otpService->createOtp($user);

            // Send OTP via email
            Mail::to($user->email)->send(new AffiliateOtpMail(
                $user->name,
                $otpData['otp_code'],
                $otpData['expires_at']
            ));

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ke email Anda.',
                'data' => [
                    'email' => $user->email,
                    'expires_at' => $otpData['expires_at']->format('Y-m-d H:i:s'),
                    'expires_in_minutes' => $otpData['expires_at']->diffInMinutes(now()),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('OTP generation failed', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Verify OTP code
     * 
     * POST /api/otp/verify
     * Body: { "email": "user@example.com", "otp_code": "123456" }
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:master_users,email',
            'otp_code' => 'required|string|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak ditemukan.'
                ], 404);
            }

            // Verify OTP
            $result = $this->otpService->verifyOtp($user, $request->otp_code);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

            // Update email_verified_at if not verified yet
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
                $user->save();
            }

            // Generate auth token (if using API tokens)
            // $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'user_id' => $user->user_id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'email_verified' => true,
                    // 'token' => $token, // If using API tokens
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('OTP verification failed', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi OTP. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Resend OTP to user
     * 
     * POST /api/otp/resend
     * Body: { "email": "user@example.com" }
     */
    public function resend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:master_users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak ditemukan.'
                ], 404);
            }

            // Resend OTP with cooldown check
            $result = $this->otpService->resendOtp($user, 60); // 60 seconds cooldown

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'cooldown_remaining' => $result['cooldown_remaining'] ?? null
                ], 429); // Too Many Requests
            }

            // Send OTP via email
            Mail::to($user->email)->send(new AffiliateOtpMail(
                $user->name,
                $result['otp_code'],
                $result['expires_at']
            ));

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'email' => $user->email,
                    'expires_at' => $result['expires_at']->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('OTP resend failed', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim ulang OTP. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Check if user has valid OTP
     * 
     * POST /api/otp/check
     * Body: { "email": "user@example.com" }
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:master_users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.'
            ], 404);
        }

        $hasValidOtp = $this->otpService->hasValidOtp($user);
        $remainingSeconds = $this->otpService->getRemainingTime($user);

        return response()->json([
            'success' => true,
            'data' => [
                'has_valid_otp' => $hasValidOtp,
                'remaining_seconds' => $remainingSeconds,
                'remaining_minutes' => $remainingSeconds ? ceil($remainingSeconds / 60) : null,
            ]
        ], 200);
    }
}
