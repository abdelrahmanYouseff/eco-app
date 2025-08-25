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

<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 ms-auto">
            <h2 class="mb-4">User List</h2>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Badge ID</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            @if($user->role === 'building_admin')
                                <span class="badge bg-primary">Building Admin</span>
                            @elseif($user->role === 'company_admin')
                                <span class="badge bg-success">Company Admin</span>
                            @elseif($user->role === 'employee')
                                <span class="badge bg-info">Employee</span>
                            @elseif($user->role === 'visitor')
                                <span class="badge bg-warning">Visitor</span>
                            @else
                                <span class="badge bg-secondary">{{ $user->role }}</span>
                            @endif
                        </td>
                        <td>
                            <code class="text-primary">{{ $user->badge_id ?? 'N/A' }}</code>
                        </td>
                        <td>{{ $user->company_name ?? 'N/A' }}</td>
                        <td>
                            @if($user->is_inside)
                                <span class="badge bg-success">
                                    <i class="ti ti-login me-1"></i> Inside
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="ti ti-logout me-1"></i> Outside
                                </span>
                            @endif
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="viewUserDetails({{ $user->id }})" title="View Details">
                                    <i class="ti ti-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info"
                                        onclick="generateQRCode('{{ $user->badge_id }}')" title="Generate QR Code">
                                    <i class="ti ti-qrcode"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                        onclick="copyBadgeId('{{ $user->badge_id }}')" title="Copy Badge ID">
                                    <i class="ti ti-copy"></i>
                                </button>
                                @if(auth()->id() != $user->id)
                                <button type="button" class="btn btn-sm btn-outline-warning"
                                        onclick="changePassword({{ $user->id }}, '{{ $user->name }}')" title="تغيير كلمة المرور">
                                    <i class="ti ti-lock"></i>
                                </button>
                                @endif
                                @if(auth()->id() != $user->id)
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" title="Delete User">
                                    <i class="ti ti-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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

function copyBadgeId(badgeId) {
    if (!badgeId || badgeId === 'N/A') {
        alert('No Badge ID available for this user');
        return;
    }

    navigator.clipboard.writeText(badgeId).then(function() {
        // إظهار رسالة نجاح
        const button = event.target.closest('button');
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
</script>
@endpush
