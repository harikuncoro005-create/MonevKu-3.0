<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ isset($titlePage) ? $titlePage : '' }}</title>

    <script src={{ URL::asset('assets/js/jquery-3.3.1.min.js') }} ></script>
    

    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2-4.0.13/dist/css/select2.min.css') }}" >
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/ytload/ytLoad.jquery.css') }}">
    <script src="{{ URL::asset('assets/plugins/ytload/jquery.transit.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/ytload/ytLoad.jquery.js') }}"></script>
    
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/boldo/assets/css/theme.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    
</head>

<body>
   
    <main class="main" id="top">
        <nav class="navbar navbar-expand-lg fixed-top navbar-dark {{ Request::segment(1) != '' ? 'bg-dark' : '' }}"  {{ Request::segment(1) == '' ? 'data-navbar-on-scroll="data-navbar-on-scroll"' : '' }} >
            <div class="container"><a class="navbar-brand" href="/"><img style="width: 10rem" src="{{ URL::asset('assets/img/logo-monev.png') }}" alt="" /></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="fa-solid fa-bars text-white fs-3"></i></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                    <li class="nav-item"><a class="nav-link {{ Request::segment(1) == '' ? 'active' : '' }}" aria-current="page" href="/">Home</a></li>
                    {{-- <li class="nav-item"><a class="nav-link {{ Request::segment(1) == 'sumbangan' ? 'active' : '' }}" aria-current="page" href="/sumbangan">Rekap Sumbangan</a></li> --}}
                    
                    <li class="nav-item mt-2 mt-lg-0"><a class="nav-link" aria-current="page" href="/login">Log In</a></li>
                    </ul>
                </div>
            </div>
        </nav>

    @yield('admin_content')

    </main>

    <script src="{{ URL::asset('assets/plugins/boldo/assets/js/theme.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/boldo/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/boldo/vendors/bootstrap/bootstrap.min.js') }}"></script>

    <script src="{{ URL::asset('assets/plugins/select2-4.0.13/dist/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ URL::asset('assets/js/scripts.js') }}"></script>
    <script src="{{ URL::asset('assets/js/autosize.js') }}"></script>
    <script src="{{ URL::asset('assets/js/jquery.mask.min.js') }}"></script>

    <script src="{{ URL::asset('assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
</body>
</html>