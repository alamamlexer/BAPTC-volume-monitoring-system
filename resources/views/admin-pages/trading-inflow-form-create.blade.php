@extends('layouts.admin')
@section('page_title', 'Trading Inflow Form')
@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
        <h1>Form Layouts</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Trading Inflow</a></li>
                <li class="breadcrumb-item active"><a href="/">Add a new trading inflow</a></li>
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
                        <h5 class="card-title">Trading Inflow Form</h5>
                        <!-- Floating Labels Form -->
                        <form class="row g-3 " action="{{ route('trading-inflow.store') }}" method="POST">
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
                                        placeholder="Date" value="{{ old('date', date('Y-m-d')) }}" required>
                                    <label for="date">Date</label>
                                    @if ($errors->has('date'))
                                        <span class="text-danger">{{ $errors->first('date') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-control" required>
                                    <fieldset>
                                        <legend class="col-form-label col-sm-5 pt-0">Time</legend>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="time" id="inlineRadio1" value="AM"
                                                {{ old('time') == 'AM' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="inlineRadio1">AM</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="time" id="inlineRadio2" value="PM"
                                                {{ old('time') == 'PM' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="inlineRadio2">PM</label>
                                        </div>
                                    </fieldset>
                                    @if ($errors->has('time'))
                                    <span class="text-danger">{{ $errors->first('time') }}</span>
                                @endif
                                </div>
                            </div>
                            
                            <div class="col-md-4 position-relative" data-col="6">
                                <div class="form-floating">
                                    <input type="hidden" id="facilitator_id" name="facilitator_id" value="{{ old('facilitator_id') }}">
                                    <input type="text" class="form-control filter-input" id="facilitator_name" name="facilitator_name" 
                                        placeholder="Select or type facilitator..." required aria-label="Facilitator"
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
                                            data-facilitator-id="{{ $facilitator->id }}" 
                                            data-facilitator-name="{{ $facilitator->facilitator_name }}">
                                            {{ $facilitator->facilitator_name }}
                                        </li>
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
                                                    data-facilitator-id="{{ $location_vehicle->facilitator->facilitator_id }}"
                                                    data-facilitator-name="{{ $location_vehicle->facilitator->facilitator_name }}"
                                                    data-barangay="{{ $location_vehicle->location->barangay }}"
                                                    data-municipality="{{ $location_vehicle->location->municipality }}"
                                                    data-province="{{ $location_vehicle->location->province }}"
                                                    data-region="{{ $location_vehicle->location->region }}">
                                                    {{ $location_vehicle->vehicle->plate_number }} ({{ $location_vehicle->vehicle->vehicle_name }}) - {{ $location_vehicle->location->barangay }}, {{ $location_vehicle->location->municipality }}</li>
                                            @endif
                                        @endforeach
                                    @endif
                                    <li class="no-records list-group-item" style="display: none; cursor: default;">No existing record/s for this plate number, fill the following to create a new record</li>
                                </ul>
                            </div>
                            
                            <div class="col-md-4 position-relative" data-col="5">
                                <div class="form-floating">
                                    <input type="text" class="form-control filter-input" name="origin" placeholder="Select or type origin..."
                                        autocomplete="off" data-dropdown="originDropdown" required value="{{ old('origin') }}">
                                    <label for="origin">Origin</label>
                                    <ul class="dropdown-list list-group position-absolute w-100"
                                        style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;" data-dropdown="originDropdown">
                                        @foreach ($facilitator_location_vehicles as $location_vehicle)
                                            <li class="list-group-item list-group-item-action input-item"
                                                data-barangay="{{ $location_vehicle->location->barangay }}"
                                                data-municipality="{{ $location_vehicle->location->municipality }}"
                                                data-province="{{ $location_vehicle->location->province }}"
                                                data-region="{{ $location_vehicle->location->region }}">{{ $location_vehicle->location->barangay }}, {{ $location_vehicle->location->municipality }}, {{ $location_vehicle->location->province }}, {{ $location_vehicle->location->region }}</li>
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
                            
                            <p class="form-label">If there is no existing record, please fill the following:</p>
                            
                           



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
                                    <label for="name">Vehicle's Name</label>
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
                          
                            

                            
                            
                            
                            
                            

                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Form --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Inflow Table</h5>                       
    
                        <div class="row mb-3">
                            <div class="col-auto">
                                <form action="{{ route('trading-inflow.submit') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="temporary"> <!-- You can set this if needed -->
                                    <button type="submit" class="btn btn-success">
                                        Submit 
                                    </button>
                                </form>
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
                                                <label for="productionOriginFilter" class="form-label" style="margin-right: 5px;">Facilitator:</label>
                                                <select id="productionOriginFilter" class="form-select" 
                                                        style="border: none; font-weight: bold;">
                                                    <option value="">All</option>
                                                    @foreach ($facilitators as $facilitators)
                                                        <option value="{{ $facilitators['facilitator_id'] }}">{{ $facilitators['facilitator_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </th>
                                        
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
                                    @if($temporary_transactions->isEmpty())
                                        <tr>
                                            <td colspan="10" class="text-center">No records available</td>
                                        </tr>
                                    @else
                                        @foreach ($temporary_transactions as $temporary_transaction)
                                        <tr data-date="{{ $temporary_transaction->date }}" data-am-pm="{{ $temporary_transaction->time }}" data-attendant="{{ $temporary_transaction->staff->staff_id }}" data-commodity="{{ $temporary_transaction->commodity->commodity_id }}" data-production-origin="{{ $temporary_transaction->barangay }}">
                                            <td>{{ $temporary_transaction->id }}</td>
                                            <td>{{ $temporary_transaction->date }}</td>
                                            <td>{{ $temporary_transaction->time }}</td>
                                            <td>{{ $temporary_transaction->staff->staff_name }}</td>
                                            <td>{{ $temporary_transaction->plate_number }}</td>
                                            <td>{{ $temporary_transaction->name }}</td>
                                            <td>{{ $temporary_transaction->commodity->commodity_name }}</td>
                                            <td>{{ $temporary_transaction->volume }}</td>
                                            <td>{{ $temporary_transaction->facilitator->facilitator_name?? 'N/A'}}</td>
                                            <td>{{ $temporary_transaction->barangay }}, {{ $temporary_transaction->municipality }}, {{ $temporary_transaction->province }}, {{ $temporary_transaction->region }}</td>
                                            <td>
                                                <a href="{{ route('trading-inflow.edit', $temporary_transaction->id) }}" class="btn btn-outline-primary m-1">
                                                    <i class="bx bxs-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('trading-inflow.destroy', $temporary_transaction->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this reservation?')">
                                                        <i class="bx bxs-trash-alt"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item {{ $temporary_transactions->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="#" data-page="{{ $temporary_transactions->currentPage() - 1 }}">Previous</a>
                                </li>
                                
                                @for ($i = 1; $i <= $temporary_transactions->lastPage(); $i++)
                                    <li class="page-item {{ $i == $temporary_transactions->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                <li class="page-item {{ $temporary_transactions->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="#" data-page="{{ $temporary_transactions->currentPage() + 1 }}">Next</a>
                                </li>
                            </ul>
                        </nav>
                        
                    </div>
                </div>
            </div>
        </div>


    

                            {{--  Submition status Modal  --}}
                            @if (session('success'))
                                <!-- Success Modal -->
                                <div class="modal fade" id="successModal" tabindex="-1"
                                    aria-labelledby="successModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body text-center">
                                                <i class="bi bi-check-circle-fill text-success"
                                                    style="font-size: 4rem;"></i>
                                                <p class="mt-3">{{ session('success') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (session('error'))
                                <!-- Error Modal -->
                                <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel"
                                    aria-hidden="true">
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

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            
        const amPmFilter = document.getElementById('amPmFilter');
        const attendantFilter = document.getElementById('attendantFilter');
        const commodityFilter = document.getElementById('commodityFilter');
        const productionOriginFilter = document.getElementById('productionOriginFilter');
        const facilitatorFilter = document.getElementById('facilitatorFilter'); // Facilitator Filter
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const tableRows = document.querySelectorAll('#inflowTableBody tr');

    function filterTable() {
        const selectedAmPm = amPmFilter.value;
        const selectedAttendant = attendantFilter.value;
        const selectedCommodity = commodityFilter.value;
        const selectedProductionOrigin = productionOriginFilter.value;
        const selectedFacilitator = facilitatorFilter.value; // New Facilitator Filter
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        tableRows.forEach(row => {
            const rowDate = new Date(row.dataset.date);
            const rowAmPm = row.dataset.amPm;
            const rowAttendant = row.dataset.attendant;
            const rowCommodity = row.dataset.commodity;
            const rowProductionOrigin = row.dataset.productionOrigin;
            const rowFacilitator = row.dataset.facilitator; // New Facilitator Data

            const isDateInRange =
                (!startDateInput.value || rowDate >= startDate) &&
                (!endDateInput.value || rowDate <= endDate);
            const isAmPmMatch = !selectedAmPm || rowAmPm.startsWith(selectedAmPm);
            const isAttendantMatch = !selectedAttendant || rowAttendant === selectedAttendant;
            const isCommodityMatch = !selectedCommodity || rowCommodity === selectedCommodity;
            const isProductionOriginMatch =
                !selectedProductionOrigin || rowProductionOrigin.includes(selectedProductionOrigin);
            const isFacilitatorMatch = !selectedFacilitator || rowFacilitator === selectedFacilitator;

            if (
                isDateInRange &&
                isAmPmMatch &&
                isAttendantMatch &&
                isCommodityMatch &&
                isProductionOriginMatch &&
                isFacilitatorMatch
            ) {
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
    facilitatorFilter.addEventListener('change', filterTable); // Facilitator Filter Listener
    startDateInput.addEventListener('change', filterTable);
    endDateInput.addEventListener('change', filterTable);

    // Reset filters button
    document.getElementById('resetFilters').addEventListener('click', () => {
        amPmFilter.value = '';
        attendantFilter.value = '';
        commodityFilter.value = '';
        productionOriginFilter.value = '';
        facilitatorFilter.value = ''; // Reset Facilitator Filter
        startDateInput.value = '';
        endDateInput.value = '';
        filterTable(); // Apply reset

                });
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Success and Error Message Modals
                @if (session('success'))
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
                setTimeout(() => successModal.hide(), 1000);
                @endif
            
                @if (session('error'))
                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
                setTimeout(() => errorModal.hide(), 1000);
                @endif
            
                // Auto select AM or PM radio button
                const currentHour = new Date().getHours();
                document.getElementById(currentHour < 12 ? 'inlineRadio1' : 'inlineRadio2').checked = true;
            
                // Filterable Dropdown Setup
                function setupFilterableDropdown(inputSelector, dropdownSelector, autofillFields = {}, autofillCallback = null) {
                    const input = document.querySelector(inputSelector);
                    const dropdown = document.querySelector(dropdownSelector);
                    const items = dropdown.querySelectorAll('.input-item');
            
                    input.addEventListener('input', function () {
                        const searchValue = input.value.toLowerCase();
                        let hasRecord = false;
            
                        items.forEach(item => {
                            if (item.textContent.toLowerCase().includes(searchValue)) {
                                item.style.display = 'block';
                                hasRecord = true;
                            } else {
                                item.style.display = 'none';
                            }
                        });
            
                        dropdown.querySelector('.no-records').style.display = hasRecord ? 'none' : 'block';
                        dropdown.style.display = searchValue ? 'block' : 'none';
                    });
            
                    items.forEach(item => {
                        item.addEventListener('click', function () {
                            input.value = item.textContent.trim(); // Set input value
                            dropdown.style.display = 'none'; // Hide dropdown
            
                            // Autofill other fields
                            for (const [fieldId, dataAttr] of Object.entries(autofillFields)) {
                                const field = document.getElementById(fieldId);
                                if (field) field.value = item.getAttribute(`data-${dataAttr}`) || '';
                            }
            
                            // Execute additional autofill logic if provided (e.g., origin autofill)
                            if (autofillCallback) autofillCallback(item);
                        });
                    });
            
                    input.addEventListener('blur', function () {
                        setTimeout(() => dropdown.style.display = 'none', 300); // Allow time for clicks
                    });
            
                    document.addEventListener('click', function (event) {
                        if (!input.contains(event.target) && !dropdown.contains(event.target)) {
                            dropdown.style.display = 'none';
                        }
                    });
                }
            
                // Autofill Plate Number and Origin Fields from Selected Item
                function autofillPlateAndOrigin(item) {
    const plateNumberInput = document.querySelector('.filter-input[data-dropdown="plateDropdown"]');
    const originInput = document.querySelector('.filter-input[data-dropdown="originDropdown"]');
    const vehicleTypeSelect = document.getElementById('vehicle_type_id');
    const vehicleNameInput = document.getElementById('name');
    const facilitatorSelect = document.querySelector('.filter-input[data-dropdown="facilitatorDropdown"]'); // Get the facilitator input

    // Autofill Plate Number
    if (plateNumberInput) {
        plateNumberInput.value = item.getAttribute('data-plate-number') || '';
    }

    // Autofill Origin Fields
    const barangay = item.getAttribute('data-barangay') || '';
    const municipality = item.getAttribute('data-municipality') || '';
    const province = item.getAttribute('data-province') || '';
    const region = item.getAttribute('data-region') || '';

    if (originInput) {
        originInput.value = `${barangay}, ${municipality}, ${province}, ${region}`;
    }

    // Autofill Vehicle Type and Vehicle Name
    const vehicleTypeId = item.getAttribute('data-vehicle-type-id') || '';
    const vehicleName = item.getAttribute('data-vehicle-name') || '';

    if (vehicleTypeSelect) {
        vehicleTypeSelect.value = vehicleTypeId; // Set vehicle type
    }
    if (vehicleNameInput) {
        vehicleNameInput.value = vehicleName; // Set vehicle name
    }

    // Autofill Facilitator details
    if (facilitatorSelect) {
        facilitatorSelect.value = item.getAttribute('data-facilitator-name') || ''; // Set facilitator name
    }

    // Autofill Facilitator ID
    const facilitatorIdInput = document.getElementById('facilitator_id'); // Make sure this input exists
    if (facilitatorIdInput) {
        facilitatorIdInput.value = item.getAttribute('data-facilitator-id') || ''; // Set facilitator ID
    }

    // Populate hidden individual fields
    document.getElementById('barangay').value = barangay;
    document.getElementById('municipality').value = municipality;
    document.getElementById('province').value = province;
    document.getElementById('region').value = region;
}
    // Initialize Plate Number Dropdown with Autofill
    setupFilterableDropdown(
        '.filter-input[data-dropdown="plateDropdown"]',
        '.dropdown-list[data-dropdown="plateDropdown"]',
        {
            'plate_number': 'plate-number',
            'barangay': 'barangay',
            'municipality': 'municipality',
            'province': 'province',
            'region': 'region',
            'vehicle_name': 'vehicle-name',
            'vehicle_type_id': 'vehicle-type-id',
            'facilitator_id': 'facilitator-id',
            'facilitator_name': 'facilitator-name'
        },
        autofillPlateAndOrigin // Autofill both plate and origin when selected
    );
                // Initialize Origin Dropdown (if the user needs to modify the address independently)
                setupFilterableDropdown(
                    '.filter-input[data-dropdown="originDropdown"]',
                    '.dropdown-list[data-dropdown="originDropdown"]',
                    {
                        'barangay': 'barangay',
                        'municipality': 'municipality',
                        'province': 'province',
                        'region': 'region'
                    }
                );
            
                // Initialize Commodity Dropdown (if needed)
                setupFilterableDropdown(
                    '.filter-input[data-dropdown="commodityDropdown"]',
                    '.dropdown-list[data-dropdown="commodityDropdown"]'
                );
                
                // Initialize Facilitator Dropdown 
                setupFilterableDropdown(
                    '.filter-input[data-dropdown="facilitatorDropdown"]',
                    '.dropdown-list[data-dropdown="facilitatorDropdown"]'
    );
            });

        </script>
            
    </section>

@endsection
