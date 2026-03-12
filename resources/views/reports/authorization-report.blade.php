@extends('reports.page')

@section('header')
    @include('reports.header')
@endsection

<style>
    .striped tr:nth-child(even) {
        background-color: #f0f0f0;
    }
</style>

@section('content')
    @if ($authorizations->count() > 0)

        {{-- TABELA DE RESUMO POR CONVENIADO (Sempre visível) --}}
        <h4 style="margin-bottom: 5px;">Resumo por Solicitante</h4>
        <table style="width: 100%; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #eee;">
                    <th style="text-align: left;">Solicitante / Conveniado</th>
                    <th style="width: 20%;">Total de Autorizações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary as $name => $total)
                    <tr>
                        <td>{{ $name }}</td>
                        <td style="text-align: center;">{{ $total }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td style="text-align: right;">TOTAL GERAL:</td>
                    <td style="text-align: center;">{{ $authorizations->count() }}</td>
                </tr>
            </tbody>
        </table>

        {{-- LISTAGEM DETALHADA (Condicional) --}}
        @if ($showDetails)
            <h4 style="margin-bottom: 5px;">Detalhamento das Autorizações</h4>
            <table class="striped">
                <thead>
                    <tr style="background-color:#e0e0e0;">
                        <th style="width: 10%;">Data</th>
                        <th style="width: 25%;">Paciente</th>
                        @if (empty($requester))
                            <th style="width: 25%;">Solicitante</th>
                        @endif
                        <th>Procedimentos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($authorizations as $authorization)
                        <tr>
                            <td style="text-align: center;">{{ optional($authorization->created_at)->format('d/m/y') }}</td>
                            <td>{{ $authorization->patient->name ?? 'Não informado' }}</td>
                            @if (empty($requester))
                                <td>{{ $authorization->partner->name ?? ($authorization->requester_name ?? '—') }}</td>
                            @endif
                            <td>
                                @if ($authorization->services->isNotEmpty())
                                    @foreach ($authorization->services as $service)
                                        • {{ $service->name }}<br>
                                    @endforeach
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <div style="text-align: center; margin-top: 20px;">
            <h4>Sem autorizações registradas.</h4>
        </div>
    @endif
@endsection

@section('footer')
    {{-- @include('reports.footer-with-single-sign') --}}
@endsection
