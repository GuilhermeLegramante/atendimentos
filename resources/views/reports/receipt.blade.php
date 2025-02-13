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
    <table style="width: 100%; margin-top: 1%;" class="striped">
        <thead>
            <tr>
                <th>Data do Atendimento</th>
                <th>Conveniado</th>
                <th>Paciente</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ date('d/m/Y', strtotime($treatment->date)) }}
                </td>
                <td>
                    {{ $treatment->partner->name }}
                </td>
                <td>
                    {{ $treatment->patient->name }}
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <hr>
    <table style="width: 100%; margin-top: 1%;" class="striped">
        <thead>
            <tr style="">
                <th>Serviço</th>
                <th style="width: 30%;">Detalhes</th>
                <th style="width: 5%; text-align:center;">Quantidade</th>
                <th style="width: 10%; text-align:right;">Valor Unitário</th>
                <th style="width: 10%; text-align:right;">Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalValue = 0;
            @endphp
            @foreach ($treatment->providedServices as $providedService)
                @php
                    $totalValue += $providedService->total;
                @endphp
                <tr style="">
                    <td>
                        {{ $providedService->service->code }} -
                        {{ $providedService->service->name }}
                    </td>
                    <td>
                        {{ $providedService->description }}
                    </td>
                    <td style="text-align: center;">
                        {{ $providedService->quantity }}
                    </td>
                    <td style="text-align: right">
                        R$ {{ number_format($providedService->value, 2, ',', '.') }}
                    </td>
                    <td style="text-align: right">
                        R$ {{ number_format($providedService->total, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <!-- Linha do Valor Total -->
            <tr style=" font-weight: bold;">
                <td colspan="4" style="text-align: right;">Valor Total:</td>
                <td>
                    R$ {{ number_format($totalValue, 2, ',', '.') }}
                </td>
            </tr>
            <!-- Linha do Valor Total para o Segurado -->
            <tr style=" font-weight: bold;">
                <td colspan="4" style="text-align: right;">Valor Total para o segurado:</td>
                <td>
                    R$ {{ number_format($treatment->provided_services_sum_patient_value, 2, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>
@endsection

@section('footer')
    @include('reports.footer-with-sign')
@endsection
