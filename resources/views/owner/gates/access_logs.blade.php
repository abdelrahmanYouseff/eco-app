@extends('master')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('building.owner.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Gates</a></li>
                            <li class="breadcrumb-item" aria-current="page">Access Logs</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Access Logs</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="accessTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="current-status-tab" data-bs-toggle="tab" data-bs-target="#current-status" type="button" role="tab">
                                    <i class="ti ti-users me-2"></i>Current Status
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="access-logs-tab" data-bs-toggle="tab" data-bs-target="#access-logs" type="button" role="tab">
                                    <i class="ti ti-history me-2"></i>Access History
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="accessTabsContent">
                            <!-- Current Status Tab -->
                            <div class="tab-pane fade show active" id="current-status" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Employee Current Status</h5>
                                    <button class="btn btn-primary btn-sm" onclick="refreshTable()">
                                        <i class="ti ti-refresh"></i> Refresh
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="currentStatusTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Employee Name</th>
                                                <th>Company</th>
                                                <th>Badge ID</th>
                                                <th>Status</th>
                                                <th>Last Activity</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-3">
                                                            <div class="avatar-content bg-primary">
                                                                <i class="ti ti-user"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                                            <small class="text-muted">{{ $user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light-primary text-primary">
                                                        {{ $user->company ? $user->company->name : 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <code>{{ $user->badge_id }}</code>
                                                </td>
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
                                                <td>
                                                    @if($user->is_inside)
                                                        <small class="text-success">
                                                            <i class="ti ti-clock me-1"></i>
                                                            Checked in recently
                                                        </small>
                                                    @else
                                                        <small class="text-muted">
                                                            <i class="ti ti-clock me-1"></i>
                                                            Last seen: {{ $user->updated_at ? $user->updated_at->diffForHumans() : 'N/A' }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                                onclick="viewUserDetails({{ $user->id }})">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                                onclick="generateQRCode('{{ $user->badge_id }}')">
                                                            <i class="ti ti-qrcode"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Access History Tab -->
                            <div class="tab-pane fade" id="access-logs" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Access History</h5>
                                    <button class="btn btn-primary btn-sm" onclick="loadAccessLogs()">
                                        <i class="ti ti-refresh"></i> Refresh
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="accessHistoryTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Employee Name</th>
                                                <th>Action</th>
                                                <th>Location</th>
                                                <th>Scanned By</th>
                                                <th>Date & Time</th>
                                            </tr>
                                        </thead>
                                        <tbody id="accessHistoryBody">
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    <i class="ti ti-loader ti-spin me-2"></i>Loading access history...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
function refreshTable() {
    location.reload();
}

function loadAccessLogs() {
    const tbody = document.getElementById('accessHistoryBody');
    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted"><i class="ti ti-loader ti-spin me-2"></i>Loading...</td></tr>';

    fetch('{{ route("gates.access.logs.api") }}')
        .then(response => response.json())
        .then(data => {
            if (data.status && data.data.length > 0) {
                let html = '';
                data.data.forEach((log, index) => {
                    const actionClass = log.type === 'check_in' ? 'bg-success' : 'bg-danger';
                    const actionIcon = log.type === 'check_in' ? 'ti-login' : 'ti-logout';
                    const actionText = log.type === 'check_in' ? 'Check In' : 'Check Out';

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-content bg-primary">
                                            <i class="ti ti-user"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">${log.user ? log.user.name : 'Unknown'}</h6>
                                        <small class="text-muted">${log.user ? log.user.email : 'N/A'}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge ${actionClass}">
                                    <i class="ti ${actionIcon} me-1"></i> ${actionText}
                                </span>
                            </td>
                            <td>${log.location}</td>
                            <td>
                                <span class="badge bg-light-info text-info">
                                    ${log.scanned_by}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="ti ti-clock me-1"></i>
                                    ${new Date(log.created_at).toLocaleString()}
                                </small>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No access logs found</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading access logs:', error);
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error loading data</td></tr>';
        });
}

function viewUserDetails(userId) {
    // يمكن إضافة تفاصيل أكثر للمستخدم هنا
    alert('User details for ID: ' + userId);
}

function generateQRCode(badgeId) {
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

// تحميل سجلات الدخول عند فتح التبويب
document.addEventListener('DOMContentLoaded', function() {
    // تحميل سجلات الدخول عند فتح التبويب
    const accessLogsTab = document.getElementById('access-logs-tab');
    accessLogsTab.addEventListener('shown.bs.tab', function() {
        loadAccessLogs();
    });
});

// تحديث تلقائي كل 30 ثانية للوضع الحالي
setInterval(function() {
    // يمكن إضافة AJAX call هنا لتحديث البيانات فقط
    // refreshTable();
}, 30000);
</script>
@endpush
