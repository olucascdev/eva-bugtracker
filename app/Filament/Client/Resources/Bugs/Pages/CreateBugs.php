<?php

namespace App\Filament\Client\Resources\Bugs\Pages;

use App\Filament\Client\Resources\Bugs\BugsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBugs extends CreateRecord
{
    protected static string $resource = BugsResource::class;
}
