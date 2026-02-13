<?php

namespace App\Filament\Resources\BugStatuses\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BugStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Status do Bug')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        ColorPicker::make('color')
                            ->label('Cor')
                            ->required(),
                        TextInput::make('order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_default')
                            ->label('Padrão')
                            ->default(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
