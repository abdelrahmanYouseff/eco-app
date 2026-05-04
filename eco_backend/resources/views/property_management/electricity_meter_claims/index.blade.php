@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">مطالبة عداد الكهرباء</h2>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.9rem;">عرض العقود وإرسال مطالبة عداد الكهرباء بالبريد</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h5 class="mb-0">
                            <i class="ti ti-bolt me-2"></i>
                            قائمة العقود
                        </h5>
                        @if($contracts->count() > 0)
                            <span class="badge bg-light text-dark">
                                إجمالي {{ $contracts->count() }} عقد
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body py-3">
                                <form method="GET" action="{{ route('property-management.electricity-meter-claims.index') }}">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-2">
                                            <label class="form-label small text-muted mb-1">حالة المطالبة</label>
                                            <select name="claim_status" class="form-select form-select-sm">
                                                <option value="all" {{ $claimStatus === 'all' ? 'selected' : '' }}>الكل</option>
                                                <option value="not_sent" {{ $claimStatus === 'not_sent' ? 'selected' : '' }}>لم يتم الإرسال</option>
                                                <option value="sent" {{ $claimStatus === 'sent' ? 'selected' : '' }}>تم الإرسال</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small text-muted mb-1">من تاريخ</label>
                                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small text-muted mb-1">إلى تاريخ</label>
                                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted mb-1">الشركة</label>
                                            <select name="company_id" class="form-select form-select-sm">
                                                <option value="">الكل</option>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="ti ti-search me-1"></i> بحث
                                            </button>
                                            <a href="{{ route('property-management.electricity-meter-claims.index') }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="ti ti-refresh me-1"></i> إعادة تعيين
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>رقم العقد</th>
                                        <th>اسم الشركة المؤجرة</th>
                                        <th class="text-center">مكتب رقم كام</th>
                                        <th class="text-center">تم الإرسال / لم يتم</th>
                                        <th class="text-center" style="width: 140px;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contracts as $contract)
                                        <tr data-contract-id="{{ $contract->id }}">
                                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="text-decoration-none fw-medium">
                                                    {{ $contract->contract_number }}
                                                </a>
                                            </td>
                                            <td>{{ $contract->client->name ?? '—' }}</td>
                                            <td class="text-center">{{ $contract->unit->unit_number ?? '—' }}</td>
                                            <td class="text-center">
                                                @if($contract->electricity_meter_claim_sent_at)
                                                    <span class="badge bg-success">تم الإرسال</span>
                                                    <div class="small text-muted mt-1">{{ $contract->electricity_meter_claim_sent_at->format('Y-m-d H:i') }}</div>
                                                @else
                                                    <span class="badge bg-secondary">لم يتم الإرسال</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                    <a href="{{ route('property-management.contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-primary" title="عرض العقد">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    @if(auth()->user()->role !== 'viewer')
                                                        @if($contract->client && $contract->client->email)
                                                            <a href="{{ route('property-management.electricity-meter-claims.preview', $contract) }}" class="btn btn-sm btn-outline-dark" title="معاينة وإرسال المطالبة">
                                                                <i class="ti ti-mail"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('property-management.electricity-meter-claims.preview', $contract) }}" class="btn btn-sm btn-outline-secondary" title="معاينة المطالبة (لا يوجد بريد للإرسال)">
                                                                <i class="ti ti-mail"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="ti ti-file-off opacity-50" style="font-size: 2rem;"></i>
                                                <p class="mt-2 mb-0">لا توجد عقود تطابق البحث</p>
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
