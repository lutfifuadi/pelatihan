@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Rekapitulasi Absensi - ' . $pelatihan->nama)

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

  .text-body-premium { color: rgba(255, 255, 255, 0.65) !important; }

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

  .attendance-cell {
    width: 60px;
    text-align: center;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 8px 4px !important;
  }
  .att-hadir { color: #34d399; }
  .att-sakit { color: #fbbf24; }
  .att-izin { color: #93c5fd; }
  .att-alpa { color: #f87171; }
  .att-null { color: rgba(255,255,255,0.15); }
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
          <a href="{{ route('admin.attendances.index', $pelatihan) }}" class="text-body-premium text-decoration-none mb-2 d-inline-block" style="font-size: 0.85rem;">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali ke Absensi
          </a>
          <h4 class="fw-bold text-white mb-1">Rekapitulasi Absensi</h4>
          <p class="text-body-premium mb-0" style="font-size: 0.95rem;">
            {{ $pelatihan->nama }} (Batch: {{ $pelatihan->batch }})
          </p>
        </div>
        <div>
          <span class="badge-premium" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.8); border-radius: 5px; padding: 4px 12px;">
            <i class="icon-base ti tabler-calendar me-1"></i> {{ $totalPertemuan }} Pertemuan
          </span>
        </div>
      </div>
    </div>

    {{-- Table --}}
    <div class="glass-card-premium px-4 py-4">
      <div class="table-responsive">
        <table class="table table-borderless text-white align-middle">
          <thead>
            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
              <th class="text-body-premium small fw-semibold px-0" style="width: 30px;">No</th>
              <th class="text-body-premium small fw-semibold">Nama Peserta</th>
              @foreach($pertemuans as $p)
                <th class="text-body-premium small fw-semibold attendance-cell">P{{ $p }}</th>
              @endforeach
              <th class="text-body-premium small fw-semibold text-center" style="width: 80px;">Hadir</th>
              <th class="text-body-premium small fw-semibold text-center" style="width: 80px;">%</th>
            </tr>
          </thead>
          <tbody>
            @forelse($enrollments as $index => $enrollment)
              @php
                $totalHadir = $enrollment->attendances->where('status', 'hadir')->count();
                $persen = $totalPertemuan > 0 ? round(($totalHadir / $totalPertemuan) * 100) : 0;
              @endphp
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                <td class="px-0 py-2 text-body-premium">{{ $loop->iteration }}</td>
                <td class="py-2">
                  <div class="fw-semibold text-white" style="font-size: 0.85rem;">{{ $enrollment->user->name }}</div>
                </td>
                @foreach($pertemuans as $p)
                  @php
                    $att = $enrollment->attendances->firstWhere('pertemuan_ke', $p);
                    $status = $att ? $att->status : null;
                  @endphp
                  <td class="attendance-cell att-{{ $status ?? 'null' }}">
                    @if($status)
                      {{ ucfirst($status) }}
                    @else
                      -
                    @endif
                  </td>
                @endforeach
                <td class="text-center py-2 fw-bold" style="color: #34d399;">{{ $totalHadir }}/{{ $totalPertemuan }}</td>
                <td class="text-center py-2">
                  @if($persen >= 80)
                    <span style="color: #34d399;">{{ $persen }}%</span>
                  @elseif($persen >= 50)
                    <span style="color: #fbbf24;">{{ $persen }}%</span>
                  @else
                    <span style="color: #f87171;">{{ $persen }}%</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="{{ 3 + count($pertemuans) }}" class="text-center text-body-premium py-4">
                  <i class="icon-base ti tabler-inbox fs-1 mb-2 d-block"></i>
                  Belum ada data absensi.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
@endsection
