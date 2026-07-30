<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Service Provider Dashboard - {{ config('app.name') }}">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="{{ asset('backend/img/icons/icon-48x48.png') }}" />

	<title>@yield('title', 'Dashboard') | {{ config('app.name') }} Service Providers</title>

	<link href="{{ asset('backend/css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<style>
		.sidebar-brand { display:flex; align-items:center; gap:8px; }
		.sp-status-pill { font-size:.7rem; padding:2px 10px; border-radius:20px; font-weight:700; }
		.sp-status-approved { background:#dcfce7; color:#166534; }
		.sp-status-pending { background:#fef3c7; color:#92400e; }
		.sp-status-rejected { background:#fee2e2; color:#991b1b; }
	</style>
	@stack('styles')
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="{{ route('service-provider.dashboard') }}">
					<i class="align-middle bi bi-tools"></i>
					<span class="align-middle">Service Provider</span>
				</a>

				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Navigation
					</li>

					<li class="sidebar-item {{ request()->routeIs('service-provider.dashboard') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('service-provider.dashboard') }}">
							<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('service-provider.profile') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('service-provider.profile') }}">
							<i class="align-middle" data-feather="user"></i> <span class="align-middle">Edit Profile</span>
						</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('service-provider.portfolio.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('service-provider.portfolio.index') }}">
							<i class="align-middle" data-feather="image"></i> <span class="align-middle">Portfolio</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="{{ route('home') }}" target="_blank">
							<i class="align-middle" data-feather="globe"></i> <span class="align-middle">Visit Website</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="#" onclick="event.preventDefault(); document.getElementById('sp-logout-form').submit();">
							<i class="align-middle" data-feather="log-out"></i> <span class="align-middle">Logout</span>
						</a>
						<form id="sp-logout-form" action="{{ route('service-provider.logout') }}" method="POST" style="display:none;">
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
						@php $sp = Auth::guard('service_provider')->user(); @endphp
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
								<i class="align-middle" data-feather="settings"></i>
							</a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								@if($sp && $sp->profile_photo)
									<img src="{{ asset('storage/' . $sp->profile_photo) }}" class="avatar img-fluid rounded me-1" alt="{{ $sp->display_name }}" />
								@else
									<img src="{{ asset('backend/img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded me-1" alt="Provider" />
								@endif
								<span class="text-dark">{{ $sp->display_name ?? 'Provider' }}</span>
							</a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="{{ route('service-provider.profile') }}"><i class="align-middle me-1" data-feather="user"></i> Edit Profile</a>
								<a class="dropdown-item" href="{{ route('service-provider.dashboard') }}"><i class="align-middle me-1" data-feather="pie-chart"></i> Dashboard</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('sp-logout-form-2').submit();">Log out</a>
								<form id="sp-logout-form-2" action="{{ route('service-provider.logout') }}" method="POST" style="display:none;">
									@csrf
								</form>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">

					@if(session('status'))
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							{{ session('status') }}
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
								<a class="text-muted" href="{{ route('home') }}" target="_blank"><strong>{{ config('app.name') }}</strong></a> &copy; {{ date('Y') }}
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
