@extends('master')

@section('content')
<div class="container py-5 mt-5x ">
  <div class="row justify-content-center align-items-center pt-5">
    <div class="col-md-8">
      <h4 class="text-start text-black mb-4">Add New Service</h4>
      @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
          Swal.fire({
            icon: 'success',
            title: "{{ session('success') == 'deleted' ? 'Service Deleted Successfully' : 'Service Added Successfully' }}",
            text: '',
            confirmButtonText: 'OK'
          });
        </script>
      @endif
      <form method="POST" action="{{ route('services.store') }}">        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Service Name</label>
          <input type="text" class="form-control" id="name" name="name" required>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary">Create Service</button>
          </div>
        </div>
      </form>
      <hr class="my-5">
      <h5 class="mb-3">All Services</h5>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Service ID</th>
            <th>Service Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
              <tr>
                <td>{{ $service->id }}</td>
                <td>{{ $service->name }}</td>
                <td>
                  <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');">
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
