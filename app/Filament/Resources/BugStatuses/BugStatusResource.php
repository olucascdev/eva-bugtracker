<?php

namespace App\Filament\Resources\BugStatuses;

use App\Filament\Resources\BugStatuses\Pages\CreateBugStatus;
use App\Filament\Resources\BugStatuses\Pages\EditBugStatus;
use App\Filament\Resources\BugStatuses\Pages\ListBugStatuses;
use App\Filament\Resources\BugStatuses\Schemas\BugStatusForm;
use App\Filament\Resources\BugStatuses\Tables\BugStatusesTable;
use App\Models\BugStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BugStatusResource extends Resource
{
    protected static ?string $model = BugStatus::class;

     protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Status de Bugs';

    protected static ?string $pluralLabel = 'Status de Bugs';

    protected static ?string $label = 'Status de Bugs';

    protected static ?string $slug = 'status-de-bugs';

    protected static ?int $navigationSort = 3;


    public static function form(Schema $schema): Schema
    {
        return BugStatusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BugStatusesTable::configure($table);
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
            'index' => ListBugStatuses::route('/'),
           // 'create' => CreateBugStatus::route('/create'),
           //'edit' => EditBugStatus::route('/{record}/edit'),
        ];
    }
}
