<?php

namespace App\Filament\Resources\Bugs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class BugForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Bug Details')
                    ->tabs([
                        Tab::make('Informações Básicas')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Select::make('company_id')
                                    ->label('Empresa')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->default(fn () => auth()->user()->company_id)
                                    ->columnSpanFull(),

                                TextInput::make('title')
                                    ->label('Título')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull()
                                    ->placeholder('Descreva o problema de forma resumida'),

                                RichEditor::make('description')
                                    ->label('Descrição do Erro')
                                    ->required()
                                    ->columnSpanFull()
                                    ->placeholder('Descreva detalhadamente o erro encontrado')
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'italic',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'undo',
                                    ]),

                                Textarea::make('expected_behavior')
                                    ->label('Comportamento Esperado')
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->placeholder('O que deveria acontecer?'),

                                TextInput::make('conversation_link')
                                    ->label('Link da Conversa')
                                    ->url()
                                    ->maxLength(255)
                                    ->columnSpanFull()
                                    ->prefix('https://')
                                    ->placeholder('URL relacionada ao bug'),
                            ]),

                        Tab::make('Classificação')
                            ->icon('heroicon-o-tag')
                            ->columns(2)
                            ->schema([
                                Select::make('bug_status_id')
                                    ->label('Status')
                                    ->relationship('status', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->default(fn () => \App\Models\BugStatus::where('slug', 'reportado')->first()?->id)
                                    ->native(false),

                                Select::make('bug_priority_id')
                                    ->label('Prioridade')
                                    ->relationship('priority', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->native(false),

                                Select::make('reported_by_user_id')
                                    ->label('Reportado Por')
                                    ->relationship('reportedBy', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->default(fn () => auth()->id())
                                    ->native(false),

                                Select::make('assigned_to_user_id')
                                    ->label('Atribuído Para')
                                    ->relationship('assignedTo', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->placeholder('Selecione um responsável'),
                            ]),

                        Tab::make('Datas & Prazos')
                            ->icon('heroicon-o-calendar')
                            ->columns(2)
                            ->schema([
                                DateTimePicker::make('error_datetime')
                                    ->label('Data/Hora do Erro')
                                    ->seconds(false)
                                    ->native(false),

                                DateTimePicker::make('opened_at')
                                    ->label('Aberto em')
                                    ->default(now())
                                    ->required()
                                    ->seconds(false)
                                    ->native(false),

                                DateTimePicker::make('estimated_completion_at')
                                    ->label('Previsão de Conclusão')
                                    ->seconds(false)
                                    ->native(false)
                                    ->minDate(now())
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Observações')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                Textarea::make('temporary_guidance')
                                    ->label('Orientações Temporárias')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->placeholder('Instruções provisórias enquanto o bug não é corrigido')
                                    ->helperText('Informe aos usuários como contornar o problema temporariamente'),

                                Textarea::make('observations')
                                    ->label('Observações Adicionais')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->placeholder('Qualquer informação adicional relevante'),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->activeTab(1),
            ]);
    }
}
