<?php

namespace App\Filament\Resources\BugStatuses\Pages;

use App\Filament\Resources\BugStatuses\BugStatusResource;
use Filament\Resources\Pages\EditRecord;

class EditBugStatus extends EditRecord
{
    protected static string $resource = BugStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
