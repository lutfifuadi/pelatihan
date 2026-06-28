@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Review Data Pendaftaran')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('page-style')
<style>


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

  .glass-card-dashboard {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
    border-radius: 5px;
    position: relative;
    z-index: 1;
    padding: 28px 24px;
  }
  @media (max-width: 660px) {
    .glass-card-dashboard { padding: 20px 16px; }
  }

  .review-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    padding: 20px 18px;
    margin-bottom: 14px;
    transition: all 0.3s ease;
    position: relative;
  }
  .review-card:hover {
    border-color: rgba(99, 102, 241, 0.2);
    background: rgba(255, 255, 255, 0.05);
  }

  .review-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
  }

  .review-card-title {
    font-family: 'Sora', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .review-card-icon {
    width: 32px;
    height: 32px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
  }

  .edit-btn {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.5);
    font-size: 11px;
    padding: 4px 12px;
    border-radius: 4px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .edit-btn:hover {
    background: rgba(99, 102, 241, 0.15);
    border-color: rgba(99, 102, 241, 0.3);
    color: #a5b4fc;
  }

  .badge-lengkap {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.05em;
    padding: 3px 10px;
    border-radius: 3px;
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.2);
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  .data-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px 24px;
  }
  @media (max-width: 660px) {
    .data-grid { grid-template-columns: 1fr; }
  }

  .data-item {
    display: flex;
    flex-direction: column;
    padding: 6px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.03);
  }
  .data-item.full-width { grid-column: 1 / -1; }

  .data-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.35);
    margin-bottom: 2px;
  }

  .data-value {
    font-size: 14px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
    word-break: break-word;
  }

  .qa-item {
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
  }
  .qa-item:last-child { border-bottom: none; }
  .qa-question {
    font-size: 12px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 4px;
  }
  .qa-answer {
    font-size: 14px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
  }
  .qa-answer.empty {
    color: rgba(255, 255, 255, 0.25);
    font-style: italic;
  }

  .btn-glow {
    position: relative; overflow: hidden; transition: all 0.3s ease; border: none;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    color: #0b0f19 !important;
  }
  .btn-glow:hover {
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 10px 30px rgba(255, 152, 0, 0.5);
    background: linear-gradient(135deg, #ffca28, #ffa726);
  }
  .btn-glow:disabled {
    opacity: 0.5; transform: none; cursor: not-allowed;
  }
  .btn-glow-outline {
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: rgba(255, 255, 255, 0.8) !important;
    transition: all 0.3s ease;
  }
  .btn-glow-outline:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.3);
    color: #ffffff !important;
  }

  .form-check-input-custom {
    background-color: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    cursor: pointer;
  }
  .form-check-input-custom:checked { background-color: #6366f1 !important; border-color: #6366f1 !important; }
  .text-white-50-custom { color: rgba(255, 255, 255, 0.5) !important; }
  .text-white-70-custom { color: rgba(255, 255, 255, 0.7) !important; }

  .invalid-feedback-custom { color: #f87171; font-size: 11px; margin-top: 3px; display: none; }
  .invalid-feedback-custom.d-block { display: block; }

  /* Entry animation */
  .review-card { animation: cardFadeIn 0.4s ease forwards; opacity: 0; }
  .review-card:nth-child(1) { animation-delay: 0.05s; }
  .review-card:nth-child(2) { animation-delay: 0.10s; }
  .review-card:nth-child(3) { animation-delay: 0.15s; }
  .review-card:nth-child(4) { animation-delay: 0.20s; }
  .review-card:nth-child(5) { animation-delay: 0.25s; }
  @keyframes cardFadeIn {
    0% { opacity: 0; transform: translateY(12px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
  {{-- Header --}}
  <div class="glass-card-dashboard mb-4">
    <div class="d-flex align-items-center gap-3">
      <div style="width: 48px; height: 48px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);">
        <i class="icon-base ti tabler-clipboard-check text-white fs-4"></i>
      </div>
      <div>
        <h4 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Review Data Pendaftaran</h4>
        <p class="text-white-50-custom mb-0 small">Pastikan seluruh data Anda sudah benar sebelum menyelesaikan pendaftaran</p>
      </div>
    </div>
  </div>

  @php
    $step1Done = $profile && !empty($profile->nama_lengkap) && !empty($profile->nik);
    $step2Done = $profile && !empty($profile->alamat_ktp) && !empty($profile->whatsapp);
    $step3Done = $profile && !empty($profile->pendidikan_terakhir) && !empty($profile->nama_institusi);
    $step4Done = $profile && !empty($profile->pelatihan_id);
    $step5Done = $profile && !empty($profile->jawaban_pertanyaan);
    $step6Done = $profile && $profile->is_completed;
  @endphp

  <!-- Step Indicator: 6 Steps -->
  <div class="step-indicator mb-4">
    <div class="step-progress-line" style="transform: scaleX(1); transform-origin: left;"></div>
    
    <!-- Step 1: Data Diri -->
    <div class="step-item {{ $step1Done ? 'completed' : '' }}" @if($step1Done) onclick="window.location.href='{{ route('dashboard.peserta.form-pendaftaran') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step1Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          1
        @endif
      </div>
      <div class="step-label">Data Diri</div>
    </div>
    
    <!-- Step 2: Alamat -->
    <div class="step-item {{ $step2Done ? 'completed' : '' }}" @if($step2Done) onclick="window.location.href='{{ route('dashboard.peserta.form-alamat') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step2Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          2
        @endif
      </div>
      <div class="step-label">Alamat</div>
    </div>
    
    <!-- Step 3: Pendidikan -->
    <div class="step-item {{ $step3Done ? 'completed' : '' }}" @if($step3Done) onclick="window.location.href='{{ route('dashboard.peserta.form-pendidikan') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step3Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          3
        @endif
      </div>
      <div class="step-label">Pendidikan</div>
    </div>
    
    <!-- Step 4: Pelatihan -->
    <div class="step-item {{ $step4Done ? 'completed' : '' }}" @if($step4Done) onclick="window.location.href='{{ route('dashboard.peserta.form-minat') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step4Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          4
        @endif
      </div>
      <div class="step-label">Pilihan Pelatihan</div>
    </div>
    
    <!-- Step 5: Dokumen -->
    <div class="step-item {{ $step5Done ? 'completed' : '' }}" @if($step5Done) onclick="window.location.href='{{ route('dashboard.peserta.form-dokumen') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step5Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          5
        @endif
      </div>
      <div class="step-label">Dokumen</div>
    </div>
    
    <!-- Step 6: Review -->
    <div class="step-item {{ $step6Done ? 'completed' : 'active' }}">
      <div class="step-circle">
        @if($step6Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          6
        @endif
      </div>
      <div class="step-label">Review</div>
    </div>
  </div>

  @if(!$profile)
  <div class="glass-card-dashboard text-center py-5">
    <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(248, 113, 113, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; border: 1px solid rgba(248, 113, 113, 0.2);">
      <i class="icon-base ti tabler-alert-triangle text-danger fs-3"></i>
    </div>
    <h5 class="text-white fw-semibold mb-2">Data Belum Lengkap</h5>
    <p class="text-white-50-custom small mb-4">Anda belum mengisi data pendaftaran. Silakan isi form pendaftaran terlebih dahulu.</p>
    <a href="{{ route('dashboard.peserta.form-pendaftaran') }}" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;">
      <i class="icon-base ti tabler-arrow-right me-1"></i> Mulai Isi Data
    </a>
  </div>
  @else

  <div x-data="reviewForm()" x-cloak>
    <form id="formReview" action="{{ route('dashboard.peserta.form-review.submit') }}" method="POST">
      @csrf

      {{-- Section 1: Data Pribadi --}}
      <div class="review-card">
        <div class="review-card-header">
          <div class="review-card-title">
            <span class="review-card-icon" style="background: rgba(99, 102, 241, 0.12); color: #818cf8;">
              <i class="icon-base ti tabler-user"></i>
            </span>
            Data Pribadi
            <span class="badge-lengkap">
              <i class="icon-base ti tabler-check" style="font-size: 10px;"></i> Lengkap
            </span>
          </div>
          <a href="{{ route('dashboard.peserta.form-pendaftaran') }}" class="edit-btn">
            <i class="icon-base ti tabler-pencil"></i> Ubah
          </a>
        </div>
        <div class="data-grid">
          <div class="data-item">
            <span class="data-label">Nama Lengkap</span>
            <span class="data-value">{{ $allData['nama_lengkap'] ?? $profile->nama_lengkap ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Jenis Kelamin</span>
            <span class="data-value">{{ $allData['jenis_kelamin'] ?? $profile->jenis_kelamin ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Tempat Lahir</span>
            <span class="data-value">{{ $allData['tempat_lahir'] ?? $profile->tempat_lahir ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Tanggal Lahir</span>
            <span class="data-value">{{ ($allData['tanggal_lahir'] ?? $profile->tanggal_lahir ?? '-') . ' ' . ($allData['bulan_lahir'] ?? $profile->bulan_lahir ?? '') . ' ' . ($allData['tahun_lahir'] ?? $profile->tahun_lahir ?? '') }}</span>
          </div>
          <div class="data-item full-width">
            <span class="data-label">NIK</span>
            <span class="data-value">{{ $allData['nik'] ?? $profile->nik ?? '-' }}</span>
          </div>
        </div>
      </div>

      {{-- Section 2: Alamat & Kontak --}}
      <div class="review-card">
        <div class="review-card-header">
          <div class="review-card-title">
            <span class="review-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #34d399;">
              <i class="icon-base ti tabler-map-pin"></i>
            </span>
            Alamat &amp; Kontak
            <span class="badge-lengkap">
              <i class="icon-base ti tabler-check" style="font-size: 10px;"></i> Lengkap
            </span>
          </div>
          <a href="{{ route('dashboard.peserta.form-alamat') }}" class="edit-btn">
            <i class="icon-base ti tabler-pencil"></i> Ubah
          </a>
        </div>
        <div class="data-grid">
          <div class="data-item full-width">
            <span class="data-label">Alamat</span>
            <span class="data-value">{{ $allData['alamat_ktp'] ?? $profile->alamat_ktp ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">RT / RW</span>
            <span class="data-value">{{ ($allData['rt'] ?? $profile->rt ?? '-') . ' / ' . ($allData['rw'] ?? $profile->rw ?? '-') }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Kelurahan</span>
            <span class="data-value">{{ $profile->kelurahan ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Kecamatan</span>
            <span class="data-value">{{ $allData['kecamatan_id'] ?? $profile->kecamatan ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Kota</span>
            <span class="data-value">{{ $allData['kota'] ?? $profile->kota ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Provinsi</span>
            <span class="data-value">{{ $allData['provinsi'] ?? $profile->provinsi ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Kode Pos</span>
            <span class="data-value">{{ $allData['kodepos'] ?? $profile->kodepos ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">WhatsApp</span>
            <span class="data-value">{{ $allData['whatsapp'] ?? $profile->whatsapp ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Email</span>
            <span class="data-value">{{ $allData['email'] ?? $profile->email ?? '-' }}</span>
          </div>
          <div class="data-item full-width">
            <span class="data-label">Link Medsos</span>
            <span class="data-value">
              @php
                $linkMedsos = $allData['link_medsos'] ?? $profile->link_medsos ?? [];
                if (is_string($linkMedsos)) $linkMedsos = json_decode($linkMedsos, true) ?? [];
              @endphp
              @if(!empty($linkMedsos) && is_array($linkMedsos))
                @foreach($linkMedsos as $medsos)
                  <div style="font-size: 12px;">{{ ($medsos['platform'] ?? '') . ': ' . ($medsos['url'] ?? '') }}</div>
                @endforeach
              @else
                <span class="text-white-50-custom">-</span>
              @endif
            </span>
          </div>
        </div>
      </div>

      {{-- Section 3: Pendidikan & Pekerjaan --}}
      <div class="review-card">
        <div class="review-card-header">
          <div class="review-card-title">
            <span class="review-card-icon" style="background: rgba(251, 191, 36, 0.12); color: #fbbf24;">
              <i class="icon-base ti tabler-school"></i>
            </span>
            Pendidikan &amp; Pekerjaan
            <span class="badge-lengkap">
              <i class="icon-base ti tabler-check" style="font-size: 10px;"></i> Lengkap
            </span>
          </div>
          <a href="{{ route('dashboard.peserta.form-pendidikan') }}" class="edit-btn">
            <i class="icon-base ti tabler-pencil"></i> Ubah
          </a>
        </div>
        <div class="data-grid">
          <div class="data-item full-width">
            <span class="data-label">Pendidikan Terakhir</span>
            <span class="data-value">{{ $allData['pendidikan_terakhir'] ?? $profile->pendidikan_terakhir ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Institusi</span>
            <span class="data-value">{{ $allData['nama_institusi'] ?? $profile->nama_institusi ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Jurusan</span>
            <span class="data-value">{{ $allData['jurusan'] ?? $profile->jurusan ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Tahun Lulus</span>
            <span class="data-value">{{ $allData['tahun_lulus'] ?? $profile->tahun_lulus ?? '-' }}</span>
          </div>
          <div class="data-item">
            <span class="data-label">Status Pekerjaan</span>
            <span class="data-value">{{ $allData['status_pekerjaan'] ?? $profile->status_pekerjaan ?? '-' }}</span>
          </div>
          @php $statusPekerjaan = $allData['status_pekerjaan'] ?? $profile->status_pekerjaan ?? ''; @endphp
          @if($statusPekerjaan === 'BEKERJA' || $statusPekerjaan === 'Bekerja')
          <div class="data-item full-width">
            <span class="data-label">Nama Perusahaan</span>
            <span class="data-value">{{ $allData['nama_perusahaan'] ?? $profile->nama_perusahaan ?? '-' }}</span>
          </div>
          @endif
        </div>
      </div>

      {{-- Section 4: Minat Pelatihan --}}
      <div class="review-card">
        <div class="review-card-header">
          <div class="review-card-title">
            <span class="review-card-icon" style="background: rgba(236, 72, 153, 0.12); color: #f472b6;">
              <i class="icon-base ti tabler-heart"></i>
            </span>
            Minat Pelatihan
            <span class="badge-lengkap">
              <i class="icon-base ti tabler-check" style="font-size: 10px;"></i> Lengkap
            </span>
          </div>
          <a href="{{ route('dashboard.peserta.form-minat') }}" class="edit-btn">
            <i class="icon-base ti tabler-pencil"></i> Ubah
          </a>
        </div>
        <div class="data-grid">
          <div class="data-item full-width">
            <span class="data-label">Batch Pelatihan</span>
            <span class="data-value">{{ $allData['batch_pelatihan'] ?? $profile->batch_pelatihan ?? '-' }}</span>
          </div>
          @if($profile->pelatihan)
          <div class="data-item full-width">
            <span class="data-label">Pelatihan Terpilih</span>
            <span class="data-value">{{ $allData['batch_pelatihan'] ?? $profile->batch_pelatihan ?? '-' }} : {{ $profile->pelatihan->nama ?? '-' }}</span>
          </div>
          @endif
          <div class="data-item full-width">
            <span class="data-label">Dinas Pelaksana</span>
            <span class="data-value">{{ $profile->pelatihan->dinas->nama_dinas ?? '-' }}</span>
          </div>
        </div>
      </div>

      {{-- Section 5: Jawaban Pertanyaan --}}
      @php
        $jawaban = $profile->jawaban_pertanyaan ?? [];
        if (is_string($jawaban)) $jawaban = json_decode($jawaban, true) ?? [];
        $fieldLabels = [
          'pengetahuan_asep' => 'Apa yang kamu ketahui tentang Bapak H. Asep Mulyadi, S.H.?',
          'alasan_pelatihan' => 'Sebutkan alasan mengikuti pelatihan tersebut.',
          'pengalaman_bisnis' => 'Ceritakan pengalaman bisnis anda dalam bidang pelatihan tersebut.',
          'rencana_setelah_pelatihan' => 'Apa minat/rencana Anda kedepannya setelah mengikuti pelatihan tersebut?',
          'punya_usaha' => 'Apakah anda sudah memiliki usaha yang sedang dijalankan?',
          'jenis_usaha' => 'Jenis usaha yang sedang dijalankan saat ini?',
          'usaha_dimiliki' => 'Usaha yang dimiliki?',
          'usaha_dimiliki_other' => 'Usaha yang dimiliki (lainnya)',
          'nama_usaha' => 'Nama usaha yang sedang dijalankan?',
          'nama_usaha_other' => 'Nama usaha (lainnya)',
          'kendala_usaha' => 'Apa kendala yang dialami dalam menjalankan usaha anda?',
        ];
      @endphp
      <div class="review-card">
        <div class="review-card-header">
          <div class="review-card-title">
            <span class="review-card-icon" style="background: rgba(6, 182, 212, 0.12); color: #22d3ee;">
              <i class="icon-base ti tabler-question-mark"></i>
            </span>
            Jawaban Pertanyaan
            @if(!empty($jawaban))
            <span class="badge-lengkap">
              <i class="icon-base ti tabler-check" style="font-size: 10px;"></i> Lengkap
            </span>
            @endif
          </div>
          <a href="{{ route('dashboard.peserta.form-dokumen') }}" class="edit-btn">
            <i class="icon-base ti tabler-pencil"></i> Ubah
          </a>
        </div>
        <div>
          @if(!empty($jawaban))
            @foreach($jawaban as $key => $value)
              @php
                $label = $fieldLabels[$key] ?? $key;
              @endphp
              <div class="qa-item">
                <div class="qa-question">{{ $label }}</div>
                <div class="qa-answer {{ empty($value) ? 'empty' : '' }}">
                  {{ !empty($value) ? $value : '(Tidak diisi)' }}
                </div>
              </div>
            @endforeach
          @else
            <div class="text-white-50-custom text-center py-3">
              <i class="icon-base ti tabler-alert-circle me-1"></i>Belum ada jawaban pertanyaan.
            </div>
          @endif
        </div>
      </div>

      {{-- Konfirmasi Checkbox --}}
      <div class="review-card" style="border-color: rgba(99, 102, 241, 0.15);">
        <div class="alert alert-warning border-warning border-opacity-20 bg-warning bg-opacity-10 text-warning p-3 mb-3 small" style="border-radius: 5px;">
            <div class="d-flex gap-2">
                <i class="icon-base ti tabler-alert-triangle mt-1 flex-shrink-0"></i>
                <div>
                    <strong>Perhatian:</strong> Setelah pendaftaran dikirim, data penting Anda (Nama, NIK, Alamat KTP, Riwayat Pendidikan, dan Pilihan Pelatihan) akan <strong>DIKUNCI</strong> demi validitas data seleksi. Hanya data kontak (Email, WhatsApp, Media Sosial) yang dapat diubah secara mandiri nantinya.
                </div>
            </div>
        </div>
        <div class="d-flex align-items-start gap-3">
          <div style="width: 32px; height: 32px; border-radius: 5px; background: rgba(99, 102, 241, 0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="icon-base ti tabler-shield-check" style="color: #818cf8; font-size: 1rem;"></i>
          </div>
          <div class="flex-grow-1">
            <div class="form-check mb-1">
              <input class="form-check-input form-check-input-custom" type="checkbox" id="konfirmasi_data" name="konfirmasi" value="1" x-model="konfirmasi" />
              <label class="form-check-label text-white-70-custom" for="konfirmasi_data" style="font-size: 13px; font-weight: 500;">
                Saya menyatakan bahwa data yang diisi adalah benar
              </label>
            </div>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.konfirmasi }" x-text="errors.konfirmasi"></div>
            <p class="text-white-50-custom mb-0 mt-1" style="font-size: 11px;">
              Dengan mencentang ini, Anda menyetujui bahwa seluruh data yang telah diisi adalah akurat dan dapat digunakan untuk keperluan pendaftaran pelatihan.
            </p>
          </div>
        </div>
      </div>

      {{-- Navigation Buttons --}}
      <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('dashboard.peserta.form-dokumen') }}" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;">
          <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
        </a>
        <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="submitReview()" :disabled="submitting">
          <template x-if="!submitting">
            <span><i class="icon-base ti tabler-circle-check me-1"></i> Konfirmasi &amp; Selesaikan</span>
          </template>
          <template x-if="submitting">
            <span><span class="spinner-border spinner-border-sm me-1" style="width:14px;height:14px;border-width:2px;"></span> Memproses...</span>
          </template>
        </button>
      </div>

    </form>
  </div>
  @endif
</div>
@endsection

@section('page-script')
<script>
  document.addEventListener('alpine:init', function() {
    Alpine.data('reviewForm', function() {
      return {
        konfirmasi: false,
        errors: {},
        submitting: false,

        clearErrors() {
          this.errors = {};
        },

        validate() {
          this.clearErrors();
          var valid = true;

          if (!this.konfirmasi) {
            this.errors['konfirmasi'] = 'Anda harus menyetujui pernyataan data benar';
            valid = false;
          }

          return valid;
        },

        submitReview() {
          if (!this.validate()) return;
          this.submitting = true;
          document.getElementById('formReview').submit();
        },
      };
    });
  });
</script>
@endsection
