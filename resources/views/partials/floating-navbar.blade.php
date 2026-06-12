<header class="navbar-glass-floating">
  <div class="container d-flex align-items-center justify-content-between p-0">
    <a href="#beranda" class="navbar-logo d-flex align-items-center gap-2 text-decoration-none">
      <div class="logo-icon-glow">
        <i class="icon-base ti tabler-bulb text-white fs-4"></i>
      </div>
      <x-brand-logo size="lg" />
    </a>
    <button class="navbar-toggler d-lg-none border-0 p-0 ms-auto me-3" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="icon-base ti tabler-menu-2 text-white fs-4"></i>
    </button>
    <nav class="d-none d-lg-flex align-items-center gap-4">
      <a href="#beranda" class="nav-link-premium">Beranda</a>
      <a href="#langkah" class="nav-link-premium">Langkah Daftar</a>
      <a href="#mengapa" class="nav-link-premium">Keunggulan</a>
      <a href="#faq" class="nav-link-premium">FAQ</a>
    </nav>
    <div class="collapse navbar-collapse d-lg-none" id="mobileNav">
      <nav class="d-flex flex-column gap-2 mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
        <a href="#beranda" class="nav-link-premium">Beranda</a>
        <a href="#langkah" class="nav-link-premium">Langkah Daftar</a>
        <a href="#mengapa" class="nav-link-premium">Keunggulan</a>
        <a href="#faq" class="nav-link-premium">FAQ</a>
      </nav>
    </div>
    <div>
      @auth
        <a href="{{ route('dashboard.admin') }}" class="btn btn-login-premium d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-dashboard fs-5"></i>Dashboard
        </a>
      @else
        <a href="{{ route('login') }}" class="btn btn-login-premium d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-login fs-5"></i>Login
        </a>
      @endauth
    </div>
  </div>
</header>
