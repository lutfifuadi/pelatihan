@php
$customizerHidden = 'customizer-hide';
$configData = Helper::appClasses();
@endphp

@extends('layouts/blankLayout')

@section('title', 'Pendaftaran Berhasil')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Sora:wght@400;500;600;700&display=swap');

  body {
    font-family: 'Outfit', sans-serif;
    background: #0b0f19;
    color: #f8fafc;
  }

  .sukses-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0b0f19;
    background-image:
      radial-gradient(at 50% 50%, rgba(99, 102, 241, 0.10) 0px, transparent 55%);
    padding: 40px 20px;
  }

  .sukses-card {
    width: 100%;
    max-width: 480px;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 5px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
    padding: 48px 36px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }

  .sukses-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #10b981, #059669);
  }

  .sukses-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #059669);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
  }

  .sukses-card h3 {
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 8px;
  }

  .sukses-card p {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 32px;
  }

  .btn-login-sukses {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border: none;
    color: #ffffff;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    padding: 14px 36px;
    border-radius: 5px;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  .btn-login-sukses:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
    color: #ffffff;
  }

  .btn-back {
    color: rgba(255, 255, 255, 0.5);
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s;
    display: inline-block;
    margin-top: 16px;
  }
  .btn-back:hover { color: #818cf8; }
</style>
@endsection

@section('content')
<div class="sukses-wrapper">
  <div class="sukses-card">
    <div class="sukses-icon">
      <i class="icon-base ti tabler-circle-check text-white fs-1"></i>
    </div>

    <h3>Pendaftaran Berhasil! 🎉</h3>
    <p>
      Terima kasih, data Anda telah berhasil dikirim.
      <strong class="text-warning">Akun Anda masih menunggu persetujuan admin.</strong>
      Silakan tunggu notifikasi aktivasi atau hubungi admin untuk informasi lebih lanjut.
    </p>

    <div class="info-banner" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 5px; padding: 16px; margin-bottom: 28px; text-align: left;">
      <div class="d-flex align-items-start gap-3">
        <i class="icon-base ti tabler-clock-hour-4 text-warning fs-4 mt-1"></i>
        <div>
          <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem; margin-bottom: 4px; line-height: 1.5;">
            Setelah disetujui, Anda akan bisa login menggunakan <strong>email</strong> dan <strong>password</strong> yang telah didaftarkan.
          </p>
        </div>
      </div>
    </div>

    <a href="{{ route('login') }}" class="btn-login-sukses">
      <i class="icon-base ti tabler-login fs-5"></i> Cek Status Login
    </a>

    <br>
    <a href="{{ url('/') }}" class="btn-back">
      <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali ke Beranda
    </a>
  </div>
</div>
@endsection
