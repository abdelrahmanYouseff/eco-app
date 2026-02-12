<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مطالبة مالية</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Tahoma', sans-serif;
            font-size: 14px;
            line-height: 1.8;
            color: #000;
            background: #fff;
            padding: 40px 60px;
            direction: rtl;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
        }

        .date-line {
            text-align: right;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: normal;
            line-height: 1.6;
        }

        .hijri-date {
            display: block;
            margin-bottom: 5px;
        }

        .gregorian-date {
            display: block;
        }

        .recipient {
            margin-bottom: 12px;
            font-size: 15px;
            font-weight: bold;
        }

        .subject {
            margin-bottom: 12px;
            font-size: 15px;
            font-weight: bold;
        }

        .greeting {
            margin-bottom: 20px;
            font-size: 16px;
            text-align: center;
            font-weight: bold;
        }

        .body-text {
            margin-bottom: 25px;
            text-align: justify;
            font-size: 14px;
            line-height: 2.2;
        }

        .body-text p {
            margin-bottom: 12px;
            text-indent: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 11px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-size: 11px;
        }

        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        table td {
            text-align: center;
        }

        .conclusion {
            margin-top: 25px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 2;
        }

        .bank-details {
            margin-top: 25px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 2;
        }

        .bank-details p {
            margin-bottom: 3px;
        }

        .closing {
            margin-top: 25px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            line-height: 2;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
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
            الموضوع: مطالبة مالية إيجار {{ $payment->contract->unit->unit_type ?? 'وحدة' }} رقم ({{ $payment->contract->unit->unit_number ?? 'N/A' }}) - {{ $payment->contract->building->name ?? 'غير محدد' }}
        </div>

        <div class="greeting">
            السلام عليكم ورحمة الله وبركاته....
        </div>

        <div class="body-text">
            <p>
                بالاشارة إلى العقد رقم <strong>{{ $payment->contract->contract_number }}</strong> والمتعلق بإيجار {{ $payment->contract->unit->unit_type ?? 'وحدة' }} رقم ({{ $payment->contract->unit->unit_number ?? 'N/A' }}) في مبنى {{ $payment->contract->building->name ?? 'غير محدد' }}.
            </p>
            <p>
                يرجى العلم بأن هناك مبلغ مستحق يتعين تحويله إلى حساب الشركة، علماً بأن المساحة المؤجرة الفعلية هي <strong>{{ number_format($payment->contract->unit->area ?? 0, 0) }} متر مربع</strong>، بمعدل إيجار <strong>{{ number_format(($payment->contract->annual_rent ?? 0) / (($payment->contract->unit->area ?? 1) > 0 ? $payment->contract->unit->area : 1), 0) }} ريال</strong>.
            </p>
            <p>
                يرفق بيان تفصيلي بالمبالغ المستحقة:
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
                    $allPayments = $contract->rentPayments()->orderBy('due_date', 'asc')->get();
                    $paymentIndex = 1;
                @endphp
                @foreach($allPayments as $index => $rentPayment)
                @php
                    $isPaid = $rentPayment->status === 'paid';
                    $paidAmount = $isPaid ? $rentPayment->total_value : 0;
                    $remainingAmount = $isPaid ? 0 : $rentPayment->total_value;
                @endphp
                <tr>
                    <td>{{ $paymentIndex++ }}</td>
                    <td>{{ $contract->start_date->format('Y.m.d') }}</td>
                    <td>{{ number_format($rentPayment->rent_value, 0) }}</td>
                    <td>{{ $rentPayment->due_date->format('Y.m.d') }}</td>
                    <td>{{ number_format($rentPayment->rent_value, 0) }}</td>
                    <td>{{ number_format($rentPayment->total_value, 0) }}</td>
                    <td>{{ number_format($paidAmount, 0) }}</td>
                    <td>{{ number_format($remainingAmount, 0) }}</td>
                </tr>
                @endforeach
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

        <div class="signature">
            إدارة التأجير
        </div>
    </div>
</body>
</html>
