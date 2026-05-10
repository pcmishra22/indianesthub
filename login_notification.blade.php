@component('mail::message')
# Hello {{ $name }},

We noticed a new login to your **India Nest Hub** account.

**Time:** {{ now()->toDayDateTimeString() }}

If you did not authorize this login, please secure your account by changing your password.

@component('mail::button', ['url' => route('password.request')])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent