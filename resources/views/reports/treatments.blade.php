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
        @foreach ($treatments as $treatment)
            <table style="width: 100%; margin-top: 1%;" class="striped">
                <thead>
                    <tr>
                        <th>
                            Data do Atendimento
                        </th>
                        <th>
                            Conveniado
                        </th>
                        <th>
                            Paciente
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="font-size: 10px;">
                        <td class="">
                            {{ date('d/m/Y', strtotime($treatment->date)) }}
                        </td>
                        <td class="">
                            {{ $treatment->partner->name }}
                        </td>
                        <td class="">
                            {{ $treatment->patient->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            {{-- <hr> --}}
            {{-- <h1>Serviços Prestados</h1> --}}
            {{-- <table style="width: 100%; margin-top: 1%;" class="striped">
                <tbody> --}}
            @php
                $totalValue = 0;
            @endphp
            @foreach ($treatment->providedServices as $providedService)
                @php
                    $totalValue += $providedService->total;
                @endphp
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
            @endforeach
            {{-- </tbody>
            </table>  --}}

            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <h2>Valor Total: R$ {{ number_format($totalValue, 2, ',', '.') }}</h2>
                    </td>
                    <td style="width: 50%;">
                        <h2>Valor Total para o segurado: R$
                            {{ number_format($treatment->provided_services_sum_patient_value, 2, ',', '.') }}</h2>
                    </td>
                </tr>
            </table>
            <hr>
        @endforeach
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
