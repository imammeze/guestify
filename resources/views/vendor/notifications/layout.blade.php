<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 0;
            color: #374151;
        }
        .wrapper {
            width: 100%;
            background: #f8fafc;
            padding: 40px 0;
        }
        .content {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(90deg, #f97316, #dc2626);
            color: white;
            padding: 20px;
            text-align: center;
            font-weight: bold;
            font-size: 20px;
        }
        .body {
            padding: 30px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            padding: 20px;
            border-top: 1px solid #e5e7eb;
            background: #fafafa;
        }
        .button {
            display: inline-block;
            background: linear-gradient(90deg, #f97316, #dc2626);
            color: white !important;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="content">
        <div class="header">
            🔒 Sistem Buku Tamu
        </div>
        <div class="body">
            {{ Illuminate\Mail\Markdown::parse($slot) }}
        </div>
        @isset($subcopy)
            <div class="footer">
                {{ Illuminate\Mail\Markdown::parse($subcopy) }}
            </div>
        @endisset
        <div class="footer">
            Email ini dikirim otomatis oleh <strong>Sistem Buku Tamu</strong>, mohon tidak membalas.
        </div>
    </div>
</div>
</body>
</html>
