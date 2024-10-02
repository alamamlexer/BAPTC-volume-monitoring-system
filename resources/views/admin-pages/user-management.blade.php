@extends('layouts.admin')
@section('page_title','Users')
@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
      <h1>Users</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="/">User Management</a></li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->
    @if(session('success'))
          <div id="success-alert" class="alert alert-success" role="alert">
              {{ session('success') }}
          </div>
        @endif
        @if(session('danger'))
          <div id="danger-alert" class="alert alert-danger" role="alert">
              {{ session('danger') }}
          </div>
        @endif
    <section class="section">
        
        
       {{-- Table --}}
       <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title"></h5>
    
                  <!-- Default Table -->
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="col-md-1">#</th>
                        <th class="col-md-3">Username</th>
                        <th class="col-md-3">Contact Number</th>
                        <th class="col-md-3">Type</th>
                        <th class="col-md-2">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($users as $user)
                        <tr>
                          <td>{{$user->id}}</td>
                          <td>{{$user->username}}</td>
                          <td>
                            {{ optional($user->staffs)->contact_number }} 
                            {{ optional($user->farmers)->contact_number }} 
                          </td>
                          <td>
                            <pre>{{ dump($user->staffs->contact_number) }}</pre>  
                          @if ($user->type == 1)
                            Farmer
                          @else
                            Inspector/Assistant
                          @endif
                          </td>
                          <td> 
                            <div class="float-start">
                              <form action="{{route('user-management.destroy',$user->id)}}" method="POST">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-outline-danger"onclick="return confirm('Are you sure you want to delete this user?')"><i class="bx bxs-trash-alt"></i> Delete</button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <div class="row mb-3">
                  <div class="col-sm-10">
                      <a href="{{route('user-management.create')}}" class="btn btn-primary">Add new Trading Inspector/Assistant</a>
                  </div>
                  </div>
                </div>
                  <!-- End Default Table Example -->
                </div>
              </div>
        </div>
    </div>
    
    {{-- End Table --}}
    
    </section>
 
@endsection