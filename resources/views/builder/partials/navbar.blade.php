<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">

            {{-- Builder Info Dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center gap-2" href="#" id="builderDropdown"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    @if(Auth::guard('builder')->user()->logo)
                        <img src="{{ asset('storage/' . Auth::guard('builder')->user()->logo) }}"
                             alt="Logo" class="avatar img-fluid rounded-circle"
                             style="width:32px;height:32px;object-fit:cover;">
                    @else
                        <div class="avatar rounded-circle d-flex align-items-center justify-content-center"
                             style="width:32px;height:32px;background:#0d6efd;color:#fff;font-weight:700;font-size:.85rem;">
                            {{ strtoupper(substr(Auth::guard('builder')->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="d-none d-sm-block">
                        <span class="fw-semibold" style="font-size:.88rem;">
                            {{ Auth::guard('builder')->user()->company_name ?: Auth::guard('builder')->user()->name }}
                        </span>
                        <small class="d-block text-muted" style="font-size:.72rem;line-height:1;">Builder</small>
                    </div>
                    <i data-feather="chevron-down" style="width:14px;height:14px;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="builderDropdown">
                    <a class="dropdown-item" href="{{ route('builder.profile') }}">
                        <i class="align-middle me-1" data-feather="user" style="width:14px;height:14px;"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('builder.dashboard') }}">
                        <i class="align-middle me-1" data-feather="sliders" style="width:14px;height:14px;"></i>
                        Dashboard
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#"
                       onclick="event.preventDefault(); document.getElementById('builder-logout-form-nav').submit();">
                        <i class="align-middle me-1" data-feather="log-out" style="width:14px;height:14px;"></i>
                        Logout
                    </a>
                    <form id="builder-logout-form-nav" action="{{ route('builder.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>

        </ul>
    </div>
</nav>
