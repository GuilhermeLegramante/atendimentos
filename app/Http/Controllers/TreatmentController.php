<?php

namespace App\Http\Controllers;

use App\Models\ProvidedService;
use App\Models\Treatment;
use App\Utils\ReportFactory;
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

    public function treatmentsReport()
    {
        $treatments = Treatment::withSum('providedServices', 'patient_value')->get();

        $totalServices = ProvidedService::selectRaw('SUM(value * quantity) as total')->value('total');

        $fileName = 'ATENDIMENTOS_REALIZADOS.pdf';

        $args = [
            'treatments' => $treatments,
            'title' => 'ATENDIMENTOS REALIZADOS',
            'totalServices' => $totalServices,
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.treatments', $args, $fileName);
    }
}
