@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تفاصيل الفاتورة: {{ $invoice->invoice_number }}</h2>
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
                            <i class="ti ti-file-invoice me-2"></i>
                            معلومات الفاتورة
                        </h5>
                        <div>
                            <a href="{{ route('property-management.invoices.index') }}" class="btn btn-sm btn-secondary">
                                <i class="ti ti-arrow-right"></i> العودة للقائمة
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">رقم الفاتورة:</th>
                                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الفاتورة:</th>
                                        <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الاستحقاق:</th>
                                        <td>
                                            {{ $invoice->due_date->format('Y-m-d') }}
                                            @if($invoice->due_date < now() && $invoice->status != 'paid')
                                                <span class="badge bg-danger ms-1">متأخر</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>الحالة:</th>
                                        <td>
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
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">العميل:</th>
                                        <td>{{ $invoice->client->name ?? 'N/A' }}</td>
                                    </tr>
                                    @if($invoice->contract)
                                    <tr>
                                        <th>رقم العقد:</th>
                                        <td>
                                            <a href="{{ route('property-management.contracts.show', $invoice->contract->id) }}" class="text-decoration-none">
                                                {{ $invoice->contract->contract_number }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>المبلغ الفرعي:</th>
                                        <td>{{ number_format($invoice->subtotal, 2) }} ريال</td>
                                    </tr>
                                    <tr>
                                        <th>ضريبة القيمة المضافة:</th>
                                        <td>{{ number_format($invoice->vat_amount, 2) }} ريال</td>
                                    </tr>
                                    <tr>
                                        <th>الإجمالي:</th>
                                        <td><strong>{{ number_format($invoice->total_amount, 2) }} ريال</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @if($invoice->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>ملاحظات:</h6>
                                <p class="text-muted">{{ $invoice->notes }}</p>
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


