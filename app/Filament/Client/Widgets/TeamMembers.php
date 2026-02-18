<?php

namespace App\Filament\Client\Widgets;

use App\Models\User;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TeamMembers extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Equipe';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => User::query()->where('company_id', auth()->user()->company_id)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role.name')
                    ->label('Função')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'client-admin' => 'success',
                        'client-user' => 'info',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ]);
    }
}
