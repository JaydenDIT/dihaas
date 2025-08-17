<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Components / Accordion - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- CSRF Token -->
    <meta id="csrf_token" name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ asset('assets/niceadmin/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/niceadmin/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <!--Font awesome -->
    <link href="{{ asset('assets/fontawesome-free-6.6.0-web/css/all.min.css') }}" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/niceadmin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/niceadmin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/niceadmin/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/niceadmin/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/niceadmin/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/niceadmin/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('assets/niceadmin/vendor/simple-datatables/style.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('assets/sweetalert2/sweetalert3.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/DataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/select2/select2.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/niceadmin/css/style.css') }}" rel="stylesheet">
    @stack('css')

    <!-- =======================================================
  * Template Name: NiceAdmin - v2.2.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
    <script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>
    <script>
        const _token = "{{ csrf_token() }}";
    </script>

</head>

<body>

    @include('layouts.niceadmin._header')

    @include('layouts.niceadmin._aside_menubar')

    <main id="main" class="main">
        @isset($pagetitle)
            <div class="pagetitle">
                <h1>{{ $pagetitle }}</h1>
            </div><!-- End Page Title -->
        @endisset


        @yield('content')

    </main><!-- End #main -->

    @include('layouts.niceadmin._footer')

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Font awesome -->
    <script src="{{ asset('assets/fontawesome-free-6.6.0-web/js/all.min.js') }}"></script>
    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/niceadmin/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/niceadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/niceadmin/vendor/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/niceadmin/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/niceadmin/vendor/quill/quill.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/niceadmin/vendor/simple-datatables/simple-datatables.js') }}"></script> --}}
    <script src="{{ asset('assets/niceadmin/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/niceadmin/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/sweetalert2/sweetalert3.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/niceadmin/js/main.js') }}"></script>
    <script src="{{ asset('js/layout.js') }}"></script>
    @stack('js')

</body>

</html>
