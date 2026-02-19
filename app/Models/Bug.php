<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bug extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'expected_behavior',
        'conversation_link',
        'error_datetime',
        'bug_status_id',
        'bug_priority_id',
        'assigned_to_user_id',
        'reported_by_user_id',
        'opened_at',
        'estimated_completion_at',
        'completed_at',
        'temporary_guidance',
        'observations',
        'total_interactions',
        'error_interactions',
        'ai_accuracy_rate',
    ];

    protected function casts(): array
    {
        return [
            'error_datetime' => 'datetime',
            'opened_at' => 'datetime',
            'estimated_completion_at' => 'datetime',
            'completed_at' => 'datetime',
            'total_interactions' => 'integer',
            'error_interactions' => 'integer',
            'ai_accuracy_rate' => 'decimal:2',
        ];
    }

    // Multi-tenancy Global Scope
    protected static function booted(): void
    {
        static::addGlobalScope('company', function (Builder $builder) {
            if (auth()->check() && auth()->user()->isClient()) {
                $builder->where('company_id', auth()->user()->company_id);
            }
        });
    }

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(BugStatus::class, 'bug_status_id');
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(BugPriority::class, 'bug_priority_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(BugAttachment::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(BugLog::class)->orderBy('created_at', 'desc');
    }

    // Helper methods
    public function calculateAiAccuracy(): void
    {
        if ($this->total_interactions > 0) {
            $successfulInteractions = $this->total_interactions - $this->error_interactions;
            $this->ai_accuracy_rate = ($successfulInteractions / $this->total_interactions) * 100;
            $this->save();
        }
    }
}
