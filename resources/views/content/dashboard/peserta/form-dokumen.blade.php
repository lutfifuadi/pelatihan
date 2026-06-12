@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Form Dokumen & Konfirmasi')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

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
  .text-white-50-custom { color: rgba(255, 255, 255, 0.5) !important; }
  .text-white-70-custom { color: rgba(255, 255, 255, 0.7) !important; }

  .file-upload-area {
    background: rgba(255, 255, 255, 0.03);
    border: 1px dashed rgba(255, 255, 255, 0.15);
    border-radius: 5px;
    padding: 24px 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .file-upload-area:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(99, 102, 241, 0.4);
  }
  .file-upload-area.has-file { border-color: #10b981; background: rgba(16, 185, 129, 0.05); }

  .review-section {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    padding: 16px;
    margin-bottom: 12px;
  }
  .review-section-title {
    font-family: 'Sora', sans-serif;
    font-size: 0.8rem; font-weight: 700;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 10px;
    display: flex; align-items: center; gap: 8px;
  }
  .review-item {
    display: flex; justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    font-size: 13px;
  }
  .review-item:last-child { border-bottom: none; }
  .review-item-label { color: rgba(255, 255, 255, 0.5); }
  .review-item-value { color: rgba(255, 255, 255, 0.9); font-weight: 500; text-align: right; max-width: 60%; }

  .invalid-feedback-custom { color: #f87171; font-size: 11px; margin-top: 3px; display: none; }
  .invalid-feedback-custom.d-block { display: block; }

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
        <i class="icon-base ti tabler-file-check text-white fs-4"></i>
      </div>
      <div>
        <h4 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Form Dokumen &amp; Konfirmasi</h4>
        <p class="text-white-50-custom mb-0 small">Unggah dokumen dan konfirmasi data Anda</p>
      </div>
    </div>
  </div>

  <div class="glass-card-dashboard" x-data="dokumenForm()" x-cloak>
    <form id="formDokumen" action="{{ route('dashboard.peserta.form-dokumen.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-file-check me-2" style="color: #6366f1;"></i>Dokumen &amp; Konfirmasi
        </h5>

        <div class="mb-3">
          <label class="form-label form-label-custom">Upload Foto Profil</label>
          <div class="file-upload-area" :class="{ 'has-file': form.foto_profil }"
            @click="document.getElementById('foto_profil_input').click()">
            <template x-if="!form.foto_profil">
              <div>
                <i class="icon-base ti tabler-photo-plus fs-2 mb-1 d-block" style="color: rgba(255,255,255,0.3);"></i>
                <span class="text-white-50-custom small">Klik untuk upload foto profil</span>
                <p class="text-white-50-custom mt-1 mb-0" style="font-size: 11px;">Format: JPG, PNG. Maks 2MB</p>
              </div>
            </template>
            <template x-if="form.foto_profil">
              <div>
                <i class="icon-base ti tabler-file-check fs-2 mb-1 d-block" style="color: #10b981;"></i>
                <span class="text-white-70-custom small fw-semibold" x-text="form.foto_profil.name"></span>
                <button type="button" class="d-block mx-auto mt-1 btn btn-sm fw-semibold"
                  style="background: none; border: none; color: #f87171; font-size: 11px;"
                  @click.stop="form.foto_profil = null; $el.closest('.file-upload-area').querySelector('input[type=file]').value = ''">
                  <i class="icon-base ti tabler-trash me-1"></i>Hapus
                </button>
              </div>
            </template>
          </div>
          <input type="file" id="foto_profil_input" name="foto_profil" accept="image/*" class="d-none"
            @change="handleFileUpload('foto_profil', $event)" />
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.foto_profil }" x-text="errors.foto_profil"></div>
        </div>

        <div class="mb-3">
          <label class="form-label form-label-custom">Upload Scan KTP</label>
          <div class="file-upload-area" :class="{ 'has-file': form.scan_ktp }"
            @click="document.getElementById('scan_ktp_input').click()">
            <template x-if="!form.scan_ktp">
              <div>
                <i class="icon-base ti tabler-id fs-2 mb-1 d-block" style="color: rgba(255,255,255,0.3);"></i>
                <span class="text-white-50-custom small">Klik untuk upload scan KTP</span>
                <p class="text-white-50-custom mt-1 mb-0" style="font-size: 11px;">Format: PDF, JPG, PNG. Maks 2MB</p>
              </div>
            </template>
            <template x-if="form.scan_ktp">
              <div>
                <i class="icon-base ti tabler-file-check fs-2 mb-1 d-block" style="color: #10b981;"></i>
                <span class="text-white-70-custom small fw-semibold" x-text="form.scan_ktp.name"></span>
                <button type="button" class="d-block mx-auto mt-1 btn btn-sm fw-semibold"
                  style="background: none; border: none; color: #f87171; font-size: 11px;"
                  @click.stop="form.scan_ktp = null; $el.closest('.file-upload-area').querySelector('input[type=file]').value = ''">
                  <i class="icon-base ti tabler-trash me-1"></i>Hapus
                </button>
              </div>
            </template>
          </div>
          <input type="file" id="scan_ktp_input" name="scan_ktp" accept=".pdf,image/*" class="d-none"
            @change="handleFileUpload('scan_ktp', $event)" />
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.scan_ktp }" x-text="errors.scan_ktp"></div>
        </div>

        <!-- Review Ringkasan Data -->
        <div class="mt-4">
          <h6 class="text-white-70-custom fw-semibold mb-2" style="font-size: 0.85rem;">
            <i class="icon-base ti tabler-eye me-1"></i>Review Data Anda
          </h6>

          @if($profile)
          <div class="review-section">
            <div class="review-section-title">
              <i class="icon-base ti tabler-user" style="font-size: 0.9rem; color: #6366f1;"></i> Data Pribadi
            </div>
            <div class="review-item">
              <span class="review-item-label">Nama Lengkap</span>
              <span class="review-item-value">{{ $profile->nama_lengkap ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Jenis Kelamin</span>
              <span class="review-item-value">{{ $profile->jenis_kelamin ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Tempat Lahir</span>
              <span class="review-item-value">{{ $profile->tempat_lahir ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Tgl Lahir</span>
              <span class="review-item-value">{{ ($profile->tanggal_lahir ?? '-') . ' ' . ($profile->bulan_lahir ?? '') . ' ' . ($profile->tahun_lahir ?? '') }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">NIK</span>
              <span class="review-item-value">{{ $profile->nik ?? '-' }}</span>
            </div>
          </div>

          <div class="review-section">
            <div class="review-section-title">
              <i class="icon-base ti tabler-map-pin" style="font-size: 0.9rem; color: #6366f1;"></i> Alamat &amp; Kontak
            </div>
            <div class="review-item">
              <span class="review-item-label">Alamat</span>
              <span class="review-item-value">{{ $profile->alamat_ktp ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">RT/RW</span>
              <span class="review-item-value">{{ ($profile->rt ?? '-') . '/' . ($profile->rw ?? '-') }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Kelurahan</span>
              <span class="review-item-value">{{ $profile->kelurahan ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Kecamatan</span>
              <span class="review-item-value">{{ $profile->kecamatan ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Kota</span>
              <span class="review-item-value">{{ $profile->kota ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Provinsi</span>
              <span class="review-item-value">{{ $profile->provinsi ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Kode Pos</span>
              <span class="review-item-value">{{ $profile->kodepos ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">WhatsApp</span>
              <span class="review-item-value">{{ $profile->whatsapp ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Email</span>
              <span class="review-item-value">{{ $profile->email ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Link Medsos</span>
              <span class="review-item-value">
                @php
                  $linkMedsos = $profile->link_medsos ?? [];
                  if (is_string($linkMedsos)) $linkMedsos = json_decode($linkMedsos, true) ?? [];
                @endphp
                @if(!empty($linkMedsos) && is_array($linkMedsos))
                  @foreach($linkMedsos as $medsos)
                    <div style="font-size: 11px;">{{ ($medsos['platform'] ?? '') . ': ' . ($medsos['url'] ?? '') }}</div>
                  @endforeach
                @else
                  -
                @endif
              </span>
            </div>
          </div>

          <div class="review-section">
            <div class="review-section-title">
              <i class="icon-base ti tabler-school" style="font-size: 0.9rem; color: #6366f1;"></i> Pendidikan &amp; Pekerjaan
            </div>
            <div class="review-item">
              <span class="review-item-label">Pendidikan</span>
              <span class="review-item-value">{{ $profile->pendidikan_terakhir ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Institusi</span>
              <span class="review-item-value">{{ $profile->nama_institusi ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Jurusan</span>
              <span class="review-item-value">{{ $profile->jurusan ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Tahun Lulus</span>
              <span class="review-item-value">{{ $profile->tahun_lulus ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Status Pekerjaan</span>
              <span class="review-item-value">{{ $profile->status_pekerjaan ?? '-' }}</span>
            </div>
            @if($profile->status_pekerjaan === 'Bekerja')
            <div class="review-item">
              <span class="review-item-label">Perusahaan</span>
              <span class="review-item-value">{{ $profile->nama_perusahaan ?? '-' }}</span>
            </div>
            @endif
          </div>

          <div class="review-section">
            <div class="review-section-title">
              <i class="icon-base ti tabler-heart" style="font-size: 0.9rem; color: #6366f1;"></i> Minat Pelatihan
            </div>
            <div class="review-item">
              <span class="review-item-label">Bidang Minat</span>
              <span class="review-item-value">
                @php
                  $bidangMinat = $profile->bidang_minat ?? [];
                  if (is_string($bidangMinat)) $bidangMinat = [$bidangMinat];
                @endphp
                {{ !empty($bidangMinat) ? implode(', ', $bidangMinat) : '-' }}
              </span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Tujuan</span>
              <span class="review-item-value">{{ $profile->tujuan_pelatihan ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Jadwal</span>
              <span class="review-item-value">{{ $profile->preferensi_jadwal ?? '-' }}</span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Mode</span>
              <span class="review-item-value">{{ $profile->preferensi_mode ?? '-' }}</span>
            </div>
          </div>
          @else
          <div class="text-white-50-custom text-center py-3">
            <i class="icon-base ti tabler-alert-circle me-1"></i>Data belum tersedia. Silakan isi form sebelumnya terlebih dahulu.
          </div>
          @endif
        </div>

        <div class="mt-3 mb-3">
          <div class="form-check">
            <input class="form-check-input form-check-input-custom" type="checkbox" id="konfirmasi_data" name="konfirmasi" value="1" x-model="form.konfirmasi" />
            <label class="form-check-label text-white-50-custom small" for="konfirmasi_data">
              Saya menyatakan bahwa data yang diisi adalah benar
            </label>
          </div>
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.konfirmasi }" x-text="errors.konfirmasi"></div>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('dashboard.peserta.form-minat') }}" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;">
          <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
        </a>
        <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="submitForm()" :disabled="submitting">
          <template x-if="!submitting">
            <span><i class="icon-base ti tabler-send me-1"></i> Submit</span>
          </template>
          <template x-if="submitting">
            <span><span class="spinner-border spinner-border-sm me-1" style="width:14px;height:14px;border-width:2px;"></span> Mengirim...</span>
          </template>
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
  document.addEventListener('alpine:init', function() {
    Alpine.data('dokumenForm', function() {
      return {
        form: {
          foto_profil: null,
          scan_ktp: null,
          konfirmasi: false,
        },
        errors: {},
        submitting: false,

        clearErrors() { this.errors = {}; },

        handleFileUpload(field, event) {
          var file = event.target.files[0];
          if (file) { this.form[field] = file; }
          var errs = this.errors;
          delete errs[field];
          this.errors = errs;
        },

        validate() {
          this.clearErrors();
          var errs = {};
          var valid = true;

          if (!this.form.foto_profil) { errs.foto_profil = 'Upload foto profil'; valid = false; }
          if (!this.form.scan_ktp) { errs.scan_ktp = 'Upload scan KTP'; valid = false; }
          if (!this.form.konfirmasi) { errs.konfirmasi = 'Centang pernyataan data benar'; valid = false; }

          this.errors = errs;
          return valid;
        },

        submitForm() {
          if (!this.validate()) return;
          this.submitting = true;
          document.getElementById('formDokumen').submit();
        },
      };
    });
  });
</script>
@endsection
