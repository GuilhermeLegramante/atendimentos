<?php

namespace App\Http\Controllers;

use App\Models\Authorization;
use App\Models\Person;
use App\Utils\ReportFactory;
use Carbon\Carbon;
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
        [$type, $value] = explode(':', $request->get('requester'), 2);

        $startDate  = $request->get('start_date');
        $finishDate = $request->get('finish_date');

        $fileName = 'RELATORIO_DE_AUTORIZACOES_' . date('Y-m-d') . '.pdf';

        $query = Authorization::with(['partner', 'patient', 'services'])
            ->orderBy('created_at');

        // 🔹 Filtro por solicitante
        if ($type === 'partner') {
            $query->where('partner_id', $value);
        }

        if ($type === 'manual') {
            $query->whereNull('partner_id')
                ->where('requester_name', $value);
        }

        // 📅 Filtro por período
        if ($startDate && $finishDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($finishDate)->endOfDay(),
            ]);
        }

        $authorizations = $query->get();

        $args = [
            'title'          => 'RELATÓRIO DE AUTORIZAÇÕES',
            'authorizations' => $authorizations,
            'type'           => $type,
            'startDate'      => $startDate,
            'finishDate'     => $finishDate,
        ];

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.authorization-report',
            $args,
            $fileName
        );
    }
}
