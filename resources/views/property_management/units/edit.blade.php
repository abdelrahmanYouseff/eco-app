@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تعديل الوحدة</h2>
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

                <form action="{{ route('property-management.units.update', $unit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
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
                                <div class="col-md-6 mb-3">
                                    <label for="building_id" class="form-label fw-bold">
                                        المبنى <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('building_id') is-invalid @enderror" 
                                            id="building_id" 
                                            name="building_id" 
                                            required>
                                        <option value="">اختر المبنى</option>
                                        @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ old('building_id', $unit->building_id) == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('building_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="unit_number" class="form-label fw-bold">
                                        رقم الوحدة <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('unit_number') is-invalid @enderror" 
                                           id="unit_number" 
                                           name="unit_number" 
                                           value="{{ old('unit_number', $unit->unit_number) }}" 
                                           required
                                           placeholder="مثال: 101">
                                    @error('unit_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="floor_number" class="form-label fw-bold">
                                        رقم الطابق <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('floor_number') is-invalid @enderror" 
                                           id="floor_number" 
                                           name="floor_number" 
                                           value="{{ old('floor_number', $unit->floor_number) }}" 
                                           required
                                           placeholder="مثال: 1 أو أرضي">
                                    @error('floor_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="unit_type" class="form-label fw-bold">
                                        نوع الوحدة <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('unit_type') is-invalid @enderror" 
                                            id="unit_type" 
                                            name="unit_type" 
                                            required>
                                        <option value="">اختر النوع</option>
                                        <option value="مكتب" {{ old('unit_type', $unit->unit_type) == 'مكتب' ? 'selected' : '' }}>مكتب</option>
                                        <option value="شقة" {{ old('unit_type', $unit->unit_type) == 'شقة' ? 'selected' : '' }}>شقة</option>
                                        <option value="محل" {{ old('unit_type', $unit->unit_type) == 'محل' ? 'selected' : '' }}>محل</option>
                                    </select>
                                    @error('unit_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="area" class="form-label fw-bold">
                                        المساحة (متر مربع) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="0.01"
                                               class="form-control @error('area') is-invalid @enderror" 
                                               id="area" 
                                               name="area" 
                                               value="{{ old('area', $unit->area) }}" 
                                               required
                                               min="0"
                                               placeholder="مثال: 120.50">
                                        <span class="input-group-text">m²</span>
                                    </div>
                                    @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات إضافية -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-settings me-2"></i>
                                المعلومات الإضافية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="parking_lots" class="form-label fw-bold">
                                        <i class="ti ti-car me-1"></i>
                                        عدد مواقف السيارات
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('parking_lots') is-invalid @enderror" 
                                           id="parking_lots" 
                                           name="parking_lots" 
                                           value="{{ old('parking_lots', $unit->parking_lots) }}" 
                                           min="0"
                                           placeholder="0">
                                    @error('parking_lots')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="ac_units" class="form-label fw-bold">
                                        <i class="ti ti-wind me-1"></i>
                                        عدد وحدات التكييف
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('ac_units') is-invalid @enderror" 
                                           id="ac_units" 
                                           name="ac_units" 
                                           value="{{ old('ac_units', $unit->ac_units) }}" 
                                           min="0"
                                           placeholder="0">
                                    @error('ac_units')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="finishing_type" class="form-label fw-bold">
                                        <i class="ti ti-paint me-1"></i>
                                        نوع التشطيب
                                    </label>
                                    <select class="form-select @error('finishing_type') is-invalid @enderror" 
                                            id="finishing_type" 
                                            name="finishing_type">
                                        <option value="">اختر النوع</option>
                                        <option value="furnished" {{ old('finishing_type', $unit->finishing_type) == 'furnished' ? 'selected' : '' }}>مفروش</option>
                                        <option value="unfurnished" {{ old('finishing_type', $unit->finishing_type) == 'unfurnished' ? 'selected' : '' }}>غير مفروش</option>
                                    </select>
                                    @error('finishing_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- العدادات -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-gauge me-2"></i>
                                قراءات العدادات
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="current_electricity_meter" class="form-label fw-bold">
                                        <i class="ti ti-bolt me-1"></i>
                                        عداد الكهرباء الحالي
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('current_electricity_meter') is-invalid @enderror" 
                                           id="current_electricity_meter" 
                                           name="current_electricity_meter" 
                                           value="{{ old('current_electricity_meter', $unit->current_electricity_meter) }}"
                                           placeholder="رقم عداد الكهرباء">
                                    @error('current_electricity_meter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="current_water_meter" class="form-label fw-bold">
                                        <i class="ti ti-droplet me-1"></i>
                                        عداد المياه الحالي
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('current_water_meter') is-invalid @enderror" 
                                           id="current_water_meter" 
                                           name="current_water_meter" 
                                           value="{{ old('current_water_meter', $unit->current_water_meter) }}"
                                           placeholder="رقم عداد المياه">
                                    @error('current_water_meter')
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
                                <a href="{{ route('property-management.units.show', $unit->id) }}" class="btn-action btn-cancel">
                                    <i class="ti ti-arrow-right"></i>
                                    <span>إلغاء</span>
                                </a>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('property-management.units.index') }}" class="btn-action btn-back">
                                        <i class="ti ti-list"></i>
                                        <span>العودة للقائمة</span>
                                    </a>
                                    <button type="submit" class="btn-action btn-save">
                                        <i class="ti ti-check"></i>
                                        <span>حفظ التعديلات</span>
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

    /* زر الحفظ */
    .btn-save {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: #ffffff;
        min-width: 160px;
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
        color: #ffffff;
    }

    .btn-save:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-save:hover:before {
        width: 300px;
        height: 300px;
    }

    /* زر العودة للقائمة */
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

    /* زر الإلغاء */
    .btn-cancel {
        background: #6c757d;
        color: #ffffff;
        min-width: 140px;
    }

    .btn-cancel:hover {
        background: #5c636a;
        color: #ffffff;
    }

    /* تأثير اللمس للأزرار */
    @media (max-width: 768px) {
        .btn-action {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .d-flex.gap-2 {
            flex-direction: column;
            width: 100%;
        }
    }
</style>
@endsection
