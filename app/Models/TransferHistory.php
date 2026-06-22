<?php

namespace App\Models;

use App\Enums\TransferAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'from_room_id',
        'to_room_id',
        'action',
        'reason',
        'performed_by',
        'metadata',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'action' => TransferAction::class,
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function fromRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'from_room_id');
    }

    public function toRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'to_room_id');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
