<?php

namespace App\Models;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'personnel_number',
        'full_name',
        'national_id',
        'department_id',
        'job_title',
        'phone',
        'email',
        'hire_date',
        'gender',
        'status',
        'photo_path',
        'notes',
        'legacy_id',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'status' => EmployeeStatus::class,
            'hire_date' => 'date',
            'national_id' => 'encrypted',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function activePlacement(): HasOne
    {
        return $this->hasOne(Placement::class)->where('is_active', true);
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }

    public function transferHistories(): HasMany
    {
        return $this->hasMany(TransferHistory::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(EmployeeHistory::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }
}
