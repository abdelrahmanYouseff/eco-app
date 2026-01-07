@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تفاصيل الوسيط: {{ $broker->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">معلومات الوسيط</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 40%;">الاسم:</td>
                                <td><strong>{{ $broker->name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">اسم الممثل:</td>
                                <td>{{ $broker->representative_name ?? 'غير متوفر' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">رقم السجل التجاري:</td>
                                <td>{{ $broker->cr_number ?? 'غير متوفر' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">الجوال:</td>
                                <td>{{ $broker->mobile }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">البريد الإلكتروني:</td>
                                <td>{{ $broker->email ?? 'غير متوفر' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">العنوان:</td>
                                <td>{{ $broker->address ?? 'غير متوفر' }}</td>
                            </tr>
                        </table>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('property-management.brokers.edit', $broker->id) }}" class="btn btn-dark flex-fill">
                                <i class="ti ti-edit"></i> تعديل
                            </a>
                            <a href="{{ route('property-management.brokers.index') }}" class="btn btn-outline-secondary flex-fill">
                                <i class="ti ti-arrow-left"></i> رجوع
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">العقود المرتبطة ({{ $broker->contracts->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">رقم العقد</th>
                                        <th class="border-0">العميل</th>
                                        <th class="border-0">الوحدة</th>
                                        <th class="border-0">المبنى</th>
                                        <th class="border-0">تاريخ البداية</th>
                                        <th class="border-0">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($broker->contracts as $contract)
                                    <tr>
                                        <td>{{ $contract->contract_number }}</td>
                                        <td>{{ $contract->client->name ?? 'غير متوفر' }}</td>
                                        <td>{{ $contract->unit->unit_number ?? 'غير متوفر' }}</td>
                                        <td>{{ $contract->building->name ?? 'غير متوفر' }}</td>
                                        <td>{{ $contract->start_date->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-dark">
                                                <i class="ti ti-eye"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">لا توجد عقود مرتبطة</div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


