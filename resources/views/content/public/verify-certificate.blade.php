@php $configData = Helper::appClasses(); @endphp

@extends('layouts/layoutMaster')

@section('title', 'Verifikasi Sertifikat')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');
  .content-wrapper { font-family: 'Outfit', sans-serif; color: #f8fafc; min-height: 100vh; position: relative; overflow: hidden; }
  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container { background-color: #0b0f19 !important; background-image: radial-gradient(at 0% 0%, rgba(99,102,241,0.15) 0px, transparent 55%), radial-gradient(at 100% 0%, rgba(139,92,246,0.15) 0px, transparent 55%) !important; color: #f8fafc !important; }
  .glass-card-premium { background: rgba(15,23,42,0.25) !important; backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.08) !important; box-shadow: 0 20px 60px rgba(0,0,0,0.4); border-radius: 5px !important; z-index: 1; }
  .text-body-premium { color: rgba(255,255,255,0.65) !important; }
  .badge-premium { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.8); border-radius: 5px; padding: 4px 12px; font-weight: 500; font-size: 0.75rem; }
  .badge-premium-success { background: rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.3); color: #34d399; }
  .badge-premium-danger { background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); color: #f87171; }
  .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: rgba(255,255,255,0.4); font-weight: 600; }
  .info-value { font-size: 0.95rem; color: #f8fafc; font-weight: 500; }
  .glow-orb { position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.4; mix-blend-mode: screen; pointer-events: none; z-index: 0; }
  .orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -20%; left: -10%; }
  .orb-2 { width: 500px; height: 500px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: -20%; right: -10%; }
  input.form-control { background: rgba(255,255,255,0.04) !important; border: 1px solid rgba(255,255,255,0.08) !important; color: #f8fafc !important; border-radius: 5px !important; padding: 12px 16px !important; font-size: 1rem !important; }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>

  <div class="container py-5 position-relative" style="z-index: 1;">
    <div class="row justify-content-center">
      <div class="col-lg-6">

        {{-- Header --}}
        <div class="text-center mb-5">
          <i class="icon-base ti tabler-certificate fs-1" style="color: #ffd700;"></i>
          <h2 class="fw-bold text-white mt-2">Verifikasi Sertifikat</h2>
          <p class="text-body-premium">Masukkan nomor sertifikat untuk verifikasi keaslian</p>
        </div>

        {{-- Form --}}
        <div class="glass-card-premium p-4 mb-4">
          <form method="GET" action="{{ route('certificates.verify') }}">
            <div class="input-group">
              <input type="text" name="nomor" class="form-control" placeholder="Contoh: SERTIFIKAT/2026/06/ABC12345" value="{{ $number ?? '' }}" required>
              <button type="submit" class="btn btn-lg fw-bold px-4" style="background: linear-gradient(135deg, #6366f1, #d946ef); color: white; border: none; border-radius: 0 5px 5px 0;">
                <i class="icon-base ti tabler-search me-1"></i> Verifikasi
              </button>
            </div>
          </form>
        </div>

        {{-- Hasil Verifikasi --}}
        @if($number)
          <div class="glass-card-premium p-4">
            @if($certificate)
              <div class="text-center mb-4">
                <i class="icon-base ti fs-1" style="color: #34d399;">✓</i>
                <h4 class="fw-bold text-white mt-2 mb-0">Sertifikat ASLI ✅</h4>
                <p class="text-body-premium">Sertifikat ini terverifikasi di sistem kami</p>
              </div>

              <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 20px;">
                <div class="row g-3">
                  <div class="col-6">
                    <div class="info-label">Nomor Sertifikat</div>
                    <div class="info-value" style="font-family: monospace; color: #ffd700; font-size: 0.85rem;">{{ $certificate->certificate_number }}</div>
                  </div>
                  <div class="col-6">
                    <div class="info-label">Nama Peserta</div>
                    <div class="info-value">{{ $certificate->enrollment->user->name }}</div>
                  </div>
                  <div class="col-6">
                    <div class="info-label">Pelatihan</div>
                    <div class="info-value">{{ $certificate->enrollment->pelatihan->nama }}</div>
                  </div>
                  <div class="col-6">
                    <div class="info-label">Batch</div>
                    <div class="info-value">{{ $certificate->enrollment->pelatihan->batch }}</div>
                  </div>
                  <div class="col-6">
                    <div class="info-label">Tanggal Terbit</div>
                    <div class="info-value">{{ $certificate->issued_at ? $certificate->issued_at->format('d F Y') : '-' }}</div>
                  </div>
                  <div class="col-6">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                      <span class="badge-premium badge-premium-success">Aktif</span>
                    </div>
                  </div>
                </div>
              </div>
            @else
              <div class="text-center">
                <i class="icon-base ti fs-1" style="color: #f87171;">✗</i>
                <h4 class="fw-bold text-white mt-2 mb-0">Sertifikat TIDAK DITEMUKAN ❌</h4>
                <p class="text-body-premium mt-2">
                  Tidak ada sertifikat dengan nomor <strong style="color: #f87171;">{{ $number }}</strong> di sistem kami.
                  <br>Periksa kembali nomor sertifikat yang dimasukkan.
                </p>
              </div>
            @endif
          </div>
        @endif

        {{-- Footer --}}
        <div class="text-center mt-4">
          <p class="text-body-premium" style="font-size: 0.8rem;">
            Sistem Pelatihan Ekonomi Kreatif — Verifikasi Sertifikat Online
          </p>
        </div>

      </div>
    </div>
  </div>
@endsection
