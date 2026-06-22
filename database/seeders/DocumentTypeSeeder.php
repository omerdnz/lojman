<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Kimlik', 'slug' => 'identity', 'is_required' => true],
            ['name' => 'Sözleşme', 'slug' => 'contract', 'is_required' => true],
            ['name' => 'Diğer Evrak', 'slug' => 'other', 'is_required' => false],
        ];

        foreach ($types as $type) {
            DocumentType::query()->firstOrCreate(
                ['slug' => $type['slug']],
                [
                    'name' => $type['name'],
                    'is_required' => $type['is_required'],
                    'allowed_extensions' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'max_size_kb' => 5120,
                ]
            );
        }
    }
}
