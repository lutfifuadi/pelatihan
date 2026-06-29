@php
  $configData = Helper::appClasses();
  $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/publicLayout')

@section('title', 'Kebijakan Privasi - Pelatihanku Bandung')

@section('content')
<div id="beranda-page-wrapper">
@include('partials.floating-navbar')

<section class="section-py first-section-pt position-relative overflow-hidden" style="background: linear-gradient(135deg, #0b0f19 0%, #1e1b4b 100%) !important; color: #f8fafc; min-height: 100vh; font-family: 'Outfit', sans-serif; padding-top: 140px !important;">
  <!-- Glow Orbs -->
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <!-- Background illustration/glow effect -->
  <div class="position-absolute w-100 h-100 top-0 start-0 z-0 opacity-25" style="background-image: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 50%), radial-gradient(circle at 20% 80%, rgba(245, 158, 11, 0.1) 0%, transparent 50%); pointer-events: none;"></div>
  
  <div class="container py-5 z-1 position-relative">
    <div class="row justify-content-center">
      <div class="col-lg-9 col-md-11">
        
        <div class="card border-0 shadow-lg text-white" style="background: rgba(30, 41, 59, 0.65); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.08);">
          <div class="card-body p-4 p-md-5">
            <h1 class="h3 mb-2 text-warning fw-bold text-center" style="font-family: 'Sora', sans-serif;">KEBIJAKAN PRIVASI</h1>
            <p class="text-center text-white-50 small mb-4">Platform pelatihanku.my.id<br><em>Terakhir Diperbarui: 29 Juni 2026</em></p>
            <hr class="mb-4" style="border-color: rgba(255, 255, 255, 0.15);">
            
            <div class="text-white-50" style="line-height: 1.7; font-size: 0.975rem; color: rgba(255, 255, 255, 0.7) !important;">
              <p class="mb-2"><strong class="text-white">1. Latar Belakang dan Tujuan Platform</strong></p>
              <p class="mb-4">Pelatihanku.my.id merupakan platform digital yang diinisiasi dan dikelola secara khusus oleh Tim Kang Asmul, dalam kapasitas beliau sebagai Ketua DPRD Kota Bandung. Tujuan utama platform ini adalah untuk menjembatani komunikasi yang efektif, menyerap aspirasi secara langsung, serta menjadi sarana dalam merumuskan dan merealisasikan berbagai program dan layanan yang bermanfaat bagi para konstituen di lingkungan Kota Bandung.</p>

              <p class="mb-2"><strong class="text-white">2. Komitmen dan Jaminan Kerahasiaan Data</strong></p>
              <p class="mb-4">Kami berkomitmen penuh untuk menjaga dan menjamin kerahasiaan seluruh data pribadi yang Anda masukkan melalui platform ini. Seluruh informasi yang kami terima akan dikelola secara profesional dan dijaga dengan tingkat keamanan tertinggi. Data Anda tidak akan dibagikan, disalahgunakan, atau diungkapkan kepada pihak ketiga mana pun tanpa persetujuan eksplisit dari Anda.</p>

              <p class="mb-2"><strong class="text-white">3. Penanggung Jawab Pengelolaan Data</strong></p>
              <p class="mb-2">Pengelolaan dan perlindungan data pada platform Pelatihanku.my.id dilakukan secara profesional dan bertanggung jawab penuh oleh Tim Kang Asmul. Untuk segala hal yang berkaitan dengan kebijakan privasi, keamanan, atau permintaan informasi mengenai data Anda, penanggung jawab utama yang dapat dihubungi adalah:</p>
              <ul class="list-unstyled ms-3">
                <li><strong class="text-white">Institusi Penanggung Jawab:</strong> Tim Kang Asmul</li>
                <li><strong class="text-white">Narahubung Privasi Data:</strong> Kang Dikdik</li>
                <li><strong class="text-white">Kontak (Telepon/WhatsApp):</strong> 0822-1944-3500</li>
              </ul>
            </div>

            <div class="mt-5 text-center">
              <a href="{{ url('/') }}" class="btn btn-warning px-4 py-2" style="border-radius: 8px;">
                <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali ke Beranda
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@include('partials.site-footer')
</div>
@endsection