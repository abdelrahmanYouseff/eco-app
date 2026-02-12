@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">المراسلات الإلكترونية</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>سجل الإيميلات المرسلة</h5>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('property-management.email-logs.index') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">الحالة</label>
                                    <select name="status" class="form-select">
                                        <option value="">الكل</option>
                                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>مرسلة</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل الإرسال</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
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
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">بحث</button>
                                        <a href="{{ route('property-management.email-logs.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>تاريخ الإرسال</th>
                                        <th>رقم العقد</th>
                                        <th>اسم الشركة</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الموضوع</th>
                                        <th>المطالبة</th>
                                        <th>الحالة</th>
                                        <th>مرسل بواسطة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($emailLogs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if($log->contract)
                                                <a href="{{ route('property-management.contracts.show', $log->contract->id) }}" class="text-decoration-none">
                                                    {{ $log->contract->contract_number }}
                                                </a>
                                            @else
                                                <span class="text-muted">غير متوفر</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->client)
                                                {{ $log->client->name }}
                                            @else
                                                <span class="text-muted">غير متوفر</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->to_email }}</td>
                                        <td>{{ $log->subject }}</td>
                                        <td>
                                            @if($log->rentPayment)
                                                <a href="{{ route('property-management.payments.request-payment', $log->rentPayment->id) }}" class="btn btn-sm btn-outline-dark">
                                                    عرض المطالبة
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->status == 'sent')
                                                <span class="badge bg-success">مرسلة</span>
                                            @elseif($log->status == 'failed')
                                                <span class="badge bg-danger" title="{{ $log->error_message }}">فشل الإرسال</span>
                                            @else
                                                <span class="badge bg-warning">قيد الانتظار</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->sentByUser)
                                                {{ $log->sentByUser->name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">لا توجد إيميلات مسجلة</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $emailLogs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
