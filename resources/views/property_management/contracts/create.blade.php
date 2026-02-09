@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">إضافة عقد جديد</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>خطأ!</strong> يرجى مراجعة الأخطاء التالية:
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('property-management.contracts.store') }}" method="POST" id="contractForm">
                    @csrf
                    
                    <!-- معلومات أساسية -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-info-circle me-2"></i>
                                المعلومات الأساسية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="contract_type" class="form-label fw-bold">
                                        نوع العقد <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('contract_type') is-invalid @enderror" 
                                            id="contract_type" 
                                            name="contract_type" 
                                            required>
                                        <option value="">اختر النوع</option>
                                        <option value="جديد" {{ old('contract_type') == 'جديد' ? 'selected' : '' }}>جديد</option>
                                        <option value="مجدد" {{ old('contract_type') == 'مجدد' ? 'selected' : '' }}>مجدد</option>
                                    </select>
                                    @error('contract_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="building_id" class="form-label fw-bold">
                                        المبنى <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('building_id') is-invalid @enderror" 
                                            id="building_id" 
                                            name="building_id" 
                                            required>
                                        <option value="">اختر المبنى</option>
                                        @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('building_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="unit_id" class="form-label fw-bold">
                                        الوحدة <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('unit_id') is-invalid @enderror" 
                                            id="unit_id" 
                                            name="unit_id" 
                                            required>
                                        <option value="">اختر المبنى أولاً</option>
                                    </select>
                                    @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="client_id" class="form-label fw-bold mb-0">
                                            العميل <span class="text-danger">*</span>
                                        </label>
                                        <a href="{{ route('property-management.tenants.create', ['return_to' => 'contract']) }}" 
                                           onclick="saveContractFormData(); return true;"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-plus"></i> إضافة عميل جديد
                                        </a>
                                    </div>
                                    <select class="form-select @error('client_id') is-invalid @enderror" 
                                            id="client_id" 
                                            name="client_id" 
                                            required>
                                        <option value="">اختر العميل</option>
                                        @forelse($clients as $client)
                                        <option value="{{ $client->id }}" {{ (old('client_id') == $client->id || (isset($newClientId) && $newClientId == $client->id)) ? 'selected' : '' }}>
                                            {{ $client->name }} ({{ $client->client_type }})
                                        </option>
                                        @empty
                                        <option value="" disabled>لا توجد عملاء - اضغط على "إضافة عميل جديد"</option>
                                        @endforelse
                                    </select>
                                    @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($clients->isEmpty())
                                    <small class="form-text text-muted">
                                        <i class="ti ti-info-circle"></i> لا توجد عملاء مسجلين. 
                                        <a href="{{ route('property-management.tenants.create', ['return_to' => 'contract']) }}" 
                                           onclick="saveContractFormData();"
                                           class="text-primary">أضف عميل جديد</a>
                                    </small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="broker_id" class="form-label fw-bold mb-0">
                                            الوسيط
                                        </label>
                                        <a href="#" 
                                           onclick="alert('سيتم إضافة صفحة إضافة وسيط جديد قريباً'); return false;"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-plus"></i> إضافة وسيط جديد
                                        </a>
                                    </div>
                                    <select class="form-select @error('broker_id') is-invalid @enderror" 
                                            id="broker_id" 
                                            name="broker_id">
                                        <option value="">بدون وسيط</option>
                                        @forelse($brokers as $broker)
                                        <option value="{{ $broker->id }}" {{ old('broker_id') == $broker->id ? 'selected' : '' }}>
                                            {{ $broker->name }}
                                        </option>
                                        @empty
                                        <option value="" disabled>لا توجد وسطاء - اضغط على "إضافة وسيط جديد"</option>
                                        @endforelse
                                    </select>
                                    @error('broker_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($brokers->isEmpty())
                                    <small class="form-text text-muted">
                                        <i class="ti ti-info-circle"></i> لا توجد وسطاء مسجلين. 
                                        <a href="#" onclick="alert('سيتم إضافة صفحة إضافة وسيط جديد قريباً'); return false;" class="text-primary">أضف وسيط جديد</a>
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تواريخ العقد -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-calendar me-2"></i>
                                تواريخ العقد
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="contract_signing_date" class="form-label fw-bold">
                                        تاريخ إبرام العقد
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('contract_signing_date') is-invalid @enderror" 
                                           id="contract_signing_date" 
                                           name="contract_signing_date" 
                                           value="{{ old('contract_signing_date') }}">
                                    @error('contract_signing_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="start_date" class="form-label fw-bold">
                                        تاريخ البدء <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="{{ old('start_date') }}" 
                                           required>
                                    @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="end_date" class="form-label fw-bold">
                                        تاريخ الانتهاء <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="{{ old('end_date') }}" 
                                           required>
                                    @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_conditional" 
                                               name="is_conditional" 
                                               value="1"
                                               {{ old('is_conditional') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_conditional">
                                            عقد مشروط
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المبالغ المالية -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-currency-dollar me-2"></i>
                                المبالغ المالية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="total_rent" class="form-label fw-bold">
                                        إجمالي الإيجار <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.01"
                                               class="form-control @error('total_rent') is-invalid @enderror" 
                                               id="total_rent" 
                                               name="total_rent" 
                                               value="{{ old('total_rent') }}" 
                                               required
                                               min="0"
                                               placeholder="0.00">
                                        <span class="input-group-text">ريال</span>
                                    </div>
                                    @error('total_rent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="annual_rent" class="form-label fw-bold">
                                        الإيجار السنوي <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.01"
                                               class="form-control @error('annual_rent') is-invalid @enderror" 
                                               id="annual_rent" 
                                               name="annual_rent" 
                                               value="{{ old('annual_rent') }}" 
                                               required
                                               min="0"
                                               placeholder="0.00">
                                        <span class="input-group-text">ريال</span>
                                    </div>
                                    @error('annual_rent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="rent_cycle" class="form-label fw-bold">
                                        دورة الإيجار (بالأشهر) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('rent_cycle') is-invalid @enderror" 
                                           id="rent_cycle" 
                                           name="rent_cycle" 
                                           value="{{ old('rent_cycle', 12) }}" 
                                           required
                                           min="1"
                                           placeholder="12">
                                    @error('rent_cycle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">عدد الأشهر في كل دورة دفع</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="deposit_amount" class="form-label fw-bold">
                                        مبلغ التأمين
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.01"
                                               class="form-control @error('deposit_amount') is-invalid @enderror" 
                                               id="deposit_amount" 
                                               name="deposit_amount" 
                                               value="{{ old('deposit_amount', 0) }}" 
                                               min="0"
                                               placeholder="0.00">
                                        <span class="input-group-text">ريال</span>
                                    </div>
                                    @error('deposit_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="vat_amount" class="form-label fw-bold">
                                        مبلغ ضريبة القيمة المضافة
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.01"
                                               class="form-control @error('vat_amount') is-invalid @enderror" 
                                               id="vat_amount" 
                                               name="vat_amount" 
                                               value="{{ old('vat_amount', 0) }}" 
                                               min="0"
                                               placeholder="0.00">
                                        <span class="input-group-text">ريال</span>
                                    </div>
                                    @error('vat_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="general_services_amount" class="form-label fw-bold">
                                        مبلغ الخدمات العامة
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.01"
                                               class="form-control @error('general_services_amount') is-invalid @enderror" 
                                               id="general_services_amount" 
                                               name="general_services_amount" 
                                               value="{{ old('general_services_amount', 0) }}" 
                                               min="0"
                                               placeholder="0.00">
                                        <span class="input-group-text">ريال</span>
                                    </div>
                                    @error('general_services_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="fixed_amounts" class="form-label fw-bold">
                                        المبالغ الثابتة
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.01"
                                               class="form-control @error('fixed_amounts') is-invalid @enderror" 
                                               id="fixed_amounts" 
                                               name="fixed_amounts" 
                                               value="{{ old('fixed_amounts', 0) }}" 
                                               min="0"
                                               placeholder="0.00">
                                        <span class="input-group-text">ريال</span>
                                    </div>
                                    @error('fixed_amounts')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="insurance_policy_number" class="form-label fw-bold">
                                        رقم بوليصة التأمين
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('insurance_policy_number') is-invalid @enderror" 
                                           id="insurance_policy_number" 
                                           name="insurance_policy_number" 
                                           value="{{ old('insurance_policy_number') }}"
                                           placeholder="رقم بوليصة التأمين">
                                    @error('insurance_policy_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <a href="{{ route('property-management.contracts.index') }}" class="btn-action btn-cancel">
                                    <i class="ti ti-arrow-right"></i>
                                    <span>إلغاء</span>
                                </a>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('property-management.contracts.index') }}" class="btn-action btn-back">
                                        <i class="ti ti-list"></i>
                                        <span>العودة للقائمة</span>
                                    </a>
                                    <button type="submit" class="btn-action btn-save">
                                        <i class="ti ti-check"></i>
                                        <span>حفظ العقد</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .card-header {
        padding: 1rem 1.5rem;
    }
    .card-header h5 {
        font-size: 1.1rem;
        font-weight: 600;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    /* تصميم الأزرار الاحترافي */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-action i {
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-action:hover i {
        transform: scale(1.1);
    }

    .btn-action:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-save {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: #ffffff;
        min-width: 160px;
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
        color: #ffffff;
    }

    .btn-back {
        background: #ffffff;
        color: #6c757d;
        border: 2px solid #dee2e6;
        min-width: 160px;
    }

    .btn-back:hover {
        background: #f8f9fa;
        color: #495057;
        border-color: #adb5bd;
    }

    .btn-cancel {
        background: #6c757d;
        color: #ffffff;
        min-width: 140px;
    }

    .btn-cancel:hover {
        background: #5c636a;
        color: #ffffff;
    }
</style>

<script>
// Function to save form data to localStorage
function saveContractFormData() {
    const form = document.getElementById('contractForm');
    const formData = new FormData(form);
    const data = {};
    
    // Save all form fields
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    // Save checkbox state
    data.is_conditional = document.getElementById('is_conditional').checked ? '1' : '';
    
    localStorage.setItem('contractFormData', JSON.stringify(data));
}

// Function to restore form data from localStorage
function restoreContractFormData() {
    const savedData = localStorage.getItem('contractFormData');
    if (!savedData) return;
    
    try {
        const data = JSON.parse(savedData);
        const form = document.getElementById('contractForm');
        
        // Restore all fields
        Object.keys(data).forEach(key => {
            const field = form.querySelector(`[name="${key}"]`);
            if (field) {
                if (field.type === 'checkbox') {
                    field.checked = data[key] === '1';
                } else {
                    field.value = data[key];
                }
            }
        });
        
        // Trigger building change to load units
        const buildingSelect = document.getElementById('building_id');
        if (buildingSelect.value) {
            buildingSelect.dispatchEvent(new Event('change'));
            setTimeout(() => {
                const unitSelect = document.getElementById('unit_id');
                if (data.unit_id) {
                    unitSelect.value = data.unit_id;
                }
            }, 100);
        }
        
        // Clear saved data after restoring
        localStorage.removeItem('contractFormData');
    } catch (e) {
        console.error('Error restoring form data:', e);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const buildingSelect = document.getElementById('building_id');
    const unitSelect = document.getElementById('unit_id');
    const units = @json($units);

    // Restore form data if available
    restoreContractFormData();

    // Filter units when building changes
    buildingSelect.addEventListener('change', function() {
        const buildingId = this.value;
        unitSelect.innerHTML = '<option value="">اختر الوحدة</option>';
        
        if (buildingId) {
            const buildingUnits = units.filter(unit => unit.building_id == buildingId);
            buildingUnits.forEach(unit => {
                const option = document.createElement('option');
                option.value = unit.id;
                option.textContent = `${unit.unit_number} - ${unit.unit_type} (طابق ${unit.floor_number})`;
                unitSelect.appendChild(option);
            });
        }
    });

    // Set initial units if building is pre-selected
    if (buildingSelect.value) {
        buildingSelect.dispatchEvent(new Event('change'));
        const oldUnitId = '{{ old("unit_id") }}';
        if (oldUnitId) {
            unitSelect.value = oldUnitId;
        }
    }
});
</script>
@endsection

