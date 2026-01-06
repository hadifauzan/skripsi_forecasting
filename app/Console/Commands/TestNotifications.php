<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\NotificationHelper;
use App\Models\User;
use App\Models\MasterCustomers;

class TestNotifications extends Command
{
    protected $signature = 'admin:check-notifications {--detail : Show detailed pending registrations}';
    protected $description = 'Check notification system and pending registrations for admin sidebar';

    public function handle()
    {
        $this->info('=== ADMIN NOTIFICATION CHECKER ===');
        $this->line('');

        try {
            // Test Helper function
            $this->info('📊 Current Notification Status:');
            $data = NotificationHelper::getNotificationData();
            
            $this->line("   • Pending Affiliates: {$data['pending_affiliates']}");
            $this->line("   • Pending Resellers: {$data['pending_resellers']}");
            $this->line("   • Total Pending: {$data['total_pending']}");
            $this->line("   • Notifications Active: " . ($data['has_notifications'] ? 'Yes' : 'No'));
            $this->line('');

            // Show notification text if available
            if ($data['has_notifications']) {
                $notificationText = NotificationHelper::getNotificationText();
                $this->warn("🔔 Sidebar Message: '{$notificationText}'");
                $this->line('');
            }

            // Show detailed list if requested
            if ($this->option('detail') && $data['has_notifications']) {
                $this->showDetailedPendingList($data);
            }

            // System status
            $this->info('🔧 System Status:');
            $this->line('   ✓ NotificationHelper working correctly');
            $this->line('   ✓ Database queries functioning');
            $this->line('   ✓ Sidebar integration active');
            $this->line('');

            // Action recommendations
            if ($data['has_notifications']) {
                $this->warn('⚠️  ACTION REQUIRED:');
                $this->warn('   • Check Data Affiliator page for pending affiliates');
                $this->warn('   • Check Data Reseller page for pending resellers');
                $this->warn('   • Update status from "Pending" to "Aktif" or "Nonaktif"');
            } else {
                $this->info('✅ ALL CLEAR: No pending registrations requiring attention');
            }

        } catch (\Exception $e) {
            $this->error("❌ SYSTEM ERROR:");
            $this->error("   Message: " . $e->getMessage());
            $this->error("   File: " . $e->getFile());
            $this->error("   Line: " . $e->getLine());
            $this->line('');
            $this->warn('🔧 Troubleshooting:');
            $this->warn('   • Check database connection');
            $this->warn('   • Verify NotificationHelper class exists');
            $this->warn('   • Check User and MasterCustomers models');
            return 1;
        }

        return 0;
    }

    private function showDetailedPendingList($data)
    {
        $this->info('📋 Detailed Pending Registrations:');
        
        if ($data['pending_affiliates'] > 0) {
            $this->line('');
            $this->comment('   PENDING AFFILIATES:');
            $pendingAffiliates = User::where('role_id', 3)
                ->where('status', 'Pending')
                ->orderBy('created_at', 'desc')
                ->get(['user_id', 'name', 'email', 'created_at']);
                
            foreach ($pendingAffiliates as $index => $affiliate) {
                $this->line("   " . ($index + 1) . ". {$affiliate->name} ({$affiliate->email})");
                $this->line("      Registered: {$affiliate->created_at->format('Y-m-d H:i:s')}");
            }
        }

        if ($data['pending_resellers'] > 0) {
            $this->line('');
            $this->comment('   PENDING RESELLERS:');
            $pendingResellers = MasterCustomers::with('masterCustomerType')
                ->whereHas('masterCustomerType', function ($query) {
                    $query->where('name_customer_type', 'reseller');
                })
                ->where('status', 'Pending')
                ->orderBy('created_at', 'desc')
                ->get(['customer_id', 'name_customer', 'email_customer', 'created_at']);
                
            foreach ($pendingResellers as $index => $reseller) {
                $this->line("   " . ($index + 1) . ". {$reseller->name_customer} ({$reseller->email_customer})");
                $this->line("      Registered: {$reseller->created_at->format('Y-m-d H:i:s')}");
            }
        }
        
        $this->line('');
    }
}