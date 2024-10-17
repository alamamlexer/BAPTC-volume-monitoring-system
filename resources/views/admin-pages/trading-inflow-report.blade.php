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

<section class="section">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Trading Inflow Chart</h5>
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('trading-inflow.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $startDate) }}">
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $endDate) }}">
                            <button class="btn btn-primary" type="submit">Filter</button>
                            <a href="{{ route('trading-inflow.index') }}" class="btn btn-secondary ms-2">Reset</a>
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
                    <h5 class="card-title">Inflow Table</h5>
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
                                                <option value="">AM/PM</option>
                                                <option value="AM">AM</option>
                                                <option value="PM">PM</option>
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
                            <tbody id="inflowTableBody">
                                @foreach ($trading_inflows_table as $trading_inflow)
                                <tr data-date="{{ $trading_inflow->date }}" data-am-pm="{{ $trading_inflow->time }}" data-attendant="{{ $trading_inflow->staff->staff_id }}" data-commodity="{{ $trading_inflow->commodity->commodity_id }}" data-production-origin="{{ $trading_inflow->barangay }}">
                                    <td>{{ $trading_inflow->id }}</td>
                                    <td>{{ $trading_inflow->date }}</td>
                                    <td>{{ $trading_inflow->time }}</td>
                                    <td>{{ $trading_inflow->staff->staff_name }}</td>
                                    <td>{{ $trading_inflow->plate_number }}</td>
                                    <td>{{ $trading_inflow->name }}</td>
                                    <td>{{ $trading_inflow->commodity->commodity_name }}</td>
                                    <td>{{ $trading_inflow->volume }}</td>
                                    <td>{{ $trading_inflow->barangay }}, {{ $trading_inflow->municipality }}, {{ $trading_inflow->province }}, {{ $trading_inflow->region }}</td>
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
                                <a class="page-link" href="#" data-page="{{ $trading_inflows_table->currentPage() - 1 }}">Previous</a>
                            </li>
                            
                            @for ($i = 1; $i <= $trading_inflows_table->lastPage(); $i++)
                                <li class="page-item {{ $i == $trading_inflows_table->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor
                            
                            <li class="page-item {{ $trading_inflows_table->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="#" data-page="{{ $trading_inflows_table->currentPage() + 1 }}">Next</a>
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
    function loadPage(page) {
        const url = `{{ route('trading-inflow.index') }}?page=${page}`; 

        fetch(url)
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
                const newTableBody = doc.querySelector('#inflowTableBody');
                const newPagination = doc.querySelector('.pagination');

                // Update the table body and pagination
                document.querySelector('#inflowTableBody').innerHTML = newTableBody.innerHTML;
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
</script>









<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amPmFilter = document.getElementById('amPmFilter');
        const attendantFilter = document.getElementById('attendantFilter');
        const commodityFilter = document.getElementById('commodityFilter');
        const productionOriginFilter = document.getElementById('productionOriginFilter');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const tableRows = document.querySelectorAll('#inflowTableBody tr');

        function filterTable() {
            const selectedAmPm = amPmFilter.value;
            const selectedAttendant = attendantFilter.value;
            const selectedCommodity = commodityFilter.value;
            const selectedProductionOrigin = productionOriginFilter.value;
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            tableRows.forEach(row => {
                const rowDate = new Date(row.dataset.date);
                const rowAmPm = row.dataset.amPm;
                const rowAttendant = row.dataset.attendant;
                const rowCommodity = row.dataset.commodity;
                const rowProductionOrigin = row.dataset.productionOrigin;

                const isDateInRange = (!startDateInput.value || rowDate >= startDate) && (!endDateInput.value || rowDate <= endDate);
                const isAmPmMatch = !selectedAmPm || (rowAmPm.startsWith(selectedAmPm));
                const isAttendantMatch = !selectedAttendant || (rowAttendant === selectedAttendant);
                const isCommodityMatch = !selectedCommodity || (rowCommodity === selectedCommodity);
                const isProductionOriginMatch = !selectedProductionOrigin || (rowProductionOrigin.includes(selectedProductionOrigin));

                if (isDateInRange && isAmPmMatch && isAttendantMatch && isCommodityMatch && isProductionOriginMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        amPmFilter.addEventListener('change', filterTable);
        attendantFilter.addEventListener('change', filterTable);
        commodityFilter.addEventListener('change', filterTable);
        productionOriginFilter.addEventListener('change', filterTable);
        startDateInput.addEventListener('change', filterTable);
        endDateInput.addEventListener('change', filterTable);

        // Reset filters button
        document.getElementById('resetFilters').addEventListener('click', () => {
            amPmFilter.value = '';
            attendantFilter.value = '';
            commodityFilter.value = '';
            productionOriginFilter.value = '';
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
        const dates = @json($dates);

        const highchartsSeries = series.map(commodity => ({
            name: commodity.name,
            data: commodity.data
        }));

        // Add total volume series
        highchartsSeries.unshift({
            name: 'Total Volume',
            data: totalVolumeData
        });

        Highcharts.chart('areaChart', {
            chart: {
                type: 'line',
                height: 350
            },
            title: {
                text: 'Volume of Trading Inflows by Commodity'
            },
            subtitle: {
                text: 'Data from your trading inflow records'
            },
            xAxis: {
                categories: dates,
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                title: {
                    text: 'Volume'
                }
            },
            series: highchartsSeries,
            tooltip: {
                shared: true,
                valueSuffix: ' units'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
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

@endsection