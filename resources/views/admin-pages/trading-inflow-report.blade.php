@extends('layouts.admin')
@section('page_title', 'Trading Inflow')

@section('content')


<div class="pagetitle">
    <h1>Trading Inflow</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="/">Trading Inflow</a></li>
        </ol>
    </nav>
</div>

<section class="section dashboard">

  <div class="row ">
    <!-- Number of Vehicles -->
    <div class="col-md-4 "> <!-- Margin bottom added for spacing -->
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Date and Time</h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-time-fill"></i>
                    </div>
                    <div> 
                        <h6 id="current-date-time" class="ps-3 fs-3 fw-bold"></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 "> <!-- Margin bottom added for spacing -->
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Vehicles </h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-car-fill"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{$today_vehicle}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- End Sales Card -->

    <!-- Revenue Card -->
    <div class="col-md-4 "> <!-- Margin bottom added for spacing -->
        <div class="card info-card revenue-card">
            <div class="card-body">
                <h5 class="card-title">Volume (kg)</h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-scales-2-fill"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{$today_volume}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- End Revenue Card -->
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4"> <!-- Added margin bottom for spacing -->
            <div class="card-body">
                <h5 class="card-title">Trading Inflow Chart</h5>
                <!-- Filter Form -->
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
                    <h5 class="card-title">Inflow Table</h5>
                    <!-- Filter Row -->
                    <div class="row mb-3">
                      <form method="GET" action="{{ route('trading-inflow.index') }}" class="mb-3">
                        <div class="input-group">
                        <div class="col-md-2">
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $startDate) }}">
                        </div>
                        <div class="col-md-2">
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $endDate) }}">
                        </div>
                            <button class="btn btn-primary" type="submit">Filter</button>
                            <a href="{{ route('trading-inflow.index') }}" class="btn btn-secondary ">Reset</a>
                        </div>
                    </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    
                                    <th scope="col">
                                        <div class="d-flex align-items-center">
                                            <label for="timeFilter" style="margin-right: 10px;">Time</label>
                                            <select name="time_filter" id="timeFilter" class="form-select" style="border: none; font-weight: bold;" onchange="filterByTime()">
                                                <option value="">All Times</option>
                                                <option value="AM" {{ request('time_filter') == 'AM' ? 'selected' : '' }}>AM</option>
                                                <option value="PM" {{ request('time_filter') == 'PM' ? 'selected' : '' }}>PM</option>
                                            </select>
                                        </div>
                                    </th>
                                    
                                    
                                    
                                    <th scope="col">Plate Number</th>
                                    <th scope="col">Name</th>
                                    
                                    <th scope="col">
                                        <div class="d-flex align-items-center">
                                            <label for="commodityFilter" style="margin-right: 10px;">Commodity</label>
                                            <select name="commodity_filter" id="commodityFilter" class="form-select" style="border: none; font-weight: bold;" onchange="filterByCommodity()">
                                                <option value="">All Commodities</option>
                                                @foreach ($commodities as $commodity)
                                                <option value="{{ $commodity->commodity_id }}" {{ request('commodity_filter') == $commodity->commodity_id ? 'selected' : '' }}>
                                                    {{ $commodity->commodity_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    
                                    <th scope="col">Volume</th>
                                    
                                    
                                    
                                     <th scope="col">
                                        <div class="d-flex align-items-center">
                                            <label for="municipalityFilter" style="margin-right: 10px;">Origin</label>
                                            <select name="municipality_filter" id="municipalityFilter" class="form-select" style="border: none; font-weight: bold;" onchange="filterByMunicipality()">
                                                <option value="">Origin:</option>
                                                @foreach ($municipalities as $municipality)
                                                <option value="{{ $municipality }}" {{ request('municipality_filter') == $municipality ? 'selected' : '' }}>
                                                    {{ $municipality }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    
                                    <th scope="col">
                                        <div style="display: flex; align-items: center;">
                                            <label for="facilitatorFilter" class="form-label" style="margin-right: 5px;"> Market Facilitator:</label>
                                            <select id="facilitatorFilter" class="form-select" 
                                                    style="border: none; font-weight: bold;">
                                                <option value="">All</option>
                                                @foreach ($facilitators as $facilitator)
                                                    <option value="{{ $facilitator->facilitator_id }}">{{ $facilitator->facilitator_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    
                                    <th scope="col">
                                        <div class="d-flex align-items-center">
                                            <label for="staffFilter" style="margin-right: 10px;">TOA/TOI</label>
                                            <select name="staff_filter" id="staffFilter" class="form-select" style="border: none; font-weight: bold;" onchange="filterByStaff()">
                                                <option value="">All Staff</option>
                                                @foreach ($staffs as $staff)
                                                <option value="{{ $staff->staff_id }}" {{ request('staff_id') == $staff->staff_id ? 'selected' : '' }}>
                                                    {{ $staff->staff_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="TableBody">
                                @foreach ($trading_inflows_table as $trading_inflow)
                                <tr data-date="{{ $trading_inflow->date }}" data-am-pm="{{ $trading_inflow->time }}" data-attendant="{{ $trading_inflow->staff->staff_id }}" data-commodity="{{ $trading_inflow->commodity->commodity_id }}" data-production-origin="{{ $trading_inflow->barangay }}" data-facilitator="{{ $trading_inflow->facilitator->facilitator_id }}"> >
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $trading_inflow->date }}</td>
                                    <td>{{ $trading_inflow->time }}</td>
                                    <td>{{ $trading_inflow->plate_number }}</td>
                                    <td>{{ $trading_inflow->name }}</td>
                                    <td>{{ $trading_inflow->commodity->commodity_name }}</td>
                                    <td>{{ $trading_inflow->volume }}</td>
                                    <td>{{ $trading_inflow->barangay }}, {{ $trading_inflow->municipality }}, {{ $trading_inflow->province }}, {{ $trading_inflow->region }}</td>
                                    <td>{{ $trading_inflow->facilitator->facilitator_name }}</td>
                                    <td>{{ $trading_inflow->staff->staff_name }}</td>
                                    <td>
                                        <a href="{{ route('trading-inflow.edit', $trading_inflow->id) }}" class="btn btn-outline-primary m-1">
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
                            <a href="{{ route('trading-inflow.create') }}" class="btn btn-primary">Add New Trading Inflow</a>
                        </div>
                    </div>
                    
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item {{ $trading_inflows_table->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $trading_inflows_table->previousPageUrl() . (request()->input('staff_id') ? '&staff_id=' . request('staff_id') : '') . (request()->input('time_filter') ? '&time_filter=' . request('time_filter') : '') . (request()->input('commodity_filter') ? '&commodity_filter=' . request('commodity_filter') : '') . (request()->input('municipality_filter') ? '&municipality_filter=' . request('municipality_filter') : '') }}">Previous</a>
                            </li>
                            @for ($i = 1; $i <= $trading_inflows_table->lastPage(); $i++)
                                <li class="page-item {{ $i == $trading_inflows_table->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $trading_inflows_table->url($i) . (request()->input('staff_id') ? '&staff_id=' . request('staff_id') : '') . (request()->input('time_filter') ? '&time_filter=' . request('time_filter') : '') . (request()->input('commodity_filter') ? '&commodity_filter=' . request('commodity_filter') : '') . (request()->input('municipality_filter') ? '&municipality_filter=' . request('municipality_filter') : '') }}">{{ $i }}</a>
                                </li>
                                @endfor
                                <li class="page-item {{ $trading_inflows_table->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $trading_inflows_table->nextPageUrl() . (request()->input('staff_id') ? '&staff_id=' . request('staff_id') : '') . (request()->input('time_filter') ? '&time_filter=' . request('time_filter') : '') . (request()->input('commodity_filter') ? '&commodity_filter=' . request('commodity_filter') : '') . (request()->input('municipality_filter') ? '&municipality_filter=' . request('municipality_filter') : '') }}">Next</a>
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
        const amPmFilter = document.getElementById('amPmFilter');
        const attendantFilter = document.getElementById('attendantFilter');
        const commodityFilter = document.getElementById('commodityFilter');
        const productionOriginFilter = document.getElementById('productionOriginFilter');
        const facilitatorFilter = document.getElementById('facilitatorFilter'); // Add this line
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const tableRows = document.querySelectorAll('#TableBody tr');

        function filterTable() {
            const selectedAmPm = amPmFilter.value;
            const selectedAttendant = attendantFilter.value;
            const selectedCommodity = commodityFilter.value;
            const selectedProductionOrigin = productionOriginFilter.value;
            const selectedFacilitator = facilitatorFilter.value; // This is now initialized
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
        
            tableRows.forEach(row => {
                const rowDate = new Date(row.dataset.date);
                const rowAmPm = row.dataset.amPm;
                const rowAttendant = row.dataset.attendant;
                const rowCommodity = row.dataset.commodity;
                const rowProductionOrigin = row.dataset.productionOrigin;
                const rowFacilitator = row.dataset.facilitator; // This is now initialized
        
                const isDateInRange = (!startDateInput.value || rowDate >= startDate) && (!endDateInput.value || rowDate <= endDate);
                const isAmPmMatch = !selectedAmPm || (rowAmPm.startsWith(selectedAmPm));
                const isAttendantMatch = !selectedAttendant || (rowAttendant === selectedAttendant);
                const isCommodityMatch = !selectedCommodity || (rowCommodity === selectedCommodity);
                const isProductionOriginMatch = !selectedProductionOrigin || (rowProductionOrigin.includes(selectedProductionOrigin));
                const isFacilitatorMatch = !selectedFacilitator || (rowFacilitator === selectedFacilitator); // This is now initialized
        
                if (isDateInRange && isAmPmMatch && isAttendantMatch && isCommodityMatch && isProductionOriginMatch && isFacilitatorMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Add event listeners for all filters
        amPmFilter.addEventListener('change', filterTable);
        attendantFilter.addEventListener('change', filterTable);
        commodityFilter.addEventListener('change', filterTable);
        productionOriginFilter.addEventListener('change', filterTable);
        facilitatorFilter.addEventListener('change', filterTable); // Add this line for facilitator filter
        startDateInput.addEventListener('change', filterTable);
        endDateInput.addEventListener('change', filterTable);

        // Reset filters button
        document.getElementById('resetFilters').addEventListener('click', () => {
            amPmFilter.value = '';
            attendantFilter.value = '';
            commodityFilter.value = '';
            productionOriginFilter.value = '';
            facilitatorFilter.value = ''; // Add this line to reset facilitator filter
            startDateInput.value = '';
            endDateInput.value = '';
            filterTable(); // Apply reset
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const totalVolumeData = @json($totalVolumeData);
        const series = @json($chartData);

        // Assuming dates is in the format of "YYYY-MM-DD"
        const dates = @json($dates).map(date => Date.parse(date)); // Convert to timestamps

        // Prepare the combined series
        const combinedSeries = [{
            name: 'Total Volume',
            data: totalVolumeData
        }, ...series];

        // Initialize Trading Inflow Chart
        Highcharts.chart('areaChart', {
            chart: {
                type: 'line',
                height: 350,
                animation: {
                    duration: 2000,
                    easing: 'easeOutBounce'
                }
            },
            title: {
                text: 'Volume of Trading Inflows by Commodity'
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    day: '%b %e, %Y' // Format as "Oct 1, 2024"
                },
                tickInterval: 24 * 3600 * 1000 // One day
            },
            yAxis: {
                title: {
                    text: 'Volume'
                },
                opposite: true
            },
            series: combinedSeries.map((serie, index) => ({
                ...serie,
                data: serie.data.map((value, i) => [dates[i], value]) // Pair each data point with its corresponding date
            })),
            legend: {
                horizontalAlign: 'left'
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: false
                    },
                    marker: {
                        enabled: false
                    }
                }
            }
        });
    });
</script>

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
<script>
    $(document).ready( function () {
    $('#reportTable').DataTable();
} );

</script>
<script>
  function updateDateTime() {
      const now = new Date();
      const options = { 
          year: 'numeric', 
          month: '2-digit', 
          day: '2-digit', 
          hour: '2-digit', 
          minute: '2-digit', 
          second: '2-digit', 
          hour12: true // Change this to true for 12-hour format
      };
      
      // Format the date and time
      const formattedDateTime = now.toLocaleString('en-US', options);
      
      // Update the content of the div
      document.getElementById('current-date-time').textContent = formattedDateTime;
  }

  // Call updateDateTime every second
  setInterval(updateDateTime, 1000);
  
  // Initial call to set the date and time right away
  updateDateTime();
</script>

<script>
    function filterByStaff() {
        const staffId = document.getElementById('staffFilter').value;
        const url = new URL(window.location.href);
        if (staffId) {
            url.searchParams.set('staff_id', staffId);
        } else {
            url.searchParams.delete('staff_id');
        }
        window.location.href = url.toString();
    }

    function filterByTime() {
        const timeFilter = document.getElementById('timeFilter').value;
        const url = new URL(window.location.href);
        if (timeFilter) {
            url.searchParams.set('time_filter', timeFilter);
        } else {
            url.searchParams.delete('time_filter');
        }
        window.location.href = url.toString();
    }

    function filterByCommodity() {
        const commodityFilter = document.getElementById('commodityFilter').value;
        const url = new URL(window.location.href);
        if (commodityFilter) {
            url.searchParams.set('commodity_filter', commodityFilter);
        } else {
            url.searchParams.delete('commodity_filter');
        }
        window.location.href = url.toString();
    }

    function filterByMunicipality() {
        const municipalityFilter = document.getElementById('municipalityFilter').value;
        const url = new URL(window.location.href);
        if (municipalityFilter) {
            url.searchParams.set('municipality_filter', municipalityFilter);
        } else {
            url.searchParams.delete('municipality_filter');
        }
        window.location.href = url.toString();
    }

    
</script>
@endsection