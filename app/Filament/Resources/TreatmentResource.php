<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages\CreateTreatment;
use App\Filament\Resources\ClientResource\Pages\EditTreatment;
use App\Filament\Resources\ClientResource\Pages\ListTreatments;
use App\Filament\Resources\TreatmentResource\Pages;
use App\Filament\Tables\Columns\ReceiptLink;
use App\Models\Person;
use App\Models\Service;
use App\Models\Treatment;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Grouping\Group;

class TreatmentResource extends Resource
{
    protected static ?string $model = Treatment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'service.name';

    protected static ?string $modelLabel = 'atendimento';

    protected static ?string $pluralModelLabel = 'atendimentos';

    // protected static ?string $navigationGroup = 'Parâmetros';

    protected static ?string $slug = 'atendimento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados do Atendimento')
                    ->description(
                        fn (string $operation): string => $operation === 'create' || $operation === 'edit' ? 'Informe os campos solicitados' : ''
                    )
                    ->schema([
                        DatePicker::make('date')
                            ->label('Data do Atendimento')
                            ->afterStateHydrated(function (DatePicker $component, $state, string $operation) {
                                if ($operation === 'create') {
                                    $component->state(date('Y-m-d'));
                                }
                            })
                            ->required(),
                        Select::make('partner_id')
                            ->columnSpanFull()
                            ->label('Conveniado')
                            ->relationship(
                                name: 'partner',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->join('user_people', 'user_people.person_id', 'people.id')
                                    ->where('partner', 1)
                                    ->where('user_people.user_id', auth()->user()->id)
                                    ->select('people.id', 'people.registration', 'people.name'),
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->registration} - {$record->name}")
                            ->required(),
                        Select::make('patient_id')
                            ->label('Paciente')
                            ->relationship('patient', 'name')
                            ->relationship(
                                name: 'patient',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('patient', 1)->orWhere('dependent', 1),
                            )
                            ->columnSpanFull()
                            ->getOptionLabelFromRecordUsing(fn (Person $record) => "{$record->registration} - {$record->name}")
                            ->required(),
                        Repeater::make('providedServices')
                            ->relationship('providedServices')
                            ->schema([
                                Select::make('service_id')
                                    ->label('Serviço')
                                    ->relationship('service', 'name')
                                    ->columnSpanFull()
                                    ->getOptionLabelFromRecordUsing(fn (Service $record) => "{$record->code} - {$record->name}")
                                    ->required(),
                                TextInput::make('value')
                                    ->numeric()
                                    ->live()
                                    ->label('Valor Unitário'),
                                TextInput::make('quantity')
                                    ->label('Quantidade')
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        $total = (float) $get('value') * (float) $get('quantity');
                                        $set('total_value', $total);
                                    })
                                    ->numeric()
                                    ->minValue(1),
                                TextInput::make('total_value')
                                    ->numeric()
                                    ->readOnly()
                                    ->live()
                                    ->label('Valor Total'),
                            ])
                            ->columnSpanFull()
                            ->columns(3)
                            ->label('Serviços prestados'),
                        FileUpload::make('receipt')
                            ->visibleOn('edit')
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
                        Toggle::make('ok')
                            ->label('Auditado')
                            ->visible(
                                fn (string $operation): string => $operation === 'edit' && auth()->user()->is_admin
                            )
                            ->inline(false),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => auth()->user()->is_admin ? $query : $query->where('user_id', auth()->user()->id))
            ->columns([
                TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('partner.name')
                    ->label('Conveniado')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuário')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Data')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->date()
                    ->sortable(),
                ReceiptLink::make('receipt')
                    ->label('Comprovante')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->alignment(Alignment::Center),
                IconColumn::make('ok')
                    ->label('Auditado')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Editado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->groups([
                Group::make('partner.name')
                    ->label('Conveniado')
                    ->collapsible(),
                Group::make('patient.name')
                    ->label('Paciente')
                    ->collapsible(),
                Group::make('user.name')
                    ->label('Usuário')
                    ->collapsible(),
                Group::make('ok')
                    ->label('Auditado')
                    ->collapsible(),
            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn (Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    // ->mutateFormDataUsing(function (array $data): array {
                    //     $data['value'] = $data['value'] / 10;
                    //     return $data;
                    // }),
                    Action::make('report')
                        ->label('Comprovante para Ass.')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(fn (Treatment $record): string => route('receipt-pdf', $record->id))
                        ->openUrlInNewTab(),
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
            'index' => ListTreatments::route('/'),
            'create' => CreateTreatment::route('/criar'),
            'edit' => EditTreatment::route('/{record}/editar'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()->is_admin) {
            return static::getModel()::count();
        } else {
            return static::getModel()::where('user_id', auth()->user()->id)->count();
        }
    }
}
