<?php

namespace App\Console\Commands;

use App\Services\RajaOngkirService;
use Illuminate\Console\Command;

class TestShippingCosts extends Command
{
    protected $signature = 'shipping:test-costs';
    protected $description = 'Test and debug shipping cost calculations by destination';

    public function handle()
    {
        $service = new RajaOngkirService();

        $this->info('=== Testing Shipping Cost Differences ===');
        $this->newLine();

        $destinations = [
            '153' => 'Jakarta Pusat',      // Dekat
            '399' => 'Surabaya',           // Sedang  
            '250' => 'Malang',             // Origin (should be cheapest)
            '1' => 'Denpasar',             // Jauh
            '419' => 'Medan'               // Sangat jauh
        ];

        $weight = 1000; // 1kg
        $origin = '250'; // Malang

        foreach ($destinations as $cityId => $cityName) {
            $this->info("=== $cityName (City ID: $cityId) ===");
            
            // Test real API first
            $realOptions = $service->getShippingOptions($origin, $cityId, $weight);
            
            if (!empty($realOptions)) {
                $this->info("✅ RajaOngkir API Response:");
                foreach (array_slice($realOptions, 0, 3) as $option) {
                    $price = isset($option['price']) ? $option['price'] : ($option['cost'] ?? 0);
                    $this->line("   - {$option['courier']} {$option['service']}: Rp" . number_format($price) . " ({$option['estimated_days']} hari)");
                }
            } else {
                $this->info("❌ RajaOngkir API failed - check .env configuration");
                $this->line("   API might be down or API key invalid");
            }
            
            $this->newLine();
        }

        $this->info('=== Test completed ===');
    }
}