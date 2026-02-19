<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'role_id',
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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
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
        return $this->role?->name === 'admin';
    }

    public function isSupport(): bool
    {
        return $this->role?->name === 'support';
    }

    public function isClient(): bool
    {
        return in_array($this->role?->name, ['client', 'client-admin', 'client-user']);
    }

    public function isEvaUser(): bool
    {
        return in_array($this->role?->name, ['admin', 'support']);
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        if ($panel->getId() === 'eva') {
            return $this->isAdmin() || $this->isSupport();
        }

        if ($panel->getId() === 'client') {
            return $this->isClient() && $this->is_active;
        }

        return true;
    }
}
