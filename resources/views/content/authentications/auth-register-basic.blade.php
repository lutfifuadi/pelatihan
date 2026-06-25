@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Register Basic - Pages')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/@form-validation/popular.js',
'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
@vite(['resources/assets/js/pages-auth.js'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
      <!-- Register Card -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-6">
            <a href="{{ url('/') }}" class="app-brand-link">
              <span class="app-brand-logo demo">@include('_partials.macros')</span>
              <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">Daftar Akun Baru 🚀</h4>
          <p class="mb-6">Mulai petualangan belajar Anda!</p>

          <!-- Error Messages -->
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form id="formAuthentication" class="mb-6" action="{{ route('auth-register') }}" method="POST">
            @csrf
            <div class="mb-6 form-control-validation">
              <label for="name" class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                placeholder="Masukkan nama lengkap" autofocus required />
            </div>
            <div class="mb-6 form-control-validation">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                placeholder="Masukkan email" required />
            </div>
            <div class="mb-6 form-control-validation">
              <label for="phone" class="form-label">No. Telepon (opsional)</label>
              <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}"
                placeholder="Masukkan no. telepon" />
            </div>
            
            {{-- NIK --}}
            <div class="mb-6 form-control-validation">
              <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
              <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik') }}"
                placeholder="Masukkan 16 digit NIK" required maxlength="16" />
            </div>

            {{-- Status Tokoh --}}
            <div class="mb-6">
              <label class="form-label">Keterlibatan di Lingkungan Sekitar</label>
              <div class="d-flex flex-wrap">
                <div class="form-check me-3 me-lg-4">
                  <input class="form-check-input" type="radio" name="status_tokoh" id="status_tokoh_kader" value="Kader" required {{ old('status_tokoh') == 'Kader' ? 'checked' : '' }}>
                  <label class="form-check-label" for="status_tokoh_kader">Kader</label>
                </div>
                <div class="form-check me-3 me-lg-4">
                  <input class="form-check-input" type="radio" name="status_tokoh" id="status_tokoh_relawan" value="Relawan" {{ old('status_tokoh') == 'Relawan' ? 'checked' : '' }}>
                  <label class="form-check-label" for="status_tokoh_relawan">Relawan</label>
                </div>
                <div class="form-check me-3 me-lg-4">
                  <input class="form-check-input" type="radio" name="status_tokoh" id="status_tokoh_simpatisan" value="Simpatisan" {{ old('status_tokoh') == 'Simpatisan' ? 'checked' : '' }}>
                  <label class="form-check-label" for="status_tokoh_simpatisan">Simpatisan</label>
                </div>
                <div class="form-check me-3 me-lg-4">
                  <input class="form-check-input" type="radio" name="status_tokoh" id="status_tokoh_umum" value="Masyarakat Umum" {{ old('status_tokoh') == 'Masyarakat Umum' ? 'checked' : '' }}>
                  <label class="form-check-label" for="status_tokoh_umum">Umum</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="status_tokoh" id="status_tokoh_lainnya" value="Lainnya" {{ old('status_tokoh') == 'Lainnya' ? 'checked' : '' }}>
                  <label class="form-check-label" for="status_tokoh_lainnya">Lainnya</label>
                </div>
              </div>
            </div>

            {{-- Sumber Informasi --}}
            <div class="mb-6 form-control-validation">
                <label for="sumber_informasi" class="form-label">Sumber Informasi</label>
                <select id="sumber_informasi" name="sumber_informasi" class="form-select" required>
                    <option value="">Pilih Sumber Informasi</option>
                    <option value="koordinator" {{ old('sumber_informasi') == 'koordinator' ? 'selected' : '' }}>Nama Koordinator</option>
                    <option value="sosmed" {{ old('sumber_informasi') == 'sosmed' ? 'selected' : '' }}>Sosial Media</option>
                    <option value="lainnya" {{ old('sumber_informasi') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="mb-6 form-control-validation" id="sumber_informasi_detail_wrapper" style="display: none;">
                <label for="sumber_informasi_detail" class="form-label">Sebutkan (Koordinator/Lainnya)</label>
                <input type="text" class="form-control" id="sumber_informasi_detail" name="sumber_informasi_detail" value="{{ old('sumber_informasi_detail') }}" placeholder="Tuliskan nama koordinator atau sumber lainnya" />
            </div>

            <script>
              document.addEventListener('DOMContentLoaded', function () {
                const sumberInformasi = document.getElementById('sumber_informasi');
                const detailWrapper = document.getElementById('sumber_informasi_detail_wrapper');
                
                function toggleDetail() {
                  if (sumberInformasi.value === 'koordinator' || sumberInformasi.value === 'lainnya') {
                    detailWrapper.style.display = 'block';
                  } else {
                    detailWrapper.style.display = 'none';
                  }
                }

                sumberInformasi.addEventListener('change', toggleDetail);
                toggleDetail(); // Run on page load
              });
            </script>

            <div class="mb-6 form-password-toggle form-control-validation">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password" required />
                <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
              </div>
            </div>
            <div class="mb-6 form-password-toggle form-control-validation">
              <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password_confirmation" required />
                <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
              </div>
            </div>
            {{-- Placeholder for Live Capture Components --}}
            <div class="mb-6 p-4 border rounded">
                <h5>Upload Foto Diri (Live)</h5>
                <div id="photo-capture-placeholder" class="text-center bg-light p-5">
                    <p class="text-muted">[Komponen Live Capture Foto Diri akan ditempatkan di sini]</p>
                </div>
            </div>

            <div class="mb-6 p-4 border rounded">
                <h5>Upload Foto KTP (Live)</h5>
                <div id="ktp-capture-placeholder" class="text-center bg-light p-5">
                    <p class="text-muted">[Komponen Live Capture KTP akan ditempatkan di sini]</p>
                </div>
            </div>
            
            <button class="btn btn-primary d-grid w-100" type="submit">Daftar</button>
          </form>

          <p class="text-center">
            <span>Sudah punya akun?</span>
            <a href="{{ route('login') }}">
              <span>Login di sini</span>
            </a>
          </p>

          <div class="divider my-6">
            <div class="divider-text">or</div>
          </div>

          <div class="d-flex justify-content-center">
            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-facebook me-1_5">
              <i class="icon-base ti tabler-brand-facebook-filled icon-20px"></i>
            </a>

            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-twitter me-1_5">
              <i class="icon-base ti tabler-brand-twitter-filled icon-20px"></i>
            </a>

            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-github me-1_5">
              <i class="icon-base ti tabler-brand-github-filled icon-20px"></i>
            </a>

            <a href="javascript:;" class="btn btn-icon rounded-circle btn-text-google-plus">
              <i class="icon-base ti tabler-brand-google-filled icon-20px"></i>
            </a>
          </div>
        </div>
      </div>
      <!-- Register Card -->
    </div>
  </div>
</div>
@endsection
