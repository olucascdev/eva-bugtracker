<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class BugAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bug_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'uploaded_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    // Relationships
    public function bug(): BelongsTo
    {
        return $this->belongsTo(Bug::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    // Helper methods
    public function getUrl(): string
    {
        return Storage::disk('minio')->url($this->file_path);
    }

    public function getFormattedSize(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    // Delete file from MinIO when model is deleted
    protected static function booted(): void
    {
        static::deleted(function (BugAttachment $attachment) {
            if ($attachment->isForceDeleting()) {
                Storage::disk('minio')->delete($attachment->file_path);
            }
        });
    }
}
