@extends('layouts.admin')
@section('page_title', 'Records')
@section('content')

<!-- Page Title -->
<div class="pagetitle">
    <h1>Records</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="/">Records</a></li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">
        <!-- Locations -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Locations</h5> 
                    <div class="table-responsive">
                        <table class="table" id="datatable-location">
                            <thead>
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Action</th>
                                </tr>
                            </thead>
                        </table>  
                    </div>
                </div>
            </div>
        </div>

        <!-- Market Facilitator -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Market Facilitator</h5> 
                    <div class="table-responsive">
                        <table class="table" id="datatable-facilitator">
                            <thead>
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th class="text-start">Code</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Action</th>
                                </tr>
                            </thead>
                        </table>  
                    </div>
                </div>
            </div>
        </div>

        <!-- Commodity -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Commodity</h5> 
                    <div class="table-responsive">
                        <table class="table" id="datatable-commodity">
                            <thead>
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Action</th>
                                </tr>
                            </thead>
                        </table>  
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vehicle Links to the Facilitator and/or Location -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Vehicle Links</h5> 
                    <div class="table-responsive">
                        <table class="table" id="datatable-links">
                            <thead>
                                <tr>
                                    <th class="text-start">No.</th>
                                    <th class="text-start">Vehicle Name</th>
                                    <th class="text-start">Location</th>
                                    <th class="text-start">Facilitator</th>
                                    <th class="text-start">Action</th> <!-- Added Action column -->
                                </tr>
                            </thead>
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function () {
    // Initialize DataTable for Locations
    $('#datatable-location').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('record.index') }}",
            data: { type: 'location' } // Specify the type
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        lengthMenu: [5, 10, 20],
        language: {
            search: "Search:",
        }
    });

    // Initialize DataTable for Facilitators
    $('#datatable-facilitator').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('record.index') }}",
            data: { type: 'facilitator' } // Specify the type
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        lengthMenu: [5, 10, 20],
        language: {
            search: "Search:",
        }
    });

    // Initialize DataTable for Commodities
    $('#datatable-commodity').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('record.index') }}",
            data: { type: 'commodity' } // Specify the type
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        lengthMenu: [5, 10, 20],
        language: {
            search: "Search:",
        }
    });

    // Initialize DataTable for Vehicle Links
    $('#datatable-links').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('record.index') }}",
            data: { type: 'link' } // Specify the type
        },
        columns: [
            { data: 'id', name: 'id' ?? "N/A"},
            { data: 'vehicle', name: 'vehicle' },
            { data: 'location', name: 'location' },
            { data: 'facilitator', name: 'facilitator' },
            { data: 'action', name: 'action', orderable: false, searchable: false } // Added action column
        ],
        lengthMenu: [5, 10, 20],
        language: {
            search: "Search:",
        }
    });
    
    function deleteRecord(url) {
        if (confirm('Are you sure you want to delete this reservation?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    alert(response.message);
                    // Refresh DataTables after deletion
                    $('#datatable-location').DataTable().ajax.reload();
                    $('#datatable-facilitator').DataTable().ajax.reload();
                    $('#datatable-commodity').DataTable().ajax.reload();
                    $('#datatable-links').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message || 'An error occurred while deleting the record.');
                }
            });
        }
    }
});
</script>
@endsection
