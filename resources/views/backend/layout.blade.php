<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, admin, dashboard, template, responsive, css, html, theme, ui kit">
    <link rel="shortcut icon" href="{{ asset('adminkit/img/icons/icon-48x48.png') }}" />
    <title>@yield('title', 'Admin Panel')</title>
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    @yield('head')
</head>
<body>
    <div class="wrapper">
        @include('backend.partials.sidebar')
        <div class="main">
            @include('backend.partials.navbar')
            <main class="content">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="{{ asset('adminkit/js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
