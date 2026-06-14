@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Detail Pendaftaran')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');

  .content-wrapper {
    font-family: 'Outfit', sans-serif;
    color: #f8fafc;
    position: relative !important;
    overflow: hidden !important;
  }

  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container {
    background-color: #0b0f19 !important;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%) !important;
    color: #f8fafc !important;
  }

  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border-radius: 5px !important;
    z-index: 1;
  }

  .text-body-premium {
    color: rgba(255, 255, 255, 0.65) !important;
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

  .info-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: rgba(255, 255, 255, 0.4);
    font-weight: 600;
  }
  .info-value {
    font-size: 0.95rem;
    color: #f8fafc;
    font-weight: 500;
  }

  .glow-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.4;
    mix-blend-mode: screen;
    pointer-events: none;
    z-index: 0;
  }
  .orb-1 { width: 400px; height: 400px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; }
  .orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    {{-- Header --}}
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <a href="{{ route('admin.enrollments.index') }}" class="text-body-premium text-decoration-none mb-2 d-inline-block" style="font-size: 0.85rem;">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
          </a>
          <h4 class="fw-bold text-white mb-1">Detail Pendaftaran</h4>
          <p class="text-body-premium mb-0" style="font-size: 0.95rem;">
            {{ $enrollment->user->name }} — {{ $enrollment->pelatihan->nama }}
          </p>
        </div>
        <div>
          @switch($enrollment->status)
            @case('pending') <span class="badge-premium badge-premium-warning">Pending</span> @break
            @case('approved') <span class="badge-premium badge-premium-success">Approved</span> @break
            @case('rejected') <span class="badge-premium badge-premium-danger">Ditolak</span> @break
            @case('waitlist') <span class="badge-premium badge-premium-info">Cadangan</span> @break
          @endswitch
        </div>
      </div>
    </div>

    {{-- Data Peserta --}}
    <div class="row g-4">
      <div class="col-md-6">
        <div class="glass-card-premium p-4">
          <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-user me-2"></i> Data Peserta
          </h5>
          <div class="row g-3">
            <div class="col-6">
              <div class="info-label">Nama Lengkap</div>
              <div class="info-value">{{ $enrollment->user->name }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">Email</div>
              <div class="info-value">{{ $enrollment->user->email }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">WhatsApp</div>
              <div class="info-value">{{ $enrollment->user->whatsapp ?? '-' }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">NIK</div>
              <div class="info-value">{{ $enrollment->user->nik ?? '-' }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">Role</div>
              <div class="info-value" style="text-transform: capitalize;">{{ $enrollment->user->role }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">Bergabung</div>
              <div class="info-value">{{ $enrollment->user->created_at->format('d/m/Y') }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="glass-card-premium p-4">
          <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-book-2 me-2"></i> Data Pelatihan
          </h5>
          <div class="row g-3">
            <div class="col-6">
              <div class="info-label">Nama Pelatihan</div>
              <div class="info-value">{{ $enrollment->pelatihan->nama }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">Batch</div>
              <div class="info-value">{{ $enrollment->pelatihan->batch }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">Tanggal Mulai</div>
              <div class="info-value">{{ $enrollment->pelatihan->tanggal_mulai ? \Carbon\Carbon::parse($enrollment->pelatihan->tanggal_mulai)->format('d/m/Y') : '-' }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">Tanggal Selesai</div>
              <div class="info-value">{{ $enrollment->pelatihan->tanggal_selesai ? \Carbon\Carbon::parse($enrollment->pelatihan->tanggal_selesai)->format('d/m/Y') : '-' }}</div>
            </div>
            <div class="col-6">
              <div class="info-label">Kuota</div>
              <div class="info-value">{{ $enrollment->pelatihan->kuota ?? '-' }} peserta</div>
            </div>
            <div class="col-6">
              <div class="info-label">Dinas</div>
              <div class="info-value">{{ $enrollment->pelatihan->dinas->nama_dinas ?? '-' }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Status Timeline --}}
      <div class="col-12">
        <div class="glass-card-premium p-4">
          <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-timeline me-2"></i> Timeline Pendaftaran
          </h5>
          <div class="row g-3">
            <div class="col-md-3">
              <div class="info-label">Tanggal Daftar</div>
              <div class="info-value">{{ $enrollment->created_at->format('d/m/Y H:i') }}</div>
            </div>
            @if($enrollment->approved_at)
            <div class="col-md-3">
              <div class="info-label">Tanggal Approve</div>
              <div class="info-value" style="color: #34d399;">{{ $enrollment->approved_at->format('d/m/Y H:i') }}</div>
            </div>
            @endif
            @if($enrollment->rejected_at)
            <div class="col-md-3">
              <div class="info-label">Tanggal Ditolak</div>
              <div class="info-value" style="color: #f87171;">{{ $enrollment->rejected_at->format('d/m/Y H:i') }}</div>
            </div>
            @endif
            @if($enrollment->waitlist_promoted_at)
            <div class="col-md-3">
              <div class="info-label">Dipromosikan dari Cadangan</div>
              <div class="info-value" style="color: #93c5fd;">{{ $enrollment->waitlist_promoted_at->format('d/m/Y H:i') }}</div>
            </div>
            @endif
            @if($enrollment->notes)
            <div class="col-12 mt-3">
              <div class="info-label">Catatan</div>
              <div class="info-value" style="background: rgba(255,255,255,0.04); padding: 12px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.06);">
                {{ $enrollment->notes }}
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    @if($enrollment->status === 'pending')
    <div class="glass-card-premium p-4 mt-4">
      <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">Aksi</h5>
      <div class="d-flex gap-2 flex-wrap">
        <form action="{{ route('admin.enrollments.approve', $enrollment) }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-success px-4 py-2" style="border-radius: 5px; font-weight: 600;">
            <i class="icon-base ti tabler-check me-1"></i> Approve
          </button>
        </form>
        <form action="{{ route('admin.enrollments.waitlist', $enrollment) }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-info px-4 py-2" style="border-radius: 5px; font-weight: 600;">
            <i class="icon-base ti tabler-clock me-1"></i> Masukkan Cadangan
          </button>
        </form>
        <button type="button" class="btn btn-danger px-4 py-2" style="border-radius: 5px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#rejectModal">
          <i class="icon-base ti tabler-x me-1"></i> Tolak
        </button>
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
    @endif

  </div>
@endsection
