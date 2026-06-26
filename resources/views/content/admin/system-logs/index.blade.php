@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Log Sistem')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  .content-wrapper { font-family: 'Outfit', sans-serif; color: #f8fafc; position: relative !important; overflow: hidden !important; }
  .content-wrapper h1,.content-wrapper h2,.content-wrapper h3,.content-wrapper h4,.content-wrapper h5,.content-wrapper h6 { font-family: 'Sora', sans-serif; }

  html,body,.layout-page,.content-wrapper,.layout-wrapper,.layout-container {
    background-color: #0b0f19 !important;
    background-image: radial-gradient(at 0% 0%, rgba(99,102,241,0.15) 0px, transparent 55%), radial-gradient(at 100% 0%, rgba(139,92,246,0.15) 0px, transparent 55%), radial-gradient(at 50% 50%, rgba(236,72,153,0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }
  .layout-navbar-fixed .layout-page::before { display: none !important; }
  .content-wrapper > .container-xxl { max-width: 100% !important; padding: 0 !important; }

  .text-body-premium { color: rgba(255,255,255,0.65)!important; }
  .glass-card-premium { background: rgba(15,23,42,0.25)!important; backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.08)!important; box-shadow:0 20px 60px rgba(0,0,0,0.4); border-radius:5px!important; position:relative; z-index:1; }

  .log-level-badge { font-size: 0.7rem; font-weight: 700; padding: 3px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.05em; }
  .log-level-ERROR { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
  .log-level-WARNING { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
  .log-level-INFO { background: rgba(6,182,212,0.15); color: #22d3ee; border: 1px solid rgba(6,182,212,0.3); }
  .log-level-DEBUG { background: rgba(107,114,128,0.15); color: #9ca3af; border: 1px solid rgba(107,114,128,0.3); }
  .log-level-CRITICAL, .log-level-ALERT, .log-level-EMERGENCY { background: rgba(220,38,38,0.15); color: #ef4444; border: 1px solid rgba(220,38,38,0.3); }

  .log-table { font-size: 0.85rem; }
  .log-table th { color: rgba(255,255,255,0.5); font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.08); }
  .log-table td { border-bottom: 1px solid rgba(255,255,255,0.04); vertical-align: top; }
  .log-message { font-family: 'JetBrains Mono', 'Fira Code', monospace; font-size: 0.8rem; word-break: break-word; }
</style>
@endsection

@section('content')
  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box stat-icon-info" style="width: 52px; height: 52px; border-radius: 5px; display: flex; align-items: center; justify-content: center; background: rgba(6,182,212,0.12); color: #06b6d4;">
          <i class="icon-base ti tabler-file-text fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0">Log Sistem</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Pantau log aplikasi Laravel secara real-time
          </p>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center"><i class="icon-base ti tabler-check-circle fs-5 me-2"></i><span>{{ session('success') }}</span></div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center"><i class="icon-base ti tabler-alert-circle fs-5 me-2"></i><span>{{ session('error') }}</span></div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label" style="font-family:'Outfit',sans-serif; font-weight:600; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:rgba(255,255,255,0.7);">Level Log</label>
          <select id="filter-level" class="form-select">
            <option value="">Semua Level</option>
            <option value="ERROR">ERROR</option>
            <option value="WARNING">WARNING</option>
            <option value="INFO">INFO</option>
            <option value="DEBUG">DEBUG</option>
            <option value="CRITICAL">CRITICAL</option>
            <option value="ALERT">ALERT</option>
            <option value="EMERGENCY">EMERGENCY</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label" style="font-family:'Outfit',sans-serif; font-weight:600; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:rgba(255,255,255,0.7);">Cari Keyword</label>
          <input type="text" id="search-keyword" class="form-control" placeholder="Cari pesan log...">
        </div>
        <div class="col-md-5">
          <div class="d-flex flex-wrap gap-2 justify-content-md-end">
            <button type="button" id="btn-refresh" class="btn btn-primary d-inline-flex align-items-center gap-2" style="border-radius: 5px; font-weight: 600;">
              <i class="icon-base ti tabler-refresh"></i> Refresh
            </button>
            <a href="{{ route('admin.system-logs.download') }}" class="btn btn-secondary-custom d-inline-flex align-items-center gap-2" style="border-radius: 5px; font-weight: 600;">
              <i class="icon-base ti tabler-download"></i> Download
            </a>
            <form action="{{ route('admin.system-logs.clear') }}" method="POST" class="d-inline" id="form-clear-log">
              @csrf
              <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-2" style="border-radius: 5px; font-weight: 600;">
                <i class="icon-base ti tabler-trash"></i> Clear Log
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="glass-card-premium px-4 px-xl-5 py-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-white mb-0">Log Entries</h5>
        <span class="badge-premium" id="log-total">Memuat...</span>
      </div>
      <div class="table-responsive">
        <table class="table table-borderless log-table text-white">
          <thead>
            <tr>
              <th style="width: 160px;">Waktu</th>
              <th style="width: 90px;">Level</th>
              <th>Pesan</th>
            </tr>
          </thead>
          <tbody id="log-table-body">
            <tr>
              <td colspan="3" class="text-center py-4 text-body-premium">Memuat log...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const levelSelect = document.getElementById('filter-level');
  const searchInput = document.getElementById('search-keyword');
  const refreshBtn = document.getElementById('btn-refresh');
  const clearForm = document.getElementById('form-clear-log');
  const tableBody = document.getElementById('log-table-body');
  const totalBadge = document.getElementById('log-total');

  async function loadLogs() {
    tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-body-premium">Memuat log...</td></tr>';

    const params = new URLSearchParams();
    params.append('limit', '100');
    if (levelSelect.value) params.append('level', levelSelect.value);
    if (searchInput.value) params.append('search', searchInput.value);

    try {
      const res = await fetch(`{{ route('admin.system-logs.data') }}?${params.toString()}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      if (!res.ok) throw new Error('Gagal memuat log: ' + res.status);

      const data = await res.json();
      const logs = data.logs || [];

      totalBadge.textContent = `${logs.length} entri`;

      if (logs.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-body-premium">Tidak ada log.</td></tr>';
        return;
      }

      tableBody.innerHTML = logs.map(log => `
        <tr>
          <td class="text-nowrap" style="color: rgba(255,255,255,0.7);">${escapeHtml(log.datetime)}</td>
          <td><span class="log-level-badge log-level-${log.level}">${escapeHtml(log.level)}</span></td>
          <td class="log-message" style="color: #f8fafc;">${escapeHtml(log.message)}</td>
        </tr>
      `).join('');
    } catch (e) {
      console.error(e);
      tableBody.innerHTML = `<tr><td colspan="3" class="text-center py-4 text-danger">Gagal memuat log: ${escapeHtml(e.message)}</td></tr>`;
      totalBadge.textContent = '0 entri';
    }
  }

  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  levelSelect.addEventListener('change', loadLogs);
  searchInput.addEventListener('input', debounce(loadLogs, 300));
  refreshBtn.addEventListener('click', loadLogs);

  if (clearForm) {
    clearForm.addEventListener('submit', function (e) {
      e.preventDefault();
      Swal.fire({
        title: 'Hapus Log?',
        text: 'Semua isi file laravel.log akan dihapus. Tindakan ini tidak bisa dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        background: '#0f172a',
        color: '#f8fafc'
      }).then((result) => {
        if (result.isConfirmed) {
          clearForm.submit();
        }
      });
    });
  }

  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

  loadLogs();
});
</script>
@endsection
