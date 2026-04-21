<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $actionText ?? 'Sistem Buku Tamu' }}</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family: Arial, sans-serif; color:#374151;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="padding:40px 0;">
        <tr>
            <td>
                <table align="center" width="600" cellpadding="0" cellspacing="0" role="presentation" 
                       style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.05);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(90deg,#f97316,#dc2626); padding:20px; text-align:center; color:#fff; font-size:20px; font-weight:bold;">
                            🔐 Sistem Buku Tamu
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; font-size:15px; line-height:1.6; color:#374151;">
                            
                            @if (!empty($greeting))
                                <h2 style="margin:0 0 15px; font-size:18px; font-weight:bold; color:#111827;">
                                    {{ $greeting }}
                                </h2>
                            @endif

                            @foreach ($introLines as $line)
                                <p>{{ $line }}</p>
                            @endforeach

                            @isset($actionText)
                                <table role="presentation" cellspacing="0" cellpadding="0" style="margin:25px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ $actionUrl }}" target="_blank"
                                               style="background:linear-gradient(90deg,#f97316,#dc2626); color:#fff; padding:12px 24px; 
                                                      font-size:14px; font-weight:bold; text-decoration:none; border-radius:6px; display:inline-block;">
                                                {{ $actionText }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endisset

                            @foreach ($outroLines as $line)
                                <p>{{ $line }}</p>
                            @endforeach

                            <p style="margin-top:30px;">
                                {{ $salutation ?? 'Terima kasih,' }}<br>
                                <strong>Sistem Buku Tamu</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f9fafb; text-align:center; font-size:12px; color:#6b7280; padding:15px;">
                            Email ini dikirim otomatis oleh <strong>Sistem Buku Tamu</strong>, mohon tidak membalas.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
