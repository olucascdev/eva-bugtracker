<?php

namespace App\Filament\Client\Resources\Bugs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BugsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->label('Empresa')
                    ->options(fn () => \App\Models\Company::where('id', auth()->user()->company_id)->pluck('name', 'id'))
                    ->default(fn () => auth()->user()->company_id)
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->placeholder('Resumo do problema'),

                RichEditor::make('description')
                    ->label('Descrição')
                    ->required()
                    ->columnSpanFull()
                    ->placeholder('Detalhes do erro...'),

                Textarea::make('expected_behavior')
                    ->label('Comportamento Esperado')
                    ->rows(3)
                    ->columnSpanFull(),

                \Filament\Schemas\Components\Grid::make(4)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('bug_priority_id')
                            ->label('Prioridade')
                            ->relationship('priority', 'name')
                            ->required()
                            ->preload(),

                        Select::make('bug_status_id')
                            ->label('Status')
                            ->relationship('status', 'name')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn () => \App\Models\BugStatus::where('slug', 'reportado')->first()?->id),

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
                    ]),

                TextInput::make('conversation_link')
                    ->label('Link Referência')
                    ->url()
                    ->columnSpanFull(),

                Hidden::make('reported_by_user_id')
                    ->default(fn () => auth()->id()),
            ]);
    }
}
