<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RajaOngkirService;
use App\Models\RajaOngkirProvince;
use App\Models\RajaOngkirCity;
use Illuminate\Support\Facades\DB;

class SyncRajaOngkirData extends Command
{
    protected $signature = 'rajaongkir:sync {--type=all : Sync type: all, provinces, cities}';
    protected $description = 'Sync provinces and cities from RajaOngkir service to database';

    public function handle()
    {
        $rajaOngkir = new RajaOngkirService();
        $type = $this->option('type');

        if ($type === 'provinces' || $type === 'all') {
            $this->info('Syncing provinces from RajaOngkir service...');
            
            $provinces = $rajaOngkir->getProvinces();
            
            if (empty($provinces)) {
                $this->warn('No provinces data received from RajaOngkir service');
                return;
            }
            
            $this->info("Found " . count($provinces) . " provinces");
            
            $progressBar = $this->output->createProgressBar(count($provinces));
            $progressBar->start();
            
            foreach ($provinces as $province) {
                RajaOngkirProvince::updateOrCreate(
                    ['province_id' => $province['province_id']],
                    [
                        'province_name' => $province['province'],
                        'updated_at' => now()
                    ]
                );
                $progressBar->advance();
            }
            
            $progressBar->finish();
            $this->newLine();
            $this->info("✓ Synced " . count($provinces) . " provinces");
        }

        if ($type === 'cities' || $type === 'all') {
            $this->info('Syncing cities from RajaOngkir service...');
            
            // Get all provinces first
            $provinces = RajaOngkirProvince::all();
            
            if ($provinces->isEmpty()) {
                $this->warn('No provinces found. Please sync provinces first.');
                return;
            }
            
            $totalCities = 0;
            
            foreach ($provinces as $province) {
                $cities = $rajaOngkir->getCities($province->province_id);
                
                if (!empty($cities)) {
                    $this->line("  Processing {$province->province_name}: " . count($cities) . " cities");
                    
                    $progressBar = $this->output->createProgressBar(count($cities));
                    $progressBar->start();
                    
                    foreach ($cities as $city) {
                        RajaOngkirCity::updateOrCreate(
                            ['city_id' => $city['city_id']],
                            [
                                'province_id' => $province->province_id,
                                'type' => $city['type'],
                                'city_name' => $city['city_name'],
                                'postal_code' => $city['postal_code'],
                                'updated_at' => now()
                            ]
                        );
                        $progressBar->advance();
                    }
                    
                    $progressBar->finish();
                    $this->newLine();
                    
                    $totalCities += count($cities);
                }
            }
            
            $this->info("✓ Synced total {$totalCities} cities");
        }

        $this->info('RajaOngkir data sync completed!');
        
        // Show summary
        $provinceCount = RajaOngkirProvince::count();
        $cityCount = RajaOngkirCity::count();
        
        $this->info("Summary:");
        $this->info("- Provinces in database: {$provinceCount}");
        $this->info("- Cities in database: {$cityCount}");
    }
}
