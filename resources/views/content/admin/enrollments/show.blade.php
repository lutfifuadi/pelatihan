@php
$configData = Helper::appClasses();
$pelatihans = \App\Models\Pelatihan::where('is_active', true)->orderBy('nama')->get();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Detail Pendaftaran')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  .content-wrapper {
    font-family: 'Outfit', sans-serif;
    color: #f8fafc;
    position: relative !important;
    overflow: hidden !important;
  }
  .content-wrapper h1,
  .content-wrapper h2,
  .content-wrapper h3,
  .content-wrapper h4,
  .content-wrapper h5,
  .content-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }

  html,
  body,
  .layout-page,
  .content-wrapper,
  .layout-wrapper,
  .layout-container {
    background-color: #0b0f19 !important;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }

  .layout-navbar-fixed .layout-page::before {
    display: none !important;
  }

  .content-wrapper > .container-xxl {
    max-width: 100% !important;
    padding: 0 !important;
  }

  .layout-menu,
  #layout-menu {
    background-color: #0b0f19 !important;
    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
  }
  .layout-menu .app-brand {
    background-color: #0b0f19 !important;
  }
  .layout-menu .menu-inner {
    background-color: #0b0f19 !important;
  }
  .layout-menu .menu-link {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  .layout-menu .menu-item.active > .menu-link {
    color: #ffffff !important;
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3) !important;
  }
  .layout-menu .menu-item.active > .menu-link i {
    color: #ffffff !important;
  }
  .layout-menu .menu-header-text {
    color: rgba(255, 255, 255, 0.4) !important;
  }
  .layout-menu .menu-link:hover {
    background-color: rgba(255, 255, 255, 0.04) !important;
    color: #ffffff !important;
  }
  .layout-menu .menu-inner-shadow {
    background: linear-gradient(#0b0f19 5%, rgba(11, 15, 25, 0) 95%) !important;
  }
  .layout-menu .app-brand .app-brand-text {
    color: #ffffff !important;
  }

  .layout-navbar,
  #layout-navbar {
    background: rgba(15, 23, 42, 0.45) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
  }
  .navbar-detached {
    background: rgba(15, 23, 42, 0.45) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    margin-top: 12px !important;
  }
  #layout-navbar .nav-link {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  #layout-navbar .nav-link:hover {
    color: #ffffff !important;
  }

  .glow-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.4;
    mix-blend-mode: screen;
    pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out;
    z-index: 0;
  }
  .orb-1 {
    width: 450px;
    height: 450px;
    background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
    top: -10%;
    left: -10%;
    animation-duration: 20s;
  }
  .orb-2 {
    width: 550px;
    height: 550px;
    background: radial-gradient(circle, #ec4899 0%, rgba(236, 72, 153, 0) 70%);
    bottom: 5%;
    right: -10%;
    animation-duration: 28s;
  }
  .orb-3 {
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, #06b6d4 0%, rgba(6, 182, 212, 0) 70%);
    top: 35%;
    left: 25%;
    animation-duration: 24s;
  }
  @keyframes orbFloat {
    0% { transform: translate(0, 0) scale(1) rotate(0deg); }
    50% { transform: translate(60px, 40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px, -50px) scale(0.92) rotate(360deg); }
  }

  .text-body-premium {
    color: rgba(255, 255, 255, 0.65) !important;
  }

  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border-radius: 5px !important;
    position: relative;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
  }

  .stat-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 5px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    flex-shrink: 0;
  }

  .stat-icon-primary {
    background: rgba(99, 102, 241, 0.12);
    color: #6366f1;
  }

  .btn-secondary-custom {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    border-radius: 5px;
    transition: all 0.3s ease;
  }
  .btn-secondary-custom:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
  }

  .nav-btn-prevnext {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 1rem;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 5px;
    color: rgba(255, 255, 255, 0.65);
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    cursor: pointer;
  }
  .nav-btn-prevnext:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
  }
  .nav-btn-prevnext.disabled {
    opacity: 0.35;
    cursor: not-allowed;
    pointer-events: none;
    transform: none;
  }
  .nav-btn-prevnext .nav-name {
    color: #f8fafc;
    font-weight: 600;
  }
  .nav-btn-prevnext .nav-label {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.75rem;
  }

  .detail-section-title {
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .detail-section-title i {
    color: #6366f1;
    font-size: 1.2rem;
  }

  .detail-label {
    color: rgba(255, 255, 255, 0.45);
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .detail-value {
    color: #f8fafc;
    font-size: 0.95rem;
    font-weight: 500;
  }

  .detail-divider {
    border-color: rgba(255, 255, 255, 0.06);
    margin: 1.5rem 0;
  }

  .badge-premium {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 4px 12px;
    font-weight: 500;
    font-size: 0.75rem;
  }
  .badge-premium-success { background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #34d399; }
  .badge-premium-warning { background: rgba(245, 158, 11, 0.15); border-color: rgba(245, 158, 11, 0.3); color: #fbbf24; }
  .badge-premium-danger { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #f87171; }
  .badge-premium-info { background: rgba(96, 165, 250, 0.15); border-color: rgba(96, 165, 250, 0.3); color: #93c5fd; }

  .timeline-premium {
    position: relative;
    padding-left: 1.5rem;
    border-left: 1px solid rgba(255, 255, 255, 0.08);
  }
  .timeline-item-premium {
    position: relative;
    padding-bottom: 1.5rem;
  }
  .timeline-item-premium:last-child {
    padding-bottom: 0;
  }
  .timeline-badge-premium {
    position: absolute;
    left: -2.1rem;
    top: 2px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0b0f19;
    border: 2px solid rgba(255, 255, 255, 0.15);
    z-index: 2;
  }
  .timeline-badge-premium.completed {
    border-color: #10b981;
    background: #10b981;
    color: #0b0f19;
  }
  .timeline-badge-premium.pending {
    border-color: rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.4);
  }
  .timeline-badge-premium i {
    font-size: 0.75rem;
  }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    {{-- Header --}}
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-3">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <a href="{{ route('admin.enrollments.index') }}" class="text-body-premium text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.85rem;">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
          </a>
        </div>

        <div class="text-center flex-grow-1">
          <h4 class="fw-bold text-white mb-1">Detail Pendaftaran</h4>
          <p class="text-body-premium mb-0" style="font-size: 0.95rem;">
            {{ $enrollment->user?->name ?? 'User tidak ditemukan' }} — {{ $enrollment->pelatihan->nama }}
          </p>
        </div>

        <div>
          @switch($enrollment->status)
            @case('pending') <span class="badge-premium badge-premium-warning">Pending</span> @break
            @case('approved') <span class="badge-premium badge-premium-success">Approved (Tahap 1)</span> @break
            @case('rejected') <span class="badge-premium badge-premium-danger">Ditolak</span> @break
            @case('waitlist') <span class="badge-premium badge-premium-info">Cadangan</span> @break
          @endswitch
        </div>

        {{-- Badge Transfer jika ada --}}
        @php $isTransferred = str_contains($enrollment->notes ?? '', '[Alihkan:'); @endphp
        @if($isTransferred)
          <div class="mt-2" style="font-size: 0.8rem; color: #93c5fd; background: rgba(96, 165, 250, 0.1); padding: 8px 12px; border-radius: 5px; border: 1px solid rgba(96, 165, 250, 0.2);">
            <i class="icon-base ti tabler-arrows-shuffle me-1"></i>
            Dialihkan dari pelatihan sebelumnya.
          </div>
        @endif

        @if($enrollment->status === 'approved')
          <div class="mt-2" style="font-size: 0.8rem; color: #fbbf24; background: rgba(251, 191, 36, 0.1); padding: 8px 12px; border-radius: 5px; border: 1px solid rgba(251, 191, 36, 0.2);">
            <i class="icon-base ti tabler-info-circle me-1"></i>
            Peserta telah di-approve tahap 1. Silakan input data peserta ke <strong>NewBimma Disnaker Kota Bandung</strong> untuk approve final.
          </div>
        @endif
      </div>
    </div>

    {{-- Info Kapasitas Pelatihan --}}
    <div class="glass-card-premium px-4 py-3 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h6 class="text-white fw-semibold mb-1">
            <i class="icon-base ti tabler-users me-1"></i> Kapasitas Pelatihan
          </h6>
          <span class="text-body-premium" style="font-size: 0.85rem;">
            {{ $enrollment->pelatihan->nama }} (Batch: {{ $enrollment->pelatihan->batch }})
          </span>
        </div>
        <div class="d-flex gap-3 flex-wrap">
          <div class="text-center px-3 py-2" style="background: rgba(99, 102, 241, 0.1); border-radius: 5px; min-width: 100px;">
            <div class="fw-bold text-white" style="font-size: 1.2rem;">{{ $totalPendaftar }}</div>
            <small class="text-body-premium">Total Pendaftar</small>
          </div>
          <div class="text-center px-3 py-2" style="background: rgba(16, 185, 129, 0.1); border-radius: 5px; min-width: 100px;">
            <div class="fw-bold text-white" style="font-size: 1.2rem;">{{ $approvedCount }}</div>
            <small class="text-body-premium">Approved</small>
          </div>
          <div class="text-center px-3 py-2" style="background: rgba(96, 165, 250, 0.1); border-radius: 5px; min-width: 100px;">
            <div class="fw-bold text-white" style="font-size: 1.2rem;">{{ $waitlistCount }}</div>
            <small class="text-body-premium">Cadangan</small>
          </div>
          <div class="text-center px-3 py-2" style="background: {{ $sisaBelumTercek > 0 ? 'rgba(251, 191, 36, 0.1)' : 'rgba(16, 185, 129, 0.1)' }}; border-radius: 5px; min-width: 100px;">
            <div class="fw-bold text-white" style="font-size: 1.2rem;">{{ $sisaBelumTercek }}</div>
            <small class="text-body-premium">Sisa Belum Tercek</small>
          </div>
        </div>
      </div>
    </div>

    {{-- Navigasi Prev / Next --}}
    <div class="glass-card-premium px-4 px-xl-5 py-3 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          @if($previousEnrollment)
            <a href="{{ route('admin.enrollments.show', $previousEnrollment->id) }}"
               class="nav-btn-prevnext">
              <i class="icon-base ti tabler-chevron-left"></i>
              <span>
                <span class="nav-name">{{ $previousEnrollment->user?->name ?? 'User tidak ditemukan' }}</span>
                <span class="nav-label">Sebelumnya</span>
              </span>
            </a>
          @else
            <span class="nav-btn-prevnext disabled">
              <i class="icon-base ti tabler-chevron-left"></i>
              <span>
                <span class="nav-label">Sebelumnya</span>
              </span>
            </span>
          @endif
        </div>

        <div>
          @if($nextEnrollment)
            <a href="{{ route('admin.enrollments.show', $nextEnrollment->id) }}"
               class="nav-btn-prevnext">
              <span>
                <span class="nav-name">{{ $nextEnrollment->user?->name ?? 'User tidak ditemukan' }}</span>
                <span class="nav-label">Selanjutnya</span>
              </span>
              <i class="icon-base ti tabler-chevron-right"></i>
            </a>
          @else
            <span class="nav-btn-prevnext disabled">
              <span>
                <span class="nav-label">Selanjutnya</span>
              </span>
              <i class="icon-base ti tabler-chevron-right"></i>
            </span>
          @endif
        </div>
      </div>
    </div>

    <div class="row g-4">

      {{-- KOLOM KIRI (Konten Utama) --}}
      <div class="col-lg-8 col-md-7">
        <div class="row g-4">

          {{-- Data Pribadi --}}
          <div class="col-12">
            <div class="glass-card-premium px-4 py-4">
              <h5 class="detail-section-title">
                <i class="icon-base ti tabler-id"></i> Data Pribadi
              </h5>
              <hr class="detail-divider">
              <div class="row g-3">
                <div class="col-md-4">
                  <div class="detail-label">Nama Lengkap</div>
                  <div class="detail-value">{{ $enrollment->user?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">NIK</div>
                  <div class="detail-value">{{ $enrollment->user?->nik ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">WhatsApp</div>
                  <div class="detail-value">{{ $enrollment->user?->whatsapp ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">Email</div>
                  <div class="detail-value">{{ $enrollment->user?->email ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">Jenis Kelamin</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->jenis_kelamin ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">Tempat, Tanggal Lahir</div>
                  <div class="detail-value">
                    @if($enrollment->user?->pesertaProfile && $enrollment->user->pesertaProfile->tempat_lahir)
                      {{ $enrollment->user->pesertaProfile->tempat_lahir }},
                    @endif
                    @if($enrollment->user?->pesertaProfile && $enrollment->user->pesertaProfile->tanggal_lahir)
                      {{ $enrollment->user->pesertaProfile->tanggal_lahir }}
                    @endif
                    @if($enrollment->user?->pesertaProfile && $enrollment->user->pesertaProfile->bulan_lahir)
                      /{{ $enrollment->user->pesertaProfile->bulan_lahir }}
                    @endif
                    @if($enrollment->user?->pesertaProfile && $enrollment->user->pesertaProfile->tahun_lahir)
                      /{{ $enrollment->user->pesertaProfile->tahun_lahir }}
                    @endif
                    @if(!$enrollment->user?->pesertaProfile || (!$enrollment->user?->pesertaProfile?->tempat_lahir && !$enrollment->user?->pesertaProfile?->tanggal_lahir))
                      -
                    @endif
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">Usia</div>
                  <div class="detail-value">{{ $usia ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">Link Medsos</div>
                  <div class="detail-value">
                    @php
                      $medsos = $enrollment->user?->pesertaProfile?->link_medsos ?? null;
                    @endphp
                    @if($medsos && is_array($medsos))
                      <div class="d-flex flex-wrap gap-2">
                        @php $hasMedsos = false; @endphp
                        @foreach($medsos as $item)
                          @if(!empty($item['url']))
                            @php $hasMedsos = true; @endphp
                            <a href="{{ $item['url'] }}" target="_blank" class="badge bg-label-primary d-inline-flex align-items-center gap-1" style="text-decoration: none; font-size: 0.75rem; padding: 6px 12px;">
                              @php
                                $platform = strtolower($item['platform'] ?? 'link');
                                $iconClass = 'tabler-link';
                                if (str_contains($platform, 'instagram')) $iconClass = 'tabler-brand-instagram';
                                elseif (str_contains($platform, 'facebook')) $iconClass = 'tabler-brand-facebook';
                                elseif (str_contains($platform, 'twitter') || str_contains($platform, 'x.com')) $iconClass = 'tabler-brand-x';
                                elseif (str_contains($platform, 'linkedin')) $iconClass = 'tabler-brand-linkedin';
                                elseif (str_contains($platform, 'youtube')) $iconClass = 'tabler-brand-youtube';
                                elseif (str_contains($platform, 'tiktok')) $iconClass = 'tabler-brand-tiktok';
                              @endphp
                              <i class="icon-base ti {{ $iconClass }} fs-6"></i>
                              {{ $item['platform'] ?? 'Medsos' }}
                            </a>
                          @endif
                        @endforeach
                        @if(!$hasMedsos)
                          -
                        @endif
                      </div>
                    @elseif($medsos && is_string($medsos))
                      {{ $medsos }}
                    @else
                      -
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Alamat --}}
          <div class="col-12">
            <div class="glass-card-premium px-4 py-4">
              <h5 class="detail-section-title">
                <i class="icon-base ti tabler-map-pin"></i> Alamat
              </h5>
              <hr class="detail-divider">
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="detail-label">Alamat KTP</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->alamat_ktp ?? '-' }}</div>
                </div>
                <div class="col-md-2">
                  <div class="detail-label">RT</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->rt ?? '-' }}</div>
                </div>
                <div class="col-md-2">
                  <div class="detail-label">RW</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->rw ?? '-' }}</div>
                </div>
                <div class="col-md-2">
                  <div class="detail-label">Kodepos</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->kodepos ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                  <div class="detail-label">Kelurahan</div>
                  <div class="detail-value">{{ $enrollment->user?->kelurahan?->name ?? $enrollment->user?->pesertaProfile?->kelurahan ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                  <div class="detail-label">Kecamatan</div>
                  <div class="detail-value">{{ $enrollment->user?->kecamatan?->name ?? $enrollment->user?->pesertaProfile?->kecamatan ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                  <div class="detail-label">Kota</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->kota ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                  <div class="detail-label">Provinsi</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->provinsi ?? '-' }}</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Pendidikan & Pekerjaan --}}
          <div class="col-12">
            <div class="glass-card-premium px-4 py-4">
              <h5 class="detail-section-title">
                <i class="icon-base ti tabler-book"></i> Pendidikan & Pekerjaan
              </h5>
              <hr class="detail-divider">
              <div class="row g-3">
                <div class="col-md-3">
                  <div class="detail-label">Pendidikan Terakhir</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->pendidikan_terakhir ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                  <div class="detail-label">Nama Institusi</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->nama_institusi ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                  <div class="detail-label">Jurusan</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->jurusan ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                  <div class="detail-label">Tahun Lulus</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->tahun_lulus ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">Status Pekerjaan</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->status_pekerjaan ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">Nama Perusahaan</div>
                  <div class="detail-value">{{ $enrollment->user?->pesertaProfile?->nama_perusahaan ?? '-' }}</div>
                </div>
              </div>
            </div>
          </div>



          {{-- Jawaban Pertanyaan (Tahap 5) --}}
          <div class="col-12">
            <div class="glass-card-premium px-4 py-4">
              <h5 class="detail-section-title">
                <i class="icon-base ti tabler-help-circle"></i> Jawaban Pertanyaan (Tahap 5)
              </h5>
              <hr class="detail-divider">
              @php
                $jawaban = $enrollment->user?->pesertaProfile?->jawaban_pertanyaan ?? [];
                if (is_string($jawaban)) {
                    $jawaban = json_decode($jawaban, true) ?? [];
                }
                $fieldLabels = [
                  'pengetahuan_asep' => 'Apa yang Anda ketahui tentang Bapak H. Asep Mulyadi, S.H.?',
                  'alasan_pelatihan' => 'Alasan mengikuti pelatihan',
                  'pengalaman_bisnis' => 'Pengalaman bisnis dalam bidang pelatihan terkait',
                  'rencana_setelah_pelatihan' => 'Minat/rencana kedepan setelah mengikuti pelatihan',
                  'punya_usaha' => 'Apakah sudah memiliki usaha?',
                  'jenis_usaha' => 'Jenis usaha yang sedang dijalankan',
                  'usaha_dimiliki' => 'Usaha yang dimiliki',
                  'usaha_dimiliki_other' => 'Usaha yang dimiliki (lainnya)',
                  'nama_usaha' => 'Nama usaha yang sedang dijalankan',
                  'nama_usaha_other' => 'Nama usaha (lainnya)',
                  'kendala_usaha' => 'Kendala yang dialami dalam menjalankan usaha',
                ];
              @endphp
              @if(!empty($jawaban))
                <div class="row g-3">
                  @foreach($jawaban as $key => $value)
                    @php
                      $label = $fieldLabels[$key] ?? ucwords(str_replace('_', ' ', $key));
                    @endphp
                    <div class="col-12">
                      <div class="detail-label" style="text-transform: none; letter-spacing: normal; font-size: 0.85rem; color: rgba(255, 255, 255, 0.45);">{{ $label }}</div>
                      <div class="detail-value fw-bold text-white mt-1" style="white-space: pre-wrap; font-size: 0.95rem;">{{ !empty($value) ? $value : '-' }}</div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="text-white-50 text-center py-3" style="font-size: 0.95rem;">
                  Belum ada jawaban pertanyaan untuk tahap ini
                </div>
              @endif
            </div>
          </div>

          {{-- Informasi Lainnya --}}
          <div class="col-12">
            <div class="glass-card-premium px-4 py-4">
              <h5 class="detail-section-title">
                <i class="icon-base ti tabler-info-circle"></i> Informasi Lainnya
              </h5>
              <hr class="detail-divider">
              <div class="row g-3">
                <div class="col-md-3">
                  <div class="detail-label">Tanggal Daftar</div>
                  <div class="detail-value">{{ $enrollment->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="col-md-3">
                  <div class="detail-label">Status</div>
                  <div class="detail-value">
                    @if($enrollment->user?->is_active)
                      <span class="badge-premium badge-premium-success">Aktif</span>
                    @else
                      <span class="badge-premium badge-premium-warning">Nonaktif</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      {{-- KOLOM KANAN (Sidebar) --}}
      <div class="col-lg-4 col-md-5">

        {{-- Status & Progress Pendaftaran --}}
        <div class="glass-card-premium px-4 py-4">
          <h5 class="detail-section-title">
            <i class="icon-base ti tabler-chart-donut"></i> Status &amp; Progress Pendaftaran
          </h5>
          <hr class="detail-divider">

          @php
            $profile = $enrollment->user?->pesertaProfile;
            $step1Done = $profile && !empty($profile->nama_lengkap) && !empty($profile->nik);
            $step2Done = $profile && !empty($profile->alamat_ktp) && !empty($enrollment->user?->whatsapp);
            $step3Done = $profile && !empty($profile->pendidikan_terakhir) && !empty($profile->nama_institusi);
            $step4Done = $profile && !empty($profile->pelatihan_id);
            $step5Done = $profile && !empty($profile->jawaban_pertanyaan);
            $step6Done = $profile && ($profile->is_completed ?? false);
          @endphp

          <div class="mb-4">
            <div class="detail-label mb-2">Status Final</div>
            @if($step6Done)
              <span class="badge-premium badge-premium-success d-inline-flex align-items-center gap-1">
                <i class="icon-base ti tabler-circle-check fs-6"></i> Sudah Submit Final
              </span>
            @else
              <span class="badge-premium badge-premium-warning d-inline-flex align-items-center gap-1">
                <i class="icon-base ti tabler-alert-circle fs-6"></i> Draf / Belum Submit
              </span>
            @endif
          </div>

          <div class="timeline-premium">
            <div class="timeline-item-premium">
              <div class="timeline-badge-premium {{ $step1Done ? 'completed' : 'pending' }}">
                <i class="icon-base ti tabler-{{ $step1Done ? 'check' : 'circle' }}"></i>
              </div>
              <div class="ps-2">
                <h6 class="mb-1 text-white" style="font-size: 0.95rem;">Tahap 1: Data Pribadi</h6>
                <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                  {{ $step1Done ? 'Selesai diisi' : 'Belum selesai' }}
                </p>
              </div>
            </div>
            <div class="timeline-item-premium">
              <div class="timeline-badge-premium {{ $step2Done ? 'completed' : 'pending' }}">
                <i class="icon-base ti tabler-{{ $step2Done ? 'check' : 'circle' }}"></i>
              </div>
              <div class="ps-2">
                <h6 class="mb-1 text-white" style="font-size: 0.95rem;">Tahap 2: Alamat &amp; Kontak</h6>
                <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                  {{ $step2Done ? 'Selesai diisi' : 'Belum selesai' }}
                </p>
              </div>
            </div>
            <div class="timeline-item-premium">
              <div class="timeline-badge-premium {{ $step3Done ? 'completed' : 'pending' }}">
                <i class="icon-base ti tabler-{{ $step3Done ? 'check' : 'circle' }}"></i>
              </div>
              <div class="ps-2">
                <h6 class="mb-1 text-white" style="font-size: 0.95rem;">Tahap 3: Riwayat Pendidikan</h6>
                <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                  {{ $step3Done ? 'Selesai diisi' : 'Belum selesai' }}
                </p>
              </div>
            </div>
            <div class="timeline-item-premium">
              <div class="timeline-badge-premium {{ $step4Done ? 'completed' : 'pending' }}">
                <i class="icon-base ti tabler-{{ $step4Done ? 'check' : 'circle' }}"></i>
              </div>
              <div class="ps-2">
                <h6 class="mb-1 text-white" style="font-size: 0.95rem;">Tahap 4: Pilihan Pelatihan</h6>
                <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                  {{ $step4Done ? 'Selesai diisi' : 'Belum selesai' }}
                </p>
              </div>
            </div>
            <div class="timeline-item-premium">
              <div class="timeline-badge-premium {{ $step5Done ? 'completed' : 'pending' }}">
                <i class="icon-base ti tabler-{{ $step5Done ? 'check' : 'circle' }}"></i>
              </div>
              <div class="ps-2">
                <h6 class="mb-1 text-white" style="font-size: 0.95rem;">Tahap 5: Dokumen &amp; Pertanyaan</h6>
                <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                  {{ $step5Done ? 'Selesai diisi' : 'Belum selesai' }}
                </p>
              </div>
            </div>
            <div class="timeline-item-premium">
              <div class="timeline-badge-premium {{ $step6Done ? 'completed' : 'pending' }}">
                <i class="icon-base ti tabler-{{ $step6Done ? 'check' : 'circle' }}"></i>
              </div>
              <div class="ps-2">
                <h6 class="mb-1 text-white" style="font-size: 0.95rem;">Tahap 6: Review &amp; Kirim</h6>
                <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                  {{ $step6Done ? 'Sudah Submit Final' : 'Belum disubmit' }}
                </p>
              </div>
            </div>
          </div>
        </div>

        {{-- Timeline Pendaftaran --}}
        <div class="glass-card-premium px-4 py-4 mt-4">
          <h5 class="detail-section-title">
            <i class="icon-base ti tabler-timeline"></i> Timeline Pendaftaran
          </h5>
          <hr class="detail-divider">
          <div class="timeline-premium">
            <div class="timeline-item-premium">
              <div class="timeline-badge-premium completed">
                <i class="icon-base ti tabler-check"></i>
              </div>
              <div class="ps-2">
                <div class="detail-label mb-0">Tanggal Daftar</div>
                <div class="detail-value">{{ $enrollment->created_at->format('d/m/Y H:i') }}</div>
              </div>
            </div>
            @if($enrollment->approved_at)
            <div class="timeline-item-premium">
              <div class="timeline-badge-premium completed" style="border-color: #10b981; background: #10b981;">
                <i class="icon-base ti tabler-check"></i>
              </div>
              <div class="ps-2">
                <div class="detail-label mb-0" style="color: #34d399;">Tanggal Approve (Tahap 1)</div>
                <div class="detail-value" style="color: #34d399;">{{ $enrollment->approved_at->format('d/m/Y H:i') }}</div>
              </div>
            </div>
            @endif
            @if($enrollment->rejected_at)
            <div class="timeline-item-premium">
              <div class="timeline-badge-premium completed" style="border-color: #ef4444; background: #ef4444;">
                <i class="icon-base ti tabler-x"></i>
              </div>
              <div class="ps-2">
                <div class="detail-label mb-0" style="color: #f87171;">Tanggal Ditolak</div>
                <div class="detail-value" style="color: #f87171;">{{ $enrollment->rejected_at->format('d/m/Y H:i') }}</div>
              </div>
            </div>
            @endif
            @if($enrollment->waitlist_promoted_at)
            <div class="timeline-item-premium">
              <div class="timeline-badge-premium completed" style="border-color: #60a5fa; background: #60a5fa;">
                <i class="icon-base ti tabler-arrow-up"></i>
              </div>
              <div class="ps-2">
                <div class="detail-label mb-0" style="color: #93c5fd;">Dipromosikan dari Cadangan</div>
                <div class="detail-value" style="color: #93c5fd;">{{ $enrollment->waitlist_promoted_at->format('d/m/Y H:i') }}</div>
              </div>
            </div>
            @endif
            @if($enrollment->notes)
            <div class="timeline-item-premium">
              <div class="ps-2 pt-2">
                <div class="detail-label mb-1">Catatan</div>
                <div class="detail-value" style="background: rgba(255,255,255,0.04); padding: 10px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.06); font-size: 0.85rem;">
                  {{ $enrollment->notes }}
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>

        {{-- Aksi --}}
        <div class="glass-card-premium px-4 py-4 mt-4">
          <h5 class="detail-section-title">
            <i class="icon-base ti tabler-settings"></i> Aksi
          </h5>
          <hr class="detail-divider">

          <div class="d-flex flex-column gap-2">
            {{-- Dropdown Ubah Status --}}
            <div class="mb-2">
              <label class="detail-label mb-2">Ubah Status Pendaftaran</label>
              <div class="d-flex gap-2">
                <select class="form-select" id="changeStatusSelect" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #f8fafc; border-radius: 5px;">
                  <option value="">-- Pilih Status --</option>
                  <option value="pending">⏳ Pending</option>
                  <option value="approved">✅ Approved</option>
                  <option value="rejected">❌ Rejected</option>
                  <option value="waitlist">🟡 Waitlist</option>
                </select>
                <button type="button" class="btn btn-primary" id="changeStatusBtn" style="border-radius: 5px; font-weight: 600; padding: 8px 16px; white-space: nowrap;" disabled>
                  <i class="icon-base ti tabler-arrows-exchange fs-6 me-1"></i> Ubah
                </button>
              </div>
            </div>

            {{-- Tombol Alihkan Pelatihan --}}
            @if(in_array($enrollment->status, ['approved', 'waitlist']))
            <button type="button" class="btn btn-secondary-custom w-100 d-inline-flex align-items-center justify-content-center gap-2" id="transferBtn" style="border-radius: 5px; font-weight: 600; padding: 10px;">
              <i class="icon-base ti tabler-arrows-shuffle fs-6"></i> Alihkan Pelatihan
            </button>
            @endif

            {{-- Reset --}}
            @if(in_array($enrollment->status, ['approved', 'waitlist', 'pending', 'rejected']))
            <form action="{{ route('admin.enrollments.reset', $enrollment) }}" method="POST" class="reset-enrollment-form mt-2" data-name="{{ $enrollment->user?->name ?? 'Unknown' }}" data-pelatihan="{{ $enrollment->pelatihan->nama }}">
              @csrf
              <button type="submit" class="btn btn-warning w-100 d-inline-flex align-items-center justify-content-center gap-2" style="border-radius: 5px; font-weight: 600; padding: 10px; background: linear-gradient(135deg, #f59e0b, #d97706); border: none;">
                <i class="icon-base ti tabler-refresh fs-6"></i> Reset Pendaftaran
              </button>
            </form>
            @endif
          </div>
        </div>

      </div>

    </div>
  </div>

  {{-- Modal Reject --}}
  <div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="background: #0b0f19; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px;">
        <div class="modal-header border-0">
          <h6 class="text-white fw-bold mb-0">Tolak Pendaftaran</h6>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('admin.enrollments.reject', $enrollment) }}" method="POST">
          @csrf
          <div class="modal-body">
            <p class="text-body-premium small mb-2">Alasan penolakan (opsional):</p>
            <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Kuota penuh, tidak memenuhi syarat..." style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #f8fafc; border-radius: 5px;"></textarea>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.7); border-radius: 5px;">Batal</button>
            <button type="submit" class="btn btn-danger" style="border-radius: 5px;">Ya, Tolak</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Change Status --}}
  <div class="modal fade" id="changeStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="background: #0b0f19; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px;">
        <div class="modal-header border-0">
          <h6 class="text-white fw-bold mb-0">Ubah Status Pendaftaran</h6>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form id="changeStatusForm" action="{{ route('admin.enrollments.change-status', $enrollment) }}" method="POST">
          @csrf
          <div class="modal-body">
            <div id="changeStatusInfo"></div>
            <input type="hidden" name="status" id="changeStatusNewStatus">
            <input type="hidden" name="notes" value="">
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.7); border-radius: 5px;">Batal</button>
            <button type="submit" class="btn btn-primary" style="border-radius: 5px;">Ya, Ubah Status</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Transfer Pelatihan --}}
  <div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="background: #0b0f19; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px;">
        <div class="modal-header border-0">
          <h6 class="text-white fw-bold mb-0">Alihkan Pelatihan</h6>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('admin.enrollments.transfer', $enrollment) }}" method="POST">
          @csrf
          <div class="modal-body">
            {{-- Info peserta --}}
            <div style="background: rgba(255,255,255,0.04); padding: 12px; border-radius: 5px; margin-bottom: 16px;">
              <div class="d-flex justify-content-between mb-2">
                <span class="text-body-premium">Peserta:</span>
                <span class="text-white fw-semibold">{{ $enrollment->user?->name ?? '-' }}</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-body-premium">Pelatihan Asal:</span>
                <span class="text-white">{{ $enrollment->pelatihan->nama }} (Batch {{ $enrollment->pelatihan->batch }})</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-body-premium">Status:</span>
                <span class="badge-premium">
                  @switch($enrollment->status)
                    @case('approved') Approved (Tahap 1) @break
                    @case('waitlist') Cadangan @break
                    @default {{ ucfirst($enrollment->status) }}
                  @endswitch
                </span>
              </div>
            </div>

            {{-- Pilih Pelatihan Tujuan --}}
            <div class="mb-3">
              <label class="detail-label mb-2">Pilih Pelatihan Tujuan <span style="color: #f87171;">*</span></label>
              <select name="pelatihan_id" class="form-select" required style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #f8fafc; border-radius: 5px;">
                <option value="">-- Pilih Pelatihan --</option>
                @foreach($pelatihans as $p)
                  <option value="{{ $p->id }}" {{ $p->id == $enrollment->pelatihan_id ? 'disabled' : '' }}>
                    {{ $p->nama }} (Batch {{ $p->batch }}) {{ $p->id == $enrollment->pelatihan_id ? '— saat ini' : '' }}
                  </option>
                @endforeach
              </select>
              <div class="mt-1 text-body-premium" style="font-size: 0.7rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i> Hanya pelatihan aktif yang ditampilkan.
              </div>
            </div>

            {{-- Alasan --}}
            <div class="mb-3">
              <label class="detail-label mb-2">Alasan Pengalihan <span style="color: #f87171;">*</span></label>
              <textarea name="notes" class="form-control" rows="3" placeholder="Jelaskan alasan pengalihan..." required style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #f8fafc; border-radius: 5px;"></textarea>
            </div>

            {{-- Peringatan --}}
            <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); padding: 10px; border-radius: 5px;">
              <p class="mb-0" style="color: #f87171; font-size: 0.8rem;">
                <i class="icon-base ti tabler-alert-triangle me-1"></i>
                <strong>Perhatian:</strong> Data kehadiran dan sertifikat yang terkait dengan pelatihan saat ini akan dihapus.
              </p>
            </div>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.7); border-radius: 5px;">Batal</button>
            <button type="submit" class="btn btn-primary" style="border-radius: 5px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none;">
              <i class="icon-base ti tabler-arrows-shuffle me-1"></i> Ya, Alihkan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
<script>
  // CHANGE STATUS - buka modal
  document.getElementById('changeStatusSelect')?.addEventListener('change', function() {
    document.getElementById('changeStatusBtn').disabled = !this.value;
  });

  document.getElementById('changeStatusBtn')?.addEventListener('click', function() {
    const status = document.getElementById('changeStatusSelect').value;
    if (!status) return;

    // Isi modal dengan data
    document.getElementById('changeStatusNewStatus').value = status;
    const labels = {'pending': '⏳ Pending', 'approved': '✅ Approved', 'rejected': '❌ Rejected', 'waitlist': '🟡 Waitlist'};
    document.getElementById('changeStatusInfo').innerHTML = `
      <div style="background: rgba(255,255,255,0.04); padding: 12px; border-radius: 5px; margin-bottom: 12px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
          <span class="text-body-premium">Status Saat Ini:</span>
          <span class="badge-premium">${document.querySelector('.badge-premium')?.textContent?.trim() || '{{ ucfirst($enrollment->status) }}'}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
          <span class="text-body-premium">Status Baru:</span>
          <span class="badge-premium">${labels[status] || status}</span>
        </div>
      </div>
    `;

    // Tampilkan modal
    const modal = new bootstrap.Modal(document.getElementById('changeStatusModal'));
    modal.show();
  });

  // CHANGE STATUS - submit
  document.getElementById('changeStatusForm')?.addEventListener('submit', function(e) {
    const notes = document.getElementById('changeStatusNotes').value.trim();
    if (!notes) {
      e.preventDefault();
      Swal.fire({
        title: 'Alasan Diperlukan',
        text: 'Harap isi alasan perubahan status.',
        icon: 'warning',
        background: '#0f172a',
        color: '#f8fafc',
        confirmButtonText: 'OK',
        customClass: { confirmButton: 'btn btn-primary px-4 py-2 border-0' },
        buttonsStyling: false,
      });
    }
  });

  // TRANSFER - buka modal
  document.getElementById('transferBtn')?.addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('transferModal'));
    modal.show();
  });

  // Reset Enrollment Forms
  document.addEventListener('submit', function(e) {
    const form = e.target.closest('.reset-enrollment-form');
    if (!form) return;

    e.preventDefault();
    const userName = form.getAttribute('data-name');
    const pelatihanNama = form.getAttribute('data-pelatihan');

    Swal.fire({
      title: 'Reset Pendaftaran?',
      html: `<div style="margin-bottom: 0.25rem;">Pendaftaran <strong style="color: #fbbf24;">${userName}</strong> untuk pelatihan</div><div><strong style="color: #93c5fd;">${pelatihanNama}</strong> akan dihapus.</div><div class="mt-3 pt-2" style="border-top: 1px solid rgba(255,255,255,0.06); color: rgba(255,255,255,0.5); font-size: 0.8rem;">Peserta dapat mendaftar ulang untuk pelatihan yang benar.</div>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Reset!',
      cancelButtonText: 'Batal',
      reverseButtons: true,
      background: '#0f172a',
      color: '#f8fafc',
      customClass: {
        popup: 'swal2-custom-popup shadow-lg',
        title: 'swal2-custom-title',
        htmlContainer: 'swal2-custom-text',
        actions: 'swal2-custom-actions gap-3',
        confirmButton: 'btn btn-warning px-4 py-2 border-0 fw-semibold',
        cancelButton: 'btn btn-secondary-custom px-4 py-2 border-0',
      },
      buttonsStyling: false,
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
</script>
@endsection
