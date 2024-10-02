@extends('layouts.login-and-register')
@section('page_title','Login')
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
                          <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                          <p class="text-center small">Enter your email & password to login</p>
                        </div>
          
                        <form class="row g-3 needs-validation"  action="{{route('login_action')}}" method="POST">
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
                              <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="{{ old('username') }}" required>
                              <label for="username">Username or Plate Number</label>
                              </div>
                              @error('username')
                                  <span class="text-danger md-3">{{$message}}</span>
                              @enderror
                          </div>
          
                          <div class="col-md-12">
                            <div class="form-floating">
                              <input type="text" class="form-control" id="password" name="password" placeholder="Password" value="{{ old('password') }}" required>
                              <label for="password">Password</label>
                              </div>
                              @error('password')
                                  <span class="text-danger md-3">{{$message}}</span>
                              @enderror
                          </div>
          
                          <div class="col-12">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                              <label class="form-check-label" for="rememberMe">Remember me</label>
                            </div>
                          </div>
                          <div class="col-12">
                            <button class="btn btn-primary w-100" type="submit">Login</button>
                          </div>
                          <div class="col-12">
                            <p class="small mb-0">Don't have account? <a href="{{route('register')}}">Create an account</a></p>
                          </div>
                        </form>
          
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
                            
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          
            </section>
          
          </div>
    @endsection