<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'floor_id',
        'room_number',
        'capacity',
        'gender',
        'status',
        'notes',
        'legacy_id',
    ];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'status' => RoomStatus::class,
        ];
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }

    public function activePlacements(): HasMany
    {
        return $this->placements()->where('is_active', true);
    }

    public function displayName(): string
    {
        $block = $this->floor?->block?->name ?? 'Bina';
        $floor = $this->floor?->name ?? '-';

        return sprintf('%s / %s — Oda %s', $block, $floor, $this->room_number);
    }
}
