<?php

namespace App\Enums;

enum RoomStatus: string
{
    case Available = 'available';
    case Partial = 'partial';
    case Full = 'full';
    case Maintenance = 'maintenance';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Boş',
            self::Partial => 'Kısmi Dolu',
            self::Full => 'Dolu',
            self::Maintenance => 'Bakımda',
            self::Inactive => 'Pasif',
        };
    }
}
