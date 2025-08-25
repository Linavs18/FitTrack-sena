<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
  <div class="sidenav-header">
    <a class="navbar-brand m-3">
      <img src="{{ asset('img/Fittrack-logo.png') }}" class="img-fluid navbar-brand-img" style="max-height: 100px;" alt="main_logo">
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('view.*') ? 'active' : '' }}" href="{{ route('welcome') }}">
          <i class="fas fa-tachometer-alt text-dark"></i>
          <span class="nav-link-text ms-3">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('activity.*') ? 'active' : '' }}" href="{{ route('activity.index') }}">
          <i class="fas fa-dumbbell text-dark"></i>
          <span class="nav-link-text ms-3">Actividades</span>
        </a>
      </li>
      <!-- Más menús -->

      <li class="nav-item">
          <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
          <a class="nav-link d-flex align-items-center" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt text-dark"></i>
              <span class="nav-link-text ms-3">Cerrar sesión</span>
          </a>
      </li>
    </ul>
  </div>
</aside>
