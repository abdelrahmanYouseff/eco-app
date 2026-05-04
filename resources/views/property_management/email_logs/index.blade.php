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
                            <table class="table email-logs-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th style="width: 150px;">تاريخ الإرسال</th>
                                        <th style="width: 120px;">رقم العقد</th>
                                        <th style="width: 180px;">اسم الشركة</th>
                                        <th style="width: 200px;">البريد الإلكتروني</th>
                                        <th>الموضوع</th>
                                        <th style="width: 130px;">المطالبة</th>
                                        <th style="width: 120px;">الحالة</th>
                                        <th style="width: 150px;">مرسل بواسطة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($emailLogs as $log)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $log->id }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">{{ $log->created_at->format('Y-m-d') }}</span>
                                                <small class="text-muted">{{ $log->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($log->contract)
                                                <a href="{{ route('property-management.contracts.show', $log->contract->id) }}" 
                                                   class="text-decoration-none fw-semibold text-primary">
                                                    <i class="ti ti-file-text me-1"></i>{{ $log->contract->contract_number }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->client)
                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-building me-2 text-muted"></i>
                                                    <span class="fw-semibold">{{ $log->client->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-mail me-2 text-muted"></i>
                                                <span class="text-break">{{ $log->to_email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 300px;" title="{{ $log->subject }}">
                                                {{ $log->subject }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($log->rentPayment)
                                                <a href="{{ route('property-management.payments.request-payment', $log->rentPayment->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-eye me-1"></i>عرض
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($log->status == 'sent')
                                                <span class="badge bg-success-subtle text-success border border-success">
                                                    <i class="ti ti-check me-1"></i>مرسلة
                                                </span>
                                            @elseif($log->status == 'failed')
                                                <span class="badge bg-danger-subtle text-danger border border-danger" 
                                                      title="{{ $log->error_message }}"
                                                      data-bs-toggle="tooltip" 
                                                      data-bs-placement="top">
                                                    <i class="ti ti-x me-1"></i>فشل
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning">
                                                    <i class="ti ti-clock me-1"></i>قيد الانتظار
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->sentByUser)
                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-user me-2 text-muted"></i>
                                                    <span>{{ $log->sentByUser->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ti ti-inbox" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
                                                <p class="text-muted mb-0">لا توجد إيميلات مسجلة</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $emailLogs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .email-logs-table {
        font-size: 14px;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .email-logs-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .email-logs-table thead th {
        font-weight: 600;
        padding: 16px 12px;
        text-align: right;
        border: none;
        white-space: nowrap;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .email-logs-table thead th:first-child {
        border-top-right-radius: 12px;
    }

    .email-logs-table thead th:last-child {
        border-top-left-radius: 12px;
    }

    .email-logs-table tbody td {
        padding: 16px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
        background-color: #fff;
        transition: all 0.2s ease;
    }

    .email-logs-table tbody tr {
        transition: all 0.2s ease;
    }

    .email-logs-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .email-logs-table tbody tr:hover td {
        background-color: #f8f9fa;
    }

    .email-logs-table tbody tr:last-child td:first-child {
        border-bottom-right-radius: 12px;
    }

    .email-logs-table tbody tr:last-child td:last-child {
        border-bottom-left-radius: 12px;
    }

    .email-logs-table .badge {
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 500;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .email-logs-table .btn {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .email-logs-table .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .email-logs-table a {
        transition: all 0.2s ease;
    }

    .email-logs-table a:hover {
        text-decoration: underline !important;
    }

    .card {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #dee2e6;
        padding: 20px 24px;
    }

    .card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #212529;
        font-size: 18px;
    }

    .card-body {
        padding: 24px;
    }

    .form-select, .form-control {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }

    .bg-success-subtle {
        background-color: #d1e7dd !important;
    }

    .bg-danger-subtle {
        background-color: #f8d7da !important;
    }

    .bg-warning-subtle {
        background-color: #fff3cd !important;
    }

    .text-success {
        color: #198754 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    @media (max-width: 768px) {
        .email-logs-table {
            font-size: 12px;
        }

        .email-logs-table thead th,
        .email-logs-table tbody td {
            padding: 10px 8px;
        }
    }
</style>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
