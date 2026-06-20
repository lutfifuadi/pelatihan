@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Form Field Config')

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
    background: rgba(15, 23, 42, 0.85) !important;
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
  .badge-type {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 5px;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.3px;
    text-transform: uppercase;
  }
  .badge-type-text {
    background: rgba(99, 102, 241, 0.15);
    color: #818cf8;
    border: 1px solid rgba(99, 102, 241, 0.25);
  }
  .badge-type-select {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.25);
  }
  .badge-type-select2 {
    background: rgba(6, 182, 212, 0.15);
    color: #22d3ee;
    border: 1px solid rgba(6, 182, 212, 0.25);
  }
  .badge-type-radio {
    background: rgba(251, 191, 36, 0.15);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.25);
  }
  .badge-type-checkbox {
    background: rgba(244, 114, 182, 0.15);
    color: #f472b6;
    border: 1px solid rgba(244, 114, 182, 0.25);
  }
  .badge-type-file {
    background: rgba(239, 68, 68, 0.15);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.25);
  }
  .badge-type-card_select {
    background: rgba(52, 211, 153, 0.15);
    color: #6ee7b7;
    border: 1px solid rgba(52, 211, 153, 0.25);
  }
  .badge-type-email {
    background: rgba(168, 85, 247, 0.15);
    color: #c084fc;
    border: 1px solid rgba(168, 85, 247, 0.25);
  }
  .badge-type-tel {
    background: rgba(34, 211, 238, 0.15);
    color: #67e8f9;
    border: 1px solid rgba(34, 211, 238, 0.25);
  }
  .badge-width-full {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.7);
  }
  .badge-width-half {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.7);
  }
  .badge-width-third {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.7);
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

  .btn-glow-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    transition: all 0.3s ease;
  }
  .btn-glow-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.5);
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
          <i class="icon-base ti tabler-input-search fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0">Form Field Config</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Konfigurasi field form pendaftaran peserta
          </p>
        </div>
      </div>
      <button type="button" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2"
              data-bs-toggle="modal" data-bs-target="#modalCreateField">
        <i class="icon-base ti tabler-plus"></i> Tambah Field Baru
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

  {{-- Filter Tabs per Section --}}
  <div class="glass-card-premium px-4 py-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
      @foreach($sections as $sectionKey => $sectionLabel)
        <a href="{{ route('admin.form-config.index', ['section' => $sectionKey]) }}"
           class="filter-tab {{ $activeSection === $sectionKey ? 'active' : '' }}">
          {{ $sectionLabel }}
        </a>
      @endforeach
    </div>
  </div>

  {{-- Table --}}
  <div class="glass-card-premium px-4 py-4">
    <div class="table-responsive">
      <table class="table table-borderless text-white align-middle mb-0" id="fields-table">
        <thead>
          <tr>
            <th class="text-body-premium small fw-semibold px-0" style="width: 40px;"></th>
            <th class="text-body-premium small fw-semibold" style="width: 140px;">Section</th>
            <th class="text-body-premium small fw-semibold" style="width: 160px;">Field Key</th>
            <th class="text-body-premium small fw-semibold">Label</th>
            <th class="text-body-premium small fw-semibold text-center" style="width: 100px;">Tipe</th>
            <th class="text-body-premium small fw-semibold text-center" style="width: 80px;">Required</th>
            <th class="text-body-premium small fw-semibold text-center" style="width: 80px;">Aktif</th>
            <th class="text-body-premium small fw-semibold text-center" style="width: 70px;">Urutan</th>
            <th class="text-body-premium small fw-semibold text-end px-0" style="width: 90px;">Aksi</th>
          </tr>
        </thead>
        <tbody id="sortable-fields">
          @forelse($fields as $field)
            <tr data-id="{{ $field->id }}" data-order="{{ $field->order }}">
              <td class="px-0 py-3 text-center drag-handle">
                <i class="icon-base ti tabler-grip-vertical fs-5"></i>
              </td>
              <td class="py-3">
                <span class="badge-premium badge-premium-info">
                  {{ $sections[$field->section] ?? $field->section }}
                </span>
              </td>
              <td class="py-3">
                <code class="text-white" style="font-size: 0.82rem;">{{ $field->field_key }}</code>
              </td>
              <td class="py-3 text-white fw-semibold">{{ $field->label }}</td>
              <td class="py-3 text-center">
                @php
                  $type = $field->type;
                  $typeClass = 'badge-type-text';
                  if (in_array($type, ['select', 'select2'])) $typeClass = 'badge-type-' . $type;
                  elseif ($type === 'radio') $typeClass = 'badge-type-radio';
                  elseif ($type === 'checkbox') $typeClass = 'badge-type-checkbox';
                  elseif ($type === 'file') $typeClass = 'badge-type-file';
                  elseif ($type === 'card_select') $typeClass = 'badge-type-card_select';
                  elseif ($type === 'email') $typeClass = 'badge-type-email';
                  elseif ($type === 'tel') $typeClass = 'badge-type-tel';
                @endphp
                <span class="badge-type {{ $typeClass }}">{{ $type }}</span>
              </td>
              <td class="py-3 text-center">
                <label class="toggle-switch mb-0">
                  <input type="checkbox" class="toggle-required"
                         data-id="{{ $field->id }}"
                         {{ $field->is_required ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </label>
              </td>
              <td class="py-3 text-center">
                <label class="toggle-switch mb-0">
                  <input type="checkbox" class="toggle-active"
                         data-id="{{ $field->id }}"
                         {{ $field->is_active ? 'checked' : '' }}>
                  <span class="toggle-slider"></span>
                </label>
              </td>
              <td class="py-3 text-center">
                <span class="badge-premium">{{ $field->order }}</span>
              </td>
              <td class="text-end px-0 py-3">
                <button type="button" class="btn btn-glow-warning btn-icon-sm"
                        data-bs-toggle="modal" data-bs-target="#modalFieldConfig"
                        data-mode="edit"
                        data-id="{{ $field->id }}"
                        data-section="{{ $field->section }}"
                        data-field_key="{{ $field->field_key }}"
                        data-label="{{ $field->label }}"
                        data-placeholder="{{ $field->placeholder ?? '' }}"
                        data-type="{{ $field->type }}"
                        data-is_required="{{ $field->is_required ? '1' : '0' }}"
                        data-is_active="{{ $field->is_active ? '1' : '0' }}"
                        data-width="{{ $field->width }}"
                        data-options_group="{{ $field->options_group ?? '' }}"
                        data-validation_rules="{{ $field->validation_rules ?? '' }}"
                        title="Edit">
                  <i class="icon-base ti tabler-edit"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center text-body-premium py-5">
                <i class="icon-base ti tabler-database-off fs-1 mb-2 d-block text-warning"></i>
                Belum ada field untuk section ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(count($fields) === 0)
      <div class="text-center py-4">
        <p class="text-muted mb-0">Belum ada data field.</p>
      </div>
    @endif
  </div>
</div>

{{-- Modal --}}
@include('content.admin.form-config._modal')
@endsection

{{-- Modal Tambah Field Baru --}}
<div class="modal fade" id="modalCreateField" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-content-premium">
      <div class="modal-header modal-header-premium">
        <h5 class="modal-title text-white fw-bold">
          <i class="icon-base ti tabler-plus me-2 text-warning"></i>Tambah Field Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.form-config.store') }}" method="POST">
        @csrf
        <div class="modal-body px-4 py-4">
          <div class="row g-4">
            {{-- Section --}}
            <div class="col-md-6">
              <label class="form-label form-label-custom">Section</label>
              <select name="section" class="form-control form-control-custom" required>
                @foreach($sections as $secKey => $secLabel)
                  <option value="{{ $secKey }}" {{ $secKey === $section ? 'selected' : '' }}>{{ $secLabel }}</option>
                @endforeach
              </select>
            </div>
            {{-- Type --}}
            <div class="col-md-6">
              <label class="form-label form-label-custom">Tipe Field</label>
              <select name="type" class="form-control form-control-custom" required>
                <option value="text">Text</option>
                <option value="textarea">Textarea</option>
                <option value="select">Select (Dropdown)</option>
                <option value="radio">Radio</option>
                <option value="checkbox">Checkbox</option>
                <option value="number">Number</option>
                <option value="email">Email</option>
                <option value="date">Date</option>
                <option value="file">File Upload</option>
              </select>
            </div>
            {{-- Label --}}
            <div class="col-12">
              <label class="form-label form-label-custom">Label</label>
              <input type="text" name="label" class="form-control form-control-custom" placeholder="Nama field yang tampil di form" required>
            </div>
            {{-- Field Key --}}
            <div class="col-md-6">
              <label class="form-label form-label-custom">Field Key <small class="text-body-premium">(identifier unik)</small></label>
              <input type="text" name="field_key" class="form-control form-control-custom" placeholder="contoh: nomor_darurat" required>
            </div>
            {{-- Placeholder --}}
            <div class="col-md-6">
              <label class="form-label form-label-custom">Placeholder</label>
              <input type="text" name="placeholder" class="form-control form-control-custom" placeholder="Teks petunjuk di dalam field">
            </div>
            {{-- Options Group --}}
            <div class="col-md-6">
              <label class="form-label form-label-custom">Options Group <small class="text-body-premium">(untuk select/radio/checkbox)</small></label>
              <select name="options_group" class="form-control form-control-custom">
                <option value="">-- Tidak pakai --</option>
                @if(isset($optionGroups) && count($optionGroups) > 0)
                  @foreach($optionGroups as $ogKey => $ogLabel)
                    <option value="{{ $ogKey }}">{{ $ogLabel }}</option>
                  @endforeach
                @else
                  @php
                    $groups = App\Models\MasterOption::select('group_key')->distinct()->pluck('group_key');
                  @endphp
                  @foreach($groups as $gk)
                    <option value="{{ $gk }}">{{ $gk }}</option>
                  @endforeach
                @endif
              </select>
            </div>
            {{-- Width --}}
            <div class="col-md-3">
              <label class="form-label form-label-custom">Lebar</label>
              <select name="width" class="form-control form-control-custom">
                <option value="full">Full (100%)</option>
                <option value="half">Half (50%)</option>
                <option value="third">Third (33%)</option>
              </select>
            </div>
            {{-- Validation Rules --}}
            <div class="col-md-6">
              <label class="form-label form-label-custom">Validation Rules <small class="text-body-premium">(opsional)</small></label>
              <input type="text" name="validation_rules" class="form-control form-control-custom" placeholder="contoh: min:3|max:255">
            </div>
            {{-- Show If --}}
            <div class="col-md-6">
              <label class="form-label form-label-custom">Show If <small class="text-body-premium">(conditional, opsional)</small></label>
              <input type="text" name="show_if" class="form-control form-control-custom" placeholder="contoh: status_pekerjaan=bekerja">
            </div>
            {{-- Toggles --}}
            <div class="col-md-6">
              <div class="d-flex gap-4 mt-3">
                <div class="form-check">
                  <input type="hidden" name="is_required" value="0">
                  <input type="checkbox" name="is_required" value="1" class="form-check-input form-check-input-custom" id="create_is_required" checked>
                  <label class="form-check-label text-white-50" for="create_is_required">Required</label>
                </div>
                <div class="form-check">
                  <input type="hidden" name="is_active" value="0">
                  <input type="checkbox" name="is_active" value="1" class="form-check-input form-check-input-custom" id="create_is_active" checked>
                  <label class="form-check-label text-white-50" for="create_is_active">Aktif</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer modal-footer-premium">
          <button type="button" class="btn btn-glow-primary px-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-glow-premium px-4">Simpan Field</button>
        </div>
      </form>
    </div>
  </div>
</div>

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // =========================================
    // SortableJS Drag & Drop Reordering
    // =========================================
    const sortableEl = document.getElementById('sortable-fields');
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
            const orderBadge = row.querySelector('td:nth-child(8) .badge-premium');
            if (orderBadge) {
              orderBadge.textContent = (index + 1).toString();
            }
          });

          // Send AJAX to update order
          fetch('{{ route("admin.form-config.reorder") }}', {
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
    // Toggle Required (AJAX)
    // =========================================
    document.querySelectorAll('.toggle-required').forEach(function(toggle) {
      toggle.addEventListener('change', function() {
        const id = this.dataset.id;
        const isRequired = this.checked ? 1 : 0;

        fetch('{{ route("admin.form-config.toggle-required", ["formFieldConfig" => "PLACEHOLDER"]) }}'.replace('PLACEHOLDER', id), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            id: id,
            is_required: isRequired
          })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
          if (data.success) {
            showToast('Status required berhasil diperbarui', 'success');
          } else {
            showToast('Gagal memperbarui status required', 'error');
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
    // Toggle Active (AJAX)
    // =========================================
    document.querySelectorAll('.toggle-active').forEach(function(toggle) {
      toggle.addEventListener('change', function() {
        const id = this.dataset.id;
        const isActive = this.checked ? 1 : 0;

        fetch('{{ route("admin.form-config.toggle-active", ["formFieldConfig" => "PLACEHOLDER"]) }}'.replace('PLACEHOLDER', id), {
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
            showToast('Status aktif berhasil diperbarui', 'success');
          } else {
            showToast('Gagal memperbarui status aktif', 'error');
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
    // Modal - Set data for Edit mode
    // =========================================
    const modalField = document.getElementById('modalFieldConfig');
    if (modalField) {
      modalField.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const mode = button.getAttribute('data-mode') || 'edit';
        const form = this.querySelector('#fieldConfigForm');
        const modalTitle = this.querySelector('.modal-title');

        modalTitle.textContent = 'Edit Field Config';
        form.action = '{{ route("admin.form-config.update", ["formFieldConfig" => "PLACEHOLDER"]) }}'.replace('PLACEHOLDER', button.getAttribute('data-id'));

        form.querySelector('#edit_label').value = button.getAttribute('data-label');
        form.querySelector('#edit_placeholder').value = button.getAttribute('data-placeholder');
        form.querySelector('#edit_width').value = button.getAttribute('data-width');
        form.querySelector('#edit_validation_rules').value = button.getAttribute('data-validation_rules');
        form.querySelector('#edit_is_required').checked = button.getAttribute('data-is_required') === '1';
        form.querySelector('#edit_is_active').checked = button.getAttribute('data-is_active') === '1';
        form.querySelector('#edit_field_key_display').textContent = button.getAttribute('data-field_key');
        form.querySelector('#edit_type_display').textContent = button.getAttribute('data-type');
        form.querySelector('#edit_section_display').textContent = button.getAttribute('data-section');
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
