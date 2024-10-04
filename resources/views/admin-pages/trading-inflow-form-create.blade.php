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
                                        name="transaction_status" value="regular">
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

                            <div class="col-md-5">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date"
                                        placeholder="Date" value="<?php echo date('Y-m-d'); ?>" required>
                                    <label for="date">Date</label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-control" required>
                                    <fieldset>
                                        <legend class="col-form-label col-sm-3 pt-0">Time</legend>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="time" id="inlineRadio1"
                                                value="AM">
                                            <label class="form-check-label" for="inlineRadio1">AM</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="time" id="inlineRadio2"
                                                value="PM">
                                            <label class="form-check-label" for="inlineRadio2">PM</label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="col-md-5">
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
                                </div>
                            </div>

                            <div class="col-md-6 position-relative" data-col="6">
                                <div class="form-floating">
                                    <input type="text" class="form-control filter-input" name="commodity_name"
                                        placeholder="Select or type commodity..." required aria-label="Commodity"
                                        autocomplete="off" data-dropdown="commodityDropdown">
                                    <label for="commodity_name">Commodity</label>
                                </div>
                                <ul class="dropdown-list list-group position-absolute w-100"
                                    style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;"
                                    data-dropdown="commodityDropdown">
                                    <li class="no-records list-group-item" style="display: none; cursor: default;">Commodity
                                        does not exist in the records</li>
                                    @foreach ($commodities as $commodity)
                                        <li class="list-group-item list-group-item-action input-item">
                                            {{ $commodity->commodity_name }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="volume" name="volume"
                                        placeholder="Volume(kg)" required>
                                    <label for="volume">Volume(kg)</label>
                                </div>
                            </div>

                            <div class="col-md-5 position-relative" data-col="5">
                                <div class="form-floating">
                                    <input type="text" class="form-control filter-input" name="plate_number"
                                        placeholder="Select or type plate number..." required aria-label="Plate Number"
                                        autocomplete="off" data-dropdown="plateDropdown">
                                    <label for="plate_number">Plate Number</label>
                                </div>
                                <ul class="dropdown-list list-group position-absolute w-100"
                                    style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;"
                                    data-dropdown="plateDropdown">
                                    @if ($location_vehicles->isEmpty())
                                        <li class="no-records list-group-item" style="cursor: default;">No records in the
                                            location vehicles</li>
                                    @else
                                        @foreach ($location_vehicles as $location_vehicle)
                                            @if ($location_vehicle->vehicle_id)
                                                <li class="list-group-item list-group-item-action input-item"
                                                    data-plate-number="{{ $location_vehicle->vehicle->plate_number }}"
                                                    data-name="{{ $location_vehicle->vehicle->vehicle_name }}"
                                                    data-vehicle_type_id="{{ $location_vehicle->vehicle->vehicle_type_id }}"
                                                    data-barangay="{{ $location_vehicle->location->barangay }}"
                                                    data-municipality="{{ $location_vehicle->location->municipality }}"
                                                    data-province="{{ $location_vehicle->location->province }}"
                                                    data-region="{{ $location_vehicle->location->region }}">
                                                    {{ $location_vehicle->vehicle->plate_number }}
                                                    ({{ $location_vehicle->vehicle->vehicle_name }}) -
                                                    {{ $location_vehicle->location->barangay }}
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif
                                    <li class="no-records list-group-item" style="display: none; cursor: default;">No
                                        existing record/s for this plate number, fill the following to create a new record
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-2">
                                <div class="form-floating">
                                    <select class="form-select" id="vehicle_type_id" name="vehicle_type_id" required>
                                        @foreach ($vehicle_types as $vehicle_type)
                                            <option value="{{ $vehicle_type->vehicle_type_id }}">
                                                {{ $vehicle_type->vehicle_type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="vehicle_type">Vehicle Type</label>
                                </div>
                            </div>



                            <div class="col-md-5">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Name(optional)">
                                    <label for="name">Name(optional)</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="barangay" name="barangay"
                                        placeholder="Barangay" required>
                                    <label for="barangay">Barangay</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="municipality" name="municipality"
                                        placeholder="Municipality" required>
                                    <label for="municipality">Municipality</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="province" name="province"
                                        placeholder="Province" required>
                                    <label for="province">Province</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="region" name="region"
                                        placeholder="Region" required>
                                    <label for="region">Region</label>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <a href="{{ route('trading-inflow.index') }}" class="btn btn-danger">Back</a>
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
                                //Success and Error Message

                                document.addEventListener('DOMContentLoaded', function() {
                                    // Check if there's a success message in the session
                                    @if (session('success'))
                                        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                                        successModal.show();
                                        setTimeout(function() {
                                            successModal.hide();
                                        }, 1000); // 1 second timeout
                                    @endif
                                    // Check if there's an error message in the session
                                    @if (session('error'))
                                        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                                        errorModal.show();
                                        setTimeout(function() {
                                            errorModal.hide();
                                        }, 1000); // 1 second timeout
                                    @endif

                                    //Auto select for AM and PM

                                    const currentHour = new Date().getHours();

                                    if (currentHour < 12) {
                                        document.getElementById('inlineRadio1').checked = true;
                                    } else {
                                        document.getElementById('inlineRadio2').checked = true;
                                    }

                                });

                                //Dropdown for the input field where user can type
                                function setupFilterableDropdown(inputSelector, dropdownSelector, autofillFields = {}) {
                                    const input = document.querySelector(inputSelector);
                                    const dropdown = document.querySelector(dropdownSelector);
                                    const items = dropdown.querySelectorAll('.input-item');

                                    input.addEventListener('input', function() {
                                        const value = input.value.toLowerCase();
                                        let hasRecord = false;

                                        items.forEach(item => {
                                            if (item.textContent.toLowerCase().includes(value)) {
                                                item.style.display = 'block';
                                                hasRecord = true;
                                            } else {
                                                item.style.display = 'none';
                                            }
                                        });

                                        // Show or hide 'no records' message
                                        dropdown.querySelector('.no-records').style.display = hasRecord ? 'none' : 'block';
                                        // Show dropdown only if there's input
                                        dropdown.style.display = value ? 'block' : 'none';
                                    });

                                    // Handle item selection
                                    items.forEach(item => {
                                        item.addEventListener('click', function() {
                                            // Check if it's a commodity or plate number based on the input selector
                                            if (inputSelector.includes('commodityDropdown')) {
                                                input.value = item
                                                .textContent; // Set input value to the selected item's full name for commodity
                                            } else if (inputSelector.includes('plateDropdown')) {
                                                const plateNumber = item.getAttribute('data-plate-number'); // Get the plate number
                                                input.value =
                                                plateNumber; // Set input value to the selected item's plate number only
                                            }

                                            dropdown.style.display = 'none'; // Hide dropdown after selection

                                            // Autofill other fields only for plate number
                                            if (inputSelector.includes('plateDropdown')) {
                                                for (const [fieldId, dataAttr] of Object.entries(autofillFields)) {
                                                    const field = document.getElementById(fieldId);
                                                    if (field) {
                                                        const value = item.getAttribute(
                                                        `data-${dataAttr}`); // Access data attribute correctly
                                                        // Set the value for other types of fields
                                                        field.value = value || '';
                                                    }
                                                }
                                            }
                                        });
                                    });

                                    // Hide dropdown when input loses focus
                                    input.addEventListener('blur', function() {
                                        setTimeout(() => { // Timeout to allow the click event to register
                                            dropdown.style.display = 'none';
                                        }, 100); // Adjust timeout as necessary
                                    });

                                    // Hide dropdown when clicking outside
                                    document.addEventListener('click', function(event) {
                                        if (!input.contains(event.target) && !dropdown.contains(event.target)) {
                                            dropdown.style.display = 'none';
                                        }
                                    });
                                }

                                // Initialize the filterable dropdowns
                                document.addEventListener("DOMContentLoaded", function() {
                                    setupFilterableDropdown(
                                        '.filter-input[data-dropdown="plateDropdown"]',
                                        '.dropdown-list[data-dropdown="plateDropdown"]', {
                                            // Autofill the fields based on selected plate number
                                            'name': 'name',
                                            'vehicle_type_id': 'vehicle_type_id',
                                            'barangay': 'barangay',
                                            'municipality': 'municipality',
                                            'province': 'province',
                                            'region': 'region'
                                        }
                                    );

                                    setupFilterableDropdown(
                                        '.filter-input[data-dropdown="commodityDropdown"]',
                                        '.dropdown-list[data-dropdown="commodityDropdown"]', {}
                                    );
                                });
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Form --}}


    </section>

@endsection
