<?php

namespace App\Filament\Resources\BugStatuses\Pages;

use App\Filament\Resources\BugStatuses\BugStatusResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBugStatus extends CreateRecord
{
    protected static string $resource = BugStatusResource::class;
}
