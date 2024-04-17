@extends('reports.page')

@section('header')
    @include('reports.header')
@endsection

<style>
    .striped tr:nth-child(even) {
        background-color: #f0f0f0;
    }

    .signature {
        font-size: 16px;
        margin-top: 30%;
        text-align: center;
    }
</style>

@section('content')
    <table style="width: 100%; margin-top: 1%;" class="striped">
        <tbody>
            <tr style="font-size: 16px;">
                <td class=""><strong>Data do Atendimento:</strong>
                    {{ date('d/m/Y', strtotime($treatment->date)) }}</td>
            </tr>
            <tr style="font-size: 16px;">
                <td class=""><strong>Conveniado:</strong>
                    {{ $treatment->partner->registration . ' - ' . $treatment->partner->name }}
                </td>
            </tr>
            <tr style="font-size: 16px;">
                <td class=""><strong>Paciente:</strong>
                    {{ $treatment->patient->registration . ' - ' . $treatment->patient->name }}
                </td>
            </tr>
            <tr style="font-size: 16px;">
                <td class=""><strong>Serviço:</strong>
                    {{ $treatment->service->code . ' - ' . $treatment->service->name }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p>
            __________________________________________
        </p>
        <p>Ass. do Paciente</p>
    </div>

    <br>
    <br>
    <br>

    <div class="signature">
        <p>
            __________________________________________
        </p>
        <p>Ass. do Responsável pelo Atendimento</p>
    </div>
@endsection

@section('footer')
    @include('reports.footer')
@endsection
