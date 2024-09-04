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

        $treatments = Treatment::withSum('providedServices', 'patient_value')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();


        // $totalServices = ProvidedService::selectRaw('SUM(value * quantity) as value')->value('value');

        $totalServices = ProvidedService::join('treatments', 'provided_services.treatment_id', '=', 'treatments.id')
            ->whereBetween('treatments.date', [$startDate, $endDate])
            ->selectRaw('SUM(provided_services.value * provided_services.quantity) as total_value')
            ->value('total_value');

        $fileName = 'ATENDIMENTOS_REALIZADOS.pdf';

        $args = [
            'treatments' => $treatments,
            'title' => 'ATENDIMENTOS REALIZADOS',
            'totalServices' => $totalServices,
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.treatments', $args, $fileName);
    }
}
