<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dreams Technologies">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ url('assets/img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ url('assets/img/favicon.png') }}">

    <!-- Theme Config Js -->
    <script src="{{ url('assets/js/theme-script.js') }}"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.min.css') }}">
    
    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap-datetimepicker.min.css') }}">
    
    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" href="{{ url('assets/plugins/daterangepicker/daterangepicker.css') }}">
    
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ url('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/fontawesome/css/all.min.css') }}">
    
    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ url('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ url('assets/plugins/select2/css/select2.min.css') }}">
    
    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{ url('assets/plugins/simplebar/simplebar.min.css') }}">
    <!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<!-- ✅ Intl Tel Input CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}" id="app-style">
</head>

<body>
    @include('components.top-navbar')
    @include('components.sidebar')
    
    @yield('content')
    
    {{-- @include('components.copyright') --}}

    <!-- jQuery (MUST BE FIRST) -->
    <script src="{{ url('assets/js/jquery-3.7.1.min.js') }}"></script>
    
    <!-- Bootstrap Core JS -->
    <script src="{{ url('assets/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Simplebar JS -->
    <script src="{{ url('assets/plugins/simplebar/simplebar.min.js') }}"></script>
    
    <!-- Select2 JS -->
    <script src="{{ url('assets/plugins/select2/js/select2.min.js') }}"></script>
    
    <!-- Chart JS -->
    <script src="{{ url('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ url('assets/plugins/apexchart/chart-data.js') }}"></script>
    
    <!-- Daterangepicker JS -->
    <script src="{{ url('assets/js/moment.min.js') }}"></script>
    <script src="{{ url('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    
    <!-- Datetimepicker JS -->
    <script src="{{ url('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    
    <!-- Fullcalendar JS -->
    <script src="{{ url('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
    <script src="{{ url('assets/plugins/fullcalendar/calendar-data.js') }}"></script>
    
    <!-- Datatable JS -->
    <script src="{{ url('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/dataTables.bootstrap5.min.js') }}"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<!-- ✅ Intl Tel Input JS -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <!-- Main JS -->
    <script src="{{ url('assets/js/script.js') }}"></script>
    
  @stack('scripts')
</body>
</html>