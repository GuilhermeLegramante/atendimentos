<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class TreatmentsListModelPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.treatments-list-model-page';

    protected static ?string $title = 'Modelo Relatório Consultas';

    protected static ?string $slug = 'modelo-relatorio-consultas';

    public $data = [];
    public $partner_id;

    public static function getNavigationGroup(): ?string
    {
        return 'Relatórios';
    }

    protected function getFormSchema(): array
    {
        return [
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
        return redirect()->route('treatments-list-model-pdf', $data);
    }


}