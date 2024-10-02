@extends('layouts.admin')
@section('page_title','Short Trip Inflow and Outflow Form')
@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
      <h1>Form Layouts</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Short Trip Inflow and Outflow</a></li>
          <li class="breadcrumb-item active"><a href="/">Add a new short trip inflow/outflow</a></li>
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
              <h5 class="card-title">Short Trip Inflow and Outflow Form</h5>
              <!-- Floating Labels Form -->
              <form class="row g-3 "  action="{{route('short-trip-inflow-and-outflow.store')}}" method="POST">
                @csrf

                {{--  input for transaction_status = "trading"  --}}
                <div class="col-md-5" hidden>
                  <div class="form-floating">
                    <input type="text" class="form-control" id="transaction_status" name="transaction_status"  value="short_trip" >
                    <label for="transaction_status">Date</label>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="date" name="date" placeholder="Date" value="<?php echo date('Y-m-d'); ?>">
                    <label for="date">Date</label>
                  </div>
                </div>
                
                <div class="col-md-2">
                    <fieldset >
                    <div class="row">
                    <div class="col-md3">
                      <div class="form-control" placeholder="Time">
                          <legend class="col-form-label col-sm-5 pt-0">Time</legend>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="time" id="inlineRadio1" value="AM">
                            <label class="form-check-label" for="inlineRadio1">AM</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="time" id="inlineRadio2" value="PM">
                            <label class="form-check-label" for="inlineRadio2">PM</label>
                          </div>
                      </div> 
                     </div>
                    </div>  
                    </fieldset>
                </div>
                
                <div class="col-md-2">
                    <fieldset >
                    <div class="row">
                    <div class="col-md3">
                      <div class="form-control" placeholder="In/Out">
                          <legend class="col-form-label col-sm-5 pt-0">In/Out</legend>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="transaction" id="inlineRadio1" value="inflow">
                            <label class="form-check-label" for="inlineRadio1">In</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="transaction" id="inlineRadio2" value="outflow">
                            <label class="form-check-label" for="inlineRadio2">Out</label>
                          </div>
                      </div> 
                     </div>
                    </div>  
                    </fieldset>
                </div>
                
                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="attendant" name="attendant"  placeholder="Attendant" >
                    <label for="attendant">Attendant</label>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="Plate Number">
                    <label for="plate_number">Plate Number</label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" placeholder="Vehicle Type">
                    <label for="vehicle_type">Vehicle Type</label>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name(optional)">
                    <label for="name">Name(optional)</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="commodity" name="commodity" placeholder="Commodity">
                    <label for="commodity">Commodity</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="volume" name="volume" placeholder="Volume(kg)">
                    <label for="volume">Volume(kg)</label>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="barangay" name="barangay" placeholder="Barangay">
                    <label for="barangay">Barangay</label>
                  </div>
                </div>

                <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="municipality" name="municipality" placeholder="Municipality">
                      <label for="municipality">Municipality</label>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="province" name="province" placeholder="Province">
                      <label for="province">Province</label>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="region" name="region" placeholder="Region">
                      <label for="region">Region</label>
                    </div>
                </div>

                <div class="text-center">
                  <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                  <a href="{{route('short-trip-inflow-and-outflow.index')}}" class="btn btn-danger">Back</a>
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
    {{-- End Form --}}
    

 
    </section>
 
@endsection