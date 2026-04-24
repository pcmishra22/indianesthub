<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('builder.dashboard') }}">
            <span class="align-middle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1" style="vertical-align:-3px;">
                    <rect x="1" y="3" width="15" height="13" rx="1"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
                {{ config('app.name') }} <span style="opacity:.65;font-weight:300;">Builder</span>
            </span>
        </a>

        <ul class="sidebar-nav">

            <li class="sidebar-header">Navigation</li>

            <li class="sidebar-item {{ request()->routeIs('builder.dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('builder.dashboard') }}">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('builder.projects.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('builder.projects.index') }}">
                    <i class="align-middle" data-feather="layers"></i>
                    <span class="align-middle">My Projects</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('builder.leads.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('builder.leads.index') }}">
                    <i class="align-middle" data-feather="inbox"></i>
                    <span class="align-middle">Leads</span>
                    @php $newLeadsCount = \App\Models\BuilderLead::where('builder_id', Auth::guard('builder')->id())->where('status','new')->count(); @endphp
                    @if($newLeadsCount > 0)
                    <span class="badge bg-danger ms-auto" style="font-size:.65rem;">{{ $newLeadsCount }}</span>
                    @endif
                </a>
            </li>

            <li class="sidebar-header">Account</li>

            <li class="sidebar-item {{ request()->routeIs('builder.profile') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('builder.profile') }}">
                    <i class="align-middle" data-feather="user"></i>
                    <span class="align-middle">Profile</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home') }}" target="_blank">
                    <i class="align-middle" data-feather="external-link"></i>
                    <span class="align-middle">View Website</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link text-danger" href="#"
                   onclick="event.preventDefault(); document.getElementById('builder-logout-form').submit();">
                    <i class="align-middle" data-feather="log-out"></i>
                    <span class="align-middle">Logout</span>
                </a>
                <form id="builder-logout-form" action="{{ route('builder.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

        </ul>
    </div>
</nav>
