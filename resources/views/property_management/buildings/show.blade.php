@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تفاصيل المبنى: {{ $building->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>معلومات المبنى</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>اسم المبنى:</th>
                                <td><strong>{{ $building->name }}</strong></td>
                            </tr>
                            <tr>
                                <th>المالك:</th>
                                <td>
                                    <span class="badge bg-secondary">{{ $building->owner->name ?? 'غير محدد' }}</span>
                                </td>
                            </tr>
                            @if($building->address)
                            <tr>
                                <th>عنوان المبنى:</th>
                                <td>{{ $building->address }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>تاريخ الإنشاء:</th>
                                <td>{{ $building->created_at ? $building->created_at->format('Y-m-d H:i') : 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <th>آخر تحديث:</th>
                                <td>{{ $building->updated_at ? $building->updated_at->format('Y-m-d H:i') : 'غير محدد' }}</td>
                            </tr>
                        </table>
                        
                        <div class="mt-3">
                            <a href="{{ route('property-management.buildings.edit', $building->id) }}" class="btn btn-secondary w-100">
                                <i class="ti ti-edit"></i> تعديل المبنى
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>إحصائيات المبنى</h5>
                        <a href="{{ route('property-management.units.index', ['building_id' => $building->id]) }}" class="btn btn-sm btn-secondary">
                            عرض جميع الوحدات
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-dark text-white">
                                    <div class="card-body text-center text-white">
                                        <h3 class="text-white">{{ $totalUnits }}</h3>
                                        <p class="mb-0 text-white">إجمالي الوحدات</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card stats-card-available text-white">
                                    <div class="card-body text-center text-white">
                                        <h3 class="text-white">{{ $availableUnits }}</h3>
                                        <p class="mb-0 text-white">وحدات متاحة</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card stats-card-occupied text-white">
                                    <div class="card-body text-center text-white">
                                        <h3 class="text-white">{{ $occupiedUnits }}</h3>
                                        <p class="mb-0 text-white">وحدات مشغولة</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>الوحدات</h5>
                        <a href="{{ route('property-management.units.create', ['building_id' => $building->id]) }}" class="btn btn-sm btn-secondary">
                            <i class="ti ti-plus"></i> إضافة وحدة جديدة
                        </a>
                    </div>
                    <div class="card-body">
                        @if($building->units && $building->units->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>رقم الوحدة</th>
                                        <th class="text-center">الطابق</th>
                                        <th class="text-center">النوع</th>
                                        <th class="text-center">المساحة</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">العقد</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($building->units as $index => $unit)
                                    @php
                                        $activeContract = $unit->contracts->where('start_date', '<=', now())
                                            ->where('end_date', '>=', now())
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <strong>{{ $unit->unit_number }}</strong>
                                        </td>
                                        <td class="text-center align-middle">{{ $unit->floor_number }}</td>
                                        <td class="text-center align-middle">
                                            <span class="badge bg-dark">{{ $unit->unit_type }}</span>
                                        </td>
                                        <td class="text-center align-middle">{{ $unit->area }} m²</td>
                                        <td class="text-center align-middle">
                                            @if($activeContract)
                                                <span class="badge bg-dark">مشغولة</span>
                                            @else
                                                <span class="badge bg-secondary">متاحة</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($activeContract)
                                                <a href="{{ route('property-management.contracts.show', $activeContract->id) }}" 
                                                   class="badge bg-primary text-white text-decoration-none"
                                                   title="عرض تفاصيل العقد">
                                                    <i class="ti ti-file-text me-1"></i>
                                                    {{ $activeContract->contract_number }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('property-management.units.show', $unit->id) }}" class="btn btn-sm btn-secondary">
                                                    <i class="ti ti-eye"></i> عرض
                                                </a>
                                                <a href="{{ route('property-management.units.edit', $unit->id) }}" class="btn btn-sm btn-dark">
                                                    <i class="ti ti-edit"></i> تعديل
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="ti ti-building-off" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">لا توجد وحدات في هذا المبنى</p>
                            <a href="{{ route('property-management.units.create', ['building_id' => $building->id]) }}" class="btn btn-secondary">
                                <i class="ti ti-plus"></i> إضافة وحدة جديدة
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stats-card-available {
        background-color: #198754 !important; /* أخضر غامق */
    }
    
    .stats-card-occupied {
        background-color: #dc3545 !important; /* أحمر غامق */
    }
</style>
@endsection

