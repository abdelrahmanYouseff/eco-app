@extends('master')

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
                                        <td>{{ $tenant->created_at->format('Y-m-d H:i') }}</td>
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
                                        <td class="text-end align-middle">{{ number_format($payment->rent_value, 2) }} ريال</td>
                                        <td class="text-end align-middle">{{ number_format($payment->services_value, 2) }} ريال</td>
                                        <td class="text-end align-middle">{{ number_format($payment->vat_value, 2) }} ريال</td>
                                        <td class="text-end align-middle">{{ number_format($payment->fixed_amounts ?? 0, 2) }} ريال</td>
                                        <td class="text-end align-middle">
                                            <strong>{{ number_format($payment->total_value, 2) }} ريال</strong>
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
</style>
@endsection

