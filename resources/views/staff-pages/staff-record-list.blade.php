@extends('layouts.staff')
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
                    <p class="card-title">New Facilitator:</p>
                    <div>
                        <form class="row g-3 " action="{{ route('staff-record.store') }}" method="POST">
                            @csrf

                            <div class="col-md-2" hidden>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="record_type" name="record_type"
                                        placeholder="" value="facilitator" required>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="facilitator_name" name="facilitator_name"
                                        placeholder="Facilitator Name" value="{{ old('facilitator_name') }}" required>
                                    <label for="facilitator_name">Facilitator Name</label>
                                    @if ($errors->has('facilitator_name'))
                                    <span class="text-danger">{{ $errors->first('facilitator_name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="facilitator_code" name="facilitator_code"
                                        placeholder="Facilitator Name" value="{{ old('facilitator_code') }}" required>
                                    <label for="facilitator_code">Facilitator Code</label>
                                    @if ($errors->has('facilitator_code'))
                                    <span class="text-danger">{{ $errors->first('facilitator_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                             <button type="submit" id="submitButton" class="btn btn-primary">Add</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>
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
                    <p class="card-title">New Commodity:</p>
                    <div>
                        <form class="row g-3 " action="{{ route('staff-record.store') }}" method="POST">
                            @csrf

                            <div class="col-md-2" hidden>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="record_type" name="record_type"
                                        placeholder="" value="commodity" required>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="commodity_name" name="commodity_name"
                                        placeholder="Commodity Name" value="{{ old('commodity_name') }}" required>
                                    <label for="commodity_name">Commodity Name</label>
                                    @if ($errors->has('commodity_name'))
                                    <span class="text-danger">{{ $errors->first('commodity_name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                             <button type="submit" id="submitButton" class="btn btn-primary">Add</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
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
                                    {{-- <th class="text-start">Action</th> --}}
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
                                    {{-- <th class="text-start">Action</th> <!-- Added Action column --> --}}
                                </tr>
                            </thead>
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
        
    });
    </script>
    
<script>
$(document).ready(function () {
    // Initialize DataTable for Locations
    $('#datatable-location').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('staff-record.index') }}",
            data: { type: 'location' } // Specify the type
        },
        columns: [
            {
            data: null, // Use null for data source since we'll create a custom render function
            name: 'loop',
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1; // Displays the current row index
            }
        },
            { data: 'name', name: 'name' },
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
            url: "{{ route('staff-record.index') }}",
            data: { type: 'facilitator' } // Specify the type
        },
        columns: [
            {
            data: null, // Use null for data source since we'll create a custom render function
            name: 'loop',
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1; // Displays the current row index
            }
        },
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            {
            data: null,
            name: 'action',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
            
                console.log(row); 
                return `
                    <form action="/record/${row.id}?type=facilitator" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                            <i class="bx bxs-trash-alt"></i> Delete
                        </button>
                    </form>
                `;
            }
        }
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
        url: "{{ route('staff-record.index') }}",
        data: { type: 'commodity' } // Specify the type
    },
    columns: [
        {
            data: null, // Use null for data source since we'll create a custom render function
            name: 'loop',
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1; // Displays the current row index
            }
        },
        { data: 'name', name: 'name' },
        {
            data: null,
            name: 'action',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
            
                console.log(row); 
                return `
                    <form action="/record/${row.id}?type=commodity" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                            <i class="bx bxs-trash-alt"></i> Delete
                        </button>
                    </form>
                `;
            }
        }
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
            url: "{{ route('staff-record.index') }}",
            data: { type: 'link' } // Specify the type
        },
        columns: [
            {
            data: null, // Use null for data source since we'll create a custom render function
            name: 'loop',
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1; // Displays the current row index
            }
        },
            { data: 'vehicle', name: 'vehicle' },
            { data: 'location', name: 'location' },
            { data: 'facilitator', name: 'facilitator' },
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
                    alert(xhr.responseJSON.message || 'An error occurred while deleting the staff-record.');
                }
            });
        }
    }
});
</script>
@endsection
