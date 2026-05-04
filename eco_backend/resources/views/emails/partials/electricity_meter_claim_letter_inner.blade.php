@php
    $client = $contract->client;
    $clientName = $client->name ?? 'المستأجر';
    $isCompany = ($client->client_type ?? '') === 'شركة';
    $officeNumber = $contract->unit->unit_number ?? '—';
    $letterDate = now()->format('d/m/Y');
@endphp
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:720px;margin:0 auto;">
    <tr>
        <td style="text-align:right;padding-bottom:24px;">
            <span style="font-size:16px;">التاريخ:{{ $letterDate }}م</span>
        </td>
    </tr>
    <tr>
        <td style="padding-bottom:8px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="text-align:right;width:50%;vertical-align:bottom;">
                        @if($isCompany)
                            <span style="font-size:16px;">شركة/</span>
                        @else
                            <span style="font-size:16px;">السادة/</span>
                        @endif
                    </td>
                    <td style="text-align:left;width:50%;vertical-align:bottom;">
                        <span style="font-size:16px;">المحترم</span>
                    </td>
                </tr>
            </table>
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
            نفيدكم بضرورة نقل عداد الكهرباء الخاص بالمكتب رقم (<strong>{{ $officeNumber }}</strong>) إلى اسمكم، وذلك وفق الالتزامات المتعلقة بالمكتب المستأجر، وعليه نأمل منكم المبادرة باستكمال إجراءات نقل العداد في أقرب وقت ممكن بشكل عاجل وهام وفوري.
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
            <strong style="display:block;margin-bottom:12px;">أسماء المكاتب</strong>
            <span style="font-size:16px;">{{ $clientName }} مكتب رقم {{ $officeNumber }}</span>
        </td>
    </tr>
</table>
