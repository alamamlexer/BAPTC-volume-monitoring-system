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
              <form class="row g-3 "  action="#" method="POST">
                @csrf

                <div class="col-md-5">
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
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                  <a href="{{route('trading-inflow.index')}}" class="btn btn-danger">Back</a>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    {{-- End Form --}}
    

    
    </section>
 
@endsection