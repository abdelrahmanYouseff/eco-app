@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">تفاصيل الوحدة: {{ $unit->unit_number }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- معلومات الوحدة الأساسية -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-layout-grid me-2"></i>
                            معلومات الوحدة
                        </h5>
                        <div>
                            <a href="{{ route('property-management.units.index') }}" class="btn btn-sm btn-secondary">
                                <i class="ti ti-arrow-right"></i> العودة للقائمة
                            </a>
                            <a href="{{ route('property-management.units.edit', $unit->id) }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-edit"></i> تعديل
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">رقم الوحدة:</th>
                                        <td><strong>{{ $unit->unit_number }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>المبنى:</th>
                                        <td>
                                            @if($unit->building)
                                                <a href="{{ route('property-management.buildings.show', $unit->building->id) }}" class="text-decoration-none">
                                                    <span class="badge bg-dark text-white">
                                                        <i class="ti ti-building me-1"></i>
                                                        {{ $unit->building->name }}
                                                    </span>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>نوع الوحدة:</th>
                                        <td>
                                            <span class="badge bg-secondary">{{ $unit->unit_type }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>حالة الوحدة:</th>
                                        <td>
                                            @php
                                                $hasActiveContract = $unit->contracts->where('start_date', '<=', now())
                                                    ->where('end_date', '>=', now())
                                                    ->count() > 0;
                                            @endphp
                                            @if($hasActiveContract)
                                                <span class="badge bg-danger">
                                                    <i class="ti ti-x me-1"></i>
                                                    مشغولة
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="ti ti-check me-1"></i>
                                                    متاحة
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>الطابق:</th>
                                        <td>{{ $unit->floor_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>المساحة:</th>
                                        <td>{{ number_format($unit->area, 2) }} متر مربع</td>
                                    </tr>
                                    @if($unit->direction)
                                    <tr>
                                        <th>الاتجاه:</th>
                                        <td>{{ $unit->direction }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 200px;">مواقف السيارات:</th>
                                        <td>{{ $unit->parking_lots ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>ميزانين:</th>
                                        <td>
                                            @if($unit->mezzanine)
                                                <span class="badge bg-success">نعم</span>
                                            @else
                                                <span class="badge bg-secondary">لا</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($unit->finishing_type)
                                    <tr>
                                        <th>نوع التشطيب:</th>
                                        <td>
                                            @if($unit->finishing_type == 'furnished')
                                                <span class="badge bg-info">مفروش</span>
                                            @else
                                                <span class="badge bg-warning text-dark">غير مفروش</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>وحدات التكييف:</th>
                                        <td>{{ $unit->ac_units ?? 0 }}</td>
                                    </tr>
                                    @if($unit->current_electricity_meter)
                                    <tr>
                                        <th>عداد الكهرباء الحالي:</th>
                                        <td><code>{{ $unit->current_electricity_meter }}</code></td>
                                    </tr>
                                    @endif
                                    @if($unit->current_water_meter)
                                    <tr>
                                        <th>عداد المياه الحالي:</th>
                                        <td><code>{{ $unit->current_water_meter }}</code></td>
                                    </tr>
                                    @endif
                                    @if($unit->current_gas_meter)
                                    <tr>
                                        <th>عداد الغاز الحالي:</th>
                                        <td><code>{{ $unit->current_gas_meter }}</code></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>تاريخ الإنشاء:</th>
                                        <td>{{ $unit->created_at ? $unit->created_at->format('Y-m-d H:i') : 'غير محدد' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- العقود النشطة -->
                @if($unit->contracts && $unit->contracts->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-file-text me-2"></i>
                            العقود النشطة ({{ $unit->contracts->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>رقم العقد</th>
                                        <th>العميل</th>
                                        <th>تاريخ البدء</th>
                                        <th>تاريخ الانتهاء</th>
                                        <th class="text-end">الإيجار السنوي</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unit->contracts as $index => $contract)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="text-decoration-none">
                                                <strong>{{ $contract->contract_number }}</strong>
                                            </a>
                                        </td>
                                        <td class="align-middle">
                                            @if($contract->client)
                                                <a href="{{ route('property-management.tenants.show', $contract->client->id) }}" class="text-decoration-none">
                                                    {{ $contract->client->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $contract->start_date->format('Y-m-d') }}</td>
                                        <td class="align-middle">{{ $contract->end_date->format('Y-m-d') }}</td>
                                        <td class="text-end align-middle">{{ number_format($contract->annual_rent, 2) }} ريال</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="btn btn-sm btn-secondary">
                                                <i class="ti ti-eye"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                <div class="card mb-4">
                    <div class="card-body text-center py-4">
                        <i class="ti ti-file-off" style="font-size: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-2">لا توجد عقود نشطة لهذه الوحدة</p>
                    </div>
                </div>
                @endif

                <!-- جميع العقود -->
                @php
                    $allContracts = \App\PropertyManagement\Models\Contract::where('unit_id', $unit->id)
                        ->with(['client'])
                        ->orderBy('start_date', 'desc')
                        ->get();
                @endphp
                @if($allContracts->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-file-text me-2"></i>
                            جميع العقود ({{ $allContracts->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>رقم العقد</th>
                                        <th>العميل</th>
                                        <th>تاريخ البدء</th>
                                        <th>تاريخ الانتهاء</th>
                                        <th class="text-end">الإيجار السنوي</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allContracts as $index => $contract)
                                    @php
                                        $now = now();
                                        $startDate = \Carbon\Carbon::parse($contract->start_date);
                                        $endDate = \Carbon\Carbon::parse($contract->end_date);
                                        $isActive = $now->gte($startDate) && $now->lte($endDate);
                                        $isExpired = $now->gt($endDate);
                                        $isUpcoming = $now->lt($startDate);
                                    @endphp
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="align-middle">
                                            <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="text-decoration-none">
                                                <strong>{{ $contract->contract_number }}</strong>
                                            </a>
                                        </td>
                                        <td class="align-middle">
                                            @if($contract->client)
                                                <a href="{{ route('property-management.tenants.show', $contract->client->id) }}" class="text-decoration-none">
                                                    {{ $contract->client->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $contract->start_date->format('Y-m-d') }}</td>
                                        <td class="align-middle">{{ $contract->end_date->format('Y-m-d') }}</td>
                                        <td class="text-end align-middle">{{ number_format($contract->annual_rent, 2) }} ريال</td>
                                        <td class="text-center align-middle">
                                            @if($isActive)
                                                <span class="badge bg-success">نشط</span>
                                            @elseif($isExpired)
                                                <span class="badge bg-secondary">منتهي</span>
                                            @elseif($isUpcoming)
                                                <span class="badge bg-info">قادم</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="btn btn-sm btn-secondary">
                                                <i class="ti ti-eye"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        color: #495057;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
</style>
@endsection
