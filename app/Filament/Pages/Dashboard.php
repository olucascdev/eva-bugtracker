<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->label('Empresa')
                    ->options(\App\Models\Company::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->placeholder('Todas as Empresas')
                    ->columnSpan(1),

                Select::make('period')
                    ->label('Período')
                    ->options([
                        7 => 'Últimos 7 dias',
                        15 => 'Últimos 15 dias',
                        30 => 'Últimos 30 dias',
                        60 => 'Últimos 60 dias',
                    ])
                    ->default(30)
                    ->selectablePlaceholder(false)
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}
