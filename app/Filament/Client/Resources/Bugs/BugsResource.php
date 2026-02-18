<?php

namespace App\Filament\Client\Resources\Bugs;

use App\Filament\Client\Resources\Bugs\Pages\CreateBugs;
use App\Filament\Client\Resources\Bugs\Pages\EditBugs;
use App\Filament\Client\Resources\Bugs\Pages\ListBugs;
use App\Filament\Client\Resources\Bugs\Pages\ViewBugs;
use App\Filament\Client\Resources\Bugs\Schemas\BugsForm;
use App\Filament\Client\Resources\Bugs\Schemas\BugsInfolist;
use App\Filament\Client\Resources\Bugs\Tables\BugsTable;
use App\Models\Bug;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BugsResource extends Resource
{
    protected static ?string $model = Bug::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBugAnt;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Reportar Bugs';

    protected static ?string $pluralLabel = 'Reportar Bugs';

    protected static ?string $label = 'Reportar Bugs';

    protected static ?string $slug = 'reportar-bugs';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return BugsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BugsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BugsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('company_id', auth()->user()->company_id);
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
            'index' => ListBugs::route('/'),
            'create' => CreateBugs::route('/create'),
            'view' => ViewBugs::route('/{record}'),
            'edit' => EditBugs::route('/{record}/edit'),
        ];
    }
}
