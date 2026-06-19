@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Form Options')

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
  .badge-premium-info {
    background: rgba(6, 182, 212, 0.15);
    border-color: rgba(6, 182, 212, 0.3);
    color: #22d3ee;
  }
  .badge-premium-secondary {
    background: rgba(148, 163, 184, 0.12);
    border-color: rgba(148, 163, 184, 0.2);
    color: #94a3b8;
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

  .btn-glow-primary {
    background: linear-gradient(135deg, #6366f1, #8b5cf6) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    transition: all 0.3s ease;
  }
  .btn-glow-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.5);
    color: #ffffff !important;
  }

  .btn-glow-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    transition: all 0.3s ease;
  }
  .btn-glow-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(239, 68, 68, 0.5);
    color: #ffffff !important;
  }

  .btn-icon-sm {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    font-size: 1.1rem;
  }

  .filter-tab {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.6);
    border-radius: 5px;
    padding: 8px 18px;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    font-size: 0.82rem;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
  }
  .filter-tab:hover {
    background: rgba(99, 102, 241, 0.12);
    border-color: rgba(99, 102, 241, 0.3);
    color: #ffffff;
  }
  .filter-tab.active {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-color: transparent;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
  }

  .table > thead > tr > th {
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .table > tbody > tr > td {
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    vertical-align: middle;
  }
  .table > tbody > tr:last-child > td {
    border-bottom: none;
  }

  /* Drag handle cursor */
  .drag-handle {
    cursor: grab;
    color: rgba(255, 255, 255, 0.3);
    transition: color 0.2s;
  }
  .drag-handle:hover {
    color: rgba(255, 255, 255, 0.7);
  }
  .drag-handle:active {
    cursor: grabbing;
  }

  /* SortableJS ghost */
  .sortable-ghost {
    opacity: 0.3;
    background: rgba(99, 102, 241, 0.2) !important;
  }
  .sortable-chosen {
    background: rgba(99, 102, 241, 0.1);
  }

  /* Toggle Switch */
  .toggle-switch {
    position: relative;
    width: 44px;
    height: 24px;
    display: inline-block;
  }
  .toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  .toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 24px;
    transition: all 0.3s ease;
  }
  .toggle-slider::before {
    content: '';
    position: absolute;
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background: #ffffff;
    border-radius: 50%;
    transition: all 0.3s ease;
  }
  .toggle-switch input:checked + .toggle-slider {
    background: linear-gradient(135deg, #10b981, #059669);
  }
  .toggle-switch input:checked + .toggle-slider::before {
    transform: translateX(20px);
  }
  .toggle-switch input:disabled + .toggle-slider {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* Modal styling */
  .modal-content-premium {
    background: rgba(15, 23, 42, 0.95) !important;
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 5px !important;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6);
  }
  .modal-header-premium {
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 1.25rem 1.5rem;
  }
  .modal-footer-premium {
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding: 1rem 1.5rem;
  }
  .modal .btn-close {
    filter: invert(1) brightness(200%);
  }

  .form-control-custom {
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #f8fafc !important;
    border-radius: 5px;
    padding: 0.6rem 0.9rem;
    font-family: 'Outfit', sans-serif;
    font-size: 0.9rem;
    transition: all 0.3s ease;
  }
  .form-control-custom:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
    color: #ffffff !important;
  }
  .form-control-custom::placeholder {
    color: rgba(255, 255, 255, 0.35);
  }
  .form-control-custom option {
    background: #1e293b;
    color: #f8fafc;
  }
  select.form-control-custom {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    padding-right: 2.5rem;
  }

  .form-label-custom {
    color: rgba(255, 255, 255, 0.75);
    font-family: 'Sora', sans-serif;
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 0.4rem;
  }

  textarea.form-control-custom {
    resize: vertical;
    min-height: 80px;
  }
</style>
@endsection

@section('content')
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

  {{-- Header --}}
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box stat-icon-primary">
          <i class="icon-base ti tabler-list fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0">Form Options</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Kelola opsi pilihan untuk dropdown, radio, dan checkbox pada form pendaftaran
          </p>
        </div>
      </div>
      <button type="button" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2"
              data-bs-toggle="modal" data-bs-target="#modalOption">
        <i class="icon-base ti tabler-plus"></i> Tambah Opsi Baru
      </button>
    </div>
  </div>

  {{-- Session Messages --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
      <div class="d-flex align-items-center">
        <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 5px;">
      <div class="d-flex align-items-center">
        <i class="icon-base ti tabler-alert-circle fs-5 me-2"></i>
        <span>{{ session('error') }}</span>
      </div>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- Filter Tabs --}}
  <div class="glass-card-premium px-4 py-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
      @foreach($groups as $groupKey => $groupLabel)
        <a href="{{ route('admin.form-options.index', ['group' => $groupKey]) }}"
           class="filter-tab {{ $activeGroup === $groupKey ? 'active' : '' }}">
          {{ $groupLabel }}
        </a>
      @endforeach
    </div>
  </div>

  {{-- Table --}}
  <div class="glass-card-premium px-4 py-4">
    <div class="table-responsive">
      <table class="table table-borderless text-white align-middle mb-0" id="options-table">
        <thead>
          <tr>
            <th class="text-body-premium small fw-semibold px-0" style="width: 40px;"></th>
            <th class="text-body-premium small fw-semibold" style="width: 160px;">Group</th>
            <th class="text-body-premium small fw-semibold">Label</th>
            <th class="text-body-premium small fw-semibold" style="width: 200px;">Value</th>
            <th class="text-body-premium small fw-semibold text-center" style="width: 80px;">Urutan</th>
            <th class="text-body-premium small fw-semibold text-center" style="width: 100px;">Status</th>
            <th class="text-body-premium small fw-semibold text-end px-0" style="width: 120px;">Aksi</th>
          </tr>
        </thead>
        <tbody id="sortable-options">
          @forelse($options as $option)
            <tr data-id="{{ $option->id }}" data-order="{{ $option->order }}">
              <td class="px-0 py-3 text-center drag-handle">
                <i class="icon-base ti tabler-grip-vertical fs-5"></i>
              </td>
              <td class="py-3">
                <span class="badge-premium badge-premium-info">
                  {{ $groups[$option->group_key] ?? $option->group_key }}
                </span>
              </td>
              <td class="py-3 text-white fw-semibold">{{ $option->label }}</td>
              <td class="py-3">
                <code class="text-body-premium" style="font-size: 0.82rem;">{{ $option->value }}</code>
              </td>
              <td class="py-3 text-center">
                <span class="badge-premium">{{ $option->order }}</span>
              </td>
              <td class="py-3 text-center">
                <label class="toggle-switch mb-0">
                  <input type="checkbox" class="toggle-active"
                         data-id="{{ $option->id }}"
                         data-type="option"
                         {{ $option->is_active ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </label>
              </td>
              <td class="text-end px-0 py-3">
                <div class="d-inline-flex gap-2">
                  <button type="button" class="btn btn-glow-primary btn-icon-sm"
                          data-bs-toggle="modal" data-bs-target="#modalOption"
                          data-mode="edit"
                          data-id="{{ $option->id }}"
                          data-group_key="{{ $option->group_key }}"
                          data-label="{{ $option->label }}"
                          data-value="{{ $option->value }}"
                          data-order="{{ $option->order }}"
                          data-is_active="{{ $option->is_active ? '1' : '0' }}"
                          title="Edit">
                    <i class="icon-base ti tabler-edit"></i>
                  </button>
                  <button type="button" class="btn btn-glow-danger btn-icon-sm btn-delete"
                          data-id="{{ $option->id }}"
                          data-label="{{ $option->label }}"
                          title="Hapus">
                    <i class="icon-base ti tabler-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-body-premium py-5">
                <i class="icon-base ti tabler-database-off fs-1 mb-2 d-block text-warning"></i>
                Belum ada opsi untuk grup ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(count($options) === 0)
      <div class="text-center py-4">
        <p class="text-muted mb-0">Belum ada data opsi.</p>
      </div>
    @endif
  </div>
</div>

{{-- Delete Form (hidden) --}}
<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

{{-- Modal --}}
@include('content.admin.form-options._modal')
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // =========================================
    // SortableJS Drag & Drop Reordering
    // =========================================
    const sortableEl = document.getElementById('sortable-options');
    if (sortableEl) {
      new Sortable(sortableEl, {
        handle: '.drag-handle',
        animation: 200,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function(evt) {
          const rows = sortableEl.querySelectorAll('tr[data-id]');
          const orderData = [];
          rows.forEach(function(row, index) {
            orderData.push({
              id: row.dataset.id,
              order: index + 1
            });
          });

          // Update displayed order numbers
          rows.forEach(function(row, index) {
            const orderBadge = row.querySelector('td:nth-child(5) .badge-premium');
            if (orderBadge) {
              orderBadge.textContent = (index + 1).toString();
            }
          });

          // Send AJAX to update order
          fetch('{{ route("admin.form-options.reorder") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify({ orders: orderData })
          })
          .then(function(res) { return res.json(); })
          .then(function(data) {
            if (data.success) {
              showToast('Urutan berhasil diperbarui', 'success');
            } else {
              showToast('Gagal memperbarui urutan', 'error');
            }
          })
          .catch(function() {
            showToast('Terjadi kesalahan jaringan', 'error');
          });
        }
      });
    }

    // =========================================
    // Toggle Active (AJAX)
    // =========================================
    document.querySelectorAll('.toggle-active').forEach(function(toggle) {
      toggle.addEventListener('change', function() {
        const id = this.dataset.id;
        const isActive = this.checked ? 1 : 0;

        fetch('{{ route("admin.form-options.toggle-active") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            id: id,
            is_active: isActive
          })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
          if (data.success) {
            showToast('Status berhasil diperbarui', 'success');
          } else {
            showToast('Gagal memperbarui status', 'error');
            // Revert
            this.checked = !this.checked;
          }
        }.bind(this))
        .catch(function() {
          showToast('Terjadi kesalahan jaringan', 'error');
          this.checked = !this.checked;
        }.bind(this));
      });
    });

    // =========================================
    // Delete Confirmation
    // =========================================
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const label = this.dataset.label;

        if (confirm('Yakin ingin menghapus opsi "' + label + '"?')) {
          const deleteForm = document.getElementById('deleteForm');
          deleteForm.action = '{{ route("admin.form-options.destroy", "") }}/' + id;
          deleteForm.submit();
        }
      });
    });

    // =========================================
    // Modal - Set data for Edit mode
    // =========================================
    const modalOption = document.getElementById('modalOption');
    if (modalOption) {
      modalOption.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const mode = button.getAttribute('data-mode') || 'create';
        const form = this.querySelector('#optionForm');
        const modalTitle = this.querySelector('.modal-title');
        const groupKeySelect = form.querySelector('#group_key');
        const labelInput = form.querySelector('#label');
        const valueInput = form.querySelector('#value');
        const orderInput = form.querySelector('#order');
        const isActiveCheck = form.querySelector('#is_active');
        const methodInput = form.querySelector('input[name="_method"]');

        if (mode === 'edit') {
          modalTitle.textContent = 'Edit Opsi';
          form.action = '{{ route("admin.form-options.update", "") }}/' + button.getAttribute('data-id');
          methodInput.value = 'PUT';
          groupKeySelect.value = button.getAttribute('data-group_key');
          labelInput.value = button.getAttribute('data-label');
          valueInput.value = button.getAttribute('data-value');
          orderInput.value = button.getAttribute('data-order');
          isActiveCheck.checked = button.getAttribute('data-is_active') === '1';

          // Disable group & value on edit (optional - adjust per needs)
          groupKeySelect.disabled = true;
          valueInput.readOnly = true;
        } else {
          modalTitle.textContent = 'Tambah Opsi Baru';
          form.action = '{{ route("admin.form-options.store") }}';
          methodInput.value = 'POST';
          form.reset();
          groupKeySelect.disabled = false;
          valueInput.readOnly = false;
          // Set default group from active tab
          groupKeySelect.value = '{{ $activeGroup }}';
          orderInput.value = '{{ count($options) + 1 }}';
          isActiveCheck.checked = true;
        }
      });

      modalOption.addEventListener('hidden.bs.modal', function() {
        const form = this.querySelector('#optionForm');
        const groupKeySelect = form.querySelector('#group_key');
        const valueInput = form.querySelector('#value');
        groupKeySelect.disabled = false;
        valueInput.readOnly = false;
      });
    }

    // =========================================
    // Toast helper
    // =========================================
    function showToast(message, type) {
      const alertDiv = document.createElement('div');
      alertDiv.className = 'alert alert-dismissible border-0 mb-4 position-fixed top-0 end-0 m-4';
      alertDiv.style.cssText = 'z-index: 9999; min-width: 300px; border-radius: 5px;';
      const bgColor = type === 'success'
        ? 'linear-gradient(135deg, #10b981, #059669)'
        : 'linear-gradient(135deg, #ef4444, #b91c1c)';
      alertDiv.style.background = bgColor;
      alertDiv.style.color = 'white';
      alertDiv.innerHTML = '<div class="d-flex align-items-center">' +
        '<i class="icon-base ti ' + (type === 'success' ? 'tabler-check-circle' : 'tabler-alert-circle') + ' fs-5 me-2"></i>' +
        '<span>' + message + '</span>' +
        '</div>' +
        '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>';

      document.body.appendChild(alertDiv);
      setTimeout(function() {
        alertDiv.remove();
      }, 4000);
    }
  });
</script>
@endsection
