<?php

namespace App\Enums;

enum GenderPolicy: string
{
    case Male = 'male';
    case Female = 'female';
    case Mixed = 'mixed';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Erkek odaları (karma blok)',
            self::Female => 'Kızlar Bloğu',
            self::Mixed => 'Karma blok',
        };
    }
}
