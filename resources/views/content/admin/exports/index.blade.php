@extends('layouts.layoutMaster')

@section('title', 'Export Data')

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

  html, body, .layout-page, .content-wrapper,
  .layout-wrapper, .layout-container {
    background-color: #0b0f19 !important;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
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
  .glass-card-premium:hover {
    transform: translateY(-2px) !important;
    border-color: rgba(99, 102, 241, 0.2) !important;
  }

  .text-body-premium {
    color: rgba(255, 255, 255, 0.65) !important;
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

  .stat-icon-primary { background: rgba(99, 102, 241, 0.12); color: #6366f1; }
  .stat-icon-success { background: rgba(16, 185, 129, 0.12); color: #34d399; }
  .stat-icon-warning { background: rgba(245, 158, 11, 0.12); color: #fbbf24; }
  .stat-icon-danger  { background: rgba(239, 68, 68, 0.12); color: #f87171; }
  .stat-icon-info    { background: rgba(6, 182, 212, 0.12); color: #22d3ee; }
  .stat-icon-purple  { background: rgba(168, 85, 247, 0.12); color: #c084fc; }

  .badge-premium {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 4px 12px;
    font-weight: 500;
    font-size: 0.75rem;
    white-space: nowrap;
  }

  .export-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    padding: 20px;
    transition: all 0.3s ease;
    height: 100%;
  }
  .export-card:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(255, 255, 255, 0.12);
    transform: translateY(-2px);
  }

  .export-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
  }

  .btn-download {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 6px 16px;
    font-size: 0.78rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .btn-download:hover {
    background: rgba(99, 102, 241, 0.15);
    border-color: rgba(99, 102, 241, 0.3);
    color: #fff;
  }
  .btn-download-pdf:hover {
    background: rgba(239, 68, 68, 0.15);
    border-color: rgba(239, 68, 68, 0.3);
  }
  .btn-download-excel:hover {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.3);
  }

  .export-section-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
  }

  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .fade-in-up {
    animation: fadeInUp 0.4s ease forwards;
  }

  .export-card {
    opacity: 0;
    animation: fadeInUp 0.35s ease forwards;
  }
  .export-card:nth-child(1) { animation-delay: 0.05s; }
  .export-card:nth-child(2) { animation-delay: 0.10s; }
  .export-card:nth-child(3) { animation-delay: 0.15s; }
  .export-card:nth-child(4) { animation-delay: 0.20s; }
  .export-card:nth-child(5) { animation-delay: 0.25s; }
  .export-card:nth-child(6) { animation-delay: 0.30s; }
  .export-card:nth-child(7) { animation-delay: 0.35s; }
  .export-card:nth-child(8) { animation-delay: 0.40s; }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

  {{-- Header --}}
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box stat-icon-success">
          <i class="icon-base ti tabler-file-export fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-1">Export Data</h4>
          <p class="text-body-premium mb-0" style="font-size: 0.95rem;">
            Download data peserta, pendaftaran, absensi, dan sertifikat dalam format PDF atau Excel
          </p>
        </div>
      </div>
      <span class="badge-premium" style="background: rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.3); color: #34d399;">
        <i class="icon-base ti tabler-download me-1"></i> Export Center
      </span>
    </div>
  </div>

  {{-- ===== PESERTA ===== --}}
  <div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
      <div class="export-icon-box" style="background: rgba(99,102,241,0.12); color: #6366f1;">
        <i class="icon-base ti tabler-users"></i>
      </div>
      <h5 class="fw-bold text-white mb-0">Data Peserta</h5>
    </div>
    <div class="row g-3">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="export-card d-flex flex-column gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="export-icon-box" style="background: rgba(239,68,68,0.12); color: #f87171;">
              <i class="icon-base ti tabler-file-type-pdf"></i>
            </div>
            <div>
              <div class="fw-semibold text-white" style="font-size: 0.9rem;">PDF</div>
              <small class="text-body-premium">Export semua peserta</small>
            </div>
          </div>
          <a href="{{ route('admin.exports.peserta.pdf') }}" class="btn-download btn-download-pdf w-100 justify-content-center">
            <i class="icon-base ti tabler-download"></i> Download PDF
          </a>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="export-card d-flex flex-column gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="export-icon-box" style="background: rgba(16,185,129,0.12); color: #34d399;">
              <i class="icon-base ti tabler-file-spreadsheet"></i>
            </div>
            <div>
              <div class="fw-semibold text-white" style="font-size: 0.9rem;">Excel</div>
              <small class="text-body-premium">Export semua peserta</small>
            </div>
          </div>
          <a href="{{ route('admin.exports.peserta.excel') }}" class="btn-download btn-download-excel w-100 justify-content-center">
            <i class="icon-base ti tabler-download"></i> Download Excel
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== ENROLLMENTS (PENDAFTARAN) ===== --}}
  <div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
      <div class="export-icon-box" style="background: rgba(245,158,11,0.12); color: #fbbf24;">
        <i class="icon-base ti tabler-clipboard-list"></i>
      </div>
      <h5 class="fw-bold text-white mb-0">Data Pendaftaran (Enrollments)</h5>
    </div>
    <div class="row g-3">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="export-card d-flex flex-column gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="export-icon-box" style="background: rgba(239,68,68,0.12); color: #f87171;">
              <i class="icon-base ti tabler-file-type-pdf"></i>
            </div>
            <div>
              <div class="fw-semibold text-white" style="font-size: 0.9rem;">PDF</div>
              <small class="text-body-premium">Semua pendaftaran</small>
            </div>
          </div>
          <a href="{{ route('admin.exports.enrollments.pdf') }}" class="btn-download btn-download-pdf w-100 justify-content-center">
            <i class="icon-base ti tabler-download"></i> Download PDF
          </a>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="export-card d-flex flex-column gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="export-icon-box" style="background: rgba(16,185,129,0.12); color: #34d399;">
              <i class="icon-base ti tabler-file-spreadsheet"></i>
            </div>
            <div>
              <div class="fw-semibold text-white" style="font-size: 0.9rem;">Excel</div>
              <small class="text-body-premium">Semua pendaftaran</small>
            </div>
          </div>
          <a href="{{ route('admin.exports.enrollments.excel') }}" class="btn-download btn-download-excel w-100 justify-content-center">
            <i class="icon-base ti tabler-download"></i> Download Excel
          </a>
        </div>
      </div>
      @if($pelatihans->count() > 0)
      <div class="col-12">
        <div class="glass-card-premium px-4 py-3">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="icon-base ti tabler-filter text-body-premium"></i>
            <small class="text-body-premium fw-semibold">Filter by Pelatihan:</small>
          </div>
          <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.exports.enrollments.pdf') }}" class="btn-download">Semua</a>
            @foreach($pelatihans as $p)
            <a href="{{ route('admin.exports.enrollments.pdf', ['pelatihan' => $p->id]) }}" class="btn-download">
              <i class="icon-base ti tabler-file-type-pdf" style="color: #f87171; font-size: 0.9rem;"></i>
              {{ $p->nama }}
            </a>
            <a href="{{ route('admin.exports.enrollments.excel', ['pelatihan' => $p->id]) }}" class="btn-download">
              <i class="icon-base ti tabler-file-spreadsheet" style="color: #34d399; font-size: 0.9rem;"></i>
              {{ $p->nama }}
            </a>
            @endforeach
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>

  {{-- ===== ATTENDANCE (ABSENSI) ===== --}}
  <div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
      <div class="export-icon-box" style="background: rgba(6,182,212,0.12); color: #22d3ee;">
        <i class="icon-base ti tabler-calendar-check"></i>
      </div>
      <h5 class="fw-bold text-white mb-0">Rekapitulasi Absensi</h5>
    </div>
    <div class="row g-3">
      @if($pelatihans->count() > 0)
        @foreach($pelatihans as $p)
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
          <div class="export-card d-flex flex-column gap-3">
            <div>
              <div class="fw-semibold text-white mb-1" style="font-size: 0.85rem;">{{ $p->nama }}</div>
              <small class="text-body-premium">Rekap absensi per pelatihan</small>
            </div>
            <div class="d-flex gap-2">
              <a href="{{ route('admin.exports.attendance.pdf', ['pelatihan' => $p->id]) }}" class="btn-download btn-download-pdf flex-fill justify-content-center">
                <i class="icon-base ti tabler-file-type-pdf"></i> PDF
              </a>
              <a href="{{ route('admin.exports.attendance.excel', ['pelatihan' => $p->id]) }}" class="btn-download btn-download-excel flex-fill justify-content-center">
                <i class="icon-base ti tabler-file-spreadsheet"></i> Excel
              </a>
            </div>
          </div>
        </div>
        @endforeach
      @else
        <div class="col-12">
          <div class="export-card text-center py-4">
            <i class="icon-base ti tabler-calendar-off text-body-premium" style="font-size: 2rem;"></i>
            <p class="text-body-premium mt-2 mb-0">Belum ada pelatihan aktif. Buat pelatihan terlebih dahulu.</p>
          </div>
        </div>
      @endif
    </div>
  </div>

  {{-- ===== CERTIFICATES (SERTIFIKAT) ===== --}}
  <div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
      <div class="export-icon-box" style="background: rgba(168,85,247,0.12); color: #c084fc;">
        <i class="icon-base ti tabler-certificate"></i>
      </div>
      <h5 class="fw-bold text-white mb-0">Data Sertifikat</h5>
    </div>
    <div class="row g-3">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="export-card d-flex flex-column gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="export-icon-box" style="background: rgba(239,68,68,0.12); color: #f87171;">
              <i class="icon-base ti tabler-file-type-pdf"></i>
            </div>
            <div>
              <div class="fw-semibold text-white" style="font-size: 0.9rem;">PDF</div>
              <small class="text-body-premium">Export semua sertifikat</small>
            </div>
          </div>
          <a href="{{ route('admin.exports.certificates.pdf') }}" class="btn-download btn-download-pdf w-100 justify-content-center">
            <i class="icon-base ti tabler-download"></i> Download PDF
          </a>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="export-card d-flex flex-column gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="export-icon-box" style="background: rgba(16,185,129,0.12); color: #34d399;">
              <i class="icon-base ti tabler-file-spreadsheet"></i>
            </div>
            <div>
              <div class="fw-semibold text-white" style="font-size: 0.9rem;">Excel</div>
              <small class="text-body-premium">Export semua sertifikat</small>
            </div>
          </div>
          <a href="{{ route('admin.exports.certificates.excel') }}" class="btn-download btn-download-excel w-100 justify-content-center">
            <i class="icon-base ti tabler-download"></i> Download Excel
          </a>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
