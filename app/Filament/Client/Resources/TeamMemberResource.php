<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\TeamMemberResource\Pages;
use App\Models\Role;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamMemberResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Membros da Equipe';

    protected static ?string $modelLabel = 'Membro da Equipe';

    protected static ?string $slug = 'team-members';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('company_id', auth()->user()->company_id)
            ->whereHas('role', function ($query) {
                $query->whereIn('name', ['client-admin', 'client-user']);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                Select::make('role_id')
                    ->label('Permissão')
                    ->options(function () {
                        return Role::whereIn('name', ['client-user'])
                            ->pluck('description', 'id');
                    })
                    ->default(function () {
                        return Role::where('name', 'client-user')->first()?->id;
                    })
                    ->required(),
                TextInput::make('password')
                    ->label('Senha')
                    ->default(fn () => Str::password(12))
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->formatStateUsing(fn ($state, string $operation) => $operation === 'view' ? '********' : $state)
                    ->helperText(fn (string $operation) => $operation === 'create' ? 'Copie a senha agora. Essa é a única vez que ela será visível.' : 'Senha oculta. Deixe vazio para manter a atual.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('role.name')
                    ->label('Permissão')
                    ->badge()
                    ->colors([
                        'primary' => 'client-user',
                        'warning' => 'client-admin',
                    ]),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('resetPassword')
                        ->label('Gerar Nova Senha')
                        ->icon('heroicon-o-key')
                        ->requiresConfirmation()
                        ->action(function (User $record) {
                            $password = Str::password(12);
                            $record->update([
                                'password' => Hash::make($password),
                            ]);

                            Notification::make()
                                ->title('Senha gerada com sucesso')
                                ->body('A nova senha é: '.$password)
                                ->persistent()
                                ->success()
                                ->send();
                        }),
                ]),

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTeamMembers::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role->name === 'client-admin';
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role->name === 'client-admin';
    }

    public static function canAccess(): bool
    {
        // Deprecated or incorrect method name, keeping it for safety if panel uses it, but relying on canViewAny
        return static::canViewAny();
    }
}
