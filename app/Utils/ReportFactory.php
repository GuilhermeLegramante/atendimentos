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

        // Renderizar o PDF antes de acessar o canvas
        $pdf->render();

        // Obter altura da página
        $canvas = $pdf->getDomPDF()->getCanvas();
        $height = $canvas->get_height();

        // Adicionar número de páginas no rodapé
        $canvas->page_text(
            490,                    // Posição X (ajuste se necessário para centralizar)
            $height - 12,           // Posição Y, próximo ao rodapé
            "Página: {PAGE_NUM}/{PAGE_COUNT}",
            null,                   // Fonte padrão
            6                       // Tamanho da fonte
        );
        
        return $pdf->setPaper('a4', $orientation)->stream($fileName);
    }
}
