<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorizationResource\Pages;
use App\Models\Authorization;
use App\Models\Person;
use App\Models\Service;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;

class AuthorizationResource extends Resource
{
    protected static ?string $model = Authorization::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $modelLabel = 'autorização';

    protected static ?string $pluralModelLabel = 'autorizações';

    protected static ?string $slug = 'autorizacao';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('partner_id')
                ->columnSpanFull()
                ->label('Conveniado')
                ->relationship(
                    name: 'partner',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn(Builder $query) => $query
                        ->join('user_people', 'user_people.person_id', 'people.id')
                        ->where('partner', 1)
                        ->where('user_people.user_id', auth()->user()->id)
                        ->select('people.id', 'people.registration', 'people.name'),
                )
                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->cpf_cnpj} - {$record->name}"),

            Select::make('patient_id')
                ->reactive()
                ->label('Paciente')
                ->relationship('patient', 'name')
                ->relationship(
                    name: 'patient',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn(Builder $query) => $query->where('is_active', 1)
                        ->where(function ($query) {
                            $query->where('patient', 1)->orWhere('dependent', 1);
                        })
                )
                ->columnSpanFull()
                ->getOptionLabelFromRecordUsing(fn(Person $record) => "{$record->cpf_cnpj} - {$record->name}")
                ->required(),

            Forms\Components\Select::make('services')
                ->label('Serviços')
                ->multiple()
                ->relationship('services', 'name')
                ->getOptionLabelFromRecordUsing(fn(Service $record) => "{$record->code} - {$record->name}")
                ->required()
                ->columnSpanFull()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $currentServices = collect($get('services_selected') ?? []);
                    $newServices = collect(self::calculateServices($state, $get('patient_id')));

                    // Mantém o status atual sempre que possível
                    $merged = $newServices->map(function ($service) use ($currentServices) {
                        $existing = $currentServices->firstWhere('service_id', $service['service_id']);
                        if ($existing) {
                            $service['status'] = $existing['status']; // preserva status alterado pelo usuário
                        }
                        return $service;
                    });

                    $set('services_selected', $merged->toArray());
                })
                ->afterStateHydrated(function ($state, callable $set, $get) {
                    // Preenche ao abrir o edit
                    $set('services_selected', AuthorizationResource::calculateServices($state, $get('patient_id')));
                }),

            Forms\Components\Repeater::make('services_selected')
                ->addable(false)
                ->label('Serviços Selecionados')
                ->default(fn($get, $record) => self::calculateServices(
                    $record ? $record->services->pluck('id')->toArray() : [],
                    $record?->patient_id
                ))
                ->schema([
                    Forms\Components\TextInput::make('service_name')
                        ->label('Serviço')
                        ->columnSpanFull()
                        ->disabled(),

                    Forms\Components\TextInput::make('waiting_days')
                        ->label('Dias de Carência')
                        ->hint('Dias de carência configurados para o serviço')
                        ->disabled(),

                    Forms\Components\TextInput::make('days_remaining')
                        ->label('Dias Restantes')
                        ->hint('Dias restantes para o fim da carência')
                        ->disabled(), // Pode deixar habilitado se quiser edição

                    Forms\Components\TextInput::make('last_service_date')
                        ->label('Último atendimento')
                        ->hint('Data do último atendimento do paciente neste serviço')
                        ->disabled(), // Pode deixar habilitado se quiser edição

                    Forms\Components\Toggle::make('status')
                        ->label('Status')
                        ->default(fn($get) => $get('status')) // <-- garante pegar o valor inicial
                        ->onColor('success')
                        ->offColor('danger')
                        ->inline(false)
                        ->helperText('Ative para autorizar este serviço mesmo durante carência'),
                ])
                ->columns(2)
                ->columnSpanFull()
                ->hidden(fn($get) => empty($get('services_selected')))
                ->dehydrated(),

            Forms\Components\Textarea::make('observations')
                ->label('Observações')
                ->rows(4)
                ->columnSpanFull(),
        ]);
    }

    public static function calculateServices(array $serviceIds, ?int $patientId): array
    {
        if (! $patientId) return [];

        return collect($serviceIds)->map(function ($id) use ($patientId) {
            $service = \App\Models\Service::find($id);
            if (! $service) return [];

            $lastProvidedService = \App\Models\ProvidedService::whereHas('treatment', function ($query) use ($patientId) {
                $query->where('patient_id', $patientId);
            })
                ->where('service_id', $service->id)
                ->orderByDesc('created_at')
                ->first();

            $canAuthorize = true;
            $daysRemaining = 0;
            $lastDate = null;

            if ($lastProvidedService) {
                $lastDate = $lastProvidedService->created_at;
                $daysSinceLast = now()->diffInDays($lastDate);
                $daysRemaining = $service->waiting_days - $daysSinceLast;
                $canAuthorize = $daysSinceLast >= $service->waiting_days;
            }

            return [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'waiting_days' => $service->waiting_days,
                'status' => $canAuthorize,
                'days_remaining' => $daysRemaining > 0 ? $daysRemaining : 0,
                'last_service_date' => $lastDate ? $lastDate->format('d/m/Y') : null,
            ];
        })->toArray();
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->label('ID'),

                TextColumn::make('user.name')
                    ->label('Criado por')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('patient.name')
                    ->label('Paciente'),

                TextColumn::make('partner.name')
                    ->label('Conveniado')
                    ->default('-'),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->label('Editado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('partner_id')
                    ->label('Conveniado')
                    ->searchable()
                    ->relationship(
                        name: 'partner',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query
                            ->join('user_people', 'user_people.person_id', 'people.id')
                            ->where('partner', 1)
                            ->where('user_people.user_id', auth()->user()->id)
                            ->select('people.id', 'people.registration', 'people.name'),
                    )
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->cpf_cnpj} - {$record->name}"),

                Tables\Filters\SelectFilter::make('patient_id')
                    ->label('Paciente')
                    ->searchable()
                    ->relationship(
                        name: 'patient',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query
                            ->where('is_active', 1)
                            ->where(function ($query) {
                                $query->where('patient', 1)->orWhere('dependent', 1);
                            })
                    )
                    ->getOptionLabelFromRecordUsing(fn(Person $record) => "{$record->cpf_cnpj} - {$record->name}"),

                Tables\Filters\Filter::make('created_at')
                    ->label('Período')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('De'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['created_from'] ?? null, fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'] ?? null, fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->defaultSort('id', 'desc') // Ordenar por ID desc
            ->actions([
                ActionGroup::make([
                    // Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Action::make('report')
                        ->label('Gerar PDF')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(fn(Authorization $record): string => route('authorization-pdf', $record->id))
                        ->openUrlInNewTab(),
                    Tables\Actions\DeleteAction::make(),
                ])->label('Ações')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('gray'),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuthorizations::route('/'),
            'create' => Pages\CreateAuthorization::route('/criar'),
            'edit' => Pages\EditAuthorization::route('/{record}/editar'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
