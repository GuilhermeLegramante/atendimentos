<header style="position: fixed; margin-top: -23%;">
    @if (!$definitive)
        <div
            style="
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 50px;
            color: rgba(200, 0, 0, 0.12);
            z-index: 9999;
            pointer-events: none;
            white-space: nowrap;
            width: 100%;
            text-align: center;
        ">
            SEM VALIDADE
        </div>
    @endif

    <div style="height: 100px;">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="height: 220px; vertical-align: middle; width: 20%;">
                        <img src="{{ public_path('img/logo.png') }}" style="width: 200px; height: 75px;">
                    </td>
                    <td style="height: 64px; width: 30%;">
                        <table>
                            <tbody>
                                <tr>
                                    <td style="width: 10%"></td>
                                    <td style="font-size: 16px; font-weight: bold;">{{ $title }} (SEM VALIDADE - APENAS P/ CONFERÊNCIA)</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 50px;"></td>
                    <td style="height: 64px; text-align: right;">
                        <table style="width: 100%">
                            <tbody>
                                <tr>
                                    <td style="height: 50px; text-align: right;">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url()->current() }}"
                                            alt="qrCode" style="width: 60px; height: 60px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="font-size: 10px; height: 10px; text-align: right; vertical-align: bottom;">
                                        <span style="font-weight: bold;">Emitido em:</span>
                                        {{ date('d/m/Y \à\s H:i:s') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr style="margin-top: 60px; width: 110%;" />
</header>
