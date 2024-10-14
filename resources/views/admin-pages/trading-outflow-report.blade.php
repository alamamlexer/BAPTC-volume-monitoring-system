@extends('layouts.admin')
@section('page_title','Trading Outflow')
@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
      <h1>Trading Outflow</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="/">Trading Outflow</a></li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->
    
    <section class="section">
        
        {{-- Graph --}} 
        <div class="row mb-4">
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-body">
                      <h5 class="card-title">Trading Outflow Chart</h5>
  
                      <form method="GET" action="{{ route('trading-outflow.index') }}" class="mb-3">
                          <div class="input-group">
                              <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $startDate) }}">
                              <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $endDate) }}">
                              <button class="btn btn-primary" type="submit">Filter</button>
                              <a href="{{ route('trading-outflow.index') }}" class="btn btn-secondary ms-2">Reset</a>
                          </div>
                      </form>
  
                      <div id="areaChart"></div>
  
                      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                      <script>
                          document.addEventListener("DOMContentLoaded", () => {
                              const totalVolumeData = @json($totalVolumeData);
                              const series = @json($chartData);
  
                              // Add Total Volume as the first series
                              const combinedSeries = [{
                                  name: 'Total Volume',
                                  data: totalVolumeData
                              }, ...series];
  
                              const dates = @json($dates);
  
                              // Initialize Trading Inflow Chart
                              const outflowChart = new ApexCharts(document.querySelector("#areaChart"), {
                                  series: combinedSeries,
                                  chart: {
                                      type: 'line',
                                      height: 350,
                                      zoom: { enabled: false }
                                  },
                                  dataLabels: { enabled: false },
                                  stroke: { curve: 'straight' },
                                  subtitle: { text: 'Volume of Trading Inflows by Commodity', align: 'left' },
                                  labels: dates,
                                  xaxis: { type: 'datetime' },
                                  yaxis: { opposite: true },
                                  legend: { horizontalAlign: 'left' }
                              });
  
                              outflowChart.render();
                          });
                      </script>
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
                  <h5 class="card-title">Default Table</h5>
    
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
                      @foreach ($trading_outflows as $trading_outflow)
                      <tr>
                      <td>{{$trading_outflow->id}}</td>
                      <td>{{$trading_outflow->date}}</td>
                      <td>{{$trading_outflow->time}}</td>
                      <td>{{$trading_outflow->staff->staff_name }}</td>
                      <td>{{$trading_outflow->plate_number}}</td>
                      <td>{{$trading_outflow->vehicle_type->vehicle_type_name }}</td>
                      <td>{{$trading_outflow->name}}</td>
                      <td>{{$trading_outflow->commodity->commodity_name}}</td>
                      <td>{{$trading_outflow->volume}}</td>
                      <td>{{$trading_outflow->barangay}}, {{$trading_outflow->municipality}}, {{$trading_outflow->province}}, {{$trading_outflow->region}}</td>
                      <td>
                              <a href="{{ route('trading-outflow.edit', $trading_outflow->id) }}" class="btn btn-outline-primary m-1">
                                  <i class="bx bxs-edit"></i> Edit
                              </a>
                                <div class="float-start">
                                    <form action="{{route('trading-outflow.destroy',$trading_outflow->id)}}" method="POST">
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
                  <div class="row mb-3">
                  <div class="col-sm-10">
                      <a href="{{route('trading-outflow.create')}}" class="btn btn-primary">Add New Trading Outflow</a>
                  </div>
                  </div>
                </div>
                  <!-- End Default Table Example -->
                </div>
              </div>
        </div>
    </div> 
    {{--  End Table  --}}

    {{--  Submition Status Modal  --}}
      @if(session('success'))
      {{--  Success Modal --}}
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
      {{--   Error Modal  --}}
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
    {{-- End Submition Status Modal --}}
    
    </section>
 
@endsection