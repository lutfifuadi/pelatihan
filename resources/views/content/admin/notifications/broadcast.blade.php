@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Broadcast WhatsApp')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

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
  .stat-icon-info { background: rgba(6,182,212,0.12); color:#06b6d4; }

  .badge-premium { background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); color:rgba(255,255,255,0.8); border-radius:5px; padding:4px 12px; font-weight:500; font-size:0.75rem; }
  .badge-premium-success { background:rgba(16,185,129,0.15); border-color:rgba(16,185,129,0.3); color:#34d399; }
  .badge-premium-info { background:rgba(6,182,212,0.15); border-color:rgba(6,182,212,0.3); color:#22d3ee; }

  .btn-glow-premium { background:linear-gradient(135deg,#ffc107,#ff9800)!important; border:none; color:#0b0f19!important; font-family:'Sora',sans-serif; font-weight:700; border-radius:5px; box-shadow:0 4px 15px rgba(255,152,0,0.2); transition:all 0.3s ease; }
  .btn-glow-premium:hover { transform:translateY(-2px); box-shadow:0 10px 25px rgba(255,152,0,0.4); background:linear-gradient(135deg,#ffca28,#ffa726)!important; color:#0b0f19!important; }
  .btn-secondary-custom { background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.15); color:#fff; font-family:'Sora',sans-serif; font-weight:600; border-radius:5px; transition:all 0.3s ease; }
  .btn-secondary-custom:hover { background:rgba(255,255,255,0.1); color:#fff; }
  .btn-wa { background:linear-gradient(135deg,#25D366,#128C7E)!important; border:none; color:#fff!important; font-family:'Sora',sans-serif; font-weight:700; border-radius:5px; transition:all 0.3s ease; }
  .btn-wa:hover { transform:translateY(-2px); box-shadow:0 10px 25px rgba(37,211,102,0.3)!important; }

  .form-control, .form-select, textarea { background:rgba(255,255,255,0.03)!important; border:1px solid rgba(255,255,255,0.12)!important; color:#fff!important; border-radius:5px!important; padding:10px 14px!important; font-size:14px!important; transition:all 0.3s ease!important; }
  .form-control:focus, .form-select:focus, textarea:focus { background:rgba(255,255,255,0.06)!important; border-color:#6366f1!important; box-shadow:0 0 0 4px rgba(99,102,241,0.25)!important; color:#fff!important; }
  .form-control::placeholder, textarea::placeholder { color: rgba(255,255,255,0.35)!important; }
  .form-control.is-invalid, .form-select.is-invalid, textarea.is-invalid { border-color:#f87171!important; box-shadow:0 0 0 4px rgba(248,113,113,0.2)!important; }
  .form-label { font-family:'Outfit',sans-serif!important; font-weight:600!important; font-size:0.75rem!important; letter-spacing:0.08em!important; text-transform:uppercase; color:rgba(255,255,255,0.7)!important; margin-bottom:6px; }
  .form-select option { background-color:#0f172a!important; color:#fff!important; }

  .form-check-input { background-color:rgba(255,255,255,0.05)!important; border:1px solid rgba(255,255,255,0.15)!important; border-radius:3px!important; }
  .form-check-input:checked { background-color:#6366f1!important; border-color:#6366f1!important; }

  .form-control:-webkit-autofill,.form-control:-webkit-autofill:hover,.form-control:-webkit-autofill:focus,.form-control:-webkit-autofill:active { -webkit-text-fill-color:#fff!important; transition:background-color 5000s ease-in-out 0s; background-clip:padding-box!important; box-shadow:0 0 0 1000px #131824 inset!important; -webkit-box-shadow:0 0 0 1000px #131824 inset!important; }

  .preview-box { background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:5px; padding:16px; min-height: 120px; white-space: pre-wrap; }

  .select2-container--default .select2-selection--single { background:rgba(255,255,255,0.03)!important; border:1px solid rgba(255,255,255,0.12)!important; border-radius:5px!important; height:42px!important; display:flex; align-items:center; transition:all 0.3s ease!important; }
  .select2-container--default .select2-selection--single .select2-selection__rendered { color:#fff!important; padding-left:14px!important; padding-right:28px!important; font-size:14px!important; }
  .select2-container--default .select2-selection--single .select2-selection__placeholder { color:rgba(255,255,255,0.35)!important; }
  .select2-container--default .select2-selection--single .select2-selection__arrow { height:40px!important; right:10px!important; }
  .select2-container--default .select2-selection--single .select2-selection__arrow b { border-color:rgba(255,255,255,0.5) transparent transparent transparent!important; }
  .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b { border-color:transparent transparent rgba(255,255,255,0.5) transparent!important; }
  .select2-container--default.select2-container--focus .select2-selection--single,
  .select2-container--default.select2-container--open .select2-selection--single { background:rgba(255,255,255,0.06)!important; border-color:#6366f1!important; box-shadow:0 0 0 4px rgba(99,102,241,0.25)!important; }
  .select2-dropdown { background:#0f172a!important; border:1px solid rgba(255,255,255,0.15)!important; border-radius:5px!important; box-shadow:0 10px 25px rgba(0,0,0,0.5)!important; }
  .select2-container--default .select2-results__option { color:rgba(255,255,255,0.8)!important; padding:8px 14px!important; font-size:14px!important; }
  .select2-container--default .select2-results__option--highlighted[aria-selected] { background:linear-gradient(135deg,#6366f1,#7c3aed)!important; color:#fff!important; }

  .target-card { background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:5px; padding:16px; cursor:pointer; transition:all 0.3s ease; }
  .target-card:hover { border-color:rgba(99,102,241,0.3); background:rgba(99,102,241,0.05); }
  .target-card.active { border-color:#6366f1; background:rgba(99,102,241,0.1); }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box stat-icon-success">
          <i class="icon-base ti tabler-brand-whatsapp fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0">Broadcast WhatsApp</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Kirim pesan broadcast ke banyak penerima sekaligus
          </p>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center"><i class="icon-base ti tabler-check-circle fs-5 me-2"></i><span>{{ session('success') }}</span></div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center"><i class="icon-base ti tabler-alert-circle fs-5 me-2"></i><span>{{ session('error') }}</span></div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="row g-4">
      <div class="col-lg-8">
        <form id="broadcastForm" action="{{ route('admin.notifications.broadcast.send') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-users text-info"></i> Pilih Penerima
            </h5>

            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <div class="target-card @if(old('target') == 'all_peserta') active @endif" onclick="selectTarget('all_peserta')">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="target_all_peserta" value="all_peserta"
                      {{ old('target') == 'all_peserta' ? 'checked' : '' }}
                      onchange="selectTarget('all_peserta')">
                    <label class="form-check-label text-white fw-semibold" for="target_all_peserta">
                      Semua Peserta Aktif
                    </label>
                  </div>
                  <small class="text-body-premium">Kirim ke seluruh peserta yang terdaftar aktif</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="target-card @if(old('target') == 'by_pelatihan') active @endif" onclick="selectTarget('by_pelatihan')">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="target_by_pelatihan" value="by_pelatihan"
                      {{ old('target') == 'by_pelatihan' ? 'checked' : '' }}
                      onchange="selectTarget('by_pelatihan')">
                    <label class="form-check-label text-white fw-semibold" for="target_by_pelatihan">
                      Per Pelatihan
                    </label>
                  </div>
                  <small class="text-body-premium">Kirim ke peserta di pelatihan tertentu</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="target-card @if(old('target') == 'all_koordinator') active @endif" onclick="selectTarget('all_koordinator')">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="target_all_koordinator" value="all_koordinator"
                      {{ old('target') == 'all_koordinator' ? 'checked' : '' }}
                      onchange="selectTarget('all_koordinator')">
                    <label class="form-check-label text-white fw-semibold" for="target_all_koordinator">
                      Semua Koordinator
                    </label>
                  </div>
                  <small class="text-body-premium">Kirim ke seluruh koordinator aktif</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="target-card @if(old('target') == 'custom') active @endif" onclick="selectTarget('custom')">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="target_custom" value="custom"
                      {{ old('target') == 'custom' ? 'checked' : '' }}
                      onchange="selectTarget('custom')">
                    <label class="form-check-label text-white fw-semibold" for="target_custom">
                      Custom (Upload CSV)
                    </label>
                  </div>
                  <small class="text-body-premium">Upload file CSV berisi nomor WhatsApp</small>
                </div>
              </div>
            </div>

            <div id="pelatihanField" class="mb-4" style="display: {{ old('target') == 'by_pelatihan' ? 'block' : 'none' }};">
              <label for="pelatihan_id" class="form-label">Pilih Pelatihan <span class="text-danger">*</span></label>
              <select class="select2 form-select" id="pelatihan_id" name="pelatihan_id" data-placeholder="-- Pilih Pelatihan --">
                <option value=""></option>
                @foreach($pelatihans as $pel)
                  <option value="{{ $pel->id }}" {{ old('pelatihan_id') == $pel->id ? 'selected' : '' }}>
                    {{ $pel->nama }} (Batch {{ $pel->batch }})
                  </option>
                @endforeach
              </select>
            </div>

            <div id="csvField" class="mb-4" style="display: {{ old('target') == 'custom' ? 'block' : 'none' }};">
              <label for="csv_file" class="form-label">File CSV <span class="text-danger">*</span></label>
              <input type="file" class="form-control @error('csv_file') is-invalid @enderror"
                id="csv_file" name="csv_file" accept=".csv,.txt">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i>Format: satu nomor per baris, format internasional (62812xxxxxxx)
              </small>
              @error('csv_file')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-message text-primary"></i> Pesan
            </h5>

            <div class="mb-4">
              <label for="template_id" class="form-label">Gunakan Template</label>
              <select class="select2 form-select" id="template_id" name="template_id" data-placeholder="-- Pilih Template (Opsional) --">
                <option value=""></option>
                @foreach($templates as $tpl)
                  <option value="{{ $tpl->id }}" data-body="{{ $tpl->body }}" {{ old('template_id') == $tpl->id ? 'selected' : '' }}>
                    {{ $tpl->name }}
                  </option>
                @endforeach
              </select>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i>Pilih template atau tulis pesan custom di bawah
              </small>
            </div>

            <div class="mb-4">
              <label for="custom_message" class="form-label">Pesan Custom</label>
              <textarea class="form-control @error('custom_message') is-invalid @enderror"
                id="custom_message" name="custom_message" rows="6"
                placeholder="Tulis pesan broadcast...">{{ old('custom_message') }}</textarea>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                Gunakan <code>{nama}</code>, <code>{pelatihan}</code>, <code>{tanggal}</code> untuk data dinamis
              </small>
              @error('custom_message')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="text-end mb-4">
            <button type="submit" class="btn btn-wa px-5 py-3 d-inline-flex align-items-center gap-2" onclick="return confirmBroadcast()">
              <i class="icon-base ti tabler-brand-whatsapp fs-5"></i> Kirim Broadcast
            </button>
          </div>
        </form>
      </div>

      <div class="col-lg-4">
        <div class="glass-card-premium px-4 py-4 mb-4">
          <h5 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-eye text-primary"></i> Preview Pesan
          </h5>
          <div class="preview-box text-white" id="previewBox">
            Pilih template atau tulis pesan untuk melihat preview...
          </div>
        </div>

        <div class="glass-card-premium px-4 py-4 mb-4">
          <h5 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-history text-info"></i> Broadcast Terakhir
          </h5>
          @if($recentBroadcasts->isEmpty())
            <div class="text-center py-4">
              <i class="icon-base ti tabler-message-off fs-2 text-body-premium mb-2 d-block"></i>
              <small class="text-body-premium">Belum ada broadcast.</small>
            </div>
          @else
            <div class="d-flex flex-column gap-3">
              @foreach($recentBroadcasts as $notif)
                <div style="border-bottom: 1px solid rgba(255,255,255,0.04); padding-bottom: 8px;">
                  <div class="d-flex justify-content-between align-items-center">
                    <small class="text-white fw-semibold">{{ $notif->user->name ?? 'Unknown' }}</small>
                    <small class="text-body-premium" style="font-size: 11px;">{{ $notif->created_at->diffForHumans() }}</small>
                  </div>
                  <small class="text-body-premium" style="font-size: 12px;">
                    {{ Str::limit($notif->body, 60) }}
                  </small>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
    jQuery('.select2').each(function () {
      var $this = jQuery(this);
      if (!$this.parent().hasClass('position-relative')) {
        $this.wrap('<div class="position-relative"></div>');
      }
      $this.select2({ dropdownParent: $this.parent(), allowClear: true });
    });
  }

  const templateSelect = document.getElementById('template_id');
  const customMsg = document.getElementById('custom_message');
  const previewBox = document.getElementById('previewBox');

  function updatePreview() {
    let text = '';
    const selected = templateSelect.options[templateSelect.selectedIndex];
    if (selected && selected.value) {
      text = selected.getAttribute('data-body') || '';
    } else if (customMsg.value.trim()) {
      text = customMsg.value.trim();
    }

    if (!text) {
      previewBox.innerHTML = 'Pilih template atau tulis pesan untuk melihat preview...';
      return;
    }

    const sample = text
      .replace(/\{nama\}/g, 'Admin')
      .replace(/\{pelatihan\}/g, 'Pelatihan Ekonomi Kreatif')
      .replace(/\{tanggal\}/g, new Date().toLocaleDateString('id-ID'))
      .replace(/\{tugas\}/g, 'Tugas Modul 1')
      .replace(/\{link\}/g, window.location.origin)
      .replace(/\{app_name\}/g, document.title);

    previewBox.innerHTML = sample;
  }

  templateSelect.addEventListener('change', updatePreview);
  customMsg.addEventListener('input', updatePreview);
});

function selectTarget(val) {
  document.querySelectorAll('.target-card').forEach(c => c.classList.remove('active'));
  document.getElementById('pelatihanField').style.display = 'none';
  document.getElementById('csvField').style.display = 'none';

  const card = document.querySelector(`.target-card:has(input[value="${val}"])`);
  if (card) card.classList.add('active');

  if (val === 'by_pelatihan') document.getElementById('pelatihanField').style.display = 'block';
  if (val === 'custom') document.getElementById('csvField').style.display = 'block';
}

function confirmBroadcast() {
  const target = document.querySelector('input[name="target"]:checked');
  if (!target) { alert('Pilih target penerima terlebih dahulu.'); return false; }
  return confirm('Broadcast akan dikirim ke antrian (queue). Lanjutkan?');
}
</script>
@endsection
