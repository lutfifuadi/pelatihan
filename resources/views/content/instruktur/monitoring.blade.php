@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Layar Monitoring Presensi')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');

  .content-wrapper {
    font-family: 'Outfit', sans-serif;
    color: #f8fafc;
    background-color: #0b0f19 !important;
    background-image: 
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }
  .content-wrapper h1,
  .content-wrapper h2,
  .content-wrapper h3,
  .content-wrapper h4,
  .content-wrapper h5,
  .content-wrapper h6 {
    font-family: 'Sora', sans-serif;
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
  }

  /* Circular Progress Bar */
  .circular-progress {
    position: relative;
    width: 250px;
    height: 250px;
    border-radius: 50%;
    background: conic-gradient(#6366f1 0%, rgba(255, 255, 255, 0.05) 0%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    transition: background 1s ease-in-out;
  }
  .circular-progress::before {
    content: "";
    position: absolute;
    width: 210px;
    height: 210px;
    border-radius: 50%;
    background-color: #0b0f19;
  }
  .circular-progress-content {
    position: relative;
    z-index: 10;
    text-align: center;
  }

  /* Grid of participants */
  .participant-grid-item {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    animation: slide-in 0.5s ease-out forwards;
  }
  .participant-grid-item:hover {
    border-color: rgba(99, 102, 241, 0.3);
    background: rgba(255, 255, 255, 0.05);
    transform: translateY(-2px);
  }

  .avatar-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #d946ef);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #fff;
    border: 2px solid rgba(255, 255, 255, 0.1);
  }

  @keyframes slide-in {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  
  <!-- Header Title -->
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
      <h3 class="fw-bold text-white mb-1"><i class="icon-base ti tabler-device-desktop-analytics text-primary me-2"></i>Layar Proyektor Instruktur</h3>
      <p class="text-body-premium mb-0">{{ $pelatihan->nama }} - Batch {{ $pelatihan->batch }}</p>
    </div>
    <div class="d-flex align-items-center gap-3">
      <span class="badge bg-primary px-3 py-2 text-white">MONITORING REAL-TIME</span>
      <span id="last-updated" class="text-body-premium small">Terakhir diperbarui: -</span>
    </div>
  </div>

  <div class="row g-4">
    <!-- Left Panel: Large Circular Stats -->
    <div class="col-12 col-lg-5">
      <div class="glass-card-premium p-5 h-100 text-center d-flex flex-column justify-content-center">
        <h4 class="text-white fw-bold mb-5">Persentase Kehadiran Hari Ini</h4>

        <!-- Circular progress bar -->
        <div id="attendance-circle" class="circular-progress mb-5">
          <div class="circular-progress-content">
            <h1 class="text-white fw-bold display-4 mb-0" id="attendance-percent">0%</h1>
            <span class="text-body-premium small">Hadir</span>
          </div>
        </div>

        <!-- Detail statistics -->
        <div class="row g-3 border-top border-white border-opacity-5 pt-4">
          <div class="col-4 border-end border-white border-opacity-5">
            <h5 class="text-white mb-1 fw-bold" id="stat-total-confirmed">0</h5>
            <small class="text-body-premium d-block">Terdaftar</small>
          </div>
          <div class="col-4 border-end border-white border-opacity-5">
            <h5 class="text-success mb-1 fw-bold" id="stat-total-hadir">0</h5>
            <small class="text-body-premium d-block">Hadir</small>
          </div>
          <div class="col-4">
            <h5 class="text-warning mb-1 fw-bold" id="stat-total-belum">0</h5>
            <small class="text-body-premium d-block">Belum Hadir</small>
          </div>
        </div>

      </div>
    </div>

    <!-- Right Panel: Grid of arrived participants -->
    <div class="col-12 col-lg-7">
      <div class="glass-card-premium p-4 h-100 d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-white border-opacity-5 pb-3">
          <h5 class="text-white mb-0 fw-semibold">
            <i class="icon-base ti tabler-users text-success me-2"></i>Daftar Hadir Hari Ini
          </h5>
          <span class="badge bg-success bg-opacity-15 text-success" id="list-counter">0 Hadir</span>
        </div>

        <!-- Participant Arrived List -->
        <div id="participant-list-grid" class="row g-3 flex-grow-1 align-content-start" style="max-height: 550px; overflow-y: auto;">
          <!-- Will be filled dynamically -->
          <div class="col-12 text-center py-5 text-muted" id="empty-state">
            <i class="icon-base ti tabler-users-off fs-1 d-block mb-2 text-muted"></i>
            Belum ada peserta yang melakukan presensi hari ini.
          </div>
        </div>

      </div>
    </div>
  </div>

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
          circle.style.background = `conic-gradient(#6366f1 ${percent}%, rgba(255, 255, 255, 0.05) ${percent}%)`;
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
                    <h6 class="text-white mb-0 fw-semibold text-truncate" style="font-size: 0.9rem;">${p.name}</h6>
                    <small class="text-body-premium d-flex align-items-center gap-1" style="font-size: 0.75rem;">
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
