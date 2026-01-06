@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">المستأجرون / العملاء</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">قائمة المستأجرين</h5>
                        <a href="{{ route('property-management.tenants.create') }}" class="btn btn-dark">
                            <i class="ti ti-plus"></i> إضافة مستأجر جديد
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('property-management.tenants.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text" name="search" class="form-control" placeholder="البحث بالاسم، الجوال، البريد الإلكتروني..." value="{{ $query }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-dark w-100">
                                        <i class="ti ti-search"></i> بحث
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">الاسم</th>
                                        <th class="border-0">النوع</th>
                                        <th class="border-0">الجوال</th>
                                        <th class="border-0">البريد الإلكتروني</th>
                                        <th class="border-0">رقم الهوية/السجل التجاري</th>
                                        <th class="border-0">العقود</th>
                                        <th class="border-0">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tenants as $tenant)
                                    <tr>
                                        <td>{{ $tenant->name }}</td>
                                        <td>{{ $tenant->client_type }}</td>
                                        <td>{{ $tenant->mobile }}</td>
                                        <td>{{ $tenant->email ?? 'غير متوفر' }}</td>
                                        <td>{{ $tenant->id_number_or_cr }}</td>
                                        <td>{{ $tenant->contracts->count() }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('property-management.tenants.show', $tenant->id) }}" class="btn btn-sm btn-outline-dark">
                                                    <i class="ti ti-eye"></i> عرض
                                                </a>
                                                <form action="{{ route('property-management.tenants.destroy', $tenant->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف المستأجر {{ $tenant->name }}؟');">
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
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-users-off" style="font-size: 48px;"></i>
                                                <p class="mt-2">لا يوجد مستأجرون</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if(method_exists($tenants, 'links'))
                            {{ $tenants->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


