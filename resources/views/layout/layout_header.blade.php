<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ isset($titlePage) ? $titlePage : '' }}</title>

    <!-- Fonts -->
    <!--<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>-->

    <script src={{ URL::asset('assets/js/jquery-3.3.1.min.js') }} ></script>
    <link rel="stylesheet" href="{{ URL::asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/mystyle.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/fontawesome-free-6.2.0-web/css/all.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datepicker-1.9.0/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2-4.0.13/dist/css/select2.min.css') }}" >
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/ytload/ytLoad.jquery.css') }}">
    <script src="{{ URL::asset('assets/plugins/ytload/jquery.transit.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/ytload/ytLoad.jquery.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('assets/css/attachment.css') }}">
    <style>
        body {
            font-family: "Roboto", Helvetica, Arial, sans-serif !important;
        }
    </style>
</head>

<body class="sb-nav-fixed" style="background-color: #f3f4f6;">
    @yield('admin_content')

    <script src="{{ URL::asset('assets/plugins/select2-4.0.13/dist/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ URL::asset('assets/js/scripts.js') }}"></script>
    <script src="{{ URL::asset('assets/js/autosize.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.mask.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/sweetalert2.all.min.js') }}"></script>

    <script src="{{ URL::asset('assets/plugins/datepicker-1.9.0/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datepicker-1.9.0/locales/bootstrap-datepicker.id.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
</body>
</html>