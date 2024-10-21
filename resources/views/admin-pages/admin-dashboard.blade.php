@extends('layouts.admin')
@section('page_title','Admin Dashboard')
@section('content')
<section class="section dashboard">
    
    <div class="pagetitle ">
        <h1>Dashboard</h1>
    </div>
    <div class="row">
    
        {{-- Inflow --}}
        <div class="col-md-6 "> 
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title fs-4">Inflow</h5>
                    <div class="row">
                    <div class="col-md-6">
                    <h5 class="card-title">Vehicles </h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ri-car-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{$today_inflow_vehicle}}</h6>
                        </div>
                    </div>
                    </div>
                    
                    <div class="col-md-6">
                    <h5 class="card-title">Volume (kg)</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ri-scales-2-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{$today_inflow_volume}}</h6>
                        </div>
                    </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    
        
        
        {{-- Inflow --}}
        <div class="col-md-6 "> 
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title fs-4">Outflow</h5>
                    <div class="row">
                    <div class="col-md-6">
                    <h5 class="card-title">Vehicles </h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ri-car-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{$today_outflow_vehicle}}</h6>
                        </div>
                    </div>
                    </div>
                    
                    <div class="col-md-6">
                    <h5 class="card-title">Volume (kg)</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ri-scales-2-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{$today_outflow_volume}}</h6>
                        </div>
                    </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    
    
    
    
    <div class="card">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Volume Tally</h5>
          <!-- Bordered Table -->
          <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col-m">In</th>
                <th scope="col">Out</th>
                <th scope="col">23131</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="">1</th>
                <td>Brandon Jacob</td>
                <td>Designer</td>
              </tr>
            </tbody>
          </table>
          <!-- End Bordered Table -->



        </div>
      </div>
    </section>
@endsection