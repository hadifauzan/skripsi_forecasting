<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OTP Configuration
    |--------------------------------------------------------------------------
    |
    | Configure OTP generation and validation settings
    |
    */

    // OTP expiry time in minutes
    'expiry_minutes' => env('OTP_EXPIRY_MINUTES', 5),

    // OTP length (number of digits)
    'length' => env('OTP_LENGTH', 6),

    // Cooldown period between OTP requests (in seconds)
    'cooldown_seconds' => env('OTP_COOLDOWN_SECONDS', 60),

    // Auto cleanup expired OTPs older than X days
    'cleanup_after_days' => env('OTP_CLEANUP_DAYS', 7),

    // Maximum OTP attempts before lockout
    'max_attempts' => env('OTP_MAX_ATTEMPTS', 5),

    // Lockout duration in minutes after max attempts
    'lockout_minutes' => env('OTP_LOCKOUT_MINUTES', 15),
];
