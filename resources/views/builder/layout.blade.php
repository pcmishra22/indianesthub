<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('adminkit/img/icons/icon-48x48.png') }}" />
    <title>@yield('title', 'Builder Portal') – {{ config('app.name') }}</title>
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    @yield('head')
</head>
<body>
<div class="wrapper">

    @include('builder.partials.sidebar')

    <div class="main">

        @include('builder.partials.navbar')

        <main class="content">
            <div class="container-fluid p-0">

                {{-- Flash Messages --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                    <i data-feather="check-circle" class="me-1" style="width:16px;height:16px;"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                    <i data-feather="alert-circle" class="me-1" style="width:16px;height:16px;"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                            <strong><a class="text-muted" href="{{ route('home') }}">{{ config('app.name') }}</a></strong>
                            &copy; {{ date('Y') }} — Builder Portal
                        </p>
                    </div>
                    <div class="col-6 text-end">
                        <ul class="list-inline">
                            <li class="list-inline-item"><a class="text-muted" href="{{ route('home') }}">Website</a></li>
                            <li class="list-inline-item"><a class="text-muted" href="{{ route('contact') }}">Support</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

    </div><!-- /.main -->
</div><!-- /.wrapper -->

<script src="{{ asset('adminkit/js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
