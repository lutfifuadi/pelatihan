@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard Instruktur')

@section('content')
<div class="row">
  <div class="col-12 mb-4">
    <h4>Dashboard Instruktur</h4>
    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong> 👋</p>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="card-info">
            <p class="card-text mb-1">Pelatihan Saya</p>
            <h3 class="fw-bold mb-0">0</h3>
          </div>
          <div class="card-icon">
            <i class="icon-base ti tabler-books fs-2 text-primary"></i>
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
            <p class="card-text mb-1">Total Peserta</p>
            <h3 class="fw-bold mb-0">0</h3>
          </div>
          <div class="card-icon">
            <i class="icon-base ti tabler-users fs-2 text-success"></i>
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
            <p class="card-text mb-1">Tugas Perlu Dinilai</p>
            <h3 class="fw-bold mb-0">0</h3>
          </div>
          <div class="card-icon">
            <i class="icon-base ti tabler-clipboard-check fs-2 text-warning"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 mt-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Pelatihan & Akses Monitoring</h5>
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Nama Pelatihan</th>
              <th>Batch</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @php
              $pelatihans = \App\Models\Pelatihan::where('is_active', true)->get();
            @endphp
            @forelse($pelatihans as $pelatihan)
              <tr>
                <td><strong>{{ $pelatihan->nama }}</strong></td>
                <td>Batch {{ $pelatihan->batch }}</td>
                <td><span class="badge bg-label-success">Aktif</span></td>
                <td>
                  <a href="{{ route('instruktur.monitoring', $pelatihan->id) }}" class="btn btn-sm btn-primary">
                    <i class="icon-base ti tabler-device-desktop-analytics me-1"></i> Layar Proyektor
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">Tidak ada pelatihan aktif saat ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
