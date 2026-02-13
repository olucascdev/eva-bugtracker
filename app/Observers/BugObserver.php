<?php

namespace App\Observers;

use App\Models\Bug;
use App\Models\BugStatusHistory;

class BugObserver
{
    public function created(Bug $bug): void
    {
        // Record initial status when bug is created
        BugStatusHistory::create([
            'bug_id' => $bug->id,
            'from_status_id' => null,
            'to_status_id' => $bug->bug_status_id,
            'changed_by_user_id' => auth()->id() ?? $bug->reported_by_user_id,
            'notes' => 'Bug criado',
        ]);
    }

    public function updating(Bug $bug): void
    {
        // Track status changes
        if ($bug->isDirty('bug_status_id')) {
            BugStatusHistory::create([
                'bug_id' => $bug->id,
                'from_status_id' => $bug->getOriginal('bug_status_id'),
                'to_status_id' => $bug->bug_status_id,
                'changed_by_user_id' => auth()->id(),
                'notes' => null,
            ]);
        }

        // Auto-set completed_at when status changes to "Resolvido" or "Fechado"
        if ($bug->isDirty('bug_status_id')) {
            $newStatus = $bug->status;
            if (in_array($newStatus?->slug, ['resolvido', 'fechado']) && !$bug->completed_at) {
                $bug->completed_at = now();
            }
        }
    }
}
