@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تفاصيل سند القبض: {{ $receiptVoucher->receipt_number }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-receipt me-2"></i>
                            معلومات سند القبض
                        </h5>
                        <div>
                            <a href="{{ route('property-management.receipt-vouchers.index') }}" class="btn btn-sm btn-secondary">
                                <i class="ti ti-arrow-right"></i> العودة للقائمة
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">رقم السند:</th>
                                        <td><strong>{{ $receiptVoucher->receipt_number }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ السند:</th>
                                        <td>{{ $receiptVoucher->receipt_date->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <th>المبلغ:</th>
                                        <td><strong>{{ number_format($receiptVoucher->amount, 2) }} ريال</strong></td>
                                    </tr>
                                    <tr>
                                        <th>طريقة الدفع:</th>
                                        <td>
                                            @if($receiptVoucher->payment_method == 'cash')
                                                <span class="badge bg-success">نقدي</span>
                                            @elseif($receiptVoucher->payment_method == 'bank_transfer')
                                                <span class="badge bg-info">تحويل بنكي</span>
                                            @elseif($receiptVoucher->payment_method == 'check')
                                                <span class="badge bg-warning text-dark">شيك</span>
                                            @elseif($receiptVoucher->payment_method == 'credit_card')
                                                <span class="badge bg-primary">بطاقة ائتمان</span>
                                            @else
                                                <span class="badge bg-secondary">أخرى</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">العميل:</th>
                                        <td>{{ $receiptVoucher->client->name ?? 'N/A' }}</td>
                                    </tr>
                                    @if($receiptVoucher->contract)
                                    <tr>
                                        <th>رقم العقد:</th>
                                        <td>
                                            <a href="{{ route('property-management.contracts.show', $receiptVoucher->contract->id) }}" class="text-decoration-none">
                                                {{ $receiptVoucher->contract->contract_number }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($receiptVoucher->reference_number)
                                    <tr>
                                        <th>رقم المرجع:</th>
                                        <td>{{ $receiptVoucher->reference_number }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>تاريخ الإنشاء:</th>
                                        <td>{{ $receiptVoucher->created_at ? $receiptVoucher->created_at->format('Y-m-d H:i') : 'غير محدد' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @if($receiptVoucher->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>ملاحظات:</h6>
                                <p class="text-muted">{{ $receiptVoucher->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


