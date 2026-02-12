@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">دفعات الإيجار</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>قائمة الدفعات</h5>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('property-management.payments.index') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">الحالة</label>
                                    <select name="status" class="form-select">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>الكل</option>
                                        <option value="unpaid" {{ request('status') == 'unpaid' || (!request('status') && $status == 'unpaid') ? 'selected' : '' }}>غير مدفوعة</option>
                                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">من تاريخ</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">إلى تاريخ</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">المبنى</label>
                                    <select name="building_id" class="form-select">
                                        <option value="">الكل</option>
                                        @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">الشركة</label>
                                    <select name="client_id" class="form-select">
                                        <option value="">الكل</option>
                                        @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">بحث (اسم العميل، رقم العقد، رقم الجوال)</label>
                                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="ابحث...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-search me-1"></i> بحث
                                        </button>
                                        <a href="{{ route('property-management.payments.index') }}" class="btn btn-secondary">
                                            <i class="ti ti-refresh me-1"></i> إعادة تعيين
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>رقم العقد</th>
                                        <th>العميل</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>أيام التأخير</th>
                                        <th>القيمة الإجمالية</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الدفع</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->contract->contract_number ?? 'غير متوفر' }}</td>
                                        <td>{{ $payment->contract->client->name ?? 'غير متوفر' }}</td>
                                        <td>{{ $payment->due_date->format('Y-m-d') }}</td>
                                        <td>
                                            @php
                                                $daysOverdue = 0;
                                                if ($payment->status !== 'paid' && $payment->due_date < now()) {
                                                    $daysOverdue = now()->diffInDays($payment->due_date);
                                                }
                                            @endphp
                                            @if($daysOverdue > 0)
                                                <span class="badge bg-danger">{{ $daysOverdue }} يوم</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($payment->total_value, 2) }} SAR</td>
                                        <td>
                                            @if($payment->status === 'paid')
                                                <span class="badge bg-success">مدفوع</span>
                                            @elseif($payment->status === 'partially_paid')
                                                <span class="badge bg-warning">مدفوع جزئياً</span>
                                            @else
                                                <span class="badge bg-danger">غير مدفوع</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'غير متوفر' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('property-management.payments.contract', $payment->contract_id) }}" class="btn btn-sm btn-outline-dark">
                                                    <i class="ti ti-eye"></i> عرض العقد
                                                </a>
                                                @if($payment->status !== 'paid')
                                                <a href="{{ route('property-management.payments.request-payment', $payment->id) }}" class="btn btn-sm btn-dark" title="إرسال مطالبة">
                                                    <i class="ti ti-mail"></i> مطالبة
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-currency-dollar-off" style="font-size: 48px;"></i>
                                                <p class="mt-2">لا توجد دفعات</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $payments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


