@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تعديل المبنى</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>معلومات المبنى</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('property-management.buildings.update', $building->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">اسم المبنى <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $building->name) }}" 
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="owner_id" class="form-label">المالك <span class="text-danger">*</span></label>
                                <select class="form-select @error('owner_id') is-invalid @enderror" 
                                        id="owner_id" 
                                        name="owner_id" 
                                        required>
                                    <option value="">اختر المالك</option>
                                    @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ old('owner_id', $building->owner_id) == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('owner_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="units_count" class="form-label">عدد الوحدات <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('units_count') is-invalid @enderror" 
                                           id="units_count" 
                                           name="units_count" 
                                           value="{{ old('units_count', $building->units_count) }}" 
                                           min="0"
                                           required
                                           placeholder="عدد الوحدات المتوقع في المبنى">
                                    @error('units_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">عدد الوحدات المتوقع في هذا المبنى</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="floors_count" class="form-label">عدد الطوابق <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('floors_count') is-invalid @enderror" 
                                           id="floors_count" 
                                           name="floors_count" 
                                           value="{{ old('floors_count', $building->floors_count ?? 1) }}" 
                                           min="1"
                                           required
                                           placeholder="عدد الطوابق في المبنى">
                                    @error('floors_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">عدد الطوابق في هذا المبنى</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">عنوان المبنى</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3" 
                                          placeholder="عنوان المبنى الكامل">{{ old('address', $building->address) }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">عنوان المبنى الكامل (اختياري)</small>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('property-management.buildings.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-arrow-right"></i> إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check"></i> حفظ التعديلات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

