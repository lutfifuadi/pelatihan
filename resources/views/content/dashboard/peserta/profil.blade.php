@php
$configData = Helper::appClasses();

// Helper untuk cek field penting
$importantFields = ['nama_lengkap', 'nik', 'alamat_ktp', 'whatsapp', 'pendidikan_terakhir', 'bidang_minat', 'foto_profil'];
$filledCount = 0;
$totalImportant = count($importantFields);
if ($profile) {
    foreach ($importantFields as $f) {
        $val = $profile->$f ?? null;
        if (is_array($val)) {
            if (!empty($val)) $filledCount++;
        } else {
            if (!empty($val)) $filledCount++;
        }
    }
}
$isComplete = $profile && $filledCount >= $totalImportant;
$isCompleted = $profile && $profile->is_completed;

// Inisial avatar
$initialName = $user->name ?? 'U';
$initials = strtoupper(substr($initialName, 0, 1));
if (str_contains($initialName, ' ')) {
    $parts = explode(' ', $initialName);
    $initials = strtoupper(substr($parts[0], 0, 1) . substr(end($parts), 0, 1));
} elseif (strlen($initialName) >= 2) {
    $initials = strtoupper(substr($initialName, 0, 2));
}
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Profil Saya')

@section('page-script')
<script>
  document.addEventListener('alpine:init', function() {
    Alpine.data('profileEdit', function() {
      return {
        editKontak: false,
        whatsapp: '{{ $profile->whatsapp ?? "" }}',
        email: '{{ $profile->email ?? $user->email ?? "" }}',
        medsos: @json($profile->link_medsos ?? []),
        
        addMedsos() {
          this.medsos.push({ platform: 'Instagram', url: '' });
        },
        removeMedsos(index) {
          this.medsos.splice(index, 1);
        }
      };
    });
  });
</script>
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

  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border-radius: 5px !important;
    position: relative;
    z-index: 1;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .glass-card-premium:hover {
    transform: translateY(-4px) !important;
    border-color: rgba(99, 102, 241, 0.3) !important;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), 0 0 30px rgba(99, 102, 241, 0.15) !important;
  }

  .stat-icon-box {
    width: 52px; height: 52px; border-radius: 5px !important;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; flex-shrink: 0;
    transition: all 0.3s ease;
  }

  .info-label {
    font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em;
    color: rgba(255, 255, 255, 0.4); font-weight: 600; margin-bottom: 2px;
  }
  .info-value {
    font-size: 0.95rem; color: #f8fafc; font-weight: 500;
  }

  hr.dark-premium { border-color: rgba(255, 255, 255, 0.06); opacity: 1; }

  .badge-status {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 16px; border-radius: 20px;
    font-weight: 600; font-size: 0.78rem;
    letter-spacing: 0.03em;
  }
  .badge-status.complete {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #34d399;
  }
  .badge-status.incomplete {
    background: rgba(245, 158, 11, 0.15);
    border: 1px solid rgba(245, 158, 11, 0.3);
    color: #fbbf24;
  }

  .btn-outline-glass {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    transition: all 0.3s ease;
  }
  .btn-outline-glass:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    transform: translateY(-2px);
  }

  .btn-glow-premium {
    background: linear-gradient(135deg, #ffc107, #ff9800) !important;
    border: none; color: #0b0f19 !important;
    font-family: 'Sora', sans-serif; font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.4);
    background: linear-gradient(135deg, #ffca28, #ffa726) !important;
    color: #0b0f19 !important;
  }

  .text-body-premium {
    color: rgba(255, 255, 255, 0.65) !important;
  }

  .text-gradient {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .avatar-initials {
    width: 120px; height: 120px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.8rem; font-weight: 800;
    font-family: 'Sora', sans-serif;
    background: linear-gradient(135deg, #6366f1, #d946ef);
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 0 40px rgba(99, 102, 241, 0.3);
  }

  .profile-photo {
    width: 120px; height: 120px; border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 0 40px rgba(99, 102, 241, 0.3);
    border: 3px solid rgba(255, 255, 255, 0.1);
  }

  .chip-badge {
    display: inline-flex; align-items: center;
    padding: 4px 12px; border-radius: 20px;
    font-size: 0.78rem; font-weight: 500;
    background: rgba(99, 102, 241, 0.12);
    border: 1px solid rgba(99, 102, 241, 0.2);
    color: #a5b4fc;
    margin: 2px 4px 2px 0;
  }

  .doc-thumb {
    width: 100px; height: 100px; border-radius: 5px;
    object-fit: cover;
    border: 1px solid rgba(255, 255, 255, 0.08);
  }

  .hover-text-primary:hover { color: #818cf8 !important; }

  ::-webkit-scrollbar { width: 8px; }
  ::-webkit-scrollbar-track { background: #0b0f19; }
  ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 4px; }
  ::-webkit-scrollbar-thumb:hover { background: #d946ef; }

  body .content-wrapper > .container-p-y {
    padding-top: 1.5rem !important;
  }
</style>
@endsection

@section('content')
<div x-data="profileEdit()" x-cloak>
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

  @if(!$profile)
  {{-- ================================================================
       EMPTY STATE — Belum ada data profil
       ================================================================ --}}
  <div class="glass-card-premium px-4 px-xl-5 py-5 mb-4 text-center">
    <div class="py-5">
      <div class="stat-icon-box mx-auto mb-4" style="width: 80px; height: 80px; border-radius: 50% !important; background: rgba(99,102,241,0.12); color: #818cf8;">
        <i class="icon-base ti tabler-user-off fs-1"></i>
      </div>
      <h4 class="fw-bold text-white mb-2">Belum Ada Data Profil</h4>
      <p class="text-body-premium mb-4" style="max-width: 400px; margin: 0 auto;">
        Anda belum mengisi data profil. Silakan lengkapi data pendaftaran terlebih dahulu untuk melanjutkan.
      </p>
      <a href="{{ route('dashboard.peserta.form-pendaftaran') }}" class="btn btn-glow-premium py-2 px-5">
        <i class="icon-base ti tabler-pencil me-1"></i> Lengkapi Data Sekarang
      </a>
    </div>
  </div>
  @else

  {{-- ================================================================
       A. HEADER — Profil Saya
       ================================================================ --}}
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <div class="row align-items-center">
      <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center gap-3 mb-2">
          <div class="stat-icon-box" style="width: 56px; height: 56px; border-radius: 50% !important; background: rgba(99,102,241,0.12); color: #818cf8;">
            <i class="icon-base ti tabler-user-circle fs-2"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-1">Profil Saya</h4>
            <p class="text-body-premium mb-0" style="font-size: 0.9rem;">
              Informasi lengkap data diri Anda
            </p>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-4 mt-3 mt-lg-0 text-lg-end">
        @if($isCompleted)
          <a href="https://wa.me/{{ \App\Models\Setting::where('key', 'whatsapp_sender')->value('value') ?? '62888888888' }}?text={{ urlencode("Halo Admin, saya ingin mengajukan perubahan data pendaftaran yang dikunci. Nama: " . ($profile->nama_lengkap ?? $user->name) . ", NIK: " . ($profile->nik ?? '-') . ".") }}" 
             target="_blank" class="btn btn-sm btn-warning fw-semibold px-3 py-1.5 me-2" style="border-radius: 5px;">
            <i class="icon-base ti tabler-edit me-1"></i> Ajukan Perubahan Data Penting
          </a>
        @endif
        @if($isComplete)
          <span class="badge-status complete">
            <i class="icon-base ti tabler-circle-check"></i>
            Lengkap
          </span>
        @else
          <span class="badge-status incomplete">
            <i class="icon-base ti tabler-alert-triangle"></i>
            Belum Lengkap
          </span>
        @endif
      </div>
    </div>
  </div>

  {{-- ================================================================
       B. HERO SECTION — Foto & Identitas (2 kolom)
       ================================================================ --}}
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <div class="row align-items-center g-4">
      {{-- Kiri: Foto --}}
      <div class="col-12 col-md-4 col-lg-3 text-center text-md-start">
        @if(!empty($user->google_drive_photo_url))
          <img src="{{ $user->google_drive_photo_url }}" alt="Foto Profil" class="profile-photo">
        @elseif(!empty($profile->foto_profil))
          <img src="{{ Storage::url($profile->foto_profil) }}" alt="Foto Profil" class="profile-photo">
        @else
          <div class="avatar-initials mx-auto mx-md-0">{{ $initials }}</div>
        @endif
      </div>
      {{-- Kanan: Identitas --}}
      <div class="col-12 col-md-8 col-lg-9">
        <h3 class="fw-bold text-white mb-1" style="font-family: 'Sora', sans-serif;">{{ $profile->nama_lengkap ?? $user->name }}</h3>
        <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
          @if($profile->nik)
            <span class="text-body-premium small" style="font-family: monospace;">
              <i class="icon-base ti tabler-id me-1"></i>NIK: {{ $profile->nik }}
            </span>
          @endif
          <span class="text-body-premium small">
            <i class="icon-base ti tabler-mail me-1"></i>{{ $profile->email ?? $user->email }}
          </span>
        </div>
        <div>
          @if($isComplete)
            <span class="badge-status complete">
              <i class="icon-base ti tabler-circle-check"></i>
              Profil Lengkap
            </span>
          @else
            <span class="badge-status incomplete">
              <i class="icon-base ti tabler-alert-triangle"></i>
              {{ $totalImportant - $filledCount }} field penting belum diisi
            </span>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ================================================================
       C. KARTU DATA — 6 Seksi
       ================================================================ --}}
  <div class="row g-4">

    {{-- SEKSI 1: Data Pribadi --}}
    <div class="col-12">
      <div class="glass-card-premium p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="d-flex align-items-center gap-2">
            <div class="stat-icon-box" style="background: rgba(99,102,241,0.12); color: #818cf8;">
              <i class="icon-base ti tabler-user"></i>
            </div>
            <h5 class="fw-bold text-white mb-0">Data Pribadi</h5>
          </div>
          @if($isCompleted)
            <span class="badge bg-secondary bg-opacity-15 text-white-50 border border-white border-opacity-10 px-2.5 py-1 small" style="border-radius: 4px;">
              <i class="icon-base ti tabler-lock me-1 fs-6"></i> Terkunci
            </span>
          @else
            <a href="{{ route('dashboard.peserta.form-pendaftaran') }}" class="btn btn-sm btn-outline-glass">
              <i class="icon-base ti tabler-pencil me-1"></i> Ubah
            </a>
          @endif
        </div>
        <hr class="dark-premium">
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Nama Lengkap</span>
            <span class="info-value">{{ $profile->nama_lengkap ?? '—' }}</span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Jenis Kelamin</span>
            <span class="info-value">{{ $profile->jenis_kelamin ?? '—' }}</span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Tempat Lahir</span>
            <span class="info-value">{{ $profile->tempat_lahir ?? '—' }}</span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Tanggal Lahir</span>
            <span class="info-value">
              @if($profile->tanggal_lahir && $profile->bulan_lahir && $profile->tahun_lahir)
                {{ $profile->tanggal_lahir }} {{ $profile->bulan_lahir }} {{ $profile->tahun_lahir }}
              @else
                —
              @endif
            </span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">NIK</span>
            <span class="info-value" style="font-family: monospace;">{{ $profile->nik ?? '—' }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- SEKSI 2: Alamat & Kontak --}}
    <div class="col-12">
      <div class="glass-card-premium p-4">
        {{-- VIEW MODE --}}
        <div x-show="!editKontak">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex align-items-center gap-2">
              <div class="stat-icon-box" style="background: rgba(16,185,129,0.12); color: #34d399;">
                <i class="icon-base ti tabler-map-pin"></i>
              </div>
              <h5 class="fw-bold text-white mb-0">Alamat & Kontak</h5>
            </div>
            @if($isCompleted)
              <button type="button" @click="editKontak = true" class="btn btn-sm btn-outline-glass">
                <i class="icon-base ti tabler-pencil me-1"></i> Ubah Kontak
              </button>
            @else
              <a href="{{ route('dashboard.peserta.form-alamat') }}" class="btn btn-sm btn-outline-glass">
                <i class="icon-base ti tabler-pencil me-1"></i> Ubah
              </a>
            @endif
          </div>
          <hr class="dark-premium">
          <div class="row g-3">
            <div class="col-12">
              <span class="info-label d-block">Alamat KTP</span>
              <span class="info-value">{{ $profile->alamat_ktp ?? '—' }}</span>
            </div>
            <div class="col-6 col-md-3">
              <span class="info-label d-block">RT</span>
              <span class="info-value">{{ $profile->rt ?? '—' }}</span>
            </div>
            <div class="col-6 col-md-3">
              <span class="info-label d-block">RW</span>
              <span class="info-value">{{ $profile->rw ?? '—' }}</span>
            </div>
            <div class="col-6 col-md-3">
              <span class="info-label d-block">Kelurahan</span>
              <span class="info-value">{{ $profile->kelurahan ?? '—' }}</span>
            </div>
            <div class="col-6 col-md-3">
              <span class="info-label d-block">Kecamatan</span>
              <span class="info-value">{{ $profile->kecamatan ?? '—' }}</span>
            </div>
            <div class="col-6 col-md-3">
              <span class="info-label d-block">Kota</span>
              <span class="info-value">{{ $profile->kota ?? '—' }}</span>
            </div>
            <div class="col-6 col-md-3">
              <span class="info-label d-block">Provinsi</span>
              <span class="info-value">{{ $profile->provinsi ?? '—' }}</span>
            </div>
            <div class="col-6 col-md-3">
              <span class="info-label d-block">Kodepos</span>
              <span class="info-value">{{ $profile->kodepos ?? '—' }}</span>
            </div>
            <div class="col-6 col-md-3">
              <span class="info-label d-block">WhatsApp</span>
              <span class="info-value" x-text="whatsapp || '—'"></span>
            </div>
            <div class="col-12 col-md-6">
              <span class="info-label d-block">Email</span>
              <span class="info-value" x-text="email || '—'"></span>
            </div>
            <div class="col-12">
              <span class="info-label d-block">Link Medsos</span>
              <div>
                <template x-if="medsos.length > 0">
                  <div class="d-flex flex-wrap gap-2">
                    <template x-for="(item, index) in medsos" :key="index">
                      <span class="chip-badge">
                        <span x-text="item.platform"></span>: <span x-text="item.url || '—'"></span>
                      </span>
                    </template>
                  </div>
                </template>
                <template x-if="medsos.length === 0">
                  <span class="text-body-premium small">—</span>
                </template>
              </div>
            </div>
          </div>
        </div>

        {{-- EDIT MODE (Hanya untuk Kontak jika Terkunci/isCompleted) --}}
        <div x-show="editKontak" style="display: none;">
          <form action="{{ route('dashboard.peserta.profil.update-kontak') }}" method="POST">
            @csrf
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="d-flex align-items-center gap-2">
                <div class="stat-icon-box" style="background: rgba(99, 102, 241, 0.12); color: #818cf8;">
                  <i class="icon-base ti tabler-pencil"></i>
                </div>
                <div>
                  <h5 class="fw-bold text-white mb-0">Ubah Kontak</h5>
                  <small class="text-warning">Hanya data kontak yang dapat diubah secara mandiri setelah pendaftaran selesai.</small>
                </div>
              </div>
            </div>
            <hr class="dark-premium">
            
            <div class="row g-3">
              {{-- Alamat-alamat tetap dikunci (read-only / info saja) --}}
              <div class="col-12">
                <div class="alert alert-secondary border-secondary border-opacity-20 bg-secondary bg-opacity-10 text-white-50 p-2 small mb-0" style="border-radius: 5px;">
                  <i class="icon-base ti tabler-lock me-1"></i> Data Alamat KTP dikunci dan tidak dapat diubah.
                </div>
              </div>

              <div class="col-12 col-md-6">
                <label class="info-label d-block mb-1">WhatsApp</label>
                <input type="text" name="whatsapp" x-model="whatsapp" class="form-control bg-dark text-white border-secondary" style="border-radius: 5px;" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="info-label d-block mb-1">Email</label>
                <input type="email" name="email" x-model="email" class="form-control bg-dark text-white border-secondary" style="border-radius: 5px;" required>
              </div>

              <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label class="info-label mb-0">Link Media Sosial</label>
                  <button type="button" @click="addMedsos()" class="btn btn-sm btn-outline-glass px-2 py-1" style="font-size: 11px;">
                    <i class="icon-base ti tabler-plus me-1"></i> Tambah Medsos
                  </button>
                </div>

                <div class="d-flex flex-col gap-2">
                  <template x-for="(item, index) in medsos" :key="index">
                    <div class="d-flex gap-2 align-items-center mb-2">
                      <select :name="'link_medsos[' + index + '][platform]'" x-model="item.platform" class="form-select bg-dark text-white border-secondary w-auto" style="border-radius: 5px;">
                        <option value="Instagram">Instagram</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Twitter">Twitter</option>
                        <option value="LinkedIn">LinkedIn</option>
                        <option value="TikTok">TikTok</option>
                        <option value="Youtube">Youtube</option>
                        <option value="Website">Website / Portofolio</option>
                      </select>
                      <input type="text" :name="'link_medsos[' + index + '][url]'" x-model="item.url" placeholder="https://..." class="form-control bg-dark text-white border-secondary flex-grow-1" style="border-radius: 5px;">
                      <button type="button" @click="removeMedsos(index)" class="btn btn-sm btn-danger px-2" style="border-radius: 5px;">
                        <i class="icon-base ti tabler-trash"></i>
                      </button>
                    </div>
                  </template>
                </div>
              </div>

              <div class="col-12 mt-4 text-end">
                <button type="button" @click="editKontak = false" class="btn btn-sm btn-outline-glass me-2 px-3 py-2">
                  Batal
                </button>
                <button type="submit" class="btn btn-sm btn-glow-premium px-4 py-2">
                  Simpan Perubahan
                </button>
              </div>

            </div>
          </form>
        </div>

      </div>
    </div>

    {{-- SEKSI 3: Pendidikan & Pekerjaan --}}
    <div class="col-12">
      <div class="glass-card-premium p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="d-flex align-items-center gap-2">
            <div class="stat-icon-box" style="background: rgba(139,92,246,0.12); color: #a78bfa;">
              <i class="icon-base ti tabler-school"></i>
            </div>
            <h5 class="fw-bold text-white mb-0">Pendidikan & Pekerjaan</h5>
          </div>
          @if($isCompleted)
            <span class="badge bg-secondary bg-opacity-15 text-white-50 border border-white border-opacity-10 px-2.5 py-1 small" style="border-radius: 4px;">
              <i class="icon-base ti tabler-lock me-1 fs-6"></i> Terkunci
            </span>
          @else
            <a href="{{ route('dashboard.peserta.form-pendidikan') }}" class="btn btn-sm btn-outline-glass">
              <i class="icon-base ti tabler-pencil me-1"></i> Ubah
            </a>
          @endif
        </div>
        <hr class="dark-premium">
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Pendidikan Terakhir</span>
            <span class="info-value">{{ $profile->pendidikan_terakhir ?? '—' }}</span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Nama Institusi</span>
            <span class="info-value">{{ $profile->nama_institusi ?? '—' }}</span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Jurusan</span>
            <span class="info-value">{{ $profile->jurusan ?? '—' }}</span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Tahun Lulus</span>
            <span class="info-value">{{ $profile->tahun_lulus ?? '—' }}</span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Status Pekerjaan</span>
            <span class="info-value">{{ $profile->status_pekerjaan ?? '—' }}</span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Nama Perusahaan</span>
            <span class="info-value">{{ $profile->nama_perusahaan ?? '—' }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- SEKSI 4: Minat Pelatihan --}}
    <div class="col-12">
      <div class="glass-card-premium p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="d-flex align-items-center gap-2">
            <div class="stat-icon-box" style="background: rgba(236,72,153,0.12); color: #f472b6;">
              <i class="icon-base ti tabler-heart"></i>
            </div>
            <h5 class="fw-bold text-white mb-0">Minat Pelatihan</h5>
          </div>
          @if($isCompleted)
            <span class="badge bg-secondary bg-opacity-15 text-white-50 border border-white border-opacity-10 px-2.5 py-1 small" style="border-radius: 4px;">
              <i class="icon-base ti tabler-lock me-1 fs-6"></i> Terkunci
            </span>
          @else
            <a href="{{ route('dashboard.peserta.form-minat') }}" class="btn btn-sm btn-outline-glass">
              <i class="icon-base ti tabler-pencil me-1"></i> Ubah
            </a>
          @endif
        </div>
        <hr class="dark-premium">
        <div class="row g-3">
          <div class="col-12">
            <span class="info-label d-block">Bidang Minat</span>
            <div>
              @if(!empty($profile->bidang_minat) && is_array($profile->bidang_minat))
                @foreach($profile->bidang_minat as $bidang)
                  <span class="chip-badge">
                    @if(is_string($bidang))
                      {{ $bidang }}
                    @elseif(is_array($bidang))
                      {{ $bidang['name'] ?? $bidang['bidang'] ?? json_encode($bidang) }}
                    @endif
                  </span>
                @endforeach
              @else
                <span class="text-body-premium small">—</span>
              @endif
            </div>
          </div>
          <div class="col-12">
            <span class="info-label d-block">Tujuan Pelatihan</span>
            <span class="info-value">{{ $profile->tujuan_pelatihan ?? '—' }}</span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Preferensi Jadwal</span>
            <span class="info-value">{{ $profile->preferensi_jadwal ?? '—' }}</span>
          </div>
          <div class="col-12 col-md-6">
            <span class="info-label d-block">Preferensi Mode</span>
            <span class="info-value">{{ $profile->preferensi_mode ?? '—' }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- SEKSI 5: Dokumen --}}
    <div class="col-12">
      <div class="glass-card-premium p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="d-flex align-items-center gap-2">
            <div class="stat-icon-box" style="background: rgba(6,182,212,0.12); color: #22d3ee;">
              <i class="icon-base ti tabler-file-check"></i>
            </div>
            <h5 class="fw-bold text-white mb-0">Dokumen</h5>
          </div>
          @if($isCompleted)
            <span class="badge bg-secondary bg-opacity-15 text-white-50 border border-white border-opacity-10 px-2.5 py-1 small" style="border-radius: 4px;">
              <i class="icon-base ti tabler-lock me-1 fs-6"></i> Terkunci
            </span>
          @else
            <a href="{{ route('dashboard.peserta.form-dokumen') }}" class="btn btn-sm btn-outline-glass">
              <i class="icon-base ti tabler-pencil me-1"></i> Ubah
            </a>
          @endif
        </div>
        <hr class="dark-premium">
        <div class="row g-3">
          <div class="col-12">
            <span class="info-label d-block">Jawaban Pertanyaan</span>
            @if(!empty($profile->jawaban_pertanyaan))
              @php
                $jawaban = is_string($profile->jawaban_pertanyaan) ? json_decode($profile->jawaban_pertanyaan, true) : $profile->jawaban_pertanyaan;
              @endphp
              @if(is_array($jawaban))
                @foreach($jawaban as $key => $value)
                  <div class="mb-2">
                    <span class="text-body-premium small d-block" style="font-size: 0.78rem;">{{ is_string($key) ? ucwords(str_replace('_', ' ', $key)) : 'Pertanyaan' }}</span>
                    <span class="info-value small">{{ is_string($value) ? $value : (is_array($value) ? json_encode($value) : '—') }}</span>
                  </div>
                @endforeach
              @else
                <span class="info-value">{{ Str::limit($profile->jawaban_pertanyaan, 150) }}</span>
              @endif
            @else
              <span class="text-body-premium small">—</span>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- SEKSI 6: Foto --}}
    <div class="col-12">
      <div class="glass-card-premium p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="d-flex align-items-center gap-2">
            <div class="stat-icon-box" style="background: rgba(245,158,11,0.12); color: #fbbf24;">
              <i class="icon-base ti tabler-camera"></i>
            </div>
            <h5 class="fw-bold text-white mb-0">Foto</h5>
          </div>
          @if($isCompleted)
            <span class="badge bg-secondary bg-opacity-15 text-white-50 border border-white border-opacity-10 px-2.5 py-1 small" style="border-radius: 4px;">
              <i class="icon-base ti tabler-lock me-1 fs-6"></i> Terkunci
            </span>
          @else
            <a href="{{ route('dashboard.peserta.upload-foto') }}" class="btn btn-sm btn-outline-glass">
              <i class="icon-base ti tabler-pencil me-1"></i> Ubah
            </a>
          @endif
        </div>
        <hr class="dark-premium">
        <div class="row g-4">
          {{-- Foto Diri --}}
          <div class="col-6 col-md-3">
            <div class="text-center">
              @if(!empty($user->google_drive_photo_url))
                <img src="{{ $user->google_drive_photo_url }}" alt="Foto Diri" class="doc-thumb mb-2">
              @elseif(!empty($profile->foto_profil))
                <img src="{{ Storage::url($profile->foto_profil) }}" alt="Foto Diri" class="doc-thumb mb-2">
              @else
                <div class="doc-thumb mb-2 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1);">
                  <i class="icon-base ti tabler-camera-off text-body-premium"></i>
                </div>
              @endif
              <span class="info-label d-block text-center">Foto Diri</span>
              @if(!empty($profile->foto_profil) || !empty($user->google_drive_photo_url))
                <span class="small text-success"><i class="icon-base ti tabler-circle-check me-1"></i>Tersimpan</span>
              @else
                <span class="small text-body-premium">Belum upload</span>
              @endif
            </div>
          </div>
          {{-- Foto KTP --}}
          <div class="col-6 col-md-3">
            <div class="text-center">
              @if(!empty($user->google_drive_ktp_url))
                <img src="{{ $user->google_drive_ktp_url }}" alt="Foto KTP" class="doc-thumb mb-2">
              @elseif(!empty($profile->scan_ktp))
                <img src="{{ Storage::url($profile->scan_ktp) }}" alt="Foto KTP" class="doc-thumb mb-2">
              @else
                <div class="doc-thumb mb-2 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1);">
                  <i class="icon-base ti tabler-camera-off text-body-premium"></i>
                </div>
              @endif
              <span class="info-label d-block text-center">Foto KTP</span>
              @if(!empty($profile->scan_ktp) || !empty($user->google_drive_ktp_url))
                <span class="small text-success"><i class="icon-base ti tabler-circle-check me-1"></i>Tersimpan</span>
              @else
                <span class="small text-body-premium">Belum upload</span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  @endif

</div>
</div>
@endsection
