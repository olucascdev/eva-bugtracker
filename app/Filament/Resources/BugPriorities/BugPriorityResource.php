<?php

namespace App\Filament\Resources\BugPriorities;

use App\Filament\Resources\BugPriorities\Pages\CreateBugPriority;
use App\Filament\Resources\BugPriorities\Pages\EditBugPriority;
use App\Filament\Resources\BugPriorities\Pages\ListBugPriorities;
use App\Filament\Resources\BugPriorities\Schemas\BugPriorityForm;
use App\Filament\Resources\BugPriorities\Tables\BugPrioritiesTable;
use App\Models\BugPriority;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BugPriorityResource extends Resource
{
    protected static ?string $model = BugPriority::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Prioridades de Bugs';

    protected static ?string $pluralLabel = 'Prioridades de Bugs';

    protected static ?string $label = 'Prioridades de Bugs';

    protected static ?string $slug = 'prioridades-de-bugs';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return BugPriorityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BugPrioritiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBugPriorities::route('/'),
            // 'create' => CreateBugPriority::route('/create'),
            // 'edit' => EditBugPriority::route('/{record}/edit'),
        ];
    }
}
