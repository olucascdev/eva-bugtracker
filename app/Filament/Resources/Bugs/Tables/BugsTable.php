<?php

namespace App\Filament\Resources\Bugs\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BugsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('company.name')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
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
                TextColumn::make('assignedTo.name')
                    ->label('Atribuído a')
                    ->toggleable(),
                TextColumn::make('opened_at')
                    ->label('Aberto em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Concluído em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label('Empresa')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->relationship('status', 'name')
                    ->label('Status')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('priority')
                    ->relationship('priority', 'name')
                    ->label('Prioridade')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('finalizar')
                    ->label('Finalizar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        DateTimePicker::make('completed_at')
                            ->label('Concluído em')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function (\App\Models\Bug $record, array $data): void {
                        $resolvedStatusId = \App\Models\BugStatus::where('slug', 'resolvido')->value('id');

                        $record->update([
                            'completed_at' => $data['completed_at'],
                            'bug_status_id' => $resolvedStatusId ?? $record->bug_status_id,
                        ]);

                        Notification::make()
                            ->title('Bug finalizado com sucesso')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (\App\Models\Bug $record): bool => optional($record->status)->slug !== 'resolvido'),
                Action::make('atribuir')
                    ->label('Atribuir')
                    ->icon('heroicon-o-user-plus')
                    ->color('primary')
                    ->form([
                        Select::make('assigned_to_user_id')
                            ->label('Atribuir a')
                            ->relationship(
                                name: 'assignedTo',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->whereIn('role', ['admin', 'support']),
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function (\App\Models\Bug $record, array $data): void {
                        $record->update([
                            'assigned_to_user_id' => $data['assigned_to_user_id'],
                        ]);

                        Notification::make()
                            ->title('Bug atribuído com sucesso')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (\App\Models\Bug $record): bool => $record->assigned_to_user_id === null),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
