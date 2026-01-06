@extends('master')

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
                                        <td class="text-end">{{ number_format($payment->rent_value, 2) }} ريال</td>
                                        <td class="text-end">{{ number_format($payment->services_value, 2) }} ريال</td>
                                        <td class="text-end">{{ number_format($payment->vat_value, 2) }} ريال</td>
                                        <td class="text-end">{{ number_format($payment->fixed_amounts ?? 0, 2) }} ريال</td>
                                        <td class="text-end"><strong>{{ number_format($payment->total_value, 2) }} ريال</strong></td>
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
                                                <form action="{{ route('property-management.contracts.payments.mark-as-paid', ['contractId' => $contract->id, 'paymentId' => $payment->id]) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من تسجيل السداد لهذه الدفعة؟ سيتم إنشاء فاتورة وسند قبض تلقائياً.');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="تم السداد">
                                                        <i class="ti ti-check"></i> تم السداد
                                                    </button>
                                                </form>
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
@endsection

