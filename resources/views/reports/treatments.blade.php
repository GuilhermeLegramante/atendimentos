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
    @if (count($treatments) > 0)
        <h1>
            Atendimentos realizados de {{ date('d/m/Y', strtotime($startDate)) }} a {{ date('d/m/Y', strtotime($endDate)) }}
        </h1>
        <hr>
        <br>
        <table class="striped">
            <thead>
                <tr>
                    <th>Data do Atendimento</th>
                    <th>Conveniado</th>
                    <th>Paciente</th>
                    <th>Valor Total</th>
                    <th>Valor Total para o Segurado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($treatments as $treatment)
                    @php
                        $totalValue = 0;
                    @endphp
                    @foreach ($treatment->providedServices as $providedService)
                        @php
                            $totalValue += $providedService->total;
                        @endphp
                    @endforeach
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($treatment->date)->format('d/m/Y') }}</td>
                        <td>{{ $treatment->partner->name ?? 'Não informado' }}</td>
                        <td>{{ $treatment->patient->name ?? 'Não informado' }}</td>
                        <td>R$ {{ number_format($totalValue, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($treatment->provided_services_sum_patient_value, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <br>
        {{-- <hr> --}}
        {{-- <h1>Serviços Prestados</h1> --}}
        {{-- <table style="width: 100%; margin-top: 1%;" class="striped">
                <tbody> --}}

        {{-- <tr style="font-size: 10px;">
                            <td colspan="3" class=""><strong>Serviço:</strong>
                                {{ $providedService->service->code }} - {{ $providedService->service->name }}
                            </td>
                        </tr>
                        <tr style="font-size: 10px;">
                            <td colspan="3" class=""><strong>Descrição Detalhada:</strong>
                                {{ $providedService->description }}
                            </td>
                        </tr>
                        <tr style="font-size: 10px;">
                            <td class=""><strong>Valor Unitário:</strong>
                                R$ {{ number_format($providedService->value, 2, ',', '.') }}
                            </td>
                            <td class=""><strong>Quantidade:</strong>
                                {{ $providedService->quantity }}
                            </td>
                            <td class=""><strong>Valor Total:</strong>
                                R$ {{ number_format($providedService->total, 2, ',', '.') }}
                            </td>
                        </tr> --}}
        {{-- <br> --}}
        {{-- </tbody>
            </table>  --}}

        <table style="width: 100%;">

        </table>
        <hr>
        <br>
        <br>
        <h1>
            Valor Total: R$ {{ number_format($totalServices, 2, ',', '.') }}
        </h1>
    @else
        <br>
        <br>
        <h2>
            Sem lançamentos registrados de {{ date('d/m/Y', strtotime($startDate)) }} a
            {{ date('d/m/Y', strtotime($endDate)) }}.
        </h2>
    @endif
@endsection

@section('footer')
    @include('reports.footer-with-single-sign')
@endsection
