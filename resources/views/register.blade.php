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
  
                <form class="row g-3 needs-validation" action="{{route('register_save')}}" method="POST" novalidate>
                  @csrf
                  <form action="{{route('register_save')}}" method="POST">
                    @csrf
                      <div class="col-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Eg. Lebron James" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger md-3">{{$message}}</span>
                        @enderror
                      </div>
                      <div class="col-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" value="{{ old('password') }}">
                            @error('password')
                                <span class="text-danger md-3">{{$message}}</span>
                            @enderror
                      </div>
                      <div class="col-12">
                          <label for="password_confirmation" class="form-label">Password</label>
                          <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Enter your password" value="{{ old('password_confirmation') }}">
                              @error('password_confirmation')
                                <span class="text-danger md-3">{{$message}}</span>
                              @enderror
                        </div>
                      <div class="col-12">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" value="{{ old('email') }}">
                              @error('email')
                                <span class="text-danger md-3">{{$message}}</span>
                              @enderror
                      </div>
                      <div class="col-12">
                          <label for="contact_number" class="form-label">Contact Number</label>
                          <input type="text" class="form-control" name="contact_number" id="contact_number" placeholder="Enter your contact number" value="{{ old('contact_number') }}">
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
                </form>
  
              </div>
            </div>
          </div>
        </div>
      </div>
  
    </section>
@endsection