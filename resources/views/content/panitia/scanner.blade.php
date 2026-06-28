@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Scanner Presensi Panitia')

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

  .btn-glow-premium {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border: none !important;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4) !important;
    transition: all 0.3s ease;
  }
  .btn-glow-premium:hover {
    box-shadow: 0 4px 25px rgba(99, 102, 241, 0.6) !important;
    transform: translateY(-2px);
  }

  #reader {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    background: rgba(15, 23, 42, 0.6);
  }

  #reader__video-flow {
    border-radius: 8px;
  }

  .scanner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    border: 2px solid transparent;
    transition: border-color 0.2s ease;
    z-index: 10;
  }

  .scanner-overlay.success-flash {
    border-color: #10b981 !important;
    background-color: rgba(16, 185, 129, 0.1);
  }

  .scanner-overlay.error-flash {
    border-color: #f87171 !important;
    background-color: rgba(248, 113, 113, 0.1);
  }

  .mode-offline-badge {
    animation: blink-yellow 1.5s infinite;
    background-color: #f59e0b !important;
    color: #0b0f19 !important;
    font-weight: 700;
  }

  @keyframes blink-yellow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
  }

  .bypass-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: bold;
  }

  /* Custom drawer stylesheet */
  .custom-drawer {
    position: fixed;
    bottom: -100%;
    left: 0;
    width: 100%;
    max-height: 80%;
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(20px);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.5);
    transition: bottom 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1050;
    border-radius: 20px 20px 0 0;
  }

  .custom-drawer.open {
    bottom: 0;
  }

  .custom-drawer-header {
    padding: 15px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }

  .custom-drawer-body {
    padding: 20px;
    overflow-y: auto;
    max-height: calc(80vh - 70px);
  }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row g-4 justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      
      <!-- Card Scanner -->
      <div class="glass-card-premium p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <div>
            <h4 class="fw-bold text-white mb-1">
              <i class="icon-base ti tabler-scan me-2 text-primary"></i>Scanner Presensi
            </h4>
            <p class="text-body-premium mb-0 small">{{ $pelatihan->nama }} - Batch {{ $pelatihan->batch }}</p>
          </div>
          <div>
            <span id="status-koneksi" class="badge bg-success px-3 py-2 text-white">ONLINE</span>
          </div>
        </div>

        <!-- Offline Queue Banner -->
        <div id="offline-banner" class="d-none alert alert-warning mb-3 d-flex align-items-center justify-content-between" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2);">
          <div>
            <i class="icon-base ti tabler-wifi-off me-2 text-warning"></i>
            <span id="offline-counter-text" class="text-white">0 Presensi tersimpan offline</span>
          </div>
          <span class="badge bg-warning text-dark animate-pulse">MODE OFFLINE</span>
        </div>

        <!-- GPS Status Banner -->
        <div id="gps-banner" class="alert alert-info mb-3 d-flex align-items-center" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.2); display:none;">
          <i class="icon-base ti tabler-map-pin me-2 text-info"></i>
          <span id="gps-status-text" class="text-white small">Mendapatkan lokasi presisi tinggi...</span>
        </div>

        <!-- Scanner Container -->
        <div class="position-relative mx-auto mb-4" style="max-width: 500px;">
          <div id="reader"></div>
          <div id="scanner-overlay" class="scanner-overlay"></div>
        </div>

        <!-- Result Feedback Banner -->
        <div id="scan-feedback" class="text-center py-2 px-3 rounded mb-4 d-none">
          <span id="scan-feedback-text" class="fw-bold text-white"></span>
        </div>

        <!-- Bypass & Search section -->
        <div class="border-top border-white border-opacity-5 pt-4">
          <h5 class="text-white mb-3 fw-semibold">Pencarian & Bypass Manual</h5>
          <div class="input-group mb-3">
            <span class="input-group-text bg-transparent text-white border-white border-opacity-10"><i class="icon-base ti tabler-search"></i></span>
            <input type="text" id="search-peserta" class="form-control bg-transparent text-white border-white border-opacity-10" placeholder="Masukkan nama atau NIK peserta...">
          </div>

          <!-- Search results list -->
          <div id="search-results" class="list-group list-group-flush bg-transparent" style="max-height: 250px; overflow-y: auto;">
            <!-- Will be populated dynamically -->
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Drawer Bypass Manual -->
<div id="bypass-drawer" class="custom-drawer">
  <div class="custom-drawer-header d-flex justify-content-between align-items-center">
    <h5 class="text-white mb-0 fw-bold"><i class="icon-base ti tabler-user-check me-2 text-success"></i>Bypass Presensi Manual</h5>
    <button type="button" id="close-drawer" class="btn-close btn-close-white" aria-label="Close"></button>
  </div>
  <div class="custom-drawer-body">
    <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
      <div class="bypass-avatar" id="bypass-avatar-text">-</div>
      <div>
        <h6 class="text-white mb-0 fw-bold" id="bypass-nama-peserta">-</h6>
        <small class="text-body-premium" id="bypass-nik-peserta">NIK: -</small>
      </div>
    </div>

    <form id="bypass-form">
      <input type="hidden" id="bypass-enrollment-id">
      <div class="mb-4">
        <label for="bypass-reason" class="form-label text-white fw-semibold mb-2">Alasan Bypass Kehadiran</label>
        <textarea id="bypass-reason" class="form-control bg-transparent text-white border-white border-opacity-10" rows="3" placeholder="Contoh: QR Code tidak terbaca, HP peserta mati, GPS eror" required></textarea>
      </div>
      <button type="submit" class="btn btn-glow-premium w-100 py-2.5 fw-bold">
        <i class="icon-base ti tabler-check me-1"></i> Konfirmasi Kehadiran (Bypass)
      </button>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let gpsCoords = { latitude: null, longitude: null };
    let isOnline = navigator.onLine;
    let localQueue = JSON.parse(localStorage.getItem('attendance_offline_queue') || '[]');

    // Audio Web API helpers
    let audioCtx = null;
    function playBeep(type) {
      try {
        if (!audioCtx) {
          audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.connect(gain);
        gain.connect(audioCtx.destination);

        if (type === 'success') {
          osc.frequency.setValueAtTime(880, audioCtx.currentTime); // A5
          gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
          osc.start();
          osc.stop(audioCtx.currentTime + 0.15);
        } else {
          // Error sound (buzz)
          osc.frequency.setValueAtTime(150, audioCtx.currentTime); // Low freq buzz
          gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
          osc.start();
          osc.stop(audioCtx.currentTime + 0.4);
        }
      } catch (e) {
        console.error('Audio beep error:', e);
      }
    }

    // Capture GPS coordinates with high accuracy
    function getGPSLocation() {
      const gpsBanner = document.getElementById('gps-banner');
      const gpsStatusText = document.getElementById('gps-status-text');
      
      if (gpsBanner) gpsBanner.style.display = 'flex';

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            gpsCoords.latitude = position.coords.latitude;
            gpsCoords.longitude = position.coords.longitude;
            if (gpsStatusText) {
              gpsStatusText.textContent = `Lokasi diperoleh: ${gpsCoords.latitude.toFixed(6)}, ${gpsCoords.longitude.toFixed(6)}`;
            }
            setTimeout(() => {
              if (gpsBanner) gpsBanner.style.display = 'none';
            }, 3000);
          },
          (error) => {
            console.error('GPS error:', error);
            if (gpsStatusText) {
              gpsStatusText.textContent = `Gagal mendapatkan GPS: ${error.message}. Silakan aktifkan izin lokasi.`;
            }
          },
          { enableHighAccuracy: true, timeout: 10000 }
        );
      } else {
        if (gpsStatusText) gpsStatusText.textContent = 'Geolocation tidak didukung oleh browser Anda.';
      }
    }

    getGPSLocation();

    // Check connection status
    function updateConnectionStatus() {
      isOnline = navigator.onLine;
      const statusBadge = document.getElementById('status-koneksi');
      const offlineBanner = document.getElementById('offline-banner');

      if (isOnline) {
        if (statusBadge) {
          statusBadge.textContent = 'ONLINE';
          statusBadge.className = 'badge bg-success px-3 py-2 text-white';
        }
        if (offlineBanner) offlineBanner.classList.add('d-none');
        // If online, process localQueue
        processOfflineQueue();
      } else {
        if (statusBadge) {
          statusBadge.textContent = 'MODE OFFLINE';
          statusBadge.className = 'badge mode-offline-badge px-3 py-2';
        }
        updateOfflineUI();
      }
    }

    window.addEventListener('online', updateConnectionStatus);
    window.addEventListener('offline', updateConnectionStatus);
    updateConnectionStatus();

    // Update UI counters for offline mode
    function updateOfflineUI() {
      const offlineBanner = document.getElementById('offline-banner');
      const counterText = document.getElementById('offline-counter-text');
      if (localQueue.length > 0) {
        if (offlineBanner) offlineBanner.classList.remove('d-none');
        if (counterText) counterText.textContent = `${localQueue.length} Presensi tersimpan offline`;
      } else {
        if (offlineBanner && !isOnline) {
          if (counterText) counterText.textContent = `0 Presensi tersimpan offline`;
        } else if (offlineBanner) {
          offlineBanner.classList.add('d-none');
        }
      }
    }

    // Save offline checkin to localStorage
    function saveOfflineCheckIn(qrToken) {
      const offlineItem = {
        qr_token: qrToken,
        scan_timestamp: Math.floor(Date.now() / 1000),
        latitude_panitia: gpsCoords.latitude || 0,
        longitude_panitia: gpsCoords.longitude || 0
      };
      localQueue.push(offlineItem);
      localStorage.setItem('attendance_offline_queue', JSON.stringify(localQueue));
      updateOfflineUI();

      // Show temporary feedback
      showFeedback(true, 'Presensi disimpan offline. Total: ' + localQueue.length);
      navigator.vibrate([100, 50, 100]);
    }

    // Process/Sync Offline Queue FIFO
    function processOfflineQueue() {
      if (localQueue.length === 0 || !navigator.onLine) return;

      const item = localQueue[0];
      fetch('/api/panitia/check-in', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Authorization': `Bearer ${document.querySelector('meta[name="api-token"]')?.getAttribute('content') ?? ''}`
        },
        body: JSON.stringify(item)
      })
      .then(response => response.json())
      .then(res => {
        // Success or Validation error both mean we remove it from queue
        // (if expired, we can't do anything about it anyway, but we log/alert)
        localQueue.shift();
        localStorage.setItem('attendance_offline_queue', JSON.stringify(localQueue));
        updateOfflineUI();

        // Recursively process next
        processOfflineQueue();
      })
      .catch(err => {
        console.error('Error syncing offline attendance:', err);
      });
    }

    // Feedback Visual & Audio
    function showFeedback(isSuccess, message) {
      const feedbackBanner = document.getElementById('scan-feedback');
      const feedbackText = document.getElementById('scan-feedback-text');
      const overlay = document.getElementById('scanner-overlay');

      if (!feedbackBanner || !feedbackText) return;

      feedbackBanner.classList.remove('d-none', 'bg-success', 'bg-danger');
      feedbackBanner.classList.add(isSuccess ? 'bg-success' : 'bg-danger');
      feedbackText.textContent = message;

      if (overlay) {
        overlay.classList.add(isSuccess ? 'success-flash' : 'error-flash');
        setTimeout(() => {
          overlay.classList.remove('success-flash', 'error-flash');
        }, 1500);
      }

      if (isSuccess) {
        playBeep('success');
        if (navigator.vibrate) navigator.vibrate(150);
      } else {
        playBeep('error');
        if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
      }

      setTimeout(() => {
        feedbackBanner.classList.add('d-none');
      }, 5000);
    }

    // QR Scanner Init
    const html5QrcodeScanner = new Html5Qrcode("reader");
    let isProcessingScan = false;

    function onScanSuccess(decodedText, decodedResult) {
      if (isProcessingScan) return;
      isProcessingScan = true;

      // Start processing scan
      if (!isOnline) {
        saveOfflineCheckIn(decodedText);
        setTimeout(() => { isProcessingScan = false; }, 2000);
        return;
      }

      // Send to server
      const payload = {
        qr_token: decodedText,
        scan_timestamp: Math.floor(Date.now() / 1000),
        latitude_panitia: gpsCoords.latitude || 0,
        longitude_panitia: gpsCoords.longitude || 0
      };

      fetch('/api/panitia/check-in', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
      })
      .then(response => {
        if (!response.ok) {
          return response.json().then(err => { throw new Error(err.message || 'Presensi gagal'); });
        }
        return response.json();
      })
      .then(res => {
        const participantName = res.attendance?.enrollment?.user?.name || 'Peserta';
        showFeedback(true, `Sukses Absen: ${participantName}`);
      })
      .catch(err => {
        showFeedback(false, err.message || 'Gagal merekam presensi');
      })
      .finally(() => {
        setTimeout(() => { isProcessingScan = false; }, 2000);
      });
    }

    // Start QR Scanner (Back camera preferred)
    html5QrcodeScanner.start(
      { facingMode: "environment" },
      {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
      },
      onScanSuccess
    ).catch(err => {
      console.error("Camera access error:", err);
    });

    // SEARCH & BYPASS MANUAL LOGIC
    const searchInput = document.getElementById('search-peserta');
    const searchResults = document.getElementById('search-results');
    const bypassDrawer = document.getElementById('bypass-drawer');
    const closeDrawerBtn = document.getElementById('close-drawer');
    const bypassForm = document.getElementById('bypass-form');

    if (searchInput && searchResults) {
      searchInput.addEventListener('input', function() {
        const query = searchInput.value.trim();
        if (query.length < 3) {
          searchResults.innerHTML = '';
          return;
        }

        const pelatihanId = "{{ $pelatihan->id }}";
        fetch(`/api/panitia/pelatihan/${pelatihanId}/search-peserta?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
          searchResults.innerHTML = '';
          if (data.length === 0) {
            searchResults.innerHTML = '<div class="list-group-item bg-transparent text-muted text-center border-0">Tidak ada peserta ditemukan</div>';
            return;
          }

          data.forEach(item => {
            const el = document.createElement('a');
            el.href = '#';
            el.className = 'list-group-item list-group-item-action bg-transparent text-white border-white border-opacity-5 d-flex justify-content-between align-items-center py-3';
            el.innerHTML = `
              <div>
                <h6 class="text-white mb-0 fw-bold">${item.name}</h6>
                <small class="text-body-premium">NIK: ${item.nik}</small>
              </div>
              <button class="btn btn-sm btn-outline-success btn-bypass-trigger" data-id="${item.id}" data-name="${item.name}" data-nik="${item.nik}">
                Bypass
              </button>
            `;
            searchResults.appendChild(el);
          });

          // Attach click listeners to bypass triggers
          document.querySelectorAll('.btn-bypass-trigger').forEach(btn => {
            btn.addEventListener('click', function(e) {
              e.preventDefault();
              openBypassDrawer(
                this.getAttribute('data-id'),
                this.getAttribute('data-name'),
                this.getAttribute('data-nik')
              );
            });
          });
        });
      });
    }

    function openBypassDrawer(id, name, nik) {
      document.getElementById('bypass-enrollment-id').value = id;
      document.getElementById('bypass-nama-peserta').textContent = name;
      document.getElementById('bypass-nik-peserta').textContent = 'NIK: ' + nik;
      document.getElementById('bypass-avatar-text').textContent = name.charAt(0).toUpperCase();
      document.getElementById('bypass-reason').value = '';
      bypassDrawer.classList.add('open');
    }

    if (closeDrawerBtn) {
      closeDrawerBtn.addEventListener('click', function() {
        bypassDrawer.classList.remove('open');
      });
    }

    if (bypassForm) {
      bypassForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Refresh GPS location before submitting bypass
        getGPSLocation();

        const enrollmentId = document.getElementById('bypass-enrollment-id').value;
        const reason = document.getElementById('bypass-reason').value;

        const payload = {
          enrollment_id: enrollmentId,
          bypass_reason: reason,
          latitude_panitia: gpsCoords.latitude || 0,
          longitude_panitia: gpsCoords.longitude || 0
        };

        fetch('/api/panitia/bypass-attendance', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(payload)
        })
        .then(response => {
          if (!response.ok) {
            return response.json().then(err => { throw new Error(err.message || 'Bypass gagal'); });
          }
          return response.json();
        })
        .then(res => {
          showFeedback(true, 'Bypass Berhasil');
          bypassDrawer.classList.remove('open');
          searchResults.innerHTML = '';
          searchInput.value = '';
        })
        .catch(err => {
          alert(err.message || 'Gagal melakukan bypass kehadiran');
        });
      });
    }
  });
</script>
@endsection
