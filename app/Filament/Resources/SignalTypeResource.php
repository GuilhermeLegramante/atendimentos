<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SignalTypeResource\Pages;
use App\Filament\Resources\SignalTypeResource\RelationManagers;
use App\Models\SignalType;
use App\Tables\Columns\SignalTypeImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SignalTypeResource extends Resource
{
    protected static ?string $model = SignalType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'descricao';

    protected static ?string $modelLabel = 'tipo de sinal';

    protected static ?string $navigationGroup = 'Marca & Sinal';

    protected static ?string $pluralModelLabel = 'tipos de sinais';

    protected static ?string $slug = 'tipo-de-sinal';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Tipo de Sinal')
                    ->searchable(),
                SignalTypeImage::make('url')
                    ->label('Desenho'),
            ])
            ->filters([
                //
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
            'index' => Pages\ManageSignalTypes::route('/'),
        ];
    }
}
