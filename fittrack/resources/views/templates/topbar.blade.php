<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl custom-navbar"
     id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <a aria-label="breadcrumb" href="https://www.sena.edu.co" class="navbar-brand font-weight-bolder text-white" target="_blank">          
      <img src="{{ asset('img/sena-logo.png') }}" alt="logo-sena" width="70px" height="70px">
    </a>
    <ul class="navbar-nav ms-auto justify-content-end align-items-center">
      <li class="nav-item me-3 text-white">
        @auth
          Bienvenido, <strong>{{ Auth::user()->fullname }}</strong>
        @endauth
      </li>
      <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
        <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
          <div class="sidenav-toggler-inner">
            <i class="sidenav-toggler-line bg-white"></i>
            <i class="sidenav-toggler-line bg-white"></i>
            <i class="sidenav-toggler-line bg-white"></i>
          </div>
        </a>
      </li>
    </ul>
  </div>
</nav>