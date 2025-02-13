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
        <tr>
            <td style="width: 15%; background-color: #f2f2f2;">
                <strong>Nome do Beneficiário</strong>
            </td>
            <td style="background-color: white;">
            </td>
        </tr>
        <tr>
            <td style="width: 15%; background-color: #f2f2f2;">
                <strong>CPF do Beneficiário</strong>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <td style="width: 15%; background-color: #f2f2f2;">
                <strong>Data do Atendimento</strong>
            </td>
            <td style="background-color: white;">
            </td>
        </tr>
    </table>

    <table class="striped fit" style="width: 48%; float: left; margin-right: 4%;">
        <thead>
            <tr style="background-color:#f2f2f2; ">
                <th colspan="5" style="background-color:#f2f2f2; color: black;">
                    Atendimentos Realizados
                </th>
            </tr>
            <tr>
                <th style="background-color:#f2f2f2; color: black; width: 3%;">
                    Código
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 20%;">
                    Descrição
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 3%; text-align: center;">
                    Qtd
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 5%; text-align: right;">
                    Valor Unitário
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 5%; text-align: right;">
                    Valor Total
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    09901
                </td>
                <td>
                    ALVEOLOTOMIA FOR HEMI-ARCADA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 199,50
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    09902
                </td>
                <td>
                    APECECTOMIA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 79,80
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    09902
                </td>
                <td>
                    APECECTOMIA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 79,80
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    05503
                </td>
                <td>
                    APLIC. DE FLUOR FOR HEMI-ARCADA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 15,96
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    05502
                </td>
                <td>
                    APLIC. SELANTE POR HEMI-ARCADA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 23,94
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    10104
                </td>
                <td>
                    CAPEAMENTO PULPAR DIRETO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 19,95
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    10105
                </td>
                <td>
                    CAPEAMENTO PULPAR INDIRETO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 27,93
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    09904
                </td>
                <td>
                    CIR. ENUCLEAÇÃO CISTOS OSTEOMAS
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 199,50
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    09903
                </td>
                <td>
                    CIRURGIA DENTE SISO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 126,35
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    09405
                </td>
                <td>
                    CIRURGIA E DRENAGEM ABCESSOS
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 99,75
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    09909
                </td>
                <td>
                    CONSULTAS DE URGENCIAS
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 47,22
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    01101
                </td>
                <td>
                    CONSULTAS
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 36,58
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    10108
                </td>
                <td>
                    COROA DE RESINA FOTO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 113,05
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    10107
                </td>
                <td>
                    CONSULTAS SEM COMPARECIMENTO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 39,90
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    09906
                </td>
                <td>
                    CORREÇÃO TORUS BRIDAS MUSCULAR
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 113,05
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    06602
                </td>
                <td>
                    CURETAGEM SUB GENGIVAL P/ ELEMENT©
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 15,96
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    03304
                </td>
                <td>
                    DESOBSTRUÇÃO CONDUTO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 59,85
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    09900
                </td>
                <td>
                    DIF. DE CIRGURGIA REALIZADA EM HOSPITAL
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 585,20
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    10106
                </td>
                <td>
                    DRENAGEM VIA CANAL
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 79,80
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    09910
                </td>
                <td>
                    EIXO DENTE INCLUSO IMPACTADO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 152,95
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    02222
                </td>
                <td>
                    EXTRAÇÃO COM RETALHO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 86,45
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table class="striped fit" style="width: 48%; float: left;">
        <thead>
            <tr style="background-color:#f2f2f2; ">
                <th colspan="5" style="background-color:#f2f2f2; color: black;">
                    Atendimentos Realizados
                </th>
            </tr>
            <tr>
                <th style="background-color:#f2f2f2; color: black; width: 3%;">
                    Código
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 20%;">
                    Descrição
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 3%; text-align: center;">
                    Qtd
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 5%; text-align: right;">
                    Valor Unitário
                </th>
                <th style="background-color:#f2f2f2; color: black; width: 5%; text-align: right;">
                    Valor Total
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    02201
                </td>
                <td>
                    EXTRAÇÃO DENTE DECIDUO
                </td>
                <td></td>
                <td style="text-align: right;">
                    RS 39,90
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    02202
                </td>
                <td>
                    EXTRAÇÃO DENTE PERMANENTE
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 39,90
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    10110
                </td>
                <td>
                    GENGIVECTOMIA P/ HEMI-ARCADA CONSULTA PARTICULAR
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 79,80
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    06603
                </td>
                <td>
                    GENGIVECTOMIA POR HEMI-ARCADA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 15,96
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    01103
                </td>
                <td>
                    PERICIA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 39,90
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    08809
                </td>
                <td>
                    PLACA DE MORDIDA MIORRELAXANTE
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 325,85
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    10103
                </td>
                <td>
                    POLIMENTO DE RESTAURAÇÃO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 15,96
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    11111
                </td>
                <td>
                    PROCEDIMENTO CIRURGICO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 186,20
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    05501
                </td>
                <td>
                    PROFILAXIA POR HEMI-ARCADA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 15,96
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    03305
                </td>
                <td>
                    PULPOTOMIA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 63,84
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    01102
                </td>
                <td>
                    RADIOGRAFIA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 19,95
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    10109
                </td>
                <td>
                    RASPAGEM SUB-GENGIVAL POR SESSAO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 53,20
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    09907
                </td>
                <td>
                    REIMPLANTE DENTARIO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 66,50
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    04403
                </td>
                <td>
                    REST. AMALÇÃMA OU RESINA 03
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 46,55
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    04401
                </td>
                <td>
                    REST. AMALÇÃMA OU RESINA 01
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 37,24
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    04402
                </td>
                <td>
                    REST. AMALÇÃMA OU RESINA 02
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 39,90
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    10102
                </td>
                <td>
                    RESTAURAÇÃO PROVISORIA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 19,95
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    06601
                </td>
                <td>
                    TARTAROCTOMIA P/ HEMI-ARCADA
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 15,96
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    03306
                </td>
                <td>
                    TRAT. DE CANAL DENTE DECIDUO
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 59,85
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    03307
                </td>
                <td>
                    TRAT. DE CANAL CONSULTA PART. (MOLARES)
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 239,40
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    02203
                </td>
                <td>
                    TRATAMENTO HEMORRAGIA E ALVEOLITE
                </td>
                <td></td>
                <td style="text-align: right;">
                    R$ 39,90
                </td>
                <td></td>
            </tr>

        </tbody>
    </table>

    <br>
    <br>
    <br>
    <br>
    <p>
        &nbsp;
    </p>
    <p>
        Declaro, que após ter sido devidamente esclarecido sobre os propósitos, riscos, custos e alternativas de tratamento,
        conforme acima apresentados, aceito e autorizo a execução do tratamento. Comprometendo-me a cumprir as orientações
        do profissional assistente e arcar com os custos previstos em contrato. Declaro, ainda, que o(s) procedimento(s)
        descrito(s) acima, e por mim assinado(s) foi/foram realizado(s) o meu consentimento e de forma satisfatória.
        Autorizo a operadora a pagar em meu nome e por minha conta, ao profissional contratado que assina este documento, os valores
        referentes ao tratamento realizado, comprometendo-me a arcar com os meus custos conforme o previsto em contrato.
    </p>
@endsection

@section('footer')
    @include('reports.footer-with-sign')
@endsection
