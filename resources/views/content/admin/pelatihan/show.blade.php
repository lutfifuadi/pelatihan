@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Peserta Pelatihan - ' . $pelatihan->nama)

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap');

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

  html,
  body,
  .layout-page,
  .content-wrapper,
  .layout-wrapper,
  .layout-container {
    background-color: #0b0f19 !important;
    background-image: 
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }

  .glass-card-premium {
    background: rgba(15, 23, 42, 0.55) !important;
    backdrop-filter: blur(16px) !important;
    -webkit-backdrop-filter: blur(16px) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 5px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
  }

  .stat-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 5px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .stat-icon-primary {
    background: rgba(99, 102, 241, 0.18);
    color: #818cf8;
    border: 1px solid rgba(99, 102, 241, 0.35);
  }
  .stat-icon-success {
    background: rgba(16, 185, 129, 0.18);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.35);
  }
  .stat-icon-warning {
    background: rgba(245, 158, 11, 0.18);
    color: #fbbf24;
    border: 1px solid rgba(245, 158, 11, 0.35);
  }
  .stat-icon-info {
    background: rgba(56, 189, 248, 0.18);
    color: #38bdf8;
    border: 1px solid rgba(56, 189, 248, 0.35);
  }

  .badge-premium {
    border-radius: 5px !important;
    padding: 4px 10px;
    font-weight: 600;
    font-size: 0.75rem;
    border: 1px solid transparent;
  }
  .badge-premium-success {
    background: rgba(16, 185, 129, 0.18);
    border-color: rgba(16, 185, 129, 0.35);
    color: #34d399;
  }
  .badge-premium-warning {
    background: rgba(245, 158, 11, 0.18);
    border-color: rgba(245, 158, 11, 0.35);
    color: #fbbf24;
  }
  .badge-premium-danger {
    background: rgba(239, 68, 68, 0.18);
    border-color: rgba(239, 68, 68, 0.35);
    color: #f87171;
  }
  .badge-premium-info {
    background: rgba(56, 189, 248, 0.18);
    border-color: rgba(56, 189, 248, 0.35);
    color: #38bdf8;
  }

  .table-custom tbody tr {
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: background 0.2s ease;
  }
  .table-custom tbody tr:hover {
    background: rgba(255, 255, 255, 0.03);
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
  .pagination .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.02) !important;
    border-color: rgba(255, 255, 255, 0.04) !important;
    color: rgba(255, 255, 255, 0.3) !important;
  }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 px-lg-5 py-3">

  {{-- Top Hero Header --}}
  <div class="glass-card-premium p-4 p-xl-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box stat-icon-primary" style="width: 48px; height: 48px; font-size: 1.5rem;">
          <i class="icon-base ti tabler-users-group"></i>
        </div>
        <div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
            <h4 class="fw-bold text-white mb-0">{{ $pelatihan->nama }}</h4>
            <span class="badge" style="background: rgba(99, 102, 241, 0.2); border: 1px solid rgba(99, 102, 241, 0.4); color: #c7d2fe; border-radius: 5px !important; padding: 4px 10px; font-size: 0.78rem;">
              Batch: {{ $pelatihan->batch }}
            </span>
          </div>
          <p class="text-body-premium mb-0" style="font-size: 0.88rem;">
            Penyelenggara: <strong class="text-white">{{ $pelatihan->dinas->nama_dinas ?? 'Dinas Terkait' }}</strong>
            <span class="mx-2 text-white-50">•</span>
            Pelaksanaan: 
            <span class="text-info fw-semibold">
              {{ $pelatihan->tanggal_mulai ? \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d M Y') : '-' }} 
              s/d 
              {{ $pelatihan->tanggal_selesai ? \Carbon\Carbon::parse($pelatihan->tanggal_selesai)->format('d M Y') : '-' }}
            </span>
          </p>
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('admin.pelatihan.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1.5" style="border-radius: 5px !important; border-color: rgba(255,255,255,0.15); color: #cbd5e1;">
          <i class="icon-base ti tabler-arrow-left"></i> <span>Kembali</span>
        </a>
        <a href="{{ route('admin.presensi.show', $pelatihan->id) }}" class="btn btn-info btn-sm d-flex align-items-center gap-1.5 text-white" style="border-radius: 5px !important; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border: 1px solid rgba(56, 189, 248, 0.4);">
          <i class="icon-base ti tabler-qrcode"></i> <span>Presensi / Scanner</span>
        </a>
        <button type="button" onclick="copyAllWaNumbers()" class="btn btn-success btn-sm d-flex align-items-center gap-1.5" style="border-radius: 5px !important; background: linear-gradient(135deg, #059669 0%, #047857 100%); border: 1px solid rgba(52, 211, 153, 0.4);">
          <i class="icon-base ti tabler-brand-whatsapp"></i> <span>Salin Semua WA</span>
        </button>
      </div>
    </div>
  </div>

  {{-- Summary Stat Cards --}}
  <div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="glass-card-premium p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-info">
            <i class="icon-base ti tabler-users fs-4"></i>
          </div>
          <div>
            <span class="text-body-premium small d-block">Total Pendaftar</span>
            <h4 class="text-white fw-bold mb-0">{{ $totalPeserta }}</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="glass-card-premium p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-success">
            <i class="icon-base ti tabler-circle-check fs-4"></i>
          </div>
          <div>
            <span class="text-body-premium small d-block">Terkonfirmasi (Resmi)</span>
            <h4 class="text-white fw-bold mb-0">{{ $confirmedCount }}</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="glass-card-premium p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-warning">
            <i class="icon-base ti tabler-clock fs-4"></i>
          </div>
          <div>
            <span class="text-body-premium small d-block">Menunggu Verifikasi</span>
            <h4 class="text-white fw-bold mb-0">{{ $pendingCount }}</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="glass-card-premium p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-primary">
            <i class="icon-base ti tabler-armchair fs-4"></i>
          </div>
          <div>
            <span class="text-body-premium small d-block">Target Kuota</span>
            <h4 class="text-white fw-bold mb-0">{{ $pelatihan->kuota ?? 40 }} <small class="fs-6 fw-normal text-white-50">Kursi</small></h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Participant Table Card --}}
  <div class="glass-card-premium p-4 p-xl-4 mb-4">
    {{-- Header Table & Filters --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3.5">
      <div>
        <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-list-details text-primary"></i>
          Daftar Peserta Pelatihan ({{ $enrollments->total() }})
        </h5>
      </div>

      {{-- Search & Filter Form --}}
      <form method="GET" action="{{ route('admin.pelatihan.peserta', $pelatihan->id) }}" class="d-flex align-items-center gap-2 flex-wrap">
        <div class="input-group input-group-sm" style="width: 240px;">
          <span class="input-group-text" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #94a3b8;">
            <i class="icon-base ti tabler-search"></i>
          </span>
          <input type="text" name="search" class="form-control" placeholder="Cari nama, NIK, WA..." value="{{ $search }}" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff;">
        </div>

        <select name="status" class="form-select form-select-sm" style="width: 160px; background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff;" onchange="this.form.submit()">
          <option value="all" {{ empty($statusFilter) || $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
          <option value="confirmed" {{ $statusFilter === 'confirmed' ? 'selected' : '' }}>Terkonfirmasi ({{ $confirmedCount }})</option>
          <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending ({{ $pendingCount }})</option>
          <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Ditolak ({{ $rejectedCount }})</option>
        </select>

        @if($search || ($statusFilter && $statusFilter !== 'all'))
          <a href="{{ route('admin.pelatihan.peserta', $pelatihan->id) }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 5px !important;" title="Reset Filter">
            <i class="icon-base ti tabler-rotate"></i>
          </a>
        @endif
      </form>
    </div>

    {{-- Table Responsive --}}
    <div class="table-responsive">
      <table class="table table-custom text-white align-middle mb-0">
        <thead>
          <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08); background: rgba(255, 255, 255, 0.02);">
            <th class="text-body-premium small fw-semibold py-3 px-3" style="width: 50px;">No</th>
            <th class="text-body-premium small fw-semibold py-3">Nama Peserta</th>
            <th class="text-body-premium small fw-semibold py-3">WhatsApp &amp; Kontak</th>
            <th class="text-body-premium small fw-semibold py-3">Domisili (Wilayah)</th>
            <th class="text-body-premium small fw-semibold py-3">Profil Usaha / Produk</th>
            <th class="text-body-premium small fw-semibold py-3">Status</th>
            <th class="text-body-premium small fw-semibold py-3">Tgl Daftar</th>
            <th class="text-body-premium small fw-semibold py-3 text-end px-3" style="width: 100px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($enrollments as $index => $enr)
            @php
              $user = $enr->user;
              $profile = $user?->pesertaProfile;
              $answers = is_array($profile?->jawaban_pertanyaan) ? $profile->jawaban_pertanyaan : (json_decode($profile?->jawaban_pertanyaan ?? '[]', true) ?: []);
              $waClean = preg_replace('/[^0-9]/', '', $user?->whatsapp ?: ($user?->phone ?: $profile?->no_wa));
            @endphp
            <tr>
              <td class="py-3 px-3 text-body-premium small">
                {{ $enrollments->firstItem() + $index }}
              </td>
              <td class="py-3">
                <div class="d-flex flex-column">
                  <span class="fw-bold text-white" style="font-size: 0.92rem; letter-spacing: 0.01em;">
                    {{ $profile?->nama_lengkap ?: ($user?->name ?? 'PESERTA') }}
                  </span>
                  <small class="text-body-premium" style="font-family: 'Fira Code', monospace; font-size: 0.75rem;">
                    NIK: {{ $user?->nik ?: ($profile?->nik ?? '-') }}
                  </small>
                </div>
              </td>
              <td class="py-3">
                <div class="d-flex flex-column gap-1">
                  @if($waClean)
                    <a href="https://wa.me/{{ $waClean }}" target="_blank" class="d-inline-flex align-items-center gap-1.5 text-success fw-semibold text-decoration-none" style="font-size: 0.82rem;">
                      <i class="icon-base ti tabler-brand-whatsapp"></i>
                      <span>{{ $user?->whatsapp ?: ($user?->phone ?: $profile?->no_wa) }}</span>
                    </a>
                  @else
                    <span class="text-body-premium small">-</span>
                  @endif
                  <small class="text-body-premium" style="font-size: 0.72rem;">
                    {{ $user?->email ?: '-' }}
                  </small>
                </div>
              </td>
              <td class="py-3">
                <div class="d-flex flex-column">
                  <span class="text-white small fw-semibold">
                    {{ $profile?->kelurahan?->name ?? ($profile?->alamat_ktp ?: 'Kec. Regol') }}
                  </span>
                  <small class="text-body-premium" style="font-size: 0.72rem;">
                    RT {{ $profile?->rt ?? '01' }} / RW {{ $profile?->rw ?? '01' }} • {{ $profile?->kelurahan?->kecamatan?->name ?? 'Regol' }}
                  </small>
                </div>
              </td>
              <td class="py-3">
                <div class="d-flex flex-column" style="max-width: 200px;">
                  @if(!empty($answers['nama_usaha']))
                    <span class="text-warning small fw-semibold text-truncate" title="{{ $answers['nama_usaha'] }}">
                      <i class="icon-base ti tabler-building-store me-1"></i>{{ $answers['nama_usaha'] }}
                    </span>
                    <small class="text-body-premium text-truncate" style="font-size: 0.72rem;" title="{{ $answers['nama_produk'] ?? '-' }}">
                      {{ $answers['nama_produk'] ?? 'Produk UMKM' }}
                    </small>
                  @else
                    <span class="text-body-premium small">-</span>
                  @endif
                </div>
              </td>
              <td class="py-3">
                @switch($enr->status?->value ?? $enr->status)
                  @case('confirmed')
                    <span class="badge-premium badge-premium-success d-inline-flex align-items-center gap-1">
                      <i class="icon-base ti tabler-circle-check"></i> Terkonfirmasi
                    </span>
                    @break
                  @case('pending')
                    <span class="badge-premium badge-premium-warning d-inline-flex align-items-center gap-1">
                      <i class="icon-base ti tabler-clock"></i> Pending
                    </span>
                    @break
                  @case('rejected')
                    <span class="badge-premium badge-premium-danger d-inline-flex align-items-center gap-1">
                      <i class="icon-base ti tabler-circle-x"></i> Ditolak
                    </span>
                    @break
                  @case('approved')
                    <span class="badge-premium badge-premium-success d-inline-flex align-items-center gap-1">
                      <i class="icon-base ti tabler-check"></i> Disetujui
                    </span>
                    @break
                  @default
                    <span class="badge-premium">{{ $enr->statusLabel() }}</span>
                @endswitch
              </td>
              <td class="py-3 text-body-premium small" style="font-size: 0.78rem;">
                {{ $enr->created_at ? $enr->created_at->format('d/m/Y H:i') : '-' }} <small class="text-white-50">WIB</small>
              </td>
              <td class="py-3 px-3 text-end">
                @if($user)
                  <a href="{{ route('admin.peserta.show', $user->id) }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 5px !important; padding: 0;" title="Lihat Profil Peserta">
                    <i class="icon-base ti tabler-arrow-right fs-5"></i>
                  </a>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-body-premium py-5">
                <div class="py-4">
                  <i class="icon-base ti tabler-users-off fs-1 text-warning d-block mb-2"></i>
                  <span class="fw-semibold text-white d-block">Tidak ada data peserta yang sesuai filter.</span>
                  <small class="text-body-premium">Coba ubah kata kunci pencarian atau status filter.</small>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($enrollments->hasPages())
      <div class="mt-4 pt-3 d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
        <small class="text-body-premium">
          Menampilkan {{ $enrollments->firstItem() }} - {{ $enrollments->lastItem() }} dari total {{ $enrollments->total() }} peserta
        </small>
        <div>
          {{ $enrollments->links() }}
        </div>
      </div>
    @endif
  </div>

</div>
@endsection

@push('scripts')
<script>
function copyAllWaNumbers() {
    const waList = @json($allWaNumbers);
    if (!waList || waList.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Info', 'Tidak ada nomor WhatsApp yang dapat disalin.', 'info');
        } else {
            alert('Tidak ada nomor WhatsApp yang dapat disalin.');
        }
        return;
    }

    navigator.clipboard.writeText(waList).then(() => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Nomor WA Berhasil Disalin! 📋',
                text: 'Daftar nomor WhatsApp seluruh peserta telah disalin ke clipboard.',
                confirmButtonText: 'Bagus',
                customClass: { confirmButton: 'btn btn-success' }
            });
        } else {
            alert('Semua nomor WA berhasil disalin ke clipboard!');
        }
    }).catch(err => {
        console.error('Gagal menyalin:', err);
        alert('Gagal menyalin nomor WhatsApp.');
    });
}
</script>
@endpush