<?php

namespace App\Filament\Resources\Bugs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BugsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->wrap(),
                \Filament\Tables\Columns\TextColumn::make('company.name')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('status.name')
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
                \Filament\Tables\Columns\TextColumn::make('priority.name')
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
                \Filament\Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Atribuído a')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('opened_at')
                    ->label('Aberto em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('completed_at')
                    ->label('Concluído em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\TrashedFilter::make(),
                \Filament\Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label('Empresa')
                    ->searchable()
                    ->preload(),
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->relationship('status', 'name')
                    ->label('Status')
                    ->searchable()
                    ->preload(),
                \Filament\Tables\Filters\SelectFilter::make('priority')
                    ->relationship('priority', 'name')
                    ->label('Prioridade')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                \Filament\Tables\Actions\ViewAction::make(),
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                    \Filament\Tables\Actions\ForceDeleteBulkAction::make(),
                    \Filament\Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
