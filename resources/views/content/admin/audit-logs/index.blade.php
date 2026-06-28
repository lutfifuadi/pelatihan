@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Audit Log Presensi')

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

  .badge-premium {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 4px 12px;
    font-weight: 500;
    font-size: 0.75rem;
    white-space: nowrap;
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
  .badge-premium-info {
    background: rgba(6, 182, 212, 0.15);
    border-color: rgba(6, 182, 212, 0.3);
    color: #22d3ee;
  }
  .badge-premium-danger {
    background: rgba(239, 68, 68, 0.15);
    border-color: rgba(239, 68, 68, 0.3);
    color: #f87171;
  }
  .badge-premium-secondary {
    background: rgba(148, 163, 184, 0.15);
    border-color: rgba(148, 163, 184, 0.3);
    color: #94a3b8;
  }
  .badge-premium-purple {
    background: rgba(168, 85, 247, 0.15);
    border-color: rgba(168, 85, 247, 0.3);
    color: #c084fc;
  }
  .badge-premium-orange {
    background: rgba(249, 115, 22, 0.15);
    border-color: rgba(249, 115, 22, 0.3);
    color: #fb923c;
  }

  /* Form controls dark style */
  .form-control-premium {
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: #f8fafc !important;
    border-radius: 5px !important;
    font-size: 0.875rem !important;
    padding: 0.5rem 0.75rem !important;
    transition: all 0.3s ease !important;
  }
  .form-control-premium:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: rgba(99, 102, 241, 0.4) !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
    color: #ffffff !important;
  }
  .form-control-premium::placeholder {
    color: rgba(255, 255, 255, 0.3) !important;
  }
  .form-control-premium option {
    background: #0b0f19 !important;
    color: #f8fafc !important;
  }

  select.form-control-premium {
    cursor: pointer;
  }
  select.form-control-premium option {
    padding: 8px;
  }

  .btn-filter {
    background: rgba(99, 102, 241, 0.15);
    border: 1px solid rgba(99, 102, 241, 0.3);
    color: #a5b4fc;
    border-radius: 5px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
  }
  .btn-filter:hover {
    background: rgba(99, 102, 241, 0.25);
    border-color: rgba(99, 102, 241, 0.5);
    color: #ffffff;
  }

  .btn-reset {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.7);
    border-radius: 5px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
  }
  .btn-reset:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
  }

  /* Pagination styling */
  .pagination .page-item .page-link {
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    transition: all 0.3s ease !important;
    border-radius: 5px !important;
    margin: 0 2px !important;
  }
  .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border-color: transparent !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
  }
  .pagination .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.02) !important;
    border-color: rgba(255, 255, 255, 0.04) !important;
    color: rgba(255, 255, 255, 0.3) !important;
  }
  .pagination .page-item .page-link:hover:not(.disabled) {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
  }

  /* Clickable row untuk modal */
  .log-row {
    cursor: pointer;
    transition: background-color 0.2s ease;
  }
  .log-row:hover {
    background: rgba(255, 255, 255, 0.03) !important;
  }

  /* Modal dark */
  .modal-content-premium {
    background: rgba(15, 23, 42, 0.35) !important;
    backdrop-filter: blur(24px) !important;
    -webkit-backdrop-filter: blur(24px) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6) !important;
    border-radius: 5px !important;
  }
  .modal-content-premium .modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
  }
  .modal-content-premium .modal-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
  }
  .modal-content-premium .btn-close {
    filter: invert(1) brightness(200%);
  }

  .json-viewer {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 5px;
    padding: 1rem;
    max-height: 300px;
    overflow-y: auto;
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
    color: #e2e8f0;
    white-space: pre-wrap;
    word-break: break-all;
  }

  /* Label filter */
  .filter-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.35rem;
  }

  /* Timestamp style */
  .timestamp-cell {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.6);
  }
  .timestamp-cell .date {
    font-weight: 500;
    color: rgba(255, 255, 255, 0.8);
  }

  /* Actor style */
  .actor-name {
    font-weight: 500;
    color: #ffffff;
  }
  .actor-email {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.4);
  }

  /* Description cell */
  .desc-cell {
    max-width: 320px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
</style>
@endsection

@section('content')
  <!-- Glow Orbs -->
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 py-4 position-relative" style="z-index: 2;">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h4 class="text-white fw-bold mb-1">Audit Log Presensi</h4>
        <p class="text-body-premium mb-0">Riwayat audit log tindakan bypass, koreksi, dan aktivitas presensi pelatihan.</p>
      </div>
    </div>

    <!-- Filter Card -->
    <div class="card glass-card-premium mb-4">
      <div class="card-body p-4">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="row g-3">
          <div class="col-md-3">
            <label class="filter-label">Cari</label>
            <input type="text" name="search" class="form-control form-control-premium" 
              placeholder="Cari pelaku, deskripsi..." value="{{ request('search') }}">
          </div>

          <div class="col-md-2">
            <label class="filter-label">Tipe Aksi</label>
            <select name="action_type" class="form-control form-control-premium">
              <option value="">Semua Aksi</option>
              @foreach($actionTypes as $type)
                <option value="{{ $type }}" {{ request('action_type') === $type ? 'selected' : '' }}>
                  {{ ucfirst($type) }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <label class="filter-label">Role Pelaku</label>
            <select name="actor_role" class="form-control form-control-premium">
              <option value="">Semua Role</option>
              @foreach($actorRoles as $role)
                <option value="{{ $role }}" {{ request('actor_role') === $role ? 'selected' : '' }}>
                  {{ ucfirst($role) }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <label class="filter-label">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control form-control-premium" value="{{ request('date_from') }}">
          </div>

          <div class="col-md-2">
            <label class="filter-label">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control form-control-premium" value="{{ request('date_to') }}">
          </div>

          <div class="col-md-1 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-filter w-100 px-0">Filter</button>
            <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-reset px-2" title="Reset Filter">
              <i class="icon-base ti tabler-refresh"></i>
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Table Card -->
    <div class="card glass-card-premium">
      <div class="card-body p-0">
        <div class="table-responsive text-nowrap">
          <table class="table table-borderless align-middle mb-0">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <th class="text-white filter-label py-3 px-4" style="width: 15%;">Waktu</th>
                <th class="text-white filter-label py-3" style="width: 20%;">Pelaku</th>
                <th class="text-white filter-label py-3" style="width: 12%;">Tipe Aksi</th>
                <th class="text-white filter-label py-3" style="width: 15%;">Target</th>
                <th class="text-white filter-label py-3" style="width: 25%;">Deskripsi</th>
                <th class="text-white filter-label py-3 text-end px-4" style="width: 13%;">IP Address</th>
              </tr>
            </thead>
            <tbody>
              @forelse($logs as $log)
                <tr class="log-row" style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);" 
                  data-bs-toggle="modal" data-bs-target="#detailModal{{ $log->id }}">
                  <td class="px-4 py-3">
                    <div class="timestamp-cell">
                      <div class="date">{{ $log->created_at->format('d M Y') }}</div>
                      <div>{{ $log->created_at->format('H:i:s') }}</div>
                    </div>
                  </td>
                  <td class="py-3">
                    <div class="actor-name">{{ $log->actor?->name ?? 'System' }}</div>
                    <div class="actor-email">Role: <span class="text-info">{{ ucfirst($log->actor_role) }}</span></div>
                  </td>
                  <td class="py-3">
                    @php
                      $badgeClass = match($log->action_type) {
                        'create' => 'badge-premium-success',
                        'update' => 'badge-premium-info',
                        'delete' => 'badge-premium-danger',
                        'bypass' => 'badge-premium-warning',
                        'correct' => 'badge-premium-orange',
                        'login' => 'badge-premium-secondary',
                        'export' => 'badge-premium-purple',
                        default => 'badge-premium',
                      };
                    @endphp
                    <span class="badge-premium {{ $badgeClass }}">
                      {{ ucfirst($log->action_type) }}
                    </span>
                  </td>
                  <td class="py-3">
                    <span class="badge-premium">{{ $log->target_entity }}</span>
                    @if($log->target_id)
                      <div class="text-body-premium mt-1" style="font-size: 0.75rem;">
                        ID: {{ $log->target_id }}
                      </div>
                    @endif
                  </td>
                  <td class="py-3">
                    <div class="desc-cell text-body-premium" title="{{ $log->description }}">
                      {{ $log->description ?? '-' }}
                    </div>
                  </td>
                  <td class="text-end px-4 py-3">
                    <span class="text-body-premium small">{{ $log->ip_address ?? '-' }}</span>
                  </td>
                </tr>

                <!-- Detail Modal -->
                <div class="modal fade" id="detailModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content modal-content-premium">
                      <div class="modal-header border-0 px-4 pt-4">
                        <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2">
                          <i class="icon-base ti tabler-info-circle"></i>
                          Detail Audit Log
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body px-4 py-3">
                        <div class="row g-3 mb-3">
                          <div class="col-6">
                            <div class="filter-label">Waktu Kejadian</div>
                            <div class="text-white">{{ $log->created_at->format('d M Y H:i:s') }}</div>
                          </div>
                          <div class="col-6">
                            <div class="filter-label">Pelaku</div>
                            <div class="text-white">{{ $log->actor?->name ?? 'System' }} (Role: {{ ucfirst($log->actor_role) }})</div>
                          </div>
                          <div class="col-4">
                            <div class="filter-label">Tipe Aksi</div>
                            <span class="badge-premium {{ $badgeClass }}">{{ ucfirst($log->action_type) }}</span>
                          </div>
                          <div class="col-4">
                            <div class="filter-label">Entitas Target</div>
                            <span class="badge-premium">{{ $log->target_entity }}</span>
                          </div>
                          <div class="col-4">
                            <div class="filter-label">ID Target</div>
                            <div class="text-white">{{ $log->target_id ?? '-' }}</div>
                          </div>
                          <div class="col-6">
                            <div class="filter-label">IP Address</div>
                            <div class="text-white">{{ $log->ip_address ?? '-' }}</div>
                          </div>
                          <div class="col-6">
                            <div class="filter-label">User Agent</div>
                            <div class="text-white small">{{ $log->user_agent ?? '-' }}</div>
                          </div>
                        </div>

                        @if($log->description)
                        <div class="mb-3">
                          <div class="filter-label mb-1">Deskripsi</div>
                          <div class="text-white" style="word-break: break-word;">{{ $log->description }}</div>
                        </div>
                        @endif

                        @if($log->old_data)
                        <div class="mb-3">
                          <div class="filter-label mb-1">Data Lama (Old Data)</div>
                          <div class="json-viewer">{{ json_encode($log->old_data, JSON_PRETTY_PRINT) }}</div>
                        </div>
                        @endif

                        @if($log->new_data)
                        <div class="mb-3">
                          <div class="filter-label mb-1">Data Baru (New Data)</div>
                          <div class="json-viewer">{{ json_encode($log->new_data, JSON_PRETTY_PRINT) }}</div>
                        </div>
                        @endif
                      </div>
                      <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn-reset" data-bs-dismiss="modal">Tutup</button>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-body-premium py-5">
                    <i class="icon-base ti tabler-shield-off fs-1 mb-2 d-block text-warning"></i>
                    Belum ada audit log presensi.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($logs->hasPages())
          <div class="mt-4 pt-3 d-flex justify-content-center" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
            {{ $logs->onEachSide(1)->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
