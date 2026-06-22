<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_required',
        'allowed_extensions',
        'max_size_kb',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'allowed_extensions' => 'array',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }
}
