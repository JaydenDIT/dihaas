<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Server Error' }}</title>

    <!-- Bootstrap CSS from Vendor CSS Files -->
    <link href="{{ asset('assets/niceadmin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
                Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji",
                "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            background-color: #f7fafc;
            /* similar to bg-gray-100 */
        }
    </style>
</head>

<body class="d-flex align-items-center min-vh-100">

    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-8 d-flex align-items-center border p-4 bg-white shadow-sm">

                <div class="pe-3 border-end text-muted fs-4 fw-semibold">
                    {{ $code ?? 'Error' }}
                </div>

                <div class="ms-3 text-uppercase text-muted fs-4 fw-semibold">
                    {{ $message ?? 'Something went wrong' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS from Vendor JS Files -->
    <script src="{{ asset('assets/niceadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
