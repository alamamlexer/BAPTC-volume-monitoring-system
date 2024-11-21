@extends('layouts.admin')
@section('page_title', 'Special Records Form')
@section('content')

<!-- Page Title -->
<div class="pagetitle">
    <h1>Special Record</h1>
    <nav>
        <ol class="breadcrumb">
           <li class="breadcrumb-item active">Add a new trading inflow</li>
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
                    <h5 class="card-title">Transaction Form</h5>
                    <!-- Floating Labels Form -->
                    <form class="row g-3 " action="{{ route('special-records.store') }}" method="POST">
                        @csrf

                        {{-- input for transaction_status = "trading"  --}}
                        <div class="col-md-4" hidden>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="transaction_status"
                                    name="transaction_status" value="temporary">
                                <label for="transaction_status"></label>
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
                                <select class="form-select" id="time" name="time" required>
                                    <option value="AM" {{ old('time', $defaultTime) == 'AM' ? 'selected' : '' }}>AM</option>
                                    <option value="PM" {{ old('time', $defaultTime) == 'PM' ? 'selected' : '' }}>PM</option>
                                </select>
                                <label for="time">Time</label>
                                @if ($errors->has('time'))
                                <span class="text-danger">{{ $errors->first('time') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" id="transaction_type" name="transaction_type" required>
                                    <option value="dry" {{ old('time', $defaultTime) == 'AM' ? 'selected' : '' }}>Dry</option>
                                    <option value="cold" {{ old('time', $defaultTime) == 'PM' ? 'selected' : '' }}>Cold</option>
                                    <option value="washing" {{ old('time', $defaultTime) == 'AM' ? 'selected' : '' }}>Washing</option>
                                    <option value="intertrading" {{ old('time', $defaultTime) == 'AM' ? 'selected' : '' }}>Intertrading</option>
                                </select>
                                <label for="time">Transaction type</label>
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
                                <input type="text" class="form-control filter-input" name="origin" placeholder="Select or type origin..."
                                    autocomplete="off" data-dropdown="originDropdown" value="{{ old('origin') }}">
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
                                <input type="number" class="form-control" id="volume" name="volume"
                                    placeholder="Volume(kg)" value="{{ old('volume') }}" required min="1" step="1">
                                <label for="volume">Volume(kg)</label>
                                @if ($errors->has('volume'))
                                    <span class="text-danger">{{ $errors->first('volume') }}</span>
                                @endif
                            </div>
                        </div>

                       <p class="form-label">Please provide the following information for new origins:</p>

                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="barangay" name="barangay"
                                    placeholder="Barangay" value="{{ old('barangay') }}" >
                                <label for="barangay">Barangay</label>
                                @if ($errors->has('barangay'))
                                <span class="text-danger">{{ $errors->first('barangay') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="municipality" name="municipality"
                                    placeholder="Municipality" value="{{ old('municipality') }}" >
                                <label for="municipality">Municipality</label>
                                @if ($errors->has('municipality'))
                                <span class="text-danger">{{ $errors->first('municipality') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="province" name="province"
                                    placeholder="Province" value="{{ old('province') }}" >
                                <label for="province">Province</label>
                                @if ($errors->has('province'))
                                <span class="text-danger">{{ $errors->first('province') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="region" name="region"
                                    placeholder="Region" value="{{ old('region') }}" >
                                <label for="region">Region</label>
                                @if ($errors->has('region'))
                                <span class="text-danger">{{ $errors->first('region') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-secondary">Clear All</button>
                            <a href="{{ route('special-records.index') }}" class="btn btn-danger">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- End Form --}}
    {{-- Submition status Modal  --}}
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
            document.getElementById('volume').addEventListener('input', function (e) {
        let volumeValue = e.target.value;
        
        // Check if the value is a whole number and >= 1
        if (volumeValue < 1 || !Number.isInteger(Number(volumeValue))) {
            e.target.setCustomValidity('Please enter a whole number greater than or equal to 1.');
        } else {
            e.target.setCustomValidity(''); // Valid input
        }
    });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success and Error Message Modals
            @if(session('success'))
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            setTimeout(() => successModal.hide(), 1000);
            @endif

            @if(session('error'))
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
            setTimeout(() => errorModal.hide(), 1000);
            @endif

            // Store the selected time in local storage
            document.querySelector('form').addEventListener('reset', function() {
                const selectedTime = document.querySelector('input[name="time"]:checked');
                if (selectedTime) {
                    localStorage.setItem('selectedTime', selectedTime.value);
                }
            });

            // Restore the selected time when the page loads
            window.addEventListener('load', function() {
                const savedTime = localStorage.getItem('selectedTime');
                if (savedTime) {
                    document.querySelector(`input[name="time"][value="${savedTime}"]`).checked = true;
                }
            });

            // Filterable Dropdown Setup
            function setupFilterableDropdown(inputSelector, dropdownSelector, autofillFields = {}, autofillCallback = null) {
                const input = document.querySelector(inputSelector);
                const dropdown = document.querySelector(dropdownSelector);
                const items = dropdown.querySelectorAll('.input-item');

                input.addEventListener('input', function() {
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
                    item.addEventListener('click', function() {
                        input.value = item.textContent.trim(); // Set input value
                        dropdown.style.display = 'none'; // Hide dropdown

                        // Autofill other fields
                        for (const [fieldId, dataAttr] of Object.entries(autofillFields)) {
                            const field = document.getElementById(fieldId);
                            if (field) field.value = item.getAttribute(`data-${dataAttr}`) || '';
                        }

                        // Execute additional autofill logic if provided
                        if (autofillCallback) autofillCallback(item);
                    });
                });

                input.addEventListener('blur', function() {
                    setTimeout(() => dropdown.style.display = 'none', 300); // Allow time for clicks
                });

                document.addEventListener('click', function(event) {
                    if (!input.contains(event.target) && !dropdown.contains(event.target)) {
                        dropdown.style.display = 'none';
                    }
                });
            }

            // Autofill Origin Fields from Selected Item
            function autofillOrigin(item) {
                const originInput = document.querySelector('.filter-input[data-dropdown="originDropdown"]');

                // Autofill Origin Fields
                const barangay = item.getAttribute('data-barangay') || '';
                const municipality = item.getAttribute('data-municipality') || '';
                const province = item.getAttribute('data-province') || '';
                const region = item.getAttribute('data-region') || '';

                if (originInput) {
                    originInput.value = `${barangay}, ${municipality}, ${province}, ${region}`;
                }

                // Populate hidden individual fields
                document.getElementById('barangay').value = barangay;
                document.getElementById('municipality').value = municipality;
                document.getElementById('province').value = province;
                document.getElementById('region').value = region;
            }

            // Initialize Origin Dropdown
            setupFilterableDropdown(
                '.filter-input[data-dropdown="originDropdown"]',
                '.dropdown-list[data-dropdown="originDropdown"]', {
                    'barangay': 'barangay',
                    'municipality': 'municipality',
                    'province': 'province',
                    'region': 'region'
                },
                autofillOrigin // Autofill origin when selected
            );

            // Initialize Facilitator Dropdown
            setupFilterableDropdown(
                '.filter-input[data-dropdown="facilitatorDropdown"]',
                '.dropdown-list[data-dropdown="facilitatorDropdown"]', {
                    'facilitator_id': 'facilitator-id',
                    'facilitator_name': 'facilitator-name'
                },
                function(item) {
                    const facilitatorIdInput = document.getElementById('facilitator_id');
                    const facilitatorNameInput = document.getElementById('facilitator_name');

                    if (facilitatorNameInput) {
                        facilitatorNameInput.value = item.getAttribute('data-facilitator-name') || '';
                    }
                    if (facilitatorIdInput) {
                        facilitatorIdInput.value = item.getAttribute('data-facilitator-id') || '';
                    }
                }
            );

            // Initialize Commodity Dropdown (if needed)
            setupFilterableDropdown(
                '.filter-input[data-dropdown="commodityDropdown"]',
                '.dropdown-list[data-dropdown="commodityDropdown"]'
            );
        });

        // Check Address Fields
        document.addEventListener('DOMContentLoaded', function() {
            const barangay = document.getElementById('barangay');
            const municipality = document.getElementById('municipality');
            const province = document.getElementById('province');
            const region = document.getElementById('region');
            const origin = document.getElementById('origin');

            const checkAddressFields = () => {
                const allFilled = [barangay, municipality, province, region].every(input => input.value.trim() !== '');
                if (allFilled) {
                    origin.removeAttribute('required');
                } else {
                    origin.setAttribute('required', 'required');
                }
            };

            // Add event listeners to address fields
            [barangay, municipality, province, region].forEach(input => {
                input.addEventListener('input', checkAddressFields);
            });

            // Run the check on page load in case fields are pre-filled
            checkAddressFields();
        });
    </script>


</section>

@endsection