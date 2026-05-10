@component('mail::message')
# Admin Alert: New Registration

A new user has just registered on the platform.

**User Details:**
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Time:** {{ now()->toDayDateTimeString() }}

@component('mail::button', ['url' => config('app.url') . '/admin/users'])
View Users
@endcomponent

@endcomponent