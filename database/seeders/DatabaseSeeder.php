<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            SettingSeeder::class,
            BlockStructureSeeder::class,
            DocumentTypeSeeder::class,
            AdminUserSeeder::class,
            LegacyDataImportSeeder::class,
        ]);
    }
}
