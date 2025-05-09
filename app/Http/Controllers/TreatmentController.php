<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\ProvidedService;
use App\Models\Treatment;
use App\Utils\ReportFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function getReceipt($treatmentId)
    {
        $treatment = Treatment::withSum('providedServices', 'patient_value')->find($treatmentId);

        $fileName = 'COMPROVANTE_DE_ATENDIMENTO_' . $treatment->id . '.pdf';

        $args = [
            'treatment' => $treatment,
            'title' => 'COMPROVANTE DE ATENDIMENTO',
        ];

        return ReportFactory::getBasicPdf('landscape', 'reports.receipt', $args, $fileName);
    }

    public function treatmentsReport(Request $request)
    {
        $start = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();
        $end = Carbon::createFromDate($request->year, $request->month, 1)->endOfMonth();
    
        $startDate = $start->format('Y-m-d');
        $endDate = $end->format('Y-m-d');
        $partnerId = $request->partner_id;

        $treatments = Treatment::withSum('providedServices', 'patient_value')
            ->join('people', 'treatments.patient_id', '=', 'people.id')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('partner_id', $partnerId)
            ->orderBy('people.name', 'asc')
            ->get();

        $totalServices = ProvidedService::join('treatments', 'provided_services.treatment_id', '=', 'treatments.id')
            ->whereBetween('treatments.date', [$startDate, $endDate])
            ->where('treatments.partner_id', $partnerId)
            ->selectRaw('SUM(provided_services.value * provided_services.quantity) as total_value')
            ->value('total_value');

        $fileName = 'ATENDIMENTOS_REALIZADOS' . date('Y-m-d') . '.pdf';

        $args = [
            'treatments' => $treatments,
            'title' => 'ATENDIMENTOS REALIZADOS',
            'totalServices' => $totalServices,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.treatments', $args, $fileName);
    }

    public function treatmentsListModel(Request $request)
    {
        $fileName = 'MODELO_RELATÓRIO_DE_CONSULTAS_' . date('Y-m-d') . '.pdf';

        $partnerId = $request->partner_id;

        $person = Person::find($partnerId);

        $args = [
            'title' => 'RELATÓRIO DE CONSULTAS',
            'person' => $person,
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.treatments-list-model', $args, $fileName);


    }

    public function dentalTreatmentGuide(Request $request)
    {
        $fileName = 'GUIA_TRATAMENTO_ODONTOLOGICO_' . date('Y-m-d') . '.pdf';

        $partnerId = $request->partner_id;

        $person = Person::find($partnerId);

        $args = [
            'title' => 'GUIA TRATAMENTO ODONTOLÓGICO',
            'person' => $person,
        ];

        return ReportFactory::getBasicPdf('landscape', 'reports.dental-treatment-guide', $args, $fileName);
    }

}
