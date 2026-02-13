<?php

namespace App\Filament\Client\Resources\Bugs\Tables;


use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BugsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->wrap()
                    ->limit(50),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Reportado' => 'danger',
                        'Em Análise' => 'warning',
                        'Em Desenvolvimento' => 'info',
                        'Aguardando Teste' => 'primary',
                        'Resolvido' => 'success',
                        'Fechado' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('priority.name')
                    ->label('Prioridade')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Crítica' => 'danger',
                        'Alta' => 'warning',
                        'Média' => 'info',
                        'Baixa' => 'success',
                        'Mínima' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('opened_at')
                    ->label('Aberto em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Atualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->relationship('status', 'name')
                    ->label('Status')
                    ->preload(),
                SelectFilter::make('priority')
                    ->relationship('priority', 'name')
                    ->label('Prioridade')
                    ->preload(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
