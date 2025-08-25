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

<div class="container py-5 mt-5x ">
  <div class="row justify-content-center align-items-center pt-5">
    <div class="col-md-8">
      <h4 class="text-start text-black mb-4">Add New Company</h4>
      <form action="{{ route('companies.store') }}" method="POST">        @csrf

        <div class="mb-3">
          <label for="name" class="form-label">Company Name</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Enter company name" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Company Email</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter company email" required>
        </div>

        <div class="mb-3">
          <label for="phone" class="form-label">Company Phone</label>
          <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter company phone" required>
        </div>

        <div class="mb-3">
          <label for="floor_number" class="form-label">Floor Number</label>
          <input type="text" name="floor_number" id="floor_number" class="form-control" placeholder="Enter floor number" required>
        </div>

        <div class="mb-3">
          <label for="office_number" class="form-label">Office Number</label>
          <input type="text" name="office_number" id="office_number" class="form-control" placeholder="Enter office number" required>
        </div>

        <div class="mb-3">
          <label for="admin_user_id" class="form-label">Admin</label>
          <!-- Debug: Show users count -->
          <p class="text-muted">Debug: Found {{ $users->count() }} building_admin users</p>
          <select name="admin_user_id" id="admin_user_id" class="form-select" required>
            <option value="">Choose</option>
            @foreach($users as $user)
              <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
          </select>
          <!-- Debug: Show all users -->
          <div class="mt-2">
            <small class="text-muted">Available users:</small>
            @foreach($users as $user)
              <br><small>{{ $user->name }} ({{ $user->email }}) - Role: {{ $user->role }}</small>
            @endforeach
          </div>
        </div>

        <div class="mb-3">
          <label for="building_id" class="form-label">Building</label>
          <select name="building_id" id="building_id" class="form-select" required>
            <option value="">Choose Building</option>
            @foreach($buildings as $building)
              <option value="{{ $building->id }}">{{ $building->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Add Company</button>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection
