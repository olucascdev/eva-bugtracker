<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BugPriority extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
        ];
    }

    // Relationships
    public function bugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'bug_priority_id');
    }
}
