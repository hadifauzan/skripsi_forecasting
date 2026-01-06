<?php

namespace App\Console\Commands;

use App\Services\OtpService;
use Illuminate\Console\Command;

class CleanupExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup {--days=7 : Number of days to keep expired OTPs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired OTPs older than specified days';

    protected OtpService $otpService;

    /**
     * Create a new command instance.
     */
    public function __construct(OtpService $otpService)
    {
        parent::__construct();
        $this->otpService = $otpService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        
        $this->info("Cleaning up expired OTPs older than {$days} days...");
        
        $deleted = $this->otpService->cleanupExpiredOtps($days);
        
        $this->info("Successfully deleted {$deleted} expired OTP records.");
        
        return Command::SUCCESS;
    }
}
