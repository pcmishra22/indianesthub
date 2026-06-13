<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @hasSection('header')
        @yield('header')
    @endif
</head>
<body>
    {{ $slot }}
</body>
</html>

