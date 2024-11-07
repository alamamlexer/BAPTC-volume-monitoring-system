@extends('layouts.staff')

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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Inflow Table</h5>

                    <!-- Filter Row for Date Range -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $startDate) }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $endDate) }}">
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
                                            <label for="transactionFilter" style="margin-right: 10px;">Type</label>
                                            <select id="transactionFilter" class="form-select" onchange="fetchFilteredData()">
                                                <option value="">All Types</option>
                                                @foreach ($transaction_types as $transaction_type)
                                                    <option value="{{ $transaction_type }}" {{ request('transaction_filter') == $transaction_type ? 'selected' : '' }}>{{ $transaction_type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div class="d-flex align-items-center">
                                            <label for="commodityFilter" style="margin-right: 10px;">Commodity</label>
                                            <select id="commodityFilter" class="form-select" onchange="fetchFilteredData()">
                                                <option value="">All Commodities</option>
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
                                            <select id="municipalityFilter" class="form-select" onchange="fetchFilteredData()">
                                                <option value="">All Origins</option>
                                                @foreach ($municipalities as $municipality)
                                                    <option value="{{ $municipality }}" {{ request('municipality_filter') == $municipality ? 'selected' : '' }}>
                                                        {{ $municipality }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    <th scope="col">Market Facilatator</th>
                                    <th scope="col">TOA/TOI</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="TableBody">
                                <!-- Data will be dynamically loaded here by JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3">
                            <div class="col-sm-10">
                                <a href="{{ route('staff-special-record.create') }}" class="btn btn-primary">Add New Special record</a>
                            </div>
                        </div>

                    <div id="paginationLinks">
                        <!-- Pagination links will be dynamically loaded here by JS -->
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
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <p class="mt-3">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
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
        fetchFilteredData();  // Call the function to fetch initial data
    });

    function fetchFilteredData(page = 1) {
        // Get selected filter values
        const transactionFilter = document.getElementById('transactionFilter').value;
        const commodityFilter = document.getElementById('commodityFilter').value;
        const municipalityFilter = document.getElementById('municipalityFilter').value;
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        // Construct the AJAX URL
        const url = "{{ route('staff-special-record.index') }}";

        // Create query parameters
        const queryParams = new URLSearchParams({
            transaction_filter: transactionFilter,
            commodity_filter: commodityFilter,
            municipality_filter: municipalityFilter,
            start_date: startDate,
            end_date: endDate,
            page: page
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
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No records found.</td></tr>';
            } else {
                data.data.forEach((transaction, index) => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${(data.current_page - 1) * 5 + (index + 1)}</td>
                            <td>${transaction.date || 'N/A'}</td>
                            <td>${transaction.transaction_type || 'N/A'}</td>
                            <td>${transaction.commodity?.commodity_name ?? 'N/A' }</td>
                            <td>${transaction.volume}</td>
                            <td>${transaction.barangay || transaction.municipality || transaction.province || transaction.region 
                                 ? `${transaction.barangay}, ${transaction.municipality}, ${transaction.province}, ${transaction.region}`
                                    : 'N/A'}
                            </td>
                            <td>${transaction.facilitator?.facilitator_name ?? 'N/A'}</td>
                            <td>${transaction.staff.staff_name}</td>
                            <td>
                                <a href="/staff-special-record/${transaction.id}/edit" class="btn btn-outline-primary m-1">
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
            startPage = 1;
            endPage = lastPage;
        } else {
            const halfMaxPages = Math.floor(maxPagesToShow / 2);
            if (currentPage <= halfMaxPages) {
                startPage = 1;
                endPage = maxPagesToShow;
            } else if (currentPage + halfMaxPages >= lastPage) {
                startPage = lastPage - maxPagesToShow + 1;
                endPage = lastPage;
            } else {
                startPage = currentPage - halfMaxPages;
                endPage = currentPage + halfMaxPages;
            }
        }

        const paginationHtml = `
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="fetchFilteredData(${currentPage - 1}); return false;">Previous</a>
                    </li>
                    ${Array.from({ length: endPage - startPage + 1 }, (_, i) => `
                        <li class="page-item ${currentPage === (startPage + i) ? 'active' : ''}">
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
</script>

@endsection