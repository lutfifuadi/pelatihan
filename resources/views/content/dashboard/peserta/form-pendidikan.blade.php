@php
$configData = Helper::appClasses();

// Config lookup helpers
$fLabels = $fields->pluck('label', 'field_key');
$fPlaceholders = $fields->pluck('placeholder', 'field_key');
$fRequired = $fields->where('is_required', true)->pluck('field_key')->toArray();
$fActive = $fields->where('is_active', true)->pluck('field_key')->toArray();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Form Pendidikan')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('page-style')
<style>


  .select2-container--default .select2-selection--single {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 5px !important;
    height: 42px !important;
    display: flex;
    align-items: center;
    transition: all 0.3s ease !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #ffffff !important;
    padding-left: 14px !important;
    padding-right: 28px !important;
    font-size: 14px !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: rgba(255, 255, 255, 0.35) !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 10px !important;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: rgba(255, 255, 255, 0.5) transparent transparent transparent !important;
    border-width: 5px 4px 0 4px !important;
  }
  .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent rgba(255, 255, 255, 0.5) transparent !important;
    border-width: 0 4px 5px 4px !important;
  }
  .select2-container--default.select2-container--focus .select2-selection--single,
  .select2-container--default.select2-container--open .select2-selection--single {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
  }

  .select2-dropdown {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    border-radius: 5px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
    z-index: 99999 !important;
  }
  .select2-container--default .select2-results__option {
    color: rgba(255, 255, 255, 0.8) !important;
    padding: 8px 14px !important;
    font-size: 14px !important;
    background-color: transparent !important;
  }
  .select2-container--default .select2-results__option--highlighted[aria-selected],
  .select2-container--default .select2-results__option[aria-selected="true"] {
    background: linear-gradient(135deg, #6366f1, #7c3aed) !important;
    color: #ffffff !important;
  }
  .select2-container--default .select2-results__option[aria-disabled="true"] {
    color: rgba(255, 255, 255, 0.35) !important;
  }
  .select2-search--dropdown {
    background: transparent !important;
    padding: 8px !important;
  }
  .select2-container--default .select2-search--dropdown .select2-search__field {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    color: #ffffff !important;
    border-radius: 4px !important;
    padding: 6px 10px !important;
    font-size: 13px !important;
  }

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

  .layout-navbar-fixed .layout-page::before { display: none !important; }
  .content-wrapper > .container-xxl { max-width: 100% !important; padding: 0 !important; }

  .glow-orb {
    position: fixed; border-radius: 50%; filter: blur(120px); opacity: 0.4;
    mix-blend-mode: screen; pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out; z-index: 0;
  }
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #06b6d4 0%, transparent 70%); top: 35%; left: 25%; animation-duration: 24s; }
  @keyframes orbFloat {
    0% { transform: translate(0,0) scale(1) rotate(0deg); }
    50% { transform: translate(60px,40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px,-50px) scale(0.92) rotate(360deg); }
  }

  .glass-card-dashboard {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
    border-radius: 5px;
    position: relative;
    z-index: 1;
    padding: 28px 24px;
  }
  @media (max-width: 660px) {
    .glass-card-dashboard { padding: 20px 16px; }
  }

  .form-control-custom {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
  }
  .form-control-custom:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }
  .form-control-custom::placeholder { color: rgba(255, 255, 255, 0.35) !important; }
  .form-control-custom.is-invalid { border-color: #f87171 !important; box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.2) !important; }
  .form-control-custom:disabled, .form-control-custom[readonly] { background: rgba(255, 255, 255, 0.02) !important; opacity: 0.6; }
  .form-control-uppercase { text-transform: uppercase !important; }

  textarea.form-control-custom { resize: vertical; min-height: 90px; }
  select.form-control-custom option { background: #1a1f2e; color: #f8fafc; }

  .form-label-custom {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 600 !important;
    font-size: 0.7rem !important;
    letter-spacing: 0.08em !important;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.7) !important;
    margin-bottom: 4px;
  }

  .btn-glow {
    position: relative; overflow: hidden; transition: all 0.3s ease; border: none;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    color: #0b0f19 !important;
  }
  .btn-glow:hover {
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 10px 30px rgba(255, 152, 0, 0.5);
    background: linear-gradient(135deg, #ffca28, #ffa726);
  }
  .btn-glow-outline {
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: rgba(255, 255, 255, 0.8) !important;
    transition: all 0.3s ease;
  }
  .btn-glow-outline:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.3);
    color: #ffffff !important;
  }

  .form-check-input-custom {
    background-color: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
  }
  .form-check-input-custom:checked { background-color: #6366f1 !important; border-color: #6366f1 !important; }
  .form-check-input-custom[type="radio"]:checked {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='%23fff'/%3e%3c/svg%3e") !important;
  }
  .text-white-50-custom { color: rgba(255, 255, 255, 0.5) !important; }
  .text-white-70-custom { color: rgba(255, 255, 255, 0.7) !important; }

  .field-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  @media (max-width: 660px) {
    .field-group { grid-template-columns: 1fr; gap: 12px; }
  }
  .field-full { grid-column: 1 / -1; }

  .tab-pane-step { animation: fadeSlideIn 0.35s ease forwards; }
  @keyframes fadeSlideIn {
    0% { opacity: 0; transform: translateY(12px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  .invalid-feedback-custom { color: #f87171; font-size: 11px; margin-top: 3px; display: none; }
  .invalid-feedback-custom.d-block { display: block; }
</style>
@endsection

@section('content')
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
  <div class="glass-card-dashboard mb-4">
    <div class="d-flex align-items-center gap-3">
      <div style="width: 48px; height: 48px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);">
        <i class="icon-base ti tabler-school text-white fs-4"></i>
      </div>
      <div>
        <h4 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Form Pendidikan</h4>
        <p class="text-white-50-custom mb-0 small">Lengkapi data pendidikan &amp; pekerjaan Anda</p>
      </div>
    </div>
  </div>

  @php
    $profile = \App\Models\PesertaProfile::where('user_id', auth()->id())->first();
    $step1Done = $profile && !empty($profile->nama_lengkap) && !empty($profile->nik);
    $step2Done = $profile && !empty($profile->alamat_ktp) && !empty($profile->whatsapp);
    $step3Done = $profile && !empty($profile->pendidikan_terakhir) && !empty($profile->nama_institusi);
    $step4Done = $profile && !empty($profile->pelatihan_id);
    $step5Done = $profile && !empty($profile->jawaban_pertanyaan);
  @endphp

  <!-- Step Indicator: 6 Steps -->
  <div class="step-indicator mb-4">
    <div class="step-progress-line" style="transform: scaleX(0.4); transform-origin: left;"></div>
    
    <!-- Step 1: Data Diri -->
    <div class="step-item {{ $step1Done ? 'completed' : '' }}" @if($step1Done) onclick="window.location.href='{{ route('dashboard.peserta.form-pendaftaran') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step1Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          1
        @endif
      </div>
      <div class="step-label">Data Diri</div>
    </div>
    
    <!-- Step 2: Alamat -->
    <div class="step-item {{ $step2Done ? 'completed' : '' }}" @if($step2Done) onclick="window.location.href='{{ route('dashboard.peserta.form-alamat') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step2Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          2
        @endif
      </div>
      <div class="step-label">Alamat</div>
    </div>
    
    <!-- Step 3: Pendidikan (active) -->
    <div class="step-item active">
      <div class="step-circle">3</div>
      <div class="step-label">Pendidikan</div>
    </div>
    
    <!-- Step 4: Pelatihan -->
    <div class="step-item {{ $step4Done ? 'completed' : '' }}" @if($step4Done) onclick="window.location.href='{{ route('dashboard.peserta.form-minat') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step4Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          4
        @endif
      </div>
      <div class="step-label">Pilihan Pelatihan</div>
    </div>
    
    <!-- Step 5: Dokumen -->
    <div class="step-item {{ $step5Done ? 'completed' : '' }}" @if($step5Done) onclick="window.location.href='{{ route('dashboard.peserta.form-dokumen') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step5Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          5
        @endif
      </div>
      <div class="step-label">Dokumen</div>
    </div>
    
    <!-- Step 6: Review -->
    <div class="step-item">
      <div class="step-circle">6</div>
      <div class="step-label">Review</div>
    </div>
  </div>

  <div class="glass-card-dashboard" x-data="pendidikanForm()" x-cloak>
    <form id="formPendidikan" action="{{ route('dashboard.peserta.form-pendidikan.store') }}" method="POST">
      @csrf

      <div class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-school me-2" style="color: #6366f1;"></i>Pendidikan &amp; Pekerjaan
        </h5>

        <div class="field-group">
          @if(in_array('pendidikan_terakhir', $fActive))
          <div>
            <label class="form-label form-label-custom" for="pendidikan_terakhir">
              {{ $fLabels['pendidikan_terakhir'] ?? 'Pendidikan Terakhir' }}
              @if(in_array('pendidikan_terakhir', $fRequired)) <span class="text-danger">*</span> @endif
            </label>
            <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="select2 form-select form-control form-control-custom" x-model="form.pendidikan_terakhir"
              :class="{ 'is-invalid': errors.pendidikan_terakhir }">
              <option value="" disabled>{{ $fPlaceholders['pendidikan_terakhir'] ?? 'PILIH PENDIDIKAN' }}</option>
              @foreach($pendidikanOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.pendidikan_terakhir }" x-text="errors.pendidikan_terakhir"></div>
          </div>
          @endif
          @if(in_array('tahun_lulus', $fActive))
          <div>
            <label class="form-label form-label-custom" for="tahun_lulus">
              {{ $fLabels['tahun_lulus'] ?? 'Tahun Lulus' }}
              @if(in_array('tahun_lulus', $fRequired)) <span class="text-danger">*</span> @endif
            </label>
            <select id="tahun_lulus" name="tahun_lulus" class="select2 form-select form-control form-control-custom" x-model="form.tahun_lulus"
              :class="{ 'is-invalid': errors.tahun_lulus }">
              <option value="" disabled>{{ $fPlaceholders['tahun_lulus'] ?? 'PILIH TAHUN' }}</option>
              <template x-for="tahun in tahunOptions" :key="tahun">
                <option :value="tahun" x-text="tahun"></option>
              </template>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tahun_lulus }" x-text="errors.tahun_lulus"></div>
          </div>
          @endif
        </div>

        <div class="field-group mt-3">
          @if(in_array('nama_institusi', $fActive))
          <div>
            <label class="form-label form-label-custom" for="nama_institusi">
              {{ $fLabels['nama_institusi'] ?? 'Nama Institusi' }}
              @if(in_array('nama_institusi', $fRequired)) <span class="text-danger">*</span> @endif
            </label>
            <input type="text" id="nama_institusi" name="nama_institusi" class="form-control form-control-custom form-control-uppercase"
              x-model="form.nama_institusi" placeholder="{{ $fPlaceholders['nama_institusi'] ?? 'NAMA SEKOLAH/UNIVERSITAS' }}"
              @input="form.nama_institusi = form.nama_institusi.toUpperCase()"
              :class="{ 'is-invalid': errors.nama_institusi }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.nama_institusi }" x-text="errors.nama_institusi"></div>
          </div>
          @endif
          @if(in_array('jurusan', $fActive))
          <div>
            <label class="form-label form-label-custom" for="jurusan">
              {{ $fLabels['jurusan'] ?? 'Jurusan' }}
              @if(in_array('jurusan', $fRequired)) <span class="text-danger">*</span> @endif
            </label>
            <input type="text" id="jurusan" name="jurusan" class="form-control form-control-custom form-control-uppercase"
              x-model="form.jurusan" placeholder="{{ $fPlaceholders['jurusan'] ?? 'JURUSAN (JIKA ADA)' }}"
              @input="form.jurusan = form.jurusan.toUpperCase()" />
          </div>
          @endif
        </div>

        <div class="field-group mt-3">
          @if(in_array('status_pekerjaan', $fActive))
          <div>
            <label class="form-label form-label-custom" for="status_pekerjaan">
              {{ $fLabels['status_pekerjaan'] ?? 'Status Pekerjaan' }}
              @if(in_array('status_pekerjaan', $fRequired)) <span class="text-danger">*</span> @endif
            </label>
            <select id="status_pekerjaan" name="status_pekerjaan" class="select2 form-select form-control form-control-custom" x-model="form.status_pekerjaan"
              :class="{ 'is-invalid': errors.status_pekerjaan }">
              <option value="" disabled>{{ $fPlaceholders['status_pekerjaan'] ?? 'PILIH STATUS' }}</option>
              @foreach($pekerjaanOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.status_pekerjaan }" x-text="errors.status_pekerjaan"></div>
          </div>
          @endif
          @if(in_array('nama_perusahaan', $fActive))
          <div x-show="form.status_pekerjaan === 'BEKERJA'">
            <label class="form-label form-label-custom" for="nama_perusahaan">
              {{ $fLabels['nama_perusahaan'] ?? 'Nama Perusahaan' }}
              @if(in_array('nama_perusahaan', $fRequired)) <span class="text-danger">*</span> @endif
            </label>
            <input type="text" id="nama_perusahaan" name="nama_perusahaan" class="form-control form-control-custom form-control-uppercase"
              x-model="form.nama_perusahaan" placeholder="{{ $fPlaceholders['nama_perusahaan'] ?? 'NAMA PERUSAHAAN / INSTANSI' }}"
              @input="form.nama_perusahaan = form.nama_perusahaan.toUpperCase()" />
          </div>
          @endif
        </div>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('dashboard.peserta.form-alamat') }}" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;">
          <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
        </a>
        <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="submitForm()" :disabled="saving">
          <span x-show="!saving">Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i></span>
          <span x-show="saving"><i class="icon-base ti tabler-loader animate-spin me-1"></i> Menyimpan...</span>
        </button>
      </div>

    </form>
  </div>
</div>

<style>
  [x-cloak] { display: none !important; }
</style>
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('page-script')
<script>
$(function () {
  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>');
      $this.select2({
        placeholder: 'Pilih',
        dropdownParent: $this.parent()
      });
    });
  }

  $(document).on('select2:select', '.select2', function () {
    $(this)[0].dispatchEvent(new Event('input', { bubbles: true }));
  });
});

window.reinitSelect2 = function() {
  if (typeof $ === 'undefined' || !$.fn.select2) return;
  $('.select2').each(function() {
    try { $(this).select2('destroy'); } catch(e) {}
  });
  $('.select2-container').remove();
  $('.select2:visible').each(function () {
    var $this = $(this);
    if ($this.hasClass('select2-hidden-accessible')) return;
    try {
      $this.wrap('<div class="position-relative"></div>');
      $this.select2({
        placeholder: 'Pilih',
        dropdownParent: $this.parent()
      });
    } catch(e) {}
  });
}
</script>

<script>
  // Data dari server untuk diisi otomatis ke form (Pola PMBM)
  window._formData = {!! json_encode($data) !!};

  document.addEventListener('alpine:init', function() {
    Alpine.data('pendidikanForm', function() {
      var fd = window._formData || {};
      return {
        saving: false,
        form: {
          pendidikan_terakhir: fd.pendidikan_terakhir || '',
          tahun_lulus: fd.tahun_lulus || '',
          nama_institusi: fd.nama_institusi || '',
          jurusan: fd.jurusan || '',
          status_pekerjaan: fd.status_pekerjaan || '',
          nama_perusahaan: fd.nama_perusahaan || '',
        },
        errors: {},
        tahunOptions: (function() {
          var years = [];
          for (var y = new Date().getFullYear(); y >= 1900; y--) { years.push(y); }
          return years;
        })(),

        clearErrors() { this.errors = {}; },

        validate() {
          this.clearErrors();
          var errs = {};
          var valid = true;

          if (!this.form.pendidikan_terakhir) { errs.pendidikan_terakhir = 'Pilih pendidikan terakhir'; valid = false; }
          if (!this.form.tahun_lulus) { errs.tahun_lulus = 'Pilih tahun lulus'; valid = false; }
          if (!this.form.nama_institusi.trim()) { errs.nama_institusi = 'Nama institusi wajib diisi'; valid = false; }
          if (!this.form.status_pekerjaan) { errs.status_pekerjaan = 'Pilih status pekerjaan'; valid = false; }

          this.errors = errs;
          return valid;
        },

        submitForm() {
          if (!this.validate()) return;
          this.saving = true;
          document.getElementById('formPendidikan').submit();
        },
      };
    });
  });
</script>
@endsection
