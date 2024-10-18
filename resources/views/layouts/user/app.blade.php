<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="shortcut icon" href="{{ asset('assets/img/CNF.jpg') }}" type="image/x-icon">

	<title>@yield('title')</title>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	@yield(section: 'extra-links')

	<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap"
		rel="stylesheet" />

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

	<div id="preloder">
		<div class="loader"></div>
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

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		crossorigin="anonymous"></script>
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