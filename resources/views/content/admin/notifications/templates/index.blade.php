@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Template Notifikasi')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  .content-wrapper { font-family: 'Outfit', sans-serif; color: #f8fafc; position: relative !important; overflow: hidden !important; }
  .content-wrapper h1,.content-wrapper h2,.content-wrapper h3,.content-wrapper h4,.content-wrapper h5,.content-wrapper h6 { font-family: 'Sora', sans-serif; }

  html,body,.layout-page,.content-wrapper,.layout-wrapper,.layout-container {
    background-color: #0b0f19 !important;
    background-image: radial-gradient(at 0% 0%, rgba(99,102,241,0.15) 0px, transparent 55%), radial-gradient(at 100% 0%, rgba(139,92,246,0.15) 0px, transparent 55%), radial-gradient(at 50% 50%, rgba(236,72,153,0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }
  .layout-navbar-fixed .layout-page::before { display: none !important; }
  .content-wrapper > .container-xxl { max-width: 100% !important; padding: 0 !important; }

  .layout-menu,#layout-menu { background-color: #0b0f19 !important; border-right: 1px solid rgba(255,255,255,0.08) !important; }
  .layout-menu .app-brand { background-color: #0b0f19 !important; }
  .layout-menu .menu-inner { background-color: #0b0f19 !important; }
  .layout-menu .menu-link { color: rgba(255,255,255,0.7) !important; }
  .layout-menu .menu-item.active > .menu-link { color:#fff!important; background: linear-gradient(135deg,#6366f1,#d946ef)!important; box-shadow: 0 4px 15px rgba(99,102,241,0.3)!important; }
  .layout-menu .menu-item.active > .menu-link i { color:#fff!important; }
  .layout-menu .menu-header-text { color: rgba(255,255,255,0.4)!important; }
  .layout-menu .menu-link:hover { background-color: rgba(255,255,255,0.04)!important; color:#fff!important; }

  .layout-navbar,#layout-navbar { background: rgba(15,23,42,0.45)!important; backdrop-filter: blur(20px)!important; -webkit-backdrop-filter: blur(20px)!important; border:1px solid rgba(255,255,255,0.08)!important; box-shadow: 0 10px 30px rgba(0,0,0,0.2)!important; }
  .navbar-detached { background: rgba(15,23,42,0.45)!important; border:1px solid rgba(255,255,255,0.08)!important; margin-top:12px!important; }
  #layout-navbar .nav-link { color: rgba(255,255,255,0.7)!important; }
  #layout-navbar .nav-link:hover { color:#fff!important; }

  .glow-orb { position:absolute; border-radius:50%; filter:blur(120px); opacity:0.4; mix-blend-mode:screen; pointer-events:none; animation:orbFloat 25s infinite alternate ease-in-out; z-index:0; }
  .orb-1 { width:450px; height:450px; background:radial-gradient(circle,#6366f1 0%,rgba(99,102,241,0) 70%); top:-10%; left:-10%; animation-duration:20s; }
  .orb-2 { width:550px; height:550px; background:radial-gradient(circle,#ec4899 0%,rgba(236,72,153,0) 70%); bottom:5%; right:-10%; animation-duration:28s; }
  .orb-3 { width:350px; height:350px; background:radial-gradient(circle,#06b6d4 0%,rgba(6,182,212,0) 70%); top:35%; left:25%; animation-duration:24s; }
  @keyframes orbFloat { 0%{transform:translate(0,0) scale(1) rotate(0deg)} 50%{transform:translate(60px,40px) scale(1.08) rotate(180deg)} 100%{transform:translate(-30px,-50px) scale(0.92) rotate(360deg)} }

  .text-body-premium { color: rgba(255,255,255,0.65)!important; }
  .glass-card-premium { background: rgba(15,23,42,0.25)!important; backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.08)!important; box-shadow:0 20px 60px rgba(0,0,0,0.4); border-radius:5px!important; position:relative; transition:all 0.4s cubic-bezier(0.4,0,0.2,1); z-index:1; }
  .glass-card-premium:hover { transform:translateY(-2px)!important; border-color:rgba(99,102,241,0.2)!important; }

  .stat-icon-box { width:52px; height:52px; border-radius:5px!important; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0; }
  .stat-icon-primary { background: rgba(99,102,241,0.12); color:#6366f1; }
  .stat-icon-success { background: rgba(16,185,129,0.12); color:#10b981; }
  .stat-icon-warning { background: rgba(245,158,11,0.12); color:#f59e0b; }

  .badge-premium { background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); color:rgba(255,255,255,0.8); border-radius:5px; padding:4px 12px; font-weight:500; font-size:0.75rem; }
  .badge-premium-success { background:rgba(16,185,129,0.15); border-color:rgba(16,185,129,0.3); color:#34d399; }
  .badge-premium-warning { background:rgba(245,158,11,0.15); border-color:rgba(245,158,11,0.3); color:#fbbf24; }
  .badge-premium-info { background:rgba(6,182,212,0.15); border-color:rgba(6,182,212,0.3); color:#22d3ee; }

  .btn-glow-premium { background:linear-gradient(135deg,#ffc107,#ff9800)!important; border:none; color:#0b0f19!important; font-family:'Sora',sans-serif; font-weight:700; border-radius:5px; box-shadow:0 4px 15px rgba(255,152,0,0.2); transition:all 0.3s ease; }
  .btn-glow-premium:hover { transform:translateY(-2px); box-shadow:0 10px 25px rgba(255,152,0,0.4); background:linear-gradient(135deg,#ffca28,#ffa726)!important; color:#0b0f19!important; }

  .btn-secondary-custom { background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.15); color:#fff; font-family:'Sora',sans-serif; font-weight:600; border-radius:5px; transition:all 0.3s ease; }
  .btn-secondary-custom:hover { background:rgba(255,255,255,0.1); color:#fff; }

  .pagination .page-item .page-link { background:rgba(255,255,255,0.04)!important; border:1px solid rgba(255,255,255,0.08)!important; color:rgba(255,255,255,0.7)!important; font-size:13px!important; padding:6px 12px!important; transition:all 0.3s ease!important; border-radius:5px!important; margin:0 2px!important; }
  .pagination .page-item.active .page-link { background:linear-gradient(135deg,#6366f1,#d946ef)!important; border-color:transparent!important; color:#fff!important; box-shadow:0 4px 12px rgba(99,102,241,0.3)!important; }

  .modal-content { background:#0f172a!important; border:1px solid rgba(255,255,255,0.1)!important; border-radius:5px!important; }
  .modal-header { border-bottom:1px solid rgba(255,255,255,0.08)!important; }
  .modal-footer { border-top:1px solid rgba(255,255,255,0.08)!important; }
  .modal .btn-close { filter:invert(1)!important; }
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
            <i class="icon-base ti tabler-template fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Template Notifikasi</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Kelola template pesan untuk notifikasi WhatsApp, Email, dan In-App
            </p>
          </div>
        </div>
        <a href="{{ route('admin.notification-templates.create') }}" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-plus"></i> Tambah Template
        </a>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center"><i class="icon-base ti tabler-check-circle fs-5 me-2"></i><span>{{ session('success') }}</span></div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="col-12">
      <div class="glass-card-premium px-4 py-4">
        <div class="table-responsive">
          <table class="table table-borderless text-white align-middle">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <th class="text-body-premium small fw-semibold px-0">Key</th>
                <th class="text-body-premium small fw-semibold">Nama</th>
                <th class="text-body-premium small fw-semibold">Channel</th>
                <th class="text-body-premium small fw-semibold">Status</th>
                <th class="text-body-premium small fw-semibold text-end px-0">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($templates as $template)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                  <td class="px-0 py-3">
                    <code class="text-info" style="background: rgba(6,182,212,0.1); padding: 2px 8px; border-radius: 3px; font-size: 12px;">{{ $template->key }}</code>
                  </td>
                  <td class="py-3 fw-semibold text-white">{{ $template->name }}</td>
                  <td class="py-3">
                    @if($template->channel == 'whatsapp')
                      <span class="badge-premium" style="background:rgba(16,185,129,0.15); border-color:rgba(16,185,129,0.3); color:#34d399;">
                        <i class="icon-base ti tabler-brand-whatsapp me-1"></i>WhatsApp
                      </span>
                    @elseif($template->channel == 'email')
                      <span class="badge-premium" style="background:rgba(6,182,212,0.15); border-color:rgba(6,182,212,0.3); color:#22d3ee;">
                        <i class="icon-base ti tabler-mail me-1"></i>Email
                      </span>
                    @else
                      <span class="badge-premium" style="background:rgba(99,102,241,0.15); border-color:rgba(99,102,241,0.3); color:#818cf8;">
                        <i class="icon-base ti tabler-bell me-1"></i>In-App
                      </span>
                    @endif
                  </td>
                  <td class="py-3">
                    @if($template->is_active)
                      <span class="badge-premium badge-premium-success">Aktif</span>
                    @else
                      <span class="badge-premium badge-premium-warning">Nonaktif</span>
                    @endif
                  </td>
                  <td class="text-end px-0 py-3">
                    <div class="d-inline-flex gap-2">
                      <a href="{{ route('admin.notification-templates.edit', $template) }}" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center"
                        style="border-radius: 5px; width: 32px; height: 32px; padding: 0;" title="Edit">
                        <i class="icon-base ti tabler-edit fs-5 text-dark"></i>
                      </a>
                      <form action="{{ route('admin.notification-templates.test', $template) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm d-flex align-items-center justify-content-center"
                          style="border-radius: 5px; width: 32px; height: 32px; padding: 0;" title="Test Kirim">
                          <i class="icon-base ti tabler-send fs-5"></i>
                        </button>
                      </form>
                      <form action="{{ route('admin.notification-templates.destroy', $template) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus template {{ $template->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center"
                          style="border-radius: 5px; width: 32px; height: 32px; padding: 0;" title="Hapus">
                          <i class="icon-base ti tabler-trash fs-5"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-body-premium py-5">
                    <i class="icon-base ti tabler-template-off fs-1 mb-2 d-block text-warning"></i>
                    Belum ada template notifikasi.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if($templates->hasPages())
          <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
            {{ $templates->links() }}
          </div>
        @endif
      </div>
    </div>

  </div>
@endsection
