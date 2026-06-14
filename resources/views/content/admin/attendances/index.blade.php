@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Absensi - ' . $pelatihan->nama)

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

  .badge-premium {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 4px 12px;
    font-weight: 500;
    font-size: 0.75rem;
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

  .status-btn-group .btn {
    border-radius: 5px !important;
    padding: 4px 10px !important;
    font-size: 0.7rem !important;
    font-weight: 600 !important;
    transition: all 0.2s ease;
  }
  .status-btn-group .btn:hover { transform: translateY(-1px); }
  .status-btn-group .btn.active {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  }
  .btn-status-hadir { border-color: rgba(16,185,129,0.3) !important; color: #34d399 !important; }
  .btn-status-hadir.active, .btn-status-hadir:hover { background: rgba(16,185,129,0.2) !important; border-color: #10b981 !important; }
  .btn-status-sakit { border-color: rgba(245,158,11,0.3) !important; color: #fbbf24 !important; }
  .btn-status-sakit.active, .btn-status-sakit:hover { background: rgba(245,158,11,0.2) !important; border-color: #f59e0b !important; }
  .btn-status-izin { border-color: rgba(96,165,250,0.3) !important; color: #93c5fd !important; }
  .btn-status-izin.active, .btn-status-izin:hover { background: rgba(96,165,250,0.2) !important; border-color: #60a5fa !important; }
  .btn-status-alpa { border-color: rgba(239,68,68,0.3) !important; color: #f87171 !important; }
  .btn-status-alpa.active, .btn-status-alpa:hover { background: rgba(239,68,68,0.2) !important; border-color: #ef4444 !important; }
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
          <a href="{{ route('admin.pelatihan.index') }}" class="text-body-premium text-decoration-none mb-2 d-inline-block" style="font-size: 0.85rem;">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali ke Pelatihan
          </a>
          <h4 class="fw-bold text-white mb-1">Absensi Peserta</h4>
          <p class="text-body-premium mb-0" style="font-size: 0.95rem;">
            {{ $pelatihan->nama }} (Batch: {{ $pelatihan->batch }})
            @if($pelatihan->dinas)
              — {{ $pelatihan->dinas->nama_dinas }}
            @endif
          </p>
        </div>
        <div class="d-flex gap-2">
          <span class="badge-premium"><i class="icon-base ti tabler-users me-1"></i>{{ $totalPeserta }} Peserta</span>
          <span class="badge-premium"><i class="icon-base ti tabler-calendar me-1"></i>Pertemuan ke-{{ $nextPertemuan }}</span>
          <a href="{{ route('admin.attendances.rapport', $pelatihan) }}" class="btn btn-outline-info btn-sm" style="border-radius: 5px; border-color: rgba(96,165,250,0.3); color: #93c5fd;">
            <i class="icon-base ti tabler-chart-bar me-1"></i> Rekapitulasi
          </a>
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

    {{-- Form Absensi --}}
    <form method="POST" action="{{ route('admin.attendances.store', $pelatihan) }}" x-data="attendanceForm()">
      @csrf

      {{-- Info Pertemuan --}}
      <div class="glass-card-premium px-4 py-3 mb-4">
        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="text-body-premium small fw-semibold mb-1">Pertemuan ke-</label>
            <input type="number" name="pertemuan_ke" class="form-control" value="{{ old('pertemuan_ke', $nextPertemuan) }}" min="1" required
              style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #f8fafc; border-radius: 5px;">
          </div>
          <div class="col-md-4">
            <label class="text-body-premium small fw-semibold mb-1">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required
              style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #f8fafc; border-radius: 5px;">
          </div>
          <div class="col-md-4 d-flex gap-2">
            <button type="button" class="btn btn-sm" style="background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); border-radius: 5px;" @click="setAll('hadir')">
              <i class="icon-base ti tabler-check me-1"></i> Semua Hadir
            </button>
            <button type="button" class="btn btn-sm" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); border-radius: 5px;" @click="setAll('alpa')">
              Semua Alpa
            </button>
          </div>
        </div>
      </div>

      {{-- Daftar Peserta --}}
      <div class="glass-card-premium px-4 py-4">
        <div class="table-responsive">
          <table class="table table-borderless text-white align-middle">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <th class="text-body-premium small fw-semibold px-0" style="width: 40px;">No</th>
                <th class="text-body-premium small fw-semibold">Nama Peserta</th>
                <th class="text-body-premium small fw-semibold">WhatsApp</th>
                <th class="text-body-premium small fw-semibold text-center" style="width: 320px;">Status Kehadiran</th>
              </tr>
            </thead>
            <tbody>
              @foreach($enrollments as $index => $enrollment)
                @php
                  $lastAttendance = $enrollment->attendances->where('pertemuan_ke', $nextPertemuan)->first();
                  $defaultStatus = $lastAttendance->status ?? 'hadir';
                @endphp
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                  <td class="px-0 py-2 text-body-premium">{{ $loop->iteration }}</td>
                  <td class="py-2">
                    <div class="fw-semibold text-white">{{ $enrollment->user->name }}</div>
                    <div class="text-body-premium" style="font-size: 0.75rem;">{{ $enrollment->user->email }}</div>
                    <input type="hidden" name="attendances[{{ $index }}][enrollment_id]" value="{{ $enrollment->id }}">
                  </td>
                  <td class="py-2 text-body-premium">{{ $enrollment->user->whatsapp ?? '-' }}</td>
                  <td class="py-2 text-center">
                    <div class="status-btn-group btn-group btn-group-sm" role="group">
                      @foreach(['hadir', 'sakit', 'izin', 'alpa'] as $status)
                        <button type="button"
                          class="btn btn-outline-secondary btn-status-{{ $status }}"
                          :class="{ 'active': selectedStatus['e{{ $enrollment->id }}'] === '{{ $status }}' }"
                          @click="selectedStatus['e{{ $enrollment->id }}'] = '{{ $status }}'"
                          data-enrollment="{{ $enrollment->id }}"
                          data-status="{{ $status }}">
                          {{ ucfirst($status) }}
                        </button>
                      @endforeach
                    </div>
                    <input type="hidden" name="attendances[{{ $index }}][status]"
                      x-model="selectedStatus['e{{ $enrollment->id }}']"
                      value="{{ old("attendances.{$index}.status", $defaultStatus) }}">
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if($enrollments->isEmpty())
          <div class="text-center text-body-premium py-4">
            <i class="icon-base ti tabler-inbox fs-1 mb-2 d-block"></i>
            Belum ada peserta yang di-approve untuk pelatihan ini.
          </div>
        @endif

        {{-- Submit --}}
        @if($enrollments->isNotEmpty())
        <div class="mt-4 pt-3 text-end" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
          <button type="submit" class="btn btn-lg px-5 py-2 fw-bold" style="background: linear-gradient(135deg, #6366f1, #d946ef); color: white; border: none; border-radius: 5px; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);">
            <i class="icon-base ti tabler-device-floppy me-2"></i> Simpan Absensi
          </button>
        </div>
        @endif
      </div>
    </form>
  </div>
@endsection

@section('page-script')
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceForm', () => ({
      selectedStatus: {},
      init() {
        // Inisialisasi dari nilai default / old input
        document.querySelectorAll('input[name^="attendances"][name$="[status]"]').forEach(el => {
          const name = el.getAttribute('name');
          const match = name.match(/attendances\[(\d+)\]\[status\]/);
          if (match) {
            // Cari enrollment_id dari hidden input sebelumnya
            const index = match[1];
            const enrollmentInput = document.querySelector(`input[name="attendances[${index}][enrollment_id]"]`);
            if (enrollmentInput) {
              const key = 'e' + enrollmentInput.value;
              this.selectedStatus[key] = el.value || 'hadir';
            }
          }
        });
      },
      setAll(status) {
        Object.keys(this.selectedStatus).forEach(key => {
          this.selectedStatus[key] = status;
        });
      }
    }));
  });
</script>
@endsection
