@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تفاصيل الإيرادات</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Date Range Filter and Search -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('property-management.accounting.revenues') }}" class="row g-3">
                            <div class="col-md-3">
                                <label for="from_date" class="form-label">من تاريخ</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label">إلى تاريخ</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-4">
                                <label for="search" class="form-label">البحث</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="اسم العميل، رقم العقد، الجوال..." value="{{ $search }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-dark">
                                    <i class="ti ti-search"></i> بحث
                                </button>
                                <a href="{{ route('property-management.accounting.revenues') }}" class="btn btn-outline-secondary" title="إعادة تعيين">
                                    <i class="ti ti-refresh"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">إجمالي الإيرادات</h6>
                                <h3 class="mb-0 text-dark fw-bold">{{ number_format($totalRevenue, 2) }} <small class="text-muted">SAR</small></h3>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">عدد الإيرادات</h6>
                                <h3 class="mb-0 text-dark fw-bold">{{ $revenues->total() }} <small class="text-muted">دفعة</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenues Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">قائمة الإيرادات</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('property-management.accounting.revenues.export', ['from_date' => $fromDate, 'to_date' => $toDate, 'search' => $search]) }}" class="btn btn-sm btn-dark">
                                <i class="ti ti-download"></i> تصدير Excel
                            </a>
                            <a href="{{ route('property-management.accounting.index') }}" class="btn btn-sm btn-outline-dark">
                                <i class="ti ti-arrow-right"></i> العودة
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">رقم العقد</th>
                                        <th class="border-0">العميل</th>
                                        <th class="border-0">المبنى</th>
                                        <th class="border-0">الوحدة</th>
                                        <th class="border-0 text-center">تاريخ الاستحقاق</th>
                                        <th class="border-0 text-center">تاريخ الدفع</th>
                                        <th class="border-0 text-end">الإيجار</th>
                                        <th class="border-0 text-end">الخدمات</th>
                                        <th class="border-0 text-end">الضريبة</th>
                                        <th class="border-0 text-end">المبلغ الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($revenues as $payment)
                                    <tr>
                                        <td>{{ $payment->contract->contract_number ?? 'غير متوفر' }}</td>
                                        <td>{{ $payment->contract->client->name ?? 'غير متوفر' }}</td>
                                        <td>{{ $payment->contract->building->name ?? 'غير متوفر' }}</td>
                                        <td>{{ $payment->contract->unit->unit_number ?? 'غير متوفر' }}</td>
                                        <td class="text-center">{{ $payment->due_date ? $payment->due_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td class="text-center">{{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td class="text-end">{{ number_format($payment->rent_value, 2) }}</td>
                                        <td class="text-end">{{ number_format($payment->services_value ?? 0, 2) }}</td>
                                        <td class="text-end">{{ number_format($payment->vat_value ?? 0, 2) }}</td>
                                        <td class="text-end"><strong>{{ number_format($payment->total_value, 2) }}</strong></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-currency-dollar-off" style="font-size: 48px;"></i>
                                                <p class="mt-3 mb-0">لا توجد إيرادات في الفترة المحددة</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($revenues->count() > 0)
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="6" class="text-end">الإجمالي:</th>
                                        <th class="text-end">{{ number_format($revenues->sum('rent_value'), 2) }}</th>
                                        <th class="text-end">{{ number_format($revenues->sum('services_value'), 2) }}</th>
                                        <th class="text-end">{{ number_format($revenues->sum('vat_value'), 2) }}</th>
                                        <th class="text-end"><strong>{{ number_format($revenues->sum('total_value'), 2) }}</strong></th>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                        
                        @if(method_exists($revenues, 'links'))
                            <div class="mt-4">
                                {{ $revenues->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

