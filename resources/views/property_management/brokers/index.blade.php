@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">الوسطاء / الوكلاء</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="font-weight: 600; color: #212529;">قائمة الوسطاء</h5>
                        <a href="{{ route('property-management.brokers.create') }}" class="btn btn-dark">
                            <i class="ti ti-plus"></i> إضافة وسيط جديد
                        </a>
                    </div>
                    <div class="card-body">
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

                        <form method="GET" action="{{ route('property-management.brokers.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text" name="search" class="form-control" placeholder="البحث بالاسم، الجوال، البريد الإلكتروني، رقم السجل التجاري..." value="{{ request('search') }}">
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
                                        <th class="border-0">اسم الممثل</th>
                                        <th class="border-0">رقم السجل التجاري</th>
                                        <th class="border-0">الجوال</th>
                                        <th class="border-0">البريد الإلكتروني</th>
                                        <th class="border-0">عدد العقود</th>
                                        <th class="border-0">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($brokers as $broker)
                                    <tr>
                                        <td><strong>{{ $broker->name }}</strong></td>
                                        <td>{{ $broker->representative_name ?? 'غير متوفر' }}</td>
                                        <td>{{ $broker->cr_number ?? 'غير متوفر' }}</td>
                                        <td>{{ $broker->mobile }}</td>
                                        <td>{{ $broker->email ?? 'غير متوفر' }}</td>
                                        <td>
                                            <span class="badge bg-dark">{{ $broker->contracts_count }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('property-management.brokers.show', $broker->id) }}" class="btn btn-sm btn-outline-dark">
                                                    <i class="ti ti-eye"></i> عرض
                                                </a>
                                                <a href="{{ route('property-management.brokers.edit', $broker->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="ti ti-edit"></i> تعديل
                                                </a>
                                                <form action="{{ route('property-management.brokers.destroy', $broker->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف الوسيط {{ $broker->name }}؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
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
                                                <i class="ti ti-user-off" style="font-size: 48px;"></i>
                                                <p class="mt-2">لا يوجد وسطاء</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($brokers->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $brokers->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

