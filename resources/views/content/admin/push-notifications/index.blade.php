@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Push Notifications')

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
          <h4 class="fw-bold text-white mb-0">Push Notifications</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Kelola dan kirim notifikasi push ke aplikasi peserta
          </p>
        </div>
        <a href="{{ route('admin.push-notifications.create') }}" class="btn btn-primary btn-action px-4 py-2">
          <i class="ti ti-plus me-1"></i> Buat Notifikasi Baru
        </a>
      </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <i class="ti ti-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- Table --}}
    <div class="glass-card-premium px-4 py-4">
      <div class="table-responsive">
        <table class="table table-borderless text-white align-middle">
          <thead>
            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
              <th class="text-body-premium small fw-semibold px-0">Judul</th>
              <th class="text-body-premium small fw-semibold">Target</th>
              <th class="text-body-premium small fw-semibold">Total Target</th>
              <th class="text-body-premium small fw-semibold">Status</th>
              <th class="text-body-premium small fw-semibold">Waktu</th>
              <th class="text-body-premium small fw-semibold text-end px-0" style="width: 140px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($notifications as $notification)
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                <td class="px-0">{{ $notification->title }}</td>
                <td>{{ $notification->target_type === 'all' ? 'Semua' : 'Filter' }}</td>
                <td>{{ $notification->total_target }}</td>
                <td>
                  @if($notification->sent_at)
                    <span class="badge-premium badge-premium-success">Sukses: {{ $notification->sent_count }} | Gagal: {{ $notification->failed_count }}</span>
                  @else
                    <span class="badge-premium badge-premium-warning">Pending</span>
                  @endif
                </td>
                <td>{{ $notification->sent_at?->format('d M Y H:i') ?? '-' }}</td>
                <td class="text-end px-0">
                  <div class="d-flex gap-2 justify-content-end">
                      <a href="{{ route('admin.push-notifications.show', $notification) }}" class="btn btn-outline-info btn-action" style="border-color: rgba(96,165,250,0.3); color: #93c5fd;">Detail</a>
                      <form action="{{ route('admin.push-notifications.destroy', $notification) }}" method="POST" class="delete-push-notification-form d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-action">Hapus</button>
                      </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div id="table-pagination">
        @if($notifications->hasPages())
          <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
            {{ $notifications->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('page-script')
<script>
  document.addEventListener('submit', function(e) {
    const form = e.target.closest('.delete-push-notification-form');
    if (!form) return;

    e.preventDefault();

    Swal.fire({
      title: 'Hapus Notifikasi?',
      text: 'Notifikasi ini akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#6b7280',
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
</script>
@endsection
