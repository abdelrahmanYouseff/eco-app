<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>مطالبة بسداد قسط الإيجار</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Tahoma', 'Segoe UI', sans-serif;
            font-size: 14px;
            line-height: 1.8;
            color: #000;
            background: #fff;
            padding: 40px 60px;
            direction: rtl;
            text-align: right;
        }

        /* Ensure RTL for all email clients */
        [dir="rtl"] {
            direction: rtl !important;
            text-align: right !important;
        }

        table {
            direction: rtl !important;
        }

        th, td {
            text-align: right !important;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
        }

        .logo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .logo-container img {
            max-height: 120px;
            width: auto;
        }

        .logo-right {
            order: 2;
        }

        .logo-left {
            order: 1;
        }

        .date-line {
            text-align: right;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: normal;
            line-height: 1.6;
            direction: rtl;
        }

        .hijri-date {
            display: block;
            margin-bottom: 5px;
            direction: rtl;
            text-align: right;
        }

        .gregorian-date {
            display: block;
            direction: rtl;
            text-align: right;
        }

        .recipient {
            margin-bottom: 12px;
            font-size: 15px;
            font-weight: bold;
            text-align: right;
            direction: rtl;
        }

        .subject {
            margin-bottom: 12px;
            font-size: 15px;
            font-weight: bold;
            text-align: right;
            direction: rtl;
        }

        .greeting {
            margin-bottom: 20px;
            font-size: 20px;
            text-align: center;
            font-weight: bold;
            direction: rtl;
        }

        .body-text {
            margin-bottom: 25px;
            text-align: justify;
            font-size: 14px;
            line-height: 2.2;
            direction: rtl;
        }

        .body-text p {
            margin-bottom: 12px;
            text-indent: 0;
            text-align: right;
            direction: rtl;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 11px;
            direction: rtl !important;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: right !important;
            font-size: 11px;
            direction: rtl !important;
        }

        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: right !important;
        }

        table td {
            text-align: right !important;
        }

        .conclusion {
            margin-top: 25px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 2;
            text-align: right;
            direction: rtl;
        }

        .bank-details {
            margin-top: 25px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 2;
            text-align: right;
            direction: rtl;
        }

        .bank-details p {
            margin-bottom: 3px;
            text-align: right;
            direction: rtl;
        }

        .closing {
            margin-top: 25px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            line-height: 2;
            direction: rtl;
        }

        .signature {
            margin-top: 40px;
            text-align: left;
            font-size: 14px;
            font-weight: bold;
            direction: ltr;
        }

        .signatures-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            gap: 40px;
        }

        .signature-box {
            flex: 1;
            min-width: 300px;
        }

        .signature-box h4 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: right;
            direction: rtl;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        .company-info {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            direction: rtl;
        }

        .company-info h4 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: right;
        }

        .company-info p {
            margin-bottom: 8px;
            font-size: 14px;
            text-align: right;
        }

        .company-info strong {
            font-weight: bold;
            margin-left: 10px;
        }

        .footer-info {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            padding: 8px 15px;
            font-size: 10px;
            text-align: center;
            direction: rtl;
            background-color: #f8f9fa;
            display: block;
            width: 100%;
        }

        .footer-info span {
            display: inline-block;
            margin: 0 15px;
            color: #6c757d;
            white-space: nowrap;
        }

        .footer-info span:not(:last-child)::after {
            content: " | ";
            margin: 0 5px;
            color: #adb5bd;
        }

        /* Additional RTL support for email clients */
        .container {
            direction: rtl !important;
            text-align: right !important;
        }

        /* Ensure proper alignment for all elements */
        div, p, span {
            direction: rtl !important;
            text-align: right !important;
        }

        /* Center alignment for specific elements */
        .greeting, .closing {
            text-align: center !important;
            direction: rtl !important;
        }

        /* Override any center alignment in tables */
        table th, table td {
            text-align: right !important;
            direction: rtl !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('assets/ملون.png') }}" alt="Alzeer Holding Logo" class="logo-left">
            <img src="{{ asset('assets/ملون (16).png') }}" alt="Alzeer Holding Logo" class="logo-right">
        </div>

        <div class="date-line">
            @php
                // Simple Hijri date conversion (approximate)
                $hijriYear = \Carbon\Carbon::now()->year - 579;
                $hijriMonth = \Carbon\Carbon::now()->month;
                $hijriDay = \Carbon\Carbon::now()->day;
                // Adjust for Hijri calendar differences
                if ($hijriMonth <= 2) {
                    $hijriYear--;
                }
            @endphp
            <span class="hijri-date">{{ $hijriYear }}/{{ str_pad($hijriMonth, 2, '0', STR_PAD_LEFT) }}/{{ str_pad($hijriDay, 2, '0', STR_PAD_LEFT) }} هـ</span>
            <span class="gregorian-date">{{ \Carbon\Carbon::now()->format('Y/m/d') }} م</span>
        </div>

        <div class="recipient">
            السادة : {{ $payment->contract->client->name }} المحترمين
        </div>

        <div class="subject">
            الموضوع: مطالبة بسداد قسط الإيجار {{ $payment->contract->unit->unit_type ?? 'وحدة' }} رقم ({{ $payment->contract->unit->unit_number ?? 'N/A' }}) - {{ $payment->contract->building->name ?? 'غير محدد' }}
        </div>

        <div class="greeting">
            السلام عليكم ورحمة الله وبركاته....
        </div>

        <div class="body-text">
            <p>
                إشارةً إلى عقد الإيجار رقم (<strong>{{ $payment->contract->contract_number }}</strong>) الخاص بـ{{ $payment->contract->unit->unit_type ?? 'الوحدة' }} رقم ({{ $payment->contract->unit->unit_number ?? 'N/A' }}) في مبنى {{ $payment->contract->building->name ?? 'غير محدد' }}.
            </p>
            <p>
                نود إفادتكم بوجود مبلغ مستحق السداد لصالح شركة فهد نواف الزير القابضة. وفق بيان المستحقات الموضح أدناه:
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>م</th>
                    <th>بداية العقد</th>
                    <th>مبلغ القسط الأول</th>
                    <th>تاريخ استحقاق القسط الثاني</th>
                    <th>مبلغ القسط الثاني</th>
                    <th>اجمالي المستحق</th>
                    <th>المبلغ المسدد</th>
                    <th>المبلغ المتبقي</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $contract = $payment->contract;
                    $isPaid = $payment->status === 'paid';
                    $paidAmount = $isPaid ? $payment->total_value : 0;
                    $remainingAmount = $isPaid ? 0 : $payment->total_value;
                @endphp
                <tr>
                    <td>1</td>
                    <td>{{ $contract->start_date->format('Y.m.d') }}</td>
                    <td>{{ number_format($payment->rent_value, 0) }}</td>
                    <td>{{ $payment->due_date->format('Y.m.d') }}</td>
                    <td>{{ number_format($payment->rent_value, 0) }}</td>
                    <td>{{ number_format($payment->total_value, 0) }}</td>
                    <td>{{ number_format($paidAmount, 0) }}</td>
                    <td>{{ number_format($remainingAmount, 0) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="conclusion">
            الرجاء التكرم وسداد المبلغ المستحق في أقرب وقت ممكن .
        </div>

        <div class="bank-details">
            <p><strong>اسم الحساب :</strong> FAHAD NAWAF ALZEER TRADING GROUP</p>
            <p><strong>رقم الايبان :</strong> SA825500000000877300433</p>
            <p><strong>اسم البنك :</strong> البنك السعودي الفرنسي</p>
            <p><strong>كود سويفت :</strong> BSFRSARI</p>
        </div>

        <div class="closing">
            وتفضلوا بقبول فائق الاحترام والتقدير...
        </div>

        <!-- Footer Info -->
        <div class="footer-info">
            <span>{{ !empty($companyInfo['name']) ? $companyInfo['name'] : 'Alzeer Holding' }}</span>
            <span><strong>رقم الهاتف:</strong> {{ !empty($companyInfo['phone']) ? $companyInfo['phone'] : '0551268748' }}</span>
            <span><strong>البريد الإلكتروني:</strong> {{ !empty($companyInfo['email']) ? $companyInfo['email'] : 'info@alzeer-holding.com' }}</span>
        </div>
    </div>
</body>
</html>
