<!DOCTYPE html>
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

    <script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script>
    <script>
        const _token = "{{ csrf_token() }}";
    </script>
    @stack('css')
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset

        </div>
    </div>
</body>
<script src="{{ asset('assets/bootstrap-5.2.3/js/bootstrap.min.js') }}"></script>
@stack('js')

</html>
