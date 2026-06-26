@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Pendaftaran Pelatihan')

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

  .layout-navbar-fixed .layout-page::before {
    display: none !important;
  }

  .content-wrapper > .container-xxl {
    max-width: 100% !important;
    padding: 0 !important;
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

  .stat-icon-box-sm {
    width: 32px;
    height: 32px;
    border-radius: 5px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
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
  .badge-premium-success {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.3);
    color: #34d399;
  }
  .badge-premium-warning {
    background: rgba(245, 158, 11, 0.15);
    border-color: rgba(245, 158, 11, 0.3);
    color: #fbbf24;
  }
  .badge-premium-danger {
    background: rgba(239, 68, 68, 0.15);
    border-color: rgba(239, 68, 68, 0.3);
    color: #f87171;
  }
  .badge-premium-info {
    background: rgba(96, 165, 250, 0.15);
    border-color: rgba(96, 165, 250, 0.3);
    color: #93c5fd;
  }

  .pagination .page-item .page-link {
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    transition: all 0.3s ease !important;
    border-radius: 5px !important;
    margin: 0 2px !important;
  }
  .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border-color: transparent !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
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
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  @keyframes orbFloat {
    0% { transform: translate(0, 0) scale(1) rotate(0deg); }
    50% { transform: translate(60px, 40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px, -50px) scale(0.92) rotate(360deg); }
  }

  .btn-action {
    border-radius: 5px !important;
    padding: 4px 12px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
  }
  .btn-action:hover {
    transform: translateY(-1px);
  }
  .btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
    border: none !important;
    color: #ffffff !important;
  }
  .btn-danger:hover {
    background: linear-gradient(135deg, #f87171, #ef4444) !important;
    transform: translateY(-1px);
    color: #ffffff !important;
  }
  .btn-danger:focus {
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.4) !important;
  }
  select.form-select {
    background-color: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    color: rgba(255,255,255,0.8) !important;
    border-radius: 5px !important;
    padding: 6px 12px !important;
    font-size: 0.85rem !important;
  }
  select.form-select option {
    background-color: #0b0f19 !important;
    color: #f8fafc !important;
  }

  /* SweetAlert2 Custom Styling */
  .swal2-popup.swal2-custom-popup {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 8px !important;
    padding: 1.75rem !important;
  }
  .swal2-title.swal2-custom-title {
    font-size: 1.15rem !important;
    font-family: 'Sora', sans-serif !important;
    font-weight: 600 !important;
    color: #ffffff !important;
    margin-top: 1rem !important;
    margin-bottom: 0.75rem !important;
  }
  .swal2-html-container.swal2-custom-text {
    font-size: 0.85rem !important;
    line-height: 1.6 !important;
    color: rgba(255, 255, 255, 0.75) !important;
    margin-bottom: 1.5rem !important;
    padding: 0 !important;
  }
  .swal2-custom-popup .swal2-icon {
    transform: scale(0.85) !important;
    margin-top: 0.5rem !important;
    margin-bottom: 0.25rem !important;
  }
  .swal2-actions.swal2-custom-actions {
    margin-top: 0.5rem !important;
  }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    {{-- Title --}}
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="fw-bold text-white mb-0">Pendaftaran Pelatihan</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Kelola approve, reject, dan daftar cadangan peserta pelatihan
          </p>
        </div>
      </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- Stat Cards --}}
    <div class="row g-2 mb-4">
      <div class="col-md col-6">
        <div class="glass-card-premium px-2 py-2">
          <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="stat-icon-box-sm" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
              <i class="icon-base ti tabler-clock" style="font-size: 1rem;"></i>
            </div>
            <div class="text-end">
              <h5 class="fw-bold text-white mb-0" id="stat-pending" style="font-size: 1.5rem; line-height: 1.2;">{{ $counts['pending'] }}</h5>
              <small class="text-body-premium" style="font-size: 0.65rem;">Pending</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md col-6">
        <div class="glass-card-premium px-2 py-2">
          <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="stat-icon-box-sm" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
              <i class="icon-base ti tabler-check" style="font-size: 1rem;"></i>
            </div>
            <div class="text-end">
              <h5 class="fw-bold text-white mb-0" id="stat-approved" style="font-size: 1.5rem; line-height: 1.2;">{{ $counts['approved'] }}</h5>
              <small class="text-body-premium" style="font-size: 0.65rem;">Approved</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md col-6">
        <div class="glass-card-premium px-2 py-2">
          <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="stat-icon-box-sm" style="background: rgba(234,179,8,0.15); color: #eab308;">
              <i class="icon-base ti tabler-brand-whatsapp" style="font-size: 1rem;"></i>
            </div>
            <div class="text-end">
              <h5 class="fw-bold text-white mb-0" id="stat-waiting-wa" style="font-size: 1.5rem; line-height: 1.2;">{{ $counts['waiting_wa'] ?? 0 }}</h5>
              <small class="text-body-premium" style="font-size: 0.65rem;">M. Chat WA</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md col-6">
        <div class="glass-card-premium px-2 py-2">
          <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="stat-icon-box-sm" style="background: rgba(59,130,246,0.15); color: #3b82f6;">
              <i class="icon-base ti tabler-search" style="font-size: 1rem;"></i>
            </div>
            <div class="text-end">
              <h5 class="fw-bold text-white mb-0" id="stat-waiting-newbimma" style="font-size: 1.5rem; line-height: 1.2;">{{ $counts['waiting_newbimma'] ?? 0 }}</h5>
              <small class="text-body-premium" style="font-size: 0.65rem;">Cek Newbimma</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md col-6">
        <div class="glass-card-premium px-2 py-2">
          <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="stat-icon-box-sm" style="background: rgba(34,197,94,0.15); color: #22c55e;">
              <i class="icon-base ti tabler-circle-check" style="font-size: 1rem;"></i>
            </div>
            <div class="text-end">
              <h5 class="fw-bold text-white mb-0" id="stat-confirmed" style="font-size: 1.5rem; line-height: 1.2;">{{ $counts['confirmed'] ?? 0 }}</h5>
              <small class="text-body-premium" style="font-size: 0.65rem;">Terkonfirmasi</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md col-6">
        <div class="glass-card-premium px-2 py-2">
          <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="stat-icon-box-sm" style="background: rgba(239, 68, 68, 0.15); color: #f87171;">
              <i class="icon-base ti tabler-x" style="font-size: 1rem;"></i>
            </div>
            <div class="text-end">
              <h5 class="fw-bold text-white mb-0" id="stat-rejected" style="font-size: 1.5rem; line-height: 1.2;">{{ $counts['rejected'] }}</h5>
              <small class="text-body-premium" style="font-size: 0.65rem;">Ditolak</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md col-6">
        <div class="glass-card-premium px-2 py-2">
          <div class="d-flex align-items-center justify-content-between gap-2">
            <div class="stat-icon-box-sm" style="background: rgba(96, 165, 250, 0.15); color: #93c5fd;">
              <i class="icon-base ti tabler-users" style="font-size: 1rem;"></i>
            </div>
            <div class="text-end">
              <h5 class="fw-bold text-white mb-0" id="stat-waitlist" style="font-size: 1.5rem; line-height: 1.2;">{{ $counts['waitlist'] }}</h5>
              <small class="text-body-premium" style="font-size: 0.65rem;">Cadangan</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Search --}}
    <div class="col-12 mb-4">
      <div class="glass-card-premium px-4 py-3">
        <div class="row align-items-center g-3">
          <div class="col-12">
            <div class="d-flex gap-2">
              <div class="position-relative flex-grow-1">
                <i class="icon-base ti tabler-search position-absolute top-50 start-0 translate-middle-y ms-3 text-body-premium" style="font-size: 1rem; z-index: 2;"></i>
                <input type="text" id="search-input" class="form-control" placeholder="Cari nama peserta..." value="{{ $search ?? '' }}" style="padding-left: 2.5rem !important;">
              </div>
              <button type="button" id="search-btn" class="btn btn-glow-premium px-3 py-2" style="white-space: nowrap; border-radius: 5px;" title="Cari">
                <i class="icon-base ti tabler-search"></i>
              </button>
              <a href="{{ route('admin.enrollments.index') }}" id="reset-btn" class="btn btn-secondary-custom px-3 py-2 {{ ($search ?? '') ? '' : 'd-none' }}" title="Reset pencarian">
                <i class="icon-base ti tabler-x"></i>
              </a>
              <div id="loading-spinner" class="d-none">
                <div class="spinner-border spinner-border-sm text-warning" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Filter --}}
    <div class="glass-card-premium px-4 py-3 mb-4">
      <div class="row g-3 align-items-end">
        <div class="col-md-6">
          <label class="text-body-premium small fw-semibold mb-1">Filter Pelatihan</label>
          <select id="filter-pelatihan" class="form-select">
            <option value="">Semua Pelatihan</option>
            @foreach($pelatihans as $p)
              <option value="{{ $p->id }}" {{ request('pelatihan_id') == $p->id ? 'selected' : '' }}>
                {{ $p->nama }} ({{ $p->batch }})
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="text-body-premium small fw-semibold mb-1">Filter Status</label>
          <select id="filter-status" class="form-select">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="waiting_wa" {{ request('status') == 'waiting_wa' ? 'selected' : '' }}>
              ⏳ Menunggu Chat WA
            </option>
            <option value="waiting_newbimma" {{ request('status') == 'waiting_newbimma' ? 'selected' : '' }}>
              🔄 Cek Newbimma
            </option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
              ✅ Terkonfirmasi
            </option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            <option value="waitlist" {{ request('status') == 'waitlist' ? 'selected' : '' }}>Cadangan</option>
          </select>
        </div>
        <div class="col-md-3">
          <button type="button" id="filter-reset-btn" class="btn btn-outline-secondary btn-action w-100" style="color: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.1);">
            <i class="icon-base ti tabler-refresh me-1"></i> Reset
          </button>
        </div>
      </div>
    </div>

    {{-- Action Buttons --}}
    <div class="glass-card-premium px-4 py-3 mb-4 {{ request('pelatihan_id') ? '' : 'd-none' }}" id="action-buttons-container">
      <div class="row align-items-center g-2">
        <div class="col-12">
          <div class="d-flex flex-wrap gap-2">
            {{-- Approve All --}}
            <button type="button" id="btn-approve-all" class="btn btn-success btn-action px-4"
              data-url="{{ request('pelatihan_id') ? route('admin.enrollments.approve-all', ['pelatihan' => request('pelatihan_id')]) : '' }}"
              data-pending="{{ $counts['pending'] }}">
              <i class="icon-base ti tabler-check me-1"></i>
              Approve All Pending <span class="badge bg-white text-dark ms-1">{{ $counts['pending'] }}</span>
            </button>

            {{-- Reject All --}}
            <button type="button" id="btn-reject-all" class="btn btn-danger btn-action px-4"
              data-url="{{ request('pelatihan_id') ? route('admin.enrollments.reject-all', ['pelatihan' => request('pelatihan_id')]) : '' }}"
              data-pending="{{ $counts['pending'] }}">
              <i class="icon-base ti tabler-x me-1"></i>
              Reject All Pending <span class="badge bg-white text-dark ms-1">{{ $counts['pending'] }}</span>
            </button>
          </div>
          <small class="text-body-premium mt-1 d-block">Approve atau tolak semua pendaftaran pending untuk pelatihan ini</small>
        </div>
      </div>
    </div>

    {{-- Generate All Verification Codes Button --}}
    <div class="glass-card-premium px-4 py-3 mb-4">
      <div class="row align-items-center">
        <div class="col-12">
        <form action="{{ route('admin.enrollments.generate-all-verification-codes') }}" method="POST" class="d-inline" id="form-generate-all-codes">
          @csrf
          <button type="submit" class="btn btn-action px-4"
                  style="background: #6366f1; color: white; border: none; border-radius: 5px; padding: 6px 16px;">
            <i class="icon-base ti tabler-key me-1"></i>
            Generate Semua Kode Verifikasi
          </button>
        </form>
          <small class="text-body-premium ms-2">Generate kode untuk semua enrollment approved yang belum punya</small>
        </div>
      </div>
    </div>

    {{-- Export Buttons --}}
    <div class="glass-card-premium px-4 py-3 mb-4">
      <div class="d-flex justify-content-end align-items-center gap-2">
        <a href="{{ request('pelatihan_id') ? route('admin.exports.enrollments.pdf', ['pelatihan' => request('pelatihan_id')]) : route('admin.exports.enrollments.pdf') }}" class="btn btn-outline-info btn-action" style="border-color: rgba(96,165,250,0.3); color: #93c5fd;">
          <i class="icon-base ti tabler-file-export me-1"></i> 📄 Export PDF
        </a>
        <a href="{{ request('pelatihan_id') ? route('admin.exports.enrollments.excel', ['pelatihan' => request('pelatihan_id')]) : route('admin.exports.enrollments.excel') }}" class="btn btn-outline-success btn-action" style="border-color: rgba(16,185,129,0.3); color: #34d399;">
          <i class="icon-base ti tabler-file-spreadsheet me-1"></i> 📊 Export Excel
        </a>
      </div>
    </div>

    {{-- Table --}}
    <div class="glass-card-premium px-4 py-4">
      <div class="table-responsive">
        <table class="table table-borderless text-white align-middle">
          <thead>
            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
              <th class="text-body-premium small fw-semibold px-0" style="width: 50px;">No</th>
              <th class="text-body-premium small fw-semibold">Nama Peserta</th>
              <th class="text-body-premium small fw-semibold">Pelatihan</th>
              <th class="text-body-premium small fw-semibold">Tgl Daftar</th>
              <th class="text-body-premium small fw-semibold">Status</th>
              <th class="text-body-premium small fw-semibold text-end px-0" style="width: 140px;">Aksi</th>
            </tr>
          </thead>
          <tbody id="table-content">
            @include('content.admin.enrollments._table_rows')
          </tbody>
        </table>
      </div>
      <div id="table-pagination">
        @if($enrollments->hasPages())
          <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
            {{ $enrollments->links() }}
          </div>
        @endif
      </div>
    </div>

  </div>
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
<script>
  // Approve All & Reject All button handlers
  document.addEventListener('DOMContentLoaded', function() {
    const approveAllBtn = document.getElementById('btn-approve-all');
    if (approveAllBtn) {
      approveAllBtn.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        const pending = this.getAttribute('data-pending');

        Swal.fire({
          title: 'Approve Semua?',
          text: `Anda akan meng-approve ${pending} pendaftaran pending untuk pelatihan ini.`,
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Ya, Approve Semua!',
          cancelButtonText: 'Batal',
          confirmButtonColor: '#10b981',
          cancelButtonColor: '#6b7280',
          reverseButtons: true,
          background: '#0f172a',
          color: '#f8fafc',
          customClass: {
            popup: 'rounded-3 shadow-lg',
            title: 'fw-bold text-white',
            htmlContainer: 'text-body-premium',
            confirmButton: 'btn btn-success px-4 py-2 border-0 me-2',
            cancelButton: 'btn btn-secondary-custom px-4 py-2 border-0',
          },
          buttonsStyling: false,
        }).then((result) => {
          if (result.isConfirmed) {
            // Show loading
            approveAllBtn.disabled = true;
            approveAllBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

            fetch(url, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
              }
            }).then(res => {
              if (res.ok) {
                window.location.reload();
              } else {
                throw new Error('Gagal');
              }
            }).catch(() => {
              window.location.reload();
            });
          }
        });
      });
    }

    // Reject All button handler
    const rejectAllBtn = document.getElementById('btn-reject-all');
    if (rejectAllBtn) {
      rejectAllBtn.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        const pending = this.getAttribute('data-pending');

        Swal.fire({
          title: 'Tolak Semua?',
          html: `<div>Anda akan menolak <strong style="color: #f87171;">${pending}</strong> pendaftaran pending untuk pelatihan ini.</div>
                 <div class="mt-2" style="font-size: 0.8rem; color: rgba(255,255,255,0.5);">Tindakan ini tidak dapat dibatalkan.</div>`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Tolak Semua!',
          cancelButtonText: 'Batal',
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#6b7280',
          reverseButtons: true,
          background: '#0f172a',
          color: '#f8fafc',
          customClass: {
            popup: 'rounded-3 shadow-lg',
            title: 'fw-bold text-white',
            htmlContainer: 'text-body-premium',
            confirmButton: 'btn btn-danger px-4 py-2 border-0 me-2',
            cancelButton: 'btn btn-secondary-custom px-4 py-2 border-0',
          },
          buttonsStyling: false,
        }).then((result) => {
          if (result.isConfirmed) {
            // Show loading
            rejectAllBtn.disabled = true;
            rejectAllBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

            fetch(url, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
              }
            }).then(res => {
              if (res.ok) {
                window.location.reload();
              } else {
                throw new Error('Gagal');
              }
            }).catch(() => {
              window.location.reload();
            });
          }
        });
      });
    }
  });

(function() {
    let search = {!! json_encode($search ?? '') !!};
    let loading = false;
    let debounceTimer = null;
    let abortController = null;

    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const resetBtn = document.getElementById('reset-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    const tableContent = document.getElementById('table-content');
    const paginationContainer = document.getElementById('table-pagination');
    const filterPelatihan = document.getElementById('filter-pelatihan');
    const filterStatus = document.getElementById('filter-status');
    const filterResetBtn = document.getElementById('filter-reset-btn');

    /**
     * Baca filter pelatihan_id & status dari dropdown.
     * Hanya mengembalikan yang punya nilai (tidak empty).
     */
    function getDropdownFilters() {
      const filters = {};
      if (filterPelatihan.value) filters.pelatihan_id = filterPelatihan.value;
      if (filterStatus.value) filters.status = filterStatus.value;
      return filters;
    }

    /**
     * Sinkronkan nilai dropdown dengan URL saat ini (misal akses langsung via URL).
     */
    function syncDropdownsFromUrl() {
      const urlObj = new URL(window.location);
      const pelatihanId = urlObj.searchParams.get('pelatihan_id');
      const status = urlObj.searchParams.get('status');
      if (pelatihanId) filterPelatihan.value = pelatihanId;
      if (status) filterStatus.value = status;
    }

    /**
     * Terapkan filter dari dropdown + search, lalu fetch data via AJAX.
     */
    function applyFilters() {
      const url = new URL(window.location);
      const dropdownFilters = getDropdownFilters();

      // Update URL params dari dropdown
      if (dropdownFilters.pelatihan_id) url.searchParams.set('pelatihan_id', dropdownFilters.pelatihan_id);
      else url.searchParams.delete('pelatihan_id');
      if (dropdownFilters.status) url.searchParams.set('status', dropdownFilters.status);
      else url.searchParams.delete('status');

      // Reset ke halaman 1 saat filter berubah
      url.searchParams.delete('page');
      window.history.replaceState({}, '', url);

      fetchData();
    }

    async function fetchData(targetPage = null) {
      // Abort previous request if still in-flight
      if (abortController) abortController.abort();
      abortController = new AbortController();

      loading = true;
      loadingSpinner.classList.remove('d-none');

      const dropdownFilters = getDropdownFilters();
      const params = new URLSearchParams({ ...dropdownFilters, search: search || '' });
      if (targetPage) params.set('page', targetPage);

      try {
        const res = await fetch(`/admin/enrollments?${params.toString()}`, {
          method: 'GET',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          signal: abortController.signal
        });
        if (!res.ok) throw new Error('Response error');
        const data = await res.json();
        tableContent.innerHTML = data.rows;
        if (data.pagination) {
          paginationContainer.innerHTML = data.pagination;
        } else {
          paginationContainer.innerHTML = '';
        }

        // Update action buttons container visibility & URL
        const actionContainer = document.getElementById('action-buttons-container');
        const approveAllBtn = document.getElementById('btn-approve-all');
        const rejectAllBtn = document.getElementById('btn-reject-all');
        const pelatihanId = dropdownFilters.pelatihan_id;

        if (actionContainer) {
          if (pelatihanId) {
            actionContainer.classList.remove('d-none');
          } else {
            actionContainer.classList.add('d-none');
          }
        }

        if (approveAllBtn && pelatihanId) {
          approveAllBtn.setAttribute('data-url', `/admin/enrollments/pelatihan/${pelatihanId}/approve-all`);
        }
        if (rejectAllBtn && pelatihanId) {
          rejectAllBtn.setAttribute('data-url', `/admin/enrollments/pelatihan/${pelatihanId}/reject-all`);
        }

        // Update stat cards dari response
        if (data.counts) {
          const pendingEl = document.getElementById('stat-pending');
          if (pendingEl) pendingEl.textContent = data.counts.pending || 0;
          const approvedEl = document.getElementById('stat-approved');
          if (approvedEl) approvedEl.textContent = data.counts.approved || 0;
          const rejectedEl = document.getElementById('stat-rejected');
          if (rejectedEl) rejectedEl.textContent = data.counts.rejected || 0;
          const waitlistEl = document.getElementById('stat-waitlist');
          if (waitlistEl) waitlistEl.textContent = data.counts.waitlist || 0;
          const waitingWaEl = document.getElementById('stat-waiting-wa');
          if (waitingWaEl) waitingWaEl.textContent = data.counts.waiting_wa || 0;
          const waitingNewbimmaEl = document.getElementById('stat-waiting-newbimma');
          if (waitingNewbimmaEl) waitingNewbimmaEl.textContent = data.counts.waiting_newbimma || 0;
          const confirmedEl = document.getElementById('stat-confirmed');
          if (confirmedEl) confirmedEl.textContent = data.counts.confirmed || 0;

          const approveAllBtn = document.getElementById('btn-approve-all');
          if (approveAllBtn) {
            const badge = approveAllBtn.querySelector('.badge');
            if (badge) badge.textContent = data.counts.pending || 0;
            approveAllBtn.setAttribute('data-pending', data.counts.pending || 0);
          }

          const rejectAllBtn = document.getElementById('btn-reject-all');
          if (rejectAllBtn) {
            const badge = rejectAllBtn.querySelector('.badge');
            if (badge) badge.textContent = data.counts.pending || 0;
            rejectAllBtn.setAttribute('data-pending', data.counts.pending || 0);
          }
        }

        // Sync URL — hanya update search & page, filter params sudah dihandle oleh applyFilters
        const url = new URL(window.location);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        if (targetPage) url.searchParams.set('page', targetPage);
        window.history.replaceState({}, '', url);

        // Reset button visibility
        if (search) resetBtn.classList.remove('d-none');
        else resetBtn.classList.add('d-none');

        // Bind pagination clicks
        const links = document.querySelectorAll('#table-pagination .pagination a');
        links.forEach(link => {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            if (href) {
              const urlObj = new URL(href, window.location.origin);
              const targetPage = urlObj.searchParams.get('page') || 1;
              fetchData(targetPage);
            }
          });
        });
      } catch (e) {
        if (e.name === 'AbortError') return;
        console.error('Gagal memuat data:', e);
      } finally {
        loading = false;
        loadingSpinner.classList.add('d-none');
      }
    }

    // --- Event Listeners ---

    // Filter dropdown changes
    filterPelatihan.addEventListener('change', applyFilters);
    filterStatus.addEventListener('change', applyFilters);

    // Filter reset button — reset semua filter & search
    filterResetBtn.addEventListener('click', function() {
      filterPelatihan.value = '';
      filterStatus.value = '';
      search = '';
      searchInput.value = '';

      const actionContainer = document.getElementById('action-buttons-container');
      if (actionContainer) actionContainer.classList.add('d-none');

      window.location.href = '{{ route('admin.enrollments.index') }}';
    });

    // Auto-search on input
    searchInput.addEventListener('input', function() {
      search = this.value;
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => fetchData(), 300);
    });

    // Enter key
    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        search = this.value;
        clearTimeout(debounceTimer);
        fetchData();
      }
    });

    // Search button click
    searchBtn.addEventListener('click', function() {
      search = searchInput.value;
      clearTimeout(debounceTimer);
      fetchData();
    });

    // Search reset button (X) — hanya reset search, filter tetap
    resetBtn.addEventListener('click', function(e) {
      e.preventDefault();
      search = '';
      searchInput.value = '';
      fetchData();
    });

    // Sinkronkan dropdown dari URL saat inisialisasi
    syncDropdownsFromUrl();
  })();

  // Reset Enrollment Forms — menggunakan event delegation agar tetap jalan setelah AJAX load
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

  // Konfirmasi ganti status dari dropdown tabel
  document.querySelectorAll('.change-status-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const status = this.querySelector('input[name="status"]').value;
      const labels = {'pending': 'Pending', 'approved': 'Approved', 'rejected': 'Rejected', 'waitlist': 'Waitlist'};
      const colors = {'pending': '#fbbf24', 'approved': '#34d399', 'rejected': '#f87171', 'waitlist': '#93c5fd'};

      Swal.fire({
        title: 'Ubah Status?',
        html: `<div>Ubah status peserta menjadi <strong style="color: ${colors[status]}">${labels[status]}</strong>?</div>
               <div class="mt-2" style="font-size: 0.8rem; color: rgba(255,255,255,0.5);">Konfirmasi untuk melanjutkan.</div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        background: '#0f172a',
        color: '#f8fafc',
        customClass: {
          popup: 'swal2-custom-popup shadow-lg',
          confirmButton: 'btn btn-primary px-4 py-2 border-0 fw-semibold',
          cancelButton: 'btn btn-secondary-custom px-4 py-2 border-0',
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });

  // Generate All Verification Codes form handler
  document.getElementById('form-generate-all-codes')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;

    Swal.fire({
      title: 'Generate Semua Kode?',
      html: `<div>Generate kode verifikasi untuk <strong style="color: #818cf8;">SEMUA</strong> enrollment <strong style="color: #34d399;">Approved</strong> yang belum punya kode?</div>
             <div class="mt-2" style="font-size: 0.8rem; color: rgba(255,255,255,0.5);">Peserta akan otomatis mendapat popup congratulations di dashboard-nya.</div>`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Generate!',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#6366f1',
      cancelButtonColor: '#6b7280',
      reverseButtons: true,
      background: '#0f172a',
      color: '#f8fafc',
      customClass: {
        popup: 'rounded-3 shadow-lg',
        title: 'fw-bold text-white',
        htmlContainer: 'text-body-premium',
        confirmButton: 'btn btn-primary px-4 py-2 border-0 me-2',
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
