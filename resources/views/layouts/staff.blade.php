<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('page_title')</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('assets/img/BAPTC_logo.png')}}" rel="icon">
  <link href="{{asset('assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

  <link href="{{asset('/custom-scripts/datatables.min.css')}}" rel="stylesheet">
  <link href="{{asset('/custom-scripts/datatables.css')}}" rel="stylesheet">

  <script src="{{asset('/custom-scripts/jquery-3.6.0.min.js')}}"></script>

  <script src="{{asset('/custom-scripts/exporting.js')}}"></script>
  <script src="{{asset('/custom-scripts/offline-exporting.js')}}"></script>
  <script src="{{asset('/custom-scripts/highcharts.js')}}"></script>

  <!-- Template Main CSS File -->
  <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">


  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <style>
    /* Active Tab Styles */
    .sidebar-nav .nav-item .nav-link.active {
      background-color: #012970;
      /* Dark background color */
      color: white;
      /* Change text color */
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="{{asset('assets/img/BAPTC_logo.png')}}" alt="BAPTC">
        <span class="d-none d-lg-block">BAPTC</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    {{-- <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar --> --}}

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'staff-dashboard') active @endif" href="{{route('staff-dashboard')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'staff-trading-inflow.index' || Route::currentRouteName() == 'staff-trading-inflow.create' || Route::currentRouteName() == 'staff-trading-inflow.edit') active @endif" href="{{route('staff-trading-inflow.index')}}">
          <i class="bi bi-pencil-square"></i>
          <span>Trading Inflow</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'staff-trading-outflow.index' || Route::currentRouteName() == 'staff-trading-outflow.create' || Route::currentRouteName() == 'staff-trading-outflow.edit') active @endif" href="{{route('staff-trading-outflow.index')}}">
          <i class="bi bi-pencil-square"></i>
          <span>Trading Outflow</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'staff-short-trip-inflow-and-outflow.index' || Route::currentRouteName() == 'staff-short-trip-inflow-and-outflow.create' || Route::currentRouteName() == 'staff-short-trip-inflow-and-outflow.edit') active @endif" href="{{route('staff-short-trip-inflow-and-outflow.index')}}">
          <i class="bi bi-pencil-square"></i>
          <span>Short Trip Inflow and Outflow</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'staff-special-record.index' || Route::currentRouteName() == 'staff-special-record.create' || Route::currentRouteName() == 'staff-special-record.edit') active @endif" href="{{route('staff-special-record.index')}}">
          <i class="bi bi-pencil-square"></i>
          <span>Special Records</span>
        </a>
      </li>



      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'staff-summary-report.index' || Route::currentRouteName() == 'staff-report.index') active @endif" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse @if(Route::currentRouteName() == 'staff-summary-report.index' || Route::currentRouteName() == 'staff-report.index') show @endif" data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{route('staff-summary-report.index')}}" class="@if(Route::currentRouteName() == 'staff-summary-report.index') active @endif">
              <i class="bi bi-circle"></i><span>Summary Reports</span>
            </a>
          </li>
          <li>
            <a href="{{route('staff-report.index')}}" class="@if(Route::currentRouteName() == 'staff-report.index') active @endif">
              <i class="bi bi-circle"></i><span>Analytical Reports</span>
            </a>
          </li>
        </ul>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'staff-record.index') active @endif" href="{{route('staff-record.index')}}">
          <i class="bi bi-laptop"></i>
          <span>System Records</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'staff.profile' && Route::current()->parameter('id') == auth()->user()->id) active @endif" href="{{ route('staff.profile', ['id' => auth()->user()->id]) }}">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li>



      <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="nav-link collapsed">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Logout</span>
          </button>
        </form>

      </li><!-- End Login Page Nav -->


    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    @yield('content')

  </main><!-- End #main -->



  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/chart.js/chart.umd.js')}}"></script>
  <script src="{{asset('assets/vendor/echarts/echarts.min.js')}}"></script>
  <script src="{{asset('assets/vendor/quill/quill.js')}}"></script>
  <script src="{{asset('assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
  <script src="{{asset('assets/vendor/tinymce/tinymce.min.js')}}"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>

  <script src="{{asset('/custom-scripts/datatables.js')}}"></script>
  <script src="{{asset('/custom-scripts/datatables.min.js')}}"></script>
  <!-- Template Main JS File -->
  <script src="{{asset('assets/js/main.js')}}"></script>

</body>

</html>