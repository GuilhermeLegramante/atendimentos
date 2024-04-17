<header style="position: fixed; margin-top: -15%;">
    <div style="height: 100px;">
        <table style="width: 120%;">
            <tbody>
                <tr>
                    <td style="height: 170px; vertical-align: middle; width: 12%;">
                        <img src="{{ public_path('img/logo.png') }}" style="width: 170px; height: 75px;">
                    </td>
                    <td style="height: 64px; width: 30%;">
                        <table>
                            <tbody>
                                <tr>
                                    <td style="font-size: 18px; font-weight: bold;">{{ $title }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
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
                                        style="font-size: 12px; height: 10px; text-align: right; vertical-align: bottom;">
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
    <hr style="margin-top: 5px; width: 100%;" />
</header>
