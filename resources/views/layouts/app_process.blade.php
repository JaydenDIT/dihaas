<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta id="csrf_token" name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="{{ asset('assets/googlefont/font.css') }}" rel="stylesheet">

    <!-- Vendor CSS -->
    <link href="{{ asset('assets/fontawesome-free-6.6.0-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bootstrap-5.2.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/sweetalert2/sweetalert3.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/DataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/select2/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    @stack('css')
</head>

<body>
    <script>
        const _token = "{{ csrf_token() }}";
    </script>

    <div>
        @include('layouts.nav_process')
        @include('layouts.error')
        @guest
        <div class="guest-div">
            @yield('content')
        </div>
        @else

        <main>
            <div class="p-1 mb-5">
                @yield('content')
            </div>
        </main>
        @endguest
    </div>

    <!-- JS Libraries -->
    <script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('assets/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-5.2.3/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/fontawesome-free-6.6.0-web/js/all.min.js') }}"></script>
    <script src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/sweetalert2/sweetalert3.js') }}"></script>
    <script src="{{ asset('js/layout.js') }}"></script>


    @stack('js')


</body>

</html>