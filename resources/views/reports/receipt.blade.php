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
    <table style="width: 100%;" class="striped">
        <tbody>
            <tr class="" style="font-size: 13px; width: 100%;">
                <td class=""><strong>Data do Atendimento:</strong>
                    {{ date('d/m/Y', strtotime($treatment->date)) }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class=""><strong>Conveniado:</strong> {{ $treatment->partner->name }}</td>
                <td class=""><strong>Paciente:</strong> {{ $treatment->patient->name }}</td>
            </tr>
        </tbody>
    </table>
@endsection

@section('footer')
    @include('reports.footer')
@endsection
