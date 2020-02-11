<!DOCTYPE html>
<html lang="br">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>@yield('title')</title>

		<link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">

		@yield('css')
	</head>
	<body>
		@include('partials.navbar')

		<div class="container-fluid">
			<div class="row flex-xl-nowrap">
				<div class="col-md-3 col-xl-2 bd-sidebar">
					@include('partials.sidebar')
				</div>

				<main class="col-md-9 col-xl-10 py-md-3 bd-content" role="main">
					@yield('content')
				</main>
			</div>
		</div>

		<script src="{{ asset('plugins/jquery/jquery-3.4.1.slim.min.js') }}" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="{{ asset('plugins/popper/popper.min.js') }}" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

		@yield('scripts')
	</body>
</html>