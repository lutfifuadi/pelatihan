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

  .stat-icon-success {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
  }

  .stat-icon-secondary {
    background: rgba(148, 163, 184, 0.12);
    color: #94a3b8;
  }

  .stat-icon-warning {
    background: rgba(245, 158, 11, 0.12);
    color: #f59e0b;
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

  /* Timeline Styles */
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
        <div class="d-flex gap-2">
          <a href="{{ route('admin.peserta.edit-biodata', $peserta) }}" class="btn btn-warning px-4 py-2 d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-edit"></i> Edit Biodata
          </a>
          <a href="{{ route('admin.peserta.index') }}" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-arrow-left"></i> Kembali
          </a>
        </div>
      </div>
    </div>

    <div class="row g-4">

      <div class="col-lg-8 col-md-7">
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
                  <div class="detail-value">
                    @php
                      $medsos = $peserta->pesertaProfile->link_medsos ?? null;
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
                <i class="icon-base ti tabler-school"></i> Pilihan Pelatihan
              </h5>
              <hr class="detail-divider">
              <div class="row g-3">
                <div class="col-md-4">
                  <div class="detail-label">Nama Pelatihan</div>
                  <div class="detail-value">{{ $peserta->pesertaProfile->pelatihan->nama ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">Batch Pelatihan</div>
                  <div class="detail-value">{{ $peserta->pesertaProfile->batch_pelatihan ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                  <div class="detail-label">Dinas Penyelenggara</div>
                  <div class="detail-value">{{ $peserta->pesertaProfile->pelatihan->dinas->nama_dinas ?? '-' }}</div>
                </div>
                <div class="col-12">
                  <div class="detail-label">Deskripsi Pelatihan</div>
                  <div class="detail-value">
                    {{ strip_tags($peserta->pesertaProfile->pelatihan->deskripsi ?? '-') }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="glass-card-premium px-4 py-4">
              <h5 class="detail-section-title">
                <i class="icon-base ti tabler-help-circle"></i> Jawaban Pertanyaan (Tahap 5)
              </h5>
              <hr class="detail-divider">
              @php
                $jawaban = $peserta->pesertaProfile->jawaban_pertanyaan ?? [];
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

      <div class="col-lg-4 col-md-5">
        <div class="glass-card-premium px-4 py-4">
          <h5 class="detail-section-title">
            <i class="icon-base ti tabler-chart-donut"></i> Status &amp; Progress Pendaftaran
          </h5>
          <hr class="detail-divider">
          
          @php
            $profile = $peserta->pesertaProfile;
            $step1Done = $profile && !empty($profile->nama_lengkap) && !empty($profile->nik);
            $step2Done = $profile && !empty($profile->alamat_ktp) && !empty($profile->whatsapp);
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
            {{-- Tahap 1 --}}
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

            {{-- Tahap 2 --}}
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

            {{-- Tahap 3 --}}
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

            {{-- Tahap 4 --}}
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

            {{-- Tahap 5 --}}
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

            {{-- Tahap 6 --}}
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

        {{-- Riwayat Pendaftaran Pelatihan --}}
        <div class="glass-card-premium px-4 py-4 mt-4">
          <h5 class="detail-section-title">
            <i class="icon-base ti tabler-file-check"></i> Riwayat Pendaftaran Pelatihan
          </h5>
          <hr class="detail-divider">

          @php
            $enrollments = $peserta->enrollments;
          @endphp

          @if($enrollments->isNotEmpty())
            @foreach($enrollments as $enrollment)
              <div class="mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <div class="fw-semibold text-white" style="font-size: 0.9rem;">{{ $enrollment->pelatihan->nama }}</div>
                    <div class="text-body-premium" style="font-size: 0.7rem;">Batch: {{ $enrollment->pelatihan->batch }}</div>
                  </div>
                  <div>
                    @switch($enrollment->status)
                      @case('pending')
                        <span class="badge-premium badge-premium-warning">Pending</span>
                        @break
                      @case('approved')
                        <span class="badge-premium badge-premium-success">Approved</span>
                        @break
                      @case('rejected')
                        <span class="badge-premium badge-premium-danger">Ditolak</span>
                        @break
                      @case('waitlist')
                        <span class="badge-premium badge-premium-info">Cadangan</span>
                        @break
                    @endswitch
                  </div>
                </div>

                @if($enrollment->notes)
                  <div class="text-body-premium mb-2" style="font-size: 0.75rem; font-style: italic;">
                    <i class="icon-base ti tabler-message"></i> {{ $enrollment->notes }}
                  </div>
                @endif

                {{-- Actions --}}
                <div class="d-flex gap-2 flex-wrap mt-2">
                  @if($enrollment->status?->value === 'pending')
                    <form action="{{ route('admin.enrollments.approve', $enrollment) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-success btn-action d-inline-flex align-items-center gap-1" style="border-radius: 5px;">
                        <i class="icon-base ti tabler-check fs-6"></i> Approve
                      </button>
                    </form>
                    <form action="{{ route('admin.enrollments.waitlist', $enrollment) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-info btn-action d-inline-flex align-items-center gap-1" style="border-radius: 5px;">
                        <i class="icon-base ti tabler-clock fs-6"></i> Cadangan
                      </button>
                    </form>
                    <button type="button" class="btn btn-danger btn-action d-inline-flex align-items-center gap-1" style="border-radius: 5px;" data-bs-toggle="modal" data-bs-target="#rejectEnrollmentModal" data-enrollment-id="{{ $enrollment->id }}" data-enrollment-route="{{ route('admin.enrollments.reject', $enrollment) }}">
                      <i class="icon-base ti tabler-x fs-6"></i> Tolak
                    </button>

                  @elseif($enrollment->status?->value === 'waitlist')
                    <form action="{{ route('admin.enrollments.promote', $enrollment) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-success btn-action d-inline-flex align-items-center gap-1" style="border-radius: 5px;">
                        <i class="icon-base ti tabler-arrow-up fs-6"></i> Promosikan
                      </button>
                    </form>
                    <button type="button" class="btn btn-danger btn-action d-inline-flex align-items-center gap-1" style="border-radius: 5px;" data-bs-toggle="modal" data-bs-target="#rejectEnrollmentModal" data-enrollment-id="{{ $enrollment->id }}" data-enrollment-route="{{ route('admin.enrollments.reject', $enrollment) }}">
                      <i class="icon-base ti tabler-x fs-6"></i> Tolak
                    </button>

                  @elseif($enrollment->status?->value === 'approved')
                    <form action="{{ route('admin.enrollments.reset', $enrollment) }}" method="POST" class="d-inline reset-enrollment-form" data-name="{{ $peserta->name }}" data-pelatihan="{{ $enrollment->pelatihan->nama }}">
                      @csrf
                      <button type="submit" class="btn btn-warning btn-action d-inline-flex align-items-center gap-1" style="border-radius: 5px; background: linear-gradient(135deg, #f59e0b, #d97706); border: none;">
                        <i class="icon-base ti tabler-refresh fs-6"></i> Reset
                      </button>
                    </form>

                  @elseif($enrollment->status?->value === 'rejected')
                    <span class="text-body-premium" style="font-size: 0.8rem;">Tidak ada aksi</span>
                  @endif
                </div>
              </div>
            @endforeach
          @else
            <div class="text-center py-3">
              <i class="icon-base ti tabler-inbox fs-1 d-block mb-2 text-body-premium"></i>
              <span class="text-body-premium" style="font-size: 0.9rem;">Peserta belum melakukan submit pendaftaran final.</span>
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>

  {{-- Modal Reject Enrollment --}}
  <div class="modal fade" id="rejectEnrollmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="background: #0b0f19; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px;">
        <div class="modal-header border-0">
          <h6 class="text-white fw-bold mb-0">Tolak Pendaftaran</h6>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form id="rejectEnrollmentForm" method="POST">
          @csrf
          <div class="modal-body">
            <p class="text-body-premium small mb-2">Alasan penolakan (opsional):</p>
            <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Kuota penuh, tidak memenuhi syarat..." style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #f8fafc; border-radius: 5px;"></textarea>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary btn-action" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.7);">Batal</button>
            <button type="submit" class="btn btn-danger btn-action">Ya, Tolak</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
<script>
  // Dynamic reject modal: set form action based on clicked button
  document.addEventListener('DOMContentLoaded', function() {
    const rejectModal = document.getElementById('rejectEnrollmentModal');
    if (rejectModal) {
      rejectModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const route = button.getAttribute('data-enrollment-route');
        const form = document.getElementById('rejectEnrollmentForm');
        if (route && form) {
          form.action = route;
        }
      });
    }
  });

  // Reset Enrollment confirmation with SweetAlert2
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
