@extends('layouts.admin')
@section('page_title','Users')
@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
      <h1>Users</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="/">User Management</a></li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->

    <section class="section">
        
        
<section class="section">
  <div class="row">
      <div class="col-lg-12">
          <div class="card">
              <div class="card-body">
              <table class="table">
  <thead>
      <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Contact Number</th>
          <th>Email</th>
          <th>Status</th>
          <th>Actions</th>
      </tr>
  </thead>
  <tbody>
      @foreach ($users as $user)
          @if($user->staff_id != null)
              <tr>
                  <td>{{ $user->id }}</td>
                  <td>{{ $user->staffs->staff_name }}</td>
                  <td>{{ $user->staffs->contact_number }}</td>
                  <td>{{ $user->staffs->email }}</td>
                  <td>
                      {{ $user->is_active ? 'Active' : 'Inactive' }}
                  </td>
                  <td>
                      @if($user->is_active)
                          <form action="{{ route('user-management.deactivate', $user->id) }}" method="POST">
                              @csrf
                              <button type="submit" class="btn btn-outline-warning">Deactivate</button>
                          </form>
                      @else
                          <form action="{{ route('user-management.activate', $user->id) }}" method="POST">
                              @csrf
                              <button type="submit" class="btn btn-outline-success">Activate</button>
                          </form>
                      @endif
                  </td>
              </tr>
          @endif
      @endforeach
  </tbody>
</table>


                  <div class="row mb-3">
                      <div class="col-sm-10">
                          <a href="{{ route('user-management.create') }}" class="btn btn-primary">Add new Trading Inspector/Assistant</a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  
  @if(session('success'))
  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
          <p class="mt-3">{{ session('success') }}</p>
        </div>
      </div>
    </div>
  </div>
  @endif

  @if(session('error'))
  <!-- Error Modal -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
          <p class="mt-3">{{ session('error') }}</p>
        </div>
      </div>
    </div>
  </div>
  @endif

  <script>
  document.addEventListener('DOMContentLoaded', function() {
  // Check if there's a success message in the session
  @if(session('success'))
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
    setTimeout(function() {
      successModal.hide();
    }, 1000); // 1 second timeout
  @endif

  // Check if there's an error message in the session
  @if(session('error'))
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
    setTimeout(function() {
      errorModal.hide();
    }, 1000); // 1 second timeout
  @endif
  });
  </script>
</section>
@endsection
