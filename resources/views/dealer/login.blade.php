<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dealer Login</title>
</head>
<body>
    <h2>Dealer Login</h2>
    <form method="POST" action="{{ route('dealer.login') }}">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    @if($errors->any())
        <div style="color:red;">
            {{ $errors->first() }}
        </div>
    @endif
</body>
</html>
sdfdsffsfsdf