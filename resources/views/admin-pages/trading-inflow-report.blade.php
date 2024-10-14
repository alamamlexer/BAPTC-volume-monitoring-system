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
                                @foreach ($trading_inflows as $trading_inflow)
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
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    @if(session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5>{{ session('success') }}</h5>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

        // Add Total Volume as the first series
        const combinedSeries = [{
            name: 'Total Volume',
            data: totalVolumeData
        }, ...series];

        const dates = @json($dates);

        // Initialize Trading Inflow Chart
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
                text: 'Volume of Trading Inflows by Commodity',
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
    });
</script>

@endsection