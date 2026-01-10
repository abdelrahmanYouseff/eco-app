@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تفاصيل الفواتير</h2>
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
                        <form method="GET" action="{{ route('property-management.accounting.invoices') }}" class="row g-3">
                            <div class="col-md-2">
                                <label for="from_date" class="form-label">من تاريخ</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-2">
                                <label for="to_date" class="form-label">إلى تاريخ</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">الحالة</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">جميع الحالات</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>مرسلة</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="search" class="form-label">البحث</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="رقم الفاتورة، اسم العميل، رقم العقد، الجوال..." value="{{ $search }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-dark">
                                    <i class="ti ti-search"></i> بحث
                                </button>
                                <a href="{{ route('property-management.accounting.invoices') }}" class="btn btn-outline-secondary" title="إعادة تعيين">
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
                            <div class="col-md-3">
                                <h6 class="text-muted mb-1">إجمالي الفواتير</h6>
                                <h3 class="mb-0 text-dark fw-bold">{{ number_format($totalInvoices, 2) }} <small class="text-muted">SAR</small></h3>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-muted mb-1">عدد الفواتير</h6>
                                <h3 class="mb-0 text-dark fw-bold">{{ $invoices->total() }} <small class="text-muted">فاتورة</small></h3>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-muted mb-1">مسودة</h6>
                                <h3 class="mb-0 text-dark fw-bold">{{ $statusCounts['draft'] }}</h3>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-muted mb-1">مرسلة</h6>
                                <h3 class="mb-0 text-info fw-bold">{{ $statusCounts['sent'] }}</h3>
                            </div>
                            <div class="col-md-2">
                                <h6 class="text-muted mb-1">مدفوعة</h6>
                                <h3 class="mb-0 text-success fw-bold">{{ $statusCounts['paid'] }}</h3>
                            </div>
                            <div class="col-md-1">
                                <h6 class="text-muted mb-1">ملغاة</h6>
                                <h3 class="mb-0 text-danger fw-bold">{{ $statusCounts['cancelled'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">قائمة الفواتير</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('property-management.accounting.invoices.export', ['from_date' => $fromDate, 'to_date' => $toDate, 'status' => request('status'), 'search' => $search]) }}" class="btn btn-sm btn-dark">
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
                                        <th class="border-0">رقم الفاتورة</th>
                                        <th class="border-0">رقم العقد</th>
                                        <th class="border-0">العميل</th>
                                        <th class="border-0">المبنى</th>
                                        <th class="border-0">الوحدة</th>
                                        <th class="border-0 text-center">تاريخ الفاتورة</th>
                                        <th class="border-0 text-center">تاريخ الاستحقاق</th>
                                        <th class="border-0 text-center">الحالة</th>
                                        <th class="border-0 text-end">المبلغ الفرعي</th>
                                        <th class="border-0 text-end">الضريبة</th>
                                        <th class="border-0 text-end">المبلغ الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                    @php
                                        $statusText = '';
                                        $statusClass = 'badge bg-secondary';
                                        
                                        switch ($invoice->status) {
                                            case 'draft':
                                                $statusText = 'مسودة';
                                                $statusClass = 'badge bg-secondary';
                                                break;
                                            case 'sent':
                                                $statusText = 'مرسلة';
                                                $statusClass = 'badge bg-info';
                                                break;
                                            case 'paid':
                                                $statusText = 'مدفوعة';
                                                $statusClass = 'badge bg-success';
                                                break;
                                            case 'cancelled':
                                                $statusText = 'ملغاة';
                                                $statusClass = 'badge bg-danger';
                                                break;
                                            default:
                                                $statusText = $invoice->status;
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('property-management.invoices.show', $invoice->id) }}" class="text-dark text-decoration-none">
                                                {{ $invoice->invoice_number ?? 'غير متوفر' }}
                                            </a>
                                        </td>
                                        <td>{{ $invoice->contract->contract_number ?? 'غير متوفر' }}</td>
                                        <td>{{ $invoice->client->name ?? 'غير متوفر' }}</td>
                                        <td>{{ $invoice->contract->building->name ?? 'غير متوفر' }}</td>
                                        <td>{{ $invoice->contract->unit->unit_number ?? 'غير متوفر' }}</td>
                                        <td class="text-center">
                                            @if($invoice->invoice_date)
                                                {{ $invoice->invoice_date->format('Y-m-d') }}
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($invoice->due_date)
                                                {{ $invoice->due_date->format('Y-m-d') }}
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td class="text-end">{{ number_format($invoice->subtotal, 2) }}</td>
                                        <td class="text-end">{{ number_format($invoice->vat_amount ?? 0, 2) }}</td>
                                        <td class="text-end"><strong>{{ number_format($invoice->total_amount, 2) }}</strong></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-file-invoice-off" style="font-size: 48px;"></i>
                                                <p class="mt-3 mb-0">لا توجد فواتير في الفترة المحددة</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($invoices->count() > 0)
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="8" class="text-end">الإجمالي:</th>
                                        <th class="text-end">{{ number_format($invoices->sum('subtotal'), 2) }}</th>
                                        <th class="text-end">{{ number_format($invoices->sum('vat_amount'), 2) }}</th>
                                        <th class="text-end"><strong>{{ number_format($invoices->sum('total_amount'), 2) }}</strong></th>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                        
                        @if(method_exists($invoices, 'links'))
                            <div class="mt-4">
                                {{ $invoices->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

