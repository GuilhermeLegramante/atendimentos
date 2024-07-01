<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SignalResource\Pages;
use App\Filament\Resources\SignalResource\RelationManagers;
use App\Models\Signal;
use App\Tables\Columns\FileLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SignalResource extends Resource
{
    protected static ?string $model = Signal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Marca & Sinal';

    protected static ?string $modelLabel = 'sinal';

    protected static ?string $pluralModelLabel = 'sinais';

    protected static ?string $slug = 'sinal';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('path')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('ok')
                    ->label('Cadastrado')
                    ->inline(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->groupBy('name')->havingRaw('COUNT(*) > 1'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome do Produtor')
                    ->searchable(),
                FileLink::make('path')
                    ->label('Sinal'),
                Tables\Columns\ToggleColumn::make('ok')
                    ->sortable()
                    ->onColor('success')
                    ->offColor('danger')
                    ->label('Cadastrado'),
            ])
            ->filters([
                //
            ])
            ->groups([
                Group::make('name')
                    ->label('Produtor')
                    ->collapsible(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSignals::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('ok', 1)->count();
    }
}
