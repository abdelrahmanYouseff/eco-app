@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">الإعدادات</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">الإعدادات العامة</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form action="{{ route('property-management.settings.update') }}" method="POST">
                            @csrf
                            
                            <!-- Financial Settings -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="font-weight: 600; color: #212529; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem;">الإعدادات المالية</h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="vat_percentage" class="form-label">نسبة ضريبة القيمة المضافة (VAT) % <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" id="vat_percentage" name="vat_percentage" value="{{ old('vat_percentage', $settings['vat_percentage']) }}" required>
                                        <small class="text-muted">النسبة المئوية لضريبة القيمة المضافة</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="currency" class="form-label">العملة <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="currency" name="currency" value="{{ old('currency', $settings['currency']) }}" required>
                                        <small class="text-muted">رمز العملة (مثل: SAR, USD, EUR)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Settings -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="font-weight: 600; color: #212529; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem;">إعدادات الإشعارات</h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="notification_days_before" class="form-label">عدد الأيام قبل الاستحقاق <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="notification_days_before" name="notification_days_before" value="{{ old('notification_days_before', $settings['notification_days_before']) }}" required min="1" max="365">
                                        <small class="text-muted">عدد الأيام قبل تاريخ الاستحقاق لإرسال الإشعار</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Company Information -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="font-weight: 600; color: #212529; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem;">معلومات الشركة</h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="company_name" class="form-label">اسم الشركة</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $settings['company_name']) }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="company_phone" class="form-label">هاتف الشركة</label>
                                        <input type="text" class="form-control" id="company_phone" name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="company_email" class="form-label">البريد الإلكتروني</label>
                                        <input type="email" class="form-control" id="company_email" name="company_email" value="{{ old('company_email', $settings['company_email']) }}">
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <label for="company_address" class="form-label">عنوان الشركة</label>
                                        <textarea class="form-control" id="company_address" name="company_address" rows="2">{{ old('company_address', $settings['company_address']) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Settings -->
                            <div class="mb-4">
                                <h6 class="mb-3" style="font-weight: 600; color: #212529; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem;">إعدادات المستندات</h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="invoice_prefix" class="form-label">بادئة رقم الفاتورة <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="invoice_prefix" name="invoice_prefix" value="{{ old('invoice_prefix', $settings['invoice_prefix']) }}" required>
                                        <small class="text-muted">مثال: INV (سيصبح INV-2025-0001)</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="receipt_prefix" class="form-label">بادئة رقم سند القبض <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="receipt_prefix" name="receipt_prefix" value="{{ old('receipt_prefix', $settings['receipt_prefix']) }}" required>
                                        <small class="text-muted">مثال: REC (سيصبح REC-2025-0001)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="submit" class="btn btn-dark">
                                    <i class="ti ti-device-floppy"></i> حفظ الإعدادات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    .form-control {
        border: 1px solid #dee2e6;
    }
    .form-control:focus {
        border-color: #212529;
        box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.1);
    }
    small.text-muted {
        font-size: 0.75rem;
        color: #6c757d;
    }
</style>
@endsection


