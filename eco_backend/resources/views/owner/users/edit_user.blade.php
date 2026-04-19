@extends('master')

@section('content')
<div class="container py-5 mt-5x ">
  <div class="row justify-content-center align-items-center pt-5">
    <div class="col-md-8">
      <h4 class="text-start text-black mb-4">Edit User: {{ $user->name }}</h4>
      @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
          Swal.fire({
            icon: 'success',
            title: 'User Updated Successfully',
            text: "{{ session('success') }}",
            confirmButtonText: 'حسناً'
          });
        </script>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
          <label for="phone_number" class="form-label">Phone Number</label>
          <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone) }}" required>
        </div>
        <div class="mb-3">
          <label for="role" class="form-label">Role</label>
          <select class="form-select" id="role" name="role" required>
            <option value="building_admin" {{ old('role', $user->role) == 'building_admin' ? 'selected' : '' }}>Building Admin</option>
            <option value="company_admin" {{ old('role', $user->role) == 'company_admin' ? 'selected' : '' }}>Company Admin</option>
            <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee</option>
            <option value="visitor" {{ old('role', $user->role) == 'visitor' ? 'selected' : '' }}>Visitor</option>
            <option value="accountant" {{ old('role', $user->role) == 'accountant' ? 'selected' : '' }}>Accountant</option>
            <option value="editor" {{ old('role', $user->role) == 'editor' ? 'selected' : '' }}>Editor</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="company_id" class="form-label">Company</label>
          <select class="form-select" id="company_id" name="company_id">
            <option value="">Please Select the Company</option>
            @foreach($companies as $company)
              <option value="{{ $company->id }}" {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>
                {{ $company->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('user.list') }}" class="btn btn-secondary">Cancel</a>
          <button type="submit" class="btn btn-primary">Update User</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
