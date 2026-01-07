@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تعديل بيانات الوسيط</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">تعديل بيانات الوسيط: {{ $broker->name }}</h5>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form action="{{ route('property-management.brokers.update', $broker->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">اسم الوسيط <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $broker->name) }}" required>
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="representative_name" class="form-label">اسم الممثل</label>
                                <input type="text" class="form-control" id="representative_name" name="representative_name" value="{{ old('representative_name', $broker->representative_name) }}">
                            </div>

                            <div class="mb-3">
                                <label for="cr_number" class="form-label">رقم السجل التجاري</label>
                                <input type="text" class="form-control" id="cr_number" name="cr_number" value="{{ old('cr_number', $broker->cr_number) }}">
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label">الجوال <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile', $broker->mobile) }}" required>
                                @error('mobile')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $broker->email) }}">
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">العنوان</label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $broker->address) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('property-management.brokers.show', $broker->id) }}" class="btn btn-outline-secondary">إلغاء</a>
                                <button type="submit" class="btn btn-dark">حفظ التغييرات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


