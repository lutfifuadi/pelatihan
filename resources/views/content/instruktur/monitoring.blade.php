@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Layar Monitoring Presensi')

@section('page-style')
<style>
  /* ============================================
     Monitoring Page – Vuexy-Compatible Styling
     Menggunakan CSS variable Vuexy, tidak ada
     override background/font global.
  ============================================ */

  /* Subtle card enhancement – tetap pakai card Vuexy */
  .monitoring-card {
    border: 1px solid var(--bs-border-color);
    border-radius: 0.5rem;
    transition: box-shadow 0.2s ease;
  }

  .monitoring-card:hover {
    box-shadow: 0 4px 20px rgba(var(--bs-primary-rgb), 0.12);
  }

  /* Left panel top accent line */
  .monitoring-card-accent {
    border-top: 3px solid var(--bs-primary);
  }

  /* Circular Progress Bar */
  .circular-progress {
    position: relative;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: conic-gradient(var(--bs-primary) 0%, var(--bs-border-color) 0%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 8px 24px rgba(var(--bs-primary-rgb), 0.2);
    transition: background 1s ease-in-out;
  }

  .circular-progress::before {
    content: "";
    position: absolute;
    width: 184px;
    height: 184px;
    border-radius: 50%;
    background-color: var(--bs-card-bg, var(--bs-body-bg));
  }

  .circular-progress-content {
    position: relative;
    z-index: 10;
    text-align: center;
  }

  /* Stat dividers – adaptif theme */
  .stat-divider {
    border-color: var(--bs-border-color) !important;
  }

  /* Participant grid item */
  .participant-grid-item {
    background: var(--bs-tertiary-bg, rgba(var(--bs-primary-rgb), 0.03));
    border: 1px solid var(--bs-border-color);
    border-radius: 8px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.3s ease;
    animation: slide-in 0.4s ease-out forwards;
  }

  .participant-grid-item:hover {
    border-color: rgba(var(--bs-primary-rgb), 0.4);
    background: rgba(var(--bs-primary-rgb), 0.05);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.1);
  }

  .avatar-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-purple, #8250df));
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
    color: #fff;
    flex-shrink: 0;
    border: 2px solid rgba(var(--bs-primary-rgb), 0.2);
  }

  /* Slide-in animation */
  @keyframes slide-in {
    from {
      opacity: 0;
      transform: translateY(16px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Scrollable participant list */
  .participant-scroll {
    max-height: 520px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--bs-primary) transparent;
  }

  .participant-scroll::-webkit-scrollbar {
    width: 5px;
  }

  .participant-scroll::-webkit-scrollbar-track {
    background: transparent;
  }

  .participant-scroll::-webkit-scrollbar-thumb {
    background: var(--bs-primary);
    border-radius: 99px;
  }

  /* Live pulse badge */
  .live-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .live-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: var(--bs-success);
    animation: pulse-dot 1.5s infinite;
    flex-shrink: 0;
  }

  @keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.75); }
  }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ auth()->user()->role === 'admin' ? route('dashboard.admin') : route('dashboard.instruktur') }}">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Monitoring Presensi</li>
    </ol>
  </nav>

  {{-- Header --}}
  <div class="d-flex align-items-start align-items-sm-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h3 class="fw-bold text-body mb-1">
        <i class="icon-base ti tabler-device-desktop-analytics text-primary me-2"></i>Layar Proyektor Instruktur
      </h3>
      <p class="text-muted mb-0">{{ $pelatihan->nama }} — Batch {{ $pelatihan->batch }}</p>
    </div>
    <div class="d-flex align-items-center gap-3 flex-wrap">
      <span class="badge bg-label-primary px-3 py-2 live-badge">
        <span class="live-dot"></span> MONITORING REAL-TIME
      </span>
      <span id="last-updated" class="text-muted small">
        <i class="icon-base ti tabler-clock me-1"></i>Terakhir diperbarui: —
      </span>
    </div>
  </div>

  {{-- Main Row --}}
  <div class="row g-4">

    {{-- Left Panel: Circular Stats --}}
    <div class="col-12 col-lg-5">
      <div class="card shadow-sm border-0 monitoring-card monitoring-card-accent h-100">
        <div class="card-body p-4 d-flex flex-column justify-content-center text-center">

          <h5 class="fw-bold text-body mb-4">
            <i class="icon-base ti tabler-chart-donut text-primary me-2"></i>Persentase Kehadiran Hari Ini
          </h5>

          {{-- Circular Progress --}}
          <div id="attendance-circle" class="circular-progress mb-4">
            <div class="circular-progress-content">
              <h2 class="fw-bold text-primary display-5 mb-0" id="attendance-percent">0%</h2>
              <span class="text-muted small fw-medium">Hadir</span>
            </div>
          </div>

          {{-- Stat Breakdown --}}
          <div class="row g-0 pt-3 border-top stat-divider">
            <div class="col-4 border-end stat-divider pe-2">
              <h5 class="text-body mb-1 fw-bold" id="stat-total-confirmed">0</h5>
              <small class="text-muted d-block">Terdaftar</small>
            </div>
            <div class="col-4 border-end stat-divider px-2">
              <h5 class="text-success mb-1 fw-bold" id="stat-total-hadir">0</h5>
              <small class="text-muted d-block">Hadir</small>
            </div>
            <div class="col-4 ps-2">
              <h5 class="text-warning mb-1 fw-bold" id="stat-total-belum">0</h5>
              <small class="text-muted d-block">Belum Hadir</small>
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- Right Panel: Participant Grid --}}
    <div class="col-12 col-lg-7">
      <div class="card shadow-sm border-0 monitoring-card h-100">
        <div class="card-header d-flex align-items-center justify-content-between py-3 border-bottom">
          <h5 class="card-title mb-0 fw-semibold">
            <i class="icon-base ti tabler-users text-success me-2"></i>Daftar Hadir Hari Ini
          </h5>
          <span class="badge bg-label-success" id="list-counter">0 Hadir</span>
        </div>
        <div class="card-body p-3">

          {{-- Participant List --}}
          <div id="participant-list-grid" class="row g-2 participant-scroll align-content-start">

            {{-- Empty State --}}
            <div class="col-12" id="empty-state">
              <div class="text-center py-5">
                <i class="icon-base ti tabler-users-off text-muted mb-3" style="font-size: 3rem;"></i>
                <p class="text-muted mb-0">Belum ada peserta yang melakukan presensi hari ini.</p>
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>

  </div>{{-- end .row --}}

</div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const pelatihanId = "{{ $pelatihan->id }}";
    let renderedParticipantIds = new Set();

    function updateMonitoringData() {
      fetch(`/api/pelatihan/${pelatihanId}/realtime-attendance`)
      .then(response => response.json())
      .then(data => {
        // 1. Update stats
        const total = data.total_confirmed_participants || 0;
        const hadir = data.total_hadir_today || 0;
        const belum = data.total_belum_hadir_today || 0;
        const percent = total > 0 ? Math.round((hadir / total) * 100) : 0;

        document.getElementById('attendance-percent').textContent = percent + '%';
        document.getElementById('stat-total-confirmed').textContent = total;
        document.getElementById('stat-total-hadir').textContent = hadir;
        document.getElementById('stat-total-belum').textContent = belum;
        document.getElementById('list-counter').textContent = `${hadir} Hadir`;

        // Update Circular progress conic gradient
        const circle = document.getElementById('attendance-circle');
        if (circle) {
          circle.style.background = `conic-gradient(var(--bs-primary) ${percent}%, var(--bs-border-color) ${percent}%)`;
        }

        // 2. Update list
        const grid = document.getElementById('participant-list-grid');
        const emptyState = document.getElementById('empty-state');
        const participants = data.participants_hadir || [];

        if (participants.length > 0) {
          if (emptyState) emptyState.remove();

          participants.forEach(p => {
            if (!renderedParticipantIds.has(p.id)) {
              // Create element and append with slide-in animation
              const col = document.createElement('div');
              col.className = 'col-12 col-md-6';
              col.id = `participant-item-${p.id}`;

              const initials = p.name ? p.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() : '?';
              const avatarHtml = p.avatar
                ? `<img src="${p.avatar}" alt="${p.name}" class="avatar-circle" style="object-fit: cover;">`
                : `<div class="avatar-circle">${initials}</div>`;

              const checkedInTime = new Date(p.checked_in_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

              col.innerHTML = `
                <div class="participant-grid-item">
                  ${avatarHtml}
                  <div class="flex-grow-1 min-w-0">
                    <h6 class="text-body mb-0 fw-semibold text-truncate" style="font-size: 0.9rem;">${p.name}</h6>
                    <small class="text-muted d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                      <i class="icon-base ti tabler-clock text-success" style="font-size: 0.85rem;"></i> ${checkedInTime} via ${p.verified_method}
                    </small>
                  </div>
                </div>
              `;

              // Insert at beginning of grid
              grid.insertBefore(col, grid.firstChild);
              renderedParticipantIds.add(p.id);
            }
          });
        }

        // Update timestamp
        const timeNow = new Date().toLocaleTimeString('id-ID');
        document.getElementById('last-updated').textContent = `Terakhir diperbarui: ${timeNow}`;
      })
      .catch(err => {
        console.error('Error fetching realtime data:', err);
      });
    }

    // Run immediately and every 3 seconds
    updateMonitoringData();
    setInterval(updateMonitoringData, 3000);
  });
</script>
@endsection
