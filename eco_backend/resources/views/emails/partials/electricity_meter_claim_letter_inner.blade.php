@php
    $client = $contract->client;
    $clientName = $client->name ?? 'المستأجر';
    $isCompany = ($client->client_type ?? '') === 'شركة';
    $unit = $contract->unit;
    $officeNumber = $unit?->unit_number ?? '—';
    $electricityMeterNumber = $unit?->current_electricity_meter;
    $electricityAccountNumber = $unit?->electricity_account_number;
    $meterDisplay = $electricityMeterNumber ? $electricityMeterNumber : '—';
    $accountDisplay = $electricityAccountNumber ? $electricityAccountNumber : '—';
    $letterDate = now()->format('d/m/Y');
@endphp
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:720px;margin:0 auto;">
    <tr>
        <td style="text-align:right;padding-bottom:24px;">
            <span style="font-size:16px;">التاريخ:{{ $letterDate }}م</span>
        </td>
    </tr>
    <tr>
        <td style="padding-bottom:8px;text-align:right;">
            @if($isCompany)
                <span style="font-size:16px;">شركة/</span>
            @else
                <span style="font-size:16px;">السادة/</span>
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding-bottom:20px;">
            <span style="font-size:17px;font-weight:bold;">{{ $clientName }}</span>
        </td>
    </tr>
    <tr>
        <td style="padding-bottom:20px;">
            <strong>الموضوع: إشعار للمستأجر بنقل عداد الكهرباء</strong>
        </td>
    </tr>
    <tr>
        <td style="padding-bottom:16px;">
            السلام عليكم ورحمة الله وبركاته،،
        </td>
    </tr>
    <tr>
        <td style="padding-bottom:24px;text-align:justify;">
            نفيدكم بضرورة نقل عداد الكهرباء الخاص بالمكتب رقم (<strong>{{ $officeNumber }}</strong>) إلى اسمكم، وذلك وفق الالتزامات المتعلقة بالمكتب المستأجر، مع الإشارة إلى أن بيانات الكهرباء المسجلة للوحدة كالتالي:
            <br><br>
            <strong>رقم عداد الكهرباء:</strong> <span dir="ltr" style="unicode-bidi:plaintext;">{{ $meterDisplay }}</span>
            <br>
            <strong>رقم حساب الكهرباء:</strong> <span dir="ltr" style="unicode-bidi:plaintext;">{{ $accountDisplay }}</span>
            <br><br>
            وعليه نأمل منكم المبادرة باستكمال إجراءات نقل العداد في أقرب وقت ممكن بشكل عاجل وهام وفوري.
            <br><br>
            ونؤكد أهمية إتمام هذا الإجراء حفاظًا على انتظام الخدمات وتفادي أي التزامات أو تبعات قد تنشأ مستقبلاً.
            <br><br>
            شاكرين لكم تعاونكم،،
        </td>
    </tr>
    <tr>
        <td style="text-align:left;padding-top:32px;padding-bottom:40px;">
            الإدارة
        </td>
    </tr>
    <tr>
        <td style="border-top:1px solid #ccc;padding-top:20px;">
            <strong style="display:block;margin-bottom:12px;">اسم المكتب</strong>
            <span style="font-size:16px;">{{ $clientName }} مكتب رقم {{ $officeNumber }}</span>
            <br><br>
            <span style="font-size:15px;"><strong>رقم عداد الكهرباء:</strong> <span dir="ltr" style="unicode-bidi:plaintext;">{{ $meterDisplay }}</span></span>
            <br>
            <span style="font-size:15px;"><strong>رقم حساب الكهرباء:</strong> <span dir="ltr" style="unicode-bidi:plaintext;">{{ $accountDisplay }}</span></span>
        </td>
    </tr>
</table>
