@extends('layouts.index-layout')
@section('page_title','Home')
@section('content')



<section class="section dashboard">

    
    <div class="row">
        <div class="col-md-12">
        <div class="card">
        <div class="card-body">
          <h5 class="card-title fs-4 fw-bold">Transactions</h5> 

          <div class="row">
          <div class="table-responsive col-md-6">
           <div class="">
                <h1 class="card-title">Inflows</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Municipality</th>
                            <th>Number of Cars</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($grouped_inflows->isEmpty())
                            <tr>
                                <td colspan="2" class="text-center">No trading inflows found.</td>
                            </tr>
                        @else
                        @foreach ($grouped_inflows as $inflow)
                            <tr>
                                <td>{{ $inflow['municipality'] }}</td>
                                <td>{{ $inflow['vehicle_count'] }}</td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
          </div>
          <div class="table-responsive col-md-6">
          <div class="">
                <h1 class="card-title">Outflows</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Municipality</th>
                            <th>Number of Cars</th>
                        </tr>
                    </thead>
                    <tbody>
                         @if ($grouped_outflows->isEmpty())
                            <tr>
                                <td colspan="2" class="text-center">No trading outflows found.</td>
                            </tr>
                        @else
                        @foreach ($grouped_outflows as $outflow)
                            <tr>
                                <td>{{ $outflow['municipality'] }}</td>
                                <td>{{ $outflow['vehicle_count'] }}</td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
          </div>
         
            
          </div>
            
         
        </div>
        </div>
        </div>
        </div> 
    
 
    

</section>


@endsection