@php
$configData = Helper::appClasses();
$isDitutup = $is_ditutup ?? false;
@endphp

@extends('layouts/publicLayout')

@section('page-style')
<style>
  .alert-closed {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 12px;
    color: #f8fafc;
  }
  .alert-closed .alert-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(239, 68, 68, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #f87171;
    flex-shrink: 0;
  }
</style>
@endsection

@section('content')
<div class="container py-5">
    @if($isDitutup)
    <div class="alert alert-closed d-flex align-items-center gap-3 px-4 py-3 mb-4" role="alert">
        <div class="alert-icon">
            <i class="icon-base ti tabler-clock-off fs-1"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1" style="color: #f87171;">Pendaftaran Ditutup</h5>
            <p class="mb-0" style="color: rgba(255,255,255,0.7);">
                Pendaftaran untuk pelatihan ini telah ditutup pada {{ $pelatihan->batas_pendaftaran?->format('d/m/Y') ?? 'tanggal yang ditentukan' }}.
                Silakan cari pelatihan lain yang masih tersedia.
            </p>
        </div>
        <a href="{{ route('pelatihan.index') }}" class="btn btn-outline-light btn-sm ms-auto flex-shrink-0">
            Lihat Pelatihan Lain
        </a>
    </div>
    @endif

    <h1>{{ $pelatihan->nama }}</h1>
    <p>{{ $pelatihan->deskripsi }}</p>
    
    <div class="mt-4">
        <strong>Batch:</strong> {{ $pelatihan->batch }}<br>
        <strong>Tanggal:</strong> {{ $pelatihan->tanggal_mulai?->format('d M Y') }} - {{ $pelatihan->tanggal_selesai?->format('d M Y') }}<br>
        <strong>Kuota:</strong> {{ $pelatihan->kuota }}<br>
        <strong>Status:</strong> {{ $pelatihan->is_active ? 'Aktif' : 'Tidak Aktif' }}
    </div>
</div>
@endsection
