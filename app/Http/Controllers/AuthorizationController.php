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
        $requesterRaw = $request->get('requester');
        $type = null;
        $value = null;

        // 1️⃣ Só tenta separar se houver um valor selecionado
        if (!empty($requesterRaw)) {
            [$type, $value] = explode(':', $requesterRaw, 2);
        }

        $startDate  = $request->get('start_date');
        $finishDate = $request->get('finish_date');

        $fileName = 'RELATORIO_DE_AUTORIZACOES_' . date('Y-m-d') . '.pdf';

        $query = Authorization::with(['partner', 'patient', 'services'])
            ->orderBy('created_at');

        // 2️⃣ Lógica de Filtro por Solicitante (Dinâmica)
        if ($type === 'partner') {
            $query->where('partner_id', $value);
        } elseif ($type === 'manual') {
            $query->whereNull('partner_id')
                ->where('requester_name', $value);
        }
        // Se $type for null, ele pula os filtros e traz todos os registros

        // 📅 Filtro por período
        if ($startDate && $finishDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($finishDate)->endOfDay(),
            ]);
        }

        $authorizations = $query->get();

        // Criar o resumo agrupado
        $summary = $authorizations->groupBy(function ($item) {
            return $item->partner->name ?? ($item->requester_name ?? 'Outros');
        })->map->count();

        $args = [
            'title'          => 'RELATÓRIO DE AUTORIZAÇÕES',
            'authorizations' => $authorizations,
            'summary'        => $summary, // Novo dado
            'showDetails'    => $request->boolean('show_details'), // Novo filtro
            'type'           => $type,
            'requester'      => $requesterRaw,
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
