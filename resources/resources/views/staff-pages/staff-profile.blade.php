@extends('layouts.staff')
@section('page_title', 'Edit Staff Profile')

@section('content')

<div class="pagetitle">
    <h1>Edit Staff Profile</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Edit Profile</li>
        </ol>
    </nav>
</div>

{{-- Success Modal --}}
@if(session('success'))
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

{{-- Error Modal --}}
@if(session('error'))
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

{{-- Error Alerts --}}
@if($errors->any())
    <div class="modal fade" id="errorAlertModal" tabindex="-1" aria-labelledby="errorAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                    <p class="mt-3">{{ $errors->first() }}</p>
                </div>
            </div>
        </div>
    </div>
@endif

<section class="section">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Personal Details</h4>
                    <form action="{{ route('staff.profile.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="staff_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="staff_name" name="staff_name" value="{{ old('staff_name', $user->staffs->staff_name) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number', $user->staffs->contact_number) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->staffs->email) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Leave blank to keep current password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            <a href="{{ route('staff.profile', $user->id) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Show modals on page load
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            setTimeout(() => successModal.hide(), 2000);
        @endif

        @if (session('error'))
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
            setTimeout(() => errorModal.hide(), 2000);
        @endif

        @if($errors->any())
            const errorAlertModal = new bootstrap.Modal(document.getElementById('errorAlertModal'));
            errorAlertModal.show();
            setTimeout(() => errorAlertModal.hide(), 2000);
        @endif
    });
</script>

@endsection
