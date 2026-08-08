<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $companyInfo['name'] ?? 'ECO Properties' }}</title>
</head>
<body style="margin:0;padding:0;font-family:Tahoma,Arial,sans-serif;background:#f5f5f5;color:#212529;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f5f5;padding:24px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background:#212529;color:#fff;padding:20px 24px;">
                            <h1 style="margin:0;font-size:20px;font-weight:600;">{{ $companyInfo['name'] ?? 'ECO Properties' }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;line-height:1.8;font-size:15px;">
                            <p style="margin:0 0 16px;">السادة / {{ $client->name }}،</p>
                            <div style="margin:0 0 24px;white-space:pre-wrap;">{!! nl2br(e($body)) !!}</div>
                            <p style="margin:0;color:#6c757d;font-size:14px;">
                                مع التحية،<br>
                                {{ $companyInfo['name'] ?? 'ECO Properties' }}
                                @if(!empty($companyInfo['phone']))
                                    <br>{{ $companyInfo['phone'] }}
                                @endif
                                @if(!empty($companyInfo['email']))
                                    <br>{{ $companyInfo['email'] }}
                                @endif
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
