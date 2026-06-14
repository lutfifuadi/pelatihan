@php $configData = Helper::appClasses(); @endphp

@extends('layouts/layoutMaster')

@section('title', 'Detail Sertifikat')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');
  .content-wrapper { font-family: 'Outfit', sans-serif; color: #f8fafc; position: relative; overflow: hidden; }
  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container { background-color: #0b0f19 !important; background-image: radial-gradient(at 0% 0%, rgba(99,102,241,0.15) 0px, transparent 55%), radial-gradient(at 100% 0%, rgba(139,92,246,0.15) 0px, transparent 55%) !important; color: #f8fafc !important; }
  .glass-card-premium { background: rgba(15,23,42,0.25) !important; backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.08) !important; box-shadow: 0 20px 60px rgba(0,0,0,0.4); border-radius: 5px !important; z-index: 1; }
  .text-body-premium { color: rgba(255,255,255,0.65) !important; }
  .badge-premium { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.8); border-radius: 5px; padding: 4px 12px; font-weight: 500; font-size: 0.75rem; }
  .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: rgba(255,255,255,0.4); font-weight: 600; }
  .info-value { font-size: 0.95rem; color: #f8fafc; font-weight: 500; }
  .glow-orb { position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.4; mix-blend-mode: screen; pointer-events: none; z-index: 0; }
  .orb-1 { width: 400px; height: 400px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; }
  .orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <a href="{{ route('admin.certificates.index') }}" class="text-body-premium text-decoration-none mb-2 d-inline-block" style="font-size: 0.85rem;">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
          </a>
          <h4 class="fw-bold text-white mb-1">Detail Sertifikat</h4>
          <p class="text-body-premium mb-0" style="font-size: 0.95rem;">
            {{ $certificate->enrollment->user->name }} — {{ $certificate->enrollment->pelatihan->nama }}
          </p>
        </div>
        <a href="{{ route('admin.certificates.download', $certificate) }}" class="btn btn-success px-4 py-2 fw-bold" style="border-radius: 5px;">
          <i class="icon-base ti tabler-download me-1"></i> Download PDF
        </a>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="glass-card-premium p-4">
          <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-user me-2"></i> Data Peserta
          </h5>
          <div class="row g-3">
            <div class="col-6">
              <div class="info-label">Nama</div>
              <div class="info-value">{{ $certificate->enrollment->user->name }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">Email</div>
              <div class="info-value">{{ $certificate->enrollment->user->email }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">WhatsApp</div>
              <div class="info-value">{{ $certificate->enrollment->user->whatsapp ?? '-' }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">NIK</div>
              <div class="info-value">{{ $certificate->enrollment->user->nik ?? '-' }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="glass-card-premium p-4">
          <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-certificate me-2"></i> Data Sertifikat
          </h5>
          <div class="row g-3">
            <div class="col-12">
              <div class="info-label">Nomor Sertifikat</div>
              <div class="info-value" style="font-family: monospace; color: #ffd700;">{{ $certificate->certificate_number }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">Diterbitkan</div>
              <div class="info-value">{{ $certificate->issued_at ? $certificate->issued_at->format('d F Y H:i') : '-' }}</div>
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
              <div class="info-label">Tanggal Pelaksanaan</div>
              <div class="info-value">
                {{ $certificate->enrollment->pelatihan->tanggal_mulai ? \Carbon\Carbon::parse($certificate->enrollment->pelatihan->tanggal_mulai)->format('d/m/Y') : '-' }}
                -
                {{ $certificate->enrollment->pelatihan->tanggal_selesai ? \Carbon\Carbon::parse($certificate->enrollment->pelatihan->tanggal_selesai)->format('d/m/Y') : '-' }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="glass-card-premium p-4 text-center">
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">Link Verifikasi Publik</h5>
          <div class="d-flex justify-content-center gap-2 flex-wrap">
            <input type="text" class="form-control w-auto" value="{{ route('certificates.verify', ['nomor' => $certificate->certificate_number]) }}" readonly style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #f8fafc; border-radius: 5px; min-width: 400px; text-align: center;">
            <button class="btn btn-outline-info" style="border-radius: 5px; border-color: rgba(96,165,250,0.3); color: #93c5fd;" onclick="navigator.clipboard.writeText(this.previousElementSibling.value); this.textContent='Tersalin!'; setTimeout(()=>this.textContent='Salin', 2000);">
              Salin
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
