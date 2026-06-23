@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Pendaftaran Berhasil')

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
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #10b981 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
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
  }

  .success-icon-box {
    width: 80px; height: 80px; border-radius: 50%;
    background: rgba(16, 185, 129, 0.15);
    border: 3px solid rgba(16, 185, 129, 0.4);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto;
    animation: successPulse 2s ease-in-out infinite;
  }
  @keyframes successPulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    70% { box-shadow: 0 0 0 20px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
  }

  .info-label {
    font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em;
    color: rgba(255, 255, 255, 0.4); font-weight: 600; margin-bottom: 2px;
  }
  .info-value {
    font-size: 0.95rem; color: #f8fafc; font-weight: 500;
  }

  .badge-status {
    background: rgba(99, 102, 241, 0.15);
    border: 1px solid rgba(99, 102, 241, 0.3);
    color: #818cf8; border-radius: 20px;
    padding: 6px 16px; font-weight: 600; font-size: 0.75rem;
  }
  .badge-status.approved {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.3);
    color: #34d399;
  }
  .badge-status.waitlist {
    background: rgba(245, 158, 11, 0.15);
    border-color: rgba(245, 158, 11, 0.3);
    color: #fbbf24;
  }
</style>
@endsection

@section('content')
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

  {{-- Success Hero --}}
  <div class="glass-card-premium px-4 px-xl-5 py-5 mb-4 text-center">
    <div class="success-icon-box mb-4">
      <i class="icon-base ti tabler-check text-success" style="font-size: 2.4rem;"></i>
    </div>
    <h3 class="fw-bold text-white mb-2" style="font-family: 'Sora', sans-serif;">Pendaftaran Berhasil! 🎉</h3>
    <p class="text-white-50 mb-1" style="font-size: 1rem; max-width: 500px; margin: 0 auto;">
      Terima kasih, <strong class="text-white">{{ $profile->nama_lengkap ?? $user->name }}</strong>!
    </p>
    <p class="text-white-50 mb-0" style="font-size: 0.9rem; max-width: 500px; margin: 0 auto;">
      Data pendaftaran Anda untuk <strong class="text-white">{{ $profile->pelatihan->nama ?? 'Pelatihan' }}</strong> telah berhasil dikirim dan akan segera diproses.
    </p>
  </div>

  <div class="row g-4">
    {{-- Status Enrollment --}}
    <div class="col-12 col-lg-6">
      <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
        <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-info-circle text-primary"></i>
          Status Pendaftaran
        </h5>

        @if($enrollment && $enrollment->status === 'approved')
          <div class="d-inline-flex align-items-center gap-2 badge-status approved mb-3">
            <i class="icon-base ti tabler-check-circle"></i>
            Disetujui Otomatis
          </div>
          <p class="text-white-50 small">Selamat! Pendaftaran Anda langsung disetujui. Anda dapat melihat detail pelatihan di dashboard.</p>
        @elseif($enrollment && $enrollment->status === 'waitlist')
          <div class="d-inline-flex align-items-center gap-2 badge-status waitlist mb-3">
            <i class="icon-base ti tabler-clock"></i>
            Cadangan (Waitlist)
          </div>
          <p class="text-white-50 small">Kuota utama saat ini sudah penuh. Anda masuk daftar cadangan dan akan dipromosikan jika ada peserta yang mengundurkan diri.</p>
        @else
          <div class="d-inline-flex align-items-center gap-2 badge-status mb-3">
            <span class="spinner-grow spinner-grow-sm me-1" style="width: 10px; height: 10px;"></span>
            Menunggu Verifikasi
          </div>
          <p class="text-white-50 small">Data Anda sedang dalam proses verifikasi oleh tim Admin/Dinas. Proses verifikasi biasanya memakan waktu <strong>1×24 jam</strong>.</p>
        @endif

        <hr class="my-4" style="border-color: rgba(255,255,255,0.06);">

        {{-- Timeline Alur --}}
        <h6 class="text-white fw-semibold mb-3">Alur Seleksi:</h6>
        <ul class="list-unstyled mb-0">
          <li class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 50%; background: rgba(16,185,129,0.15); color: #10b981; flex-shrink: 0;">
              <i class="icon-base ti tabler-check" style="font-size: 14px;"></i>
            </div>
            <span class="text-white small">Data dikirim & terverifikasi sistem</span>
          </li>
          <li class="d-flex align-items-center gap-3 mb-3">
            @if($enrollment && $enrollment->status === 'approved')
              <div class="d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 50%; background: rgba(16,185,129,0.15); color: #10b981; flex-shrink: 0;">
                <i class="icon-base ti tabler-check" style="font-size: 14px;"></i>
              </div>
              <span class="text-white small">Disetujui</span>
            @elseif($enrollment && $enrollment->status === 'rejected')
              <div class="d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 50%; background: rgba(248,113,113,0.15); color: #f87171; flex-shrink: 0;">
                <i class="icon-base ti tabler-x" style="font-size: 14px;"></i>
              </div>
              <span class="text-white small">Ditolak</span>
            @else
              <div class="d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 50%; background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.4); flex-shrink: 0;">
                <span class="spinner-grow spinner-grow-sm" style="width: 10px; height: 10px;"></span>
              </div>
              <span class="text-white-50 small">Verifikasi Admin</span>
            @endif
          </li>
          <li class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 50%; background: rgba(255,255,255,0.03); color: rgba(255,255,255,0.25); flex-shrink: 0; border: 1px dashed rgba(255,255,255,0.1);">
              <i class="icon-base ti tabler-bell" style="font-size: 14px;"></i>
            </div>
            <span class="text-white-50 small">Pengumuman hasil seleksi via WhatsApp & Dashboard</span>
          </li>
        </ul>
      </div>
    </div>

    {{-- Detail Pelatihan --}}
    <div class="col-12 col-lg-6">
      <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
        <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-file-text text-success"></i>
          Ringkasan Pendaftaran
        </h5>

        @if($profile->pelatihan)
        <div class="p-3 rounded mb-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
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
              <span class="info-label d-block">Penyelenggara</span>
              <span class="info-value text-white">{{ $profile->pelatihan->dinas->nama_dinas ?? '-' }}</span>
            </div>
            <div class="col-12">
              <span class="info-label d-block">Tanggal Pelaksanaan</span>
              <span class="info-value text-white">
                @if($profile->pelatihan->tanggal_mulai)
                  {{ $profile->pelatihan->tanggal_mulai->format('d M Y') }} s/d {{ $profile->pelatihan->tanggal_selesai ? $profile->pelatihan->tanggal_selesai->format('d M Y') : '-' }}
                @else
                  Akan segera diumumkan
                @endif
              </span>
            </div>
          </div>
        </div>
        @endif

        {{-- Informasi Kontak --}}
        <hr class="my-4" style="border-color: rgba(255,255,255,0.06);">
        <h6 class="text-white fw-semibold mb-3">Butuh Bantuan?</h6>
        <div class="d-flex align-items-center gap-3 mb-3">
          <div style="width: 36px; height: 36px; border-radius: 5px; background: rgba(16,185,129,0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="icon-base ti tabler-brand-whatsapp text-success" style="font-size: 1.2rem;"></i>
          </div>
          <div>
            <span class="info-label d-block">WhatsApp</span>
            <a href="https://wa.me/{{ \App\Models\Setting::where('key', 'whatsapp_sender')->value('value') ?? '62888888888' }}" target="_blank" class="text-white fw-semibold text-decoration-none hover-text-primary small">Hubungi Admin</a>
          </div>
        </div>
        <div class="d-flex align-items-center gap-3">
          <div style="width: 36px; height: 36px; border-radius: 5px; background: rgba(99,102,241,0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="icon-base ti tabler-layout-dashboard text-primary" style="font-size: 1.2rem;"></i>
          </div>
          <div>
            <span class="info-label d-block">Pantau Status</span>
            <a href="{{ route('dashboard.peserta') }}" class="text-white fw-semibold text-decoration-none hover-text-primary small">Lihat Dashboard</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Tombol Aksi --}}
  <div class="glass-card-premium px-4 px-xl-5 py-4 mt-4 mb-4">
    <div class="row align-items-center g-3">
      <div class="col-12 col-md-6">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-success" style="width: 42px; height: 42px; border-radius: 50% !important;">
            <i class="icon-base ti tabler-brand-whatsapp fs-5"></i>
          </div>
          <div>
            <span class="info-label d-block">Konfirmasi ke Admin</span>
            <span class="text-white-50 small">Kirim data pendaftaran Anda via WhatsApp</span>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 text-md-end">
        <div class="d-flex gap-2 flex-wrap justify-content-md-end">
          @php
            $whatsappSender = \App\Models\Setting::where('key', 'whatsapp_sender')->value('value') ?? '62888888888';
            $waNama = $profile->nama_lengkap ?? $user->name ?? '-';
            $waPelatihan = $profile->pelatihan->nama ?? '-';
            $waKelurahan = $profile->kelurahan ?? '-';
            $waKecamatan = $profile->kecamatan ?? '-';
            $waNoHp = $profile->whatsapp ?? $user->whatsapp ?? '-';
            $waAutoFillMessage = "Halo Admin, saya telah melakukan pendaftaran pelatihan.\n\nNama Lengkap Sesuai KTP : {$waNama}\nJenis Pelatihan : {$waPelatihan}\nKelurahan : {$waKelurahan}\nKecamatan : {$waKecamatan}\nNo. HP Peserta Terdaftar : {$waNoHp}\n\n#pelatihanku2026";
          @endphp
          <a href="https://wa.me/{{ $whatsappSender }}?text={{ urlencode($waAutoFillMessage) }}" 
             target="_blank" class="btn btn-glow-premium py-2 px-4">
            <i class="icon-base ti tabler-clipboard-check me-1"></i> Konfirmasi Pendaftaran
          </a>
          <a href="{{ route('dashboard.peserta') }}" class="btn btn-outline-glass py-2 px-4">
            <i class="icon-base ti tabler-layout-dashboard me-1"></i> Ke Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
