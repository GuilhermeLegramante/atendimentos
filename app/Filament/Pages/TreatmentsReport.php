<?php

namespace App\Filament\Pages;

use App\Models\Treatment;
use App\Utils\ReportFactory;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TreatmentsReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.treatments-report';

    protected static ?string $title = 'Atendimentos Realizados';

    protected static ?string $slug = 'atendimentos-realizados';

    public $data = [];
    public $start_date;
    public $end_date;
    public $partner_id;

    public static function getNavigationGroup(): ?string
    {
        return 'Relatórios';
    }

    protected function getFormSchema(): array
    {
        $anoAtual = now()->year;
        $mesAtual = now()->format('m');

        return [
            Select::make('year')
                ->label('Ano')
                ->options([
                    $anoAtual => $anoAtual,
                    $anoAtual - 1 => $anoAtual - 1,
                    $anoAtual - 2 => $anoAtual - 2,
                ])
                ->default($anoAtual)
                ->reactive(),

            Select::make('month')
                ->label('Mês')
                ->options([
                    '01' => 'Janeiro',
                    '02' => 'Fevereiro',
                    '03' => 'Março',
                    '04' => 'Abril',
                    '05' => 'Maio',
                    '06' => 'Junho',
                    '07' => 'Julho',
                    '08' => 'Agosto',
                    '09' => 'Setembro',
                    '10' => 'Outubro',
                    '11' => 'Novembro',
                    '12' => 'Dezembro',
                ])
                ->default($mesAtual)
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $year = $get('year') ?? now()->year;

                    $start = Carbon::createFromDate($year, $state, 1)->startOfMonth();
                    $end = Carbon::createFromDate($year, $state, 1)->endOfMonth();

                    $set('start_date', $start->format('Y-m-d'));
                    $set('end_date', $end->format('Y-m-d'));
                }),

            DatePicker::make('start_date')
                ->label('Data Inicial')
                ->disabled()
                ->reactive(),

            DatePicker::make('end_date')
                ->label('Data Final')
                ->disabled()
                ->reactive(),
            Select::make('partner_id')
                ->columnSpanFull()
                ->label('Conveniado')
                ->options(function () {
                    return DB::table('people')
                        ->join('user_people', 'user_people.person_id', '=', 'people.id')
                        ->where('partner', 1)
                        ->where('user_people.user_id', auth()->user()->id)
                        ->select('people.id', DB::raw("CONCAT(people.registration, ' - ', people.name) as label"))
                        ->pluck('label', 'people.id');
                })
                ->required()
                ->getOptionLabelUsing(fn($value) => DB::table('people')
                    ->where('id', $value)
                    ->select(DB::raw("CONCAT(people.registration, ' - ', people.name) as label"))
                    ->first()->label),
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();
        return redirect()->route('treatments-report-pdf', $data);
    }


    public function mount()
    {
        // return Redirect::route('treatments-report-pdf');
    }
}
