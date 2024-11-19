@extends('layouts.login-and-register')

@section('page_title', 'Recover Account')

@section('content')
  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <div class="d-flex justify-content-center py-4">
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                <img src="{{asset('assets/img/BAPTC_logo.png')}}" alt="BAPTC">
                <span class="d-none d-lg-block">BAPTC</span>
              </a>
            </div><!-- End Logo -->
  
            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Recover Your Account</h5>
                  <p class="text-center small">Enter your email address to receive a temporary password</p>
                </div>

                <!-- Password Reset Request Form -->
                <form action="{{ route('password.email') }}" method="POST" class="row g-3 needs-validation">
                  @csrf
                  @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>Error!</strong>
                      <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  @endif

                  <div class="col-md-12">
                    <div class="form-floating">
                      <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email address" required>
                      <label for="email">Email Address</label>
                    </div>
                    @error('email')
                      <span class="text-danger md-3">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Send Temporary Password</button>
                  </div>
                </form>

                <!-- Go Back to Login -->
                <div class="mt-3 text-start">
                    <a href="{{ route('login') }}" class="small text-end" style="color: #012970;"><u>Back to login</u></a>
                </div>

                {{-- Submission status Modals --}}
                @if(session('status'))
                  <!-- Success Modal -->
                  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-body text-center">
                          <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                          <p class="mt-3">{{ session('status') }}</p>
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
                  document.addEventListener('DOMContentLoaded', function () {
                    // Show success modal if there's a success message
                    @if(session('status'))
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
      </div>
    </section>
  </div>
@endsection
