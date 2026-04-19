@extends('master')

@section('content')
<div class="container py-5 mt-5x ">
  <div class="row justify-content-center align-items-center pt-5">
    <div class="col-md-8">
      <h4 class="text-start text-black mb-4">Add New Event</h4>
      @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
          Swal.fire({
            icon: 'success',
            title: "{{ session('success') == 'deleted' ? 'Event Deleted Successfully' : 'Event Added Successfully' }}",
            text: '',
            confirmButtonText: 'OK'
          });
        </script>
      @endif
      <form method="POST" action="{{ route('event.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="sub_title" class="form-label">Sub Title</label>
            <input type="text" class="form-control" id="sub_title" name="sub_title">
          </div>
        </div>

        <div class="mb-3">
          <label for="body" class="form-label">Body</label>
          <textarea class="form-control" id="body" name="body" rows="4"></textarea>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="image" class="form-label">Upload Image</label>
            <input type="file" class="form-control" id="image" name="image">
          </div>
          <div class="col-md-6 mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" class="form-control" id="type" name="type" value="news" readonly>
          </div>
        </div>

        <input type="hidden" name="published_by" value="{{ auth()->user()->id }}">

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="requested_by" class="form-label">Published By</label>
            <input type="text" class="form-control" id="requested_by" name="requested_by" value="{{ auth()->user()->name }}" readonly>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label d-block mb-2">Visible To</label>
            <div class="dropdown">
              <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="dropdownVisibleTo" data-bs-toggle="dropdown" aria-expanded="false">
                Select Companies
              </button>
              <ul class="dropdown-menu w-100 px-3" style="max-height: 200px; overflow-y: auto;" aria-labelledby="dropdownVisibleTo">
                @foreach ($companies as $company)
                  <li>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="visible_to[]" value="{{ $company->id }}" id="company_{{ $company->id }}">
                      <label class="form-check-label" for="company_{{ $company->id }}">
                        {{ $company->name }}
                      </label>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>

        <div class="d-grid mt-4">
          <button type="submit" class="btn btn-primary">Create Event</button>
        </div>
      </form>
      <hr class="my-5">
      <h5 class="mb-3">All Events</h5>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Title</th>
            <th>Sub Title</th>
            <th>Body</th>
            <th>Image</th>
            <th>Type</th>
            <th>Visible To</th>
            <th>Published By</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($announcements as $announcement)
              <tr>
                <td>{{ $announcement->title }}</td>
                <td>{{ $announcement->sub_title }}</td>
                <td>{{ $announcement->body }}</td>
                <td>
                  @if ($announcement->image)
                    <img src="{{ asset('storage/' . $announcement->image) }}" width="80">
                  @endif
                </td>
                <td>{{ $announcement->type }}</td>
                <td>{{ $announcement->visible_to }}</td>
                <td>{{ optional($announcement->user)->name }}</td>
                <td>
                  <form method="POST" action="#" onsubmit="return confirm('Are you sure you want to delete this event?');">
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
