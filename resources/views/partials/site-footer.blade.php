@php
  $institutionDesc = \App\Models\Setting::where('key', 'institution_description')->value('value') ?? 'Program Pelatihan Ekonomi Kreatif diselenggarakan oleh MAN SABA sebagai wadah pembekalan kompetensi keahlian praktis yang mandiri, kreatif, dan mandiri secara finansial.';
  $institutionAddress = \App\Models\Setting::where('key', 'institution_address')->value('value') ?? '';
  $institutionPhone = \App\Models\Setting::where('key', 'institution_phone')->value('value') ?? '';
  $footerCopyright = \App\Models\Setting::where('key', 'footer_copyright')->value('value') ?? 'Pelatihan Ekonomi Kreatif';
@endphp

<footer class="footer-premium py-6 py-lg-7 text-white">
  <div class="container">
    <div class="row g-5 mb-5 text-start">
      <div class="col-lg-5 col-md-12">
        <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none mb-3">
          <div class="logo-icon-glow" style="width:34px; height:34px; border-radius:5px;">
            <i class="icon-base ti tabler-bulb text-white fs-5"></i>
          </div>
          <x-brand-logo size="md" />
        </a>
        <p class="text-white-50 mb-4" style="max-width: 400px; font-size: 0.95rem; line-height: 1.65;">
          {{ $institutionDesc }}
        </p>
        <div class="d-flex gap-3">
          <a href="#" class="social-icon-btn"><i class="icon-base ti tabler-brand-instagram"></i></a>
          <a href="#" class="social-icon-btn"><i class="icon-base ti tabler-brand-facebook"></i></a>
          <a href="#" class="social-icon-btn"><i class="icon-base ti tabler-brand-youtube"></i></a>
          <a href="#" class="social-icon-btn"><i class="icon-base ti tabler-mail"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-md-4 col-sm-6">
        <h6 class="text-white fw-bold mb-4" style="font-family: 'Sora', sans-serif;">{{ __('Kategori Pelatihan') }}</h6>
        <ul class="list-unstyled d-flex flex-column gap-2">
          <li><a href="#" class="footer-link">Kuliner Kreatif</a></li>
          <li><a href="#" class="footer-link">Konten Kreator</a></li>
          <li><a href="#" class="footer-link">Desain Grafis</a></li>
          <li><a href="#" class="footer-link">Kriya & Seni Tradisional</a></li>
        </ul>
      </div>

      <div class="col-lg-2 col-md-4 col-sm-6">
        <h6 class="text-white fw-bold mb-4" style="font-family: 'Sora', sans-serif;">{{ __('Tautan Penting') }}</h6>
          <ul class="list-unstyled d-flex flex-column gap-2">
            <li><a href="#beranda" class="footer-link">{{ __('Beranda') }}</a></li>
            <li><a href="#pelatihan" class="footer-link">{{ __('Pelatihan') }}</a></li>
            <li><a href="#langkah" class="footer-link">{{ __('Cara Daftar') }}</a></li>
            <li><a href="#mengapa" class="footer-link">{{ __('Keunggulan') }}</a></li>
            <li><a href="#faq" class="footer-link">{{ __('FAQ') }}</a></li>
            <li><a href="{{ route('koordinator.register') }}" class="footer-link">{{ __('Daftar Koordinator') }}</a></li>
          </ul>
      </div>

      <div class="col-lg-2 col-md-4 col-sm-12">
        <h6 class="text-white fw-bold mb-4" style="font-family: 'Sora', sans-serif;">{{ __('Hubungi Penyelenggara') }}</h6>
        <p class="text-white-50 mb-3 small d-flex align-items-start gap-2" style="line-height: 1.5;">
           <i class="icon-base ti tabler-map-pin text-warning mt-1"></i> {{ $institutionAddress }}
        </p>
        <p class="text-white-50 mb-0 small d-flex align-items-center gap-2">
           <i class="icon-base ti tabler-phone text-warning"></i> {{ $institutionPhone }}
        </p>
      </div>
    </div>

    <div style="width: 100%; height: 1px; background: rgba(255, 255, 255, 0.07); margin-bottom: 24px;"></div>

    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
      <p class="text-white-50 small mb-0">
        &copy; {{ date('Y') }} <span class="text-white fw-medium">{{ $footerCopyright }}</span>. All rights reserved.
      </p>
      <div class="d-flex gap-4">
        <a href="#" class="text-white-50 small text-decoration-none hover-white">{{ __('Kebijakan Privasi') }}</a>
        <a href="#" class="text-white-50 small text-decoration-none hover-white">{{ __('Syarat & Ketentuan') }}</a>
      </div>
    </div>
  </div>
</footer>
