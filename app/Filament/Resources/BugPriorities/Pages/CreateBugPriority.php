<?php

namespace App\Filament\Resources\BugPriorities\Pages;

use App\Filament\Resources\BugPriorities\BugPriorityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBugPriority extends CreateRecord
{
    protected static string $resource = BugPriorityResource::class;
}
