<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\GenderPolicy;
use App\Models\Block;
use App\Models\Floor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlockStructureSeeder extends Seeder
{
    private const MAIN_FLOORS = [
        ['name' => 'Giriş', 'level' => 0, 'sort' => 1],
        ['name' => '1.Kat', 'level' => 1, 'sort' => 2],
        ['name' => '2.Kat', 'level' => 2, 'sort' => 3],
        ['name' => '3.Kat', 'level' => 3, 'sort' => 4],
    ];

    private const FEMALE_FLOORS = [
        ['name' => 'Kadın', 'level' => 0, 'sort' => 1],
        ['name' => 'KIZ BLOK', 'level' => 1, 'sort' => 2],
    ];

    public function run(): void
    {
        $mainBlock = Block::query()->whereIn('code', ['ANA_LOJMAN', 'ERKEK_LOJMAN'])->first()
            ?? Block::query()->create([
                'code' => 'ANA_LOJMAN',
                'name' => 'Ana Lojman',
                'gender_policy' => GenderPolicy::Mixed,
                'description' => 'Kız ve erkek personel farklı odalarda kalır.',
                'is_active' => true,
                'sort_order' => 1,
            ]);

        $mainBlock->update([
            'code' => 'ANA_LOJMAN',
            'name' => 'Ana Lojman',
            'gender_policy' => GenderPolicy::Mixed,
            'description' => 'Kız ve erkek personel farklı odalarda kalır.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Block::query()
            ->whereIn('code', ['ERKEK_LOJMAN'])
            ->where('id', '!=', $mainBlock->id)
            ->each(function (Block $legacy) use ($mainBlock) {
                Floor::query()->where('block_id', $legacy->id)->update(['block_id' => $mainBlock->id]);
                $legacy->delete();
            });

        foreach (self::MAIN_FLOORS as $floor) {
            Floor::query()->firstOrCreate(
                ['block_id' => $mainBlock->id, 'name' => $floor['name']],
                [
                    'level_number' => $floor['level'],
                    'sort_order' => $floor['sort'],
                    'is_active' => true,
                ]
            );
        }

        $femaleBlock = Block::query()->firstOrCreate(
            ['code' => 'KIZLAR_LOJMANI'],
            [
                'name' => 'Kızlar Bloğu',
                'gender_policy' => GenderPolicy::Female,
                'description' => 'Yalnızca kadın personel yerleştirilebilir.',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $femaleBlock->update([
            'name' => 'Kızlar Bloğu',
            'gender_policy' => GenderPolicy::Female,
            'description' => 'Yalnızca kadın personel yerleştirilebilir.',
        ]);

        foreach (self::FEMALE_FLOORS as $floor) {
            Floor::query()->firstOrCreate(
                ['block_id' => $femaleBlock->id, 'name' => $floor['name']],
                [
                    'level_number' => $floor['level'],
                    'sort_order' => $floor['sort'],
                    'is_active' => true,
                ]
            );
        }
    }

    public static function mainBlock(): ?Block
    {
        return Block::query()->whereIn('code', ['ANA_LOJMAN', 'ERKEK_LOJMAN'])->first();
    }

    public static function floorIdForLegacyName(string $floorName): ?int
    {
        $mainFloors = collect(self::MAIN_FLOORS)->pluck('name')->all();
        $femaleFloors = collect(self::FEMALE_FLOORS)->pluck('name')->all();

        if (in_array($floorName, $mainFloors, true)) {
            $block = self::mainBlock();

            return Floor::query()
                ->where('block_id', $block?->id)
                ->where('name', $floorName)
                ->value('id');
        }

        if (in_array($floorName, $femaleFloors, true)) {
            $block = Block::query()->where('code', 'KIZLAR_LOJMANI')->first();

            return Floor::query()
                ->where('block_id', $block?->id)
                ->where('name', $floorName)
                ->value('id');
        }

        return null;
    }

    public static function defaultGenderForFloor(string $floorName): ?Gender
    {
        if (in_array($floorName, collect(self::FEMALE_FLOORS)->pluck('name')->all(), true)) {
            return Gender::Female;
        }

        return null;
    }

    public static function departmentCode(string $name): string
    {
        $code = Str::upper(Str::ascii(Str::slug($name, '_')));

        return Str::limit($code ?: 'GENEL', 20, '');
    }
}
