<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Utils\ReportFactory;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function getReceipt($treatmentId)
    {
        $treatment = Treatment::find($treatmentId);

        $fileName = 'COMPROVANTE_DE_ATENDIMENTO_' . $treatment->id . '.pdf';

        $args = [
            'treatment' => $treatment,
            'title' => 'COMPROVANTE DE ATENDIMENTO',
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.receipt', $args, $fileName);
    }
}
