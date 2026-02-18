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
                            'title' => 'Titulo',
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
                            $value = $attributes[$key] ?? null;
                            $oldValue = $old[$key] ?? null;

                            // Skip if values are identical
                            if ($value == $oldValue) continue;

                            // Format specific fields logic could go here (e.g. resolving IDs to names), 
                            // but for "simple" text we just show the field name change.
                            
                            // Simple output: "Mudou [Campo]"
                            // Or slightly more detailed: "Mudou [Campo] (Antigo -> Novo)"
                            // User asked for: "mudou a a descrição, mudou horario"
                            
                            $changes[] = "Mudou <strong>{$label}</strong>";
                        }
                        
                        if (empty($changes)) {
                             return 'Sem alterações visíveis';
                        }
                        
                        return new \Illuminate\Support\HtmlString(implode(', ', $changes));
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
