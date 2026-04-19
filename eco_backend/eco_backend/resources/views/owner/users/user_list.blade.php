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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Company</th>
                        <th>Created At</th>
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
                                Building Admin
                            @elseif($user->role === 'company_admin')
                                Company Admin
                            @elseif($user->role === 'employee')
                                Employee
                            @elseif($user->role === 'visitor')
                                Visitor
                            @else
                                {{ $user->role }}
                            @endif
                        </td>
                        <td>{{ $user->company_name ?? 'N/A' }}</td>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
