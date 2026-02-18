<?php

namespace App\Filament\Client\Resources\Bugs\Pages;

use App\Filament\Client\Resources\Bugs\BugsResource;
use Filament\Resources\Pages\ViewRecord;

class ViewBugs extends ViewRecord
{
    protected static string $resource = BugsResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
