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
                                <i class="ti ti-file-text me-2"></i>
                                قائمة العقود
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Statistics Cards -->
            <div class="col-md-12 mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card stats-card-dark">
                            <div class="card-body">
                                <h3 class="mb-0 text-white">{{ $contracts->count() }}</h3>
                                <p class="text-white mb-0 small" style="opacity: 0.9;">إجمالي العقود</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card-gray">
                            <div class="card-body">
                                <h3 class="mb-0 text-white">{{ $contracts->where('start_date', '<=', now())->where('end_date', '>=', now())->count() }}</h3>
                                <p class="text-white mb-0 small" style="opacity: 0.9;">عقود نشطة</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card-gray-light">
                            <div class="card-body">
                                <h3 class="mb-0 text-white">{{ $contracts->where('end_date', '<', now())->count() }}</h3>
                                <p class="text-white mb-0 small" style="opacity: 0.9;">عقود منتهية</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card-dark-light">
                            <div class="card-body">
                                <h3 class="mb-0 text-white">{{ number_format($contracts->sum('annual_rent'), 0) }}</h3>
                                <p class="text-white mb-0 small" style="opacity: 0.9;">إجمالي الإيجار (ريال)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="ti ti-list me-2 text-dark"></i>
                                العقود
                            </h5>
                            <div class="d-flex gap-2">
                                <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm" style="display: none;">
                                    <i class="ti ti-trash"></i> حذف المحدد (<span id="selectedCount">0</span>)
                                </button>
                                <a href="{{ route('property-management.contracts.create') }}" class="btn btn-primary btn-sm">
                                    <i class="ti ti-plus"></i> إضافة عقد جديد
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="ti ti-check-circle me-2"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="ti ti-alert-circle me-2"></i>
                            <div>{{ session('error') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="ti ti-alert-triangle me-2"></i>
                            <div>
                                {{ session('warning') }}
                                @if(session('errors'))
                                <ul class="mb-0 mt-2">
                                    @foreach(session('errors') as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <!-- Filters -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body p-3">
                                <form action="{{ route('property-management.contracts.index') }}" method="GET">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-4">
                                            <label for="building_id" class="form-label fw-semibold mb-2">
                                                <i class="ti ti-building me-1"></i> المبنى
                                            </label>
                                            <select name="building_id" id="building_id" class="form-select form-select-sm">
                                                <option value="">جميع المباني</option>
                                                @foreach($buildings as $building)
                                                    <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                                        {{ $building->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" name="show_active_only" id="show_active_only" value="1" {{ request('show_active_only') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="show_active_only">
                                                    <i class="ti ti-list-check me-1"></i> عرض العقود النشطة فقط
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                                    <i class="ti ti-search"></i> بحث
                                                </button>
                                                <a href="{{ route('property-management.contracts.index') }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="ti ti-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <form id="bulkActionForm" method="POST" action="{{ route('property-management.contracts.bulk-delete') }}">
                            @csrf
                            @method('DELETE')
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="contractsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">
                                                <input type="checkbox" id="selectAll" title="تحديد الكل" class="form-check-input">
                                            </th>
                                            <th style="min-width: 120px;">
                                                <i class="ti ti-hash me-1"></i> رقم العقد
                                            </th>
                                            <th style="min-width: 150px;">
                                                <i class="ti ti-building me-1"></i> المبنى
                                            </th>
                                            <th style="min-width: 150px;">
                                                <i class="ti ti-user me-1"></i> العميل
                                            </th>
                                            <th style="min-width: 150px;">
                                                <i class="ti ti-layout-grid me-1"></i> الوحدة / النوع
                                            </th>
                                            <th style="min-width: 120px;">
                                                <i class="ti ti-calendar me-1"></i> تاريخ البدء
                                            </th>
                                            <th style="min-width: 120px;">
                                                <i class="ti ti-calendar-off me-1"></i> تاريخ الانتهاء
                                            </th>
                                            <th style="min-width: 130px;" class="text-end">
                                                <i class="ti ti-currency-dollar me-1"></i> الإيجار السنوي
                                            </th>
                                            <th class="text-center" style="min-width: 100px;">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($contracts as $contract)
                                        <tr class="table-row-hover">
                                            <td class="text-center align-middle">
                                                <input type="checkbox" 
                                                       name="contract_ids[]" 
                                                       value="{{ $contract->id }}" 
                                                       class="contract-checkbox form-check-input"
                                                       data-contract-number="{{ $contract->contract_number }}">
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="text-decoration-none fw-semibold text-dark">
                                                    <i class="ti ti-file-text me-1"></i>
                                                    {{ $contract->contract_number }}
                                                </a>
                                            </td>
                                            <td class="align-middle">
                                                @if($contract->building)
                                                    <a href="{{ route('property-management.buildings.show', $contract->building->id) }}" class="text-decoration-none">
                                                        <span class="badge bg-dark text-white border-0 px-3 py-2">
                                                            <i class="ti ti-building me-1"></i>
                                                            {{ $contract->building->name }}
                                                        </span>
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="ti ti-user text-dark"></i>
                                                    </div>
                                                    <span class="fw-medium text-dark">{{ $contract->client->name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                @if($contract->unit)
                                                    <div>
                                                        <span class="badge bg-secondary text-dark mb-1">
                                                            {{ $contract->unit->unit_number }}
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="ti ti-tag me-1"></i>
                                                            {{ $contract->unit->unit_type ?? '-' }}
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-muted">
                                                    <i class="ti ti-calendar me-1"></i>
                                                    {{ $contract->start_date->format('Y-m-d') }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                @php
                                                    $isExpired = $contract->end_date < now();
                                                @endphp
                                                <span class="{{ $isExpired ? 'text-dark' : 'text-muted' }}">
                                                    <i class="ti ti-calendar-off me-1"></i>
                                                    {{ $contract->end_date->format('Y-m-d') }}
                                                    @if($isExpired)
                                                        <span class="badge bg-dark text-white ms-1">منتهي</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="text-end align-middle">
                                                <span class="fw-semibold text-dark">
                                                    {{ number_format($contract->annual_rent, 2) }} <small class="text-muted">ريال</small>
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('property-management.contracts.show', $contract->id) }}" 
                                                       class="btn btn-sm btn-outline-dark" 
                                                       title="عرض التفاصيل">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a href="{{ route('property-management.contracts.edit', $contract->id) }}" 
                                                       class="btn btn-sm btn-outline-dark" 
                                                       title="تعديل">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="ti ti-file-off" style="font-size: 64px; opacity: 0.3;"></i>
                                                    <p class="mt-3 mb-0 fs-5">لا توجد عقود</p>
                                                    <p class="text-muted small">ابدأ بإضافة عقد جديد</p>
                                                    <a href="{{ route('property-management.contracts.create') }}" class="btn btn-primary btn-sm mt-2">
                                                        <i class="ti ti-plus"></i> إضافة عقد جديد
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stats-card-dark {
        background: #212529;
        color: white;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stats-card-gray {
        background: #6c757d;
        color: white;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stats-card-gray-light {
        background: #adb5bd;
        color: white;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stats-card-dark-light {
        background: #495057;
        color: white;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stats-card-dark:hover,
    .stats-card-gray:hover,
    .stats-card-gray-light:hover,
    .stats-card-dark-light:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .stats-card-dark .text-white,
    .stats-card-gray .text-white,
    .stats-card-gray-light .text-white,
    .stats-card-dark-light .text-white {
        color: #ffffff !important;
    }
    
    .contract-checkbox, #selectAll {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    #bulkDeleteBtn {
        transition: all 0.3s ease;
    }
    
    .table-row-hover {
        transition: all 0.2s ease;
    }
    
    .table-row-hover:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    #contractsTable thead th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem 0.75rem;
    }
    
    #contractsTable tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .form-select, .form-control {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #212529;
        box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
    }
    
    .avatar-sm {
        width: 48px;
        height: 48px;
    }
    
    .avatar-xs {
        width: 32px;
        height: 32px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const contractCheckboxes = document.querySelectorAll('.contract-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkActionForm = document.getElementById('bulkActionForm');

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            contractCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkDeleteButton();
        });
    }

    // Individual checkbox change
    contractCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateBulkDeleteButton();
        });
    });

    // Update Select All checkbox state
    function updateSelectAllState() {
        if (!selectAllCheckbox) return;
        const allChecked = Array.from(contractCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(contractCheckboxes).some(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked && !allChecked;
    }

    // Update bulk delete button visibility and count
    function updateBulkDeleteButton() {
        if (!bulkDeleteBtn) return;
        const selectedCount = Array.from(contractCheckboxes).filter(cb => cb.checked).length;
        if (selectedCount > 0) {
            bulkDeleteBtn.style.display = 'inline-block';
            selectedCountSpan.textContent = selectedCount;
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }

    // Bulk delete confirmation
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedCheckboxes = Array.from(contractCheckboxes).filter(cb => cb.checked);
            const selectedContractNumbers = selectedCheckboxes.map(cb => cb.getAttribute('data-contract-number')).join(', ');
            
            if (confirm(`هل أنت متأكد من حذف ${selectedCheckboxes.length} عقد؟\nالعقود: ${selectedContractNumbers}`)) {
                bulkActionForm.submit();
            }
        });
    }
});
</script>
@endsection
