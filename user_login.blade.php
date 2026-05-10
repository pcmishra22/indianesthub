@component('mail::message')
# Admin Alert: User Login Activity

A user has logged into the platform.

**User Details:**
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Time:** {{ now()->toDayDateTimeString() }}

@endcomponent