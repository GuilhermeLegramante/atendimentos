@extends('reports.page')

@section('header')
    @include('reports.header')
@endsection

<style>
    .striped tr:nth-child(even) {
        background-color: #f0f0f0;
        border-collapse: separate;
        /* Essencial para que o border-spacing funcione */
        border-spacing: 30px;
    }
</style>

@section('content')
    <h2>
        Conveniado: {{ $person->name }}
    </h2>
    <hr>
    <br>
    <table class="striped">
        <thead>
            <tr>
                <th style="width: 15%">Data do Atendimento</th>
                <th style="width: 15%">CPF</th>
                <th style="width: 50%;">Paciente</th>
                <th style="width: 25%">Assinatura</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 24; $i++)
                <tr>
                    <td style="padding: 16px;"></td>
                    <td style="padding: 16px;"></td>
                    <td style="padding: 16px;"></td>
                    <td style="padding: 16px;"></td>
                </tr>
            @endfor
        </tbody>
    </table>


    <br>
@endsection

@section('footer')
    @include('reports.footer-with-single-sign')
@endsection
