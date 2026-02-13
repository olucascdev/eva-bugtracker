<?php

namespace App\Filament\Resources\BugPriorities\Pages;

use App\Filament\Resources\BugPriorities\BugPriorityResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBugPriority extends EditRecord
{
    protected static string $resource = BugPriorityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
