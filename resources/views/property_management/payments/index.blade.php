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
                        <h5>قائمة الدفعات - {{ $type == 'all' ? 'الكل' : ($type == 'paid' ? 'المدفوعة' : ($type == 'unpaid' ? 'غير المدفوعة' : 'المدفوعة جزئياً')) }}</h5>
                    </div>
                    <div class="card-body">
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
                                            <a href="{{ route('property-management.payments.contract', $payment->contract_id) }}" class="btn btn-sm btn-info">
                                                <i class="ti ti-eye"></i> عرض العقد
                                            </a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


