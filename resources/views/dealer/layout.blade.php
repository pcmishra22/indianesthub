<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Dealer Dashboard - Property Dealer">
	<meta name="author" content="AdminKit">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="{{ asset('backend/img/icons/icon-48x48.png') }}" />

	<title>@yield('title', 'Dealer Dashboard') | Property Dealer</title>

	<link href="{{ asset('backend/css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	@stack('styles')
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="{{ route('dealer.dashboard') }}">
					<span class="align-middle">Property Dealer</span>
				</a>

				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Navigation
					</li>

					<li class="sidebar-item {{ request()->routeIs('dealer.dashboard') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dealer.dashboard') }}">
							<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('dealer.properties.index') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dealer.properties.index') }}">
							<i class="align-middle" data-feather="list"></i> <span class="align-middle">My Properties</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('dealer.properties.create') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dealer.properties.create') }}">
							<i class="align-middle" data-feather="plus-square"></i> <span class="align-middle">Add Property</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('dealer.properties.marketing*') ? 'active' : '' }}">
					<a class="sidebar-link" href="{{ route('dealer.properties.index') }}">
						<i class="align-middle" data-feather="megaphone"></i> <span class="align-middle">Marketing Studio</span>
					</a>
				</li>

				<li class="sidebar-item {{ request()->routeIs('dealer.inquiries.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dealer.inquiries.index') }}">
							<i class="align-middle" data-feather="message-square"></i> <span class="align-middle">Inquiries</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('dealer.schedule-viewings.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dealer.schedule-viewings.index') }}">
							<i class="align-middle" data-feather="calendar"></i> <span class="align-middle">Schedule Viewings</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('dealer.subscription') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dealer.subscription') }}">
							<i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Subscription</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('dealer.profile') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dealer.profile') }}">
							<i class="align-middle" data-feather="user"></i> <span class="align-middle">Profile</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="#" onclick="event.preventDefault(); document.getElementById('dealer-logout-form').submit();">
							<i class="align-middle" data-feather="log-out"></i> <span class="align-middle">Logout</span>
						</a>
						<form id="dealer-logout-form" action="{{ route('dealer.logout') }}" method="POST" style="display:none;">
							@csrf
						</form>
					</li>
				</ul>
			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
								<i class="align-middle" data-feather="settings"></i>
							</a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								@if(Auth::guard('dealer')->user()->profile_photo)
									<img src="{{ asset('storage/' . Auth::guard('dealer')->user()->profile_photo) }}" class="avatar img-fluid rounded me-1" alt="{{ Auth::guard('dealer')->user()->first_name }}" />
								@else
									<img src="{{ asset('backend/img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded me-1" alt="{{ Auth::guard('dealer')->user()->first_name }}" />
								@endif
								<span class="text-dark">{{ Auth::guard('dealer')->user()->first_name }} {{ Auth::guard('dealer')->user()->last_name }}</span>
							</a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="{{ route('dealer.profile') }}"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								<a class="dropdown-item" href="{{ route('dealer.dashboard') }}"><i class="align-middle me-1" data-feather="pie-chart"></i> Dashboard</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('dealer-logout-form').submit();">Log out</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">

					@if(session('success'))
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							{{ session('success') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif

					@if(session('error'))
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							{{ session('error') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif

					@yield('content')

				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="{{ route('home') }}" target="_blank"><strong>Property Dealer</strong></a> &copy; {{ date('Y') }}
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="{{ route('contact') }}" target="_blank">Support</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="{{ route('privacy') }}" target="_blank">Privacy</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="{{ route('terms') }}" target="_blank">Terms</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="{{ asset('backend/js/app.js') }}"></script>
	@stack('scripts')

</body>

</html>
