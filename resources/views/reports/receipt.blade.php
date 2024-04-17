@extends('reports.page')

@section('header')
    @include('reports.header')
@endsection

@section('content')
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Data do Atendimento:</strong>
                    {{ date('d/m/Y', strtotime($treatment->date)) }}</td>
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Conveniado:</strong> {{ $treatment->partner->name }}</td>
                <td class="collumn-right"><strong>Paciente:</strong> {{ $treatment->patient->name }}</td>
            </tr>
        </tbody>
    </table>
@endsection

@section('footer')
    @include('reports.footer')
@endsection
