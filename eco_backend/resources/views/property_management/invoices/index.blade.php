@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Invoices</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Invoices List</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <!-- Filters -->
                        <form action="{{ route('property-management.invoices.index') }}" method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="بحث برقم الفاتورة..."
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">جميع الحالات</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>مرسل</option>
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-search"></i> بحث
                                    </button>
                                    <a href="{{ route('property-management.invoices.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x"></i> إعادة تعيين
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>رقم الفاتورة</th>
                                        <th>العميل</th>
                                        <th>رقم العقد</th>
                                        <th>تاريخ الفاتورة</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th class="text-end">الإجمالي</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $index => $invoice)
                                    <tr>
                                        <td class="text-center align-middle">{{ ($invoices->currentPage() - 1) * $invoices->perPage() + $index + 1 }}</td>
                                        <td class="align-middle">
                                            <strong>{{ $invoice->invoice_number }}</strong>
                                        </td>
                                        <td class="align-middle">
                                            {{ $invoice->client->name ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle">
                                            @if($invoice->contract)
                                                <a href="{{ route('property-management.contracts.show', $invoice->contract->id) }}" class="text-decoration-none">
                                                    {{ $invoice->contract->contract_number }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                        <td class="align-middle">
                                            {{ $invoice->due_date->format('Y-m-d') }}
                                            @if($invoice->due_date < now() && $invoice->status != 'paid')
                                                <span class="badge bg-danger ms-1">متأخر</span>
                                            @endif
                                        </td>
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
                                            <a href="{{ route('property-management.invoices.show', $invoice->id) }}" class="btn btn-sm btn-info">
                                                <i class="ti ti-eye"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-file-invoice-off" style="font-size: 48px;"></i>
                                                <p class="mt-2">لا توجد فواتير</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($invoices->hasPages())
                        <div class="d-flex justify-content-center mt-4">
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


