@extends('layouts.admin')
@section('page_title', 'Special Records')

@section('content')

<div class="pagetitle">
    <h1>Special Records</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="/">Special Records</a></li>
        </ol>
    </nav>
</div>

<section class="section dashboard">

    <div class="row">


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Inflow Table</h5>
                        <!-- Filter Row -->
                        <div class="row mb-3">
                          <form method="GET" action="{{ route('special-records.index') }}" class="mb-3">
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
                                                <label for="transactionFilter" style="margin-right: 10px;">Type</label>
                                                <select name="transaction_filter" id="transactionFilter" class="form-select" style="border: none; font-weight: bold;" onchange="filterByTransaction()">
                                                    <option value="">All</option>
                                                    @foreach ($transaction_types as $transaction_type)
                                                        <option value="{{ $transaction_type }}" {{ request('transaction_filter') == $transaction_type ? 'selected' : '' }}>
                                                            {{ $transaction_type}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </th>
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
                                <a href="{{ route('special-records.create') }}" class="btn btn-primary">Add New Special record</a>
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

</section>

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
            transaction_filter: transactionFilter,
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
                            <td>${transaction.transaction_type}</td>

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
    function filterByTransaction() {
        fetchFilteredData();
    }
    
    
   </script>

@endsection
