<?php

namespace App\Filament\Resources\Bugs;

use App\Filament\Resources\Bugs\Pages\CreateBug;
use App\Filament\Resources\Bugs\Pages\EditBug;
use App\Filament\Resources\Bugs\Pages\ListBugs;
use App\Filament\Resources\Bugs\Pages\ViewBug;
use App\Filament\Resources\Bugs\Schemas\BugForm;
use App\Filament\Resources\Bugs\Schemas\BugInfolist;
use App\Filament\Resources\Bugs\Tables\BugsTable;
use App\Models\Bug;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BugResource extends Resource
{
    protected static ?string $model = Bug::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBugAnt;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Central de Bugs';

    protected static ?string $pluralLabel = 'Central de Bugs';

    protected static ?string $label = 'Central de Bugs';

    protected static ?string $slug = 'central-de-bugs';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return BugForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BugInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BugsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\HistoryRelationManager::class,
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditBug::class,
            Pages\ViewBug::class,

        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBugs::route('/'),
            'create' => CreateBug::route('/criar'),
            'view' => ViewBug::route('/{record}'),
            'edit' => EditBug::route('/{record}/editar'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
