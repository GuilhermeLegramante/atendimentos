@extends('reports.page')

@section('header')
    @include('reports.header')
@endsection

<style>
    .striped tr:nth-child(even) {
        background-color: #b3b3b3;
    }
</style>

@section('content')
    <table class="striped">
        <tbody>
            <tr class="" style="font-size: 13px;">
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
