<?php

namespace App\Utils;

use Barryvdh\DomPDF\PDF;

class ReportFactory
{
    /**
     * @param $orientation (landscape ou portrait)
     * @param $view (View do PDF)
     * @param $args (Argumentos para a view do PDF)
     * @param $fileName (Nome do arquivo PDF);
     */
    public static function getBasicPdf($orientation, $view, $args, $fileName)
    {
        $pdf = app('dompdf.wrapper');

        $pdf->loadView($view, $args);

        // $pdf->getDomPDF()->get_canvas()->page_text(490, 25, "Página: {PAGE_NUM}/{PAGE_COUNT}", null, 8);

        return $pdf->setPaper('a4', $orientation)->stream($fileName);
    }
}
