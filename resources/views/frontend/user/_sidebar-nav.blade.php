{{-- resources/views/frontend/user/_sidebar-nav.blade.php --}}
<ul class="sidebar-nav">
  <li>
    <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
  </li>
  <li>
    <a href="{{ route('user.profile') }}" class="{{ request()->routeIs('user.profile') ? 'active' : '' }}">
      <i class="bi bi-person"></i> My Profile
    </a>
  </li>
  <li>
    <a href="{{ route('user.wishlist') }}" class="{{ request()->routeIs('user.wishlist') ? 'active' : '' }}">
      <i class="bi bi-heart"></i> My Wishlist
    </a>
  </li>
  <li>
    <a href="{{ route('user.inquiries') }}" class="{{ request()->routeIs('user.inquiries') ? 'active' : '' }}">
      <i class="bi bi-chat-left-text"></i> My Inquiries
    </a>
  </li>
  <li>
    <a href="{{ route('user.recently-viewed') }}" class="{{ request()->routeIs('user.recently-viewed') ? 'active' : '' }}">
      <i class="bi bi-clock-history"></i> Recently Viewed
    </a>
  </li>
  <li class="nav-divider"></li>
  <li>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();" style="color: #dc3545;">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
  </li>
</ul>
