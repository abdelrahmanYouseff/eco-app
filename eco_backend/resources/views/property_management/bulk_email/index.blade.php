@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">البريد الإلكتروني</h2>
                        </div>
                        <p class="text-muted mb-0 mt-2">اكتب رسالة واختر الشركات/العملاء المراد إرسالها إليهم</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('send_errors') && count(session('send_errors')))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>تفاصيل الأخطاء:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach(session('send_errors') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('property-management.bulk-email.send') }}" method="POST" id="bulkEmailForm">
                    @csrf
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">محتوى الرسالة</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">موضوع الرسالة <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('subject') is-invalid @enderror"
                                               id="subject"
                                               name="subject"
                                               value="{{ old('subject') }}"
                                               required
                                               placeholder="مثال: إشعار هام للمستأجرين">
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="body" class="form-label">نص الرسالة <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('body') is-invalid @enderror"
                                                  id="body"
                                                  name="body"
                                                  rows="12"
                                                  required
                                                  placeholder="اكتب نص الرسالة هنا...">{{ old('body') }}</textarea>
                                        @error('body')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">ستُرسل الرسالة باسم {{ $companyInfo['name'] ?? 'الشركة' }}</small>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('property-management.email-logs.index') }}" class="btn btn-outline-secondary">
                                            سجل المراسلات
                                        </a>
                                        <button type="submit" class="btn btn-dark" id="sendBtn">
                                            <i class="ti ti-send me-1"></i> إرسال للمحددين
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <h5 class="mb-0">المستلمون</h5>
                                    <span class="badge bg-secondary" id="selectedCount">0 محدد</span>
                                </div>
                                <div class="card-body">
                                    @error('client_ids')
                                        <div class="alert alert-danger py-2">{{ $message }}</div>
                                    @enderror

                                    @if($clients->isEmpty())
                                        <div class="alert alert-info mb-0">
                                            لا يوجد عملاء/شركات مسجلة ببريد إلكتروني.
                                            <a href="{{ route('property-management.tenants.index') }}">أضف من المستأجرون / العملاء</a>
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="clientSearch" placeholder="بحث بالاسم أو البريد...">
                                        </div>

                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <button type="button" class="btn btn-sm btn-outline-dark" id="selectAllBtn">تحديد الكل</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllBtn">إلغاء التحديد</button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectCompaniesBtn">الشركات فقط</button>
                                        </div>

                                        <div class="border rounded" style="max-height: 420px; overflow-y: auto;">
                                            <ul class="list-group list-group-flush" id="clientsList">
                                                @foreach($clients as $client)
                                                    <li class="list-group-item client-row"
                                                        data-name="{{ mb_strtolower($client->name) }}"
                                                        data-email="{{ mb_strtolower($client->email) }}"
                                                        data-type="{{ $client->client_type }}">
                                                        <div class="form-check">
                                                            <input class="form-check-input client-checkbox"
                                                                   type="checkbox"
                                                                   name="client_ids[]"
                                                                   value="{{ $client->id }}"
                                                                   id="client_{{ $client->id }}"
                                                                   {{ in_array($client->id, old('client_ids', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100" for="client_{{ $client->id }}">
                                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                                    <div>
                                                                        <span class="fw-semibold">{{ $client->name }}</span>
                                                                        <div class="text-muted small" dir="ltr">{{ $client->email }}</div>
                                                                    </div>
                                                                    <span class="badge {{ $client->client_type === 'شركة' ? 'bg-primary' : 'bg-light text-dark border' }}">
                                                                        {{ $client->client_type }}
                                                                    </span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <small class="text-muted d-block mt-2">{{ $clients->count() }} عميل/شركة لديها بريد إلكتروني</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.client-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const searchInput = document.getElementById('clientSearch');
    const form = document.getElementById('bulkEmailForm');

    function updateCount() {
        const count = document.querySelectorAll('.client-checkbox:checked').length;
        if (selectedCount) {
            selectedCount.textContent = count + ' محدد';
        }
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateCount));
    updateCount();

    document.getElementById('selectAllBtn')?.addEventListener('click', function () {
        document.querySelectorAll('.client-row:not([style*="display: none"]) .client-checkbox').forEach(cb => {
            cb.checked = true;
        });
        updateCount();
    });

    document.getElementById('deselectAllBtn')?.addEventListener('click', function () {
        checkboxes.forEach(cb => cb.checked = false);
        updateCount();
    });

    document.getElementById('selectCompaniesBtn')?.addEventListener('click', function () {
        checkboxes.forEach(cb => {
            const row = cb.closest('.client-row');
            cb.checked = row && row.dataset.type === 'شركة' && row.style.display !== 'none';
        });
        updateCount();
    });

    searchInput?.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        document.querySelectorAll('.client-row').forEach(row => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            row.style.display = (!q || name.includes(q) || email.includes(q)) ? '' : 'none';
        });
    });

    form?.addEventListener('submit', function (e) {
        const checked = document.querySelectorAll('.client-checkbox:checked').length;
        if (checked === 0) {
            e.preventDefault();
            alert('يجب اختيار شركة أو عميل واحد على الأقل');
            return;
        }
        if (!confirm('إرسال الرسالة إلى ' + checked + ' مستلم؟')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
