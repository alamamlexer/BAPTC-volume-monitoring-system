@extends('layouts.staff')
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
                        <form class="row g-3 " action="{{ route('staff-trading-inflow.store') }}" method="POST">
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

                            
                            <div class="text-center">
                                <button type="submit" id="submitButton" class="btn btn-primary">Add</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <a href="{{ route('staff-trading-inflow.index') }}" class="btn btn-danger">Back</a>
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
                                <form action="{{ route('staff-trading-inflow.submit') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="temporary"> <!-- You can set this if needed -->
                                    {{-- <button type="submit" class="btn btn-success">
                                        Submit 
                                    </button> --}}
                                    <button type="submit" class="btn btn-success">
                                        Submit 
                                    </button>
                                </form>

                                    <div class="form-floating">
                                        <form action="{{ route('staff-trading-inflow.import') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <!-- Responsive column for file input and Import button -->
                                                <div class="col-md-6 col-sm-12 d-flex align-items-center">
                                                    <input type="file" name="file" id="file" class="form-control me-2" required>
                                                </div>
                                                <div class="col-md-3 col-sm-6 mt-2 mt-md-0">
                                                    <button type="submit" class="btn btn-primary w-100">Import</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                            </div>
                        </div>
    
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Date</th>
                                        
                                        <th scope="col">
                                            <div class="d-flex align-items-center">
                                                <label for="timeFilter" style="margin-right: 10px;">Time:</label>
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
                                                <label for="commodityFilter" style="margin-right: 10px;">Commodity:</label>
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
                                                <label for="municipalityFilter" style="margin-right: 10px;">Origin:</label>
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
                                                <label for="productionOriginFilter" class="form-label" style="margin-right: 5px;"> Market Facilitator</label>
                                                <select id="productionOriginFilter" class="form-select" 
                                                        style="border: none; font-weight: bold;" hidden>
                                                    <option value="">All</option>
                                                    @foreach ($facilitators as $facilitators)
                                                        <option value="{{$facilitators['facilitator_id'] }}">{{$facilitators['facilitator_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </th>
                                        
                                        <th scope="col">
                                            <div class="d-flex align-items-center">
                                                <label for="staffFilter" style="margin-right: 10px;">TOA/TOI:</label>
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
                        
                        
                        <div id="paginationLinks">
                        </div>    
                        
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
            
                
                // Store the selected time in local storage
                document.querySelector('form').addEventListener('reset', function() {
                    // Get the selected radio button value
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

    // Autofill Facilitator ID
    const facilitatorIdInput = document.getElementById('facilitator_id'); // Make sure this input exists
    if (facilitatorSelect) {
        facilitatorSelect.value = item.getAttribute('data-facilitator-name') || ''; // Set facilitator name
    }
    
    // Autofill Facilitator ID
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
            <script>
                // Store the selected time in local storage
                document.querySelector('form').addEventListener('reset', function() {
                    // Get the selected radio button value
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
            </script>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
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

        // Construct the AJAX URL
        const url = "{{ route('staff-trading-inflow.create') }}"; // Make sure to replace this with your route

        // Create query parameters
        const queryParams = new URLSearchParams({
            time_filter: timeFilter,
            staff_id: staffFilter,
            commodity_filter: commodityFilter,
            municipality_filter: municipalityFilter,
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
                    console.log('Index:', index)
                    tableBody.innerHTML += `
                        <tr data-date="${transaction.date}" data-am-pm="${transaction.time}" data-attendant="${transaction.staff_id}" data-commodity="${transaction.commodity_id}" data-production-origin="${transaction.barangay}">
                            <td>${index + 1}</td>
                            <td>${transaction.date}</td>
                            <td>${transaction.time}</td>
                            <td>${transaction.plate_number ?? 'N/A'}</td>
                            <td>${transaction.name ?? 'N/A'}</td>
                            <td>${transaction.commodity.commodity_name}</td>
                            <td>${transaction.volume}</td>
                            <td>${transaction.barangay}, ${transaction.municipality}, ${transaction.province}, ${transaction.region}</td>
                            <td>${transaction.facilitator?.facilitator_name ?? 'N/A'}</td>
                            <td>${transaction.staff.staff_name}</td>
                            <td>
                                <a href="/staff-trading-inflow/${transaction.id}/edit" class="btn btn-outline-primary m-1">
                                    <i class="bx bxs-edit"></i> Edit
                                </a>
                                <form action="/staff-trading-inflow/${transaction.id}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                        <i class="bx bxs-trash-alt"></i> Delete
                                    </button>
                                </form>
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
    </section>

@endsection