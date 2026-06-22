<?php

namespace Database\Seeders;

use App\Services\SettingsService;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = app(SettingsService::class);

        $settings->set(
            SettingsService::DEFAULT_ROOM_CAPACITY,
            4,
            'Varsayılan Oda Kapasitesi',
            'Kapasitesi tanımsız (0) odalar için kullanılacak yatak sayısı.',
            'capacity'
        );
    }
}
