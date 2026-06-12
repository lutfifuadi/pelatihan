@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard Koordinator')

@section('content')
<div class="row">
  <div class="col-12 mb-4">
    <h4>Dashboard Koordinator</h4>
    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong> 👋</p>
    @if(auth()->user()->kecamatan)
      <p class="text-muted">
        <i class="icon-base ti tabler-map-pin me-1"></i> Wilayah: <strong>{{ auth()->user()->kecamatan->name }}</strong>
      </p>
    @endif
  </div>

  <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="card-info">
            <p class="card-text mb-1">Peserta Wilayah</p>
            <h3 class="fw-bold mb-0">0</h3>
          </div>
          <div class="card-icon">
            <i class="icon-base ti tabler-users fs-2 text-primary"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="card-info">
            <p class="card-text mb-1">Pelatihan Aktif</p>
            <h3 class="fw-bold mb-0">0</h3>
          </div>
          <div class="card-icon">
            <i class="icon-base ti tabler-books fs-2 text-success"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="card-info">
            <p class="card-text mb-1">Pendaftar Baru</p>
            <h3 class="fw-bold mb-0">0</h3>
          </div>
          <div class="card-icon">
            <i class="icon-base ti tabler-user-plus fs-2 text-info"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Daftar Peserta Wilayah {{ auth()->user()->kecamatan?->name ?? '-' }}</h5>
      </div>
      <div class="card-body">
        <p class="text-muted mb-0">Belum ada peserta terdaftar di wilayah ini.</p>
      </div>
    </div>
  </div>
</div>
@endsection
