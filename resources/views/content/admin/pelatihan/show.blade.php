@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Peserta Pelatihan - ' . $pelatihan->nama)

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

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

  .layout-navbar-fixed .layout-page::before {
    display: none !important;
  }

  .content-wrapper > .container-xxl {
    max-width: 100% !important;
    padding: 0 !important;
  }

  .layout-menu,
  #layout-menu {
    background-color: #0b0f19 !important;
    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
  }
  .layout-menu .app-brand {
    background-color: #0b0f19 !important;
  }
  .layout-menu .menu-inner {
    background-color: #0b0f19 !important;
  }
  .layout-menu .menu-link {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  .layout-menu .menu-item.active > .menu-link {
    color: #ffffff !important;
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3) !important;
  }
  .layout-menu .menu-item.active > .menu-link i {
    color: #ffffff !important;
  }
  .layout-menu .menu-header-text {
    color: rgba(255, 255, 255, 0.4) !important;
  }
  .layout-menu .menu-link:hover {
    background-color: rgba(255, 255, 255, 0.04) !important;
    color: #ffffff !important;
  }
  .layout-menu .menu-inner-shadow {
    background: linear-gradient(#0b0f19 5%, rgba(11, 15, 25, 0) 95%) !important;
  }
  .layout-menu .app-brand .app-brand-text {
    color: #ffffff !important;
  }

  .layout-navbar,
  #layout-navbar {
    background: rgba(15, 23, 42, 0.45) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
  }
  .navbar-detached {
    background: rgba(15, 23, 42, 0.45) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    margin-top: 12px !important;
  }
  #layout-navbar .nav-link {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  #layout-navbar .nav-link:hover {
    color: #ffffff !important;
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
  .orb-1 {
    width: 450px;
    height: 450px;
    background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
    top: -10%;
    left: -10%;
    animation-duration: 20s;
  }
  .orb-2 {
    width: 550px;
    height: 550px;
    background: radial-gradient(circle, #ec4899 0%, rgba(236, 72, 153, 0) 70%);
    bottom: 5%;
    right: -10%;
    animation-duration: 28s;
  }
  .orb-3 {
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, #06b6d4 0%, rgba(6, 182, 212, 0) 70%);
    top: 35%;
    left: 25%;
    animation-duration: 24s;
  }
  @keyframes orbFloat {
    0% { transform: translate(0, 0) scale(1) rotate(0deg); }
    50% { transform: translate(60px, 40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px, -50px) scale(0.92) rotate(360deg); }
  }

  .text-body-premium {
    color: rgba(255, 255, 255, 0.65) !important;
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

  .stat-icon-primary {
    background: rgba(99, 102, 241, 0.12);
    color: #6366f1;
  }
  .stat-icon-success {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
  }
  .stat-icon-info {
    background: rgba(6, 182, 212, 0.12);
    color: #06b6d4;
  }
  .stat-icon-warning {
    background: rgba(245, 158, 11, 0.12);
    color: #f59e0b;
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

  .btn-glow-premium {
    background: linear-gradient(135deg, #ffc107, #ff9800) !important;
    border: none;
    color: #0b0f19 !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
    transition: all 0.3s ease;
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.4);
    background: linear-gradient(135deg, #ffca28, #ffa726) !important;
    color: #0b0f19 !important;
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
  .pagination .page-item .page-link:hover:not(.disabled) {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
  }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-primary">
            <i class="icon-base ti tabler-users-group fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Peserta Pelatihan</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              {{ $pelatihan->nama }} — Batch {{ $pelatihan->batch }}
              @if($pelatihan->dinas)
                <span class="mx-2">|</span> {{ $pelatihan->dinas->nama_dinas }}
              @endif
            </p>
          </div>
        </div>
        <a href="{{ route('admin.pelatihan.index') }}" class="btn btn-outline-light btn-sm d-flex align-items-center gap-2" style="border-radius: 5px;">
          <i class="icon-base ti tabler-arrow-left"></i> Kembali
        </a>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-3 col-6">
        <div class="glass-card-premium px-3 py-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-info" style="width: 44px; height: 44px; font-size: 1.3rem;">
              <i class="icon-base ti tabler-users"></i>
            </div>
            <div>
              <div class="text-body-premium small">Total Peserta</div>
              <div class="text-white fw-bold fs-4">{{ $totalPeserta }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="glass-card-premium px-3 py-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-success" style="width: 44px; height: 44px; font-size: 1.3rem;">
              <i class="icon-base ti tabler-check"></i>
            </div>
            <div>
              <div class="text-body-premium small">Lengkap</div>
              <div class="text-white fw-bold fs-4">{{ $completedCount }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="glass-card-premium px-3 py-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-warning" style="width: 44px; height: 44px; font-size: 1.3rem;">
              <i class="icon-base ti tabler-clipboard-list"></i>
            </div>
            <div>
              <div class="text-body-premium small">Kuota</div>
              <div class="text-white fw-bold fs-4">{{ $pelatihan->kuota ?? '-' }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="glass-card-premium px-3 py-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-primary" style="width: 44px; height: 44px; font-size: 1.3rem;">
              <i class="icon-base ti tabler-calendar"></i>
            </div>
            <div>
              <div class="text-body-premium small">Pelaksanaan</div>
              <div class="text-white fw-semibold" style="font-size: 0.85rem;">
                {{ $pelatihan->tanggal_mulai ? \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d/m/Y') : '-' }}
                —
                {{ $pelatihan->tanggal_selesai ? \Carbon\Carbon::parse($pelatihan->tanggal_selesai)->format('d/m/Y') : '-' }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="glass-card-premium px-4 py-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
          <h5 class="fw-bold text-white mb-0">
            <i class="icon-base ti tabler-list me-2"></i> Daftar Peserta
          </h5>
          <div class="d-flex gap-2">
            <button onclick="copyAllWA()" class="btn btn-info btn-sm d-flex align-items-center gap-1" style="border-radius: 5px;">
              <i class="icon-base ti tabler-copy"></i> Salin Semua WA
            </button>
            <a href="#" class="btn btn-success btn-sm d-flex align-items-center gap-1" style="border-radius: 5px;">
              <i class="icon-base ti tabler-file-spreadsheet"></i> Export Excel
            </a>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-borderless text-white align-middle">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <th class="text-body-premium small fw-semibold px-0" style="width: 50px;">No</th>
                <th class="text-body-premium small fw-semibold">Nama</th>
                <th class="text-body-premium small fw-semibold">NIK</th>
                <th class="text-body-premium small fw-semibold">WhatsApp</th>
                <th class="text-body-premium small fw-semibold">Email</th>
                <th class="text-body-premium small fw-semibold">Kecamatan</th>
                <th class="text-body-premium small fw-semibold">Status</th>
                <th class="text-body-premium small fw-semibold text-end px-0" style="width: 80px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($peserta as $index => $p)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                  <td class="px-0 py-3 text-body-premium">{{ $peserta->firstItem() + $index }}</td>
                  <td class="py-3 fw-semibold text-white">{{ $p->nama_lengkap ?? $p->user?->name ?? '-' }}</td>
                  <td class="py-3 text-body-premium" style="font-size: 0.85rem;">{{ $p->nik ?? '-' }}</td>
                  <td class="py-3 text-body-premium">
                    @if($p->whatsapp)
                      <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->whatsapp) }}" target="_blank" class="text-info text-decoration-none">
                        <i class="icon-base ti tabler-brand-whatsapp me-1"></i>{{ $p->whatsapp }}
                      </a>
                    @else
                      <span class="text-body-premium">-</span>
                    @endif
                  </td>
                  <td class="py-3 text-body-premium" style="font-size: 0.85rem;">{{ $p->email ?? $p->user?->email ?? '-' }}</td>
                  <td class="py-3 text-body-premium" style="font-size: 0.85rem;">
                    {{ $p->kelurahan?->kecamatan?->name ?? $p->kecamatan ?? '-' }}
                  </td>
                  <td class="py-3">
                    @if($p->is_completed)
                      <span class="badge-premium badge-premium-success">Lengkap</span>
                    @else
                      <span class="badge-premium badge-premium-warning">Belum Lengkap</span>
                    @endif
                  </td>
                  <td class="text-end px-0 py-3">
                    @if($p->user)
                      <a href="{{ route('admin.peserta.show', $p->user) }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0;" title="Detail">
                        <i class="icon-base ti tabler-arrow-right fs-5"></i>
                      </a>
                    @else
                      <span class="text-body-premium small">-</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center text-body-premium py-5">
                    <i class="icon-base ti tabler-users-off fs-1 mb-2 d-block text-warning"></i>
                    Belum ada peserta terdaftar untuk pelatihan ini.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($peserta->hasPages())
          <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
            {{ $peserta->links() }}
          </div>
        @endif
      </div>
    </div>

  </div>
@endsection

@push('scripts')
<script>
function copyAllWA() {
    const waNumbers = @json($peserta->pluck('whatsapp')->filter()->implode(', '));
    navigator.clipboard.writeText(waNumbers).then(() => {
        alert('Semua nomor WA berhasil disalin!');
    }).catch(() => {
        alert('Gagal menyalin nomor WA.');
    });
}
</script>
@endpush