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
        <table class="striped fit" style="">
            <tr>
                <td style="width: 15%; border: 1px solid black;">
                    <strong>Conveniado</strong>
                </td>
                <td style="border: 1px solid black;">
                    <strong>{{ $treatments->first()->partner->name }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 15%; border: 1px solid black;">
                    <strong>Endereço</strong>
                </td>
                <td style="border: 1px solid black;">
                    {{ $treatments->first()->partner->address ?? '—' }}
                </td>
            </tr>
            <tr>
                <td style="width: 15%; border: 1px solid black;">
                    <strong>Telefone</strong>
                </td>
                <td style="border: 1px solid black;">
                    {{ $treatments->first()->partner->phone ?? '—' }}
                </td>
            </tr>
            <tr>
                <td style="width: 15%; border: 1px solid black;">
                    <strong>Total de Atendimentos</strong>
                </td>
                <td style="border: 1px solid black;">
                    <strong>{{ count($treatments) }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 15%; border: 1px solid black;">
                    <strong>Período</strong>
                </td>
                <td style="border: 1px solid black;">
                    <strong>{{ date('d/m/Y', strtotime($startDate)) }} a
                        {{ date('d/m/Y', strtotime($endDate)) }}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 15%; border: 1px solid black;">
                    <strong>Valor Total</strong>
                </td>
                <td style="border: 1px solid black;">
                    <strong>R$ {{ number_format($totalServices, 2, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
        <table class="striped fit" style="width: 100%; float: left; margin-right: 4%; border: 1px solid black;">
            <thead>
                <tr style="background-color:#f2f2f2; ">
                    <th colspan="4" style="background-color:#f2f2f2; color: black; border: 1px solid black;">
                        Atendimentos Realizados
                    </th>
                </tr>
                <tr>
                    <th style="width: 5%; background-color:#f2f2f2; color: black; border: 1px solid black;">Data</th>
                    <th style="width: 40%; background-color:#f2f2f2; color: black; border: 1px solid black;">Paciente</th>
                    <th style="width: 15%; background-color:#f2f2f2; color: black; border: 1px solid black;">Valor Total
                    </th>
                    <th style="width: 15%; background-color:#f2f2f2; color: black; border: 1px solid black;">Valor Total
                        para
                        o Segurado</th>
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
                        <td style="border: 1px solid black;">{{ \Carbon\Carbon::parse($treatment->date)->format('d/m/Y') }}
                        </td>
                        <td style="border: 1px solid black;">{{ $treatment->patient->name ?? 'Não informado' }}</td>
                        <td style="border: 1px solid black;">R$ {{ number_format($totalValue, 2, ',', '.') }}</td>
                        <td style="border: 1px solid black;">R$
                            {{ number_format($treatment->provided_services_sum_patient_value, 2, ',', '.') }}</td>
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
    @else
        <br>
        <br>
        <h2>
            Sem lançamentos registrados de {{ date('d/m/Y', strtotime($startDate)) }} a
            {{ date('d/m/Y', strtotime($endDate)) }}.
        </h2>
    @endif

    <p>&nbsp;</p>
    <br>
@endsection

@section('footer')
    @include('reports.footer-with-single-sign')
@endsection
