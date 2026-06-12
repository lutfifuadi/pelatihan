@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Tambah Template Notifikasi')

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

  .btn-glow-premium { background:linear-gradient(135deg,#ffc107,#ff9800)!important; border:none; color:#0b0f19!important; font-family:'Sora',sans-serif; font-weight:700; border-radius:5px; box-shadow:0 4px 15px rgba(255,152,0,0.2); transition:all 0.3s ease; }
  .btn-glow-premium:hover { transform:translateY(-2px); box-shadow:0 10px 25px rgba(255,152,0,0.4); background:linear-gradient(135deg,#ffca28,#ffa726)!important; color:#0b0f19!important; }
  .btn-secondary-custom { background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.15); color:#fff; font-family:'Sora',sans-serif; font-weight:600; border-radius:5px; transition:all 0.3s ease; }
  .btn-secondary-custom:hover { background:rgba(255,255,255,0.1); color:#fff; }

  .form-control, .form-select, textarea { background:rgba(255,255,255,0.03)!important; border:1px solid rgba(255,255,255,0.12)!important; color:#fff!important; border-radius:5px!important; padding:10px 14px!important; font-size:14px!important; transition:all 0.3s ease!important; }
  .form-control:focus, .form-select:focus, textarea:focus { background:rgba(255,255,255,0.06)!important; border-color:#6366f1!important; box-shadow:0 0 0 4px rgba(99,102,241,0.25)!important; color:#fff!important; }
  .form-control::placeholder, textarea::placeholder { color: rgba(255,255,255,0.35)!important; }
  .form-control.is-invalid, .form-select.is-invalid, textarea.is-invalid { border-color:#f87171!important; box-shadow:0 0 0 4px rgba(248,113,113,0.2)!important; }
  .form-label { font-family:'Outfit',sans-serif!important; font-weight:600!important; font-size:0.75rem!important; letter-spacing:0.08em!important; text-transform:uppercase; color:rgba(255,255,255,0.7)!important; margin-bottom:6px; }
  .form-select option { background-color:#0f172a!important; color:#fff!important; }

  .form-check-input { background-color:rgba(255,255,255,0.05)!important; border:1px solid rgba(255,255,255,0.15)!important; border-radius:3px!important; }
  .form-check-input:checked { background-color:#6366f1!important; border-color:#6366f1!important; }

  .form-control:-webkit-autofill,.form-control:-webkit-autofill:hover,.form-control:-webkit-autofill:focus,.form-control:-webkit-autofill:active { -webkit-text-fill-color:#fff!important; transition:background-color 5000s ease-in-out 0s; background-clip:padding-box!important; box-shadow:0 0 0 1000px #131824 inset!important; -webkit-box-shadow:0 0 0 1000px #131824 inset!important; }

  .placeholder-info { background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.2); border-radius:5px; padding:12px 16px; }
  .placeholder-info code { background:rgba(99,102,241,0.15); color:#818cf8; padding:2px 6px; border-radius:3px; font-size:12px; }
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
          <i class="icon-base ti tabler-plus fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0">Tambah Template Notifikasi</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Buat template pesan baru untuk notifikasi
          </p>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="glass-card-premium px-4 px-xl-5 py-5">
        <div class="row">
          <div class="col-lg-8">
            <form action="{{ route('admin.notification-templates.store') }}" method="POST">
              @csrf

              <div class="mb-4">
                <label for="key" class="form-label">Key <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('key') is-invalid @enderror"
                  id="key" name="key" value="{{ old('key') }}"
                  placeholder="contoh: welcome_peserta, tugas_baru" required>
                <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                  <i class="icon-base ti tabler-info-circle me-1"></i>Unique identifier, gunakan snake_case
                </small>
                @error('key')
                  <div class="invalid-feedback mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-4">
                <label for="name" class="form-label">Nama Template <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                  id="name" name="name" value="{{ old('name') }}"
                  placeholder="Nama template yang mudah dikenali" required>
                @error('name')
                  <div class="invalid-feedback mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-4">
                <label for="title" class="form-label">Judul (Title)</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror"
                  id="title" name="title" value="{{ old('title') }}"
                  placeholder="Judul notifikasi (opsional)">
                @error('title')
                  <div class="invalid-feedback mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-4">
                <label for="body" class="form-label">Body Pesan <span class="text-danger">*</span></label>
                <textarea class="form-control @error('body') is-invalid @enderror"
                  id="body" name="body" rows="8"
                  placeholder="Tulis isi pesan. Gunakan {placeholder} untuk variabel dinamis." required>{{ old('body') }}</textarea>
                @error('body')
                  <div class="invalid-feedback mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                  <label for="channel" class="form-label">Channel <span class="text-danger">*</span></label>
                  <select class="form-select @error('channel') is-invalid @enderror" id="channel" name="channel" required>
                    <option value="">-- Pilih Channel --</option>
                    <option value="whatsapp" @selected(old('channel') == 'whatsapp')>WhatsApp</option>
                    <option value="email" @selected(old('channel') == 'email')>Email</option>
                    <option value="in_app" @selected(old('channel') == 'in_app')>In-App</option>
                  </select>
                  @error('channel')
                    <div class="invalid-feedback mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check d-flex align-items-center gap-2 mt-2">
                      <input type="hidden" name="is_active" value="0">
                      <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                      <label class="form-check-label text-white-50 ms-1 mb-0" for="is_active" style="cursor: pointer; font-size: 13px;">Template Aktif</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="{{ route('admin.notification-templates.index') }}" class="btn btn-secondary-custom px-4 py-2">
                  Batal
                </a>
                <button type="submit" class="btn btn-glow-premium px-4 py-2">
                  <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan
                </button>
              </div>
            </form>
          </div>
          <div class="col-lg-4">
            <div class="placeholder-info">
              <h6 class="text-white fw-semibold mb-2" style="font-size: 0.85rem;">
                <i class="icon-base ti tabler-info-circle me-1 text-primary"></i>Placeholder Tersedia
              </h6>
              <p class="text-body-premium small mb-3">
                Gunakan placeholder berikut di body/title untuk data dinamis:
              </p>
              <div class="d-flex flex-column gap-2">
                <div><code>{nama}</code> <span class="text-body-premium" style="font-size: 12px;">— Nama peserta/user</span></div>
                <div><code>{pelatihan}</code> <span class="text-body-premium" style="font-size: 12px;">— Nama pelatihan</span></div>
                <div><code>{tanggal}</code> <span class="text-body-premium" style="font-size: 12px;">— Tanggal (format: d/m/Y)</span></div>
                <div><code>{tugas}</code> <span class="text-body-premium" style="font-size: 12px;">— Nama tugas</span></div>
                <div><code>{link}</code> <span class="text-body-premium" style="font-size: 12px;">— URL link</span></div>
                <div><code>{app_name}</code> <span class="text-body-premium" style="font-size: 12px;">— Nama aplikasi</span></div>
              </div>
              <hr class="my-3" style="border-color:rgba(255,255,255,0.08);">
              <p class="text-body-premium small mb-0">
                <i class="icon-base ti tabler-automation me-1"></i>
                Variables akan di-generate otomatis dari placeholder yang digunakan di body.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
