@component('mail::message')
# Hello {{ $name }},

Thanks for registering with **India Nest Hub**! We're thrilled to have you as part of our community.

You can now explore premium properties and connect with top dealers and builders.

@component('mail::button', ['url' => config('app.url') . '/dashboard'])
Go to Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent