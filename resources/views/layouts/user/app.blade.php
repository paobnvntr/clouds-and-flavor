<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.2/jquery.overlayScrollbars.min.js"></script>

    <!-- Google Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap"
      rel="stylesheet"
    />
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('assets/import/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/jquery-ui.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/import/css/style.css') }}" type="text/css">
</head>

<body>
    

    {{-- @include('layouts.user.hamburger') --}}
    @include('layouts.user.header')

        @yield('content')

    @include('layouts.footer')
    


    <!-- Bootstrap JS (Bundle with Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    <!-- Js Plugins -->
    <script src="{{ asset('assets/import/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('assets/import/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/import/js/main.js') }}"></script>
</body>

</html>
