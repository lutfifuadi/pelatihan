<header class="navbar-glass-floating">
  <div class="container d-flex align-items-center justify-content-between p-0">
    <a href="#beranda" class="navbar-logo d-flex align-items-center gap-2 text-decoration-none">
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
      <a href="#beranda" class="nav-link-premium">{{ __('Beranda') }}</a>
      <a href="#langkah" class="nav-link-premium">{{ __('Alur Pendaftaran') }}</a>
      <a href="#mengapa" class="nav-link-premium">{{ __('Keunggulan') }}</a>
      <a href="#faq" class="nav-link-premium">{{ __('FAQ') }}</a>
    </nav>
    <div class="d-flex align-items-center gap-2">
      <!-- Language Switcher -->
      <div class="dropdown dropdown-language">
        <a class="nav-link-premium dropdown-toggle d-flex align-items-center gap-1" href="#" data-bs-toggle="dropdown" style="font-size:0.85rem;">
          <i class="icon-base ti tabler-language fs-5"></i>
          <span>
            @switch(session('locale', 'id'))
              @case('en') EN @break
              @case('ar') AR @break
              @default ID
            @endswitch
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1100;">
          <li>
            <a class="dropdown-item d-flex align-items-center {{ session('locale') === 'id' ? 'active' : '' }}" href="{{ url('/lang/id') }}">
              <i class="icon-base ti tabler-check me-2 {{ session('locale') === 'id' ? '' : 'opacity-0' }}"></i>
              <span>Indonesia</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center {{ session('locale') === 'en' ? 'active' : '' }}" href="{{ url('/lang/en') }}">
              <i class="icon-base ti tabler-check me-2 {{ session('locale') === 'en' ? '' : 'opacity-0' }}"></i>
              <span>English</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center {{ session('locale') === 'ar' ? 'active' : '' }}" href="{{ url('/lang/ar') }}">
              <i class="icon-base ti tabler-check me-2 {{ session('locale') === 'ar' ? '' : 'opacity-0' }}"></i>
              <span>العربية</span>
            </a>
          </li>
        </ul>
      </div>
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
    <a href="#beranda" class="panel-link">
      <i class="icon-base ti tabler-smart-home"></i>
      {{ __('Beranda') }}
    </a>
    <a href="#langkah" class="panel-link">
      <i class="icon-base ti tabler-list-check"></i>
      {{ __('Alur Pendaftaran') }}
    </a>
    <a href="#mengapa" class="panel-link">
      <i class="icon-base ti tabler-star"></i>
      {{ __('Keunggulan') }}
    </a>
    <a href="#faq" class="panel-link">
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
