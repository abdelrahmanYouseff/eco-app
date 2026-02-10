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
                            <h2 class="mb-0">تفاصيل العقد: {{ $contract->contract_number }}</h2>
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

                <!-- معلومات العقد الأساسية -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-file-text me-2"></i>
                            معلومات العقد
                        </h5>
                        <div>
                            <a href="{{ route('property-management.contracts.index') }}" class="btn btn-sm btn-secondary">
                                <i class="ti ti-arrow-right"></i> العودة للقائمة
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">رقم العقد:</th>
                                        <td><strong>{{ $contract->contract_number }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>نوع العقد:</th>
                                        <td>
                                            <span class="badge bg-secondary">{{ $contract->contract_type }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>المبنى:</th>
                                        <td>
                                            <a href="{{ route('property-management.buildings.show', $contract->building_id) }}" class="text-decoration-none">
                                                {{ $contract->building->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>الوحدة:</th>
                                        <td>
                                            <a href="{{ route('property-management.units.show', $contract->unit_id) }}" class="text-decoration-none">
                                                {{ $contract->unit->unit_number }} - {{ $contract->unit->unit_type }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>العميل:</th>
                                        <td>
                                            <a href="{{ route('property-management.tenants.show', $contract->client_id) }}" class="text-decoration-none">
                                                {{ $contract->client->name }} ({{ $contract->client->client_type }})
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">تاريخ البدء:</th>
                                        <td>{{ \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الانتهاء:</th>
                                        <td>{{ \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <th>مدة العقد:</th>
                                        <td>
                                            @php
                                                $start = \Carbon\Carbon::parse($contract->start_date);
                                                $end = \Carbon\Carbon::parse($contract->end_date);
                                                $months = $start->diffInMonths($end);
                                            @endphp
                                            {{ $months }} شهر
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>حالة العقد:</th>
                                        <td>
                                            @php
                                                $now = now();
                                                $startDate = \Carbon\Carbon::parse($contract->start_date);
                                                $endDate = \Carbon\Carbon::parse($contract->end_date);
                                            @endphp
                                            @if($now->lt($startDate))
                                                <span class="badge bg-info">قادم</span>
                                            @elseif($now->gt($endDate))
                                                <span class="badge bg-secondary">منتهي</span>
                                            @else
                                                <span class="badge bg-success">نشط</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>عقد مشروط:</th>
                                        <td>
                                            @if($contract->is_conditional)
                                                <span class="badge bg-warning text-dark">نعم</span>
                                            @else
                                                <span class="badge bg-secondary">لا</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($contract->broker)
                                    <tr>
                                        <th>الوسيط:</th>
                                        <td>{{ $contract->broker->name }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- معلومات العميل -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-user me-2"></i>
                            معلومات العميل
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">الاسم:</th>
                                        <td>
                                            <strong>{{ $contract->client->name }}</strong>
                                            <a href="{{ route('property-management.tenants.show', $contract->client_id) }}"
                                               class="btn btn-sm btn-outline-dark ms-2"
                                               title="عرض تفاصيل العميل">
                                                <i class="ti ti-eye"></i> عرض التفاصيل
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>نوع العميل:</th>
                                        <td>
                                            <span class="badge bg-secondary">{{ $contract->client->client_type }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>رقم الهوية / السجل التجاري:</th>
                                        <td>{{ $contract->client->id_number_or_cr }}</td>
                                    </tr>
                                    @if($contract->client->id_type)
                                    <tr>
                                        <th>نوع الهوية:</th>
                                        <td>{{ $contract->client->id_type }}</td>
                                    </tr>
                                    @endif
                                    @if($contract->client->nationality)
                                    <tr>
                                        <th>الجنسية:</th>
                                        <td>{{ $contract->client->nationality }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">رقم الجوال:</th>
                                        <td>
                                            <a href="tel:{{ $contract->client->mobile }}" class="text-decoration-none">
                                                <i class="ti ti-phone me-1"></i>{{ $contract->client->mobile }}
                                            </a>
                                        </td>
                                    </tr>
                                    @if($contract->client->email)
                                    <tr>
                                        <th>البريد الإلكتروني:</th>
                                        <td>
                                            <a href="mailto:{{ $contract->client->email }}" class="text-decoration-none">
                                                <i class="ti ti-mail me-1"></i>{{ $contract->client->email }}
                                            </a>
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <th>البريد الإلكتروني:</th>
                                        <td><span class="text-muted">غير متوفر</span></td>
                                    </tr>
                                    @endif
                                    @if($contract->client->national_address)
                                    <tr>
                                        <th>العنوان الوطني:</th>
                                        <td>{{ $contract->client->national_address }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- المبالغ المالية -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-dark text-white">
                            <div class="card-body text-center">
                                <h4 class="text-white">{{ number_format($contract->total_rent, 2) }}</h4>
                                <p class="mb-0 text-white">إجمالي الإيجار</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-secondary text-white">
                            <div class="card-body text-center">
                                <h4 class="text-white">{{ number_format($dueAmounts['total_paid'] ?? 0, 2) }}</h4>
                                <p class="mb-0 text-white">المدفوع</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card-available text-white">
                            <div class="card-body text-center">
                                <h4 class="text-white">{{ number_format($dueAmounts['total_due'] ?? 0, 2) }}</h4>
                                <p class="mb-0 text-white">المستحق</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card-occupied text-white">
                            <div class="card-body text-center">
                                <h4 class="text-white">{{ number_format($dueAmounts['overdue'] ?? 0, 2) }}</h4>
                                <p class="mb-0 text-white">المتأخر</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تفاصيل المبالغ -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-currency-dollar me-2"></i>
                            تفاصيل المبالغ المالية
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">الإيجار السنوي:</th>
                                        <td>{{ number_format($contract->annual_rent, 2) }} ريال</td>
                                    </tr>
                                    <tr>
                                        <th>دورة الإيجار:</th>
                                        <td>{{ $contract->rent_cycle }} شهر</td>
                                    </tr>
                                    <tr>
                                        <th>مبلغ التأمين:</th>
                                        <td>{{ number_format($contract->deposit_amount, 2) }} ريال</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">ضريبة القيمة المضافة:</th>
                                        <td>{{ number_format($contract->vat_amount, 2) }} ريال</td>
                                    </tr>
                                    <tr>
                                        <th>الخدمات العامة:</th>
                                        <td>{{ number_format($contract->general_services_amount, 2) }} ريال</td>
                                    </tr>
                                    <tr>
                                        <th>المبالغ الثابتة:</th>
                                        <td>{{ number_format($contract->fixed_amounts ?? 0, 2) }} ريال</td>
                                    </tr>
                                    @if($contract->insurance_policy_number)
                                    <tr>
                                        <th>رقم بوليصة التأمين:</th>
                                        <td>{{ $contract->insurance_policy_number }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الممثلون -->
                @if($contract->representatives && $contract->representatives->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-users me-2"></i>
                            الممثلون
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>الدور</th>
                                        <th>الاسم</th>
                                        <th>نوع الهوية</th>
                                        <th>رقم الهوية</th>
                                        <th>الجنسية</th>
                                        <th>الجوال</th>
                                        <th>البريد الإلكتروني</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contract->representatives as $representative)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $representative->role == 'lessor' ? 'المؤجر' : 'المستأجر' }}
                                            </span>
                                        </td>
                                        <td>{{ $representative->name }}</td>
                                        <td>{{ $representative->id_type ?? '-' }}</td>
                                        <td>{{ $representative->id_number ?? '-' }}</td>
                                        <td>{{ $representative->nationality ?? '-' }}</td>
                                        <td>{{ $representative->mobile ?? '-' }}</td>
                                        <td>{{ $representative->email ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- جدول الدفعات -->
                @if($contract->rentPayments && $contract->rentPayments->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-calendar me-2"></i>
                            جدول الدفعات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>تاريخ الإصدار</th>
                                        <th class="text-end">قيمة الإيجار</th>
                                        <th class="text-end">قيمة الخدمات</th>
                                        <th class="text-end">قيمة الضريبة</th>
                                        <th class="text-end">المبالغ الثابتة</th>
                                        <th class="text-end">الإجمالي</th>
                                        <th class="text-center">الحالة</th>
                                        <th>تاريخ الدفع</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contract->rentPayments->sortBy('due_date') as $index => $payment)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->due_date)->format('Y-m-d') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->issued_date)->format('Y-m-d') }}</td>
                                        <td class="text-end">
                                            @if(auth()->user()->role === 'accountant')
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
                                        <td class="text-end">{{ number_format($payment->services_value, 2) }} ريال</td>
                                        <td class="text-end">{{ number_format($payment->vat_value, 2) }} ريال</td>
                                        <td class="text-end">
                                            @if(auth()->user()->role === 'accountant')
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
                                        <td class="text-end">
                                            @if(auth()->user()->role === 'accountant')
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
                                        <td class="text-center">
                                            @if($payment->status == 'paid')
                                                <span class="badge bg-success">مدفوع</span>
                                            @elseif($payment->status == 'partially_paid')
                                                <span class="badge bg-warning text-dark">مدفوع جزئياً</span>
                                            @else
                                                <span class="badge bg-danger">غير مدفوع</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->payment_date)
                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($payment->status != 'paid')
                                                @if(auth()->user()->role !== 'editor')
                                                    <button type="button"
                                                            class="btn btn-sm btn-dark"
                                                            title="سداد الدفعة"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#paymentModal{{ $payment->id }}">
                                                        سداد الدفعة
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- جدول الفواتير -->
                @if($contract->invoices && $contract->invoices->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-file-invoice me-2"></i>
                            الفواتير ({{ $contract->invoices->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>رقم الفاتورة</th>
                                        <th>تاريخ الفاتورة</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th class="text-end">المبلغ الفرعي</th>
                                        <th class="text-end">ضريبة القيمة المضافة</th>
                                        <th class="text-end">الإجمالي</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contract->invoices->sortByDesc('invoice_date') as $index => $invoice)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <strong>{{ $invoice->invoice_number }}</strong>
                                        </td>
                                        <td class="align-middle">{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                        <td class="align-middle">
                                            {{ $invoice->due_date->format('Y-m-d') }}
                                            @if($invoice->due_date < now() && $invoice->status != 'paid')
                                                <span class="badge bg-danger ms-1">متأخر</span>
                                            @endif
                                        </td>
                                        <td class="text-end align-middle">{{ number_format($invoice->subtotal, 2) }} ريال</td>
                                        <td class="text-end align-middle">{{ number_format($invoice->vat_amount, 2) }} ريال</td>
                                        <td class="text-end align-middle">
                                            <strong>{{ number_format($invoice->total_amount, 2) }} ريال</strong>
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($invoice->status == 'paid')
                                                <span class="badge bg-success">مدفوع</span>
                                            @elseif($invoice->status == 'sent')
                                                <span class="badge bg-info">مرسل</span>
                                            @elseif($invoice->status == 'draft')
                                                <span class="badge bg-secondary">مسودة</span>
                                            @else
                                                <span class="badge bg-danger">ملغي</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('property-management.invoices.show', $invoice->id) }}"
                                               class="btn btn-sm btn-info"
                                               title="عرض">
                                                <i class="ti ti-eye"></i>
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
                        <i class="ti ti-file-invoice-off" style="font-size: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-2">لا توجد فواتير لهذا العقد</p>
                    </div>
                </div>
                @endif

                <!-- جدول سندات القبض -->
                @if($contract->receiptVouchers && $contract->receiptVouchers->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-receipt me-2"></i>
                            سندات القبض ({{ $contract->receiptVouchers->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>رقم السند</th>
                                        <th>تاريخ السند</th>
                                        <th class="text-end">المبلغ</th>
                                        <th>طريقة الدفع</th>
                                        <th>رقم المرجع</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contract->receiptVouchers->sortByDesc('receipt_date') as $index => $receipt)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <strong>{{ $receipt->receipt_number }}</strong>
                                        </td>
                                        <td class="align-middle">{{ $receipt->receipt_date->format('Y-m-d') }}</td>
                                        <td class="text-end align-middle">
                                            <strong>{{ number_format($receipt->amount, 2) }} ريال</strong>
                                        </td>
                                        <td class="align-middle">
                                            @if($receipt->payment_method == 'cash')
                                                <span class="badge bg-success">نقدي</span>
                                            @elseif($receipt->payment_method == 'bank_transfer')
                                                <span class="badge bg-info">تحويل بنكي</span>
                                            @elseif($receipt->payment_method == 'check')
                                                <span class="badge bg-warning text-dark">شيك</span>
                                            @elseif($receipt->payment_method == 'credit_card')
                                                <span class="badge bg-primary">بطاقة ائتمان</span>
                                            @else
                                                <span class="badge bg-secondary">أخرى</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            {{ $receipt->reference_number ?? '-' }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('property-management.receipt-vouchers.show', $receipt->id) }}"
                                               class="btn btn-sm btn-info"
                                               title="عرض">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">الإجمالي:</th>
                                        <th class="text-end">{{ number_format($contract->receiptVouchers->sum('amount'), 2) }} ريال</th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                <div class="card mb-4">
                    <div class="card-body text-center py-4">
                        <i class="ti ti-receipt-off" style="font-size: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-2">لا توجد سندات قبض لهذا العقد</p>
                    </div>
                </div>
                @endif

                <!-- مستندات العقد -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-files me-2"></i>
                            مستندات العقد
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($contract->contract_pdf_path)
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ti ti-file-pdf text-danger" style="font-size: 24px;"></i>
                                        <div>
                                            <h6 class="mb-0">ملف العقد</h6>
                                            <small class="text-muted">PDF Document</small>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('property-management.contracts.view-pdf', $contract->id) }}"
                                           class="btn btn-sm btn-primary"
                                           target="_blank">
                                            <i class="ti ti-eye"></i> عرض الملف
                                        </a>
                                        <a href="{{ route('property-management.contracts.download-pdf', $contract->id) }}"
                                           class="btn btn-sm btn-outline-dark">
                                            <i class="ti ti-download"></i> تحميل
                                        </a>
                                        <form action="{{ route('property-management.contracts.delete-pdf', $contract->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف ملف العقد؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ti ti-trash"></i> حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info mb-3">
                                <i class="ti ti-info-circle me-2"></i>
                                لا يوجد ملف PDF مرفق للعقد
                            </div>
                        @endif

                        <div class="border-top pt-3">
                            <h6 class="mb-3">
                                <i class="ti ti-upload me-2"></i>
                                رفع ملف جديد
                            </h6>
                            <form action="{{ route('property-management.contracts.upload-pdf', $contract->id) }}"
                                  method="POST"
                                  enctype="multipart/form-data"
                                  class="d-flex align-items-end gap-2">
                                @csrf
                                <div class="flex-grow-1">
                                    <input type="file"
                                           name="contract_pdf"
                                           id="contract_pdf"
                                           class="form-control @error('contract_pdf') is-invalid @enderror"
                                           accept=".pdf"
                                           required>
                                    @error('contract_pdf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">الحد الأقصى لحجم الملف: 10 ميجابايت (PDF فقط)</small>
                                </div>
                                <button type="submit" class="btn btn-dark">
                                    <i class="ti ti-upload"></i> رفع الملف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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
</style>

<!-- Payment Modal -->
@foreach($contract->rentPayments->sortBy('due_date') as $payment)
@if($payment->status != 'paid')
<div class="modal fade" id="paymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="paymentModalLabel{{ $payment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel{{ $payment->id }}">
                    <i class="ti ti-currency-dollar me-2"></i>
                    تفاصيل الدفعة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <h6 class="mb-3">معلومات الدفعة</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>تاريخ الاستحقاق:</strong>
                                <span class="ms-2">{{ \Carbon\Carbon::parse($payment->due_date)->format('Y-m-d') }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>تاريخ الإصدار:</strong>
                                <span class="ms-2">{{ \Carbon\Carbon::parse($payment->issued_date)->format('Y-m-d') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <h6 class="mb-3">تفاصيل المبالغ</h6>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td style="width: 50%;"><strong>قيمة الإيجار:</strong></td>
                                <td class="text-end">{{ number_format($payment->rent_value, 2) }} ريال</td>
                            </tr>
                            <tr>
                                <td><strong>قيمة الخدمات:</strong></td>
                                <td class="text-end">{{ number_format($payment->services_value, 2) }} ريال</td>
                            </tr>
                            <tr>
                                <td><strong>قيمة الضريبة:</strong></td>
                                <td class="text-end">{{ number_format($payment->vat_value, 2) }} ريال</td>
                            </tr>
                            <tr>
                                <td><strong>المبالغ الثابتة:</strong></td>
                                <td class="text-end">{{ number_format($payment->fixed_amounts ?? 0, 2) }} ريال</td>
                            </tr>
                            <tr class="border-top">
                                <td><strong class="fs-5">الإجمالي:</strong></td>
                                <td class="text-end"><strong class="fs-5 text-primary">{{ number_format($payment->total_value, 2) }} ريال</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="alert alert-info mb-3">
                    <i class="ti ti-info-circle me-2"></i>
                    <small>سيتم إنشاء فاتورة وسند قبض تلقائياً عند تأكيد السداد.</small>
                </div>

                <form action="{{ route('property-management.contracts.payments.mark-as-paid', ['contractId' => $contract->id, 'paymentId' => $payment->id]) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="paymentForm{{ $payment->id }}">
                    @csrf
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="mb-3">رفع صورة الإيصال</h6>
                            <div class="mb-2">
                                <label for="receipt_image{{ $payment->id }}" class="form-label">
                                    <strong>صورة الإيصال <span class="text-danger">*</span></strong>
                                </label>
                                <input type="file"
                                       class="form-control @error('receipt_image') is-invalid @enderror"
                                       id="receipt_image{{ $payment->id }}"
                                       name="receipt_image"
                                       accept="image/*"
                                       required>
                                @error('receipt_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">الحد الأقصى لحجم الملف: 5 ميجابايت (صورة فقط: JPG, PNG, GIF)</small>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit"
                            class="btn btn-dark"
                            id="submitBtn{{ $payment->id }}">
                        <i class="ti ti-check me-1"></i>
                        تأكيد السداد
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($contract->rentPayments->sortBy('due_date') as $payment)
    @if($payment->status != 'paid')
    const receiptInput{{ $payment->id }} = document.getElementById('receipt_image{{ $payment->id }}');
    const submitBtn{{ $payment->id }} = document.getElementById('submitBtn{{ $payment->id }}');

    if (receiptInput{{ $payment->id }} && submitBtn{{ $payment->id }}) {
        receiptInput{{ $payment->id }}.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                // Enable submit button when file is selected
                submitBtn{{ $payment->id }}.disabled = false;
            } else {
                submitBtn{{ $payment->id }}.disabled = true;
            }
        });

        // Disable submit button initially until file is selected
        submitBtn{{ $payment->id }}.disabled = true;
    }
    @endif
    @endforeach

    // Editable fields functionality for Accountant
    const editableFields = document.querySelectorAll('.editable-field');

    editableFields.forEach(field => {
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
            // في جدول العقد: العمود 5 = الخدمات، العمود 6 = الضريبة
            const servicesCell = row.querySelectorAll('td')[4]; // index 4 = العمود 5
            const vatCell = row.querySelectorAll('td')[5]; // index 5 = العمود 6
            const servicesText = servicesCell ? servicesCell.textContent.trim() : '0';
            const vatText = vatCell ? vatCell.textContent.trim() : '0';
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

<style>
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
@endsection

