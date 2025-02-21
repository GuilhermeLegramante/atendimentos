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
    <table class="striped" style="width: 100%; float: left; margin-right: 4%; border: 1px solid black;">
        <thead>
            <tr>
                <th style="width: 1%; background-color:#f2f2f2; color: black; border: 1px solid black;">N°</th>
                <th style="width: 15%; background-color:#f2f2f2; color: black; border: 1px solid black;">Data do Atendimento
                </th>
                <th style="width: 15%; background-color:#f2f2f2; color: black; border: 1px solid black;">CPF</th>
                <th style="width: 50%; background-color:#f2f2f2; color: black; border: 1px solid black;">Paciente</th>
                <th style="width: 25%; background-color:#f2f2f2; color: black; border: 1px solid black;">Assinatura</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 30; $i++)
                <tr>
                    <td style="padding: 6px; border: 1px solid black;">{{ $i + 1 }}</td>
                    <td style="padding: 6px; border: 1px solid black;"></td>
                    <td style="padding: 6px; border: 1px solid black;"></td>
                    <td style="padding: 6px; border: 1px solid black;"></td>
                    <td style="padding: 6px; border: 1px solid black;"></td>
                </tr>
            @endfor
        </tbody>
    </table>
    <p>&nbsp;</p>

    <br>
@endsection

@section('footer')
    @include('reports.footer-with-single-sign')
@endsection
