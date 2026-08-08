<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مطالبة بسداد قسط الإيجار</title>
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
            font-size: 20px;
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

        .section-title {
            margin: 20px 0 10px;
            font-size: 15px;
            font-weight: bold;
            text-align: right;
        }

        .empty-state {
            margin: 15px 0;
            padding: 12px 16px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 13px;
            text-align: right;
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
            text-align: left;
            font-size: 14px;
            font-weight: bold;
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
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 8px 15px;
            font-size: 10px;
            text-align: center;
            direction: rtl;
            z-index: 1000;
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

        @media print {
            .footer-info {
                position: relative;
            }
        }

        @media print {
            body {
                padding: 20px 40px;
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
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .print-button:hover {
            background-color: #333;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .back-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .back-button:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        /* Success Modal Styles */
        .success-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .success-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-modal-content {
            background-color: #fff;
            padding: 0;
            border-radius: 16px;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .success-modal-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            animation: scaleIn 0.5s ease;
        }

        .success-modal-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .success-modal-body {
            padding: 30px 20px;
            text-align: center;
            direction: rtl;
        }

        .success-modal-message {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .success-modal-email {
            font-size: 14px;
            color: #666;
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            direction: ltr;
            text-align: center;
        }

        .success-modal-footer {
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .success-modal-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .success-modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .success-modal-btn:active {
            transform: translateY(0);
        }

        /* Error Modal Styles */
        .error-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .error-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-modal-content {
            background-color: #fff;
            padding: 0;
            border-radius: 16px;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .error-modal-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .error-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            animation: scaleIn 0.5s ease;
        }

        .error-modal-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .error-modal-body {
            padding: 30px 20px;
            text-align: center;
            direction: rtl;
        }

        .error-modal-message {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .error-modal-footer {
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .error-modal-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .error-modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 20px; left: 20px; z-index: 1000; display: flex; gap: 10px;">
        <button onclick="window.print()" class="print-button">🖨️ طباعة</button>
        <a href="{{ url()->previous() !== url()->current() && url()->previous() !== route('login') ? url()->previous() : route('property-management.payments.index') }}"
           class="back-button">
            ← رجوع
        </a>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="success-modal">
        <div class="success-modal-content">
            <div class="success-modal-header">
                <div class="success-icon">✓</div>
                <h2 class="success-modal-title">تم الإرسال بنجاح</h2>
            </div>
            <div class="success-modal-body">
                <p class="success-modal-message">تم إرسال المطالبة المالية بنجاح</p>
                <div class="success-modal-email" id="successEmail"></div>
            </div>
            <div class="success-modal-footer">
                <button class="success-modal-btn" onclick="closeSuccessModal()">حسناً</button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="error-modal">
        <div class="error-modal-content">
            <div class="error-modal-header">
                <div class="error-icon">✕</div>
                <h2 class="error-modal-title">فشل الإرسال</h2>
                </div>
            <div class="error-modal-body">
                <p class="error-modal-message" id="errorMessage"></p>
            </div>
            <div class="error-modal-footer">
                <button class="error-modal-btn" onclick="closeErrorModal()">حسناً</button>
            </div>
        </div>
    </div>
        @if($payment->contract->client->email)
        <button onclick="sendPaymentEmail()" class="print-button" id="sendEmailBtn" style="background-color: #28a745;">
            📧 إرسال بالبريد الإلكتروني
        </button>
        @else
        <button disabled class="print-button" style="background-color: #6c757d; cursor: not-allowed;" title="لا يوجد بريد إلكتروني مسجل للعميل">
            📧 إرسال بالبريد الإلكتروني (غير متوفر)
        </button>
        @endif
    </div>

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

        @php
            $contract = $payment->contract;
            $duePaymentPaidAmount = $duePayment->status === 'paid' ? (float) $duePayment->total_value : 0;
            $duePaymentRemainingAmount = max((float) $duePayment->total_value - $duePaymentPaidAmount, 0);
        @endphp

        <div class="section-title">الدفعات التي تم سدادها</div>
        @if($paidPayments->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>م</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>تاريخ السداد</th>
                        <th>قيمة الإيجار</th>
                        <th>قيمة الخدمات</th>
                        <th>قيمة الضريبة</th>
                        <th>المبالغ الثابتة</th>
                        <th>إجمالي الدفعة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paidPayments as $index => $paidPayment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $paidPayment->due_date->format('Y.m.d') }}</td>
                            <td>{{ optional($paidPayment->payment_date)->format('Y.m.d') ?? '-' }}</td>
                            <td>{{ number_format($paidPayment->rent_value, 0) }}</td>
                            <td>{{ number_format($paidPayment->services_value, 0) }}</td>
                            <td>{{ number_format($paidPayment->vat_value, 0) }}</td>
                            <td>{{ number_format($paidPayment->fixed_amounts ?? 0, 0) }}</td>
                            <td>{{ number_format($paidPayment->total_value, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">لا توجد دفعات تم سدادها سابقاً لهذا العقد.</div>
        @endif

        <div class="section-title">بيانات الدفعة المستحقة</div>
        <table>
            <thead>
                <tr>
                    <th>م</th>
                    <th>تاريخ الاستحقاق</th>
                    <th>تاريخ الإصدار</th>
                    <th>قيمة الإيجار</th>
                    <th>قيمة الخدمات</th>
                    <th>قيمة الضريبة</th>
                    <th>المبالغ الثابتة</th>
                    <th>إجمالي الدفعة</th>
                    <th>المبلغ المسدد</th>
                    <th>المبلغ المتبقي</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $duePayment->due_date->format('Y.m.d') }}</td>
                    <td>{{ $duePayment->issued_date->format('Y.m.d') }}</td>
                    <td>{{ number_format($duePayment->rent_value, 0) }}</td>
                    <td>{{ number_format($duePayment->services_value, 0) }}</td>
                    <td>{{ number_format($duePayment->vat_value, 0) }}</td>
                    <td>{{ number_format($duePayment->fixed_amounts ?? 0, 0) }}</td>
                    <td>{{ number_format($duePayment->total_value, 0) }}</td>
                    <td>{{ number_format($duePaymentPaidAmount, 0) }}</td>
                    <td>{{ number_format($duePaymentRemainingAmount, 0) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="conclusion">
            الرجاء التكرم وسداد المبلغ المستحق في أقرب وقت ممكن .
        </div>

        <div class="bank-details">
            <p><strong>اسم الحساب :</strong> FAHAD NAWAF ALZEER TRADING GROUP</p>
            <p><strong>رقم الايبان :</strong> {{ $claimBankIban ?? \App\PropertyManagement\Support\ClaimMailSettings::bankIban() }}</p>
            <p><strong>اسم البنك :</strong> البنك السعودي الفرنسي</p>
            <p><strong>كود سويفت :</strong> BSFRSARI</p>
        </div>

        <div class="closing">
            وتفضلوا بقبول فائق الاحترام والتقدير...
        </div>

    </div>

    <!-- Footer Info -->
    <div class="footer-info">
        <span>{{ !empty($companyInfo['name']) ? $companyInfo['name'] : 'Alzeer Holding' }}</span>
        <span><strong>رقم الهاتف:</strong> {{ !empty($companyInfo['phone']) ? $companyInfo['phone'] : '0551268748' }}</span>
        <span><strong>البريد الإلكتروني:</strong> {{ !empty($companyInfo['email']) ? $companyInfo['email'] : 'info@alzeer-holding.com' }}</span>
    </div>

    @if($payment->contract->client->email)
    <script>
        function sendPaymentEmail() {
            const btn = document.getElementById('sendEmailBtn');
            const originalText = btn.innerHTML;

            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = '⏳ جاري الإرسال...';
            btn.style.backgroundColor = '#6c757d';

            fetch('{{ route("property-management.payments.send-email", $payment->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.innerHTML = '✅ تم الإرسال بنجاح';
                    btn.style.backgroundColor = '#28a745';

                    // Show success modal
                    document.getElementById('successEmail').textContent = '{{ $payment->contract->client->email }}';
                    showSuccessModal();

                    // Reset button after 3 seconds
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        btn.style.backgroundColor = '#28a745';
                    }, 3000);
                } else {
                    btn.innerHTML = '❌ فشل الإرسال';
                    btn.style.backgroundColor = '#dc3545';

                    // Show error modal
                    document.getElementById('errorMessage').textContent = data.message || 'حدث خطأ أثناء إرسال البريد';
                    showErrorModal();

                    // Reset button after 3 seconds
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        btn.style.backgroundColor = '#28a745';
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = '❌ خطأ في الاتصال';
                btn.style.backgroundColor = '#dc3545';

                // Show error modal
                document.getElementById('errorMessage').textContent = 'حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.';
                showErrorModal();

                // Reset button after 3 seconds
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    btn.style.backgroundColor = '#28a745';
                }, 3000);
            });
        }

        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.add('show');
        }

        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.classList.remove('show');
        }

        function showErrorModal() {
            const modal = document.getElementById('errorModal');
            modal.classList.add('show');
        }

        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            modal.classList.remove('show');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const successModal = document.getElementById('successModal');
            const errorModal = document.getElementById('errorModal');
            if (event.target == successModal) {
                closeSuccessModal();
            }
            if (event.target == errorModal) {
                closeErrorModal();
            }
        }
    </script>
    @endif
</body>
</html>
