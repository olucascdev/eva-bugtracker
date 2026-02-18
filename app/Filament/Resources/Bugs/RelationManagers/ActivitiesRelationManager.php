<?php

namespace App\Filament\Resources\Bugs\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public static function canViewForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === \App\Filament\Resources\Bugs\Pages\ViewBug::class;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bug_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->width('150px'),
                TextColumn::make('causer.name')
                    ->label('Usuário')
                    ->width('150px'),
                TextColumn::make('description')
                    ->label('Ação')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'created' => 'Criou o registro',
                        'updated' => 'Atualizou',
                        'deleted' => 'Removeu',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('properties')
                    ->label('Alterações')
                    ->formatStateUsing(function ($state, $record) {
                        $attributes = $state['attributes'] ?? [];
                        $old = $state['old'] ?? [];

                        // Field Label Mapping
                        $labels = [
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

                        if (($record->event === 'created' || $record->description === 'created') && !empty($attributes)) {
                            return 'Registro criado inicialmente.';
                        }
                        
                        // For updates
                        if (empty($attributes) && empty($old)) {
                            return 'Sem alterações registradas';
                        }

                        $changes = [];
                        $keys = array_unique(array_merge(array_keys($attributes), array_keys($old)));

                        foreach ($keys as $key) {
                            if (in_array($key, ['updated_at', 'created_at', 'id', 'deleted_at'])) continue;
                            
                            $label = $labels[$key] ?? $key;
                            $newValue = $attributes[$key] ?? null;
                            $oldValue = $old[$key] ?? null;

                            // Skip if values are identical
                            if ($newValue == $oldValue) continue;

                            // Resolve logic
                            if ($key === 'bug_status_id') {
                                $newStatus = \App\Models\BugStatus::find($newValue);
                                $changes[] = "Mudou <strong>{$label}</strong> para '{$newStatus?->name}'";
                                continue;
                            }

                            if ($key === 'bug_priority_id') {
                                $newPriority = \App\Models\BugPriority::find($newValue);
                                $changes[] = "Mudou <strong>{$label}</strong> para '{$newPriority?->name}'";
                                continue;
                            }

                            if ($key === 'assigned_to_user_id') {
                                if (empty($newValue)) {
                                    $changes[] = "Removeu <strong>{$label}</strong>";
                                } else {
                                    $newUser = \App\Models\User::find($newValue);
                                    $changes[] = "Atribuiu <strong>{$label}</strong> a '{$newUser?->name}'";
                                }
                                continue;
                            }

                            if (in_array($key, ['opened_at', 'estimated_completion_at', 'completed_at', 'error_datetime'])) {
                                $formattedDate = $newValue ? \Carbon\Carbon::parse($newValue)->format('d/m/Y H:i') : 'N/A';
                                $changes[] = "Alterou <strong>{$label}</strong> para '{$formattedDate}'";
                                continue;
                            }

                            // Generic fallback
                            $changes[] = "Atualizou <strong>{$label}</strong>";
                        }
                        
                        if (empty($changes)) {
                             return 'Sem alterações visíveis';
                        }
                        
                        return new \Illuminate\Support\HtmlString(implode('<br>', $changes));
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
