@extends('layouts.admin')
@section('page_title', 'Trading Inflow Form')
@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
        <h1>Form Layouts</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Records</a></li>
                <li class="breadcrumb-item active"><a href="/">Add a new commodity</a></li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->

    <section class="section">

        {{-- Form --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Commodity Form</h5>
                        <!-- Floating Labels Form -->
                        <form class="row g-3 " action="{{ route('commodity.store') }}" method="POST">
                            @csrf

                            {{--  input for transaction_status = "trading"  --}}
                            <div class="col-md-5" hidden>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="transaction_status"
                                        name="transaction_status" value="temporary">
                                    <label for="transaction_status"></label>
                                </div>
                            </div>

                            <div class="col-md-5" hidden>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="transaction_type" name="transaction_type"
                                        value="trading inflow">
                                    <label for="transaction_type"></label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date"
                                        placeholder="Date" value="{{ old('date', date('Y-m-d')) }}" required readonly>
                                    <label for="date">Date</label>
                                    @if ($errors->has('date'))
                                        <span class="text-danger">{{ $errors->first('date') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="time" name="time" required >
                                        <option value="AM" {{ old('time', $defaultTime) == 'AM' ? 'selected' : '' }}>AM</option>
                                        <option value="PM" {{ old('time', $defaultTime) == 'PM' ? 'selected' : '' }}>PM</option>
                                    </select>
                                    <label for="time">Time</label>
                                    @if ($errors->has('time'))
                                        <span class="text-danger">{{ $errors->first('time') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            
                            <div class="col-md-4 position-relative" data-col="6">
                                <div class="form-floating">
                                    <input type="text" class="form-control filter-input"  name="facilitator_name" 
                                        placeholder="Select or type facilitator..."  aria-label="Facilitator"
                                        autocomplete="off" data-dropdown="facilitatorDropdown" value="{{ old('facilitator_name') }}">
                                    <label for="facilitator_name">Facilitator</label>
                                    @if ($errors->has('facilitator_name'))
                                        <span class="text-danger">{{ $errors->first('facilitator_name') }}</span>
                                    @endif
                                </div>
                                <ul class="dropdown-list list-group position-absolute w-100"
                                    style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;"
                                    data-dropdown="facilitatorDropdown">
                                    <li class="no-records list-group-item" style="display: none; cursor: default;">Facilitator does not exist in the records</li>
                                    @foreach ($facilitators as $facilitator)
                                    <li class="list-group-item list-group-item-action input-item" 
                                        data-facilitator-id="{{ $facilitator->facilitator_id }}" 
                                        data-facilitator-name="{{ $facilitator->facilitator_name }}">{{ $facilitator->facilitator_name }}</li>
                                    @endforeach
                                </ul>
                            </div>


                            <div class="col-md-5" hidden>
                                <div class="form-floating">
                                    <select class="form-select" id="staff_id" name="staff_id" required>
                                        @foreach ($staffs as $staff)
                                            @if ($staff->staff_id == $logged_in_staff)
                                                <option value="{{ $staff->staff_id }}" selected>
                                                    {{ $staff->staff_name }}
                                                </option>
                                            @else
                                                <option value="{{ $staff->staff_id }}">
                                                    {{ $staff->staff_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <label for="staff_id">Attendant</label>
                                    @if ($errors->has('staff_id'))
                                    <span class="text-danger">{{ $errors->first('staff_id') }}</span>
                                @endif
                                </div>
                            </div>
                            
                            <div class="col-md-4 position-relative" data-col="5">
                                <div class="form-floating">
                                    <input type="text" class="form-control filter-input" name="plate_number"
                                        placeholder="Select or type plate number..."  aria-label="Plate Number"
                                        autocomplete="off" data-dropdown="plateDropdown" value="{{ old('plate_number') }}">
                                    <label for="plate_number">Plate Number</label>
                                    @if ($errors->has('plate_number'))
                                        <span class="text-danger">{{ $errors->first('plate_number') }}</span>
                                    @endif
                                </div>
                                <ul class="dropdown-list list-group position-absolute w-100"
                                    style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;"
                                    data-dropdown="plateDropdown">
                                    @if ($facilitator_location_vehicles->isEmpty())
                                        <li class="no-records list-group-item" style="cursor: default;">No records in the location vehicles</li>
                                    @else
                                        @foreach ($facilitator_location_vehicles as $location_vehicle)
                                            @if ($location_vehicle->vehicle_id)
                                                <li class="list-group-item list-group-item-action input-item"
                                                    data-plate-number="{{ $location_vehicle->vehicle->plate_number }}"
                                                    data-vehicle-name="{{ $location_vehicle->vehicle->vehicle_name }}"
                                                    data-vehicle-type-id="{{ $location_vehicle->vehicle->vehicle_type_id }}"
                                                    data-facilitator-id="{{ $location_vehicle->facilitator->facilitator_id ?? ''}}"
                                                    data-facilitator-name="{{ $location_vehicle->facilitator->facilitator_name ?? ''}}"
                                                    data-barangay="{{ $location_vehicle->location->barangay ?? ''}}"
                                                    data-municipality="{{ $location_vehicle->location->municipality ?? ''}}"
                                                    data-province="{{ $location_vehicle->location->province ?? ''}}"
                                                    data-region="{{ $location_vehicle->location->region?? '' }}">
                                                    {{ $location_vehicle->vehicle->plate_number }} - {{ $location_vehicle->vehicle->vehicle_name??'N/A'}} ({{ $location_vehicle->facilitator->facilitator_name ?? 'N/A'}}) - {{ $location_vehicle->location->barangay?? 'N/A' }}, {{ $location_vehicle->location->municipality ?? ''}}</li>
                                            @endif
                                        @endforeach
                                    @endif
                                    <li class="no-records list-group-item" style="display: none; cursor: default;">No existing record/s for this plate number, fill the following to create a new record</li>
                                </ul>
                            </div>
                            
                            <div class="col-md-4 position-relative" data-col="5">
                                <div class="form-floating">
                                    <input type="text" class="form-control filter-input" name="origin" placeholder="Select or type origin..."
                                        autocomplete="off" data-dropdown="originDropdown"  value="{{ old('origin') }}">
                                    <label for="origin">Origin</label>
                                    <ul class="dropdown-list list-group position-absolute w-100"
                                        style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;" data-dropdown="originDropdown">
                                        @foreach ($locations as $location)
                                            <li class="list-group-item list-group-item-action input-item"
                                                data-barangay="{{ $location->barangay }}"
                                                data-municipality="{{ $location->municipality }}"
                                                data-province="{{ $location->province }}"
                                                data-region="{{ $location->region }}">{{ $location->barangay }}, {{ $location->municipality }}, {{ $location->province }}, {{ $location->region }}</li>
                                        @endforeach
                                        <li class="no-records list-group-item" style="display: none;">No matching records found.</li>
                                    </ul>
                                </div>
                            </div>
                            
                            

                            <div class="col-md-2 position-relative" data-col="6">
                                <div class="form-floating">
                                    <input type="text" class="form-control filter-input" name="commodity_name" 
                                        placeholder="Select or type commodity..." required aria-label="Commodity"
                                        autocomplete="off" data-dropdown="commodityDropdown" value="{{ old('commodity_name') }}">
                                    <label for="commodity_name">Commodity</label>
                                    @if ($errors->has('commodity_name'))
                                    <span class="text-danger">{{ $errors->first('commodity_name') }}</span>
                                @endif
                                </div>
                                <ul class="dropdown-list list-group position-absolute w-100"
                                    style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;"
                                    data-dropdown="commodityDropdown">
                                    <li class="no-records list-group-item" style="display: none; cursor: default;">Commodity
                                        does not exist in the records</li>
                                    @foreach ($commodities as $commodity)
                                        <li class="list-group-item list-group-item-action input-item">{{ $commodity->commodity_name }}</li>
                                    @endforeach
                                </ul>
                            </div>


                            
                            
                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="volume" name="volume"
                                        placeholder="Volume(kg)" value="{{ old('volume') }}" required>
                                    <label for="volume">Volume(kg)</label>
                                    @if ($errors->has('volume'))
                                    <span class="text-danger">{{ $errors->first('volume') }}</span>
                                @endif
                                </div>
                            </div>
                            
                            <p class="form-label">New Record:</p>
                            
                            <div class="col-md-2">
                                <div class="form-floating">
                                    <select class="form-select" id="vehicle_type_id" name="vehicle_type_id">
                                        <option value="" disabled selected>Select a vehicle type</option>
                                        @foreach ($vehicle_types as $vehicle_type)
                                            <option value="{{ $vehicle_type->vehicle_type_id }}" 
                                                {{ old('vehicle_type_id') == $vehicle_type->vehicle_type_id ? 'selected' : '' }}>
                                                {{ $vehicle_type->vehicle_type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="vehicle_type_id">Vehicle Type</label>
                                    @if ($errors->has('vehicle_type_id'))
                                        <span class="text-danger">{{ $errors->first('vehicle_type_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Name (optional)" value="{{ old('name') }}">
                                    <label for="name">Vehicles Name</label>
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2" >
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="barangay" name="barangay"
                                        placeholder="Barangay" value="{{ old('barangay') }}" required>
                                    <label for="barangay">Barangay</label>
                                    @if ($errors->has('barangay'))
                                    <span class="text-danger">{{ $errors->first('barangay') }}</span>
                                @endif
                                </div>
                            </div>

                            <div class="col-md-2" >
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="municipality" name="municipality"
                                        placeholder="Municipality" value="{{ old('municipality') }}" required>
                                    <label for="municipality">Municipality</label>
                                    @if ($errors->has('municipality'))
                                    <span class="text-danger">{{ $errors->first('municipality') }}</span>
                                @endif
                                </div>
                            </div>

                            <div class="col-md-2" >
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="province" name="province"
                                        placeholder="Province" value="{{ old('province') }}" required>
                                    <label for="province">Province</label>
                                    @if ($errors->has('province'))
                                    <span class="text-danger">{{ $errors->first('province') }}</span>
                                @endif
                                </div>
                            </div>

                            <div class="col-md-2" >
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="region" name="region"
                                        placeholder="Region" value="{{ old('region') }}" required>
                                    <label for="region">Region</label>
                                    @if ($errors->has('region'))
                                    <span class="text-danger">{{ $errors->first('region') }}</span>
                                @endif
                                </div>
                            </div>  
                            
                          
                            
                                 <div class="text-center">
                                <button type="submit" id="submitButton" class="btn btn-primary">Add</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <a href="{{ route('trading-inflow.index') }}" class="btn btn-danger">Back</a>
                            </div>       


                            </div>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
