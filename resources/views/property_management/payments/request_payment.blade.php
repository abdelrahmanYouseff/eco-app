<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مطالبة بدفع الإيجار</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Tahoma', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #000;
            background: #fff;
            padding: 40px;
            direction: rtl;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #000;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .header .date {
            font-size: 14px;
            margin-top: 10px;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-item.full-width {
            grid-column: 1 / -1;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            min-width: 150px;
            margin-left: 10px;
        }
        
        .info-value {
            display: inline-block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        
        table th,
        table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: right;
        }
        
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        table td.amount {
            text-align: left;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #000;
        }
        
        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        
        .signature-box {
            text-align: center;
            padding-top: 60px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 5px;
            padding-top: 5px;
        }
        
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
        }
        
        .notes h3 {
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .notes ul {
            margin-right: 20px;
            list-style-type: disc;
        }
        
        .notes li {
            margin-bottom: 5px;
        }
        
        @media print {
            body {
                padding: 20px;
            }
            
            .no-print {
                display: none;
            }
            
            @page {
                margin: 2cm;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #000;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ طباعة</button>
    
    <div class="container">
        <div class="header">
            <h1>مطالبة بدفع الإيجار المستحق</h1>
            <div class="date">تاريخ اليوم: {{ \Carbon\Carbon::now()->locale('ar')->format('Y-m-d') }}</div>
        </div>
        
        <!-- تفاصيل العميل -->
        <div class="section">
            <h2 class="section-title">بيانات العميل</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">الاسم:</span>
                    <span class="info-value">{{ $payment->contract->client->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">نوع العميل:</span>
                    <span class="info-value">{{ $payment->contract->client->client_type }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">رقم الهوية / السجل التجاري:</span>
                    <span class="info-value">{{ $payment->contract->client->id_number_or_cr }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">رقم الجوال:</span>
                    <span class="info-value">{{ $payment->contract->client->mobile }}</span>
                </div>
                @if($payment->contract->client->email)
                <div class="info-item">
                    <span class="info-label">البريد الإلكتروني:</span>
                    <span class="info-value">{{ $payment->contract->client->email }}</span>
                </div>
                @endif
                @if($payment->contract->client->national_address)
                <div class="info-item full-width">
                    <span class="info-label">العنوان الوطني:</span>
                    <span class="info-value">{{ $payment->contract->client->national_address }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <!-- تفاصيل العقد -->
        <div class="section">
            <h2 class="section-title">تفاصيل العقد</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">رقم العقد:</span>
                    <span class="info-value">{{ $payment->contract->contract_number }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">نوع العقد:</span>
                    <span class="info-value">{{ $payment->contract->contract_type }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">المبنى:</span>
                    <span class="info-value">{{ $payment->contract->building->name ?? 'غير محدد' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">الوحدة / المكتب:</span>
                    <span class="info-value">{{ $payment->contract->unit->unit_number ?? 'غير محدد' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">تاريخ بداية العقد:</span>
                    <span class="info-value">{{ $payment->contract->start_date->format('Y-m-d') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">تاريخ نهاية العقد:</span>
                    <span class="info-value">{{ $payment->contract->end_date->format('Y-m-d') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">الإيجار السنوي:</span>
                    <span class="info-value">{{ number_format($payment->contract->annual_rent, 2) }} ريال سعودي</span>
                </div>
                <div class="info-item">
                    <span class="info-label">دورة الدفع:</span>
                    <span class="info-value">{{ $payment->contract->rent_cycle }} شهر</span>
                </div>
            </div>
        </div>
        
        <!-- تفاصيل الدفعة المستحقة -->
        <div class="section">
            <h2 class="section-title">تفاصيل الدفعة المستحقة</h2>
            <table>
                <thead>
                    <tr>
                        <th>البند</th>
                        <th class="amount">المبلغ (ريال سعودي)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>الإيجار</td>
                        <td class="amount">{{ number_format($payment->rent_value, 2) }}</td>
                    </tr>
                    @if($payment->services_value > 0)
                    <tr>
                        <td>الخدمات العامة</td>
                        <td class="amount">{{ number_format($payment->services_value, 2) }}</td>
                    </tr>
                    @endif
                    @if($payment->vat_value > 0)
                    <tr>
                        <td>ضريبة القيمة المضافة (VAT)</td>
                        <td class="amount">{{ number_format($payment->vat_value, 2) }}</td>
                    </tr>
                    @endif
                    @if($payment->fixed_amounts && $payment->fixed_amounts > 0)
                    <tr>
                        <td>مبالغ ثابتة</td>
                        <td class="amount">{{ number_format($payment->fixed_amounts, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td><strong>الإجمالي المستحق</strong></td>
                        <td class="amount"><strong>{{ number_format($payment->total_value, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">تاريخ الاستحقاق:</span>
                    <span class="info-value"><strong>{{ $payment->due_date->format('Y-m-d') }}</strong></span>
                </div>
                <div class="info-item">
                    <span class="info-label">تاريخ إصدار المطالبة:</span>
                    <span class="info-value">{{ $payment->issued_date ? $payment->issued_date->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}</span>
                </div>
                @php
                    $daysOverdue = 0;
                    if ($payment->status !== 'paid' && $payment->due_date < now()) {
                        $daysOverdue = now()->diffInDays($payment->due_date);
                    }
                @endphp
                @if($daysOverdue > 0)
                <div class="info-item full-width">
                    <span class="info-label">أيام التأخير:</span>
                    <span class="info-value"><strong style="color: #d00;">{{ $daysOverdue }} يوم</strong></span>
                </div>
                @endif
            </div>
        </div>
        
        <!-- ملاحظات -->
        <div class="notes">
            <h3>ملاحظات مهمة:</h3>
            <ul>
                <li>يرجى السداد في الموعد المحدد لتجنب التأخير والإجراءات القانونية.</li>
                <li>في حالة التأخير في السداد، قد يتم تطبيق رسوم تأخير وفقاً لشروط العقد.</li>
                <li>يرجى الاحتفاظ بهذه المطالبة كإثبات للسداد.</li>
                <li>للاستفسار، يرجى التواصل مع إدارة المبنى.</li>
            </ul>
        </div>
        
        <!-- التوقيعات -->
        <div class="footer">
            <div class="signature-section">
                <div class="signature-box">
                    <div>توقيع العميل / المكلف</div>
                    <div class="signature-line"></div>
                    <div style="margin-top: 10px; font-size: 12px;">الاسم: {{ $payment->contract->client->name }}</div>
                    <div style="font-size: 12px;">التاريخ: _________________</div>
                </div>
                <div class="signature-box">
                    <div>توقيع مالك المبنى / الممثل</div>
                    <div class="signature-line"></div>
                    <div style="margin-top: 10px; font-size: 12px;">الاسم: _________________</div>
                    <div style="font-size: 12px;">التاريخ: _________________</div>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
            <p>تم إصدار هذه المطالبة بتاريخ {{ \Carbon\Carbon::now()->locale('ar')->format('Y-m-d') }}</p>
            <p>رقم المطالبة: PM-{{ $payment->id }}-{{ date('Ymd') }}</p>
        </div>
    </div>
</body>
</html>

