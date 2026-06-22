<?php

namespace App\Enums;

enum TransferAction: string
{
    case Assign = 'assign';
    case Transfer = 'transfer';
    case Remove = 'remove';
    case BulkAssign = 'bulk_assign';
    case BulkRemove = 'bulk_remove';

    public function label(): string
    {
        return match ($this) {
            self::Assign => 'Yerleştirme',
            self::Transfer => 'Oda Değişikliği',
            self::Remove => 'Çıkarma',
            self::BulkAssign => 'Toplu Yerleştirme',
            self::BulkRemove => 'Toplu Çıkarma',
        };
    }
}
