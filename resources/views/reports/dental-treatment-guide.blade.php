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
                <th colspan="5" style="background-color:#f2f2f2; color: black;">
                    Atendimentos Realizados
                </th>
            </tr>
            <tr>
                <th style="background-color:#f2f2f2; color: black; width: 3%;">
                    Data
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 10%;">
                    CPF
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 40%;">
                    Nome Completo
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 5%; text-align: right;">
                    Valor
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 5%;">
                    Assinatura
                </th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 30; $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
