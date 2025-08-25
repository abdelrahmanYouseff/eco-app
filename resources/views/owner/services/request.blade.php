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

<div class="container-fluid" style="padding-top: 80px;">
    <div class="row">
        <div class="col-md-10 ms-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Maintenance Requests</h4>
                <a href="{{ route('service.view') }}" class="btn btn-success btn-sm">
                    <i class="ti ti-plus me-1"></i>Add New Request
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0">{{ $serviceRequest->where('status', 'pending')->count() }}</h5>
                                    <small>Pending</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="ti ti-clock" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0">{{ $serviceRequest->where('status', 'in_progress')->count() }}</h5>
                                    <small>In Progress</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="ti ti-loader" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0">{{ $serviceRequest->where('status', 'completed')->count() }}</h5>
                                    <small>Completed</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="ti ti-check" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0">{{ $serviceRequest->where('status', 'rejected')->count() }}</h5>
                                    <small>Rejected</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="ti ti-x" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th style="width: 15%">Title</th>
                                    <th style="width: 12%">Company</th>
                                    <th style="width: 12%">Requested By</th>
                                    <th style="width: 25%">Description</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 10%">Created</th>
                                    <th style="width: 11%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($serviceRequest as $request)
                                <tr>
                                    <td class="text-center">{{ $request->id }}</td>
                                    <td>
                                        <strong class="text-primary">{{ $request->title ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $request->company_name ?? 'N/A' }}</td>
                                    <td>
                                        @if($request->requestedBy)
                                            <span class="badge bg-primary">{{ $request->requestedBy->name }}</span>
                                        @else
                                            <span class="text-muted">User ID: {{ $request->requested_by ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;" title="{{ $request->description }}">
                                            {{ Str::limit($request->description, 40) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="ti ti-clock me-1"></i>Pending
                                            </span>
                                        @elseif($request->status === 'in_progress')
                                            <span class="badge bg-info">
                                                <i class="ti ti-loader me-1"></i>In Progress
                                            </span>
                                        @elseif($request->status === 'completed')
                                            <span class="badge bg-success">
                                                <i class="ti ti-check me-1"></i>Completed
                                            </span>
                                        @elseif($request->status === 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="ti ti-x me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ $request->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted">
                                            {{ $request->created_at ? $request->created_at->format('m/d H:i') : 'N/A' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="viewRequestDetails({{ $request->id }})" title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            @if($request->status !== 'completed')
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                    onclick="updateStatus({{ $request->id }}, 'completed')" title="Mark as Completed">
                                                <i class="ti ti-check"></i>
                                            </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteRequest({{ $request->id }})" title="Delete Request">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3">
                                        <div class="text-muted">
                                            <i class="ti ti-tools-off" style="font-size: 2rem;"></i>
                                            <h6 class="mt-2">No Maintenance Requests</h6>
                                            <p class="mb-2">No maintenance requests found in the system.</p>
                                            <a href="{{ route('service.view') }}" class="btn btn-primary btn-sm">
                                                <i class="ti ti-plus me-1"></i>Create First Request
                                            </a>
                                        </div>
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

@endsection

@push('scripts')
<script>
function viewRequestDetails(requestId) {
    // يمكن إضافة تفاصيل أكثر للطلب هنا
    alert('Request details for ID: ' + requestId);
}

function updateStatus(requestId, status) {
    if (confirm('Are you sure you want to update the status of this request?')) {
        // يمكن إضافة AJAX request هنا لتحديث الحالة
        alert('Status updated for request ID: ' + requestId + ' to ' + status);
    }
}

function deleteRequest(requestId) {
    if (confirm('Are you sure you want to delete this request? This action cannot be undone.')) {
        // يمكن إضافة AJAX request هنا لحذف الطلب
        alert('Request deleted: ' + requestId);
    }
}
</script>
@endpush
