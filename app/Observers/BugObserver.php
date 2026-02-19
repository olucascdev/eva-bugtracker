<?php

namespace App\Observers;

use App\Filament\Resources\Bugs\Pages\ViewBug;
use App\Models\Bug;
use Filament\Actions\Action;

class BugObserver
{
    public function created(Bug $bug): void
    {
        // Log creation
        \App\Models\BugLog::create([
            'bug_id' => $bug->id,
            'user_id' => auth()->id(),
            'event' => 'created',
            'field' => null,
            'old_value' => null,
            'new_value' => null,
        ]);

        \Illuminate\Support\Facades\Log::info('BugObserver: created called', ['bug_id' => $bug->id, 'user_id' => auth()->id(), 'is_client' => auth()->user()?->isClient()]);
    }

    public function updating(Bug $bug): void
    {
        // Auto-set completed_at when status changes to "Resolvido" or "Fechado"
        if ($bug->isDirty('bug_status_id')) {
            $newStatus = $bug->status;
            if (in_array($newStatus?->slug, ['resolvido', 'fechado']) && ! $bug->completed_at) {
                $bug->completed_at = now();
            }
        }
    }

    public function updated(Bug $bug): void
    {
        // Log changes (Atomic History)
        $ignoredFields = ['updated_at', 'created_at', 'id', 'deleted_at', 'total_interactions', 'error_interactions', 'ai_accuracy_rate'];
        $dirty = $bug->getDirty();

        foreach ($dirty as $field => $newValue) {
            if (in_array($field, $ignoredFields)) {
                continue;
            }

            $oldValue = $bug->getOriginal($field);
            if ($oldValue == $newValue) continue;

            // Save to BugLog
            \App\Models\BugLog::create([
                'bug_id' => $bug->id,
                'user_id' => auth()->id(),
                'event' => 'updated',
                'field' => $field,
                'old_value' => (string) $oldValue,
                'new_value' => (string) $newValue,
            ]);
        }
        
        \Illuminate\Support\Facades\Log::info('BugObserver: updated called', ['bug_id' => $bug->id, 'changes' => $bug->getChanges(), 'user' => auth()->id()]);
    }

    public function deleted(Bug $bug): void
    {
        // Log deletion
        \App\Models\BugLog::create([
            'bug_id' => $bug->id,
            'user_id' => auth()->id(),
            'event' => 'deleted',
            'field' => null,
            'old_value' => null,
            'new_value' => null,
        ]);

        \Illuminate\Support\Facades\Log::info('BugObserver: deleted called', ['bug_id' => $bug->id, 'user_id' => auth()->id()]);
    }
}
