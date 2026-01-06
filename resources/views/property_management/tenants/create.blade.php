@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">إضافة عميل جديد</h2>
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

                <form action="{{ route('property-management.tenants.store') }}" method="POST" id="tenantForm">
                    @csrf
                    @if(isset($returnTo) && $returnTo === 'contract')
                        <input type="hidden" name="return_to" value="contract">
                    @endif
                    
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
                                    <label for="name" class="form-label fw-bold">
                                        الاسم <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required
                                           placeholder="اسم العميل">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="client_type" class="form-label fw-bold">
                                        نوع العميل <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('client_type') is-invalid @enderror" 
                                            id="client_type" 
                                            name="client_type" 
                                            required>
                                        <option value="">اختر النوع</option>
                                        <option value="فرد" {{ old('client_type') == 'فرد' ? 'selected' : '' }}>فرد</option>
                                        <option value="شركة" {{ old('client_type') == 'شركة' ? 'selected' : '' }}>شركة</option>
                                    </select>
                                    @error('client_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="id_number_or_cr" class="form-label fw-bold">
                                        <span id="id_label">رقم الهوية / السجل التجاري</span> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('id_number_or_cr') is-invalid @enderror" 
                                           id="id_number_or_cr" 
                                           name="id_number_or_cr" 
                                           value="{{ old('id_number_or_cr') }}" 
                                           required
                                           placeholder="رقم الهوية أو السجل التجاري">
                                    @error('id_number_or_cr')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="id_type" class="form-label fw-bold">
                                        نوع الهوية
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('id_type') is-invalid @enderror" 
                                           id="id_type" 
                                           name="id_type" 
                                           value="{{ old('id_type') }}"
                                           placeholder="مثال: هوية وطنية، إقامة">
                                    @error('id_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات الاتصال -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-phone me-2"></i>
                                معلومات الاتصال
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mobile" class="form-label fw-bold">
                                        رقم الجوال <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('mobile') is-invalid @enderror" 
                                           id="mobile" 
                                           name="mobile" 
                                           value="{{ old('mobile') }}" 
                                           required
                                           placeholder="05xxxxxxxx">
                                    @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">
                                        البريد الإلكتروني
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           placeholder="example@email.com">
                                    @error('email')
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
                                <i class="ti ti-map-pin me-2"></i>
                                معلومات إضافية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nationality" class="form-label fw-bold">
                                        الجنسية
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nationality') is-invalid @enderror" 
                                           id="nationality" 
                                           name="nationality" 
                                           value="{{ old('nationality') }}"
                                           placeholder="مثال: سعودي">
                                    @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="national_address" class="form-label fw-bold">
                                        العنوان الوطني
                                    </label>
                                    <textarea class="form-control @error('national_address') is-invalid @enderror" 
                                              id="national_address" 
                                              name="national_address" 
                                              rows="3"
                                              placeholder="العنوان الوطني الكامل">{{ old('national_address') }}</textarea>
                                    @error('national_address')
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
                                <a href="{{ route('property-management.tenants.index') }}" class="btn-action btn-cancel">
                                    <i class="ti ti-arrow-right"></i>
                                    <span>إلغاء</span>
                                </a>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('property-management.tenants.index') }}" class="btn-action btn-back">
                                        <i class="ti ti-list"></i>
                                        <span>العودة للقائمة</span>
                                    </a>
                                    <button type="submit" class="btn-action btn-save">
                                        <i class="ti ti-check"></i>
                                        <span>حفظ العميل</span>
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
document.addEventListener('DOMContentLoaded', function() {
    const clientTypeSelect = document.getElementById('client_type');
    const idLabel = document.getElementById('id_label');
    const idTypeField = document.getElementById('id_type').parentElement;

    // تحديث التسمية حسب نوع العميل
    clientTypeSelect.addEventListener('change', function() {
        if (this.value === 'شركة') {
            idLabel.textContent = 'السجل التجاري';
            idTypeField.style.display = 'none';
        } else if (this.value === 'فرد') {
            idLabel.textContent = 'رقم الهوية';
            idTypeField.style.display = 'block';
        } else {
            idLabel.textContent = 'رقم الهوية / السجل التجاري';
            idTypeField.style.display = 'block';
        }
    });

    // تعيين القيمة الأولية
    if (clientTypeSelect.value) {
        clientTypeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection

