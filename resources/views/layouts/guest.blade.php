<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/CNF.jpg') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        @if(Session::has('success'))
            <div class="alert alert-success floating-alert fw-bold" id="alert-success" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif

        @if(Session::has('failed'))
            <div class="alert alert-danger floating-alert fw-bold" id="alert-failed" role="alert">
                {{ Session::get('failed') }}
            </div>
        @endif

        <div>
            <a href="/">
                {{-- <x-application-logo class="w-20 h-20 fill-current text-gray-500" /> --}}
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            let successAlert = document.getElementById('alert-success');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.transition = "opacity 0.5s ease";
                    successAlert.style.opacity = 0;
                    setTimeout(() => { successAlert.remove(); }, 500);
                }, 4000);
            }

            let failedAlert = document.getElementById('alert-failed');
            if (failedAlert) {
                setTimeout(() => {
                    failedAlert.style.transition = "opacity 0.5s ease";
                    failedAlert.style.opacity = 0;
                    setTimeout(() => { failedAlert.remove(); }, 500);
                }, 4000);
            }
        });
    </script>
</body>

</html>