@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Rekap Kehadiran - ' . $pelatihan->nama)

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

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

  /* --- LAYOUT OVERRIDES FOR LANDING PAGE ALIGNMENT --- */
  html,
  body,
  .layout-page,
  .content-wrapper,
  .layout-wrapper,
  .layout-container {
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

  /* Sidebar styling */
  .layout-menu,
  #layout-menu {
    background-color: #0b0f19 !important;
    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
  }
  .layout-menu .app-brand {
    background-color: #0b0f19 !important;
  }
  .layout-menu .menu-inner {
    background-color: #0b0f19 !important;
  }
  .layout-menu .menu-link {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  .layout-menu .menu-item.active > .menu-link {
    color: #ffffff !important;
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3) !important;
  }
  .layout-menu .menu-item.active > .menu-link i {
    color: #ffffff !important;
  }
  .layout-menu .menu-header-text {
    color: rgba(255, 255, 255, 0.4) !important;
  }
  .layout-menu .menu-link:hover {
    background-color: rgba(255, 255, 255, 0.04) !important;
    color: #ffffff !important;
  }
  .layout-menu .menu-inner-shadow {
    background: linear-gradient(#0b0f19 5%, rgba(11, 15, 25, 0) 95%) !important;
  }
  .layout-menu .app-brand .app-brand-text {
    color: #ffffff !important;
  }

  /* Top Navbar styling */
  .layout-navbar,
  #layout-navbar {
    background: rgba(15, 23, 42, 0.45) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
  }
  .navbar-detached {
    background: rgba(15, 23, 42, 0.45) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    margin-top: 12px !important;
  }
  #layout-navbar .nav-link {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  #layout-navbar .nav-link:hover {
    color: #ffffff !important;
  }

  /* Dynamic Floating Orbs */
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
  .orb-1 {
    width: 450px;
    height: 450px;
    background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
    top: -10%;
    left: -10%;
    animation-duration: 20s;
  }
  .orb-2 {
    width: 550px;
    height: 550px;
    background: radial-gradient(circle, #ec4899 0%, rgba(236, 72, 153, 0) 70%);
    bottom: 5%;
    right: -10%;
    animation-duration: 28s;
  }
  .orb-3 {
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, #06b6d4 0%, rgba(6, 182, 212, 0) 70%);
    top: 35%;
    left: 25%;
    animation-duration: 24s;
  }
  @keyframes orbFloat {
    0% { transform: translate(0, 0) scale(1) rotate(0deg); }
    50% { transform: translate(60px, 40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px, -50px) scale(0.92) rotate(360deg); }
  }

  .text-body-premium {
    color: rgba(255, 255, 255, 0.65) !important;
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
  .glass-card-premium:hover {
    transform: translateY(-2px) !important;
    border-color: rgba(99, 102, 241, 0.2) !important;
  }

  .stat-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 5px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    flex-shrink: 0;
  }

  .stat-icon-primary {
    background: rgba(99, 102, 241, 0.12);
    color: #6366f1;
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
  .badge-premium-info {
    background: rgba(59, 130, 246, 0.15);
    border-color: rgba(59, 130, 246, 0.3);
    color: #60a5fa;
  }

  .btn-glow-premium {
    background: linear-gradient(135deg, #ffc107, #ff9800) !important;
    border: none;
    color: #0b0f19 !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
    transition: all 0.3s ease;
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.4);
    background: linear-gradient(135deg, #ffca28, #ffa726) !important;
    color: #0b0f19 !important;
  }

  /* Custom input styling for Dark Premium Modal */
  .modal-premium {
    background: rgba(15, 23, 42, 0.9) !important;
    backdrop-filter: blur(25px) !important;
    -webkit-backdrop-filter: blur(25px) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5) !important;
    color: #ffffff;
  }

  .modal-premium .modal-header,
  .modal-premium .modal-footer {
    border-color: rgba(255, 255, 255, 0.08) !important;
  }

  .form-control-premium {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
  }
  .form-control-premium:focus {
    background: rgba(255, 255, 255, 0.05) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
  }
  .form-select-premium {
    background-color: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
  }
  .form-select-premium:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25) !important;
  }
</style>
@endsection

@section('content')
  <!-- Floating Background Orbs -->
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <!-- Content Wrapper -->
  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
    
    <!-- Title Section -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-primary">
            <i class="icon-base ti tabler-award fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">{{ $pelatihan->nama }}</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Dinas: {{ $pelatihan->dinas->nama_dinas ?? '-' }} | Rekap Absensi Peserta (Confirmed Only)
            </p>
          </div>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('admin.exports.attendance.excel', $pelatihan) }}" class="btn btn-success d-flex align-items-center gap-2 px-3 py-2" style="border-radius: 5px;">
            <i class="icon-base ti tabler-file-spreadsheet"></i> Export Excel
          </a>
          <a href="{{ route('admin.exports.attendance.pdf', $pelatihan) }}" class="btn btn-danger d-flex align-items-center gap-2 px-3 py-2" style="border-radius: 5px;">
            <i class="icon-base ti tabler-file-type-pdf"></i> Export PDF
          </a>
          <a href="{{ route('admin.presensi.index') }}" class="btn btn-secondary px-3 py-2" style="border-radius: 5px;">
            Kembali
          </a>
        </div>
      </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
          <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Attendance Grid Table -->
    <div class="col-12">
      <div class="glass-card-premium px-4 py-4">
        <div class="table-responsive">
          <table class="table table-borderless text-white align-middle">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <th class="text-body-premium small fw-semibold px-0" style="width: 60px;">No</th>
                <th class="text-body-premium small fw-semibold">Nama Peserta</th>
                <th class="text-body-premium small fw-semibold">NIK</th>
                @foreach($pertemuans as $p)
                  <th class="text-body-premium small fw-semibold text-center">Pertemuan {{ $p }}</th>
                @endforeach
                <th class="text-body-premium small fw-semibold text-end px-0" style="width: 100px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($enrollments as $index => $enrollment)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                  <td class="px-0 py-3 text-body-premium">{{ $index + 1 }}</td>
                  <td class="py-3 fw-semibold text-white text-nowrap">
                    {{ $enrollment->user->name ?? 'N/A' }}
                  </td>
                  <td class="py-3 text-body-premium text-nowrap">{{ $enrollment->user->nik ?? '-' }}</td>
                  
                  @foreach($pertemuans as $p)
                    @php
                      $attendance = $enrollment->attendances->firstWhere('pertemuan_ke', $p);
                      $status = $attendance ? $attendance->status : 'belum_absen';
                    @endphp
                    <td class="py-3 text-center">
                      @if($status === 'hadir')
                        <span class="badge-premium badge-premium-success">Hadir</span>
                      @elseif($status === 'sakit')
                        <span class="badge-premium badge-premium-info">Sakit</span>
                      @elseif($status === 'izin')
                        <span class="badge-premium badge-premium-warning">Izin</span>
                      @elseif($status === 'alpa')
                        <span class="badge-premium badge-premium-danger">Alpa</span>
                      @else
                        <span class="badge-premium" style="opacity: 0.5;">Belum Absen</span>
                      @endif
                    </td>
                  @endforeach

                  <td class="text-end px-0 py-3">
                    <button type="button" class="btn btn-primary btn-sm px-2 py-1" style="border-radius: 4px; font-size: 0.8rem;" 
                            data-bs-toggle="modal" data-bs-target="#koreksiModal" 
                            data-enrollment-id="{{ $enrollment->id }}" 
                            data-user-name="{{ $enrollment->user->name ?? 'N/A' }}">
                      Koreksi
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="{{ 3 + count($pertemuans) + 1 }}" class="text-center text-body-premium py-5">
                    <i class="icon-base ti tabler-user-off fs-1 mb-2 d-block text-warning"></i>
                    Belum ada data peserta dengan status Terkonfirmasi (Confirmed).
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Koreksi Kehadiran -->
  <div class="modal fade" id="koreksiModal" tabindex="-1" aria-labelledby="koreksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-premium">
        <form action="{{ route('admin.presensi.koreksi') }}" method="POST">
          @csrf
          <input type="hidden" name="enrollment_id" id="modalEnrollmentId">
          <div class="modal-header">
            <h5 class="modal-title text-white" id="koreksiModalLabel">Koreksi Kehadiran</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label text-body-premium">Nama Peserta</label>
              <input type="text" class="form-control form-control-premium" id="modalUserName" readonly>
            </div>
            <div class="mb-3">
              <label for="pertemuan_ke" class="form-label text-body-premium">Pertemuan Ke</label>
              <select name="pertemuan_ke" id="pertemuan_ke" class="form-select form-select-premium" required>
                @foreach($pertemuans as $p)
                  <option value="{{ $p }}">Pertemuan {{ $p }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label text-body-premium">Status Kehadiran</label>
              <select name="status" id="status" class="form-select form-select-premium" required>
                <option value="hadir">Hadir</option>
                <option value="sakit">Sakit</option>
                <option value="izin">Izin</option>
                <option value="alpa">Alpa</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="bypass_reason" class="form-label text-body-premium">Alasan Koreksi</label>
              <textarea name="bypass_reason" id="bypass_reason" class="form-control form-control-premium" rows="3" required placeholder="Tuliskan alasan koreksi kehadiran..."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 5px;">Tutup</button>
            <button type="submit" class="btn btn-primary" style="border-radius: 5px;">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const koreksiModal = document.getElementById('koreksiModal');
      koreksiModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const enrollmentId = button.getAttribute('data-enrollment-id');
        const userName = button.getAttribute('data-user-name');

        const modalEnrollmentIdInput = koreksiModal.querySelector('#modalEnrollmentId');
        const modalUserNameInput = koreksiModal.querySelector('#modalUserName');

        modalEnrollmentIdInput.value = enrollmentId;
        modalUserNameInput.value = userName;
      });
    });
  </script>
  @endsection
@endsection
