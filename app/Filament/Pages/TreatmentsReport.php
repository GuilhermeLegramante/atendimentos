<?php

namespace App\Filament\Pages;

use App\Models\Treatment;
use App\Utils\ReportFactory;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Redirect;

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

    public static function getNavigationGroup(): ?string
    {
        return 'Relatórios';
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('start_date')
                ->label('Data Inicial')
                ->required()
                ->placeholder('Selecione a data inicial'),
            DatePicker::make('end_date')
                ->label('Data Final')
                ->required()
                ->placeholder('Selecione a data final'),
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
                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->registration} - {$record->name}")
                ->required()
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
