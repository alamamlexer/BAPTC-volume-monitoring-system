@extends('layouts.staff')

@section('page_title', 'Summary Report')

@section('content')
<div class="pagetitle">
    <h1>Summary Report</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Overview reports of daily transactions</li>
        </ol>
    </nav>
</div>


<!-- Date Range Filter Form -->
<div class="row mb-3">
    <div class="col-lg-6">
        <form method="GET" action="{{ route('staff-summary-report.index') }}" id="filter-form">
            <div class="input-group">
                <select name="range_type" class="form-control" id="range_type" onchange="this.form.submit()">
                    <option value="daily" {{ $rangeType == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="custom" {{ $rangeType == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    <option value="monthly" {{ $rangeType == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="quarterly" {{ $rangeType == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                    <option value="yearly" {{ $rangeType == 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>

                <div id="date-fields">
                    @if($rangeType == 'monthly')
                        <!-- Dropdown for months -->
                        <select name="month" class="form-control">
                            <option value="">Select Month</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('month', $selectedMonth) == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->format('F') }} <!-- Full month name -->
                                </option>
                            @endfor
                        </select>
                    @elseif($rangeType == 'daily')
                        <!-- Daily date input -->
                        <input type="date" name="date" class="form-control" value="{{ old('date', $startDate) }}">
                    @elseif($rangeType == 'custom')
                        <!-- Custom date range inputs (start and end date) -->
                        <div class="d-flex">
                            <!-- Start Date -->
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $startDate) }}" placeholder="Start Date">
                            <span class="mx-2">to</span>
                            <!-- End Date -->
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $endDate) }}" placeholder="End Date">
                        </div>
                    @elseif($rangeType == 'yearly' || $rangeType == 'quarterly')
                        <!-- Year input for yearly and quarterly ranges -->
                        <input type="number" name="year" class="form-control" value="{{ old('year', $selectedYear ?? $currentYear) }}" min="2000" max="2100">
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
</div>




<!-- Table: Commodities for Daily and Monthly Views -->
@if($rangeType != 'yearly' && $rangeType != 'quarterly')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><b>Commodity Trading Volume Report (Inflow)</b></h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">RANK</th>
                                <th class="text-center">COMMODITY</th>
                                <th class="text-center">TOTAL VOLUME (KG)</th>
                                <th class="text-center">PERCENTAGE SHARE (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inflow_volumes as $commodity)
                            @if ($commodity['total_volume'] > 0)
                                <tr>
                                    <td class="text-center">{{ $commodity['rank'] }}</td>
                                    <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                    <td class="text-center">{{ number_format($commodity['total_volume'], 2) }}</td>
                                    <td class="text-center">{{ number_format($commodity['percentage_share'], 2) }}%</td>
                                </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                <td class="text-center fw-bold">{{ number_format($grandTotalVolume, 2) }}</td>
                                <td class="text-center fw-bold">
                                    @if ($grandTotalVolume > 0)
                                        100%
                                        @else
                                        0.00%
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Short Trip Inflow Table (Updated) -->
                <div class="table-responsive">
                    <h5 class="card-title"><b>Short Trip Inflow Volume Report</b></h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">RANK</th>
                                <th class="text-center">COMMODITY</th>
                                <th class="text-center">TOTAL VOLUME (KG)</th>
                                <th class="text-center">PERCENTAGE SHARE (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shortTripInflowVolumes as $commodity)
                            @if ($commodity['short_trip_inflow_volume'] > 0)
                            <tr>
                                <td class="text-center">{{ $commodity['rank'] }}</td>
                                <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                <td class="text-center">{{ number_format($commodity['short_trip_inflow_volume'], 2) }}</td>
                                <td class="text-center">{{ number_format(($commodity['short_trip_inflow_volume'] / $grandTotalShortTripInflow) * 100, 2) }}%</td>
                            </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                <td class="text-center fw-bold">{{ number_format($grandTotalShortTripInflow, 2) }}</td>
                                <td class="text-center fw-bold">
                                    @if ($grandTotalShortTripInflow > 0)
                                        100%
                                        @else
                                        0.00%
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif


<!-- Table: Commodities for Quarterly View -->
@if($rangeType == 'quarterly')
<div class="row">

    <!-- Regular Trading Inflow Table -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><b>Trading Inflow Volume Report (Quarterly)</b></h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">RANK</th>
                                <th class="text-center" scope="col">COMMODITY</th>
                                <th class="text-center" scope="col">Q1 (Jan-Mar)</th>
                                <th class="text-center" scope="col">Q2 (Apr-Jun)</th>
                                <th class="text-center" scope="col">Q3 (Jul-Sep)</th>
                                <th class="text-center" scope="col">Q4 (Oct-Dec)</th>
                                <th class="text-center" scope="col">TOTAL VOLUME (KG)</th>
                                <th class="text-center" scope="col">PERCENTAGE SHARE (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inflow_volumes as $commodity)
                            @if ($commodity['total_volume'] > 0)
                            <tr>
                                <td class="text-center">{{ $commodity['rank'] }}</td>
                                <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_volumes'][1] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_volumes'][2] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_volumes'][3] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_volumes'][4] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['total_volume'], 2) }}</td>
                                <td class="text-center">
                                    @if ($grandTotalVolume > 0)
                                        {{ number_format(($commodity['total_volume'] / $grandTotalVolume) * 100, 2) }}%
                                    @else
                                        0.00%
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyGrandTotals['Q1'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyGrandTotals['Q2'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyGrandTotals['Q3'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyGrandTotals['Q4'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($grandTotalVolume, 2) }}</td>
                                <td class="text-center fw-bold">
                                    @if ($grandTotalShortTripInflow > 0)
                                        100%
                                        @else
                                        0.00%
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- Short Trip Inflow Quarterly Table -->
    <div class="col-lg-12 mt-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><b>Short Trip Inflow Volume Report (Quarterly)</b></h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">RANK</th>
                                <th class="text-center" scope="col">COMMODITY</th>
                                <th class="text-center" scope="col">Q1 (Jan-Mar)</th>
                                <th class="text-center" scope="col">Q2 (Apr-Jun)</th>
                                <th class="text-center" scope="col">Q3 (Jul-Sep)</th>
                                <th class="text-center" scope="col">Q4 (Oct-Dec)</th>
                                <th class="text-center" scope="col">TOTAL VOLUME (KG)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shortTripInflowVolumes as $commodity)
                            @if ($commodity['short_trip_inflow_volume'] > 0)
                            <tr>
                                <td class="text-center">{{ $commodity['rank'] }}</td>
                                <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_short_trip_inflow_volumes'][1] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_short_trip_inflow_volumes'][2] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_short_trip_inflow_volumes'][3] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_short_trip_inflow_volumes'][4] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['short_trip_inflow_volume'], 2) }}</td>
                            </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyShortTripGrandTotals['Q1'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyShortTripGrandTotals['Q2'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyShortTripGrandTotals['Q3'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyShortTripGrandTotals['Q4'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($grandTotalShortTripInflow, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endif



<!-- Table: Commodities for Yearly View -->
<!-- Regular Trading Yearly View -->
    @if($rangeType == 'yearly')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><b>Trading Inflow Report (Yearly)</b></h5>

                    <!-- Regular Trading Inflow Table (Yearly) -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">RANK</th>
                                    <th class="text-center">COMMODITY</th>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <th class="text-center">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</th>
                                    @endfor
                                    <th class="text-center">TOTAL VOLUME (KG)</th>
                                    <th class="text-center">PERCENTAGE SHARE (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inflow_volumes as $commodity)
                                @if ($commodity['total_volume'] > 0)
                                <tr>
                                    <td class="text-center">{{ $commodity['rank'] }}</td>
                                    <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <td class="text-center">{{ number_format($commodity['monthly_volumes'][$month] ?? 0, 2) }}</td>
                                    @endfor
                                    <td class="text-center">{{ number_format($commodity['total_volume'], 2) }}</td>
                                    <td class="text-center">{{ number_format($commodity['percentage_share'], 2) }}%</td>
                                </tr>
                                @endif
                                @endforeach

                                <!-- Grand Total Row -->
                                <tr>
                                    <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                    @foreach (range(1, 12) as $month)
                                        <td class="text-center fw-bold">{{ number_format($monthlyGrandTotals[$month] ?? 0, 2) }}</td>
                                    @endforeach
                                    <td class="text-center fw-bold">{{ number_format($grandTotalVolume, 2) }}</td>
                                    <td class="text-center fw-bold">
                                        @if ($grandTotalVolume > 0)
                                            100%
                                            @else
                                            0.00%
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Short Trip Inflow Table (Yearly) -->
                    <div class="table-responsive">
                        <h5 class="card-title"><b>Short Trip Inflow Volume Report</b></h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">RANK</th>
                                    <th class="text-center">COMMODITY</th>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <th class="text-center">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</th>
                                    @endfor
                                    <th class="text-center">TOTAL VOLUME (KG)</th>
                                    <th class="text-center">PERCENTAGE SHARE (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shortTripInflowVolumes as $commodity)
                                @if ($commodity['total_volume'] > 0)
                                <tr>
                                    <td class="text-center">{{ $commodity['rank'] }}</td>
                                    <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                    @for ($month = 1; $month <= 12; $month++)
                                    <td class="text-center">{{ number_format($commodity['monthly_short_trip_inflow_volumes'][$month] ?? 0, 2) }}</td>
                                    @endfor
                                    <td class="text-center">{{ number_format($commodity['short_trip_inflow_volume'], 2) }}</td>
                                    <td class="text-center">{{ number_format(($commodity['short_trip_inflow_volume'] / $grandTotalShortTripInflow) * 100, 2) }}%</td>
                                </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                    @foreach (range(1, 12) as $month)
                                        <td class="text-center fw-bold">{{ number_format($monthlyShortTripGrandTotals[$month] ?? 0, 2) }}</td>
                                    @endforeach
                                    <td class="text-center fw-bold">{{ number_format($grandTotalShortTripInflow, 2) }}</td>
                                    <td class="text-center fw-bold">
                                        @if ($grandTotalShortTripInflow > 0)
                                            100%
                                            @else
                                            0.00%
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

<!--OUTFLOW-->
<!-- Table: Commodities for Daily and Monthly Views -->
@if($rangeType != 'yearly' && $rangeType != 'quarterly')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><b>Commodity Trading Volume Report (Outflow)</b></h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">RANK</th>
                                <th class="text-center">COMMODITY</th>
                                <th class="text-center">TOTAL VOLUME (KG)</th>
                                <th class="text-center">PERCENTAGE SHARE (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($outflow_volumes as $commodity)
                            @if ($commodity['outtotal_volume'] > 0)
                            <tr>
                                <td class="text-center">{{ $commodity['rank'] }}</td>
                                <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                <td class="text-center">{{ number_format($commodity['outtotal_volume'], 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['outpercentage_share'], 2) }}%</td>
                            </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                <td class="text-center fw-bold">{{ number_format($grandOutTotalVolume, 2) }}</td>
                                <td class="text-center fw-bold">
                                    @if ($grandOutTotalVolume > 0)
                                        100%
                                        @else
                                        0.00%
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Short Trip Outflow Table (Updated) -->
                <div class="table-responsive">
                    <h5 class="card-title"><b>Short Trip Outflow Volume Report</b></h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">RANK</th>
                                <th class="text-center">COMMODITY</th>
                                <th class="text-center">TOTAL VOLUME (KG)</th>
                                <th class="text-center">PERCENTAGE SHARE (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shortTripOutflowVolumes as $commodity)
                            @if ($commodity['short_trip_outflow_volume'] > 0)
                            <tr>
                                <td class="text-center">{{ $commodity['rank'] }}</td>
                                <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                <td class="text-center">{{ number_format($commodity['short_trip_outflow_volume'], 2) }}</td>
                                <td class="text-center">{{ number_format(($commodity['short_trip_outflow_volume'] / $grandTotalShortTripOutflow) * 100, 2) }}%</td>
                            </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                <td class="text-center fw-bold">{{ number_format($grandTotalShortTripOutflow, 2) }}</td>
                                <td class="text-center fw-bold">
                                    @if ($grandOutTotalVolume > 0)
                                        100%
                                        @else
                                        0.00%
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Table: Commodities for Quarterly View -->
@if($rangeType == 'quarterly')
<div class="row">

    <!-- Regular Trading Outflow Table -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><b>Trading Outflow Volume Report (Quarterly)</b></h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">RANK</th>
                                <th class="text-center" scope="col">COMMODITY</th>
                                <th class="text-center" scope="col">Q1 (Jan-Mar)</th>
                                <th class="text-center" scope="col">Q2 (Apr-Jun)</th>
                                <th class="text-center" scope="col">Q3 (Jul-Sep)</th>
                                <th class="text-center" scope="col">Q4 (Oct-Dec)</th>
                                <th class="text-center" scope="col">TOTAL VOLUME (KG)</th>
                                <th class="text-center" scope="col">PERCENTAGE SHARE (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($outflow_volumes as $commodity)
                            @if($commodity['outtotal_volume'] > 0)
                            <tr>
                                <td class="text-center">{{ $commodity['rank'] }}</td>
                                <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                <td class="text-center">{{ number_format($commodity['outquarterly_volumes'][1] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['outquarterly_volumes'][2] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['outquarterly_volumes'][3] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['outquarterly_volumes'][4] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['outtotal_volume'], 2) }}</td>
                                <td class="text-center">
                                    @if ($grandOutTotalVolume > 0)
                                        {{ number_format(($commodity['outtotal_volume'] / $grandOutTotalVolume) * 100, 2) }}%
                                    @else
                                        0.00%
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyOutGrandTotals['Q1'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyOutGrandTotals['Q2'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyOutGrandTotals['Q3'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyOutGrandTotals['Q4'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($grandOutTotalVolume, 2) }}</td>
                                <td class="text-center fw-bold">100%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Short Trip Outflow Quarterly Table -->
    <div class="col-lg-12 mt-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><b>Short Trip Outflow Volume Report (Quarterly)</b></h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">RANK</th>
                                <th class="text-center" scope="col">COMMODITY</th>
                                <th class="text-center" scope="col">Q1 (Jan-Mar)</th>
                                <th class="text-center" scope="col">Q2 (Apr-Jun)</th>
                                <th class="text-center" scope="col">Q3 (Jul-Sep)</th>
                                <th class="text-center" scope="col">Q4 (Oct-Dec)</th>
                                <th class="text-center" scope="col">TOTAL VOLUME (KG)</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            @foreach ($shortTripOutflowVolumes as $commodity)
                            @if($commodity['short_trip_outflow_volume'] > 0)
                            <tr>
                                <td class="text-center">{{ $commodity['rank'] }}</td>
                                <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_short_trip_outflow_volumes'][1] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_short_trip_outflow_volumes'][2] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_short_trip_outflow_volumes'][3] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['quarterly_short_trip_outflow_volumes'][4] ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($commodity['short_trip_outflow_volume'], 2) }}</td>
                            </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyShortTripOutGrandTotals['Q1'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyShortTripOutGrandTotals['Q2'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyShortTripOutGrandTotals['Q3'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($quarterlyShortTripOutGrandTotals['Q4'] ?? 0, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($grandTotalShortTripOutflow, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endif



<!-- Table: Commodities for Yearly View -->
<!-- Regular Trading Yearly View -->
    @if($rangeType == 'yearly')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><b>Trading Outflow Report (Yearly)</b></h5>

                    <!-- Regular Trading Outflow Table (Yearly) -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">RANK</th>
                                    <th class="text-center">COMMODITY</th>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <th class="text-center">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</th>
                                    @endfor
                                    <th class="text-center">TOTAL VOLUME (KG)</th>
                                    <th class="text-center">PERCENTAGE SHARE (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outflow_volumes as $commodity)
                                @if($commodity['outtotal_volume'] > 0)
                                <tr>
                                    <td class="text-center">{{ $commodity['rank'] }}</td>
                                    <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <td class="text-center">{{ number_format($commodity['outmonthly_volumes'][$month] ?? 0, 2) }}</td>
                                    @endfor
                                    <td class="text-center">{{ number_format($commodity['outtotal_volume'], 2) }}</td>
                                    <td class="text-center">{{ number_format($commodity['outpercentage_share'], 2) }}%</td>
                                </tr>
                                @endif
                                @endforeach

                                <!-- Grand Total Row -->
                                <tr>
                                    <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                    @foreach (range(1, 12) as $month)
                                        <td class="text-center fw-bold">{{ number_format($monthlyOutGrandTotals[$month] ?? 0, 2) }}</td>
                                    @endforeach
                                    <td class="text-center fw-bold">{{ number_format($grandOutTotalVolume, 2) }}</td>
                                    <td class="text-center fw-bold">
                                        @if ($grandOutTotalVolume > 0)
                                            100%
                                            @else
                                            0.00%
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Short Trip Outflow Table (Yearly) -->
                    <div class="table-responsive">
                        <h5 class="card-title"><b>Short Trip Outflow Volume Report</b></h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">RANK</th>
                                    <th class="text-center">COMMODITY</th>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <th class="text-center">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</th>
                                    @endfor
                                    <th class="text-center">TOTAL VOLUME (KG)</th>
                                    <th class="text-center">PERCENTAGE SHARE (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shortTripOutflowVolumes as $commodity)
                                @if($commodity['short_trip_outflow_volume'] > 0)
                                <tr>
                                    <td class="text-center">{{ $commodity['rank'] }}</td>
                                    <td class="text-center">{{ $commodity['commodity_name'] }}</td>
                                    @for ($month = 1; $month <= 12; $month++)
                                    <td class="text-center">{{ number_format($commodity['monthly_short_trip_outflow_volumes'][$month] ?? 0, 2) }}</td>
                                    @endfor
                                    <td class="text-center">{{ number_format($commodity['short_trip_outflow_volume'], 2) }}</td>
                                    <td class="text-center">{{ number_format(($commodity['short_trip_outflow_volume'] / $grandTotalShortTripOutflow) * 100, 2) }}%</td>
                                </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <td class="text-center fw-bold" colspan="2">GRAND TOTAL</td>
                                    @foreach (range(1, 12) as $month)
                                        <td class="text-center fw-bold">{{ number_format($monthlyShortTripOutGrandTotals[$month] ?? 0, 2) }}</td>
                                    @endforeach
                                    <td class="text-center fw-bold">{{ number_format($grandTotalShortTripOutflow, 2) }}</td>
                                    <td class="text-center fw-bold">
                                        @if ($grandOutTotalVolume > 0)
                                            100%
                                            @else
                                            0.00%
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection