<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados do Usuário')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Senha')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                        Select::make('role')
                            ->label('Função')
                            ->options([
                                'admin' => 'Admin',
                                'support' => 'Suporte',
                                'client' => 'Cliente',
                            ])
                            ->required()
                            ->live(),
                        Select::make('company_id')
                            ->label('Empresa')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->required(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('role') === 'client')
                            ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('role') === 'client')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Ativo')
                            ->required()
                            ->default(true)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
