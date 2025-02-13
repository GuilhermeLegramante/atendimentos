<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages\CreateUser;
use App\Filament\Resources\ClientResource\Pages\EditUser;
use App\Filament\Resources\ClientResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Person;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'usuário';

    protected static ?string $pluralModelLabel = 'usuários';

    protected static ?string $navigationGroup = 'Parâmetros';

    protected static ?string $slug = 'usuario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados do Usuário')
                    ->description(
                        fn(string $operation): string => $operation === 'create' || $operation === 'edit' ? 'Informe os campos solicitados' : ''
                    )
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        TextInput::make('username')
                            ->label('Login')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        TextInput::make('email')
                            ->label('E-mail')
                            ->required()
                            ->email(),
                        TextInput::make('password')
                            ->label('Senha')
                            ->password()
                            ->revealable()
                            ->required()
                            ->rule('min:4')
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->same('passwordConfirmation')
                            ->validationAttribute('senha'),
                        TextInput::make('passwordConfirmation')
                            ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
                            ->password()
                            ->required()
                            ->revealable()
                            ->dehydrated(false),
                        Toggle::make('is_admin')
                            ->label('Administrador')
                            ->inline(false)
                            ->onIcon('heroicon-m-bolt')
                            ->offIcon('heroicon-m-user'),
                        Toggle::make('is_manager')
                            ->label('Gerente')
                            ->inline(false)
                            ->onIcon('heroicon-m-bolt')
                            ->offIcon('heroicon-m-user'),
                        Select::make('partners')
                            ->columnSpanFull()
                            ->label('Conveniados')
                            ->multiple()
                            ->relationship(
                                name: 'partners',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('partner', 1),
                            )
                            ->getOptionLabelFromRecordUsing(fn(Person $record) => "{$record->registration} - {$record->name}")
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Login')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                IconColumn::make('is_admin')
                    ->label('Admin')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Editado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/criar'),
            'edit' => EditUser::route('/{record}/editar'),
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
