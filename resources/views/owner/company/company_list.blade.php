@extends('master')
@section('content')
<div class="container mt-4">
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
        <div class="col-md-10 ms-auto">
            <h2 class="mb-4">Company List</h2>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Company Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Floor Number</th>
                        <th>Office Number</th>
                        <th>Admin</th>
                        <th>Building</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->email ?? 'N/A' }}</td>
                        <td>{{ $company->phone ?? 'N/A' }}</td>
                        <td>{{ $company->floor_number ?? 'N/A' }}</td>
                        <td>{{ $company->office_number ?? 'N/A' }}</td>
                        <td>{{ $company->admin ? $company->admin->name : 'N/A' }}</td>
                        <td>{{ $company->building ? $company->building->name : 'N/A' }}</td>
                        <td>{{ $company->created_at ? $company->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">View Details</a>
                            <form action="{{ route('company.destroy', $company->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف الشركة {{ $company->name }}؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
