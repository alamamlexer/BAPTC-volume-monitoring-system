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
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'admin.index') active @endif" href="{{route('admin.index')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'trading-inflow.index' || Route::currentRouteName() == 'trading-inflow.create' || Route::currentRouteName() == 'trading-inflow.edit') active @endif" href="{{route('trading-inflow.index')}}">
          <i class="bi bi-pencil-square"></i>
          <span>Trading Inflow</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'trading-outflow.index' || Route::currentRouteName() == 'trading-outflow.create' || Route::currentRouteName() == 'trading-outflow.edit') active @endif" href="{{route('trading-outflow.index')}}">
          <i class="bi bi-pencil-square"></i>
          <span>Trading Outflow</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'short-trip-inflow-and-outflow.index' || Route::currentRouteName() == 'short-trip-inflow-and-outflow.create' || Route::currentRouteName() == 'short-trip-inflow-and-outflow.edit') active @endif" href="{{route('short-trip-inflow-and-outflow.index')}}">
          <i class="bi bi-pencil-square"></i>
          <span>Short Trip Inflow and Outflow</span>
        </a>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'special-records.index' || Route::currentRouteName() == 'special-records.create' || Route::currentRouteName() == 'special-records.edit') active @endif" href="{{route('special-records.index')}}">
          <i class="bi bi-pencil-square"></i>
          <span>Special Records</span>
        </a>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'summary-report.index' || Route::currentRouteName() == 'report.index' || Route::currentRouteName() == 'transactions') active @endif"
          data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse @if(Route::currentRouteName() == 'summary-report.index' || Route::currentRouteName() == 'report.index' || Route::currentRouteName() == 'transactions') show @endif" data-bs-parent="#sidebar-nav">
          <li>
            <a class="@if(Route::currentRouteName() == 'summary-report.index') active @endif" href="{{route('summary-report.index')}}">
              <i class="bi bi-circle"></i><span>Summary Reports</span>
            </a>
          </li>
          <li>
            <a class="@if(Route::currentRouteName() == 'report.index') active @endif" href="{{route('report.index')}}">
              <i class="bi bi-circle"></i><span>Analytical Reports</span>
            </a>
          </li>
          <li>
            <a class="@if(Route::currentRouteName() == 'transactions') active @endif" href="{{ route('transactions') }}">
              <i class="bi bi-circle"></i><span>Activity Log</span>
            </a>
          </li>

        </ul>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'record.index') active @endif" href="{{route('record.index')}}">
          <i class="bi bi-laptop"></i>
          <span>System Records</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed @if(Route::currentRouteName() == 'user-management.index') active @endif" href="{{route('user-management.index')}}">
          <i class="bi bi-person"></i>
          <span>User Management</span>
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

  <!-- Vendor JS Files -->
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

  <script>
    // Ensure the collapse is expanded if the submenu item is active
    document.addEventListener("DOMContentLoaded", function() {
      var activeLink = document.querySelector('.nav-item .nav-link.active');
      if (activeLink) {
        var parentCollapse = activeLink.closest('.collapse');
        if (parentCollapse) {
          parentCollapse.classList.add('show');
        }
      }
    });
  </script>
</body>

</html>