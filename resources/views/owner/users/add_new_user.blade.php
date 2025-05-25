@extends('master')

@section('content')
<div class="container py-5 mt-5x ">
  <div class="row justify-content-center align-items-center pt-5">
    <div class="col-md-8">
      <h4 class="text-start text-black mb-4">Add New User</h4>
      @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
          Swal.fire({
            icon: 'success',
            title: 'User Added Successfully',
            text: "{{ session('success') }}",
            confirmButtonText: 'حسناً'
          });
        </script>
      @endif
      <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="phone_number" class="form-label">Phone Number</label>
          <input type="text" class="form-control" id="phone_number" name="phone_number" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
          <label for="role" class="form-label">Role</label>
          <select class="form-select" id="role" name="role" required>
            <option value="building_admin">Building Admin</option>
            <option value="company_admin">Company Admin</option>
            <option value="employee">Employee</option>
            <option value="visitor">Visitor</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="company_id" class="form-label">Company</label>
          <select class="form-select" id="company_id" name="company_id">
            <option value="" disabled selected>Please Select the Company</option>
            @foreach($companies as $company)
              <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Add User</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
