@php
  $pageConfigs = ['myLayout' => 'blank'];
  $configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', 'Offline - Koneksi Terputus')

@section('page-style')
  <style>
    .offline-container {
      min-height: 100dvh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #1a1d29;
      padding: 1.5rem;
    }

    .offline-card {
      max-width: 420px;
      width: 100%;
      text-align: center;
      background: #232635;
      border: 1px solid #2e3140;
      border-radius: 1.25rem;
      padding: 3rem 2rem;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }

    .offline-icon {
      width: 96px;
      height: 96px;
      margin: 0 auto 1.5rem;
      background: linear-gradient(135deg, #7367f0, #4a3fcf);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 32px rgba(115, 103, 240, 0.35);
    }

    .offline-icon svg {
      width: 48px;
      height: 48px;
      color: #ffffff;
    }

    .offline-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 0.75rem;
    }

    .offline-description {
      font-size: 0.9375rem;
      color: #8e92a4;
      line-height: 1.6;
      margin-bottom: 2rem;
    }

    .offline-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      width: 100%;
      padding: 0.75rem 1.5rem;
      background: linear-gradient(135deg, #7367f0, #5e50ee);
      color: #ffffff;
      font-size: 0.9375rem;
      font-weight: 600;
      border: none;
      border-radius: 0.75rem;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
    }

    .offline-btn:hover {
      background: linear-gradient(135deg, #5e50ee, #4a3fcf);
      transform: translateY(-1px);
      box-shadow: 0 8px 24px rgba(115, 103, 240, 0.4);
    }

    .offline-btn:active {
      transform: translateY(0);
    }

    .offline-btn svg {
      width: 20px;
      height: 20px;
    }

    .offline-footer {
      margin-top: 1.5rem;
      font-size: 0.8125rem;
      color: #5e6170;
    }

    .offline-footer .app-name {
      color: #7367f0;
      font-weight: 600;
    }
  </style>
@endsection

@section('content')
  <div class="offline-container">
    <div class="offline-card">
      <!-- Icon -->
      <div class="offline-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="1" y1="1" x2="23" y2="23"></line>
          <path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55"></path>
          <path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39"></path>
          <path d="M10.71 5.05A16 16 0 0 1 22.56 9"></path>
          <path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88"></path>
          <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
          <line x1="12" y1="20" x2="12.01" y2="20"></line>
        </svg>
      </div>

      <!-- Title -->
      <h1 class="offline-title">Koneksi Terputus</h1>

      <!-- Description -->
      <p class="offline-description">
        Kamu sedang offline. Halaman ini dimuat dari cache lokal. 
        Periksa koneksi internetmu dan coba lagi.
      </p>

      <!-- Retry Button -->
      <button class="offline-btn" onclick="window.location.reload()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="23 4 23 10 17 10"></polyline>
          <polyline points="1 20 1 14 7 14"></polyline>
          <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
        </svg>
        Coba Lagi
      </button>

      <!-- Footer -->
      <div class="offline-footer">
        <span class="app-name">{{ config('app.name') }}</span> &middot; Sistem Manajemen Pelatihan
      </div>
    </div>
  </div>
@endsection
