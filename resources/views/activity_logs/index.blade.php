@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">
                                <i class="ti ti-list me-2"></i>
                                سجل الإجراءات
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="ti ti-file-text me-2 text-dark"></i>
                            جميع الإجراءات على النظام
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Filters -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body p-3">
                                <form action="{{ route('activity-logs.index') }}" method="GET">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-3">
                                            <label for="action" class="form-label fw-semibold mb-2">
                                                <i class="ti ti-filter me-1"></i> نوع الإجراء
                                            </label>
                                            <select name="action" id="action" class="form-select form-select-sm">
                                                <option value="">جميع الإجراءات</option>
                                                @foreach($actions as $action)
                                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                                        {{ $action }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="model_type" class="form-label fw-semibold mb-2">
                                                <i class="ti ti-database me-1"></i> نوع النموذج
                                            </label>
                                            <select name="model_type" id="model_type" class="form-select form-select-sm">
                                                <option value="">جميع النماذج</option>
                                                @foreach($modelTypes as $modelType)
                                                    <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                                        {{ $modelType }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="date_from" class="form-label fw-semibold mb-2">
                                                <i class="ti ti-calendar me-1"></i> من تاريخ
                                            </label>
                                            <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="date_to" class="form-label fw-semibold mb-2">
                                                <i class="ti ti-calendar me-1"></i> إلى تاريخ
                                            </label>
                                            <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                                    <i class="ti ti-search"></i> بحث
                                                </button>
                                                <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="ti ti-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Activity Logs Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 150px;">
                                            <i class="ti ti-clock me-1"></i> التاريخ والوقت
                                        </th>
                                        <th style="min-width: 150px;">
                                            <i class="ti ti-user me-1"></i> المستخدم
                                        </th>
                                        <th style="min-width: 120px;">
                                            <i class="ti ti-activity me-1"></i> الإجراء
                                        </th>
                                        <th style="min-width: 150px;">
                                            <i class="ti ti-database me-1"></i> النموذج
                                        </th>
                                        <th>
                                            <i class="ti ti-file-text me-1"></i> الوصف
                                        </th>
                                        <th style="min-width: 120px;">
                                            <i class="ti ti-network me-1"></i> IP
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                    <tr>
                                        <td class="align-middle">
                                            <div>
                                                <strong>{{ $log->created_at->format('Y-m-d') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            @if($log->user)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="ti ti-user text-dark"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $log->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $log->user->email }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">مستخدم محذوف</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @php
                                                $badgeColors = [
                                                    'create' => 'bg-success',
                                                    'update' => 'bg-info',
                                                    'delete' => 'bg-danger',
                                                    'view' => 'bg-primary',
                                                    'login' => 'bg-success',
                                                    'logout' => 'bg-secondary',
                                                ];
                                                $color = $badgeColors[$log->action] ?? 'bg-secondary';
                                            @endphp
                                            <span class="badge {{ $color }} text-white">
                                                {{ $log->action }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            @if($log->model_type)
                                                <span class="badge bg-dark text-white">
                                                    {{ $log->model_type }}
                                                </span>
                                                @if($log->model_id)
                                                    <br>
                                                    <small class="text-muted">ID: {{ $log->model_id }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($log->description)
                                                <span>{{ $log->description }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <code class="text-primary">{{ $log->ip_address ?? '-' }}</code>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-file-off" style="font-size: 64px; opacity: 0.3;"></i>
                                                <p class="mt-3 mb-0 fs-5">لا توجد سجلات</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        color: #495057;
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .avatar-xs {
        width: 32px;
        height: 32px;
    }
</style>
@endsection
