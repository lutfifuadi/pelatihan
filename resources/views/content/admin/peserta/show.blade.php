@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Detail Peserta')

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

  .btn-glow-premium {
    background: linear-gradient(135deg, #ffc107, #ff9800) !important;
    border: none;
    color: #0b0f19 !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
    transition: all 0.3s ease;
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.4);
    background: linear-gradient(135deg, #ffca28, #ffa726) !important;
    color: #0b0f19 !important;
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

</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-primary">
            <i class="icon-base ti tabler-user fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Detail Peserta</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Informasi lengkap peserta {{ $peserta->name }}
            </p>
          </div>
        </div>
        <a href="{{ route('admin.peserta.index') }}" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-arrow-left"></i> Kembali
        </a>
      </div>
    </div>

    <div class="row g-4">

      <div class="col-12">
        <div class="glass-card-premium px-4 py-4">
          <h5 class="detail-section-title">
            <i class="icon-base ti tabler-id"></i> Data Pribadi
          </h5>
          <hr class="detail-divider">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="detail-label">Nama Lengkap</div>
              <div class="detail-value">{{ $peserta->name }}</div>
            </div>
            <div class="col-md-4">
              <div class="detail-label">NIK</div>
              <div class="detail-value">{{ $peserta->nik ?? '-' }}</div>
            </div>
            <div class="col-md-4">
              <div class="detail-label">WhatsApp</div>
              <div class="detail-value">{{ $peserta->whatsapp ?? '-' }}</div>
            </div>
            <div class="col-md-4">
              <div class="detail-label">Email</div>
              <div class="detail-value">{{ $peserta->email ?? '-' }}</div>
            </div>
            <div class="col-md-4">
              <div class="detail-label">Jenis Kelamin</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->jenis_kelamin ?? '-' }}</div>
            </div>
            <div class="col-md-4">
              <div class="detail-label">Tempat, Tanggal Lahir</div>
              <div class="detail-value">
                @if($peserta->pesertaProfile && $peserta->pesertaProfile->tempat_lahir)
                  {{ $peserta->pesertaProfile->tempat_lahir }},
                @endif
                @if($peserta->pesertaProfile && $peserta->pesertaProfile->tanggal_lahir)
                  {{ $peserta->pesertaProfile->tanggal_lahir }}
                @endif
                @if($peserta->pesertaProfile && $peserta->pesertaProfile->bulan_lahir)
                  /{{ $peserta->pesertaProfile->bulan_lahir }}
                @endif
                @if($peserta->pesertaProfile && $peserta->pesertaProfile->tahun_lahir)
                  /{{ $peserta->pesertaProfile->tahun_lahir }}
                @endif
                @if(!$peserta->pesertaProfile || (!$peserta->pesertaProfile->tempat_lahir && !$peserta->pesertaProfile->tanggal_lahir))
                  -
                @endif
              </div>
            </div>
            <div class="col-md-4">
              <div class="detail-label">Link Medsos</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->link_medsos ?? '-' }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="glass-card-premium px-4 py-4">
          <h5 class="detail-section-title">
            <i class="icon-base ti tabler-map-pin"></i> Alamat
          </h5>
          <hr class="detail-divider">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="detail-label">Alamat KTP</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->alamat_ktp ?? '-' }}</div>
            </div>
            <div class="col-md-2">
              <div class="detail-label">RT</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->rt ?? '-' }}</div>
            </div>
            <div class="col-md-2">
              <div class="detail-label">RW</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->rw ?? '-' }}</div>
            </div>
            <div class="col-md-2">
              <div class="detail-label">Kodepos</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->kodepos ?? '-' }}</div>
            </div>
            <div class="col-md-3">
              <div class="detail-label">Kelurahan</div>
              <div class="detail-value">{{ $peserta->kelurahan->name ?? $peserta->pesertaProfile->kelurahan ?? '-' }}</div>
            </div>
            <div class="col-md-3">
              <div class="detail-label">Kecamatan</div>
              <div class="detail-value">{{ $peserta->kecamatan->name ?? $peserta->pesertaProfile->kecamatan ?? '-' }}</div>
            </div>
            <div class="col-md-3">
              <div class="detail-label">Kota</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->kota ?? '-' }}</div>
            </div>
            <div class="col-md-3">
              <div class="detail-label">Provinsi</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->provinsi ?? '-' }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="glass-card-premium px-4 py-4">
          <h5 class="detail-section-title">
            <i class="icon-base ti tabler-book"></i> Pendidikan & Pekerjaan
          </h5>
          <hr class="detail-divider">
          <div class="row g-3">
            <div class="col-md-3">
              <div class="detail-label">Pendidikan Terakhir</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->pendidikan_terakhir ?? '-' }}</div>
            </div>
            <div class="col-md-3">
              <div class="detail-label">Nama Institusi</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->nama_institusi ?? '-' }}</div>
            </div>
            <div class="col-md-3">
              <div class="detail-label">Jurusan</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->jurusan ?? '-' }}</div>
            </div>
            <div class="col-md-3">
              <div class="detail-label">Tahun Lulus</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->tahun_lulus ?? '-' }}</div>
            </div>
            <div class="col-md-4">
              <div class="detail-label">Status Pekerjaan</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->status_pekerjaan ?? '-' }}</div>
            </div>
            <div class="col-md-4">
              <div class="detail-label">Nama Perusahaan</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->nama_perusahaan ?? '-' }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="glass-card-premium px-4 py-4">
          <h5 class="detail-section-title">
            <i class="icon-base ti tabler-star"></i> Minat Pelatihan
          </h5>
          <hr class="detail-divider">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="detail-label">Bidang Minat</div>
              <div class="detail-value">
                @if($peserta->pesertaProfile && $peserta->pesertaProfile->bidang_minat)
                  @if(is_array($peserta->pesertaProfile->bidang_minat))
                    {{ implode(', ', $peserta->pesertaProfile->bidang_minat) }}
                  @else
                    {{ $peserta->pesertaProfile->bidang_minat }}
                  @endif
                @else
                  -
                @endif
              </div>
            </div>
            <div class="col-md-4">
              <div class="detail-label">Tujuan Pelatihan</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->tujuan_pelatihan ?? '-' }}</div>
            </div>
            <div class="col-md-2">
              <div class="detail-label">Preferensi Jadwal</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->preferensi_jadwal ?? '-' }}</div>
            </div>
            <div class="col-md-2">
              <div class="detail-label">Preferensi Mode</div>
              <div class="detail-value">{{ $peserta->pesertaProfile->preferensi_mode ?? '-' }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="glass-card-premium px-4 py-4">
          <h5 class="detail-section-title">
            <i class="icon-base ti tabler-info-circle"></i> Informasi Lainnya
          </h5>
          <hr class="detail-divider">
          <div class="row g-3">
            <div class="col-md-3">
              <div class="detail-label">Tanggal Daftar</div>
              <div class="detail-value">{{ $peserta->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="col-md-3">
              <div class="detail-label">Status</div>
              <div class="detail-value">
                @if($peserta->is_active)
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
@endsection
