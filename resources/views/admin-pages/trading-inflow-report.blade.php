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
                      
                        </div>
                    </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    
                                    <th scope="col">
                                        <div class="d-flex align-items-center">
                                            <label for="timeFilter" style="margin-right: 10px;">Time</label>
                                            <select name="time_filter" id="timeFilter" class="form-select" style="border: none; font-weight: bold;" onchange="filterByTime()">
                                                <option value="">All</option>
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
                                                <option value="">All</option>
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
                                                <option value="">All</option>
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

                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-10">
                            <a href="{{ route('trading-inflow.create') }}" class="btn btn-primary">Add New Trading Inflow</a>
                        </div>
                    </div>
                    
                    <div id="paginationLinks">
                            
                    </div>           
                    
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


<script src="{{asset('/custom-scripts/exporting.js')}}"></script>
<script src="{{asset('/custom-scripts/offline-exporting.js')}}"></script>
<script src="{{asset('/custom-scripts/highcharts.js')}}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Set initial filter values based on URL parameters
        if (urlParams.has('time_filter')) {
            document.getElementById('timeFilter').value = urlParams.get('time_filter');
        }
        if (urlParams.has('staff_id')) {
            document.getElementById('staffFilter').value = urlParams.get('staff_id');
        }
        if (urlParams.has('commodity_filter')) {
            document.getElementById('commodityFilter').value = urlParams.get('commodity_filter');
        }
        if (urlParams.has('municipality_filter')) {
            document.getElementById('municipalityFilter').value = urlParams.get('municipality_filter');
        }

        // Fetch filtered data based on current filter selections
        fetchFilteredData();
    });
 function fetchFilteredData(page = 1) {
        // Get selected filter values
        const timeFilter = document.getElementById('timeFilter').value;
        const staffFilter = document.getElementById('staffFilter').value;
        const commodityFilter = document.getElementById('commodityFilter').value;
        const municipalityFilter = document.getElementById('municipalityFilter').value;
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;


        // Construct the AJAX URL
        const url = "{{ route('trading-inflow.index') }}"; // Make sure to replace this with your route

        // Create query parameters
        const queryParams = new URLSearchParams({
            time_filter: timeFilter,
            staff_id: staffFilter,
            commodity_filter: commodityFilter,
            municipality_filter: municipalityFilter,
            start_date: startDate, 
            end_date: endDate,
            page:page
        });

        // Perform AJAX request
        fetch(url + '?' + queryParams.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update table body
            const tableBody = document.getElementById('TableBody');
            tableBody.innerHTML = '';

            if (data.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="10" class="text-center">No records added</td></tr>';
            } else {
                data.data.forEach((transaction,index) => {
                    tableBody.innerHTML += `
                        <tr data-date="${transaction.date}" data-am-pm="${transaction.time}" data-attendant="${transaction.staff_id}" data-commodity="${transaction.commodity_id}" data-production-origin="${transaction.barangay}">
                            <td>${index + 1}</td>
                            <td>${transaction.date}</td>
                            <td>${transaction.time}</td>
                            <td>${transaction.plate_number ?? 'N/A'}</td>
                            <td>${transaction.name ?? 'N/A'}</td>
                            <td>${transaction.commodity?.commodity_name ?? 'N/A' }</td>
                            <td>${transaction.volume}</td>
                            <td>${transaction.barangay}, ${transaction.municipality}, ${transaction.province}, ${transaction.region}</td>
                            <td>${transaction.facilitator?.facilitator_name ?? 'N/A'}</td>
                            <td>${transaction.staff.staff_name}</td>
                            <td>
                                <a href="/trading-inflow/${transaction.id}/edit" class="btn btn-outline-primary m-1">
                                    <i class="bx bxs-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                    `;
                });
            }
            // Update pagination
        updatePagination(data.current_page, data.last_page);
        })
        .catch(error => console.error('Error:', error));
    }
    
    function updatePagination(currentPage, lastPage) {
    const paginationLinks = document.getElementById('paginationLinks');
    const maxPagesToShow = 5;
    let startPage, endPage;

    if (lastPage <= maxPagesToShow) {
        // If the total number of pages is less than or equal to max, show all
        startPage = 1;
        endPage = lastPage;
    } else {
        // Determine the start and end pages to show
        const halfMaxPages = Math.floor(maxPagesToShow / 2);
        if (currentPage <= halfMaxPages) {
            // If current page is near the beginning
            startPage = 1;
            endPage = maxPagesToShow;
        } else if (currentPage + halfMaxPages >= lastPage) {
            // If current page is near the end
            startPage = lastPage - maxPagesToShow + 1;
            endPage = lastPage;
        } else {
            // Current page is somewhere in the middle
            startPage = currentPage - halfMaxPages;
            endPage = currentPage + halfMaxPages;
        }
    }

    const paginationHtml = `
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="fetchFilteredData(${currentPage - 1}); return false;">Previous</a>
                </li>
                ${Array.from({ length: endPage - startPage + 1 }, (_, i) => `
                    <li class="page-item ${startPage + i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="fetchFilteredData(${startPage + i}); return false;">${startPage + i}</a>
                    </li>
                `).join('')}
                <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="fetchFilteredData(${currentPage + 1}); return false;">Next</a>
                </li>
            </ul>
        </nav>
    `;
    paginationLinks.innerHTML = paginationHtml;
}

    function filterByStaff() {
        fetchFilteredData();
    }

    function filterByTime() {
        fetchFilteredData();
    }

    function filterByCommodity() {
        fetchFilteredData();
    }

    function filterByMunicipality() {
        fetchFilteredData();
    }
    
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
            },
            exporting: {
                enabled: true,
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
</section>




@endsection