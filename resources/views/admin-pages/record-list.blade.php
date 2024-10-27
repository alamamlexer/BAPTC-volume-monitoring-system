@extends('layouts.admin')
@section('page_title', 'Records')
@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
        <h1>Records</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="/">Records</a></li>
            </ol>
        </nav>
    </div>


<section class="section dashboard">

    <div class="row">
    
        <div class="col-md-12">
        <div class="card">
        <div class="card-body">
           <h5 class="card-title">Locations</h5> 
          <div class="table-responsive">
           <table class="table datatable-location">
          <thead>
            <tr>
                <th class="text-start">No.</th>
                <th class="text-start">Name</th>
                <th class="text-start">Action</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($locations as $location)
              <tr>
                <td class="text-start">{{$loop->iteration}}</td>
                <td class="text-start">{{$location->barangay}}, {{$location->municipality}}, {{$location->province}}, {{$location->region}}</th>
                    <td>
                        <a href="" class="btn btn-outline-primary m-1">
                            <i class="bx bxs-edit"></i> Edit
                        </a>
                    </td>
            </tr>
          @endforeach
          </tbody>
          </table>  
          </div>
        </div>
        </div>
        </div>










        <!-- Market Facilitator -->
        <div class="col-md-12">
            <div class="card">
            <div class="card-body">
               <h5 class="card-title">Market Facilitator</h5> 
              <div class="table-responsive">
               <table class="table datatable-facilitator">
              <thead>
                <tr>
                    <th class="text-start">No.</th>
                    <th class="text-start">Code</th>
                    <th class="text-start">Name</th>
                    <th class="text-start">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($facilitators as $facilitator)
                <tr>
                  <td class="text-start">{{$loop->iteration}}</td>
                  <td class="text-start">{{$facilitator->facilitator_code}}</td>
                  <td class="text-start">{{$facilitator->facilitator_name}}</th>
                    <td>
                        <a href="" class="btn btn-outline-primary m-1">
                            <i class="bx bxs-edit"></i> Edit
                        </a>
                    </td>
              </tr>
            @endforeach
              </tbody>
              </table>  
              </div>
            </div>
            </div>
            </div>
            

            <!-- Commodity -->
            <div class="col-md-12">
                <div class="card">
                <div class="card-body">
                   <h5 class="card-title">Commodity</h5> 
                  <div class="table-responsive">
                   <table class="table datatable-commodity">
                  <thead>
                    <tr>
                        <th class="text-start">No.</th>
                        <th class="text-start">Name</th>
                        <th class="text-start">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($commodities as $commodity)
                    <tr>
                      <td class="text-start">{{$loop->iteration}}</td>
                      <td class="text-start">{{$commodity->commodity_name}}</td>
                      <td>
                        <a href="" class="btn btn-outline-primary m-1">
                            <i class="bx bxs-edit"></i> Edit
                        </a>
                    </td>
                  </tr>
                @endforeach
                  </tbody>
                  </table>  
                  </div>
                </div>
                </div>
                
                </div>
    </div>
    <script>
   $(document).ready(function () {
    // Initialize DataTables for each specific table
    const datatables = ['.datatable-location', '.datatable-facilitator', '.datatable-commodity'];

    datatables.forEach(function (tableClass) {
        if (!$.fn.DataTable.isDataTable(tableClass)) {
            $(tableClass).DataTable({
                paging: false,          // Enable pagination
                searching: true,       // Enable search
                ordering: true,        // Enable column ordering
                info: true,            // Show table info
                autoWidth: false,      // Disable auto column width
                language: {
                    search: "Search:",  // Customize the search input placeholder
                },
            });
        }
    });
});
    </script>
  
</section>
  



@endsection
