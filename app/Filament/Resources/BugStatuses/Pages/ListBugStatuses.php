<?php

namespace App\Filament\Resources\BugStatuses\Pages;

use App\Filament\Resources\BugStatuses\BugStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBugStatuses extends ListRecords
{
    protected static string $resource = BugStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
