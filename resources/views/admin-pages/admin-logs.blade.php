@extends('layouts.admin')
@section('page_title', 'Activity Logs')

@section('content')


<div class="pagetitle">
    <h1>Activty Logs</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="/">Tracks the actions done by users</a></li>
        </ol>
    </nav>
</div>


<section class="section dashboard">

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">BAPTC Volume Monitoring System Logs</h5> 
                <div class="table-responsive">
                    <table class="table" id="datatable-log">
                        <thead>
                            <tr>
                                <th class="text-start">No.</th>
                                <th class="text-start">Date</th>
                                <th class="text-start">Action Type</th>
                                <th class="text-start">Transaction</th>
                                <th class="text-start">Author</th>
                                <th class="text-start">Author ID</th>
                            </tr>
                        </thead>
                    </table>  
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
            
        });
        </script>


<script>
    $(document).ready(function () {
        // Initialize DataTable for Locations
        $('#datatable-log').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/transactions",
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
                { data: 'date', name: 'date' },
                { data: 'action_type', name: 'action_type' },
                { data: 'transaction', name: 'transaction' },
                { data: 'author', name: 'author' },
                { data: 'user_id', name: 'user_id' },
            ],
            lengthMenu: [5, 10, 20],
            language: {
                search: "Search:",
            }
        });
        
    
        // Initialize DataTable for Vehicle Links
    });
    </script>
</section>




@endsection