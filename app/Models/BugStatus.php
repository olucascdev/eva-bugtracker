<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BugStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'order',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_default' => 'boolean',
        ];
    }

    // Relationships
    public function bugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'bug_status_id');
    }

    public function historyAsFromStatus(): HasMany
    {
        return $this->hasMany(BugStatusHistory::class, 'from_status_id');
    }

    public function historyAsToStatus(): HasMany
    {
        return $this->hasMany(BugStatusHistory::class, 'to_status_id');
    }
}
