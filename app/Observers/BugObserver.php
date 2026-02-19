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

        if (auth()->check() && auth()->user()->isClient()) {
            \Filament\Notifications\Notification::make()
                ->title('Novo Bug Reportado')
                ->body("O cliente {$bug->company->name} reportou um novo bug: {$bug->title}")
                ->success()
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(ViewBug::getUrl(['record' => $bug->id], panel: 'eva')),
                ])
                ->sendToDatabase(\App\Models\User::whereHas('role', fn ($q) => $q->whereIn('name', ['admin', 'support']))->get());
        }
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
        // Log changes
        $ignoredFields = ['updated_at', 'created_at', 'id', 'deleted_at', 'total_interactions', 'error_interactions', 'ai_accuracy_rate'];
        $dirty = $bug->getDirty();

        foreach ($dirty as $field => $newValue) {
            if (in_array($field, $ignoredFields)) {
                continue;
            }

            $oldValue = $bug->getOriginal($field);

            // Skip if values are effectively the same (e.g. "1" vs 1)
            if ($oldValue == $newValue) {
                continue;
            }

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

        // Client -> Eva notifications
        if (auth()->check() && auth()->user()->isClient()) {
            \Filament\Notifications\Notification::make()
                ->title('Bug Atualizado pelo Cliente')
                ->body("O cliente atualizou o bug: {$bug->title}")
                ->info()
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(ViewBug::getUrl(['record' => $bug->id], panel: 'eva')),
                ])
                ->sendToDatabase(\App\Models\User::whereHas('role', fn ($q) => $q->whereIn('name', ['admin', 'support']))->get());
        }

        // Eva -> Client notifications
        if (auth()->check() && auth()->user()->isEvaUser()) {
            $client = $bug->reportedBy;

            if ($client) {
                if ($bug->wasChanged('estimated_completion_at') && $bug->estimated_completion_at) {
                    \Filament\Notifications\Notification::make()
                        ->title('Previsão de Conclusão Atualizada')
                        ->body("A previsão para o bug '{$bug->title}' foi definida para: ".$bug->estimated_completion_at->format('d/m/Y'))
                        ->success()
                        ->sendToDatabase($client);
                }

                if ($bug->wasChanged('temporary_guidance') && $bug->temporary_guidance) {
                    \Filament\Notifications\Notification::make()
                        ->title('Nova Orientação Temporária')
                        ->body("Foi adicionada uma orientação para o bug '{$bug->title}': {$bug->temporary_guidance}")
                        ->info()
                        ->sendToDatabase($client);
                }

                if ($bug->wasChanged('assigned_to_user_id') && $bug->assigned_to_user_id) {
                    \Filament\Notifications\Notification::make()
                        ->title('Bug Atribuído')
                        ->body("O bug '{$bug->title}' foi atribuído ao técnico {$bug->assignedTo->name}")
                        ->info()
                        ->sendToDatabase($client);
                }

                if ($bug->wasChanged('bug_status_id')) {
                    \Filament\Notifications\Notification::make()
                        ->title('Status do Bug Atualizado')
                        ->body("O status do bug '{$bug->title}' mudou para: {$bug->status->name}")
                        ->success()
                        ->sendToDatabase($client);
                }
            }
        }
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

        if (auth()->check() && auth()->user()->isClient()) {
            \Filament\Notifications\Notification::make()
                ->title('Bug Removido pelo Cliente')
                ->body("O cliente removeu o bug: {$bug->title}")
                ->warning()
                ->sendToDatabase(\App\Models\User::whereHas('role', fn ($q) => $q->whereIn('name', ['admin', 'support']))->get());
        }
    }
}
