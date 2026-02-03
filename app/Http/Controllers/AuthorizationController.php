<?php

namespace App\Http\Controllers;

use App\Models\Authorization;
use App\Models\Person;
use App\Utils\ReportFactory;
use Illuminate\Http\Request;

class AuthorizationController extends Controller
{
    public function getPdf($id)
    {
        $authorization = Authorization::with('services')->find($id);

        $fileName = 'GUIA_DE_AUTORIZAÇÃO_' . $authorization->id . '.pdf';

        $args = [
            'authorization' => $authorization,
            'title' => 'GUIA DE AUTORIZAÇÃO SISPREM',
        ];

        return ReportFactory::getBasicPdf('landscape', 'reports.authorization', $args, $fileName);
    }

    public function getReport(Request $request)
    {
        $type  = $request->get('type');
        $value = $request->get('value');

        $fileName = 'RELATORIO_DE_AUTORIZACOES_' . date('Y-m-d') . '.pdf';

        $query = Authorization::with('partner')
            ->orderBy('created_at');

        if ($type === 'partner') {
            $query->where('partner_id', $value);
        }

        if ($type === 'manual') {
            $query->whereNull('partner_id')
                ->where('requester_name', $value);
        }

        $authorizations = $query->get();

        $args = [
            'title' => 'RELATÓRIO DE AUTORIZAÇÕES',
            'authorizations' => $authorizations,
        ];

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.authorization-report',
            $args,
            $fileName
        );
    }
}
