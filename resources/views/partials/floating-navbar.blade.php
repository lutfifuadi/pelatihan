<header class="navbar-glass-floating">
  <div class="container d-flex align-items-center justify-content-between p-0">
    <a href="{{ route('pages-home') }}#beranda" class="navbar-logo d-flex align-items-center gap-2 text-decoration-none">
      <div class="logo-icon-glow">
        <i class="icon-base ti tabler-bulb text-white fs-4"></i>
      </div>
      <x-brand-logo size="lg" />
    </a>
    <!-- Mobile Hamburger: animated 3-bar toggle -->
    <button class="mobile-menu-btn d-lg-none ms-auto me-3" id="mobileMenuToggle" aria-label="Buka menu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>
    <nav class="d-none d-lg-flex align-items-center gap-4">
      <a href="{{ route('pages-home') }}#beranda" class="nav-link-premium">{{ __('Beranda') }}</a>
      <a href="{{ route('pages-home') }}#pelatihan" class="nav-link-premium">{{ __('Pelatihan') }}</a>
      <a href="{{ route('pages-home') }}#langkah" class="nav-link-premium">{{ __('Alur Pendaftaran') }}</a>
      <a href="{{ route('pages-home') }}#mengapa" class="nav-link-premium">{{ __('Keunggulan') }}</a>
      <a href="{{ route('pages-home') }}#faq" class="nav-link-premium">{{ __('FAQ') }}</a>
    </nav>
    <div class="d-flex align-items-center gap-2">
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

<!-- Mobile overlay (blur background) -->
<div class="mobile-overlay" id="mobileOverlay"></div>

<!-- Mobile slide-in panel from left -->
<div class="mobile-slide-panel" id="mobileSlidePanel">
  <div class="panel-header">
    <span class="panel-title">{{ __('Menu') }}</span>
    <button class="panel-close-btn" id="mobileMenuClose" aria-label="{{ __('Close') }}">
      <i class="icon-base ti tabler-x fs-4"></i>
    </button>
  </div>

  <nav class="panel-nav">
    <a href="{{ route('pages-home') }}#beranda" class="panel-link">
      <i class="icon-base ti tabler-smart-home"></i>
      {{ __('Beranda') }}
    </a>
    <a href="{{ route('pages-home') }}#pelatihan" class="panel-link">
      <i class="icon-base ti tabler-school"></i>
      {{ __('Pelatihan') }}
    </a>
    <a href="{{ route('pages-home') }}#langkah" class="panel-link">
      <i class="icon-base ti tabler-list-check"></i>
      {{ __('Alur Pendaftaran') }}
    </a>
    <a href="{{ route('pages-home') }}#mengapa" class="panel-link">
      <i class="icon-base ti tabler-star"></i>
      {{ __('Keunggulan') }}
    </a>
    <a href="{{ route('pages-home') }}#faq" class="panel-link">
      <i class="icon-base ti tabler-question-mark"></i>
      {{ __('FAQ') }}
    </a>
  </nav>

  <div class="panel-footer">
    @auth
      <a href="{{ route('dashboard.admin') }}" class="btn btn-login-premium w-100 d-flex align-items-center justify-content-center gap-2">
        <i class="icon-base ti tabler-dashboard fs-5"></i>Dashboard
      </a>
    @else
      <a href="{{ route('login') }}" class="btn btn-login-premium w-100 d-flex align-items-center justify-content-center gap-2">
        <i class="icon-base ti tabler-login fs-5"></i>Login
      </a>
    @endauth
  </div>
</div>
