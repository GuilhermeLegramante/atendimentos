@extends('reports.page')

@section('header')
    @include('reports.landscape-header')
@endsection

<style>
    .striped tr:nth-child(even) {
        background-color: #f0f0f0;
        border-collapse: separate;
        /* Essencial para que o border-spacing funcione */
        border-spacing: 30px;
    }

    @page {
        size: a4 landscape;
    }
</style>

@section('content')
    <table class="striped fit" style="margin-top: -2%;">
        <tr>
            <td style="width: 15%; background-color: #f2f2f2;">
                <strong>Conveniado</strong>
            </td>
            <td>
                <strong>{{ $person->name }}</strong>
            </td>
        </tr>
    </table>

    <table class="striped fit" style="width: 100%; float: left; margin-right: 4%; border: 1px solid black;">
        <thead>
            <tr style="background-color:#f2f2f2; ">
                <th colspan="6" style="background-color:#f2f2f2; color: black; border: 1px solid black;">
                    Atendimentos Realizados
                </th>
            </tr>
            <tr>
                <th style="background-color:#f2f2f2; color: black; width: 1%; border: 1px solid black;">
                    N°
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 5%; border: 1px solid black;">
                    Data
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 10%; border: 1px solid black;">
                    CPF
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 40%; border: 1px solid black;">
                    Nome Completo
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 7%; text-align: right; border: 1px solid black;">
                    Valor
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 7%; border: 1px solid black;">
                    Assinatura
                </th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 30; $i++)
                <tr>
                    <td style="border: 1px solid black;">{{ $i + 1 }}</td>
                    <td style="border: 1px solid black;">&nbsp;</td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>
                </tr>
            @endfor
        </tbody>
    </table>

    <p>
        &nbsp;
    </p>
    <br>
    <br>
@endsection

@section('footer')
    @include('reports.footer-with-single-sign')
@endsection
