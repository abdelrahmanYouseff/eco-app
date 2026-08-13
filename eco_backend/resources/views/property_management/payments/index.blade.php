@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">دفعات الإيجار</h2>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.9rem;">عرض وإدارة دفعات الإيجار والبحث والتصفية</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h5 class="mb-0">
                            <i class="ti ti-list me-2"></i>
                            قائمة الدفعات
                        </h5>
                        @if($payments->total() > 0)
                            <span class="badge bg-light text-dark">
                                {{ $payments->firstItem() }} – {{ $payments->lastItem() }} من {{ $payments->total() }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body py-3">
                                <form method="GET" action="{{ route('property-management.payments.index') }}">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-2">
                                            <label class="form-label small text-muted mb-1">الحالة</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>الكل</option>
                                                <option value="unpaid" {{ request('status') == 'unpaid' || (!request('status') && $status == 'unpaid') ? 'selected' : '' }}>غير مدفوعة</option>
                                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
                                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small text-muted mb-1">من تاريخ</label>
                                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small text-muted mb-1">إلى تاريخ</label>
                                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted mb-1">الشركة</label>
                                            <select name="company_id" class="form-select form-select-sm">
                                                <option value="">الكل</option>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="ti ti-search me-1"></i> بحث
                                            </button>
                                            <a href="{{ route('property-management.payments.index') }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="ti ti-refresh me-1"></i> إعادة تعيين
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>رقم العقد</th>
                                        <th>العميل</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th class="text-center">أيام التأخير</th>
                                        <th class="text-end">القيمة الإجمالية</th>
                                        <th class="text-center">الحالة</th>
                                        <th>تاريخ الدفع</th>
                                        <th class="text-center" style="width: 200px;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $index => $payment)
                                        <tr>
                                            <td class="text-center text-muted">{{ $payments->firstItem() + $index }}</td>
                                            <td>
                                                <a href="{{ route('property-management.contracts.show', $payment->contract_id) }}" class="text-decoration-none fw-medium">
                                                    {{ $payment->contract->contract_number ?? '—' }}
                                                </a>
                                            </td>
                                            <td>{{ $payment->contract->client->name ?? '—' }}</td>
                                            <td>{{ $payment->due_date->format('Y-m-d') }}</td>
                                            <td class="text-center">
                                                @php
                                                    $daysOverdue = 0;
                                                    if ($payment->status !== 'paid' && $payment->due_date < now()) {
                                                        $daysOverdue = now()->diffInDays($payment->due_date);
                                                    }
                                                @endphp
                                                @if($daysOverdue > 0)
                                                    <span class="badge bg-danger">{{ $daysOverdue }} يوم</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end">{{ number_format($payment->total_value, 2) }} <small class="text-muted">SAR</small></td>
                                            <td class="text-center">
                                                @if($payment->status === 'paid')
                                                    <span class="badge bg-success">مدفوع</span>
                                                @elseif($payment->status === 'partially_paid')
                                                    <span class="badge bg-warning text-dark">مدفوع جزئياً</span>
                                                @else
                                                    <span class="badge bg-danger">غير مدفوع</span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : '—' }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                    <a href="{{ route('property-management.payments.contract', $payment->contract_id) }}" class="btn btn-sm btn-outline-primary" title="عرض العقد">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    @if($payment->status !== 'paid')
                                                        <a href="{{ route('property-management.payments.request-payment', $payment->id) }}" class="btn btn-sm btn-outline-dark" title="إرسال مطالبة">
                                                            <i class="ti ti-mail"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="ti ti-currency-dollar-off opacity-50" style="font-size: 2rem;"></i>
                                                    <p class="mt-2 mb-0">لا توجد دفعات تطابق البحث</p>
                                                    <small>غيّر معايير التصفية أو أضف عقوداً جديدة</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($payments->hasPages())
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    عرض {{ $payments->firstItem() ?? 0 }} – {{ $payments->lastItem() ?? 0 }} من {{ $payments->total() }} دفعة
                                </small>
                                <nav aria-label="تصفح الدفعات">
                                    {{ $payments->withQueryString()->links() }}
                                </nav>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
