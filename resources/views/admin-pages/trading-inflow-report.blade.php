@extends('layouts.admin')
@section('page_title','Trading Inflow')
@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
      <h1>Trading Inflow</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="/">Trading Inflow</a></li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->
    
    <section class="section">
        
        {{-- Graph --}} 
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Chart</h5>
      
                    <!-- Area Chart -->
                    <div id="areaChart"></div>
      
                    <script>
                      document.addEventListener("DOMContentLoaded", () => {
                        const series = {
                          "monthDataSeries1": {
                            "prices": [
                              8107.85,
                              8128.0,
                              8122.9,
                              8165.5,
                              8340.7,
                              8423.7,
                              8423.5,
                              8514.3,
                              8481.85,
                              8487.7,
                              8506.9,
                              8626.2,
                              8668.95,
                              8602.3,
                              8607.55,
                              8512.9,
                              8496.25,
                              8600.65,
                              8881.1,
                              9340.85
                            ],
                            "dates": [
                              "13 Nov 2017",
                              "14 Nov 2017",
                              "15 Nov 2017",
                              "16 Nov 2017",
                              "17 Nov 2017",
                              "20 Nov 2017",
                              "21 Nov 2017",
                              "22 Nov 2017",
                              "23 Nov 2017",
                              "24 Nov 2017",
                              "27 Nov 2017",
                              "28 Nov 2017",
                              "29 Nov 2017",
                              "30 Nov 2017",
                              "01 Dec 2017",
                              "04 Dec 2017",
                              "05 Dec 2017",
                              "06 Dec 2017",
                              "07 Dec 2017",
                              "08 Dec 2017"
                            ]
                          },
                          "monthDataSeries2": {
                            "prices": [
                              8423.7,
                              8423.5,
                              8514.3,
                              8481.85,
                              8487.7,
                              8506.9,
                              8626.2,
                              8668.95,
                              8602.3,
                              8607.55,
                              8512.9,
                              8496.25,
                              8600.65,
                              8881.1,
                              9040.85,
                              8340.7,
                              8165.5,
                              8122.9,
                              8107.85,
                              8128.0
                            ],
                            "dates": [
                              "13 Nov 2017",
                              "14 Nov 2017",
                              "15 Nov 2017",
                              "16 Nov 2017",
                              "17 Nov 2017",
                              "20 Nov 2017",
                              "21 Nov 2017",
                              "22 Nov 2017",
                              "23 Nov 2017",
                              "24 Nov 2017",
                              "27 Nov 2017",
                              "28 Nov 2017",
                              "29 Nov 2017",
                              "30 Nov 2017",
                              "01 Dec 2017",
                              "04 Dec 2017",
                              "05 Dec 2017",
                              "06 Dec 2017",
                              "07 Dec 2017",
                              "08 Dec 2017"
                            ]
                          },
                          "monthDataSeries3": {
                            "prices": [
                              7114.25,
                              7126.6,
                              7116.95,
                              7203.7,
                              7233.75,
                              7451.0,
                              7381.15,
                              7348.95,
                              7347.75,
                              7311.25,
                              7266.4,
                              7253.25,
                              7215.45,
                              7266.35,
                              7315.25,
                              7237.2,
                              7191.4,
                              7238.95,
                              7222.6,
                              7217.9,
                              7359.3,
                              7371.55,
                              7371.15,
                              7469.2,
                              7429.25,
                              7434.65,
                              7451.1,
                              7475.25,
                              7566.25,
                              7556.8,
                              7525.55,
                              7555.45,
                              7560.9,
                              7490.7,
                              7527.6,
                              7551.9,
                              7514.85,
                              7577.95,
                              7592.3,
                              7621.95,
                              7707.95,
                              7859.1,
                              7815.7,
                              7739.0,
                              7778.7,
                              7839.45,
                              7756.45,
                              7669.2,
                              7580.45,
                              7452.85,
                              7617.25,
                              7701.6,
                              7606.8,
                              7620.05,
                              7513.85,
                              7498.45,
                              7575.45,
                              7601.95,
                              7589.1,
                              7525.85,
                              7569.5,
                              7702.5,
                              7812.7,
                              7803.75,
                              7816.3,
                              7851.15,
                              7912.2,
                              7972.8,
                              8145.0,
                              8161.1,
                              8121.05,
                              8071.25,
                              8088.2,
                              8154.45,
                              8148.3,
                              8122.05,
                              8132.65,
                              8074.55,
                              7952.8,
                              7885.55,
                              7733.9,
                              7897.15,
                              7973.15,
                              7888.5,
                              7842.8,
                              7838.4,
                              7909.85,
                              7892.75,
                              7897.75,
                              7820.05,
                              7904.4,
                              7872.2,
                              7847.5,
                              7849.55,
                              7789.6,
                              7736.35,
                              7819.4,
                              7875.35,
                              7871.8,
                              8076.5,
                              8114.8,
                              8193.55,
                              8217.1,
                              8235.05,
                              8215.3,
                              8216.4,
                              8301.55,
                              8235.25,
                              8229.75,
                              8201.95,
                              8164.95,
                              8107.85,
                              8128.0,
                              8122.9,
                              8165.5,
                              8340.7,
                              8423.7,
                              8423.5,
                              8514.3,
                              8481.85,
                              8487.7,
                              8506.9,
                              8626.2
                            ],
                            "dates": [
                              "02 Jun 2017",
                            ]
                          }
                        }
                        new ApexCharts(document.querySelector("#areaChart"), {
                          series: [{
                            name: "STOCK ABC",
                            data: series.monthDataSeries1.prices
                          }],
                          chart: {
                            type: 'area',
                            height: 350,
                            zoom: {
                              enabled: false
                            }
                          },
                          dataLabels: {
                            enabled: false
                          },
                          stroke: {
                            curve: 'straight'
                          },
                          subtitle: {
                            text: 'Price Movements',
                            align: 'left'
                          },
                          labels: series.monthDataSeries1.dates,
                          xaxis: {
                            type: 'datetime',
                          },
                          yaxis: {
                            opposite: true
                          },
                          legend: {
                            horizontalAlign: 'left'
                          }
                        }).render();
                      });
                    </script>
                    <!-- End Area Chart -->
      
                  </div>
                </div>
            </div>
        </div>
        {{-- End Graph --}}
        
       {{-- Table --}}
       <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Inflow Table</h5>
                    <div class="table-responsive">
                    <!-- Default Table -->
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Date</th>
                          <th scope="col">AM/PM</th>
                          <th scope="col">Attendant</th>
                          <th scope="col">Plate Number</th>
                          <th scope="col">Vehicle Type</th>
                          <th scope="col">Name</th>
                          <th scope="col">Commodity</th>
                          <th scope="col">Volume</th>
                          <th scope="col">Production Origin</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($trading_inflows as $trading_inflow)
                        <tr>
                          <td>{{$trading_inflow->id}}</td>
                          <td>{{$trading_inflow->date}}</td>
                          <td>{{$trading_inflow->time}}</td>
                          <td>{{$trading_inflow->staff->staff_name }}</td>
                          <td>{{$trading_inflow->plate_number}}</td>
                          <td>{{$trading_inflow->vehicle_type->vehicle_type_name }}</td>
                          <td>{{$trading_inflow->name}}</td>
                          <td>{{$trading_inflow->commodity->commodity_name}}</td>
                          <td>{{$trading_inflow->volume}}</td>
                          <td>{{$trading_inflow->barangay}}, {{$trading_inflow->municipality}}, {{$trading_inflow->province}}, {{$trading_inflow->region}}</td>
                          <td>
                            <a class="btn btn-outline-primary m-1" href="{{route('trading-inflow.edit',$trading_inflow->id)}}"><i class="bx bx-revision"></i> Edit</a> 
                            <div class="float-start">
                                <form action="{{route('trading-inflow.destroy',$trading_inflow->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger m-1" onclick="return confirm('Are you sure you want to delete this reservation?')">
                                        <i class="bx bxs-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                        </tr>
                          
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  <div class="row mb-3">
                  <div class="col-sm-10">
                      <a href="{{route('trading-inflow.create')}}" class="btn btn-primary">Add New Trading Inflow</a>
                  </div>
                  </div>
                </div>
                  <!-- End Default Table Example -->
                </div>
              </div>
        </div>
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
    {{-- End Table --}}
    
    </section>
 
@endsection