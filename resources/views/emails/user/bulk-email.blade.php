<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? '' }}</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; line-height: 1.5; color: #111;">

<p>Hi <strong>{{ $user->name }}</strong>,</p>

<div style="white-space: pre-wrap;">
    {!! nl2br(e($body)) !!}
</div>

<p>Best regards,<br>
    {{ config('app.name') }}
</p>

</body>
</html>

