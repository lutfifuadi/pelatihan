@php $configData = Helper::appClasses(); @endphp

@extends('layouts/layoutMaster')

@section('title', 'Generate Sertifikat - ' . $pelatihan->nama)

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');
  .content-wrapper { font-family: 'Outfit', sans-serif; color: #f8fafc; position: relative; overflow: hidden; }
  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container { background-color: #0b0f19 !important; background-image: radial-gradient(at 0% 0%, rgba(99,102,241,0.15) 0px, transparent 55%), radial-gradient(at 100% 0%, rgba(139,92,246,0.15) 0px, transparent 55%) !important; color: #f8fafc !important; }
  .glass-card-premium { background: rgba(15,23,42,0.25) !important; backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.08) !important; box-shadow: 0 20px 60px rgba(0,0,0,0.4); border-radius: 5px !important; z-index: 1; }
  .text-body-premium { color: rgba(255,255,255,0.65) !important; }
  .badge-premium { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.8); border-radius: 5px; padding: 4px 12px; font-weight: 500; font-size: 0.75rem; }
  .glow-orb { position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.4; mix-blend-mode: screen; pointer-events: none; z-index: 0; }
  .orb-1 { width: 400px; height: 400px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; }
  .orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; }
  .btn-action { border-radius: 5px !important; font-weight: 600 !important; }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <a href="{{ route('admin.pelatihan.index') }}" class="text-body-premium text-decoration-none mb-2 d-inline-block" style="font-size: 0.85rem;">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
          </a>
          <h4 class="fw-bold text-white mb-1">Generate Sertifikat</h4>
          <p class="text-body-premium mb-0" style="font-size: 0.95rem;">
            {{ $pelatihan->nama }} (Batch: {{ $pelatihan->batch }})
            @if($pelatihan->dinas) — {{ $pelatihan->dinas->nama_dinas }} @endif
          </p>
        </div>
        <div class="d-flex gap-2">
          <span class="badge-premium"><i class="icon-base ti tabler-users me-1"></i> {{ $enrollments->count() }} belum bersertifikat</span>
          <span class="badge-premium" style="background: rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.3); color: #34d399;">
            <i class="icon-base ti tabler-certificate me-1"></i> {{ $certified }} sudah
          </span>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <i class="icon-base ti tabler-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- Batch Generate --}}
    @if($enrollments->isNotEmpty())
      <div class="glass-card-premium px-4 py-3 mb-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <strong class="text-white">{{ $enrollments->count() }} peserta</strong>
            <span class="text-body-premium ms-2" style="font-size: 0.85rem;">siap digenerate sertifikatnya</span>
          </div>
          <form action="{{ route('admin.certificates.batch', $pelatihan) }}" method="POST" onsubmit="return confirm('Generate {{ $enrollments->count() }} sertifikat sekaligus?')">
            @csrf
            <button type="submit" class="btn btn-lg fw-bold" style="background: linear-gradient(135deg, #ffd700, #ff9800); color: #0b0f19; border: none; border-radius: 5px; box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);">
              <i class="icon-base ti tabler-certificate me-2"></i> Generate Semua ({{ $enrollments->count() }})
            </button>
          </form>
        </div>
      </div>
    @endif

    {{-- Daftar Peserta --}}
    <div class="glass-card-premium px-4 py-4">
      @if($enrollments->isEmpty())
        <div class="text-center text-body-premium py-5">
          <i class="icon-base ti tabler-certificate fs-1 mb-2 d-block text-success"></i>
          Semua peserta sudah memiliki sertifikat ✓
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-borderless text-white align-middle">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                <th class="text-body-premium small fw-semibold px-0" style="width: 40px;">No</th>
                <th class="text-body-premium small fw-semibold">Nama Peserta</th>
                <th class="text-body-premium small fw-semibold">WhatsApp</th>
                <th class="text-body-premium small fw-semibold">Tanggal Daftar</th>
                <th class="text-body-premium small fw-semibold text-end px-0">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($enrollments as $index => $enrollment)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                  <td class="px-0 py-3 text-body-premium">{{ $loop->iteration }}</td>
                  <td class="py-3">
                    <div class="fw-semibold text-white">{{ $enrollment->user->name }}</div>
                    <div class="text-body-premium" style="font-size: 0.75rem;">{{ $enrollment->user->email }}</div>
                  </td>
                  <td class="py-3 text-body-premium">{{ $enrollment->user->whatsapp ?? '-' }}</td>
                  <td class="py-3 text-body-premium" style="font-size: 0.85rem;">{{ $enrollment->created_at->format('d/m/Y') }}</td>
                  <td class="text-end px-0 py-3">
                    <form action="{{ route('admin.certificates.store') }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">
                      <button type="submit" class="btn btn-warning btn-action" style="color: #0b0f19; background: linear-gradient(135deg, #ffd700, #ff9800); border: none;">
                        <i class="icon-base ti tabler-certificate me-1"></i> Generate
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
@endsection
