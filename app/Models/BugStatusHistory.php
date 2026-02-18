<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BugStatusHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'bug_status_history';

    protected $fillable = [
        'bug_id',
        'from_status_id',
        'to_status_id',
        'changed_by_user_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function bug(): BelongsTo
    {
        return $this->belongsTo(Bug::class);
    }

    public function fromStatus(): BelongsTo
    {
        return $this->belongsTo(BugStatus::class, 'from_status_id');
    }

    public function toStatus(): BelongsTo
    {
        return $this->belongsTo(BugStatus::class, 'to_status_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
