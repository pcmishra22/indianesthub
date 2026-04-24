{{-- Redirect to the agent-profile page which now shows dealer + properties --}}
@php
    return redirect()->route('agent-profile', $dealer);
@endphp
