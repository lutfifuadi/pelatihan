@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Kalender Jadwal Pelatihan')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/fullcalendar/fullcalendar.scss'])
@endsection

@section('page-style')
<style>
  /* --- Dark Theme FullCalendar Overrides --- */
  .content-wrapper {
    color: #f8fafc;
    position: relative !important;
    overflow: hidden !important;
  }
  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container {
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

  /* Glass card style */
  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border-radius: 5px !important;
    position: relative;
    z-index: 1;
  }
  .text-body-premium {
    color: rgba(255, 255, 255, 0.65) !important;
  }

  /* Floating Orbs */
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
    width: 450px; height: 450px;
    background: radial-gradient(circle, #6366f1 0%, rgba(99,102,241,0) 70%);
    top: -10%; left: -10%;
    animation-duration: 20s;
  }
  .orb-2 {
    width: 550px; height: 550px;
    background: radial-gradient(circle, #ec4899 0%, rgba(236,72,153,0) 70%);
    bottom: 5%; right: -10%;
    animation-duration: 28s;
  }
  .orb-3 {
    width: 350px; height: 350px;
    background: radial-gradient(circle, #06b6d4 0%, rgba(6,182,212,0) 70%);
    top: 35%; left: 25%;
    animation-duration: 24s;
  }
  @keyframes orbFloat {
    0%   { transform: translate(0,0) scale(1) rotate(0deg); }
    50%  { transform: translate(60px,40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px,-50px) scale(0.92) rotate(360deg); }
  }

  /* FullCalendar dark overrides */
  #calendar {
    min-height: 700px;
  }
  #calendar .fc {
    --fc-page-bg-color: transparent;
    --fc-neutral-bg-color: rgba(255,255,255,0.03);
    --fc-neutral-text-color: rgba(255,255,255,0.6);
    --fc-border-color: rgba(255,255,255,0.08);
    --fc-button-text-color: #f8fafc;
    --fc-button-bg-color: rgba(99,102,241,0.15);
    --fc-button-border-color: rgba(99,102,241,0.3);
    --fc-button-hover-bg-color: rgba(99,102,241,0.25);
    --fc-button-hover-border-color: rgba(99,102,241,0.4);
    --fc-button-active-bg-color: rgba(99,102,241,0.35);
    --fc-button-active-border-color: rgba(99,102,241,0.5);
    --fc-event-bg-color: #7367f0;
    --fc-event-border-color: #7367f0;
    --fc-event-text-color: #fff;
    --fc-event-selected-overlay-color: rgba(0,0,0,0.25);
    --fc-more-link-bg-color: rgba(255,255,255,0.05);
    --fc-more-link-text-color: #e2e8f0;
    --fc-today-bg-color: rgba(99,102,241,0.1);
    --fc-list-event-hover-bg-color: rgba(255,255,255,0.03);
    --fc-highlight-color: rgba(99,102,241,0.08);
    --fc-non-business-color: rgba(255,255,255,0.02);
  }
  #calendar .fc .fc-toolbar-title {
    color: #f8fafc;
    font-size: 1.25rem;
    font-weight: 700;
  }
  #calendar .fc .fc-col-header-cell-cushion {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
  }
  #calendar .fc .fc-daygrid-day-number {
    color: rgba(255,255,255,0.6);
    text-decoration: none;
  }
  #calendar .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
    color: #a5b4fc;
    font-weight: 700;
  }
  #calendar .fc .fc-day-other .fc-daygrid-day-top {
    opacity: 0.35;
  }
  #calendar .fc .fc-day-other .fc-daygrid-day-number {
    color: rgba(255,255,255,0.25);
  }
  #calendar .fc .fc-button {
    border-radius: 5px;
    font-weight: 500;
    text-transform: capitalize;
    border: 1px solid rgba(255,255,255,0.08);
  }
  #calendar .fc .fc-button-primary:not(:disabled).fc-button-active,
  #calendar .fc .fc-button-primary:not(:disabled):active {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border-color: transparent !important;
    box-shadow: 0 4px 15px rgba(99,102,241,0.3) !important;
    color: #fff !important;
  }
  #calendar .fc .fc-prev-button,
  #calendar .fc .fc-next-button {
    background: rgba(255,255,255,0.05);
    border-color: rgba(255,255,255,0.08);
    color: #f8fafc;
  }
  #calendar .fc .fc-prev-button:hover,
  #calendar .fc .fc-next-button:hover {
    background: rgba(255,255,255,0.1);
  }
  #calendar .fc .fc-prev-button .fc-icon,
  #calendar .fc .fc-next-button .fc-icon {
    color: #f8fafc;
  }
  #calendar .fc .fc-today-button {
    background: rgba(99,102,241,0.15);
    border-color: rgba(99,102,241,0.3);
    color: #a5b4fc;
  }
  #calendar .fc .fc-today-button:disabled {
    opacity: 0.4;
  }
  #calendar .fc .fc-today-button:hover:not(:disabled) {
    background: rgba(99,102,241,0.25);
  }
  #calendar .fc .fc-popover {
    background: #1e293b;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
  }
  #calendar .fc .fc-popover-header {
    background: rgba(15,23,42,0.9);
    color: #f8fafc;
    padding: 8px 12px;
  }
  #calendar .fc .fc-popover-body {
    background: #1e293b;
  }
  #calendar .fc .fc-more-popover .fc-popover-body {
    padding: 8px;
  }
  #calendar .fc .fc-list-empty {
    background: transparent;
    color: rgba(255,255,255,0.5);
  }
  #calendar .fc .fc-list-day-cushion {
    background: rgba(255,255,255,0.03);
  }
  #calendar .fc .fc-list-day-text,
  #calendar .fc .fc-list-day-side-text {
    color: #f8fafc;
  }
  #calendar .fc .fc-list-event:hover td {
    background: rgba(255,255,255,0.03);
  }
  #calendar .fc .fc-list-event-dot {
    border-color: #7367f0;
  }
  #calendar .fc .fc-scrollgrid {
    border-color: rgba(255,255,255,0.08);
  }
  #calendar .fc .fc-scrollgrid-section-header > td,
  #calendar .fc .fc-scrollgrid-section-footer > td {
    border-color: rgba(255,255,255,0.08);
  }
  #calendar .fc .fc-timegrid-axis,
  #calendar .fc .fc-timegrid-slot {
    border-color: rgba(255,255,255,0.06);
  }
  #calendar .fc .fc-timegrid-slot-label-cushion {
    color: rgba(255,255,255,0.5);
  }
  #calendar .fc .fc-timegrid-axis-cushion {
    color: rgba(255,255,255,0.5);
  }

  /* Pelatihan range background events */
  #calendar .fc .pelatihan-range-event {
    opacity: 0.6;
    border-radius: 4px;
  }

  /* Event styling */
  #calendar .fc .fc-event {
    border-radius: 4px;
    padding: 2px 6px;
    font-size: 0.8rem;
    cursor: pointer;
    border: none;
  }
  #calendar .fc .fc-daygrid-event {
    margin: 2px 4px;
  }
  #calendar .fc .fc-event .fc-event-title {
    font-weight: 600;
  }
  #calendar .fc .fc-event .fc-event-time {
    font-weight: 500;
  }
  #calendar .fc .fc-daygrid-event-harness + .fc-daygrid-event-harness {
    margin-top: 2px;
  }

  /* Modal styling */
  .modal-content-premium {
    background: #1e293b !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    border-radius: 8px !important;
  }
  .modal-content-premium .modal-header {
    border-bottom: 1px solid rgba(255,255,255,0.08);
    padding: 1rem 1.5rem;
  }
  .modal-content-premium .modal-header .modal-title {
    color: #f8fafc;
    font-weight: 700;
  }
  .modal-content-premium .modal-header .btn-close {
    filter: invert(1) brightness(2);
  }
  .modal-content-premium .modal-body {
    padding: 1.5rem;
    color: rgba(255,255,255,0.8);
  }
  .modal-content-premium .modal-footer {
    border-top: 1px solid rgba(255,255,255,0.08);
    padding: 1rem 1.5rem;
  }
  .modal-content-premium .form-label {
    color: rgba(255,255,255,0.7);
    font-weight: 500;
    font-size: 0.85rem;
    margin-bottom: 0.4rem;
  }
  .modal-content-premium .form-control,
  .modal-content-premium .form-select {
    background: rgba(15,23,42,0.6);
    border: 1px solid rgba(255,255,255,0.1);
    color: #f8fafc;
    border-radius: 5px;
    padding: 0.5rem 0.75rem;
    font-size: 0.9rem;
  }
  .modal-content-premium .form-control:focus,
  .modal-content-premium .form-select:focus {
    background: rgba(15,23,42,0.8);
    border-color: rgba(99,102,241,0.5);
    box-shadow: 0 0 0 2px rgba(99,102,241,0.15);
    color: #f8fafc;
  }
  .modal-content-premium .form-control::placeholder {
    color: rgba(255,255,255,0.3);
  }
  .modal-content-premium .form-select option {
    background: #1e293b;
    color: #f8fafc;
  }
  .modal-content-premium textarea.form-control {
    min-height: 80px;
    resize: vertical;
  }
  .modal-content-premium .invalid-feedback {
    color: #f87171;
    font-size: 0.8rem;
  }

  .btn-modal-primary {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border: none;
    color: #fff !important;
    font-weight: 600;
    border-radius: 5px;
    padding: 0.5rem 1.25rem;
  }
  .btn-modal-primary:hover {
    box-shadow: 0 4px 15px rgba(99,102,241,0.4);
    transform: translateY(-1px);
  }
  .btn-modal-secondary {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.7);
    border-radius: 5px;
    padding: 0.5rem 1.25rem;
  }
  .btn-modal-secondary:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
  }
  .btn-modal-danger {
    background: linear-gradient(135deg, #ef4444, #b91c1c) !important;
    border: none;
    color: #fff !important;
    font-weight: 600;
    border-radius: 5px;
    padding: 0.5rem 1.25rem;
  }

  .detail-label {
    color: rgba(255,255,255,0.5);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.2rem;
  }
  .detail-value {
    color: #f8fafc;
    font-size: 0.95rem;
    margin-bottom: 1rem;
  }

  .badge-tipe {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
  }
  .badge-tipe-offline {
    background: rgba(99,102,241,0.15);
    color: #a5b4fc;
    border: 1px solid rgba(99,102,241,0.3);
  }
  .badge-tipe-online {
    background: rgba(16,185,129,0.15);
    color: #34d399;
    border: 1px solid rgba(16,185,129,0.3);
  }

  /* Loading spinner */
  .fc-loading-spinner {
    display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 5;
  }
  .fc-loading-spinner.active {
    display: block;
  }
</style>
@endsection

@section('content')
<!-- Floating Background Orbs -->
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

  <!-- Title Section -->
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box" style="width:52px;height:52px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;flex-shrink:0;background:rgba(99,102,241,0.12);color:#6366f1;">
          <i class="icon-base ti tabler-calendar-event fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0">Kalender Jadwal Pelatihan</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size:0.95rem;">
            Kelola jadwal pertemuan pelatihan dengan kalender interaktif
          </p>
        </div>
      </div>
      <button type="button" class="btn btn-modal-primary px-4 py-2 d-flex align-items-center gap-2" onclick="openCreateModal()">
        <i class="icon-base ti tabler-plus"></i> Tambah Jadwal
      </button>
    </div>
  </div>

  <!-- Alert Messages -->
  <div id="alert-container"></div>

  <!-- Calendar Card -->
  <div class="glass-card-premium px-4 py-4 position-relative">
    <div id="calendar"></div>
  </div>

</div>

{{-- ===== MODAL TAMBAH / EDIT JADWAL ===== --}}
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-content-premium">
      <form id="scheduleForm" autocomplete="off">
        @csrf
        <input type="hidden" name="id" id="scheduleId" value="">
        <input type="hidden" name="_method" id="formMethod" value="POST">

        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Tambah Jadwal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <!-- Pelatihan -->
            <div class="col-12">
              <label for="pelatihan_id" class="form-label">Pelatihan <span class="text-danger">*</span></label>
              <select name="pelatihan_id" id="pelatihan_id" class="form-select" required>
                <option value="">-- Pilih Pelatihan --</option>
                @foreach($pelatihans as $pelatihan)
                  <option value="{{ $pelatihan->id }}">{{ $pelatihan->nama }} ({{ $pelatihan->batch }})</option>
                @endforeach
              </select>
              <div class="invalid-feedback" id="pelatihan_id_error"></div>
            </div>

            <!-- Judul -->
            <div class="col-md-8">
              <label for="judul" class="form-label">Judul Sesi <span class="text-danger">*</span></label>
              <input type="text" name="judul" id="judul" class="form-control" placeholder="Mis: Pembukaan & Perkenalan" required>
              <div class="invalid-feedback" id="judul_error"></div>
            </div>

            <!-- Pertemuan Ke -->
            <div class="col-md-4">
              <label for="pertemuan_ke" class="form-label">Pertemuan Ke</label>
              <input type="number" name="pertemuan_ke" id="pertemuan_ke" class="form-control" placeholder="1" min="1">
            </div>

            <!-- Tanggal -->
            <div class="col-md-6">
              <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
              <input type="date" name="tanggal" id="tanggal" class="form-control" required>
              <div class="invalid-feedback" id="tanggal_error"></div>
            </div>

            <!-- Tipe -->
            <div class="col-md-6">
              <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
              <select name="tipe" id="tipe" class="form-select" required>
                <option value="offline">Offline</option>
                <option value="online">Online</option>
              </select>
            </div>

            <!-- Waktu Mulai -->
            <div class="col-md-6">
              <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
              <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control">
            </div>

            <!-- Waktu Selesai -->
            <div class="col-md-6">
              <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
              <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control">
              <div class="invalid-feedback" id="waktu_selesai_error"></div>
            </div>

            <!-- Lokasi -->
            <div class="col-md-12">
              <label for="lokasi" class="form-label">Lokasi / Tempat</label>
              <input type="text" name="lokasi" id="lokasi" class="form-control" placeholder="Mis: Ruang A lt. 2">
            </div>

            <!-- Deskripsi -->
            <div class="col-12">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Deskripsi sesi pertemuan..."></textarea>
            </div>

            <!-- Active -->
            <div class="col-12">
              <div class="form-check">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                <label class="form-check-label text-white" for="is_active">Aktif</label>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-modal-primary" id="btnSubmit">
            <span id="btnSubmitText">Simpan</span>
            <span id="btnSubmitLoading" class="spinner-border spinner-border-sm d-none" role="status"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ===== MODAL DETAIL JADWAL ===== --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content modal-content-premium">
      <div class="modal-header">
        <h5 class="modal-title">Detail Jadwal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="detailBody">
        <!-- Populated by JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-modal-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-modal-primary" id="btnEditFromDetail">Edit</button>
        <button type="button" class="btn btn-modal-danger" id="btnDeleteFromDetail">Hapus</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/fullcalendar/fullcalendar.js'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
<script>
  // ===== GLOBALS =====
  let calendar = null;
  let currentEventData = null;
  let isEditing = false;

  // ===== SWEETALERT2 DARK THEME =====
  const swalDark = Swal.mixin({
    background: '#1e293b',
    color: '#f8fafc',
    confirmButtonColor: '#6366f1',
    cancelButtonColor: '#6b7280',
    iconColor: '#a5b4fc',
    customClass: {
      popup: 'rounded-3 shadow-lg',
      title: 'fw-bold',
      confirmButton: 'btn btn-modal-primary me-2 border-0',
      cancelButton: 'btn btn-modal-secondary border-0',
    },
    buttonsStyling: false,
  });

  // ===== INIT FULLCALENDAR =====
  document.addEventListener('DOMContentLoaded', function() {
    initCalendar();
  });

  function initCalendar() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    calendar = new Calendar(calendarEl, {
      plugins: [dayGridPlugin, timegridPlugin, listPlugin, interactionPlugin],
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      events: {
        url: '{{ route('admin.schedules.data') }}',
        method: 'GET',
        extraParams: {},
        failure: function() {
          console.error('Gagal memuat data kalender');
        },
      },
      editable: false,
      selectable: true,
      dayMaxEvents: true,
      navLinks: true,
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        meridiem: 'short'
      },
      height: 'auto',
      contentHeight: 'auto',
      aspectRatio: 1.8,
      locale: 'id',
      firstDay: 1, // Monday
      buttonText: {
        today: 'Hari Ini',
        month: 'Bulan',
        week: 'Minggu',
        day: 'Hari',
        list: 'Agenda'
      },
      noEventsText: 'Tidak ada jadwal',
      moreLinkText: function(n) {
        return '+ ' + n + ' lagi';
      },
      loading: function(isLoading) {
        if (isLoading) {
          // Show loading state
        }
      },

      // Click event untuk lihat detail
      eventClick: function(info) {
        if (info.event.extendedProps.is_range) {
          return; // Skip background range events
        }
        showEventDetail(info.event);
      },

      // Click tanggal untuk tambah jadwal
      dateClick: function(info) {
        openCreateModal(info.dateStr);
      },
    });

    calendar.render();
  }

  // ===== FUNGSI REFRESH CALENDAR =====
  function refreshCalendar() {
    if (calendar) {
      calendar.refetchEvents();
    }
  }

  // ===== OPEN CREATE MODAL =====
  function openCreateModal(dateStr = null) {
    isEditing = false;
    document.getElementById('modalTitle').textContent = 'Tambah Jadwal';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('scheduleId').value = '';
    document.getElementById('btnSubmitText').textContent = 'Simpan';

    // Reset form
    document.getElementById('scheduleForm').reset();
    clearErrors();
    document.getElementById('is_active').checked = true;

    // Set date if provided
    if (dateStr) {
      document.getElementById('tanggal').value = dateStr;
    }

    const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
    modal.show();
  }

  // ===== OPEN EDIT MODAL =====
  function openEditModal(eventData) {
    isEditing = true;
    const props = eventData.extendedProps;

    document.getElementById('modalTitle').textContent = 'Edit Jadwal';
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('scheduleId').value = eventData.id;
    document.getElementById('btnSubmitText').textContent = 'Perbarui';

    // Populate form
    document.getElementById('pelatihan_id').value = props.pelatihan_id || '';
    document.getElementById('judul').value = props.judul || '';
    document.getElementById('tanggal').value = props.tanggal || '';
    document.getElementById('waktu_mulai').value = props.waktu_mulai && props.waktu_mulai !== '-' ? props.waktu_mulai : '';
    document.getElementById('waktu_selesai').value = props.waktu_selesai && props.waktu_selesai !== '-' ? props.waktu_selesai : '';
    document.getElementById('lokasi').value = props.lokasi || '';
    document.getElementById('tipe').value = props.tipe || 'offline';
    document.getElementById('pertemuan_ke').value = props.pertemuan_ke || '';
    document.getElementById('deskripsi').value = props.deskripsi || '';
    document.getElementById('is_active').checked = props.is_active !== false;

    clearErrors();

    const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
    modal.show();
  }

  // ===== SHOW EVENT DETAIL =====
  function showEventDetail(event) {
    currentEventData = event;
    const props = event.extendedProps;

    const tipeBadge = props.tipe === 'online'
      ? '<span class="badge-tipe badge-tipe-online"><i class="icon-base ti tabler-video me-1"></i> Online</span>'
      : '<span class="badge-tipe badge-tipe-offline"><i class="icon-base ti tabler-building me-1"></i> Offline</span>';

    const html = `
      <div>
        <div class="detail-label">Pelatihan</div>
        <div class="detail-value fw-semibold">
          <i class="icon-base ti tabler-book-2 me-1" style="color:#a5b4fc;"></i>
          ${props.pelatihan || '-'}
        </div>

        <div class="detail-label">Judul Sesi</div>
        <div class="detail-value fw-semibold" style="font-size:1.1rem;">
          ${props.judul || '-'}
          ${props.pertemuan_ke ? `<span class="badge-tipe badge-tipe-offline ms-2">Pertemuan ${props.pertemuan_ke}</span>` : ''}
        </div>

        <div class="row g-3 mt-1">
          <div class="col-6">
            <div class="detail-label">Tanggal</div>
            <div class="detail-value">
              <i class="icon-base ti tabler-calendar me-1" style="color:#a5b4fc;"></i>
              ${props.tanggal || '-'}
            </div>
          </div>
          <div class="col-6">
            <div class="detail-label">Tipe</div>
            <div class="detail-value">${tipeBadge}</div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-6">
            <div class="detail-label">Waktu Mulai</div>
            <div class="detail-value">
              <i class="icon-base ti tabler-clock me-1" style="color:#a5b4fc;"></i>
              ${props.waktu_mulai && props.waktu_mulai !== '-' ? props.waktu_mulai : '-'}
            </div>
          </div>
          <div class="col-6">
            <div class="detail-label">Waktu Selesai</div>
            <div class="detail-value">
              <i class="icon-base ti tabler-clock me-1" style="color:#a5b4fc;"></i>
              ${props.waktu_selesai && props.waktu_selesai !== '-' ? props.waktu_selesai : '-'}
            </div>
          </div>
        </div>

        ${props.lokasi ? `
        <div class="detail-label">Lokasi</div>
        <div class="detail-value">
          <i class="icon-base ti tabler-map-pin me-1" style="color:#a5b4fc;"></i>
          ${props.lokasi}
        </div>
        ` : ''}

        ${props.deskripsi ? `
        <div class="detail-label">Deskripsi</div>
        <div class="detail-value" style="background:rgba(255,255,255,0.03); padding:0.75rem; border-radius:5px; border:1px solid rgba(255,255,255,0.06);">
          ${props.deskripsi}
        </div>
        ` : ''}
      </div>
    `;

    document.getElementById('detailBody').innerHTML = html;

    // Setup buttons
    document.getElementById('btnEditFromDetail').onclick = function() {
      const detailModal = bootstrap.Modal.getInstance(document.getElementById('detailModal'));
      detailModal.hide();
      setTimeout(() => openEditModal(event), 300);
    };

    document.getElementById('btnDeleteFromDetail').onclick = function() {
      const detailModal = bootstrap.Modal.getInstance(document.getElementById('detailModal'));
      detailModal.hide();
      setTimeout(() => deleteSchedule(event.id, event.title), 300);
    };

    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
  }

  // ===== FORM SUBMIT (Create / Update) =====
  document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    clearErrors();

    const id = document.getElementById('scheduleId').value;
    const method = document.getElementById('formMethod').value;
    const isEdit = method === 'PUT';

    let url = '{{ route('admin.schedules.store') }}';
    if (isEdit) {
      url = '{{ route('admin.schedules.update', ':id') }}'.replace(':id', id);
    }

    const formData = new FormData(this);
    formData.set('_method', method);
    formData.set('is_active', document.getElementById('is_active').checked ? '1' : '0');

    // Loading state
    document.getElementById('btnSubmit').disabled = true;
    document.getElementById('btnSubmitText').classList.add('d-none');
    document.getElementById('btnSubmitLoading').classList.remove('d-none');

    fetch(url, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
      body: formData,
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('scheduleModal'));
        modal.hide();

        swalDark.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: data.message,
          timer: 1500,
          showConfirmButton: false,
        });

        refreshCalendar();
      } else {
        swalDark.fire({
          icon: 'error',
          title: 'Gagal!',
          text: data.message || 'Terjadi kesalahan.',
        });
      }
    })
    .catch(error => {
      if (error.response && error.response.status === 422) {
        // Validation errors
        const errors = error.response.errors;
        Object.keys(errors).forEach(field => {
          const errorEl = document.getElementById(field + '_error');
          const inputEl = document.getElementById(field);
          if (errorEl) errorEl.textContent = errors[field][0];
          if (inputEl) inputEl.classList.add('is-invalid');
        });
      } else {
        swalDark.fire({
          icon: 'error',
          title: 'Gagal!',
          text: 'Terjadi kesalahan server. Silakan coba lagi.',
        });
      }
    })
    .finally(() => {
      document.getElementById('btnSubmit').disabled = false;
      document.getElementById('btnSubmitText').classList.remove('d-none');
      document.getElementById('btnSubmitLoading').classList.add('d-none');
    });
  });

  // ===== DELETE SCHEDULE =====
  function deleteSchedule(id, title) {
    swalDark.fire({
      title: 'Hapus Jadwal?',
      text: `Yakin ingin menghapus "${title}"?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        const url = '{{ route('admin.schedules.destroy', ':id') }}'.replace(':id', id);

        fetch(url, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            _method: 'DELETE',
            _token: '{{ csrf_token() }}',
          }),
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            swalDark.fire({
              icon: 'success',
              title: 'Dihapus!',
              text: data.message,
              timer: 1500,
              showConfirmButton: false,
            });
            refreshCalendar();
          } else {
            swalDark.fire({
              icon: 'error',
              title: 'Gagal!',
              text: data.message || 'Terjadi kesalahan.',
            });
          }
        })
        .catch(() => {
          swalDark.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan server.',
          });
        });
      }
    });
  }

  // ===== CLEAR ERRORS =====
  function clearErrors() {
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
  }
</script>
@endsection
