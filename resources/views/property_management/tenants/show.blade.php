@extends('master')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تفاصيل العميل: {{ $tenant->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- معلومات العميل -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-user me-2"></i>
                            معلومات العميل
                        </h5>
                        <div>
                            <a href="{{ route('property-management.tenants.index') }}" class="btn btn-sm btn-secondary">
                                <i class="ti ti-arrow-right"></i> العودة للقائمة
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">الاسم:</th>
                                        <td><strong>{{ $tenant->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>نوع العميل:</th>
                                        <td>
                                            <span class="badge bg-secondary">{{ $tenant->client_type }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>رقم الهوية / السجل التجاري:</th>
                                        <td>{{ $tenant->id_number_or_cr }}</td>
                                    </tr>
                                    @if($tenant->id_type)
                                    <tr>
                                        <th>نوع الهوية:</th>
                                        <td>{{ $tenant->id_type }}</td>
                                    </tr>
                                    @endif
                                    @if($tenant->nationality)
                                    <tr>
                                        <th>الجنسية:</th>
                                        <td>{{ $tenant->nationality }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">رقم الجوال:</th>
                                        <td>{{ $tenant->mobile }}</td>
                                    </tr>
                                    @if($tenant->email)
                                    <tr>
                                        <th>البريد الإلكتروني:</th>
                                        <td>{{ $tenant->email }}</td>
                                    </tr>
                                    @endif
                                    @if($tenant->national_address)
                                    <tr>
                                        <th>العنوان الوطني:</th>
                                        <td>{{ $tenant->national_address }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>تاريخ التسجيل:</th>
                                        <td>{{ $tenant->created_at ? $tenant->created_at->format('Y-m-d H:i') : 'غير محدد' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- كشف الحساب -->
                @if(isset($statement))
                @php
                    // حساب المستحقات من جميع الدفعات
                    $totalDue = 0;
                    $totalPaid = 0;
                    $overdue = 0;

                    foreach($tenant->contracts as $contract) {
                        foreach($contract->rentPayments as $payment) {
                            $totalDue += $payment->total_value;
                            if($payment->status == 'paid') {
                                $totalPaid += $payment->total_value;
                            } elseif($payment->status == 'partially_paid') {
                                // يمكن إضافة منطق لحساب المدفوع جزئياً لاحقاً
                                $totalPaid += $payment->total_value * 0.5; // تقدير
                            }

                            // حساب المتأخرات
                            if($payment->status != 'paid' && $payment->due_date < now()) {
                                $overdue += $payment->total_value;
                            }
                        }
                    }

                    $balance = $statement['summary']['balance'] ?? 0;
                @endphp
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-dark text-white">
                            <div class="card-body text-center">
                                <h4 class="text-white">{{ number_format($totalDue, 2) }} ريال</h4>
                                <p class="mb-0 text-white">إجمالي المستحق</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-secondary text-white">
                            <div class="card-body text-center">
                                <h4 class="text-white">{{ number_format($totalPaid, 2) }} ريال</h4>
                                <p class="mb-0 text-white">إجمالي المدفوع</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card-available text-white">
                            <div class="card-body text-center">
                                <h4 class="text-white">{{ number_format($balance, 2) }} ريال</h4>
                                <p class="mb-0 text-white">الرصيد</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card-occupied text-white">
                            <div class="card-body text-center">
                                <h4 class="text-white">{{ number_format($overdue, 2) }} ريال</h4>
                                <p class="mb-0 text-white">المتأخر</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- العقود -->
                @if($tenant->contracts && $tenant->contracts->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-file-text me-2"></i>
                            العقود ({{ $tenant->contracts->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>رقم العقد</th>
                                        <th>المبنى</th>
                                        <th>الوحدة</th>
                                        <th>تاريخ البدء</th>
                                        <th>تاريخ الانتهاء</th>
                                        <th class="text-end">الإيجار السنوي</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tenant->contracts as $index => $contract)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="text-decoration-none">
                                                <strong>{{ $contract->contract_number }}</strong>
                                            </a>
                                        </td>
                                        <td class="align-middle">
                                            @if($contract->unit && $contract->unit->building)
                                                <a href="{{ route('property-management.buildings.show', $contract->unit->building->id) }}" class="text-decoration-none">
                                                    {{ $contract->unit->building->name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($contract->unit)
                                                {{ $contract->unit->unit_number }} - {{ $contract->unit->unit_type }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $contract->start_date->format('Y-m-d') }}</td>
                                        <td class="align-middle">{{ $contract->end_date->format('Y-m-d') }}</td>
                                        <td class="text-end align-middle">{{ number_format($contract->annual_rent, 2) }} ريال</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="btn btn-sm btn-secondary">
                                                <i class="ti ti-eye"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                <div class="card mb-4">
                    <div class="card-body text-center py-4">
                        <i class="ti ti-file-off" style="font-size: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-2">لا توجد عقود لهذا العميل</p>
                    </div>
                </div>
                @endif

                <!-- جدول الدفعات -->
                @php
                    // جمع جميع الدفعات من جميع العقود
                    $allPayments = collect();
                    foreach($tenant->contracts as $contract) {
                        foreach($contract->rentPayments as $payment) {
                            $allPayments->push([
                                'payment' => $payment,
                                'contract' => $contract,
                            ]);
                        }
                    }
                    // ترتيب حسب تاريخ الاستحقاق
                    $allPayments = $allPayments->sortBy(function($item) {
                        return $item['payment']->due_date;
                    });
                @endphp

                @if($allPayments->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-currency-dollar me-2"></i>
                            جدول الدفعات ({{ $allPayments->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>رقم العقد</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>تاريخ الإصدار</th>
                                        <th class="text-end">قيمة الإيجار</th>
                                        <th class="text-end">قيمة الخدمات</th>
                                        <th class="text-end">قيمة الضريبة</th>
                                        <th class="text-end">المبالغ الثابتة</th>
                                        <th class="text-end">الإجمالي</th>
                                        <th class="text-center">الحالة</th>
                                        <th>تاريخ الدفع</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allPayments as $index => $item)
                                    @php
                                        $payment = $item['payment'];
                                        $contract = $item['contract'];
                                    @endphp
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="text-decoration-none">
                                                <strong>{{ $contract->contract_number }}</strong>
                                            </a>
                                        </td>
                                        <td class="align-middle">
                                            {{ $payment->due_date->format('Y-m-d') }}
                                            @if($payment->due_date < now() && $payment->status != 'paid')
                                                <span class="badge bg-danger ms-1">متأخر</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $payment->issued_date->format('Y-m-d') }}</td>
                                        <td class="text-end align-middle">
                                            @if(auth()->user()->role === 'accountant' || auth()->user()->role === 'editor')
                                                <input type="number"
                                                       class="form-control form-control-sm editable-field text-end"
                                                       data-payment-id="{{ $payment->id }}"
                                                       data-field="rent_value"
                                                       value="{{ $payment->rent_value }}"
                                                       step="0.01"
                                                       min="0"
                                                       style="width: 120px; display: inline-block;">
                                                <span class="ms-1">ريال</span>
                                            @else
                                                {{ number_format($payment->rent_value, 2) }} ريال
                                            @endif
                                        </td>
                                        <td class="text-end align-middle">{{ number_format($payment->services_value, 2) }} ريال</td>
                                        <td class="text-end align-middle">{{ number_format($payment->vat_value, 2) }} ريال</td>
                                        <td class="text-end align-middle">
                                            @if(auth()->user()->role === 'accountant' || auth()->user()->role === 'editor')
                                                <input type="number"
                                                       class="form-control form-control-sm editable-field text-end"
                                                       data-payment-id="{{ $payment->id }}"
                                                       data-field="fixed_amounts"
                                                       value="{{ $payment->fixed_amounts ?? 0 }}"
                                                       step="0.01"
                                                       min="0"
                                                       style="width: 120px; display: inline-block;">
                                                <span class="ms-1">ريال</span>
                                            @else
                                                {{ number_format($payment->fixed_amounts ?? 0, 2) }} ريال
                                            @endif
                                        </td>
                                        <td class="text-end align-middle">
                                            @if(auth()->user()->role === 'accountant' || auth()->user()->role === 'editor')
                                                <input type="number"
                                                       class="form-control form-control-sm editable-field text-end"
                                                       data-payment-id="{{ $payment->id }}"
                                                       data-field="total_value"
                                                       value="{{ $payment->total_value }}"
                                                       step="0.01"
                                                       min="0"
                                                       style="width: 120px; display: inline-block;">
                                                <span class="ms-1">ريال</span>
                                            @else
                                                <strong>{{ number_format($payment->total_value, 2) }}</strong> ريال
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($payment->status == 'paid')
                                                <span class="badge bg-success">مدفوع</span>
                                            @elseif($payment->status == 'partially_paid')
                                                <span class="badge bg-warning text-dark">مدفوع جزئياً</span>
                                            @else
                                                <span class="badge bg-danger">غير مدفوع</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($payment->payment_date)
                                                {{ $payment->payment_date->format('Y-m-d') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">الإجمالي:</th>
                                        <th class="text-end">{{ number_format($allPayments->sum(function($item) { return $item['payment']->rent_value; }), 2) }} ريال</th>
                                        <th class="text-end">{{ number_format($allPayments->sum(function($item) { return $item['payment']->services_value; }), 2) }} ريال</th>
                                        <th class="text-end">{{ number_format($allPayments->sum(function($item) { return $item['payment']->vat_value; }), 2) }} ريال</th>
                                        <th class="text-end">{{ number_format($allPayments->sum(function($item) { return $item['payment']->fixed_amounts ?? 0; }), 2) }} ريال</th>
                                        <th class="text-end">{{ number_format($allPayments->sum(function($item) { return $item['payment']->total_value; }), 2) }} ريال</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                <div class="card mb-4">
                    <div class="card-body text-center py-4">
                        <i class="ti ti-currency-dollar-off" style="font-size: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-2">لا توجد دفعات لهذا العميل</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .stats-card-available {
        background-color: #198754 !important;
    }

    .stats-card-occupied {
        background-color: #dc3545 !important;
    }

    .table th {
        font-weight: 600;
        color: #495057;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .editable-field {
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .editable-field:focus {
        border-color: #212529;
        box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
        background-color: #fff;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editableFields = document.querySelectorAll('.editable-field');

    editableFields.forEach(field => {
        let originalValue = field.value;
        let timeout;

        // عند تغيير القيمة
        field.addEventListener('input', function() {
            clearTimeout(timeout);
            const paymentId = this.getAttribute('data-payment-id');
            const fieldName = this.getAttribute('data-field');

            // تحديث الإجمالي فوراً
            updateTotal(paymentId);

            // حفظ التعديلات بعد 1 ثانية من التوقف عن الكتابة
            timeout = setTimeout(() => {
                savePaymentField(paymentId, fieldName, this.value);
            }, 1000);
        });

        // عند الضغط على Enter
        field.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(timeout);
                const paymentId = this.getAttribute('data-payment-id');
                const fieldName = this.getAttribute('data-field');
                savePaymentField(paymentId, fieldName, this.value);
                this.blur();
            }
        });

        // عند فقدان التركيز
        field.addEventListener('blur', function() {
            clearTimeout(timeout);
            const paymentId = this.getAttribute('data-payment-id');
            const fieldName = this.getAttribute('data-field');
            savePaymentField(paymentId, fieldName, this.value);
        });
    });

    function updateTotal(paymentId) {
        const row = document.querySelector(`input[data-payment-id="${paymentId}"]`).closest('tr');
        const rentValueInput = row.querySelector('input[data-field="rent_value"]');
        const fixedAmountsInput = row.querySelector('input[data-field="fixed_amounts"]');
        const totalValueInput = row.querySelector('input[data-field="total_value"]');

        if (rentValueInput && fixedAmountsInput && totalValueInput) {
            // الحصول على القيم الثابتة من النص (الخدمات والضريبة)
            const servicesText = row.querySelector('td:nth-child(6)').textContent.trim();
            const vatText = row.querySelector('td:nth-child(7)').textContent.trim();
            const servicesValue = parseFloat(servicesText.replace(/[^\d.]/g, '')) || 0;
            const vatValue = parseFloat(vatText.replace(/[^\d.]/g, '')) || 0;

            const rentValue = parseFloat(rentValueInput.value) || 0;
            const fixedAmounts = parseFloat(fixedAmountsInput.value) || 0;

            // تحديث الإجمالي فقط إذا لم يتم تعديله مباشرة
            if (document.activeElement !== totalValueInput) {
                const total = rentValue + servicesValue + vatValue + fixedAmounts;
                totalValueInput.value = total.toFixed(2);
            }
        }
    }

    function savePaymentField(paymentId, fieldName, value) {
        const data = {
            [fieldName]: parseFloat(value) || 0
        };

        fetch(`/property-management/payments/${paymentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                               document.querySelector('input[name="_token"]')?.value
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // تحديث الإجمالي من الاستجابة
                const totalInput = document.querySelector(`input[data-payment-id="${paymentId}"][data-field="total_value"]`);
                if (totalInput && data.payment) {
                    totalInput.value = parseFloat(data.payment.total_value).toFixed(2);
                }

                // إظهار رسالة نجاح خفيفة
                const field = document.querySelector(`input[data-payment-id="${paymentId}"][data-field="${fieldName}"]`);
                if (field) {
                    field.style.borderColor = '#28a745';
                    setTimeout(() => {
                        field.style.borderColor = '#dee2e6';
                    }, 1000);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حفظ التعديلات');
        });
    }
});
</script>
@endsection

