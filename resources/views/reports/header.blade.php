<header style="position: fixed; margin-top: -23%;">
    <div style="height: 100px;">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="height: 220px; vertical-align: middle; width: 20%;">
                        <img src="{{ public_path('img/logo.png') }}" style="width: 200px; height: 75px;">
                    </td>
                    <td style="height: 64px; width: 40%;">
                        <table>
                            <tbody>
                                <tr>
                                    <td style="font-size: 16px; font-weight: bold;">{{ $title }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="widht: 50px;">

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
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr style="margin-top: 60px; width: 110%;" />
</header>
