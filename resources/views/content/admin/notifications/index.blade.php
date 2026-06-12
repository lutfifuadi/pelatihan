@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Log Pengiriman Notifikasi')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  .content-wrapper {
    font-family: 'Outfit', sans-serif;
    color: #f8fafc;
    position: relative !important;
    overflow: hidden !important;
  }
  .content-wrapper h1,.content-wrapper h2,.content-wrapper h3,.content-wrapper h4,.content-wrapper h5,.content-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }

  html,body,.layout-page,.content-wrapper,.layout-wrapper,.layout-container {
    background-color: #0b0f19 !important;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }

  .layout-navbar-fixed .layout-page::before { display: none !important; }
  .content-wrapper > .container-xxl { max-width: 100% !important; padding: 0 !important; }

  .layout-menu,#layout-menu {
    background-color: #0b0f19 !important;
    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
  }
  .layout-menu .app-brand { background-color: #0b0f19 !important; }
  .layout-menu .menu-inner { background-color: #0b0f19 !important; }
  .layout-menu .menu-link { color: rgba(255, 255, 255, 0.7) !important; }
  .layout-menu .menu-item.active > .menu-link {
    color: #ffffff !important;
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3) !important;
  }
  .layout-menu .menu-item.active > .menu-link i { color: #ffffff !important; }
  .layout-menu .menu-header-text { color: rgba(255, 255, 255, 0.4) !important; }
  .layout-menu .menu-link:hover { background-color: rgba(255, 255, 255, 0.04) !important; color: #ffffff !important; }
  .layout-menu .menu-inner-shadow { background: linear-gradient(#0b0f19 5%, rgba(11, 15, 25, 0) 95%) !important; }
  .layout-menu .app-brand .app-brand-text { color: #ffffff !important; }

  .layout-navbar,#layout-navbar {
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
  #layout-navbar .nav-link { color: rgba(255, 255, 255, 0.7) !important; }
  #layout-navbar .nav-link:hover { color: #ffffff !important; }

  .glow-orb {
    position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.4;
    mix-blend-mode: screen; pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out; z-index: 0;
  }
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, rgba(99,102,241,0) 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, rgba(236,72,153,0) 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #06b6d4 0%, rgba(6,182,212,0) 70%); top: 35%; left: 25%; animation-duration: 24s; }
  @keyframes orbFloat {
    0% { transform: translate(0, 0) scale(1) rotate(0deg); }
    50% { transform: translate(60px, 40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px, -50px) scale(0.92) rotate(360deg); }
  }

  .text-body-premium { color: rgba(255, 255, 255, 0.65) !important; }

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
    width: 52px; height: 52px; border-radius: 5px !important;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; flex-shrink: 0;
  }
  .stat-icon-primary { background: rgba(99, 102, 241, 0.12); color: #6366f1; }
  .stat-icon-info { background: rgba(6, 182, 212, 0.12); color: #06b6d4; }
  .stat-icon-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
  .stat-icon-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
  .stat-icon-danger { background: rgba(248, 113, 113, 0.12); color: #f87171; }

  .badge-premium {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px; padding: 4px 12px; font-weight: 500; font-size: 0.75rem;
  }
  .badge-premium-success { background: rgba(16, 185, 129, 0.15); border-color: rgba(16,185,129,0.3); color: #34d399; }
  .badge-premium-warning { background: rgba(245, 158, 11, 0.15); border-color: rgba(245,158,11,0.3); color: #fbbf24; }
  .badge-premium-danger { background: rgba(239, 68, 68, 0.15); border-color: rgba(239,68,68,0.3); color: #f87171; }
  .badge-premium-info { background: rgba(6, 182, 212, 0.15); border-color: rgba(6,182,212,0.3); color: #22d3ee; }

  .btn-glow-premium {
    background: linear-gradient(135deg, #ffc107, #ff9800) !important;
    border: none; color: #0b0f19 !important;
    font-family: 'Sora', sans-serif; font-weight: 700; border-radius: 5px;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
    transition: all 0.3s ease;
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.4);
    background: linear-gradient(135deg, #ffca28, #ffa726) !important;
    color: #0b0f19 !important;
  }

  .btn-secondary-custom {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff; font-family: 'Sora', sans-serif; font-weight: 600; border-radius: 5px;
    transition: all 0.3s ease;
  }
  .btn-secondary-custom:hover { background: rgba(255, 255, 255, 0.1); color: #ffffff; }

  .form-control, .form-select {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important; border-radius: 5px !important;
    padding: 8px 12px !important; font-size: 13px !important;
    transition: all 0.3s ease !important;
  }
  .form-control:focus, .form-select:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }
  .form-select option { background-color: #0f172a !important; color: #ffffff !important; }

  .pagination .page-item .page-link {
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 13px !important; padding: 6px 12px !important;
    transition: all 0.3s ease !important; border-radius: 5px !important; margin: 0 2px !important;
  }
  .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border-color: transparent !important; color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
  }
  .pagination .page-item .page-link:hover:not(.disabled) { background: rgba(255, 255, 255, 0.08) !important; color: #ffffff !important; }

  .modal-content {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 5px !important;
  }
  .modal-header { border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important; }
  .modal-footer { border-top: 1px solid rgba(255, 255, 255, 0.08) !important; }
  .modal .btn-close { filter: invert(1) !important; }

  .filter-section { display: flex; flex-wrap: wrap; gap: 12px; align-items: end; }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box stat-icon-primary">
          <i class="icon-base ti tabler-bell fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0">Log Pengiriman Notifikasi</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Riwayat pengiriman notifikasi ke peserta, koordinator, dan instruktur
          </p>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
          <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-alert-circle fs-5 me-2"></i>
          <span>{{ session('error') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="glass-card-premium px-4 py-4 mb-4">
      <form method="GET" action="{{ route('admin.notifications.index') }}">
        <div class="filter-section">
          <div>
            <label class="form-label small mb-1">Channel</label>
            <select name="channel" class="form-select" style="min-width: 130px;">
              <option value="">All Channels</option>
              <option value="whatsapp" @selected(request('channel') == 'whatsapp')>WhatsApp</option>
              <option value="email" @selected(request('channel') == 'email')>Email</option>
              <option value="in_app" @selected(request('channel') == 'in_app')>In-App</option>
            </select>
          </div>
          <div>
            <label class="form-label small mb-1">Status</label>
            <select name="status" class="form-select" style="min-width: 130px;">
              <option value="">All Status</option>
              <option value="pending" @selected(request('status') == 'pending')>Pending</option>
              <option value="sent" @selected(request('status') == 'sent')>Sent</option>
              <option value="failed" @selected(request('status') == 'failed')>Failed</option>
              <option value="read" @selected(request('status') == 'read')>Read</option>
            </select>
          </div>
          <div>
            <label class="form-label small mb-1">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="min-width: 140px;">
          </div>
          <div>
            <label class="form-label small mb-1">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" style="min-width: 140px;">
          </div>
          <div>
            <label class="form-label small mb-1">Cari User</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama user..." style="min-width: 160px;">
          </div>
          <div>
            <button type="submit" class="btn btn-glow-premium px-3 py-2" style="font-size: 13px;">
              <i class="icon-base ti tabler-filter me-1"></i> Filter
            </button>
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary-custom px-3 py-2" style="font-size: 13px;">
              <i class="icon-base ti tabler-refresh me-1"></i> Reset
            </a>
          </div>
        </div>
      </form>
    </div>

    <div class="col-12">
      <div class="glass-card-premium px-4 py-4">
        <div class="table-responsive">
          <table class="table table-borderless text-white align-middle">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <th class="text-body-premium small fw-semibold px-0" style="width: 60px;">ID</th>
                <th class="text-body-premium small fw-semibold">User</th>
                <th class="text-body-premium small fw-semibold">Channel</th>
                <th class="text-body-premium small fw-semibold">Template</th>
                <th class="text-body-premium small fw-semibold">Status</th>
                <th class="text-body-premium small fw-semibold">Waktu Kirim</th>
                <th class="text-body-premium small fw-semibold text-end px-0" style="width: 120px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($notifications as $notification)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                  <td class="px-0 py-3 text-body-premium">#{{ $notification->id }}</td>
                  <td class="py-3">
                    <div class="fw-semibold text-white">{{ $notification->user->name ?? 'System' }}</div>
                    <small class="text-body-premium">{{ $notification->recipient }}</small>
                  </td>
                  <td class="py-3">
                    @if($notification->channel == 'whatsapp')
                      <span class="badge-premium" style="background: rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.3); color: #34d399;">
                        <i class="icon-base ti tabler-brand-whatsapp me-1"></i>WA
                      </span>
                    @elseif($notification->channel == 'email')
                      <span class="badge-premium" style="background: rgba(6,182,212,0.15); border-color: rgba(6,182,212,0.3); color: #22d3ee;">
                        <i class="icon-base ti tabler-mail me-1"></i>Email
                      </span>
                    @else
                      <span class="badge-premium" style="background: rgba(99,102,241,0.15); border-color: rgba(99,102,241,0.3); color: #818cf8;">
                        <i class="icon-base ti tabler-bell me-1"></i>In-App
                      </span>
                    @endif
                  </td>
                  <td class="py-3 text-body-premium">
                    {{ $notification->template->name ?? 'Custom' }}
                  </td>
                  <td class="py-3">
                    @php
                      $statusColors = [
                        'sent' => 'badge-premium-success',
                        'pending' => 'badge-premium-warning',
                        'failed' => 'badge-premium-danger',
                        'read' => 'badge-premium-info',
                      ];
                      $statusLabels = [
                        'sent' => 'Terkirim',
                        'pending' => 'Menunggu',
                        'failed' => 'Gagal',
                        'read' => 'Dibaca',
                      ];
                    @endphp
                    <span class="badge-premium {{ $statusColors[$notification->status] ?? 'badge-premium-warning' }}">
                      {{ $statusLabels[$notification->status] ?? ucfirst($notification->status) }}
                    </span>
                  </td>
                  <td class="py-3 text-body-premium">
                    @if($notification->sent_at)
                      {{ $notification->sent_at->format('d/m/Y H:i') }}
                    @else
                      -
                    @endif
                  </td>
                  <td class="text-end px-0 py-3">
                    <div class="d-inline-flex gap-2">
                      <button type="button" class="btn btn-info btn-sm d-flex align-items-center justify-content-center"
                        style="border-radius: 5px; width: 32px; height: 32px; padding: 0;"
                        onclick="showDetail({{ $notification->id }})" title="Detail">
                        <i class="icon-base ti tabler-eye fs-5"></i>
                      </button>
                      @if($notification->status == 'failed')
                        <form action="{{ route('admin.notifications.resend', $notification) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center"
                            style="border-radius: 5px; width: 32px; height: 32px; padding: 0;" title="Kirim Ulang">
                            <i class="icon-base ti tabler-refresh fs-5 text-dark"></i>
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-body-premium py-5">
                    <i class="icon-base ti tabler-bell-off fs-1 mb-2 d-block text-warning"></i>
                    Belum ada data notifikasi.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if($notifications->hasPages())
          <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
            {{ $notifications->links() }}
          </div>
        @endif
      </div>
    </div>

  </div>

  <!-- Detail Modal -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="fw-bold text-white mb-0">Detail Notifikasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="detailBody">
          <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
<script>
function showDetail(id) {
  const modal = new bootstrap.Modal(document.getElementById('detailModal'));
  const body = document.getElementById('detailBody');
  body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
  modal.show();

  fetch('/admin/notifications/' + id)
    .then(res => res.json())
    .then(data => {
      const statusLabels = { sent: 'Terkirim', pending: 'Menunggu', failed: 'Gagal', read: 'Dibaca' };
      const channelLabels = { whatsapp: 'WhatsApp', email: 'Email', in_app: 'In-App' };
      body.innerHTML = `
        <div class="mb-3">
          <small class="text-body-premium">ID Notifikasi</small>
          <p class="text-white fw-semibold mb-0">#${data.id}</p>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <small class="text-body-premium">User</small>
            <p class="text-white fw-semibold mb-0">${data.user ? data.user.name : 'System'}</p>
          </div>
          <div class="col-md-6">
            <small class="text-body-premium">Penerima</small>
            <p class="text-white fw-semibold mb-0">${data.recipient}</p>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <small class="text-body-premium">Channel</small>
            <p class="text-white fw-semibold mb-0">${channelLabels[data.channel] || data.channel}</p>
          </div>
          <div class="col-md-4">
            <small class="text-body-premium">Status</small>
            <p class="text-white fw-semibold mb-0">${statusLabels[data.status] || data.status}</p>
          </div>
          <div class="col-md-4">
            <small class="text-body-premium">Template</small>
            <p class="text-white fw-semibold mb-0">${data.template ? data.template.name : 'Custom'}</p>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <small class="text-body-premium">Waktu Kirim</small>
            <p class="text-white fw-semibold mb-0">${data.sent_at ? new Date(data.sent_at).toLocaleString('id-ID') : '-'}</p>
          </div>
          <div class="col-md-6">
            <small class="text-body-premium">Dibaca</small>
            <p class="text-white fw-semibold mb-0">${data.read_at ? new Date(data.read_at).toLocaleString('id-ID') : '-'}</p>
          </div>
        </div>
        ${data.title ? `
        <div class="mb-3">
          <small class="text-body-premium">Judul</small>
          <p class="text-white fw-semibold mb-0">${data.title}</p>
        </div>` : ''}
        <div class="mb-3">
          <small class="text-body-premium">Isi Pesan</small>
          <div class="p-3 mt-1" style="background: rgba(255,255,255,0.03); border-radius: 5px; border: 1px solid rgba(255,255,255,0.08);">
            <p class="text-white mb-0" style="white-space: pre-wrap;">${data.body}</p>
          </div>
        </div>
        ${data.failed_reason ? `
        <div class="mb-3">
          <small class="text-danger">Alasan Gagal</small>
          <p class="text-danger fw-semibold mb-0">${data.failed_reason}</p>
        </div>` : ''}
      `;
    })
    .catch(err => {
      body.innerHTML = '<div class="text-center py-4 text-danger">Gagal memuat detail notifikasi.</div>';
    });
}
</script>
@endsection
