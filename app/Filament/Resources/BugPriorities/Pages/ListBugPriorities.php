<?php

namespace App\Filament\Resources\BugPriorities\Pages;

use App\Filament\Resources\BugPriorities\BugPriorityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBugPriorities extends ListRecords
{
    protected static string $resource = BugPriorityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
