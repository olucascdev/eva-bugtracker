<?php

namespace App\Filament\Resources\Bugs\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'history';

    protected static ?string $title = 'Histórico de Alterações';

    protected static ?string $modelLabel = 'Histórico';

    public static function canViewForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === \App\Filament\Resources\Bugs\Pages\ViewBug::class;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->width('150px'),
                TextColumn::make('user.name')
                    ->label('Usuário')
                    ->placeholder('Sistema')
                    ->width('200px'),
                TextColumn::make('event')
                    ->label('Ação')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'Criou',
                        'updated' => 'Editou',
                        'deleted' => 'Removeu',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->width('100px'),
                TextColumn::make('details')
                    ->label('Detalhes')
                    ->state(function ($record) {
                        if ($record->event === 'created') {
                            return 'Criou o registro do bug.';
                        }

                        if ($record->event === 'deleted') {
                            return 'Removeu o registro do bug.';
                        }

                        if ($record->event === 'updated') {
                            $fieldLabels = [
                                'title' => 'Título',
                                'description' => 'Descrição',
                                'company_id' => 'Empresa',
                                'bug_status_id' => 'Status',
                                'bug_priority_id' => 'Prioridade',
                                'assigned_to_user_id' => 'Responsável',
                                'reported_by_user_id' => 'Reportado Por',
                                'opened_at' => 'Data de Abertura',
                                'estimated_completion_at' => 'Previsão',
                                'completed_at' => 'Data de Conclusão',
                                'temporary_guidance' => 'Orientação Temp.',
                                'observations' => 'Observações',
                                'error_datetime' => 'Data do Erro',
                                'conversation_link' => 'Link Conversa',
                                'expected_behavior' => 'Comportamento Esperado',
                            ];

                            $label = $fieldLabels[$record->field] ?? $record->field;

                            // Relation resolution for IDs
                            $oldDisplay = $record->old_value;
                            $newDisplay = $record->new_value;

                            if (str_ends_with($record->field, '_id')) {
                                if ($record->field === 'bug_status_id') {
                                    $oldDisplay = ! empty($record->old_value) ? (\App\Models\BugStatus::find($record->old_value)?->name ?? $record->old_value) : '---';
                                    $newDisplay = ! empty($record->new_value) ? (\App\Models\BugStatus::find($record->new_value)?->name ?? $record->new_value) : '---';
                                } elseif ($record->field === 'bug_priority_id') {
                                    $oldDisplay = ! empty($record->old_value) ? (\App\Models\BugPriority::find($record->old_value)?->name ?? $record->old_value) : '---';
                                    $newDisplay = ! empty($record->new_value) ? (\App\Models\BugPriority::find($record->new_value)?->name ?? $record->new_value) : '---';
                                } elseif ($record->field === 'assigned_to_user_id' || $record->field === 'reported_by_user_id') {
                                    $oldDisplay = ! empty($record->old_value) ? (\App\Models\User::find($record->old_value)?->name ?? 'Ninguém') : 'Ninguém';
                                    $newDisplay = ! empty($record->new_value) ? (\App\Models\User::find($record->new_value)?->name ?? 'Ninguém') : 'Ninguém';
                                } elseif ($record->field === 'company_id') {
                                    $oldDisplay = ! empty($record->old_value) ? (\App\Models\Company::find($record->old_value)?->name ?? $record->old_value) : '---';
                                    $newDisplay = ! empty($record->new_value) ? (\App\Models\Company::find($record->new_value)?->name ?? $record->new_value) : '---';
                                }
                            }

                            // Date formatting
                            if (in_array($record->field, ['opened_at', 'estimated_completion_at', 'completed_at', 'error_datetime'])) {
                                $oldDisplay = $record->old_value ? \Carbon\Carbon::parse($record->old_value)->format('d/m/Y H:i') : 'N/A';
                                $newDisplay = $record->new_value ? \Carbon\Carbon::parse($record->new_value)->format('d/m/Y H:i') : 'N/A';
                            }

                            return new \Illuminate\Support\HtmlString("Alterou <strong>{$label}</strong> de '{$oldDisplay}' para '{$newDisplay}'");
                        }

                        return '';
                    })
                    ->html(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }
}
