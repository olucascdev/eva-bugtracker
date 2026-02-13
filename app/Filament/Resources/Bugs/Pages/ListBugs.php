<?php

namespace App\Filament\Resources\Bugs\Pages;

use App\Filament\Resources\Bugs\BugResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListBugs extends ListRecords
{
    protected static string $resource = BugResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->icon('heroicon-o-bug-ant')
            ->label('Reportar Bug'),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'todos' => Tab::make('Todos')
                ->badge(\App\Models\Bug::count()),
        ];

        
        $statuses = \App\Models\BugStatus::orderBy('order')->get();

        foreach ($statuses as $status) {
            $tabs[$status->slug] = Tab::make($status->name)
                ->modifyQueryUsing(fn ($query) => $query->where('bug_status_id', $status->id))
                ->badge(\App\Models\Bug::where('bug_status_id', $status->id)->count())
                ->badgeColor(\Filament\Support\Colors\Color::hex($status->color)); 
        }

        return $tabs;
    }
}
