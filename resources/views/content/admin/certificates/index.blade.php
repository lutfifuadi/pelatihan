@php $configData = Helper::appClasses(); @endphp

@extends('layouts/layoutMaster')

@section('title', 'Sertifikat')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');
  .content-wrapper { font-family: 'Outfit', sans-serif; color: #f8fafc; position: relative; overflow: hidden; }
  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container { background-color: #0b0f19 !important; background-image: radial-gradient(at 0% 0%, rgba(99,102,241,0.15) 0px, transparent 55%), radial-gradient(at 100% 0%, rgba(139,92,246,0.15) 0px, transparent 55%) !important; color: #f8fafc !important; }
  .glass-card-premium { background: rgba(15,23,42,0.25) !important; backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.08) !important; box-shadow: 0 20px 60px rgba(0,0,0,0.4); border-radius: 5px !important; z-index: 1; }
  .text-body-premium { color: rgba(255,255,255,0.65) !important; }
  .badge-premium { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.8); border-radius: 5px; padding: 4px 12px; font-weight: 500; font-size: 0.75rem; }
  .badge-premium-success { background: rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.3); color: #34d399; }
  .glow-orb { position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.4; mix-blend-mode: screen; pointer-events: none; z-index: 0; }
  .orb-1 { width: 400px; height: 400px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; }
  .orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; }
  .pagination .page-item .page-link { background: rgba(255,255,255,0.04) !important; border: 1px solid rgba(255,255,255,0.08) !important; color: rgba(255,255,255,0.7) !important; border-radius: 5px !important; margin: 0 2px; }
  .pagination .page-item.active .page-link { background: linear-gradient(135deg, #6366f1, #d946ef) !important; border-color: transparent !important; color: #fff !important; }
  select.form-select { background: rgba(255,255,255,0.04) !important; border: 1px solid rgba(255,255,255,0.08) !important; color: rgba(255,255,255,0.8) !important; border-radius: 5px !important; }
  select.form-select option { background: #0b0f19 !important; color: #f8fafc !important; }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="fw-bold text-white mb-1">Sertifikat</h4>
          <p class="text-body-premium mb-0" style="font-size: 0.95rem;">Kelola sertifikat kelulusan peserta pelatihan</p>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <i class="icon-base ti tabler-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- Filter --}}
    <div class="glass-card-premium px-4 py-3 mb-4">
      <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-6">
          <label class="text-body-premium small fw-semibold mb-1">Filter Pelatihan</label>
          <select name="pelatihan_id" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Pelatihan</option>
            @foreach($pelatihans as $p)
              <option value="{{ $p->id }}" {{ request('pelatihan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->batch }})</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary w-100" style="color: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.1); border-radius: 5px;">
            <i class="icon-base ti tabler-refresh me-1"></i> Reset
          </a>
        </div>
      </form>
    </div>

    {{-- Table --}}
    <div class="glass-card-premium px-4 py-4">
      <div class="table-responsive">
        <table class="table table-borderless text-white align-middle">
          <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.08);">
              <th class="text-body-premium small fw-semibold px-0" style="width: 40px;">No</th>
              <th class="text-body-premium small fw-semibold">Peserta</th>
              <th class="text-body-premium small fw-semibold">Pelatihan</th>
              <th class="text-body-premium small fw-semibold">No. Sertifikat</th>
              <th class="text-body-premium small fw-semibold">Tanggal Terbit</th>
              <th class="text-body-premium small fw-semibold text-end px-0">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($certificates as $index => $cert)
              <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                <td class="px-0 py-3 text-body-premium">{{ $certificates->firstItem() + $index }}</td>
                <td class="py-3">
                  <div class="fw-semibold text-white">{{ $cert->enrollment->user->name }}</div>
                  <div class="text-body-premium" style="font-size: 0.75rem;">{{ $cert->enrollment->user->email }}</div>
                </td>
                <td class="py-3 text-body-premium" style="font-size: 0.85rem;">{{ $cert->enrollment->pelatihan->nama }}</td>
                <td class="py-3">
                  <span class="badge-premium" style="font-size: 0.7rem;">{{ $cert->certificate_number }}</span>
                </td>
                <td class="py-3 text-body-premium" style="font-size: 0.85rem;">
                  {{ $cert->issued_at ? $cert->issued_at->format('d/m/Y') : '-' }}
                </td>
                <td class="text-end px-0 py-3">
                  <a href="{{ route('admin.certificates.show', $cert) }}" class="btn btn-info btn-sm" style="border-radius: 5px;" title="Detail">
                    <i class="icon-base ti tabler-eye"></i>
                  </a>
                  <a href="{{ route('admin.certificates.download', $cert) }}" class="btn btn-success btn-sm" style="border-radius: 5px;" title="Download PDF">
                    <i class="icon-base ti tabler-download"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-body-premium py-5">
                  <i class="icon-base ti tabler-certificate fs-1 mb-2 d-block text-warning"></i>
                  Belum ada sertifikat. Generate sertifikat dari halaman pelatihan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($certificates->hasPages())
        <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
          {{ $certificates->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
