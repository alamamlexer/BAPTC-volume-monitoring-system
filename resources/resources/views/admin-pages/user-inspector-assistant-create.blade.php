@extends('layouts.admin')
@section('page_title', 'Users')
@section('content')

<!-- Page Title -->
<div class="pagetitle">
    <h1></h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a >User Management</a></li>
            <li class="breadcrumb-item active"><a >Create an Inspector/Assistant account</a></li>
        </ol>
    </nav>
</div>
<!-- End Page Title -->

<section class="section">

    {{-- Form --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create an Inspector/Assistant account form</h5>
                    <form class="row g-3" action="{{ route('user-management.store') }}" method="POST">
                        @csrf

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="staff_name" name="staff_name" placeholder="Name">
                                <label for="staff_name">Name</label>
                            </div>
                            @error('staff_name')
                            <span class="text-danger md-3">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Contact Number">
                                <label for="contact_number">Contact Number</label>
                            </div>
                            @error('contact_number')
                            <span class="text-danger md-3">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                <label for="email">Email</label>
                            </div>
                            @error('email')
                            <span class="text-danger md-3">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="{{ old('password') }}">
                                <label for="password">Password</label>
                            </div>
                            @error('password')
                            <span class="text-danger md-3">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Re-enter password" value="{{ old('password_confirmation') }}" required>
                                <label for="password_confirmation">Re-enter password</label>
                            </div>
                            @error('password_confirmation')
                            <span class="text-danger md-3">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <a href="{{ route('user-management.index') }}" class="btn btn-danger">Back</a>
                        </div>
                    </form>

                    {{-- Submission status Modal --}}
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

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Show success modal if there's a success message
                            @if(session('success'))
                                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                                successModal.show();
                                setTimeout(function () {
                                    successModal.hide();
                                }, 3000); // Show for 3 seconds
                            @endif

                            // Show error modal if there's an error message
                            @if(session('error'))
                                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                                errorModal.show();
                                setTimeout(function () {
                                    errorModal.hide();
                                }, 3000); // Show for 3 seconds
                            @endif
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
    {{-- End Form --}}

</section>

@endsection