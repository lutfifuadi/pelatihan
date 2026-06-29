@php
  $configData = Helper::appClasses();
  $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/publicLayout')

@section('title', 'Disclaimer - Pelatihanku Bandung')

@section('content')
@include('partials.floating-navbar')

<section class="section-py first-section-pt position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #f8fafc; min-height: 100vh; font-family: 'Outfit', sans-serif; padding-top: 120px !important;">
  <!-- Background illustration/glow effect -->
  <div class="position-absolute w-100 h-100 top-0 start-0 z-0 opacity-25" style="background-image: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 50%), radial-gradient(circle at 20% 80%, rgba(245, 158, 11, 0.1) 0%, transparent 50%); pointer-events: none;"></div>
  
  <div class="container py-5 z-1 position-relative">
    <div class="row justify-content-center">
      <div class="col-lg-9 col-md-11">
        
        <!-- Logos Officially Displayed Side-by-Side -->
        <div class="d-flex align-items-center justify-content-center gap-3 mb-4">
          <!-- Logo Pemkot Bandung -->
          <div class="bg-dark px-3 py-2 rounded d-flex align-items-center gap-2" style="background: rgba(15, 23, 42, 0.6) !important; border: 1px solid rgba(255,255,255,0.08);">
            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold shadow-sm" style="width: 32px; height: 32px; font-size: 10px;">
              BDG
            </div>
            <div class="text-start">
              <span class="text-white fw-bold d-block" style="font-size: 11px; line-height: 1.1; letter-spacing: 0.5px;">PEMKOT</span>
              <span class="text-white-50 fw-semibold" style="font-size: 9px; line-height: 1;">BANDUNG</span>
            </div>
          </div>
          <!-- Logo Disbudpar -->
          <div class="bg-dark px-3 py-2 rounded d-flex align-items-center gap-2" style="background: rgba(15, 23, 42, 0.6) !important; border: 1px solid rgba(255,255,255,0.08);">
            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold shadow-sm" style="width: 32px; height: 32px; font-size: 10px;">
              DBP
            </div>
            <div class="text-start">
              <span class="text-white fw-bold d-block" style="font-size: 11px; line-height: 1.1; letter-spacing: 0.5px;">DISBUDPAR</span>
              <span class="text-white-50 fw-semibold" style="font-size: 9px; line-height: 1;">KOTA BANDUNG</span>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-lg text-white" style="background: rgba(30, 41, 59, 0.65); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.08);">
          <div class="card-body p-4 p-md-5">
            <h1 class="h3 mb-2 text-warning fw-bold text-center" style="font-family: 'Sora', sans-serif;">DISCLAIMER (PENOLAKAN JAMINAN & PERNYATAAN RESMI)</h1>
            <p class="text-center text-white-50 small mb-4">Platform pelatihanku.my.id<br><em>Terakhir Diperbarui: 29 Juni 2026</em></p>
            <hr class="mb-4" style="border-color: rgba(255, 255, 255, 0.15);">
            
            <div class="text-white-50" style="line-height: 1.7; font-size: 0.975rem; color: rgba(255, 255, 255, 0.7) !important;">
              <p>Pernyataan Disclaimer ini mengatur penggunaan platform <strong class="text-white">pelatihanku.my.id</strong> oleh seluruh pengguna dan peserta program pelatihan. Dengan mengakses dan menggunakan platform ini, Anda dianggap telah memahami dan menyetujui poin-poin yang tercantum di bawah ini.</p>
              
              <h5 class="mt-4 fw-bold text-warning" style="font-family: 'Sora', sans-serif;">A. Status Kepemilikan dan Kemitraan Platform</h5>
              <p>Platform <strong class="text-white">pelatihanku.my.id</strong> merupakan platform kemitraan resmi yang ditunjuk dan disetujui oleh <strong class="text-white">Dinas Kebudayaan dan Pariwisata (Disbudpar) Kota Bandung</strong> untuk menyelenggarakan pendaftaran, manajemen peserta, dan fasilitasi proses belajar-mengajar dalam program pelatihan kerja sama. Platform ini bertindak sebagai perantara sistem informasi digital guna mendukung kelancaran program-program pembinaan yang dicanangkan oleh Disbudpar Kota Bandung.</p>

              <h5 class="mt-4 fw-bold text-warning" style="font-family: 'Sora', sans-serif;">B. Ketiadaan Biaya Program (100% Gratis)</h5>
              <p>Seluruh program pelatihan resmi yang diselenggarakan melalui platform pelatihanku.my.id <strong>sama sekali tidak memungut biaya apa pun dari peserta (100% Gratis)</strong>. Kami tidak pernah meminta pembayaran, transfer uang, biaya administrasi, biaya pendaftaran, biaya sertifikat, atau kompensasi finansial lainnya dalam bentuk apa pun.</p>

              <h5 class="mt-4 fw-bold text-warning" style="font-family: 'Sora', sans-serif;">C. Imbauan Kewaspadaan terhadap Penipuan</h5>
              <p>Kami mengimbau kepada seluruh calon peserta, peserta aktif, maupun alumni pelatihan untuk senantiasa waspada terhadap segala bentuk modus penipuan yang mencatut nama program pelatihan Disbudpar Kota Bandung atau platform pelatihanku.my.id.</p>
              
              <p>Harap memperhatikan ketentuan keselamatan berikut:</p>
              <ol>
                <li><strong class="text-white">Saluran Komunikasi Resmi</strong>: Komunikasi resmi dari pengelola platform pelatihanku.my.id hanya dilakukan melalui saluran komunikasi resmi yang tercantum di situs web ini.</li>
                <li><strong class="text-white">Nomor Admin Resmi Terverifikasi</strong>: Nomor kontak layanan pelanggan atau administrator resmi kami yang akan menghubungi peserta untuk keperluan koordinasi teknis, verifikasi, atau grup koordinasi adalah nomor yang telah terdaftar dan terverifikasi di dalam sistem kami, yaitu:
                  <ul>
                    <li><strong class="text-warning">+62 889-8947-0609</strong> (WhatsApp/Telepon Resmi Admin)</li>
                  </ul>
                </li>
                <li><strong class="text-white">Konfirmasi Informasi</strong>: Apabila Anda dihubungi oleh pihak yang mengaku sebagai panitia, perwakilan Disbudpar, atau admin pelatihanku.my.id dengan nomor yang berbeda dari nomor resmi di atas, atau jika pihak tersebut meminta imbalan uang/barang, harap segera mengabaikan pesan tersebut dan melaporkannya kepada kami melalui menu pengaduan resmi di platform.</li>
                <li><strong class="text-white">Tanggung Jawab</strong>: Kami tidak bertanggung jawab atas segala kerugian materiil maupun immateriil yang timbul akibat transaksi, penyerahan data sensitif, atau interaksi yang dilakukan oleh pengguna dengan pihak-pihak tidak bertanggung jawab di luar saluran komunikasi resmi pelatihanku.my.id.</li>
              </ol>
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
@endsection
