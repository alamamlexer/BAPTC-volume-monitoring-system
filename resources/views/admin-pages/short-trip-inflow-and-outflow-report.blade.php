@extends('layouts.admin')
@section('page_title', 'Short Trip')

@section('content')

<div class="pagetitle">
    <h1>Short Trip</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="/">Short Trip</a></li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Short Trip Chart</h5>
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('short-trip-inflow-and-outflow.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $startDate) }}">
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $endDate) }}">
                            <button class="btn btn-primary" type="submit">Filter</button>
                            <a href="{{ route('short-trip-inflow-and-outflow.index') }}" class="btn btn-secondary ms-2">Reset</a>
                        </div>
                    </form>

                    <!-- Chart Container -->
                    <div id="areaChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Short Trip Table</h5>
                    <!-- Filter Row -->
                    <div class="row mb-3">
                        <!-- Filter Fields -->
                        <div class="col-md-2">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" id="startDate" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" id="endDate" class="form-control" />
                        </div>
                    </div>


                        <div class="row mb-3">
                            <div class="col-sm-10">
                                <button id="resetFilters" class="btn btn-secondary">Display all</button>
                            </div>
                        </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    
                                    <th scope="col">
                                        <div style="display: flex; align-items: center;">
                                            <label for="amPmFilter" class="form-label" style="margin-right: 5px;">Time:</label>
                                            <select id="amPmFilter" class="form-select" 
                                                    style="border: none; font-weight: bold;">
                                                <option value="">Am/Pm</option>
                                                <option value="AM">Am</option>
                                                <option value="PM">Pm</option>
                                            </select>
                                        </div>
                                    </th>
                                    
                                    <th scope="col">
                                      <div style="display: flex; align-items: center;">
                                          <label for="transactionTypeFilter" class="form-label" style="margin-right: 5px;">In/Out:</label>
                                          <select id="transactionTypeFilter" class="form-select" 
                                                  style="border: none; font-weight: bold;">
                                              <option value="">All</option>
                                              <option value="short trip inflow">Inflow</option>
                                              <option value="short trip outflow">Outflow</option>
                                          </select>
                                      </div>
                                  </th>
                                    
                                    <th scope="col">
                                        <div style="display: flex; align-items: center;">
                                            <label for="attendantFilter" class="form-label" style="margin-right: 5px;">Attendant:</label>
                                            <select id="attendantFilter" class="form-select" 
                                                    style="border: none; font-weight: bold;">
                                                <option value="">All</option>
                                                @foreach ($staffs as $staff)
                                                    <option value="{{ $staff->staff_id }}">{{ $staff->staff_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    
                                    <th scope="col">Plate Number</th>
                                    <th scope="col">Name</th>
                                    
                                    <th scope="col">
                                        <div style="display: flex; align-items: center;">
                                            <label for="commodityFilter" class="form-label" style="margin-right: 5px;">Commodity:</label>
                                            <select id="commodityFilter" class="form-select" 
                                                    style="border: none; font-weight: bold;">
                                                <option value="">All</option>
                                                @foreach ($commodities as $commodity)
                                                    <option value="{{ $commodity->commodity_id }}">{{ $commodity->commodity_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    
                                    <th scope="col">Volume</th>
                                    
                                    <th scope="col">
                                        <div style="display: flex; align-items: center;">
                                            <label for="productionOriginFilter" class="form-label" style="margin-right: 5px;">Origin:</label>
                                            <select id="productionOriginFilter" class="form-select" 
                                                    style="border: none; font-weight: bold;">
                                                <option value="">All</option>
                                                @foreach ($productionOrigins as $origin)
                                                    <option value="{{ $origin['barangay'] }}">{{ $origin['full_address'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="shortTripTableBody">
                              @foreach ($short_trips_table as $short_trip)
                              <tr data-date="{{ $short_trip->date }}" 
                                  data-am-pm="{{ $short_trip->time }}" 
                                  data-attendant="{{ $short_trip->staff->staff_id }}" 
                                  data-commodity="{{ $short_trip->commodity->commodity_id }}" 
                                  data-production-origin="{{ $short_trip->barangay }}" 
                                  data-transaction-type="{{ $short_trip->transaction_type }}">
                                  <td>{{ $short_trip->id }}</td>
                                  <td>{{ $short_trip->date }}</td>
                                  <td>{{ $short_trip->time }}</td>
                                  <td>{{ $short_trip->transaction_type }}</td> <!-- Show the transaction type -->
                                  <td>{{ $short_trip->staff->staff_name }}</td>
                                  <td>{{ $short_trip->plate_number }}</td>
                                  <td>{{ $short_trip->name }}</td>
                                  <td>{{ $short_trip->commodity->commodity_name }}</td>
                                  <td>{{ $short_trip->volume }}</td>
                                  <td>{{ $short_trip->barangay }}, {{ $short_trip->municipality }}, {{ $short_trip->province }}, {{ $short_trip->region }}</td>
                                  <td>
                                      <a href="{{ route('short-trip-inflow-and-outflow.edit', $short_trip->id) }}" class="btn btn-outline-primary m-1">
                                          <i class="bx bxs-edit"></i> Edit
                                      </a>
                                  </td>
                              </tr>
                              @endforeach                              
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-10">
                            <a href="{{ route('short-trip-inflow-and-outflow.create') }}" class="btn btn-primary">Add New Short Trip</a>
                        </div>
                    </div>
                    
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item {{ $short_trips_table->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="#" data-page="{{ $short_trips_table->currentPage() - 1 }}">Previous</a>
                            </li>
                            
                            @for ($i = 1; $i <= $short_trips_table->lastPage(); $i++)
                                <li class="page-item {{ $i == $short_trips_table->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor
                            
                            <li class="page-item {{ $short_trips_table->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="#" data-page="{{ $short_trips_table->currentPage() + 1 }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
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
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load Page function
        function loadPage(page) {
            const url = `{{ route('short-trip-inflow-and-outflow.index') }}?page=${page}`; 

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const newTableBody = doc.querySelector('#shortTripTableBody');
                    const newPagination = doc.querySelector('.pagination');

                    // Update the table body and pagination
                    document.querySelector('#shortTripTableBody').innerHTML = newTableBody.innerHTML;
                    document.querySelector('.pagination').innerHTML = newPagination.innerHTML;

                    // Prevent the page from jumping to the top
                    scrollToTable();
                })
                .catch(error => console.error('Error loading page:', error));
        }

        // Function to smoothly scroll to the table position
        function scrollToTable() {
            const table = document.querySelector('.table-responsive');
            if (table) {
                table.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Event delegation for pagination links
        document.addEventListener('click', function (e) {
            if (e.target.closest('.page-link')) {
                e.preventDefault(); // Prevent default anchor click behavior
                const page = e.target.getAttribute('data-page');
                if (page) {
                    loadPage(page);
                }
            }
        });

        // Filters setup
const amPmFilter = document.getElementById('amPmFilter');
const attendantFilter = document.getElementById('attendantFilter');
const commodityFilter = document.getElementById('commodityFilter');
const productionOriginFilter = document.getElementById('productionOriginFilter');
const transactionTypeFilter = document.getElementById('transactionTypeFilter'); // New filter
const startDateInput = document.getElementById('startDate');
const endDateInput = document.getElementById('endDate');
const tableRows = document.querySelectorAll('#shortTripTableBody tr');

function filterTable() {
    const selectedAmPm = amPmFilter.value;
    const selectedAttendant = attendantFilter.value;
    const selectedCommodity = commodityFilter.value;
    const selectedProductionOrigin = productionOriginFilter.value;
    const selectedTransactionType = transactionTypeFilter.value; // Get the selected transaction type
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    tableRows.forEach(row => {
        const rowDate = new Date(row.dataset.date);
        const rowAmPm = row.dataset.amPm;
        const rowAttendant = row.dataset.attendant;
        const rowCommodity = row.dataset.commodity;
        const rowProductionOrigin = row.dataset.productionOrigin;
        const rowTransactionType = row.dataset.transactionType; // Get the transaction type from data attribute

        const isDateInRange = (!startDateInput.value || rowDate >= startDate) && (!endDateInput.value || rowDate <= endDate);
        const isAmPmMatch = !selectedAmPm || (rowAmPm.startsWith(selectedAmPm));
        const isAttendantMatch = !selectedAttendant || (rowAttendant === selectedAttendant);
        const isCommodityMatch = !selectedCommodity || (rowCommodity === selectedCommodity);
        const isProductionOriginMatch = !selectedProductionOrigin || (rowProductionOrigin.includes(selectedProductionOrigin));
        const isTransactionTypeMatch = !selectedTransactionType || (rowTransactionType === selectedTransactionType); // Check transaction type

        if (isDateInRange && isAmPmMatch && isAttendantMatch && isCommodityMatch && isProductionOriginMatch && isTransactionTypeMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Event listeners for all filters
amPmFilter.addEventListener('change', filterTable);
attendantFilter.addEventListener('change', filterTable);
commodityFilter.addEventListener('change', filterTable);
productionOriginFilter.addEventListener('change', filterTable);
transactionTypeFilter.addEventListener('change', filterTable); // Add event listener for transaction type
startDateInput.addEventListener('change', filterTable);
endDateInput.addEventListener('change', filterTable);

// Reset filters button
document.getElementById('resetFilters').addEventListener('click', () => {
    amPmFilter.value = '';
    attendantFilter.value = '';
    commodityFilter.value = '';
    productionOriginFilter.value = '';
    transactionTypeFilter.value = ''; // Reset transaction type filter
    startDateInput.value = '';
    endDateInput.value = '';
    filterTable(); // Apply reset
});


        // Initialize Trading Inflow Chart
        const totalVolumeData = @json($totalVolumeData);
        const series = @json($chartData);

        // Add Total Volume as the first series
        const combinedSeries = [{
            name: 'Total Volume',
            data: totalVolumeData
        }, ...series];

        const dates = @json($dates);

        const inflowChart = new ApexCharts(document.querySelector("#areaChart"), {
            series: combinedSeries,
            chart: {
                type: 'line',
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
                text: 'Volume of ShortTrip by Commodity',
                align: 'left'
            },
            labels: dates,
            xaxis: {
                type: 'datetime'
            },
            yaxis: {
                opposite: true
            },
            legend: {
                horizontalAlign: 'left'
            }
        });

        inflowChart.render();

        // Check for success message in session
        @if(session('success'))
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            setTimeout(function() {
                successModal.hide();
            }, 1000); // 1 second timeout
        @endif
  
        // Check for error message in session
        @if(session('error'))
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
            setTimeout(function() {
                errorModal.hide();
            }, 1000); // 1 second timeout
        @endif
    });
</script>

@endsection