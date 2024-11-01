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
        <!-- Date and Time Card -->
        <div class="col-md-4">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Date and Time</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ri-time-fill"></i>
                        </div>
                        <div>
                            <h6 id="current-date-time" class="ps-3 fs-3 fw-bold"></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Special Records Table</h5>

                    <div class="table-responsive">
                        <table class="table" id="reportTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Transaction Type</th>
                                    <th scope="col">Commodity</th>
                                    <th scope="col">Volume</th>
                                    <th scope="col">Origin</th>
                                    <th scope="col">Facilitator</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="TableBody">
                                @forelse ($specialRecords as $index => $record)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $record->date }}</td>
                                    <td>{{ $record->time }}</td>
                                    <td>{{ $record->transaction_type }}</td>
                                    <td>{{ $record->commodity->commodity_name ?? 'N/A' }}</td>
                                    <td>{{ $record->volume }}</td>
                                    <td>{{ $record->barangay }}, {{ $record->municipality }}, {{ $record->province }}, {{ $record->region }}</td>
                                    <td>{{ $record->facilitator->facilitator_name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('special-records.edit', $record->id) }}" class="btn btn-outline-primary m-1">Edit</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-10">
                            <a href="{{ route('special-records.create') }}" class="btn btn-primary">Add New Special Record</a>
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
        // Check for session messages
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

        // Initialize DataTable
        $('#reportTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50]
        });
    });

    function updateDateTime() {
        const now = new Date();
        const options = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        const formattedDateTime = now.toLocaleString('en-US', options);
        document.getElementById('current-date-time').textContent = formattedDateTime;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

@endsection
