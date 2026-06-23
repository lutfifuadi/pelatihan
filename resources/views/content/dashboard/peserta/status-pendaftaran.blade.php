@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Status Pendaftaran')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');

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

  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container {
    background-color: #0b0f19 !important;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }

  .layout-navbar-fixed .layout-page::before { display: none !important; }
  .content-wrapper > .container-xxl { max-width: 100% !important; padding: 0 !important; }

  .glow-orb {
    position: fixed; border-radius: 50%; filter: blur(120px); opacity: 0.4;
    mix-blend-mode: screen; pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out; z-index: 0;
  }
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #06b6d4 0%, transparent 70%); top: 35%; left: 25%; animation-duration: 24s; }
  @keyframes orbFloat {
    0% { transform: translate(0,0) scale(1) rotate(0deg); }
    50% { transform: translate(60px,40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px,-50px) scale(0.92) rotate(360deg); }
  }

  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border-radius: 5px !important;
    position: relative;
    z-index: 1;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .glass-card-premium:hover {
    transform: translateY(-4px) !important;
    border-color: rgba(99, 102, 241, 0.3) !important;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), 0 0 30px rgba(99, 102, 241, 0.15) !important;
  }

  .stat-icon-box {
    width: 52px; height: 52px; border-radius: 5px !important;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; flex-shrink: 0;
    transition: all 0.3s ease;
  }

  .info-label {
    font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em;
    color: rgba(255, 255, 255, 0.4); font-weight: 600; margin-bottom: 2px;
  }
  .info-value {
    font-size: 0.95rem; color: #f8fafc; font-weight: 500;
  }

  hr.dark-premium { border-color: rgba(255, 255, 255, 0.06); opacity: 1; }

  .btn-glow-premium {
    background: linear-gradient(135deg, #ffc107, #ff9800) !important;
    border: none; color: #0b0f19 !important;
    font-family: 'Sora', sans-serif; font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.4);
    background: linear-gradient(135deg, #ffca28, #ffa726) !important;
    color: #0b0f19 !important;
  }

  /* === BADGE STATUS === */
  .badge-status {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 16px; border-radius: 20px;
    font-weight: 600; font-size: 0.78rem;
    letter-spacing: 0.03em;
  }
  .badge-status.pending {
    background: rgba(99, 102, 241, 0.15);
    border: 1px solid rgba(99, 102, 241, 0.3);
    color: #818cf8;
  }
  .badge-status.approved {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #34d399;
  }
  .badge-status.rejected {
    background: rgba(248, 113, 113, 0.15);
    border: 1px solid rgba(248, 113, 113, 0.3);
    color: #f87171;
  }
  .badge-status.waitlist {
    background: rgba(245, 158, 11, 0.15);
    border: 1px solid rgba(245, 158, 11, 0.3);
    color: #fbbf24;
  }

  /* === VERTICAL TIMELINE === */
  .timeline-vert {
    position: relative;
    padding-left: 48px;
    list-style: none;
    margin-bottom: 0;
  }
  .timeline-vert::before {
    content: '';
    position: absolute;
    left: 19px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: linear-gradient(to bottom, #6366f1, rgba(99, 102, 241, 0.1));
  }
  .timeline-item {
    position: relative;
    margin-bottom: 32px;
  }
  .timeline-item:last-child {
    margin-bottom: 0;
  }
  .timeline-icon {
    position: absolute;
    left: -48px;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    z-index: 2;
    flex-shrink: 0;
    border: 2px solid transparent;
    transition: all 0.3s ease;
  }
  .timeline-icon.done {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.4);
    color: #34d399;
  }
  .timeline-icon.active {
    background: rgba(99, 102, 241, 0.15);
    border-color: rgba(99, 102, 241, 0.4);
    color: #818cf8;
    box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
    animation: timelinePulse 2s ease-in-out infinite;
  }
  .timeline-icon.waiting {
    background: rgba(255, 255, 255, 0.04);
    border-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.3);
  }
  .timeline-icon.rejected-status {
    background: rgba(248, 113, 113, 0.15);
    border-color: rgba(248, 113, 113, 0.4);
    color: #f87171;
  }
  @keyframes timelinePulse {
    0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
    70% { box-shadow: 0 0 0 12px rgba(99, 102, 241, 0); }
    100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
  }
  .timeline-content h6 {
    font-size: 0.9rem;
    margin-bottom: 2px;
  }
  .timeline-content p {
    font-size: 0.78rem;
    margin-bottom: 0;
  }

  .text-gradient {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  ::-webkit-scrollbar { width: 8px; }
  ::-webkit-scrollbar-track { background: #0b0f19; }
  ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 4px; }
  ::-webkit-scrollbar-thumb:hover { background: #d946ef; }

  .hover-text-primary:hover { color: #818cf8 !important; }

  body .content-wrapper > .container-p-y {
    padding-top: 1.5rem !important;
  }

  .btn-outline-glass {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    transition: all 0.3s ease;
  }
  .btn-outline-glass:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    transform: translateY(-2px);
  }
</style>
@endsection

@section('content')
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

  {{-- ================================================================
       A. HEADER — Status Pendaftaran
       ================================================================ --}}
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <div class="row align-items-center">
      <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center gap-3 mb-2">
          <div class="stat-icon-box" style="width: 56px; height: 56px; border-radius: 50% !important; background: rgba(99,102,241,0.12); color: #818cf8;">
            <i class="icon-base ti tabler-clipboard-check fs-2"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-1">Status Pendaftaran</h4>
            <p class="text-body-premium mb-0" style="font-size: 0.9rem;">
              Pantau perkembangan pendaftaran pelatihan Anda secara real-time
            </p>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-4 mt-3 mt-lg-0 text-lg-end">
        @if(!$enrollment)
          <span class="badge-status pending">
            <i class="icon-base ti tabler-file-search"></i>
            Belum Mendaftar
          </span>
        @elseif($enrollment->status === 'pending')
          <span class="badge-status pending">
            <span class="spinner-grow spinner-grow-sm me-1" style="width: 8px; height: 8px;"></span>
            Menunggu Verifikasi
          </span>
        @elseif($enrollment->status === 'approved')
          <span class="badge-status approved">
            <i class="icon-base ti tabler-circle-check"></i>
            Disetujui
          </span>
        @elseif($enrollment->status === 'rejected')
          <span class="badge-status rejected">
            <i class="icon-base ti tabler-circle-x"></i>
            Ditolak
          </span>
        @elseif($enrollment->status === 'waitlist')
          <span class="badge-status waitlist">
            <i class="icon-base ti tabler-clock"></i>
            Cadangan (Waitlist)
          </span>
        @endif
      </div>
    </div>
  </div>

  {{-- ================================================================
       B. TIMELINE VERTIKAL ALUR SELEKSI + CARD PELATIHAN
       ================================================================ --}}
  <div class="row g-4 mb-4">
    {{-- Kiri: Timeline --}}
    <div class="col-12 col-lg-7">
      <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
        <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-timeline text-primary"></i>
          Alur Seleksi
        </h5>

        @php
          $status = $enrollment ? $enrollment->status : null;
          $createdAt = $enrollment ? $enrollment->created_at : null;
          $approvedAt = $enrollment ? $enrollment->approved_at : null;
          $rejectedAt = $enrollment ? $enrollment->rejected_at : null;
          $rejectedNotes = $enrollment ? $enrollment->notes : null;
          $pelatihanMulai = $profile->pelatihan ? $profile->pelatihan->tanggal_mulai : null;
        @endphp

        <ul class="timeline-vert">
          {{-- Langkah 1: Data Dikirim --}}
          <li class="timeline-item">
            <div class="timeline-icon done">
              <i class="icon-base ti tabler-check"></i>
            </div>
            <div class="timeline-content">
              <h6 class="fw-bold text-white">Data Dikirim</h6>
              <p class="text-body-premium">
                Pendaftaran Anda telah berhasil dikirim ke sistem.
                @if($createdAt)
                  <br><span class="text-white-50" style="font-size: 0.75rem;">
                    <i class="icon-base ti tabler-clock me-1"></i>{{ $createdAt->format('d M Y H:i') }} WIB
                  </span>
                @endif
              </p>
            </div>
          </li>

          {{-- Langkah 2: Verifikasi Admin --}}
          <li class="timeline-item">
            @if($status === 'pending')
              <div class="timeline-icon active">
                <span class="spinner-border spinner-border-sm" style="width: 14px; height: 14px;"></span>
              </div>
              <div class="timeline-content">
                <h6 class="fw-bold text-white">Verifikasi Admin</h6>
                <p class="text-body-premium">
                  Tim Admin/Dinas sedang memverifikasi data dan kelengkapan berkas Anda.
                  <br><span class="text-white-50" style="font-size: 0.75rem;">Proses verifikasi biasanya memakan waktu 1×24 jam.</span>
                </p>
              </div>
            @elseif(in_array($status, ['approved', 'rejected', 'waitlist']))
              <div class="timeline-icon done">
                <i class="icon-base ti tabler-check"></i>
              </div>
              <div class="timeline-content">
                <h6 class="fw-bold text-white">Verifikasi Admin</h6>
                <p class="text-body-premium">
                  Data Anda telah diverifikasi oleh Admin.
                  @if($approvedAt)
                    <br><span class="text-white-50" style="font-size: 0.75rem;">
                      <i class="icon-base ti tabler-clock me-1"></i>{{ $approvedAt->format('d M Y H:i') }} WIB
                    </span>
                  @endif
                </p>
              </div>
            @else
              <div class="timeline-icon waiting">
                <i class="icon-base ti tabler-minus"></i>
              </div>
              <div class="timeline-content">
                <h6 class="text-white-50 fw-bold">Verifikasi Admin</h6>
                <p class="text-body-premium">Belum ada data pendaftaran.</p>
              </div>
            @endif
          </li>

          {{-- Langkah 3: Hasil Seleksi --}}
          <li class="timeline-item">
            @if($status === 'approved')
              <div class="timeline-icon done">
                <i class="icon-base ti tabler-check"></i>
              </div>
              <div class="timeline-content">
                <h6 class="fw-bold text-white">
                  <span class="text-success"><i class="icon-base ti tabler-circle-check me-1"></i></span>
                  Hasil Seleksi: Diterima
                </h6>
                <p class="text-body-premium">
                  Selamat! Pendaftaran Anda telah disetujui. Silakan siapkan diri untuk mengikuti pelatihan.
                  @if($approvedAt)
                    <br><span class="text-white-50" style="font-size: 0.75rem;">
                      <i class="icon-base ti tabler-clock me-1"></i>Disetujui {{ $approvedAt->format('d M Y H:i') }} WIB
                    </span>
                  @endif
                </p>
              </div>
            @elseif($status === 'rejected')
              <div class="timeline-icon rejected-status">
                <i class="icon-base ti tabler-x"></i>
              </div>
              <div class="timeline-content">
                <h6 class="fw-bold text-white">
                  <span class="text-danger"><i class="icon-base ti tabler-circle-x me-1"></i></span>
                  Hasil Seleksi: Ditolak
                </h6>
                <p class="text-body-premium">
                  Mohon maaf, pendaftaran Anda belum dapat disetujui.
                  @if($rejectedNotes)
                    <br><span class="text-danger" style="font-size: 0.78rem;">Alasan: {{ $rejectedNotes }}</span>
                  @endif
                  @if($rejectedAt)
                    <br><span class="text-white-50" style="font-size: 0.75rem;">
                      <i class="icon-base ti tabler-clock me-1"></i>{{ $rejectedAt->format('d M Y H:i') }} WIB
                    </span>
                  @endif
                </p>
              </div>
            @elseif($status === 'waitlist')
              <div class="timeline-icon" style="background: rgba(245, 158, 11, 0.15); border-color: rgba(245, 158, 11, 0.4); color: #fbbf24;">
                <i class="icon-base ti tabler-clock"></i>
              </div>
              <div class="timeline-content">
                <h6 class="fw-bold text-white">
                  <span class="text-warning"><i class="icon-base ti tabler-clock me-1"></i></span>
                  Hasil Seleksi: Cadangan (Waitlist)
                </h6>
                <p class="text-body-premium">
                  Kuota utama saat ini sudah terpenuhi. Anda masuk ke daftar cadangan dan akan dipromosikan jika ada peserta yang mengundurkan diri.
                </p>
              </div>
            @elseif($status === 'pending')
              <div class="timeline-icon active">
                <span class="spinner-border spinner-border-sm" style="width: 14px; height: 14px;"></span>
              </div>
              <div class="timeline-content">
                <h6 class="fw-bold text-white">Hasil Seleksi</h6>
                <p class="text-body-premium">Menunggu hasil verifikasi dari Admin/Dinas penyelenggara.</p>
              </div>
            @else
              <div class="timeline-icon waiting">
                <i class="icon-base ti tabler-minus"></i>
              </div>
              <div class="timeline-content">
                <h6 class="text-white-50 fw-bold">Hasil Seleksi</h6>
                <p class="text-body-premium">Belum ada data pendaftaran.</p>
              </div>
            @endif
          </li>

          {{-- Langkah 4: Pelatihan Dimulai --}}
          <li class="timeline-item">
            @if($status === 'approved' && $pelatihanMulai)
              <div class="timeline-icon" style="background: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.4); color: #22d3ee;">
                <i class="icon-base ti tabler-calendar-event"></i>
              </div>
              <div class="timeline-content">
                <h6 class="fw-bold text-white">
                  <i class="icon-base ti tabler-calendar-check text-info me-1"></i>
                  Pelatihan Dimulai
                </h6>
                <p class="text-body-premium">
                  Pelatihan akan dimulai pada:
                  <br><span class="fw-bold text-white" style="font-size: 0.9rem;">
                    <i class="icon-base ti tabler-calendar me-1"></i>{{ $pelatihanMulai->format('d M Y') }}
                  </span>
                  @if($profile->pelatihan && $profile->pelatihan->tanggal_selesai)
                    <span class="text-white-50"> — {{ $profile->pelatihan->tanggal_selesai->format('d M Y') }}</span>
                  @endif
                </p>
              </div>
            @elseif($status === 'approved')
              <div class="timeline-icon waiting">
                <i class="icon-base ti tabler-calendar-question"></i>
              </div>
              <div class="timeline-content">
                <h6 class="text-white-50 fw-bold">Pelatihan Dimulai</h6>
                <p class="text-body-premium">Tanggal mulai pelatihan akan diumumkan kemudian.</p>
              </div>
            @else
              <div class="timeline-icon waiting">
                <i class="icon-base ti tabler-calendar-off"></i>
              </div>
              <div class="timeline-content">
                <h6 class="text-white-50 fw-bold">Pelatihan Dimulai</h6>
                <p class="text-body-premium">Menunggu status pendaftaran Anda untuk melanjutkan ke tahap ini.</p>
              </div>
            @endif
          </li>
        </ul>
      </div>
    </div>

    {{-- Kanan: Card Detail Pelatihan + Profil Peserta --}}
    <div class="col-12 col-lg-5 d-flex flex-column gap-4">

      {{-- C. Card Detail Pelatihan --}}
      <div class="glass-card-premium px-4 px-xl-5 py-4">
        <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-book text-success"></i>
          Detail Pelatihan
        </h5>

        @if($profile->pelatihan)
          <div class="p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
            <div class="row g-3">
              <div class="col-12">
                <span class="info-label d-block">Nama Pelatihan</span>
                <span class="info-value fw-bold text-white">{{ $profile->pelatihan->nama }}</span>
              </div>
              <div class="col-6">
                <span class="info-label d-block">Batch</span>
                <span class="info-value text-white">{{ $profile->pelatihan->batch }}</span>
              </div>
              <div class="col-6">
                <span class="info-label d-block">Dinas Penyelenggara</span>
                <span class="info-value text-white">{{ $profile->pelatihan->dinas->nama_dinas ?? '-' }}</span>
              </div>
              <div class="col-12">
                <span class="info-label d-block">Jadwal Pelaksanaan</span>
                <span class="info-value text-white">
                  @if($profile->pelatihan->tanggal_mulai)
                    {{ $profile->pelatihan->tanggal_mulai->format('d M Y') }}
                    @if($profile->pelatihan->tanggal_selesai)
                      — {{ $profile->pelatihan->tanggal_selesai->format('d M Y') }}
                    @endif
                  @else
                    Akan segera diumumkan
                  @endif
                </span>
              </div>
              @if($profile->pelatihan->kuota)
              <div class="col-6">
                <span class="info-label d-block">Kuota</span>
                <span class="info-value text-white">{{ $profile->pelatihan->kuota }} peserta</span>
              </div>
              @endif
              @if($profile->pelatihan->deskripsi)
              <div class="col-12">
                <span class="info-label d-block">Deskripsi</span>
                <p class="text-white-50 small mb-0" style="font-size: 0.82rem; line-height: 1.5;">
                  {{ Illuminate\Support\Str::limit(strip_tags($profile->pelatihan->deskripsi), 120) }}
                </p>
              </div>
              @endif
            </div>
          </div>
        @else
          <div class="text-center py-4 rounded border border-white border-opacity-5" style="background: rgba(255, 255, 255, 0.05);">
            <i class="icon-base ti tabler-book-off fs-2 text-muted mb-2 d-block"></i>
            <span class="text-body-premium small">Belum ada pelatihan yang dipilih.</span>
          </div>
        @endif
      </div>

      {{-- D. Card Profil Peserta --}}
      <div class="glass-card-premium px-4 px-xl-5 py-4">
        <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-user-circle text-info"></i>
          Profil Peserta
        </h5>

        <div class="row g-3">
          <div class="col-12">
            <span class="info-label d-block">Nama Lengkap</span>
            <span class="info-value text-white">{{ $profile->nama_lengkap ?? $user->name ?? '-' }}</span>
          </div>
          <div class="col-6">
            <span class="info-label d-block">NIK</span>
            <span class="info-value text-white" style="font-family: monospace;">{{ $profile->nik ?? '-' }}</span>
          </div>
          <div class="col-6">
            <span class="info-label d-block">Jenis Kelamin</span>
            <span class="info-value text-white">{{ $profile->jenis_kelamin ?? '-' }}</span>
          </div>
          <div class="col-6">
            <span class="info-label d-block">WhatsApp</span>
            <span class="info-value text-white">{{ $profile->whatsapp ?? '-' }}</span>
          </div>
          <div class="col-6">
            <span class="info-label d-block">Email</span>
            <span class="info-value text-white" style="font-size: 0.85rem;">{{ $profile->email ?? $user->email ?? '-' }}</span>
          </div>
          @if($profile->alamat_ktp)
          <div class="col-12">
            <span class="info-label d-block">Alamat</span>
            <span class="info-value text-white" style="font-size: 0.85rem;">
              {{ $profile->alamat_ktp }}, {{ $profile->kelurahan ?? '' }} {{ $profile->kecamatan ?? '' }}
            </span>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ================================================================
       E. TOMBOL AKSI
       ================================================================ --}}
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <div class="row align-items-center g-3">
      <div class="col-12 col-md-6">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box" style="width: 42px; height: 42px; background: rgba(16,185,129,0.12); color: #10b981; border-radius: 50% !important;">
            <i class="icon-base ti tabler-brand-whatsapp fs-5"></i>
          </div>
          <div>
            <span class="info-label d-block">Butuh Bantuan?</span>
            <a href="https://wa.me/{{ \App\Models\Setting::where('key', 'whatsapp_sender')->value('value') ?? '62888888888' }}" 
               target="_blank" 
               class="text-white fw-semibold text-decoration-none hover-text-primary">
              Hubungi Admin via WhatsApp
            </a>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 text-md-end">
        <div class="d-flex gap-2 flex-wrap justify-content-md-end">
          {{-- WA Auto-fill Konfirmasi Pendaftaran --}}
          @php
            $whatsappSender = \App\Models\Setting::where('key', 'whatsapp_sender')->value('value') ?? '62888888888';
            $waNama = $profile->nama_lengkap ?? auth()->user()->name ?? '-';
            $waPelatihan = $profile->pelatihan->nama ?? '-';
            $waKelurahan = $profile->kelurahan ?? '-';
            $waKecamatan = $profile->kecamatan ?? '-';
            $waNoHp = $profile->whatsapp ?? auth()->user()->whatsapp ?? '-';
            $waAutoFillMessage = "Halo Admin, saya telah melakukan pendaftaran pelatihan.\n\nNama Lengkap Sesuai KTP : {$waNama}\nJenis Pelatihan : {$waPelatihan}\nKelurahan : {$waKelurahan}\nKecamatan : {$waKecamatan}\nNo. HP Peserta Terdaftar : {$waNoHp}\n\n#pelatihanku2026";
          @endphp
          <a href="https://wa.me/{{ $whatsappSender }}?text={{ urlencode($waAutoFillMessage) }}" 
             target="_blank" class="btn btn-glow-premium py-2 px-4">
            <i class="icon-base ti tabler-clipboard-check me-1"></i> Konfirmasi Pendaftaran
          </a>
          <a href="{{ route('dashboard.peserta') }}" class="btn btn-outline-glass py-2 px-4">
            <i class="icon-base ti tabler-layout-dashboard me-1"></i> Kembali ke Dashboard
          </a>
        </div>
      </div>
    </div>

    {{-- Info tambahan untuk rejected --}}
    @if($enrollment && $enrollment->status === 'rejected')
    <hr class="dark-premium my-3">
    <div class="d-flex align-items-center gap-3 justify-content-between flex-wrap">
      <div class="d-flex align-items-center gap-2">
        <i class="icon-base ti tabler-refresh text-danger"></i>
        <span class="text-body-premium small">Ingin mendaftar pelatihan lain?</span>
      </div>
      <a href="{{ route('dashboard.peserta.form-minat') }}" class="btn btn-sm fw-semibold px-4" 
         style="border-radius: 5px; background: rgba(248,113,113,0.15); border: 1px solid rgba(248,113,113,0.3); color: #f87171;">
        <i class="icon-base ti tabler-plus me-1"></i> Pilih Pelatihan Lain
      </a>
    </div>
    @endif

    {{-- Info tambahan untuk waitlist --}}
    @if($enrollment && $enrollment->status === 'waitlist')
    <hr class="dark-premium my-3">
    <div class="d-flex align-items-start gap-2">
      <i class="icon-base ti tabler-info-circle text-warning mt-1 flex-shrink-0"></i>
      <div>
        <span class="text-warning fw-semibold small d-block">Informasi Waitlist</span>
        <p class="text-body-premium small mb-0" style="font-size: 0.78rem;">
          Anda akan otomatis dipromosikan menjadi peserta utama jika ada peserta lain yang mengundurkan diri atau dibatalkan pendaftarannya. Status Anda akan diperbarui secara real-time di halaman ini.
        </p>
      </div>
    </div>
    @endif
  </div>

</div>
@endsection
