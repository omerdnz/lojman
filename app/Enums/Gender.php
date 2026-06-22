<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Erkek',
            self::Female => 'Kadın',
        };
    }

    public static function fromLegacy(?string $value): ?self
    {
        $normalized = mb_strtolower(trim((string) $value));

        return match ($normalized) {
            'erkek', 'male', 'e' => self::Male,
            'kadın', 'kadin', 'female', 'k' => self::Female,
            default => null,
        };
    }
}
