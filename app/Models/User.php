<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function reportedBugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'reported_by_user_id');
    }

    public function assignedBugs(): HasMany
    {
        return $this->hasMany(Bug::class, 'assigned_to_user_id');
    }

    public function bugStatusChanges(): HasMany
    {
        return $this->hasMany(BugStatusHistory::class, 'changed_by_user_id');
    }

    public function uploadedAttachments(): HasMany
    {
        return $this->hasMany(BugAttachment::class, 'uploaded_by_user_id');
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSupport(): bool
    {
        return $this->role === 'support';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isEvaUser(): bool
    {
        return in_array($this->role, ['admin', 'support']);
    }
}
