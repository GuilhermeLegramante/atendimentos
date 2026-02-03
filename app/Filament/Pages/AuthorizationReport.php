<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class AuthorizationReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static string $view = 'filament.pages.authorization-report';

    protected static ?string $title = 'Relatório de Autorizações';

    protected static ?string $slug = 'relatorio-de-autorizacoes';

    public $data = [];
    public $requester;
    public $start_date;
    public $finish_date;

    public static function getNavigationGroup(): ?string
    {
        return 'Relatórios';
    }

    public function mount()
    {
        $this->start_date = Carbon::now()->startOfMonth()->toDateString();
        $this->finish_date = Carbon::today()->toDateString();
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('requester')
                ->columnSpanFull()
                ->label('Solicitante / Conveniado')
                ->options(function () {

                    // 1️⃣ Conveniados que já tiveram autorizações
                    $partners = DB::table('authorizations')
                        ->join('people', 'people.id', '=', 'authorizations.partner_id')
                        ->whereNotNull('authorizations.partner_id')
                        ->where('people.partner', 1)
                        ->select(
                            DB::raw("CONCAT('partner:', people.id) as value"),
                            DB::raw("CONCAT(people.registration, ' - ', people.name) as label")
                        )
                        ->distinct()
                        ->orderBy('people.name')
                        ->pluck('label', 'value')
                        ->toArray();

                    // 2️⃣ Solicitantes não conveniados
                    $manualRequesters = DB::table('authorizations')
                        ->whereNull('partner_id')
                        ->whereNotNull('requester_name')
                        ->select(
                            DB::raw("CONCAT('manual:', requester_name) as value"),
                            'requester_name as label'
                        )
                        ->distinct()
                        ->orderBy('requester_name')
                        ->pluck('label', 'value')
                        ->toArray();

                    return [
                        'Conveniados' => $partners,
                        'Solicitantes não conveniados' => $manualRequesters,
                    ];
                })
                ->required()
                ->searchable(),

            DatePicker::make('start_date')
                ->label('Data inicial')
                ->required()
                ->maxDate(now()),

            DatePicker::make('finish_date')
                ->label('Data final')
                ->required()
                ->maxDate(now())
                ->afterOrEqual('start_date'),
        ];
    }


    public function submit()
    {
        $data = $this->form->getState();

        return redirect()->route('authorization-report-pdf', $data);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }
}
