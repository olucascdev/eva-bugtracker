<?php

namespace App\Filament\Client\Resources\TeamMemberResource\Pages;

use App\Filament\Client\Resources\TeamMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTeamMembers extends ManageRecords
{
    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['company_id'] = auth()->user()->company_id;

                    return $data;
                })
                ->createAnother(false),
        ];
    }
}
