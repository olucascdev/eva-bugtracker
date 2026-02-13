<?php

namespace App\Filament\Client\Resources\Bugs\Pages;

use App\Filament\Client\Resources\Bugs\BugsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBugs extends ListRecords
{
    protected static string $resource = BugsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-bug-ant')
                ->label('Reportar Bug'),
        ];
    }
}
