@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">المحاسبة / الكشوفات</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Date Range Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('property-management.accounting.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <label for="from_date" class="form-label">من تاريخ</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-4">
                                <label for="to_date" class="form-label">إلى تاريخ</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-dark me-2">
                                    <i class="ti ti-filter"></i> فلترة
                                </button>
                                <a href="{{ route('property-management.accounting.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-refresh"></i> إعادة تعيين
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-light rounded-circle p-3">
                                    <i class="ti ti-currency-dollar text-dark" style="font-size: 24px;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1" style="font-size: 0.875rem; font-weight: 500;">إجمالي الإيرادات</h6>
                                <h4 class="mb-0 text-dark fw-bold">{{ number_format($totalRevenue, 2) }} <small class="text-muted" style="font-size: 0.875rem;">SAR</small></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-light rounded-circle p-3">
                                    <i class="ti ti-clock text-dark" style="font-size: 24px;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1" style="font-size: 0.875rem; font-weight: 500;">المتبقي</h6>
                                <h4 class="mb-0 text-dark fw-bold">{{ number_format($totalPending, 2) }} <small class="text-muted" style="font-size: 0.875rem;">SAR</small></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-light rounded-circle p-3">
                                    <i class="ti ti-alert-triangle text-dark" style="font-size: 24px;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1" style="font-size: 0.875rem; font-weight: 500;">المتأخر</h6>
                                <h4 class="mb-0 text-dark fw-bold">{{ number_format($totalOverdue, 2) }} <small class="text-muted" style="font-size: 0.875rem;">SAR</small></h4>
                                <small class="text-muted">{{ $overduePaymentsCount }} دفعة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-light rounded-circle p-3">
                                    <i class="ti ti-file-invoice text-dark" style="font-size: 24px;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1" style="font-size: 0.875rem; font-weight: 500;">إجمالي الفواتير</h6>
                                <h4 class="mb-0 text-dark fw-bold">{{ number_format($totalInvoices, 2) }} <small class="text-muted" style="font-size: 0.875rem;">SAR</small></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Payments -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>آخر الدفعات المدفوعة</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">رقم العقد</th>
                                        <th class="border-0">العميل</th>
                                        <th class="border-0">المبلغ</th>
                                        <th class="border-0">التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->contract->contract_number ?? 'غير متوفر' }}</td>
                                        <td>{{ $payment->contract->client->name ?? 'غير متوفر' }}</td>
                                        <td>{{ number_format($payment->total_value, 2) }} SAR</td>
                                        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">لا توجد دفعات</div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overdue Payments -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>الدفعات المتأخرة</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">رقم العقد</th>
                                        <th class="border-0">العميل</th>
                                        <th class="border-0">المبلغ</th>
                                        <th class="border-0">أيام التأخير</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($overduePayments as $payment)
                                    <tr>
                                        <td>{{ $payment->contract->contract_number ?? 'غير متوفر' }}</td>
                                        <td>{{ $payment->contract->client->name ?? 'غير متوفر' }}</td>
                                        <td>{{ number_format($payment->total_value, 2) }} SAR</td>
                                        <td>
                                            <span class="badge bg-dark">
                                                {{ now()->diffInDays($payment->due_date) }} يوم
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">لا توجد دفعات متأخرة</div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Statistics -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>الإحصائيات الشهرية (آخر 6 أشهر)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">الشهر</th>
                                        <th class="border-0">عدد الدفعات</th>
                                        <th class="border-0">إجمالي الإيرادات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyStats as $stat)
                                    <tr>
                                        <td>{{ $stat['month_name'] }}</td>
                                        <td>{{ $stat['count'] }} دفعة</td>
                                        <td>{{ number_format($stat['revenue'], 2) }} SAR</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        transform: translateY(-2px);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 1.25rem;
    }
    .card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #212529;
        font-size: 1rem;
    }
    .table {
        margin-bottom: 0;
    }
    .table thead th {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        text-transform: none;
        padding: 0.75rem 1rem;
    }
    .table tbody td {
        padding: 0.75rem 1rem;
        color: #212529;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .table tbody tr {
        border-bottom: 1px solid #f1f3f5;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .btn-dark {
        background-color: #212529;
        border-color: #212529;
    }
    .btn-dark:hover {
        background-color: #000;
        border-color: #000;
    }
    .btn-outline-secondary {
        border-color: #dee2e6;
        color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #495057;
    }
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
        font-size: 0.75rem;
    }
    .badge.bg-dark {
        background-color: #212529 !important;
    }
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    .form-control {
        border: 1px solid #dee2e6;
    }
    .form-control:focus {
        border-color: #212529;
        box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.1);
    }
</style>
@endsection

