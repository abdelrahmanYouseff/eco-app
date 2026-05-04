@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">إدارة المباني</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
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
        
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">قائمة المباني</h5>
                        <div>
                            <a href="{{ route('property-management.buildings.create') }}" class="btn btn-dark">
                                <i class="ti ti-plus"></i> إضافة مبنى جديد
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="buildingsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">#</th>
                                        <th class="text-right">اسم المبنى</th>
                                        <th class="text-center">المالك</th>
                                        <th class="text-center" style="width: 150px;">عدد الوحدات</th>
                                        <th class="text-center" style="width: 130px;">تاريخ الإنشاء</th>
                                        <th class="text-center" style="width: 200px;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($buildings as $building)
                                    <tr>
                                        <td class="text-center align-middle">{{ $building->id }}</td>
                                        <td class="text-right align-middle">
                                            <strong>{{ $building->name }}</strong>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge bg-dark">{{ $building->owner->name ?? 'غير محدد' }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge bg-secondary">{{ $building->units_count ?? 0 }} وحدة</span>
                                        </td>
                                        <td class="text-center align-middle">{{ $building->created_at ? $building->created_at->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('property-management.buildings.show', $building->id) }}" 
                                                   class="btn btn-sm btn-outline-dark" 
                                                   title="تفاصيل">
                                                    <i class="ti ti-eye"></i> تفاصيل
                                                </a>
                                                <a href="{{ route('property-management.buildings.edit', $building->id) }}" 
                                                   class="btn btn-sm btn-outline-secondary" 
                                                   title="تعديل">
                                                    <i class="ti ti-edit"></i> تعديل
                                                </a>
                                                <form action="{{ route('property-management.buildings.destroy', $building->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف المبنى {{ $building->name }}؟');">
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
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-building-off" style="font-size: 48px;"></i>
                                                <p class="mt-2">لا توجد مباني في النظام</p>
                                                <a href="{{ route('property-management.buildings.create') }}" class="btn btn-dark mt-2">
                                                    إضافة أول مبنى
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($buildings->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $buildings->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #buildingsTable {
        font-size: 14px;
        width: 100%;
        border-collapse: collapse;
    }
    #buildingsTable thead th {
        font-weight: 600;
        padding: 12px 8px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        white-space: nowrap;
    }
    #buildingsTable tbody td {
        padding: 12px 8px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }
    #buildingsTable tbody tr:hover {
        background-color: #f8f9fa;
    }
    #buildingsTable .badge {
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 500;
    }
    #buildingsTable .btn {
        padding: 6px 12px;
        font-size: 12px;
        white-space: nowrap;
    }
    .d-flex.gap-2 {
        gap: 8px;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>
@endsection
