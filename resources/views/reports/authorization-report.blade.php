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
    @if ($authorizations->count() > 0)
        {{-- Tabela de Resumo --}}
        <table class="striped fit" style="font-size: 14px; margin-bottom: 20px;">
            <tr>
                <td style="width: 20%; border: 1px solid black;">
                    <strong>Solicitante</strong>
                </td>
                <td style="border: 1px solid black;">
                    <strong>
                        @if (empty($requester))
                            TODOS OS CONVENIADOS
                        @elseif ($type === 'partner')
                            {{ $authorizations->first()->partner->name ?? 'N/A' }}
                        @else
                            {{ $authorizations->first()->requester_name ?? 'N/A' }}
                        @endif
                    </strong>
                </td>
            </tr>

            {{-- Exibir detalhes de contato apenas se for um parceiro específico --}}
            @if (!empty($requester) && $type === 'partner' && $authorizations->first()->partner)
                <tr>
                    <td style="border: 1px solid black;"><strong>Endereço</strong></td>
                    <td style="border: 1px solid black;">
                        {{ strtoupper($authorizations->first()->partner->address ?? '—') }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;"><strong>Telefone</strong></td>
                    <td style="border: 1px solid black;">
                        {{ $authorizations->first()->partner->phone ?? '—' }}
                    </td>
                </tr>
            @endif

            <tr>
                <td style="border: 1px solid black;"><strong>Total de Autorizações</strong></td>
                <td style="border: 1px solid black;">{{ $authorizations->count() }}</td>
            </tr>
        </table>

        {{-- Tabela Principal --}}
        <table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th style="border: 1px solid black; padding: 8px;">Data</th>
                    <th style="border: 1px solid black; padding: 8px;">Paciente</th>
                    {{-- Nova coluna para identificar o solicitante quando "Todos" estiver ativo --}}
                    @if (empty($requester))
                        <th style="border: 1px solid black; padding: 8px;">Solicitante</th>
                    @endif
                    <th style="border: 1px solid black; padding: 8px;">Procedimento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($authorizations as $authorization)
                    <tr>
                        <td style="border: 1px solid black; padding: 8px;">
                            {{ optional($authorization->created_at)->format('d/m/Y') }}
                        </td>
                        <td style="border: 1px solid black; padding: 8px;">
                            {{ $authorization->patient->name ?? 'Não informado' }}
                        </td>
                        {{-- Conteúdo da nova coluna --}}
                        @if (empty($requester))
                            <td style="border: 1px solid black; padding: 8px;">
                                {{ $authorization->partner->name ?? ($authorization->requester_name ?? '—') }}
                            </td>
                        @endif
                        <td style="border: 1px solid black; padding: 8px;">
                            @if ($authorization->services->isNotEmpty())
                                <ul style="margin: 0; padding-left: 18px;">
                                    @foreach ($authorization->services as $service)
                                        <li>{{ $service->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; margin-top: 50px;">
            <h2>Sem autorizações registradas para o período selecionado.</h2>
        </div>
    @endif
@endsection

@section('footer')
    {{-- @include('reports.footer-with-single-sign') --}}
@endsection
