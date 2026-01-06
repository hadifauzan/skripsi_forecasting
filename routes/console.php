<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule auto confirmation orders daily at configured time
Schedule::command('orders:auto-confirm')
    ->dailyAt(config('services.auto_confirmation.time', '09:00'))
    ->withoutOverlapping()
    ->runInBackground()
    ->onSuccess(function () {
        Log::info('Auto confirmation completed successfully');
    })
    ->onFailure(function () {
        Log::error('Auto confirmation failed');
    });
