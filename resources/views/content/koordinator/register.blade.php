@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/publicLayout')

@section('title', 'Pendaftaran Koordinator Wilayah')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Sora:wght@400;500;600;700&display=swap');

  body {
    font-family: 'Outfit', sans-serif;
    background: #0b0f19;
    color: #f8fafc;
  }

  .register-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0b0f19;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.12) 0px, transparent 55%),
      radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.10) 0px, transparent 55%);
    padding: 40px 20px;
  }

  .register-card {
    width: 95%;
    max-width: 560px;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 5px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
    padding: 32px 24px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  /* HP & Tablet kecil */
  @media (min-width: 576px) {
    .register-card {
      width: 92%;
      max-width: 640px;
      padding: 36px 28px;
    }
  }

  /* Tablet besar & Laptop kecil */
  @media (min-width: 768px) {
    .register-card {
      width: 88%;
      max-width: 780px;
      padding: 40px 32px;
    }
  }

  /* Laptop & Desktop */
  @media (min-width: 992px) {
    .register-card {
      width: 85%;
      max-width: 960px;
      padding: 44px 40px;
    }
  }

  /* Desktop besar */
  @media (min-width: 1200px) {
    .register-card {
      width: 80%;
      max-width: 1080px;
      padding: 48px 44px;
    }
  }

  /* Layar sangat lebar */
  @media (min-width: 1600px) {
    .register-card {
      width: 75%;
      max-width: 1280px;
      padding: 52px 48px;
    }
  }

  .register-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6366f1, #d946ef, #f59e0b);
  }

  .register-header {
    text-align: center;
    margin-bottom: 32px;
  }

  .register-header .logo-icon {
    width: 56px;
    height: 56px;
    border-radius: 5px;
    background: linear-gradient(135deg, #6366f1, #d946ef);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
  }

  .register-header h3 {
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 6px;
  }

  .register-header p {
    color: rgba(255, 255, 255, 0.55);
    font-size: 0.9rem;
    margin-bottom: 0;
  }

  .form-label {
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.7) !important;
    margin-bottom: 6px;
  }

  .form-control, .form-select {
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 12px 14px !important;
    font-size: 0.95rem !important;
    transition: all 0.3s ease !important;
  }

  .form-control:focus, .form-select:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2) !important;
  }

  .form-control::placeholder {
    color: rgba(255, 255, 255, 0.3) !important;
  }

  .form-control.is-invalid, .form-select.is-invalid {
    border-color: #f87171 !important;
    box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.15) !important;
  }

  .form-control:-webkit-autofill,
  .form-control:-webkit-autofill:hover,
  .form-control:-webkit-autofill:focus,
  .form-control:-webkit-autofill:active,
  .form-select:-webkit-autofill,
  .form-select:-webkit-autofill:hover,
  .form-select:-webkit-autofill:focus,
  .form-select:-webkit-autofill:active {
    -webkit-text-fill-color: #ffffff !important;
    transition: background-color 5000s ease-in-out 0s;
    background-clip: padding-box !important;
    box-shadow: 0 0 0 1000px #131824 inset !important;
    -webkit-box-shadow: 0 0 0 1000px #131824 inset !important;
  }

  .invalid-feedback {
    color: #f87171 !important;
    font-size: 0.8rem;
  }

  .form-select option {
    background: #1e293b;
    color: #ffffff;
  }

  .btn-register {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border: none;
    color: #ffffff;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    padding: 14px 28px;
    border-radius: 5px;
    transition: all 0.3s ease;
    width: 100%;
  }

  .btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
    background: linear-gradient(135deg, #818cf8, #6366f1);
    color: #ffffff;
  }

  .input-group-text {
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: rgba(255, 255, 255, 0.6) !important;
    border-radius: 5px !important;
  }

  .input-group > :not(:last-child) { border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important; }
  .input-group > :not(:first-child) { border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; }

  .info-banner {
    background: rgba(99, 102, 241, 0.08);
    border: 1px solid rgba(99, 102, 241, 0.15);
    border-radius: 5px;
    padding: 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }

  .info-banner i { color: #818cf8; font-size: 1.3rem; margin-top: 2px; flex-shrink: 0; }
  .info-banner p { color: rgba(255, 255, 255, 0.65); font-size: 0.85rem; margin-bottom: 0; line-height: 1.5; }

  .login-link {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.9rem;
    transition: color 0.3s;
  }
  .login-link:hover { color: #818cf8; }
</style>
@endsection

@section('content')
<div class="register-wrapper">
  <div class="register-card">

    <!-- Header -->
    <div class="register-header">
      <div class="logo-icon">
        <i class="icon-base ti tabler-map-pin text-white fs-3"></i>
      </div>
      <h3>Pendaftaran Koordinator</h3>
      <p>Daftar sebagai koordinator wilayah Kecamatan Kota Bandung</p>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show mb-4 border-0"
        style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
          <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- Form -->
    <form action="{{ route('koordinator.register') }}" method="POST">
      @csrf

      <div class="row g-3">
        <!-- Baris 2 kolom: Nama Lengkap | No. WhatsApp -->
        <div class="col-md-6">
          <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
          <input type="text" id="whatsapp" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
            value="{{ old('whatsapp') }}" placeholder="Contoh: 0812xxxxxxxx" maxlength="20" required>
          <div id="wa-feedback" class="small mt-1 d-none"></div>
          <small id="wa-format-hint" style="color: rgba(255,255,255,0.4);">Format: 08xx atau 628xx</small>
          @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Baris 3 kolom: Email | Kecamatan | NIK -->
        <div class="col-md-4">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}" placeholder="contoh@email.com" required>
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">Wilayah Kecamatan <span class="text-danger">*</span></label>
          <select name="kecamatan_id" id="kecamatan_id" class="form-select @error('kecamatan_id') is-invalid @enderror" required>
            <option value="">— Pilih Kecamatan —</option>
            @foreach($kecamatans as $kec)
              <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                {{ $kec->name }}
              </option>
            @endforeach
          </select>
          @error('kecamatan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">NIK (Username Login) <span class="text-danger">*</span></label>
          <input type="text" id="nik" name="nik" class="form-control @error('nik') is-invalid @enderror"
            value="{{ old('nik') }}" placeholder="15-16 digit NIK" maxlength="16" required>
          <div id="nik-feedback" class="small mt-1 d-none"></div>
          @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Kelurahan -->
        <div class="col-12">
          <label for="kelurahan_id" class="form-label">Kelurahan <span class="text-danger">*</span></label>
          <select class="form-select @error('kelurahan_id') is-invalid @enderror"
            id="kelurahan_id" name="kelurahan_id" disabled required>
            <option value="">-- Pilih Kecamatan Dahulu --</option>
          </select>
          @error('kelurahan_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

      </div>

      <!-- Info Banner -->
      <div class="info-banner mt-4 mb-4">
        <i class="icon-base ti tabler-info-circle"></i>
        <div>
          <p>🔑 Password akan dibuat otomatis dan dikirim ke WhatsApp. Login menggunakan <strong>NIK</strong> sebagai username dan password yang dikirimkan. Setelah mendaftar, akun akan diverifikasi admin. Silakan tunggu notifikasi aktivasi.</p>
        </div>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn btn-register">
        <i class="icon-base ti tabler-user-plus me-2"></i> Daftar Sebagai Koordinator
      </button>

      <!-- Login Link -->
      <div class="text-center mt-4">
        <span style="color: rgba(255,255,255,0.4); font-size: 0.9rem;">Sudah punya akun? </span>
        <a href="{{ route('login') }}" class="login-link fw-semibold">Login di sini</a>
      </div>

      <div class="text-center mt-2">
        <span style="color: rgba(255,255,255,0.4); font-size: 0.85rem;">Bukan koordinator? </span>
        <a href="{{ url('/') }}" class="login-link fw-semibold">Kembali ke Beranda</a>
      </div>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // ============================================================
  // WHATSAPP INPUT — Auto-convert & Check WA Registration
  // ============================================================
  const waInput = document.getElementById('whatsapp');
  const waFeedback = document.getElementById('wa-feedback');
  const waHint = document.getElementById('wa-format-hint');
  let waRegistered = null;

  function convertWaNumber(num) {
    num = num.replace(/\D/g, '');
    if (num.startsWith('0')) {
      return '62' + num.substring(1);
    }
    if (num.startsWith('62') && num.length >= 10) {
      return num;
    }
    return '62' + num;
  }

  if (waInput) {
    let waTimeout = null;

    waInput.addEventListener('input', function() {
      clearTimeout(waTimeout);

      const raw = this.value.replace(/\D/g, '');
      this.value = raw;

      if (raw.length >= 4) {
        const converted = convertWaNumber(raw);
        waHint.textContent = '→ ' + converted + ' (format internasional)';
      } else {
        waHint.textContent = 'Format: 08xx atau 628xx';
      }

      if (raw.length < 8) {
        waFeedback.classList.add('d-none');
        waFeedback.className = 'small mt-1 d-none';
        waFeedback.textContent = '';
        waRegistered = null;
        return;
      }

      waFeedback.className = 'small mt-1 d-flex align-items-center text-info';
      waFeedback.innerHTML = '<span class="spinner-border spinner-border-xs me-1" style="width:12px;height:12px;border-width:2px;"></span> Memeriksa nomor WhatsApp...';
      waFeedback.classList.remove('d-none');

      waTimeout = setTimeout(function() {
        const finalNumber = convertWaNumber(raw);
        fetch('{{ route('landing.check-wa') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ number: finalNumber })
        })
        .then(res => res.json())
        .then(data => {
          waFeedback.classList.remove('d-none');
          if (data.exists) {
            waFeedback.className = 'small mt-1 d-flex align-items-center text-success';
            waFeedback.innerHTML = '<i class="icon-base ti tabler-brand-whatsapp me-1"></i> Nomor WhatsApp terdaftar ✓';
            waRegistered = true;
          } else {
            waFeedback.className = 'small mt-1 d-flex align-items-center text-danger';
            waFeedback.innerHTML = '<i class="icon-base ti tabler-alert-triangle me-1"></i> Nomor WhatsApp tidak terdaftar. Pastikan nomor ini aktif di WA.';
            waRegistered = false;
          }
        })
        .catch(function() {
          waFeedback.className = 'small mt-1 d-flex align-items-center text-warning';
          waFeedback.innerHTML = '<i class="icon-base ti tabler-cloud-off me-1"></i> Gagal memverifikasi WA, tapi kamu tetap bisa daftar.';
          waRegistered = true;
        });
      }, 600);
    });
  }

  // ============================================================
  // NIK INPUT — Auto filter & Check via AJAX
  // ============================================================
  const nikInput = document.getElementById('nik');
  const nikFeedback = document.getElementById('nik-feedback');

  if (nikInput) {
    let nikTimeout = null;

    nikInput.addEventListener('input', function() {
      clearTimeout(nikTimeout);

      const nik = this.value.replace(/\D/g, '');
      this.value = nik;

      if (nik.length < 15 || nik.length > 16) {
        nikFeedback.classList.add('d-none');
        nikFeedback.className = 'small mt-1 d-none';
        nikFeedback.textContent = '';
        return;
      }

      nikFeedback.className = 'small mt-1 d-flex align-items-center text-info';
      nikFeedback.innerHTML = '<span class="spinner-border spinner-border-xs me-1" style="width:12px;height:12px;border-width:2px;"></span> Memeriksa NIK...';
      nikFeedback.classList.remove('d-none');

      nikTimeout = setTimeout(function() {
        fetch('{{ route('landing.check-nik') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ nik: nik })
        })
        .then(res => res.json())
        .then(data => {
          nikFeedback.classList.remove('d-none');
          if (data.exists) {
            nikFeedback.className = 'small mt-1 d-flex align-items-center text-danger';
            nikFeedback.innerHTML = '<i class="icon-base ti tabler-alert-circle me-1"></i> NIK sudah terdaftar!';
          } else {
            nikFeedback.className = 'small mt-1 d-flex align-items-center text-success';
            nikFeedback.innerHTML = '<i class="icon-base ti tabler-check-circle me-1"></i> NIK tersedia ✓';
            setTimeout(function() {
              nikFeedback.classList.add('d-none');
            }, 3000);
          }
        })
        .catch(function() {
          nikFeedback.classList.add('d-none');
        });
      }, 500);
    });

    // ============================================================
    // KECAMATAN -> KELURAHAN Dependent Dropdown
    // ============================================================
    const kecamatanSelect = document.getElementById('kecamatan_id');
    const kelurahanSelect = document.getElementById('kelurahan_id');

    if (kecamatanSelect && kelurahanSelect) {
      kecamatanSelect.addEventListener('change', function() {
        const kecamatanId = this.value;
        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        kelurahanSelect.disabled = true;

        if (!kecamatanId) {
          kelurahanSelect.innerHTML = '<option value="">-- Pilih Kecamatan Dahulu --</option>';
          return;
        }

        fetch('/api/kelurahan?kecamatan_id=' + kecamatanId)
          .then(function(res) {
            if (!res.ok) throw new Error('Gagal memuat data');
            return res.json();
          })
          .then(function(data) {
            data.forEach(function(k) {
              const opt = document.createElement('option');
              opt.value = k.id;
              opt.textContent = k.name;
              kelurahanSelect.appendChild(opt);
            });
            kelurahanSelect.disabled = false;
          })
          .catch(function() {
            kelurahanSelect.innerHTML = '<option value="">— Gagal memuat data —</option>';
            kelurahanSelect.disabled = false;
          });
      });

      // Trigger on page load jika ada old value (setelah validasi error)
      if (kecamatanSelect.value) {
        kecamatanSelect.dispatchEvent(new Event('change'));
      }
    }
  }
});
</script>
@endsection
