<?php

namespace App\Filament\Pages;

use App\Models\Treatment;
use App\Utils\ReportFactory;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class TreatmentsReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.treatments-report';

    protected static ?string $title = 'Atendimentos Realizados';

    protected static ?string $slug = 'atendimentos-realizados';

    public $data = [];
    public $partner_id;
    public $year;
    public $month;

    public $address;
    public $phone;

    public function mount()
    {
        $ano = now()->year;
        $mes = now()->month;

        $this->year = $ano;
        $this->month = str_pad($mes, 2, '0', STR_PAD_LEFT);
    }


    public static function getNavigationGroup(): ?string
    {
        return 'Relatórios';
    }

    protected function getFormSchema(): array
    {
        $currentYear = now()->year;
        $currentMonth = now()->format('m');

        return [
            Section::make('Período')
                ->description('Selecione o mês e o ano. Serão considerados os atendimentos lançados do primeiro ao último dia do mês selecionado.')
                ->columns(2)
                ->schema([
                    Select::make('year')
                        ->label('Ano')
                        ->options([
                            $currentYear => $currentYear,
                            $currentYear - 1 => $currentYear - 1,
                            $currentYear - 2 => $currentYear - 2,
                        ])
                        ->default($currentYear)
                        ->required()
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
                        ->default($currentMonth)
                        ->required()
                        ->reactive(),

                    Select::make('partner_id')
                        ->columnSpanFull()
                        ->label('Conveniado (profissional ou empresa que realizou o atendimento)')
                        ->options(function () {
                            return DB::table('people')
                                ->join('user_people', 'user_people.person_id', '=', 'people.id')
                                ->where('partner', 1)
                                ->where('user_people.user_id', auth()->user()->id)
                                ->select('people.id', DB::raw("CONCAT(people.registration, ' - ', people.name) as label"))
                                ->pluck('label', 'people.id');
                        })
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $partner = DB::table('people')->where('id', $state)->first();
                            $set('address', $partner->address ?? '');
                            $set('phone', $partner->phone ?? '');
                        }),

                    TextInput::make('address')
                        ->label('Endereço do Conveniado')
                        ->columnSpanFull()
                        ->required()
                        ->visible(fn($get) => filled($get('partner_id'))),

                    PhoneNumber::make('phone')
                        ->label('Telefone para Contato')
                        ->columnSpanFull()
                        ->required()
                        ->visible(fn($get) => filled($get('partner_id'))),
                ])
        ];
    }

    public function submit($definitive = true)
    {
        $data = $this->form->getState();

        $data['definitive'] = $definitive;

        // Atualiza os dados do conveniado
        if (!empty($data['partner_id'])) {
            DB::table('people')
                ->where('id', $data['partner_id'])
                ->update([
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                ]);
        }

        return redirect()->route('treatments-report-pdf', $data);
    }

    // Quero que esse botão apareça embaixo e não no header
    protected function getActions(): array
    {
        return [
            // Action::make('Gerar Relatório p/ Conferência')
            //     ->label('Gerar Relatório p/ Conferência')
            //     ->requiresConfirmation()
            //     ->modalHeading('Atenção!')
            //     ->modalDescription('Este relatório é para simples conferência. Para gerar o relatório a ser entregue clique em "Gerar Relatório".')
            //     ->action(fn() => $this->submit(false)),
            Action::make('Gerar Relatório')
                ->label('Gerar Relatório')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Atenção!')
                ->modalDescription('Após a geração do relatório, não será mais possível editar ou excluir atendimentos lançados no mês.')
                ->action(fn() => $this->submit()),
        ];
    }
}
