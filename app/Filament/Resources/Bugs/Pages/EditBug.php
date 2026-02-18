<?php

namespace App\Filament\Resources\Bugs\Pages;

use App\Filament\Resources\Bugs\BugResource;
use Filament\Resources\Pages\EditRecord;

class EditBug extends EditRecord
{
    protected static string $resource = BugResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
