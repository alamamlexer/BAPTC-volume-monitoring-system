@extends('layouts.login-and-register')
@section('page_title','Register')
@section('content')
<div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
  
            <div class="d-flex justify-content-center py-4">
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                <img src="assets/img/BAPTC_logo.png" alt="BAPTC">
                <span class="d-none d-lg-block">BAPTC</span>
              </a>
            </div><!-- End Logo -->
  
            <div class="card mb-3">
  
              <div class="card-body">
  
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                  <p class="text-center small">Enter your personal details to create account</p>
                </div>
  
                <form class="row g-3 needs-validation" action="{{route('register_save_farmer')}}" method="POST" novalidate>
                  @csrf

                    
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="farmer_name" name="farmer_name" placeholder="Name" value="{{ old('farmer_name') }}" required>
                        <label for="farmer_name">Name</label>
                        </div>
                        @error('farmer_name')
                            <span class="text-danger md-3">{{$message}}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="{{ old('password') }}" required>
                        <label for="password">Password</label>
                      </div>
                      @error('password')
                            <span class="text-danger md-3">{{$message}}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Re-enter password" value="{{ old('password_confirmation') }}" required>
                        <label for="password_confirmation">Re-enter password</label>
                      </div>
                      @error('password_confirmation')
                            <span class="text-danger md-3">{{$message}}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="Plate number" value="{{ old('plate_number') }}" required>
                        <label for="plate_number">Plate number</label>
                      </div>
                      @error('plate_number')
                            <span class="text-danger md-3">{{$message}}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Contact number" value="{{ old('contact_number') }}" required>
                        <label for="contact_number">Contact number</label>
                      </div>
                      @error('contact_number')
                                <span class="text-danger md-3">{{$message}}</span>
                      @enderror
                    </div>
                  
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Create Account</button>
                  </div>
                  
                  <div class="col-12">
                    <p class="small mb-0">Already have an account? <a href="{{route('login')}}">Log in</a></p>
                  </div>
                  
                  {{--  Submition status Modal  --}}
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
                  
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
  
    </section>
@endsection