<?php

namespace App\Http\Controllers;

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

        return ReportFactory::getBasicPdf('portrait', 'reports.receipt', $args, $fileName);
    }

    public function treatmentsReport(Request $request)
    {
        // $treatments = Treatment::withSum('providedServices', 'patient_value')->get();
        // $startOfMonth = Carbon::now()->startOfMonth();
        // $endOfMonth = Carbon::now()->endOfMonth();
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $partnerId = $request->partner_id;

        $treatments = Treatment::withSum('providedServices', 'patient_value')
            ->join('people', 'treatments.patient_id', '=', 'people.id')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('partner_id', $partnerId)
            ->orderBy('people.name', 'asc')
            ->get();


        // $totalServices = ProvidedService::selectRaw('SUM(value * quantity) as value')->value('value');

        $totalServices = ProvidedService::join('treatments', 'provided_services.treatment_id', '=', 'treatments.id')
            ->whereBetween('treatments.date', [$startDate, $endDate])
            ->where('treatments.partner_id', $partnerId)
            ->selectRaw('SUM(provided_services.value * provided_services.quantity) as total_value')
            ->value('total_value');

        $fileName = 'ATENDIMENTOS_REALIZADOS.pdf';

        $args = [
            'treatments' => $treatments,
            'title' => 'ATENDIMENTOS REALIZADOS',
            'totalServices' => $totalServices,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.treatments', $args, $fileName);
    }
}
