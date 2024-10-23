@extends('layouts.admin')
@section('page_title', 'Admin Dashboard')
@section('content')
<section class="section dashboard">
    <div class="pagetitle">
        <h1>Dashboard</h1>
    </div>
    <div class="container">
        <div class="row">
            {{-- Inflow --}}
            <div class="col-md-6"> 
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title fs-4 fw-bold">Inflow</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">Vehicles</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-car-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $today_inflow_vehicle }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title">Volume (kg)</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-scales-2-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $today_inflow_volume }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Outflow --}}
            <div class="col-md-6"> 
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title fs-4 fw-bold">Outflow</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">Vehicles</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-car-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $today_outflow_vehicle }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title">Volume (kg)</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-scales-2-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $today_outflow_volume }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-md-12">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class=" col-md-12 card-title text-center fs-4 fw-bold">Volume Tally</h5>
                            <div class=" col-md-12 card-title">
                                <form action="{{ route('admin.index') }}" method="GET" class="mb-4">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-2 mb-2">  {{-- Added mb-2 for closer spacing --}}
                                            <select name="year" class="form-select">
                                                @foreach ($years as $yearOption)
                                                    <option value="{{ $yearOption }}" {{ $yearOption == $year ? 'selected' : '' }}>{{ $yearOption }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2 mb-2">  {{-- Added mb-2 for closer spacing --}}
                                            <select name="month" class="form-select">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-sm-2 mb-2">  {{-- Added mb-2 for closer spacing --}}
                                            <button type="submit" class="btn btn-secondary w-100">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        <!-- Bordered Table -->
                        <div class=" col-md-12 table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th colspan="2" class="fs-5 card-title fw-bold text-success">Inflow</th>
                                        <th class="fs-5 card-title fw-bold text-success">{{ $volume_tally_inflow }}</th>
                                        <th></th>
                                        <th colspan="2" class="fs-5 card-title fw-bold text-success">Outflow</th>
                                        <th class="fs-5 card-title fw-bold text-success">{{ $volume_tally_outflow }}</th>
                                        <th></th>
                                        <th colspan="2" class="fs-5 card-title text-center fw-bold text-success">{{ $volume_tally_variance }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="card-title">Date</th>
                                        <th class="card-title">Farmer</th>
                                        <th class="card-title">Short Trip In</th>
                                        <th class="card-title">Total</th>
                                        <th class="card-title"></th>
                                        <th class="card-title">Truckings</th>
                                        <th class="card-title">Short Trip Out</th>
                                        <th class="card-title">Total</th>
                                        <th class="card-title"></th>
                                        <th class="card-title">Variance</th>
                                        <th class="card-title">Remarks</th>
                                    </tr>

                                    @foreach ($volume_tally_data as $data)
                                    <tr>
                                        <td>{{ $data['date'] }}</td>
                                        <td>{{ $data['farmers'] }}</td>
                                        <td>{{ $data['short_trip_ins'] }}</td>
                                        <td>{{ $data['daily_in_total'] }}</td>
                                        <td></td>
                                        <td>{{ $data['truckings'] }}</td>
                                        <td>{{ $data['short_trip_outs'] }}</td>
                                        <td>{{ $data['daily_out_total'] }}</td>
                                        <td></td>
                                        <td>{{ $data['variance'] }}</td>
                                        <td>{{ $data['remarks'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- End Bordered Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
