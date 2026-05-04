@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">معاينة مطالبة عداد الكهرباء</h2>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.9rem;">راجع النص ثم أرسل بالبريد الإلكتروني</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <a href="{{ route('property-management.electricity-meter-claims.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-arrow-right me-1"></i> العودة للقائمة
                        </a>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @if($contract->client && $contract->client->email)
                                <span class="text-muted small">إرسال إلى: <strong dir="ltr">{{ $contract->client->email }}</strong></span>
                            @endif
                            @if(auth()->user()->role !== 'viewer')
                                @if($contract->client && $contract->client->email)
                                    <button type="button" id="btn-send-electricity-claim" class="btn btn-primary btn-sm">
                                        <i class="ti ti-mail me-1"></i> إرسال بالبريد الإلكتروني
                                    </button>
                                @else
                                    <span class="badge bg-warning text-dark">لا يوجد بريد إلكتروني للمستأجر</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-4" style="font-family:Tahoma,Arial,sans-serif;font-size:16px;line-height:1.9;color:#111;direction:rtl;text-align:right;background:#fff;">
                        @include('emails.partials.electricity_meter_claim_letter_inner')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if(auth()->user()->role !== 'viewer' && $contract->client && $contract->client->email)
@push('scripts')
<script>
(function () {
    const btn = document.getElementById('btn-send-electricity-claim');
    if (!btn) return;
    const url = @json(route('property-management.electricity-meter-claims.send-email', $contract));
    const csrf = @json(csrf_token());
    const indexUrl = @json(route('property-management.electricity-meter-claims.index'));
    btn.addEventListener('click', function () {
        if (!confirm('تأكيد إرسال المطالبة إلى البريد المسجل للمستأجر؟')) return;
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> جاري الإرسال...';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, body: j }; }); })
        .then(function (res) {
            if (res.body && res.body.success) {
                alert(res.body.message || 'تم الإرسال');
                window.location.href = indexUrl;
            } else {
                alert((res.body && res.body.message) || 'حدث خطأ');
                btn.disabled = false;
                btn.innerHTML = original;
            }
        })
        .catch(function () {
            alert('تعذر الاتصال بالخادم');
            btn.disabled = false;
            btn.innerHTML = original;
        });
    });
})();
</script>
@endpush
@endif
