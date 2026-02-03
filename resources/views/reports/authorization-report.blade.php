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
        {{-- Cabeçalho do relatório --}}
        <table class="striped fit" style="font-size: 14px;">
            <tr>
                <td style="width: 20%; border: 1px solid black;">
                    <strong>Solicitante</strong>
                </td>
                <td style="border: 1px solid black;">
                    <strong>
                        @if ($type === 'partner')
                            {{ $authorizations->first()->partner->name }}
                        @else
                            {{ $authorizations->first()->requester_name }}
                        @endif
                    </strong>
                </td>
            </tr>

            @if ($type === 'partner')
                <tr>
                    <td style="border: 1px solid black;">
                        <strong>Endereço</strong>
                    </td>
                    <td style="border: 1px solid black;">
                        {{ strtoupper($authorizations->first()->partner->address ?? '—') }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;">
                        <strong>Telefone</strong>
                    </td>
                    <td style="border: 1px solid black;">
                        {{ $authorizations->first()->partner->phone ?? '—' }}
                    </td>
                </tr>
            @endif

            <tr>
                <td style="border: 1px solid black;">
                    <strong>Total de Autorizações</strong>
                </td>
                <td style="border: 1px solid black;">
                    {{ $authorizations->count() }}
                </td>
            </tr>

            @if (!empty($startDate) && !empty($endDate))
                <tr>
                    <td style="border: 1px solid black;">
                        <strong>Período</strong>
                    </td>
                    <td style="border: 1px solid black;">
                        {{ date('d/m/Y', strtotime($startDate)) }} a
                        {{ date('d/m/Y', strtotime($endDate)) }}
                    </td>
                </tr>
            @endif
        </table>

        <table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr>
                    <th colspan="3" style="background-color:#f2f2f2; border: 1px solid black;">
                        Autorizações Realizadas
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;">Data</th>
                    <th style="border: 1px solid black;">Paciente</th>
                    <th style="border: 1px solid black;">Procedimento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($authorizations as $authorization)
                    <tr>
                        <td style="border: 1px solid black;">
                            {{ optional($authorization->created_at)->format('d/m/Y') }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $authorization->patient->name ?? 'Não informado' }}
                        </td>
                        <td style="border: 1px solid black; padding: 6px;">
                            @if ($authorization->services->isNotEmpty())
                                <ul style="margin: 0; padding-left: 16px;">
                                    @foreach ($authorization->services as $service)
                                        <li>{{ $service->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <br><br>
        <h2>Sem autorizações registradas.</h2>
    @endif
@endsection

@section('footer')
    @include('reports.footer-with-single-sign')
@endsection
