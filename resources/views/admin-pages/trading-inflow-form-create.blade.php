@extends('layouts.admin')
@section('page_title','Trading Inflow Form')
@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
      <h1>Form Layouts</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Trading Inflow</a></li>
          <li class="breadcrumb-item active"><a href="/">Add a new trading inflow</a></li>
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
              <h5 class="card-title">Trading Inflow Form</h5>
              <!-- Floating Labels Form -->
              <form class="row g-3 "  action="{{route('trading-inflow.store')}}" method="POST">
                @csrf

                {{--  input for transaction_status = "trading"  --}}
                <div class="col-md-5" hidden>
                  <div class="form-floating">
                    <input type="text" class="form-control" id="transaction_status" name="transaction_status"  value="regular" >
                    <label for="transaction_status"></label>
                  </div>
                </div>
                
                <div class="col-md-5" hidden>
                  <div class="form-floating">
                    <input type="text" class="form-control" id="transaction_type" name="transaction_type"  value="trading_inflow" >
                    <label for="transaction_type"></label>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="date" name="date" placeholder="Date" value="<?php echo date('Y-m-d'); ?>" required>
                    <label for="date">Date</label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-control" required>
                      <fieldset>
                          <legend class="col-form-label col-sm-3 pt-0">Time</legend>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="time" id="inlineRadio1" value="AM">
                              <label class="form-check-label" for="inlineRadio1">AM</label>
                          </div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="time" id="inlineRadio2" value="PM">
                              <label class="form-check-label" for="inlineRadio2">PM</label>
                          </div>
                      </fieldset>
                  </div>
              </div>
                
                <div class="col-md-5">
                  <div class="form-floating">
                    <select class="form-select" id="staff_id" name="staff_id" required>
                      @foreach ($staffs as $staff)
                        @if ($staff->staff_id==$logged_in_staff)
                          <option value="{{ $staff->staff_id }}" selected>
                          {{ $staff->staff_name }}
                        </option>
                        @else
                        <option value="{{ $staff->staff_id }}" >
                          {{ $staff->staff_name }}
                        </option>
                        @endif
                      @endforeach
                    </select>
                    <label for="staff_id">Attendant</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <select class="form-select select2" id="commodity_id" name="commodity_id" required>
                      @foreach ($commodities as $commodity)
                        <option value="{{ $commodity->commodity_id }}">
                          {{ $commodity->commodity_name }}
                        </option>
                      @endforeach
                    </select>
                    <label for="commodity">Commodity</label>
                  </div>
                </div>                

                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="volume" name="volume" placeholder="Volume(kg)" required>
                    <label for="volume">Volume(kg)</label>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="Plate Number" required>
                    <label for="plate_number">Plate Number</label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-floating">
                    <select class="form-select" id="vehicle_type_id" name="vehicle_type_id" required>
                      @foreach ($vehicle_types as $vehicle_type)
                        <option value="{{ $vehicle_type->vehicle_type_id }}" >
                          {{ $vehicle_type->vehicle_type_name }}
                        </option>
                      @endforeach
                    </select>
                    <label for="vehicle_type">Vehicle Type</label>
                  </div>
                </div>
                
                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name(optional)">
                    <label for="name">Name(optional)</label>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="barangay" name="barangay" placeholder="Barangay" required>
                    <label for="barangay">Barangay</label>
                  </div>
                </div>

                <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="municipality" name="municipality" placeholder="Municipality" required>
                      <label for="municipality">Municipality</label>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="province" name="province" placeholder="Province" required>
                      <label for="province">Province</label>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="region" name="region" placeholder="Region" required>
                      <label for="region">Region</label>
                    </div>
                </div>

                <div class="text-center">
                  <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                  <a href="{{route('trading-inflow.index')}}" class="btn btn-danger">Back</a>
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
              //Success and Error Message
              
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

              //Auto select for AM and PM

                  const currentHour = new Date().getHours();
                  
                  if (currentHour < 12) {
                    document.getElementById('inlineRadio1').checked = true;
                  } else {
                    document.getElementById('inlineRadio2').checked = true;
                  }
                  
                });
              </script>
              </form>
            </div>
          </div>
        </div>
      </div>
    {{-- End Form --}}

    
    </section>
 
@endsection