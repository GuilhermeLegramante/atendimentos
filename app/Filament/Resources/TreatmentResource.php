<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreatmentResource\Pages;
use App\Filament\Tables\Columns\ReceiptLink;
use App\Models\Treatment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Leandrocfe\FilamentPtbrFormFields\Money;

class TreatmentResource extends Resource
{
    protected static ?string $model = Treatment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'service.name';

    protected static ?string $modelLabel = 'atendimento';

    protected static ?string $pluralModelLabel = 'atendimentos';

    protected static ?string $navigationGroup = 'Parâmetros';

    protected static ?string $slug = 'atendimento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Data do Atendimento')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Select::make('service_id')
                    ->label('Serviço')
                    ->relationship('service', 'name')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Select::make('patient_id')
                    ->label('Paciente')
                    ->relationship('patient', 'name')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Select::make('partner_id')
                    ->columnSpanFull()
                    ->label('Conveniado')
                    ->relationship('partner', 'name')
                    ->required(),
                Money::make('value')
                    ->label('Valor'),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantidade')
                    ->numeric(),
                Forms\Components\FileUpload::make('receipt')
                    ->columnSpanFull()
                    ->label('Comprovante')
                    ->previewable()
                    ->openable()
                    ->downloadable()
                    ->moveFiles()
                    ->imageEditor()
                    ->imageEditorEmptyFillColor('#000000')
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Serviço')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Conveniado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Valor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantidade')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Data')
                    ->date()
                    ->sortable(),
                ReceiptLink::make('receipt')
                    ->label('Comprovante')
                    ->alignment(Alignment::Center),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTreatments::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
