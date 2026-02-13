<?php

namespace App\Filament\Resources\Bugs\Schemas;

use Filament\Schemas\Schema;

class BugForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Detalhes do Bug')
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\RichEditor::make('description')
                            ->label('Descrição do Erro')
                            ->required()
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Textarea::make('expected_behavior')
                            ->label('Comportamento Esperado')
                            ->rows(3)
                            ->columnSpanFull(),
                        \Filament\Forms\Components\TextInput::make('conversation_link')
                            ->label('Link da Conversa')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),
                \Filament\Schemas\Components\Section::make('Classificação e Status')
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\Select::make('bug_status_id')
                            ->label('Status')
                            ->relationship('status', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(fn () => \App\Models\BugStatus::where('slug', 'reportado')->first()?->id),
                        \Filament\Forms\Components\Select::make('bug_priority_id')
                            ->label('Prioridade')
                            ->relationship('priority', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        \Filament\Forms\Components\Select::make('reported_by_user_id')
                            ->label('Reportado Por')
                            ->relationship('reportedBy', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id()),
                        \Filament\Forms\Components\Select::make('assigned_to_user_id')
                            ->label('Atribuído Para')
                            ->relationship('assignedTo', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                \Filament\Schemas\Components\Section::make('Datas e Prazos')
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\DateTimePicker::make('error_datetime')
                            ->label('Data/Hora do Erro'),
                        \Filament\Forms\Components\DateTimePicker::make('opened_at')
                            ->label('Aberto em')
                            ->default(now())
                            ->required(),
                        \Filament\Forms\Components\DateTimePicker::make('estimated_completion_at')
                            ->label('Previsão de Conclusão'),
                        \Filament\Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Concluído em'),
                    ]),
                \Filament\Schemas\Components\Section::make('Métricas e Observações')
                    ->columns(2)
                    ->schema([
                        \Filament\Forms\Components\Textarea::make('temporary_guidance')
                            ->label('Orientações Temporárias')
                            ->columnSpanFull(),
                        \Filament\Forms\Components\Textarea::make('observations')
                            ->label('Observações Adicionais')
                            ->columnSpanFull(),
                        \Filament\Forms\Components\TextInput::make('total_interactions')
                            ->label('Total de Interações')
                            ->numeric()
                            ->default(0),
                        \Filament\Forms\Components\TextInput::make('error_interactions')
                            ->label('Interações com Erro')
                            ->numeric()
                            ->default(0),
                        \Filament\Forms\Components\TextInput::make('ai_accuracy_rate')
                            ->label('Taxa de Assertividade IA (%)')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),
                    ]),
            ]);
    }
}
