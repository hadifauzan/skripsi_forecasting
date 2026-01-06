<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\MasterCustomers;

class NotificationHelper
{
    /**
     * Get count of pending affiliates
     *
     * @return int
     */
    public static function getPendingAffiliatesCount()
    {
        return User::where('role_id', 4) // role_id 4 untuk affiliate
            ->where('status', 'Pending')
            ->count();
    }

    /**
     * Get count of pending resellers
     *
     * @return int
     */
    public static function getPendingResellersCount()
    {
        return MasterCustomers::whereHas('masterCustomerType', function ($query) {
            $query->where('name_customer_type', 'reseller');
        })
        ->where('status', 'Pending')
        ->count();
    }

    /**
     * Get total count of pending registrations (affiliate + reseller)
     *
     * @return int
     */
    public static function getTotalPendingRegistrations()
    {
        return self::getPendingAffiliatesCount() + self::getPendingResellersCount();
    }

    /**
     * Get detailed notification data
     *
     * @return array
     */
    public static function getNotificationData()
    {
        $pendingAffiliates = self::getPendingAffiliatesCount();
        $pendingResellers = self::getPendingResellersCount();
        $totalPending = $pendingAffiliates + $pendingResellers;

        return [
            'pending_affiliates' => $pendingAffiliates,
            'pending_resellers' => $pendingResellers,
            'total_pending' => $totalPending,
            'has_notifications' => $totalPending > 0
        ];
    }

    /**
     * Format notification text
     *
     * @return string
     */
    public static function getNotificationText()
    {
        $data = self::getNotificationData();
        
        if (!$data['has_notifications']) {
            return '';
        }

        $parts = [];
        
        if ($data['pending_affiliates'] > 0) {
            $parts[] = $data['pending_affiliates'] . ' affiliate';
        }
        
        if ($data['pending_resellers'] > 0) {
            $parts[] = $data['pending_resellers'] . ' reseller';
        }

        return implode(' & ', $parts) . ' menunggu konfirmasi';
    }
}