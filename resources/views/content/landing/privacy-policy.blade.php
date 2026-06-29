@php
  $configData = Helper::appClasses();
  $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts.layoutFront')

@section('title', 'Kebijakan Privasi - Pelatihanku Bandung')

@section('content')
<section class="section-py first-section-pt position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #f8fafc; min-height: 100vh; font-family: 'Outfit', sans-serif;">
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
            <h1 class="h3 mb-2 text-warning fw-bold text-center" style="font-family: 'Sora', sans-serif;">KEBIJAKAN PRIVASI (PRIVACY POLICY)</h1>
            <p class="text-center text-white-50 small mb-4">Platform pelatihanku.my.id<br><em>Terakhir Diperbarui: 29 Juni 2026</em></p>
            <hr class="mb-4" style="border-color: rgba(255, 255, 255, 0.15);">
            
            <div class="text-white-50" style="line-height: 1.7; font-size: 0.975rem; color: rgba(255, 255, 255, 0.7) !important;">
              <p>Selamat datang di platform <strong class="text-white">pelatihanku.my.id</strong>. Kami sangat menghargai privasi Anda dan berkomitmen untuk melindungi Data Pribadi Anda sesuai dengan peraturan perundang-undangan yang berlaku di Republik Indonesia, khususnya <strong class="text-white">Undang-Undang Nomor 27 Tahun 2022 tentang Perlindungan Data Pribadi (UU PDP)</strong>.</p>
              
              <p>Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, melindungi, dan memberikan hak kepada Anda sebagai subjek data dalam hubungannya dengan penggunaan platform pelatihanku.my.id.</p>
              
              <h5 class="mt-4 fw-bold text-warning" style="font-family: 'Sora', sans-serif;">A. Kemitraan Resmi dengan Dinas Kebudayaan dan Pariwisata Kota Bandung</h5>
              <p>Platform <strong class="text-white">pelatihanku.my.id</strong> dikelola dan dioperasikan dalam rangka kemitraan resmi dengan <strong class="text-white">Dinas Kebudayaan dan Pariwisata (Disbudpar) Kota Bandung</strong> untuk memfasilitasi program pelatihan, peningkatan kapasitas, serta pengembangan kompetensi masyarakat di sektor kebudayaan dan pariwisata. Seluruh proses pengolahan data dalam platform ini diselaraskan dengan kebutuhan administratif kepesertaan pelatihan resmi yang diselenggarakan oleh Disbudpar Kota Bandung.</p>

              <h5 class="mt-4 fw-bold text-warning" style="font-family: 'Sora', sans-serif;">B. Jenis Data yang Dikumpulkan</h5>
              <p>Kami mengumpulkan data yang Anda berikan secara sukarela pada saat melakukan pendaftaran akun, pengisian formulir kepesertaan, atau selama mengikuti program pelatihan. Jenis data tersebut meliputi:</p>
              <ol>
                <li><strong class="text-white">Nama Lengkap</strong>: Digunakan untuk verifikasi identitas, pencantuman pada sertifikat resmi, dan pencatatan administratif.</li>
                <li><strong class="text-white">Informasi Kontak (Nomor Telepon/WhatsApp dan Alamat Email)</strong>: Digunakan sebagai saluran komunikasi resmi untuk mengirimkan detail pelaksanaan pelatihan, notifikasi sistem, koordinasi kelas, dan informasi penting lainnya.</li>
                <li><strong class="text-white">Nomor Induk Kependudukan (NIK)</strong>: Digunakan sebagai data identifikasi unik yang sah secara hukum.</li>
              </ol>

              <h5 class="mt-4 fw-bold text-warning" style="font-family: 'Sora', sans-serif;">C. Landasan Rasional Pengumpulan NIK (Nomor Induk Kependudukan)</h5>
              <p>Kami menyadari bahwa NIK merupakan data pribadi yang bersifat sensitif dan penting. Oleh karena itu, pengumpulan NIK melalui platform pelatihanku.my.id didasarkan atas alasan-alasan rasional dan kebutuhan hukum sebagai berikut:</p>
              <ol>
                <li><strong class="text-white">Syarat Administrasi Keikutsertaan</strong>: Sebagai program resmi yang diselenggarakan oleh instansi pemerintah (Disbudpar Kota Bandung), NIK diperlukan untuk memenuhi keabsahan administrasi pertanggungjawaban program pelatihan yang dibiayai atau didukung oleh anggaran daerah.</li>
                <li><strong class="text-white">Verifikasi Domisili Warga Kota Bandung</strong>: Program pelatihan ini diprioritaskan bagi warga yang berdomisili atau memiliki identitas sah di wilayah hukum Kota Bandung. NIK digunakan untuk memvalidasi kelayakan kepesertaan berdasarkan kriteria wilayah tersebut.</li>
                <li><strong class="text-white">Sinkronisasi Data dengan Sistem Newbimma</strong>: Data NIK diperlukan untuk melakukan integrasi dan sinkronisasi dengan <strong class="text-white">Newbimma</strong> (sistem database terpadu milik Disbudpar Kota Bandung). Sinkronisasi ini bertujuan untuk menghindari duplikasi kepesertaan, mencatat riwayat pelatihan warga secara berkelanjutan, dan memastikan transparansi serta akuntabilitas program pembinaan masyarakat.</li>
              </ol>

              <h5 class="mt-4 fw-bold text-warning" style="font-family: 'Sora', sans-serif;">D. Jaminan Keamanan dan Batasan Penggunaan Data</h5>
              <p>Kami menerapkan standar keamanan teknis dan organisasi yang ketat guna melindungi Data Pribadi Anda dari akses tanpa izin, kehilangan, penyalahgunaan, perubahan, atau pengungkapan yang tidak sah. Kami memberikan jaminan sebagai berikut:</p>
              <ul>
                <li><strong class="text-white">Tujuan Khusus</strong>: Data Pribadi Anda dikumpulkan dan diolah <strong class="text-white">murni untuk keperluan administratif, koordinasi, pelaksanaan, dan pelaporan program pelatihan</strong> yang diselenggarakan bersama Disbudpar Kota Bandung.</li>
                <li><strong class="text-white">Tidak untuk Disebarluaskan/Dijual</strong>: Kami menjamin bahwa Data Pribadi Anda tidak akan disebarluaskan, diperjualbelikan, atau diserahkan kepada pihak ketiga mana pun di luar kemitraan resmi Disbudpar Kota Bandung tanpa persetujuan eksplisit dari Anda, kecuali jika diwajibkan oleh hukum atau putusan pengadilan yang sah.</li>
              </ul>

              <h5 class="mt-4 fw-bold text-warning" style="font-family: 'Sora', sans-serif;">E. Hak Peserta (Subjek Data Pribadi)</h5>
              <p>Sesuai dengan amanat UU PDP, Anda sebagai pemilik data memiliki hak penuh atas data pribadi Anda yang tersimpan di sistem kami. Hak-hak tersebut meliputi:</p>
              <ol>
                <li><strong class="text-white">Hak Mengakses dan Mendapatkan Salinan</strong>: Anda berhak meminta informasi mengenai data pribadi Anda yang kami kelola.</li>
                <li><strong class="text-white">Hak Memperbarui/Memperbaiki</strong>: Anda berhak memperbarui data jika terdapat kesalahan atau perubahan informasi.</li>
                <li><strong class="text-white">Hak Menarik Persetujuan dan Penghapusan Data (Right to Erasure)</strong>: Setelah program pelatihan selesai dilaksanakan dan seluruh proses pelaporan administratif wajib kepada Disbudpar Kota Bandung terpenuhi, Anda berhak mengajukan permohonan untuk menarik persetujuan pengolahan data serta meminta penghapusan permanen atas data pribadi Anda (termasuk NIK) dari database aktif pelatihanku.my.id.</li>
              </ol>
              <p>Untuk mengajukan penarikan persetujuan atau penghapusan data, Anda dapat menghubungi saluran layanan resmi kami yang tertera di platform ini.</p>
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
@endsection
