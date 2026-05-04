@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Receipt Vouchers</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Receipt Vouchers List</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <!-- Filters -->
                        <form action="{{ route('property-management.receipt-vouchers.index') }}" method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="بحث برقم السند..."
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="payment_method" class="form-select">
                                        <option value="">جميع طرق الدفع</option>
                                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                        <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                                        <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                                        <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>أخرى</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-search"></i> بحث
                                    </button>
                                    <a href="{{ route('property-management.receipt-vouchers.index') }}" class="btn btn-secondary">
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
                                        <th>رقم السند</th>
                                        <th>العميل</th>
                                        <th>رقم العقد</th>
                                        <th>تاريخ السند</th>
                                        <th class="text-end">المبلغ</th>
                                        <th>طريقة الدفع</th>
                                        <th>رقم المرجع</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($receiptVouchers as $index => $receipt)
                                    <tr>
                                        <td class="text-center align-middle">{{ ($receiptVouchers->currentPage() - 1) * $receiptVouchers->perPage() + $index + 1 }}</td>
                                        <td class="align-middle">
                                            <strong>{{ $receipt->receipt_number }}</strong>
                                        </td>
                                        <td class="align-middle">
                                            {{ $receipt->client->name ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle">
                                            @if($receipt->contract)
                                                <a href="{{ route('property-management.contracts.show', $receipt->contract->id) }}" class="text-decoration-none">
                                                    {{ $receipt->contract->contract_number }}
                                                </a>
                                            @else
                                                -
                                            @endif
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
                                            <a href="{{ route('property-management.receipt-vouchers.show', $receipt->id) }}" class="btn btn-sm btn-info">
                                                <i class="ti ti-eye"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-receipt-off" style="font-size: 48px;"></i>
                                                <p class="mt-2">لا توجد سندات قبض</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($receiptVouchers->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $receiptVouchers->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


