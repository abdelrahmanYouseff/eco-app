@extends('master')
@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 ms-auto">
            <h2 class="mb-4">User List</h2>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Company Name</th>
                        <th>Requested By</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceRequest as $serviceRequests)
                    <tr>
                        <td>{{ $serviceRequests->id }}</td>
                        <td>{{ $serviceRequests->company_id }}</td>
                        <td>{{ $serviceRequests->requested_by }}</td>
                        <td>{{ $serviceRequests->category_id }}</td>
                        <td>{{ $serviceRequests->description }}</td>
                        <td>{{ $serviceRequests->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
