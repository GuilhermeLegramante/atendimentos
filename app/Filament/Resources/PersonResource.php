<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonResource\Pages;
use App\Filament\Resources\PersonResource\RelationManagers;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'segurado';

    protected static ?string $pluralModelLabel = 'segurados';

    protected static ?string $navigationGroup = 'Parâmetros';

    protected static ?string $slug = 'segurado';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('registration')
                    ->label('Inscrição Municipal')
                    ->required()
                    ->unique()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration')
                    ->label('Inscrição Municipal')
                    ->alignCenter()
                    ->searchable(),

                Tables\Columns\TextColumn::make('cpf_cnpj')
                    ->label('CPF ou CNPJ')
                    ->alignCenter()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('Endereço')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Telefone'),

                IconColumn::make('partner')
                    ->label('Conveniado')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->visible(fn() => ! Auth::user()?->view_people) // esconde para os hospitais que só vão ver se está ativo
                    ->boolean(),

                IconColumn::make('patient')
                    ->label('Segurado')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->visible(fn() => ! Auth::user()?->view_people)
                    ->boolean(),

                IconColumn::make('dependent')
                    ->label('Dependente')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->visible(fn() => ! Auth::user()?->view_people)
                    ->boolean(),

                ToggleColumn::make('is_active')
                    ->label('Ativo')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->disabled(fn() => ! Auth::user()?->is_admin),

                ToggleColumn::make('can_edit_values')
                    ->label('Alterar valores')
                    ->alignCenter()
                    ->sortable()
                    ->visible(fn() => ! Auth::user()?->view_people)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->disabled(fn() => ! Auth::user()?->is_admin),

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
                SelectFilter::make('vinculo')
                    ->label('Tipo de Vínculo')
                    ->options([
                        'partner' => 'Conveniado',
                        'patient' => 'Segurado',
                        'dependent' => 'Dependente',
                    ])
                    ->query(function ($query, $state) {
                        if ($state === 'partner') {
                            $query->where('partner', true);
                        } elseif ($state === 'patient') {
                            $query->where('patient', true);
                        } elseif ($state === 'dependent') {
                            $query->where('dependent', true);
                        }
                    }),

                TernaryFilter::make('is_active')
                    ->label('Está Ativo')
                    ->trueLabel('Somente ativos')
                    ->falseLabel('Somente inativos'),

                TernaryFilter::make('can_edit_values')
                    ->label('Pode alterar valores')
                    ->trueLabel('Pode alterar')
                    ->falseLabel('Não pode alterar'),
            ])
            ->actions([
                // ActionGroup::make([
                //     Tables\Actions\EditAction::make(),
                //     Tables\Actions\DeleteAction::make(),
                // ]),
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
            'index' => Pages\ManagePeople::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
