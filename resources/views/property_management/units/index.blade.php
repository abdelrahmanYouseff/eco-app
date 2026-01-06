@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">الوحدات / المكاتب</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">قائمة الوحدات</h5>
                        <div class="d-flex gap-2">
                            <button type="button" id="bulkDeleteBtn" class="btn btn-outline-dark" style="display: none;">
                                <i class="ti ti-trash"></i> حذف المحدد (<span id="selectedCount">0</span>)
                            </button>
                            <a href="{{ route('property-management.units.create') }}" class="btn btn-dark">
                                <i class="ti ti-plus"></i> إضافة وحدة جديدة
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('property-management.units.index') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="building_id" class="form-label">المبنى</label>
                                    <select name="building_id" id="building_id" class="form-select">
                                        <option value="">جميع المباني</option>
                                        @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ $buildingId == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="unit_type" class="form-label">نوع الوحدة</label>
                                    <select name="unit_type" id="unit_type" class="form-select">
                                        <option value="">الكل</option>
                                        <option value="مكتب" {{ $unitType == 'مكتب' ? 'selected' : '' }}>مكتب</option>
                                        <option value="شقة" {{ $unitType == 'شقة' ? 'selected' : '' }}>شقة</option>
                                        <option value="محل" {{ $unitType == 'محل' ? 'selected' : '' }}>محل</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="status" class="form-label">الحالة</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">الكل</option>
                                        <option value="available" {{ $status == 'available' ? 'selected' : '' }}>متاحة</option>
                                        <option value="occupied" {{ $status == 'occupied' ? 'selected' : '' }}>مشغولة</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="floor_number" class="form-label">الطابق</label>
                                    <select name="floor_number" id="floor_number" class="form-select">
                                        <option value="">الكل</option>
                                        @foreach($floors as $floor)
                                        <option value="{{ $floor }}" {{ $floorNumber == $floor ? 'selected' : '' }}>
                                            {{ $floor }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-3 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-dark flex-fill">
                                        <i class="ti ti-filter"></i> فلترة
                                    </button>
                                    <a href="{{ route('property-management.units.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-x"></i> إعادة تعيين
                                    </a>
                                </div>
                            </div>
                        </form>
                        
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form id="bulkActionForm" method="POST" action="{{ route('property-management.units.bulk-delete') }}">
                            @csrf
                            @method('DELETE')
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="unitsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center border-0" style="width: 50px;">
                                                <input type="checkbox" id="selectAll" title="تحديد الكل">
                                            </th>
                                            <th class="text-center border-0" style="width: 60px;">#</th>
                                            <th class="border-0">رقم الوحدة</th>
                                            <th class="border-0">المبنى</th>
                                            <th class="text-center border-0">الطابق</th>
                                            <th class="text-center border-0">النوع</th>
                                            <th class="text-center border-0">المساحة</th>
                                            <th class="text-center border-0">الحالة</th>
                                            <th class="text-center border-0" style="width: 250px;">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($units as $index => $unit)
                                        <tr>
                                            <td class="text-center align-middle">
                                                <input type="checkbox" 
                                                       name="unit_ids[]" 
                                                       value="{{ $unit->id }}" 
                                                       class="unit-checkbox"
                                                       data-unit-number="{{ $unit->unit_number }}">
                                            </td>
                                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <strong>{{ $unit->unit_number }}</strong>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-dark">{{ $unit->building->name ?? 'غير محدد' }}</span>
                                        </td>
                                        <td class="text-center align-middle">{{ $unit->floor_number }}</td>
                                        <td class="text-center align-middle">
                                            <span class="badge bg-secondary">{{ $unit->unit_type }}</span>
                                        </td>
                                        <td class="text-center align-middle">{{ $unit->area }} m²</td>
                                        <td class="text-center align-middle">
                                            @if($unit->contracts->where('start_date', '<=', now())->where('end_date', '>=', now())->count() > 0)
                                                <span class="badge bg-dark">مشغولة</span>
                                            @else
                                                <span class="badge bg-secondary">متاحة</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('property-management.units.show', $unit->id) }}" class="btn btn-sm btn-outline-dark">
                                                    <i class="ti ti-eye"></i> عرض
                                                </a>
                                                <a href="{{ route('property-management.units.edit', $unit->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="ti ti-edit"></i> تعديل
                                                </a>
                                                <form action="{{ route('property-management.units.destroy', $unit->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف الوحدة {{ $unit->unit_number }}؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-dark" 
                                                            title="حذف">
                                                        <i class="ti ti-trash"></i> حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-building-off" style="font-size: 48px;"></i>
                                                <p class="mt-2">لا توجد وحدات</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        </form>
                        
                        @if($units->count() > 0)
                        <div class="mt-3">
                            <p class="text-muted">إجمالي النتائج: <strong>{{ $units->count() }}</strong> وحدة</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #unitsTable {
        font-size: 14px;
    }
    #unitsTable thead th {
        font-weight: 600;
        padding: 12px 8px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    #unitsTable tbody td {
        padding: 12px 8px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }
    #unitsTable tbody tr:hover {
        background-color: #f8f9fa;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 5px;
    }
    .unit-checkbox, #selectAll {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    #bulkDeleteBtn {
        transition: all 0.3s ease;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const unitCheckboxes = document.querySelectorAll('.unit-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkActionForm = document.getElementById('bulkActionForm');

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        unitCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkDeleteButton();
    });

    // Individual checkbox change
    unitCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateBulkDeleteButton();
        });
    });

    // Update Select All checkbox state
    function updateSelectAllState() {
        const allChecked = Array.from(unitCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(unitCheckboxes).some(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked && !allChecked;
    }

    // Update bulk delete button visibility and count
    function updateBulkDeleteButton() {
        const selectedCount = Array.from(unitCheckboxes).filter(cb => cb.checked).length;
        if (selectedCount > 0) {
            bulkDeleteBtn.style.display = 'inline-block';
            selectedCountSpan.textContent = selectedCount;
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }

    // Bulk delete confirmation
    bulkDeleteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const selectedCheckboxes = Array.from(unitCheckboxes).filter(cb => cb.checked);
        const selectedUnitNumbers = selectedCheckboxes.map(cb => cb.getAttribute('data-unit-number')).join(', ');
        
        if (confirm(`هل أنت متأكد من حذف ${selectedCheckboxes.length} وحدة؟\nالوحدات: ${selectedUnitNumbers}`)) {
            bulkActionForm.submit();
        }
    });
});
</script>
@endsection

