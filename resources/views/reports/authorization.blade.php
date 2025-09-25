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
                <th>Data da Autorização</th>
                <th>Conveniado</th>
                <th>Paciente</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ date('d/m/Y', strtotime($authorization->created_at)) }}
                </td>
                <td>
                    {{ $authorization->partner?->name ?? '-' }}
                    {{-- O conveniado é opcional --}}
                </td>
                <td>
                    {{ $authorization->patient->name }}
                </td>
            </tr>
        </tbody>
    </table>

    <br>
    <hr>

    <table style="width: 100%; margin-top: 1%;" class="striped">
        <thead>
            <tr>
                <th>Serviços</th>
                <th style="width: 15%; text-align:center;">Situação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($authorization->services as $service)
                <tr>
                    <td>
                        {{ $service->code }} - {{ $service->name }}
                    </td>
                    <td style="text-align: center;">
                        {{ $service->pivot->status ? 'AUTORIZADO' : 'NÃO AUTORIZADO' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <hr>

    <div style="margin-top: 1%;">
        <strong>Observações:</strong>
        <p>{{ $authorization->observations ?? '-' }}</p>
    </div>
@endsection

@section('footer')
    @include('reports.footer-with-single-sign')
@endsection
