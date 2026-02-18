<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Schema;

class RoleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Infolists\Components\TextEntry::make('name')
                    ->label('Nome'),

                \Filament\Infolists\Components\TextEntry::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),

                \Filament\Infolists\Components\TextEntry::make('created_at')
                    ->label('Criado em')
                    ->dateTime(),
            ]);
    }
}
