@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">كشف حساب العملاء</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Filter and Date Range -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('property-management.tenants.account-statements') }}" class="row g-3">
                            <div class="col-md-4">
                                <label for="customer_id" class="form-label">اختر العميل <span class="text-danger">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-select" required>
                                    <option value="">-- اختر العميل --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $customerId == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} - {{ $customer->mobile }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="from_date" class="form-label">من تاريخ</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label">إلى تاريخ</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-dark">
                                    <i class="ti ti-search"></i> بحث
                                </button>
                                @if($customerId)
                                    <a href="{{ route('property-management.tenants.account-statements') }}" class="btn btn-outline-secondary" title="إعادة تعيين">
                                        <i class="ti ti-refresh"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($selectedCustomer)
        <!-- Customer Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">معلومات العميل</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 150px;">الاسم:</th>
                                        <td>{{ $selectedCustomer->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>النوع:</th>
                                        <td>{{ $selectedCustomer->client_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>الجوال:</th>
                                        <td>{{ $selectedCustomer->mobile }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">ملخص الحساب</h5>
                                <table class="table table-borderless">
                                    @php
                                        $totalDebit = collect($transactions)->sum('debit');
                                        $totalCredit = collect($transactions)->sum('credit');
                                        $currentBalance = count($transactions) > 0 ? end($transactions)['balance'] : 0;
                                    @endphp
                                    <tr>
                                        <th style="width: 150px;">إجمالي المدين:</th>
                                        <td class="text-end">{{ number_format($totalDebit, 2) }} <small class="text-muted">SAR</small></td>
                                    </tr>
                                    <tr>
                                        <th>إجمالي الدائن:</th>
                                        <td class="text-end">{{ number_format($totalCredit, 2) }} <small class="text-muted">SAR</small></td>
                                    </tr>
                                    <tr>
                                        <th><strong>الرصيد الحالي:</strong></th>
                                        <td class="text-end">
                                            <strong class="{{ $currentBalance >= 0 ? 'text-dark' : 'text-success' }}">
                                                {{ number_format($currentBalance, 2) }} <small class="text-muted">SAR</small>
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Statement Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">كشف الحساب</h5>
                        @if(count($transactions) > 0)
                            <button onclick="window.print()" class="btn btn-sm btn-dark">
                                <i class="ti ti-printer"></i> طباعة
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if(count($transactions) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 text-center">رقم العملية</th>
                                        <th class="border-0">رقم المرجع</th>
                                        <th class="border-0 text-center">تاريخ العملية</th>
                                        <th class="border-0 text-center">تاريخ الاستحقاق</th>
                                        <th class="border-0">التعريف</th>
                                        <th class="border-0 text-end">مدين</th>
                                        <th class="border-0 text-end">دائن</th>
                                        <th class="border-0 text-end">الرصيد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td class="text-center">{{ $transaction['operation_number'] }}</td>
                                        <td>{{ $transaction['reference_number'] }}</td>
                                        <td class="text-center">{{ $transaction['date']->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            @if(isset($transaction['due_date']) && $transaction['due_date'])
                                                {{ $transaction['due_date']->format('Y-m-d') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction['description'] }}</td>
                                        <td class="text-end">
                                            @if($transaction['debit'] > 0)
                                                <span class="text-dark">{{ number_format($transaction['debit'], 2) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($transaction['credit'] > 0)
                                                <span class="text-success">{{ number_format($transaction['credit'], 2) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <strong class="{{ $transaction['balance'] >= 0 ? 'text-dark' : 'text-success' }}">
                                                {{ number_format($transaction['balance'], 2) }}
                                            </strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    @php
                                        $totalDebit = collect($transactions)->sum('debit');
                                        $totalCredit = collect($transactions)->sum('credit');
                                        $finalBalance = count($transactions) > 0 ? end($transactions)['balance'] : 0;
                                    @endphp
                                    <tr>
                                        <th colspan="5" class="text-end">الإجمالي:</th>
                                        <th class="text-end">{{ number_format($totalDebit, 2) }}</th>
                                        <th class="text-end">{{ number_format($totalCredit, 2) }}</th>
                                        <th class="text-end">
                                            <strong>{{ number_format($finalBalance, 2) }}</strong>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="ti ti-file-off" style="font-size: 48px;"></i>
                                <p class="mt-3 mb-0">لا توجد معاملات في الفترة المحددة</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- No Customer Selected -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="text-muted">
                            <i class="ti ti-user-search" style="font-size: 64px;"></i>
                            <p class="mt-3 mb-0 fs-5">يرجى اختيار عميل لعرض كشف حسابه</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
@media print {
    .pc-sidebar,
    .page-header,
    .card-header,
    .btn,
    .form-label,
    form {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .table {
        font-size: 12px;
    }
}
</style>
@endsection
