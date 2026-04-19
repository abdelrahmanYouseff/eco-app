@extends('master')
@section('content')

@if(session('success'))
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: "{{ session('success') }}",
      confirmButtonText: 'OK'
    });
  </script>
@endif

@if(session('error'))
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: "{{ session('error') }}",
      confirmButtonText: 'OK'
    });
  </script>
@endif

@php
  $totalUsers = $users->count();
  $insideCount = $users->where('is_inside', true)->count();
  $outsideCount = $totalUsers - $insideCount;
@endphp

<style>
  .users-search .form-control:focus { box-shadow: none; }
  .users-table thead th { white-space: nowrap; }
  .users-table td { vertical-align: middle; }
  .users-badge code { font-size: .85rem; }
  .stat-card { border: 1px solid rgba(0,0,0,.06); }
  .stat-card .stat-label { font-size: .85rem; color: #6c757d; }
  .stat-card .stat-value { font-size: 1.25rem; font-weight: 700; }
</style>

<div class="pc-container">
  <div class="pc-content">
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col">
            <div class="page-header-title">
              <h5 class="m-b-10">المستخدمون</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('building.owner.dashboard') }}">لوحة التحكم</a></li>
              <li class="breadcrumb-item" aria-current="page">قائمة المستخدمين</li>
            </ul>
          </div>
          <div class="col-auto d-flex gap-2">
            <a href="{{ route('user.add') }}" class="btn btn-primary">
              <i class="ti ti-user-plus me-1"></i> إضافة مستخدم
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <div class="card mb-0 stat-card">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="stat-label">إجمالي المستخدمين</div>
              <div class="stat-value">{{ $totalUsers }}</div>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
              <i class="ti ti-users me-1"></i> Users
            </span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-0 stat-card">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="stat-label">داخل</div>
              <div class="stat-value">{{ $insideCount }}</div>
            </div>
            <span class="badge bg-success-subtle text-success border border-success-subtle">
              <i class="ti ti-login me-1"></i> Inside
            </span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-0 stat-card">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <div class="stat-label">خارج</div>
              <div class="stat-value">{{ $outsideCount }}</div>
            </div>
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
              <i class="ti ti-logout me-1"></i> Outside
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="row align-items-center g-2">
          <div class="col">
            <h5 class="mb-0">قائمة المستخدمين</h5>
            <small class="text-muted">ابحث بالاسم أو الإيميل أو رقم الجوال أو الشركة</small>
          </div>
          <div class="col-12 col-md-5 users-search">
            <div class="input-group">
              <span class="input-group-text bg-transparent"><i class="ti ti-search"></i></span>
              <input id="users-search-input" type="text" class="form-control" placeholder="بحث..." autocomplete="off">
              <button id="users-search-clear" class="btn btn-outline-secondary" type="button">مسح</button>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 users-table">
            <thead class="table-light">
              <tr>
                <th style="width:80px;">ID</th>
                <th>المستخدم</th>
                <th class="d-none d-lg-table-cell">Email</th>
                <th class="d-none d-xl-table-cell">Phone</th>
                <th>الدور</th>
                <th>Badge</th>
                <th>الشركة</th>
                <th>الحالة</th>
                <th>تاريخ الإنشاء</th>
                <th class="text-end" style="width:90px;">إجراءات</th>
              </tr>
            </thead>
            <tbody id="users-tbody">
              @foreach($users as $user)
                <tr data-search="{{ strtolower(($user->name ?? '').' '.($user->email ?? '').' '.($user->phone ?? '').' '.($user->company_name ?? '')) }}">
                  <td class="text-muted">#{{ $user->id }}</td>
                  <td>
                    <span class="fw-semibold">{{ $user->name }}</span>
                  </td>
                  <td class="d-none d-lg-table-cell text-muted">{{ $user->email }}</td>
                  <td class="d-none d-xl-table-cell text-muted">{{ $user->phone }}</td>
                  <td>
                    @if($user->role === 'building_admin')
                      <span class="badge bg-primary">Building Admin</span>
                    @elseif($user->role === 'company_admin')
                      <span class="badge bg-success">Company Admin</span>
                    @elseif($user->role === 'employee')
                      <span class="badge bg-info">Employee</span>
                    @elseif($user->role === 'visitor')
                      <span class="badge bg-warning text-dark">Visitor</span>
                    @elseif($user->role === 'accountant')
                      <span class="badge bg-danger">Accountant</span>
                    @elseif($user->role === 'editor')
                      <span class="badge bg-dark">Editor</span>
                    @elseif($user->role === 'viewer')
                      <span class="badge bg-secondary">Viewer</span>
                    @else
                      <span class="badge bg-secondary">{{ $user->role }}</span>
                    @endif
                  </td>
                  <td class="users-badge">
                    <div class="d-flex align-items-center gap-2">
                      <code class="text-primary">{{ $user->badge_id ?? 'N/A' }}</code>
                      <button type="button" class="btn btn-sm btn-outline-secondary"
                              onclick="copyBadgeId(event, '{{ $user->badge_id }}')" title="نسخ">
                        <i class="ti ti-copy"></i>
                      </button>
                    </div>
                  </td>
                  <td>{{ $user->company_name ?? 'N/A' }}</td>
                  <td>
                    @if($user->is_inside)
                      <span class="badge bg-success"><i class="ti ti-login me-1"></i> داخل</span>
                    @else
                      <span class="badge bg-danger"><i class="ti ti-logout me-1"></i> خارج</span>
                    @endif
                  </td>
                  <td class="text-muted">{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                  <td class="text-end">
                    <div class="dropdown">
                      <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-dots"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                          <button class="dropdown-item" type="button" onclick="viewUserDetails({{ $user->id }})">
                            <i class="ti ti-eye me-2"></i> عرض
                          </button>
                        </li>
                        <li>
                          <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                            <i class="ti ti-edit me-2"></i> تعديل
                          </a>
                        </li>
                        <li>
                          <button class="dropdown-item" type="button" onclick="generateQRCode('{{ $user->badge_id }}')">
                            <i class="ti ti-qrcode me-2"></i> QR
                          </button>
                        </li>
                        @if(auth()->id() != $user->id)
                          <li><hr class="dropdown-divider"></li>
                          <li>
                            <button class="dropdown-item text-warning" type="button" onclick="changePassword({{ $user->id }}, '{{ $user->name }}')">
                              <i class="ti ti-lock me-2"></i> تغيير كلمة المرور
                            </button>
                          </li>
                          <li>
                            <button class="dropdown-item text-danger" type="button" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                              <i class="ti ti-trash me-2"></i> حذف
                            </button>
                          </li>
                        @endif
                      </ul>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel">QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrcode"></div>
                <p class="mt-3">
                    <code id="badgeIdDisplay"></code>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="downloadQRCode()">Download</button>
            </div>
        </div>
    </div>
</div>

<!-- Password Change Modal -->
<div class="modal fade" id="passwordChangeModal" tabindex="-1" aria-labelledby="passwordChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordChangeModalLabel">
                    <i class="ti ti-lock me-2"></i>تغيير كلمة المرور
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="passwordChangeForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="userName" class="form-label">اسم المستخدم</label>
                        <input type="text" class="form-control" id="userName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                        <div class="form-text">يجب أن تكون كلمة المرور 6 أحرف على الأقل</div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-lock me-1"></i>تغيير كلمة المرور
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
function viewUserDetails(userId) {
    // يمكن إضافة تفاصيل أكثر للمستخدم هنا
    alert('User details for ID: ' + userId);
}

function generateQRCode(badgeId) {
    if (!badgeId || badgeId === 'N/A') {
        alert('No Badge ID available for this user');
        return;
    }

    document.getElementById('badgeIdDisplay').textContent = badgeId;

    // إنشاء QR Code
    QRCode.toCanvas(document.getElementById('qrcode'), badgeId, {
        width: 200,
        margin: 2,
        color: {
            dark: '#000000',
            light: '#FFFFFF'
        }
    }, function (error) {
        if (error) console.error(error);
    });

    // عرض Modal
    new bootstrap.Modal(document.getElementById('qrCodeModal')).show();
}

function downloadQRCode() {
    const canvas = document.querySelector('#qrcode canvas');
    const link = document.createElement('a');
    link.download = 'qr-code.png';
    link.href = canvas.toDataURL();
    link.click();
}

function copyBadgeId(evt, badgeId) {
    if (!badgeId || badgeId === 'N/A') {
        alert('No Badge ID available for this user');
        return;
    }

    navigator.clipboard.writeText(badgeId).then(function() {
        // إظهار رسالة نجاح
        const button = evt && evt.target ? evt.target.closest('button') : null;
        if (!button) return;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="ti ti-check"></i>';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');

        setTimeout(function() {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy Badge ID');
    });
}

function deleteUser(userId, userName) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete user "${userName}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // إنشاء form للحذف
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/users/${userId}`;

            // إضافة CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // إضافة method override
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // إضافة form للصفحة وتشغيله
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function changePassword(userId, userName) {
    // تعيين اسم المستخدم في Modal
    document.getElementById('userName').value = userName;

    // تعيين action للform
    document.getElementById('passwordChangeForm').action = `/users/${userId}/change-password`;

    // عرض Modal
    new bootstrap.Modal(document.getElementById('passwordChangeModal')).show();
}

// معالجة تقديم form تغيير كلمة المرور
document.getElementById('passwordChangeForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;

    if (newPassword !== confirmPassword) {
        Swal.fire({
            icon: 'error',
            title: 'خطأ في كلمة المرور',
            text: 'كلمة المرور الجديدة وتأكيد كلمة المرور غير متطابقين.',
            confirmButtonText: 'حسناً'
        });
        return;
    }

    if (newPassword.length < 6) {
        Swal.fire({
            icon: 'error',
            title: 'كلمة المرور قصيرة جداً',
            text: 'يجب أن تكون كلمة المرور 6 أحرف على الأقل.',
            confirmButtonText: 'حسناً'
        });
        return;
    }

    // إرسال form
    this.submit();
});

// Search filter
(function () {
  const input = document.getElementById('users-search-input');
  const clearBtn = document.getElementById('users-search-clear');
  const tbody = document.getElementById('users-tbody');
  if (!input || !clearBtn || !tbody) return;

  function applyFilter() {
    const q = (input.value || '').trim().toLowerCase();
    const rows = tbody.querySelectorAll('tr');
    rows.forEach((tr) => {
      const hay = (tr.getAttribute('data-search') || '').toLowerCase();
      tr.style.display = (!q || hay.includes(q)) ? '' : 'none';
    });
  }

  input.addEventListener('input', applyFilter);
  clearBtn.addEventListener('click', () => {
    input.value = '';
    input.focus();
    applyFilter();
  });
})();
</script>
@endpush
