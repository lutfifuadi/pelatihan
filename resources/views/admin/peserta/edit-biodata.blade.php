@php
  $configData = Helper::appClasses();
  $indoMonths = [
      1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
      5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
      9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
  ];
  $jawaban = $profile?->jawaban_pertanyaan ?? [];
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Edit Biodata Peserta')

@section('page-style')
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');
      .content-wrapper { font-family: 'Outfit', sans-serif; color: #f8fafc; position: relative !important; overflow: hidden !important; }
      .content-wrapper h1,.content-wrapper h2,.content-wrapper h3,.content-wrapper h4,.content-wrapper h5,.content-wrapper h6 { font-family: 'Sora', sans-serif; }
      html,body,.layout-page,.content-wrapper,.layout-wrapper,.layout-container { background-color: #0b0f19 !important; background-image: radial-gradient(at 0% 0%, rgba(99,102,241,0.15) 0px, transparent 55%), radial-gradient(at 100% 0%, rgba(139,92,246,0.15) 0px, transparent 55%), radial-gradient(at 50% 50%, rgba(236,72,153,0.08) 0px, transparent 50%) !important; color: #f8fafc !important; }
      .layout-navbar-fixed .layout-page::before { display: none !important; }
      .content-wrapper > .container-xxl { max-width: 100% !important; padding: 0 !important; }
      .layout-menu,#layout-menu { background-color: #0b0f19 !important; border-right: 1px solid rgba(255,255,255,0.08) !important; }
      .layout-menu .app-brand,.layout-menu .menu-inner { background-color: #0b0f19 !important; }
      .layout-menu .menu-link { color: rgba(255,255,255,0.7) !important; }
      .layout-menu .menu-item.active > .menu-link { color: #ffffff !important; background: linear-gradient(135deg, #6366f1, #d946ef) !important; box-shadow: 0 4px 15px rgba(99,102,241,0.3) !important; }
      .layout-menu .menu-link:hover { background-color: rgba(255,255,255,0.04) !important; color: #ffffff !important; }
      .layout-navbar,#layout-navbar { background: rgba(15,23,42,0.45) !important; backdrop-filter: blur(20px) !important; border: 1px solid rgba(255,255,255,0.08) !important; box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important; }
      .glow-orb { position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.4; mix-blend-mode: screen; pointer-events: none; animation: orbFloat 25s infinite alternate ease-in-out; z-index: 0; }
      .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, rgba(99,102,241,0) 70%); top: -10%; left: -10%; animation-duration: 20s; }
      .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, rgba(236,72,153,0) 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
      .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #06b6d4 0%, rgba(6,182,212,0) 70%); top: 35%; left: 25%; animation-duration: 24s; }
      @keyframes orbFloat { 0% { transform: translate(0,0) scale(1) rotate(0deg); } 50% { transform: translate(60px,40px) scale(1.08) rotate(180deg); } 100% { transform: translate(-30px,-50px) scale(0.92) rotate(360deg); } }
      .text-body-premium { color: rgba(255,255,255,0.65) !important; }
      .glass-card-premium { background: rgba(15,23,42,0.25) !important; backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.08) !important; box-shadow: 0 20px 60px rgba(0,0,0,0.4); border-radius: 5px !important; position: relative; transition: all 0.4s cubic-bezier(0.4,0,0.2,1); z-index: 1; }
      .stat-icon-box { width: 52px; height: 52px; border-radius: 5px !important; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; }
      .stat-icon-primary { background: rgba(99,102,241,0.12); color: #6366f1; }
      .stat-icon-warning { background: rgba(245,158,11,0.12); color: #f59e0b; }
      .stat-icon-success { background: rgba(16,185,129,0.12); color: #10b981; }
      .btn-glow-premium { background: linear-gradient(135deg, #ffc107, #ff9800) !important; border: none; color: #0b0f19 !important; font-family: 'Sora', sans-serif; font-weight: 700; border-radius: 5px; box-shadow: 0 4px 15px rgba(255,152,0,0.2); transition: all 0.3s ease; }
      .btn-glow-premium:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(255,152,0,0.4); background: linear-gradient(135deg, #ffca28, #ffa726) !important; color: #0b0f19 !important; }
      .btn-secondary-custom { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #ffffff; font-family: 'Sora', sans-serif; font-weight: 600; border-radius: 5px; transition: all 0.3s ease; }
      .btn-secondary-custom:hover { background: rgba(255,255,255,0.1); color: #ffffff; }
      .form-control,.form-select,textarea.form-control { background: rgba(255,255,255,0.03) !important; border: 1px solid rgba(255,255,255,0.12) !important; color: #ffffff !important; border-radius: 5px !important; }
      .form-control:focus,.form-select:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 4px rgba(99,102,241,0.15) !important; outline: none !important; }
      .form-control::placeholder,textarea::placeholder { color: rgba(255,255,255,0.25) !important; }
      .form-label { color: rgba(255,255,255,0.6); font-weight: 600; font-size: 0.75rem; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 0.5rem; }
      .form-text { color: rgba(255,255,255,0.35) !important; font-size: 0.75rem; }
      .input-group-text { background: rgba(255,255,255,0.05) !important; border: 1px solid rgba(255,255,255,0.12) !important; color: rgba(255,255,255,0.5) !important; }
      .form-check-input { background-color: rgba(255,255,255,0.05) !important; border: 1px solid rgba(255,255,255,0.2) !important; }
      .form-check-input:checked { background-color: #6366f1 !important; border-color: #6366f1 !important; }
      .form-check-label { color: rgba(255,255,255,0.7); }
      .form-switch .form-check-input { width: 2.5em; height: 1.25em; }
      .section-title { font-family: 'Sora', sans-serif; font-weight: 700; color: rgba(255,255,255,0.85); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 0.5rem; }
      .section-title i { color: #6366f1; font-size: 1.1rem; }
      .badge-premium { display: inline-flex; align-items: center; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.8); border-radius: 5px; padding: 3px 10px; font-size: 0.75rem; font-weight: 500; }
      .badge-premium-info { background: rgba(96,165,250,0.15); border-color: rgba(96,165,250,0.3); color: #93c5fd; }
      .badge-premium-warning { background: rgba(245,158,11,0.15); border-color: rgba(245,158,11,0.3); color: #fbbf24; }
      .detail-divider { border-color: rgba(255,255,255,0.06); margin: 1.25rem 0; }
      select option { background-color: #0b0f19; color: #f8fafc; }
      
      /* Readonly styling */
      .view-label { font-size: 0.75rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }
      .view-value { font-size: 0.95rem; color: #ffffff; font-weight: 500; min-height: 1.5rem; }
      [x-cloak] { display: none !important; }
    </style>
@endsection

@section('content')
    <div class="glow-orb orb-1"></div>
    <div class="glow-orb orb-2"></div>
    <div class="glow-orb orb-3"></div>
    <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index:1;">

      {{-- Header Card --}}
      <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-warning">
              <i class="icon-base ti tabler-edit fs-4"></i>
            </div>
            <div>
              <h4 class="fw-bold text-white mb-0">Edit Biodata Peserta</h4>
              <p class="text-body-premium mb-0 mt-1" style="font-size:0.95rem;">
                <span class="badge-premium badge-premium-info me-2">ID: {{ $user->id }}</span>
                {{ $profile?->nama_lengkap ?? ($user->name ?? '-') }}
                @if($user->status_tokoh)
                  <span class="ms-2 badge-premium badge-premium-warning">★ Tokoh</span>
                @endif
              </p>
            </div>
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('admin.peserta.show', $user) }}" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-arrow-left"></i> Kembali ke Detail
            </a>
          </div>
        </div>
      </div>

      {{-- ────────────────────────────────────────────────────────────────────────
           SEKSI 1: IDENTITAS & KONTAK
           ──────────────────────────────────────────────────────────────────────── --}}
      <div class="glass-card-premium px-4 py-4 mb-4" x-data="cardIdentitas()" x-cloak>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="section-title mb-0">
            <i class="icon-base ti tabler-user"></i> Identitas &amp; Kontak
          </h5>
          <button type="button" class="btn btn-sm btn-secondary-custom" @click="toggleEdit()" x-show="!editMode">
            <i class="icon-base ti tabler-edit me-1"></i> Edit
          </button>
          <button type="button" class="btn btn-sm btn-secondary-custom text-danger" @click="toggleEdit()" x-show="editMode">
            <i class="icon-base ti tabler-x me-1"></i> Batal
          </button>
        </div>
        <hr class="detail-divider">

        {{-- VIEW MODE --}}
        <div x-show="!editMode" class="row g-3">
          <div class="col-md-6">
            <div class="view-label">Nama Lengkap</div>
            <div class="view-value">{{ $profile?->nama_lengkap ?? ($user->name ?? '-') }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">NIK</div>
            <div class="view-value">{{ $user->nik ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Jenis Kelamin</div>
            <div class="view-value">{{ $profile?->jenis_kelamin ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Tempat &amp; Tanggal Lahir</div>
            <div class="view-value">
              @if(($profile?->tempat_lahir ?? '') || ($profile?->tanggal_lahir ?? ''))
                {{ $profile?->tempat_lahir ?? '-' }}, {{ $profile?->tanggal_lahir ?? '' }} {{ $indoMonths[(int) ($profile?->bulan_lahir ?? 0)] ?? ($profile?->bulan_lahir ?? '') }} {{ $profile?->tahun_lahir ?? '' }}
              @else
                -
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Email</div>
            <div class="view-value">{{ $user->email ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Nomor WhatsApp / Telepon</div>
            <div class="view-value">{{ $user->whatsapp ?? ($user->phone ?? '-') }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Status Tokoh</div>
            <div class="view-value">
              @if($user->status_tokoh)
                <span class="badge bg-label-warning">★ Tokoh / Figur Publik</span>
              @else
                Bukan Tokoh
              @endif
            </div>
          </div>
          @if(($profile?->link_medsos ?? null) && count($profile->link_medsos) > 0)
            <div class="col-12">
              <div class="view-label">Media Sosial</div>
              <div class="d-flex gap-2 flex-wrap mt-1">
                @foreach($profile->link_medsos as $item)
                  <span class="badge bg-label-secondary">
                    {{ $item['platform'] ?? '' }}: {{ $item['url'] ?? '' }}
                  </span>
                @endforeach
              </div>
            </div>
          @endif
        </div>

        {{-- EDIT MODE --}}
        <form x-show="editMode" @submit.prevent="submitForm($event)" action="{{ route('admin.peserta.update-biodata', $user->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="row g-3">
            {{-- Nama Lengkap (Synced with name) --}}
            <div class="col-md-6">
              <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control form-control-uppercase" :class="{'is-invalid': errors.nama_lengkap}" value="{{ $profile?->nama_lengkap ?? ($user->name ?? '') }}" oninput="this.value = this.value.toUpperCase()" required>
              <template x-if="errors.nama_lengkap">
                <div class="invalid-feedback" x-text="errors.nama_lengkap[0]"></div>
              </template>
            </div>

            {{-- NIK --}}
            <div class="col-md-6">
              <label for="nik" class="form-label">NIK</label>
              <input type="text" id="nik" name="nik" class="form-control" :class="{'is-invalid': errors.nik}" value="{{ $user->nik ?? '' }}" maxlength="16" inputmode="numeric" @input="$el.value = $el.value.replace(/[^0-9]/g, '')">
              <template x-if="errors.nik">
                <div class="invalid-feedback" x-text="errors.nik[0]"></div>
              </template>
            </div>

            {{-- Jenis Kelamin --}}
            <div class="col-md-6">
              <label class="form-label">Jenis Kelamin</label>
              <div class="d-flex gap-4 mt-1">
                <div class="form-check">
                  <input type="radio" id="jk_laki" name="jenis_kelamin" value="Laki-laki" class="form-check-input" {{ ($profile?->jenis_kelamin ?? '') === 'Laki-laki' ? 'checked' : '' }}>
                  <label class="form-check-label" for="jk_laki">Laki-laki</label>
                </div>
                <div class="form-check">
                  <input type="radio" id="jk_perempuan" name="jenis_kelamin" value="Perempuan" class="form-check-input" {{ ($profile?->jenis_kelamin ?? '') === 'Perempuan' ? 'checked' : '' }}>
                  <label class="form-check-label" for="jk_perempuan">Perempuan</label>
                </div>
              </div>
              <template x-if="errors.jenis_kelamin">
                <div class="text-danger mt-1" style="font-size:0.75rem;" x-text="errors.jenis_kelamin[0]"></div>
              </template>
            </div>

            {{-- Tempat Lahir --}}
            <div class="col-md-6">
              <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
              <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" :class="{'is-invalid': errors.tempat_lahir}" value="{{ $profile?->tempat_lahir ?? '' }}">
              <template x-if="errors.tempat_lahir">
                <div class="invalid-feedback" x-text="errors.tempat_lahir[0]"></div>
              </template>
            </div>

            {{-- Tanggal Lahir --}}
            <div class="col-md-4">
              <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
              <input type="number" id="tanggal_lahir" name="tanggal_lahir" class="form-control" :class="{'is-invalid': errors.tanggal_lahir}" value="{{ $profile?->tanggal_lahir ?? '' }}" min="1" max="31">
              <template x-if="errors.tanggal_lahir">
                <div class="invalid-feedback" x-text="errors.tanggal_lahir[0]"></div>
              </template>
            </div>
            <div class="col-md-4">
              <label for="bulan_lahir" class="form-label">Bulan Lahir</label>
              <select id="bulan_lahir" name="bulan_lahir" class="form-select" :class="{'is-invalid': errors.bulan_lahir}">
                <option value="">- Bulan -</option>
                @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $nama)
                  <option value="{{ $num }}" {{ ($profile?->bulan_lahir ?? '') == $num ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
              </select>
              <template x-if="errors.bulan_lahir">
                <div class="invalid-feedback" x-text="errors.bulan_lahir[0]"></div>
              </template>
            </div>
            <div class="col-md-4">
              <label for="tahun_lahir" class="form-label">Tahun Lahir</label>
              <input type="number" id="tahun_lahir" name="tahun_lahir" class="form-control" :class="{'is-invalid': errors.tahun_lahir}" value="{{ $profile?->tahun_lahir ?? '' }}" min="1900" max="{{ date('Y') }}">
              <template x-if="errors.tahun_lahir">
                <div class="invalid-feedback" x-text="errors.tahun_lahir[0]"></div>
              </template>
            </div>

            {{-- Email --}}
            <div class="col-md-6">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" id="email" name="email" class="form-control" :class="{'is-invalid': errors.email}" value="{{ $user->email ?? '' }}" required>
              <template x-if="errors.email">
                <div class="invalid-feedback" x-text="errors.email[0]"></div>
              </template>
            </div>

            {{-- WhatsApp/Phone (Synced) --}}
            <div class="col-md-6">
              <label for="whatsapp" class="form-label">Nomor WhatsApp / Telepon</label>
              <input type="text" id="whatsapp" name="whatsapp" class="form-control" :class="{'is-invalid': errors.whatsapp}" value="{{ $user->whatsapp ?? ($user->phone ?? '') }}" maxlength="15" inputmode="tel" @input="$el.value = $el.value.replace(/[^0-9]/g, '')">
              <template x-if="errors.whatsapp">
                <div class="invalid-feedback" x-text="errors.whatsapp[0]"></div>
              </template>
            </div>

            {{-- Status Tokoh --}}
            <div class="col-md-6">
              <label class="form-label">Status Tokoh</label>
              <div class="form-check form-switch mt-1">
                <input type="hidden" name="status_tokoh" value="0">
                <input type="checkbox" id="status_tokoh" name="status_tokoh" value="1" class="form-check-input" {{ ($user->status_tokoh ?? false) ? 'checked' : '' }} role="switch">
                <label class="form-check-label" for="status_tokoh">Tandai sebagai Tokoh / Figur Publik</label>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-secondary-custom" @click="toggleEdit()" :disabled="isSubmitting">Batal</button>
            <button type="submit" class="btn btn-glow-premium d-flex align-items-center gap-2" :disabled="isSubmitting">
              <template x-if="!isSubmitting">
                <span><i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Perubahan</span>
              </template>
              <template x-if="isSubmitting">
                <span class="d-flex align-items-center gap-2"><span class="spinner-border spinner-border-sm"></span> Menyimpan...</span>
              </template>
            </button>
          </div>
        </form>
      </div>

      {{-- ────────────────────────────────────────────────────────────────────────
           SEKSI 2: ALAMAT KTP & WILAYAH
           ──────────────────────────────────────────────────────────────────────── --}}
      <div class="glass-card-premium px-4 py-4 mb-4" x-data="cardAlamat()" x-cloak>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="section-title mb-0">
            <i class="icon-base ti tabler-map-pin"></i> Alamat
          </h5>
          <button type="button" class="btn btn-sm btn-secondary-custom" @click="toggleEdit()" x-show="!editMode">
            <i class="icon-base ti tabler-edit me-1"></i> Edit
          </button>
          <button type="button" class="btn btn-sm btn-secondary-custom text-danger" @click="toggleEdit()" x-show="editMode">
            <i class="icon-base ti tabler-x me-1"></i> Batal
          </button>
        </div>
        <hr class="detail-divider">

        {{-- VIEW MODE --}}
        <div x-show="!editMode" class="row g-3">
          <div class="col-12">
            <div class="view-label">Alamat Lengkap (KTP)</div>
            <div class="view-value">{{ $profile?->alamat_ktp ?? '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="view-label">RT</div>
            <div class="view-value">{{ $profile?->rt ?? '-' }}</div>
          </div>
          <div class="col-md-3">
            <div class="view-label">RW</div>
            <div class="view-value">{{ $profile?->rw ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Kode Pos</div>
            <div class="view-value">{{ $profile?->kodepos ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Kelurahan</div>
            <div class="view-value">{{ $profile?->kelurahan ?? ($user->kelurahan->name ?? '-') }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Kecamatan</div>
            <div class="view-value">{{ $profile?->kecamatan ?? ($user->kecamatan->name ?? '-') }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Kota / Kabupaten</div>
            <div class="view-value">{{ $profile?->kota ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Provinsi</div>
            <div class="view-value">{{ $profile?->provinsi ?? '-' }}</div>
          </div>
        </div>

        {{-- EDIT MODE --}}
        <form x-show="editMode" @submit.prevent="submitForm($event)" action="{{ route('admin.peserta.update-biodata', $user->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="row g-3">
            {{-- Alamat KTP --}}
            <div class="col-12">
              <label for="alamat_ktp" class="form-label">Alamat KTP</label>
              <textarea id="alamat_ktp" name="alamat_ktp" rows="3" class="form-control" :class="{'is-invalid': errors.alamat_ktp}">{{ $profile?->alamat_ktp ?? '' }}</textarea>
              <template x-if="errors.alamat_ktp">
                <div class="invalid-feedback" x-text="errors.alamat_ktp[0]"></div>
              </template>
            </div>

            {{-- RT & RW --}}
            <div class="col-md-3">
              <label for="rt" class="form-label">RT</label>
              <input type="text" id="rt" name="rt" class="form-control" :class="{'is-invalid': errors.rt}" value="{{ $profile?->rt ?? '' }}" maxlength="3" inputmode="numeric" @input="$el.value = $el.value.replace(/[^0-9]/g, '')">
              <template x-if="errors.rt">
                <div class="invalid-feedback" x-text="errors.rt[0]"></div>
              </template>
            </div>
            <div class="col-md-3">
              <label for="rw" class="form-label">RW</label>
              <input type="text" id="rw" name="rw" class="form-control" :class="{'is-invalid': errors.rw}" value="{{ $profile?->rw ?? '' }}" maxlength="3" inputmode="numeric" @input="$el.value = $el.value.replace(/[^0-9]/g, '')">
              <template x-if="errors.rw">
                <div class="invalid-feedback" x-text="errors.rw[0]"></div>
              </template>
            </div>
            <div class="col-md-6">
              <label for="kodepos" class="form-label">Kode Pos</label>
              <input type="text" id="kodepos" name="kodepos" class="form-control" :class="{'is-invalid': errors.kodepos}" value="{{ $profile?->kodepos ?? '' }}" maxlength="5" inputmode="numeric" @input="$el.value = $el.value.replace(/[^0-9]/g, '')">
              <template x-if="errors.kodepos">
                <div class="invalid-feedback" x-text="errors.kodepos[0]"></div>
              </template>
            </div>

            <div class="col-12"><hr class="my-2"></div>
            <div class="col-12">
              <h6 class="text-muted fw-semibold mb-0 text-uppercase" style="font-size:0.75rem; letter-spacing:0.05em;">
                Referensi Wilayah (Dropdown Sistem)
              </h6>
            </div>

            {{-- Provinsi --}}
            <div class="col-md-6">
              <label for="provinsi_select" class="form-label">Provinsi</label>
              <select id="provinsi_select" name="provinsi" class="form-select" x-model="provinsi">
                <option value="">-- Pilih Provinsi --</option>
                <option value="Jawa Barat">Jawa Barat</option>
              </select>
            </div>

            {{-- Kota --}}
            <div class="col-md-6">
              <label for="kota_select" class="form-label">Kota / Kabupaten</label>
              <select id="kota_select" name="kota" class="form-select" x-model="kota" :disabled="!provinsi">
                <option value="">-- Pilih Kota --</option>
                <template x-if="provinsi === 'Jawa Barat'">
                  <option value="BANDUNG">BANDUNG</option>
                </template>
              </select>
            </div>

            {{-- Kecamatan (Sistem) --}}
            <div class="col-md-6">
              <label for="kecamatan_id" class="form-label">Kecamatan (Referensi Sistem)</label>
              <select id="kecamatan_id" name="kecamatan_id" class="form-select" :class="{'is-invalid': errors.kecamatan_id}" x-model="kecamatanId">
                <option value="">-- Pilih Kecamatan --</option>
                @foreach ($kecamatanList as $kec)
                  <option value="{{ $kec->id }}">{{ $kec->name }}</option>
                @endforeach
              </select>
              <template x-if="errors.kecamatan_id">
                <div class="invalid-feedback" x-text="errors.kecamatan_id[0]"></div>
              </template>
            </div>

            {{-- Kelurahan (Sistem) --}}
            <div class="col-md-6">
              <label for="kelurahan_id" class="form-label">Kelurahan (Referensi Sistem)</label>
              <select id="kelurahan_id" name="kelurahan_id" class="form-select" :class="{'is-invalid': errors.kelurahan_id}" x-model="kelurahanId" :disabled="isLoadingKelurahan || !kecamatanId">
                <option value="">-- Pilih Kelurahan --</option>
                <template x-for="kel in kelurahans" :key="kel.id">
                  <option :value="kel.id" :selected="kel.id == kelurahanId" x-text="kel.name"></option>
                </template>
              </select>
              <template x-if="isLoadingKelurahan">
                <div class="form-text text-info mt-1"><span class="spinner-border spinner-border-sm me-1"></span>Memuat kelurahan...</div>
              </template>
              <template x-if="errors.kelurahan_id">
                <div class="invalid-feedback" x-text="errors.kelurahan_id[0]"></div>
              </template>
            </div>

            {{-- Hidden fields to preserve text version kelurahan/kecamatan --}}
            <input type="hidden" name="kelurahan" :value="getSelectedKelurahanName()">
            <input type="hidden" name="kecamatan" :value="getSelectedKecamatanName()">
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-secondary-custom" @click="toggleEdit()" :disabled="isSubmitting">Batal</button>
            <button type="submit" class="btn btn-glow-premium d-flex align-items-center gap-2" :disabled="isSubmitting">
              <template x-if="!isSubmitting">
                <span><i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Perubahan</span>
              </template>
              <template x-if="isSubmitting">
                <span class="d-flex align-items-center gap-2"><span class="spinner-border spinner-border-sm"></span> Menyimpan...</span>
              </template>
            </button>
          </div>
        </form>
      </div>

      {{-- ────────────────────────────────────────────────────────────────────────
           SEKSI 3: PENDIDIKAN & PEKERJAAN
           ──────────────────────────────────────────────────────────────────────── --}}
      <div class="glass-card-premium px-4 py-4 mb-4" x-data="cardPendidikan()" x-cloak>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="section-title mb-0">
            <i class="icon-base ti tabler-school"></i> Pendidikan &amp; Pekerjaan
          </h5>
          <button type="button" class="btn btn-sm btn-secondary-custom" @click="toggleEdit()" x-show="!editMode">
            <i class="icon-base ti tabler-edit me-1"></i> Edit
          </button>
          <button type="button" class="btn btn-sm btn-secondary-custom text-danger" @click="toggleEdit()" x-show="editMode">
            <i class="icon-base ti tabler-x me-1"></i> Batal
          </button>
        </div>
        <hr class="detail-divider">

        {{-- VIEW MODE --}}
        <div x-show="!editMode" class="row g-3">
          <div class="col-md-6">
            <div class="view-label">Pendidikan Terakhir</div>
            <div class="view-value">{{ $profile?->pendidikan_terakhir ? (App\Models\PesertaProfile::PENDIDIKAN_OPTIONS[$profile?->pendidikan_terakhir] ?? $profile?->pendidikan_terakhir) : '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Tahun Lulus</div>
            <div class="view-value">{{ $profile?->tahun_lulus ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Nama Institusi / Sekolah</div>
            <div class="view-value">{{ $profile?->nama_institusi ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Jurusan / Program Studi</div>
            <div class="view-value">{{ $profile?->jurusan ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Status Pekerjaan</div>
            <div class="view-value">{{ $profile?->status_pekerjaan ? (App\Models\PesertaProfile::PEKERJAAN_OPTIONS[$profile?->status_pekerjaan] ?? $profile?->status_pekerjaan) : '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Nama Perusahaan / Instansi</div>
            <div class="view-value">{{ $profile?->nama_perusahaan ?? '-' }}</div>
          </div>
        </div>

        {{-- EDIT MODE --}}
        <form x-show="editMode" @submit.prevent="submitForm($event)" action="{{ route('admin.peserta.update-biodata', $user->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="row g-3">
            {{-- Pendidikan --}}
            <div class="col-md-6">
              <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
              <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="form-select" :class="{'is-invalid': errors.pendidikan_terakhir}">
                <option value="">-- Pilih Pendidikan --</option>
                @foreach ($pendidikanList as $key => $label)
                  <option value="{{ $key }}" {{ ($profile?->pendidikan_terakhir ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
              <template x-if="errors.pendidikan_terakhir">
                <div class="invalid-feedback" x-text="errors.pendidikan_terakhir[0]"></div>
              </template>
            </div>
            <div class="col-md-6">
              <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
              <input type="number" id="tahun_lulus" name="tahun_lulus" class="form-control" :class="{'is-invalid': errors.tahun_lulus}" value="{{ $profile?->tahun_lulus ?? '' }}" min="1950" max="{{ date('Y') }}">
              <template x-if="errors.tahun_lulus">
                <div class="invalid-feedback" x-text="errors.tahun_lulus[0]"></div>
              </template>
            </div>
            <div class="col-md-6">
              <label for="nama_institusi" class="form-label">Nama Institusi / Sekolah</label>
              <input type="text" id="nama_institusi" name="nama_institusi" class="form-control" :class="{'is-invalid': errors.nama_institusi}" value="{{ $profile?->nama_institusi ?? '' }}">
              <template x-if="errors.nama_institusi">
                <div class="invalid-feedback" x-text="errors.nama_institusi[0]"></div>
              </template>
            </div>
            <div class="col-md-6">
              <label for="jurusan" class="form-label">Jurusan / Program Studi</label>
              <input type="text" id="jurusan" name="jurusan" class="form-control" :class="{'is-invalid': errors.jurusan}" value="{{ $profile?->jurusan ?? '' }}">
              <template x-if="errors.jurusan">
                <div class="invalid-feedback" x-text="errors.jurusan[0]"></div>
              </template>
            </div>

            {{-- Pekerjaan --}}
            <div class="col-md-6">
              <label for="status_pekerjaan" class="form-label">Status Pekerjaan</label>
              <select id="status_pekerjaan" name="status_pekerjaan" class="form-select" :class="{'is-invalid': errors.status_pekerjaan}">
                <option value="">-- Pilih Status --</option>
                @foreach ($pekerjaanList as $key => $label)
                  <option value="{{ $key }}" {{ ($profile?->status_pekerjaan ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
              <template x-if="errors.status_pekerjaan">
                <div class="invalid-feedback" x-text="errors.status_pekerjaan[0]"></div>
              </template>
            </div>
            <div class="col-md-6">
              <label for="nama_perusahaan" class="form-label">Nama Perusahaan / Instansi</label>
              <input type="text" id="nama_perusahaan" name="nama_perusahaan" class="form-control" :class="{'is-invalid': errors.nama_perusahaan}" value="{{ $profile?->nama_perusahaan ?? '' }}">
              <template x-if="errors.nama_perusahaan">
                <div class="invalid-feedback" x-text="errors.nama_perusahaan[0]"></div>
              </template>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-secondary-custom" @click="toggleEdit()" :disabled="isSubmitting">Batal</button>
            <button type="submit" class="btn btn-glow-premium d-flex align-items-center gap-2" :disabled="isSubmitting">
              <template x-if="!isSubmitting">
                <span><i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Perubahan</span>
              </template>
              <template x-if="isSubmitting">
                <span class="d-flex align-items-center gap-2"><span class="spinner-border spinner-border-sm"></span> Menyimpan...</span>
              </template>
            </button>
          </div>
        </form>
      </div>

      {{-- ────────────────────────────────────────────────────────────────────────
           SEKSI 4: PREFERENSI
           ──────────────────────────────────────────────────────────────────────── --}}
      <div class="glass-card-premium px-4 py-4 mb-4" x-data="cardPreferensi()" x-cloak>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="section-title mb-0">
            <i class="icon-base ti tabler-heart"></i> Preferensi &amp; Minat Pelatihan
          </h5>
          <button type="button" class="btn btn-sm btn-secondary-custom" @click="toggleEdit()" x-show="!editMode">
            <i class="icon-base ti tabler-edit me-1"></i> Edit
          </button>
          <button type="button" class="btn btn-sm btn-secondary-custom text-danger" @click="toggleEdit()" x-show="editMode">
            <i class="icon-base ti tabler-x me-1"></i> Batal
          </button>
        </div>
        <hr class="detail-divider">

        {{-- VIEW MODE --}}
        <div x-show="!editMode" class="row g-3">
          <div class="col-md-6">
            <div class="view-label">Program Pelatihan yang Diikuti</div>
            <div class="view-value text-warning fw-bold">
              {{ $profile?->pelatihan?->nama ?? '-' }} 
              @if($profile?->batch_pelatihan)
                <span class="badge bg-label-info ms-2">Batch: {{ $profile?->batch_pelatihan }}</span>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Sumber Informasi</div>
            <div class="view-value">{{ $user->sumber_informasi ?? '-' }}</div>
          </div>
          <div class="col-12">
            <div class="view-label">Detail Sumber Informasi</div>
            <div class="view-value">{{ $user->sumber_informasi_detail ?? '-' }}</div>
          </div>

          
          <!-- Jawaban Pertanyaan Tahap 5 -->
          <div class="col-12"><hr class="detail-divider"></div>
          <div class="col-12">
            <h6 class="text-muted fw-semibold mb-3 text-uppercase" style="font-size:0.75rem; letter-spacing:0.05em;">
              Jawaban Pertanyaan Tahap 5
            </h6>
          </div>
          <div class="col-12">
            <div class="view-label">Apa yang Anda ketahui tentang Kang Asep?</div>
            <div class="view-value" style="white-space: pre-wrap;">{{ $jawaban['pengetahuan_asep'] ?? '-' }}</div>
          </div>
          <div class="col-12">
            <div class="view-label">Alasan Mengikuti Pelatihan</div>
            <div class="view-value" style="white-space: pre-wrap;">{{ $jawaban['alasan_pelatihan'] ?? '-' }}</div>
          </div>
          <div class="col-12">
            <div class="view-label">Pengalaman Bisnis dalam Bidang Pelatihan</div>
            <div class="view-value" style="white-space: pre-wrap;">{{ $jawaban['pengalaman_bisnis'] ?? '-' }}</div>
          </div>
          <div class="col-12">
            <div class="view-label">Minat/Rencana Kedepannya Setelah Pelatihan</div>
            <div class="view-value" style="white-space: pre-wrap;">{{ $jawaban['rencana_setelah_pelatihan'] ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Apakah Sudah Memiliki Usaha?</div>
            <div class="view-value">{{ $jawaban['punya_usaha'] ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Jenis Usaha yang Sedang Dijalankan</div>
            <div class="view-value">{{ $jawaban['jenis_usaha'] ?? '-' }}</div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Usaha yang Dimiliki</div>
            <div class="view-value">
              {{ $jawaban['usaha_dimiliki'] ?? '-' }}
              @if(!empty($jawaban['usaha_dimiliki_other']))
                ({{ $jawaban['usaha_dimiliki_other'] }})
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="view-label">Nama Usaha yang Sedang Dijalankan</div>
            <div class="view-value">
              {{ $jawaban['nama_usaha'] ?? '-' }}
              @if(!empty($jawaban['nama_usaha_other']))
                ({{ $jawaban['nama_usaha_other'] }})
              @endif
            </div>
          </div>
          <div class="col-12">
            <div class="view-label">Kendala yang Dialami dalam Menjalankan Usaha</div>
            <div class="view-value" style="white-space: pre-wrap;">{{ $jawaban['kendala_usaha'] ?? '-' }}</div>
          </div>
        </div>

        {{-- EDIT MODE --}}
        <form x-show="editMode" @submit.prevent="submitForm($event)" action="{{ route('admin.peserta.update-biodata', $user->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="row g-3">
            {{-- Program Pelatihan --}}
            <div class="col-md-6">
              <label for="pelatihan_id" class="form-label">Program Pelatihan yang Diikuti</label>
              <select id="pelatihan_id" name="pelatihan_id" class="form-select" :class="{'is-invalid': errors.pelatihan_id}">
                <option value="">-- Pilih Program Pelatihan --</option>
                @foreach($pelatihanList as $pel)
                  <option value="{{ $pel->id }}" {{ ($profile?->pelatihan_id ?? '') == $pel->id ? 'selected' : '' }}>
                    {{ $pel->batch }} : {{ $pel->nama }}
                  </option>
                @endforeach
              </select>
              <template x-if="errors.pelatihan_id">
                <div class="invalid-feedback" x-text="errors.pelatihan_id[0]"></div>
              </template>
            </div>

            {{-- Sumber Informasi --}}
            <div class="col-md-6">
              <label for="sumber_informasi" class="form-label">Sumber Informasi</label>
              <input type="text" id="sumber_informasi" name="sumber_informasi" class="form-control" :class="{'is-invalid': errors.sumber_informasi}" value="{{ $user->sumber_informasi ?? '' }}">
              <template x-if="errors.sumber_informasi">
                <div class="invalid-feedback" x-text="errors.sumber_informasi[0]"></div>
              </template>
            </div>

            {{-- Detail Sumber Informasi --}}
            <div class="col-12">
              <label for="sumber_informasi_detail" class="form-label">Detail Sumber Informasi</label>
              <textarea id="sumber_informasi_detail" name="sumber_informasi_detail" rows="2" class="form-control" :class="{'is-invalid': errors.sumber_informasi_detail}">{{ $user->sumber_informasi_detail ?? '' }}</textarea>
              <template x-if="errors.sumber_informasi_detail">
                <div class="invalid-feedback" x-text="errors.sumber_informasi_detail[0]"></div>
              </template>
            </div>


            <!-- Jawaban Pertanyaan Tahap 5 -->
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-12">
              <h6 class="text-muted fw-semibold mb-0 text-uppercase" style="font-size:0.75rem; letter-spacing:0.05em;">
                Jawaban Pertanyaan Tahap 5
              </h6>
            </div>
            
            <div class="col-md-12">
              <label for="jawaban_pengetahuan_asep" class="form-label">Apa yang Anda ketahui tentang Kang Asep?</label>
              <textarea id="jawaban_pengetahuan_asep" name="jawaban_pertanyaan[pengetahuan_asep]" rows="2" class="form-control">{{ $jawaban['pengetahuan_asep'] ?? '' }}</textarea>
            </div>

            <div class="col-md-12">
              <label for="jawaban_alasan_pelatihan" class="form-label">Alasan Mengikuti Pelatihan</label>
              <textarea id="jawaban_alasan_pelatihan" name="jawaban_pertanyaan[alasan_pelatihan]" rows="2" class="form-control">{{ $jawaban['alasan_pelatihan'] ?? '' }}</textarea>
            </div>

            <div class="col-md-12">
              <label for="jawaban_pengalaman_bisnis" class="form-label">Pengalaman Bisnis dalam Bidang Pelatihan</label>
              <textarea id="jawaban_pengalaman_bisnis" name="jawaban_pertanyaan[pengalaman_bisnis]" rows="2" class="form-control">{{ $jawaban['pengalaman_bisnis'] ?? '' }}</textarea>
            </div>

            <div class="col-md-12">
              <label for="jawaban_rencana_setelah_pelatihan" class="form-label">Minat/Rencana Kedepannya Setelah Pelatihan</label>
              <textarea id="jawaban_rencana_setelah_pelatihan" name="jawaban_pertanyaan[rencana_setelah_pelatihan]" rows="2" class="form-control">{{ $jawaban['rencana_setelah_pelatihan'] ?? '' }}</textarea>
            </div>

            <div class="col-md-6">
              <label for="jawaban_punya_usaha" class="form-label">Apakah Sudah Memiliki Usaha?</label>
              <select id="jawaban_punya_usaha" name="jawaban_pertanyaan[punya_usaha]" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="Sudah" {{ ($jawaban['punya_usaha'] ?? '') === 'Sudah' ? 'selected' : '' }}>Sudah</option>
                <option value="Belum" {{ ($jawaban['punya_usaha'] ?? '') === 'Belum' ? 'selected' : '' }}>Belum</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="jawaban_jenis_usaha" class="form-label">Jenis Usaha yang Sedang Dijalankan</label>
              <select id="jawaban_jenis_usaha" name="jawaban_pertanyaan[jenis_usaha]" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="Belum Pernah" {{ ($jawaban['jenis_usaha'] ?? '') === 'Belum Pernah' ? 'selected' : '' }}>Belum Pernah</option>
                <option value="Fashion" {{ ($jawaban['jenis_usaha'] ?? '') === 'Fashion' ? 'selected' : '' }}>Fashion</option>
                <option value="Kuliner" {{ ($jawaban['jenis_usaha'] ?? '') === 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                <option value="Jasa" {{ ($jawaban['jenis_usaha'] ?? '') === 'Jasa' ? 'selected' : '' }}>Jasa</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="jawaban_usaha_dimiliki" class="form-label">Usaha yang Dimiliki</label>
              <input type="text" id="jawaban_usaha_dimiliki" name="jawaban_pertanyaan[usaha_dimiliki]" class="form-control" value="{{ $jawaban['usaha_dimiliki'] ?? '' }}">
            </div>

            <div class="col-md-6">
              <label for="jawaban_usaha_dimiliki_other" class="form-label">Usaha yang Dimiliki (Lainnya)</label>
              <input type="text" id="jawaban_usaha_dimiliki_other" name="jawaban_pertanyaan[usaha_dimiliki_other]" class="form-control" value="{{ $jawaban['usaha_dimiliki_other'] ?? '' }}">
            </div>

            <div class="col-md-6">
              <label for="jawaban_nama_usaha" class="form-label">Nama Usaha yang Sedang Dijalankan</label>
              <input type="text" id="jawaban_nama_usaha" name="jawaban_pertanyaan[nama_usaha]" class="form-control" value="{{ $jawaban['nama_usaha'] ?? '' }}">
            </div>

            <div class="col-md-6">
              <label for="jawaban_nama_usaha_other" class="form-label">Nama Usaha (Lainnya)</label>
              <input type="text" id="jawaban_nama_usaha_other" name="jawaban_pertanyaan[nama_usaha_other]" class="form-control" value="{{ $jawaban['nama_usaha_other'] ?? '' }}">
            </div>

            <div class="col-md-12">
              <label for="jawaban_kendala_usaha" class="form-label">Apa kendala yang dialami dalam menjalankan usaha anda?</label>
              <textarea id="jawaban_kendala_usaha" name="jawaban_pertanyaan[kendala_usaha]" rows="2" class="form-control">{{ $jawaban['kendala_usaha'] ?? '' }}</textarea>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-secondary-custom" @click="toggleEdit()" :disabled="isSubmitting">Batal</button>
            <button type="submit" class="btn btn-glow-premium d-flex align-items-center gap-2" :disabled="isSubmitting">
              <template x-if="!isSubmitting">
                <span><i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Perubahan</span>
              </template>
              <template x-if="isSubmitting">
                <span class="d-flex align-items-center gap-2"><span class="spinner-border spinner-border-sm"></span> Menyimpan...</span>
              </template>
            </button>
          </div>
        </form>
      </div>

      {{-- ────────────────────────────────────────────────────────────────────────
           SEKSI 5: DOKUMEN
           ──────────────────────────────────────────────────────────────────────── --}}
      <div class="glass-card-premium px-4 py-4 mb-4" x-data="cardDokumen()" x-cloak>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="section-title mb-0">
            <i class="icon-base ti tabler-file-upload"></i> Upload Dokumen
          </h5>
          <button type="button" class="btn btn-sm btn-secondary-custom" @click="toggleEdit()" x-show="!editMode">
            <i class="icon-base ti tabler-edit me-1"></i> Edit
          </button>
          <button type="button" class="btn btn-sm btn-secondary-custom text-danger" @click="toggleEdit()" x-show="editMode">
            <i class="icon-base ti tabler-x me-1"></i> Batal
          </button>
        </div>
        <hr class="detail-divider">

        {{-- VIEW MODE --}}
        <div x-show="!editMode" class="row g-4">
          {{-- Foto Profil --}}
          <div class="col-md-6">
            <div class="glass-card-premium p-3 h-100 text-center border-0">
              <div class="view-label mb-2">Foto Profil</div>
              <div class="d-inline-block rounded-circle overflow-hidden border border-2 mb-2" style="width:100px; height:100px;">
                @if($profile?->foto_profil ?? '')
                  <img src="{{ asset('storage/' . $profile?->foto_profil) }}" alt="Foto Profil" class="w-100 h-100" style="object-fit:cover;">
                @else
                  <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                    <i class="icon-base ti tabler-user fs-1"></i>
                  </div>
                @endif
              </div>
              <div class="view-value">
                @if($profile?->foto_profil ?? '')
                  <span class="text-success text-xs d-block"><i class="icon-base ti tabler-circle-check-filled me-1"></i>Tersedia</span>
                  <a href="{{ asset('storage/' . $profile?->foto_profil) }}" target="_blank" class="btn btn-xs btn-secondary-custom mt-2">Lihat Foto</a>
                @else
                  <span class="text-muted text-xs d-block">Belum Diunggah</span>
                @endif
              </div>
            </div>
          </div>

          {{-- Scan KTP --}}
          <div class="col-md-6">
            <div class="glass-card-premium p-3 h-100 text-center border-0">
              <div class="view-label mb-2">Scan KTP</div>
              <div class="d-inline-flex align-items-center justify-content-center border rounded mb-2" style="width:160px; height:100px; background: rgba(255,255,255,0.02);">
                @if($profile?->scan_ktp ?? '')
                  @if(Str::endsWith($profile?->scan_ktp, '.pdf'))
                    <div class="text-center">
                      <i class="icon-base ti tabler-file-text text-danger fs-1"></i>
                      <div class="text-xs text-muted mt-1">PDF Document</div>
                    </div>
                  @else
                    <img src="{{ asset('storage/' . $profile?->scan_ktp) }}" alt="Scan KTP" style="max-width:100%; max-height:100%; object-fit:contain;">
                  @endif
                @else
                  <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                    <i class="icon-base ti tabler-id-badge fs-1"></i>
                  </div>
                @endif
              </div>
              <div class="view-value">
                @if($profile?->scan_ktp ?? '')
                  <span class="text-success text-xs d-block"><i class="icon-base ti tabler-circle-check-filled me-1"></i>Tersedia</span>
                  <a href="{{ asset('storage/' . $profile?->scan_ktp) }}" target="_blank" class="btn btn-xs btn-secondary-custom mt-2">Lihat Dokumen KTP</a>
                @else
                  <span class="text-muted text-xs d-block">Belum Diunggah</span>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- EDIT MODE --}}
        <form x-show="editMode" @submit.prevent="submitForm($event)" action="{{ route('admin.peserta.update-biodata', $user->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="row g-4">
            <div class="col-12">
              <div class="alert alert-warning border-0 mb-0 d-flex align-items-center gap-2" style="border-radius: 5px; background: rgba(245,158,11,0.1); color:#fbbf24;">
                <i class="icon-base ti tabler-info-circle fs-5"></i>
                <small>Kosongkan field upload jika tidak ingin mengganti file. File yang sudah ada akan tetap dipertahankan.</small>
              </div>
            </div>

            {{-- Upload Foto Profil --}}
            <div class="col-md-6">
              <label for="foto_profil" class="form-label">Foto Profil</label>
              <input type="file" id="foto_profil" name="foto_profil" accept="image/jpeg,image/png" class="form-control" :class="{'is-invalid': errors.foto_profil}">
              <div class="form-text">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
              <template x-if="errors.foto_profil">
                <div class="invalid-feedback" x-text="errors.foto_profil[0]"></div>
              </template>
            </div>

            {{-- Upload Scan KTP --}}
            <div class="col-md-6">
              <label for="scan_ktp" class="form-label">Scan KTP</label>
              <input type="file" id="scan_ktp" name="scan_ktp" accept="image/jpeg,image/png,application/pdf" class="form-control" :class="{'is-invalid': errors.scan_ktp}">
              <div class="form-text">Format: JPG, JPEG, PNG, PDF. Maksimal 5MB.</div>
              <template x-if="errors.scan_ktp">
                <div class="invalid-feedback" x-text="errors.scan_ktp[0]"></div>
              </template>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-secondary-custom" @click="toggleEdit()" :disabled="isSubmitting">Batal</button>
            <button type="submit" class="btn btn-glow-premium d-flex align-items-center gap-2" :disabled="isSubmitting">
              <template x-if="!isSubmitting">
                <span><i class="icon-base ti tabler-device-floppy me-1"></i> Simpan Perubahan</span>
              </template>
              <template x-if="isSubmitting">
                <span class="d-flex align-items-center gap-2"><span class="spinner-border spinner-border-sm"></span> Menyimpan...</span>
              </template>
            </button>
          </div>
        </form>
      </div>

    </div>
@endsection

@section('page-script')
    <script>
      /**
       * Global AJAX form submit handler helper.
       * Performs validation-safe section-based updates without page reloads on failure.
       */
      function handleAjaxSubmit(event, alpineComponent, sectionName) {
        alpineComponent.isSubmitting = true;
        alpineComponent.errors = {};

        const form = event.target;
        const formData = new FormData(form);
        formData.append('section', sectionName);

        fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => {
          if (response.status === 422) {
            return response.json().then(data => {
              alpineComponent.errors = data.errors || {};
              alpineComponent.isSubmitting = false;
              Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                text: 'Silakan perbaiki kesalahan validasi pada kartu ini.',
                background: '#0f172a',
                color: '#f8fafc',
                customClass: {
                  confirmButton: 'btn btn-glow-premium px-4 py-2 border-0 fw-semibold'
                }
              });
            });
          }
          if (!response.ok) {
            throw new Error('Terjadi kesalahan jaringan atau server.');
          }
          return response.json();
        })
        .then(data => {
          if (data && data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil Disimpan',
              text: data.message || 'Data telah berhasil diperbarui.',
              timer: 1500,
              showConfirmButton: false,
              background: '#0f172a',
              color: '#f8fafc'
            }).then(() => {
              window.location.reload();
            });
          }
        })
        .catch(error => {
          alpineComponent.isSubmitting = false;
          Swal.fire({
            icon: 'error',
            title: 'Kesalahan Sistem',
            text: error.message || 'Gagal mengirimkan data.',
            background: '#0f172a',
            color: '#f8fafc',
            customClass: {
              confirmButton: 'btn btn-glow-premium px-4 py-2 border-0 fw-semibold'
            }
          });
        });
      }

      document.addEventListener('alpine:init', () => {
        // Alpine data untuk Identitas & Kontak
        Alpine.data('cardIdentitas', () => ({
          editMode: false,
          isSubmitting: false,
          errors: {},
          toggleEdit() {
            this.editMode = !this.editMode;
            this.errors = {};
          },
          submitForm(event) {
            handleAjaxSubmit(event, this, 'identitas');
          }
        }));

        // Alpine data untuk Alamat
        Alpine.data('cardAlamat', () => ({
          editMode: false,
          isSubmitting: false,
          errors: {},
          provinsi: '{{ old('provinsi', $profile?->provinsi ?? '') }}',
          kota: '{{ old('kota', $profile?->kota ?? '') }}',
          kecamatanId: '{{ old('kecamatan_id', $user->kecamatan_id ?? '') }}',
          kelurahanId: '{{ old('kelurahan_id', $user->kelurahan_id ?? '') }}',
          kelurahans: [],
          isLoadingKelurahan: false,

          init() {
            if (this.kecamatanId) {
              this.fetchKelurahans(this.kecamatanId);
            }
            this.$watch('kecamatanId', value => {
              this.fetchKelurahans(value);
            });
          },
          toggleEdit() {
            this.editMode = !this.editMode;
            this.errors = {};
            if (!this.editMode) {
              // Reset values to original DB values
              this.provinsi = '{{ $profile?->provinsi ?? '' }}';
              this.kota = '{{ $profile?->kota ?? '' }}';
              this.kecamatanId = '{{ $user->kecamatan_id ?? '' }}';
              this.kelurahanId = '{{ $user->kelurahan_id ?? '' }}';
            }
          },
          fetchKelurahans(kecId) {
            if (!kecId) {
              this.kelurahans = [];
              return;
            }
            this.isLoadingKelurahan = true;
            fetch(`/api/kelurahan?kecamatan_id=${kecId}`)
              .then(response => {
                if (!response.ok) throw new Error('Gagal');
                return response.json();
              })
              .then(data => {
                this.kelurahans = data;
                this.isLoadingKelurahan = false;
              })
              .catch(err => {
                console.error('Error fetching kelurahans:', err);
                this.isLoadingKelurahan = false;
              });
          },
          getSelectedKecamatanName() {
            const select = document.getElementById('kecamatan_id');
            if (select && select.selectedIndex > 0) {
              return select.options[select.selectedIndex].text;
            }
            return '{{ $profile?->kecamatan ?? '' }}';
          },
          getSelectedKelurahanName() {
            // Find in kelurahans list
            const found = this.kelurahans.find(k => k.id == this.kelurahanId);
            return found ? found.name : '{{ $profile?->kelurahan ?? '' }}';
          },
          submitForm(event) {
            handleAjaxSubmit(event, this, 'alamat');
          }
        }));

        // Alpine data untuk Pendidikan
        Alpine.data('cardPendidikan', () => ({
          editMode: false,
          isSubmitting: false,
          errors: {},
          toggleEdit() {
            this.editMode = !this.editMode;
            this.errors = {};
          },
          submitForm(event) {
            handleAjaxSubmit(event, this, 'pendidikan');
          }
        }));

        // Alpine data untuk Preferensi
        Alpine.data('cardPreferensi', () => ({
          editMode: false,
          isSubmitting: false,
          errors: {},
          toggleEdit() {
            this.editMode = !this.editMode;
            this.errors = {};
          },
          submitForm(event) {
            handleAjaxSubmit(event, this, 'preferensi');
          }
        }));

        // Alpine data untuk Dokumen
        Alpine.data('cardDokumen', () => ({
          editMode: false,
          isSubmitting: false,
          errors: {},
          toggleEdit() {
            this.editMode = !this.editMode;
            this.errors = {};
          },
          submitForm(event) {
            handleAjaxSubmit(event, this, 'dokumen');
          }
        }));
      });
    </script>
@endsection
