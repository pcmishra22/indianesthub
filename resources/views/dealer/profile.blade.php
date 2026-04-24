<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dealer Profile</title>
</head>
<body>
    <h2>Dealer Profile</h2>
    <p>Name: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>
    <p>Role: {{ $user->role }}</p>
    <a href="{{ route('dealer.properties.index') }}">My Properties</a><br>
    <a href="/logout">Logout</a>
</body>
</html>
