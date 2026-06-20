@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@php
    $institutionName = \App\Models\Setting::where('key', 'institution_name')->value('value') ?? 'Lembaga Pelatihan';
    $institutionAddress = \App\Models\Setting::where('key', 'institution_address')->value('value') ?? 'Gedung Pusat Pembelajaran Kreatif';
    $institutionPhone = \App\Models\Setting::where('key', 'institution_phone')->value('value') ?? '+62 812-3456-7890';
    $institutionEmail = \App\Models\Setting::where('key', 'institution_email')->value('value') ?? 'admin@sabakreatif.com';
    $institutionDesc = \App\Models\Setting::where('key', 'institution_description')->value('value') ?? 'Program pelatihan pengembangan kompetensi dan keterampilan praktis yang mandiri, kreatif, dan berdaya saing.';
    $footerCopyright = \App\Models\Setting::where('key', 'footer_copyright')->value('value') ?? 'Pelatihan — Pengembangan Kompetensi';
    $faqs = \App\Models\Faq::active()->ordered()->get();

    // Landing page settings
    $landingSettings = \App\Models\Setting::where('group', 'landing')->get()->keyBy('key');
    if (!function_exists('landingVal')) {
        function landingVal($settings, $key, $default) {
            return $settings[$key]->value ?? $default;
        }
    }
    $hero_title = landingVal($landingSettings, 'hero_title', 'Pendaftaran');
    $hero_subtitle = landingVal($landingSettings, 'hero_subtitle', 'Pelatihan Ekonomi Kreatif');
    $hero_description = landingVal($landingSettings, 'hero_description', 'Dapatkan keterampilan praktis dan kembangkan potensi diri melalui program pelatihan berbasis ekonomi kreatif yang dirancang khusus untuk Anda.');
    $hero_tag_1_icon = landingVal($landingSettings, 'hero_tag_1_icon', 'chef-hat');
    $hero_tag_1_text = landingVal($landingSettings, 'hero_tag_1_text', 'Kuliner Kreatif');
    $hero_tag_2_icon = landingVal($landingSettings, 'hero_tag_2_icon', 'camera');
    $hero_tag_2_text = landingVal($landingSettings, 'hero_tag_2_text', 'Konten Kreator');
    $hero_tag_3_icon = landingVal($landingSettings, 'hero_tag_3_icon', 'palette');
    $hero_tag_3_text = landingVal($landingSettings, 'hero_tag_3_text', 'Desain Grafis');
    $hero_stat_1_value = landingVal($landingSettings, 'hero_stat_1_value', '4+');
    $hero_stat_1_label = landingVal($landingSettings, 'hero_stat_1_label', 'Bidang Kreatif');
    $hero_stat_2_value = landingVal($landingSettings, 'hero_stat_2_value', 'Gratis');
    $hero_stat_2_label = landingVal($landingSettings, 'hero_stat_2_label', 'Tanpa Biaya');
    $hero_stat_3_value = landingVal($landingSettings, 'hero_stat_3_value', '2026');
    $hero_stat_3_label = landingVal($landingSettings, 'hero_stat_3_label', 'Tahun Akademik');
    $hero_scroll_text = landingVal($landingSettings, 'hero_scroll_text', 'Scroll ke bawah untuk informasi lanjut');
    $form_title = landingVal($landingSettings, 'form_title', 'Daftar Sekarang');
    $form_password_info = landingVal($landingSettings, 'form_password_info', 'Password akun akan diisi otomatis');
    $form_password_value = landingVal($landingSettings, 'form_password_value', 'pelatihanku2026');
    $form_button_text = landingVal($landingSettings, 'form_button_text', 'Daftar Sekarang');
    $form_button_loading = landingVal($landingSettings, 'form_button_loading', 'Memproses Pendaftaran...');
    $form_login_text = landingVal($landingSettings, 'form_login_text', 'Sudah memiliki akun?');
    $form_login_link = landingVal($landingSettings, 'form_login_link', 'Login di sini');
    $steps_badge = landingVal($landingSettings, 'steps_badge', 'Alur Pendaftaran');
    $steps_title = landingVal($landingSettings, 'steps_title', 'Ikuti 3 Langkah Mudah');
    $steps_subtitle = landingVal($landingSettings, 'steps_subtitle', 'Panduan ringkas pendaftaran hingga Anda dapat mengakses materi pelatihan kami');
    $steps_1_title = landingVal($landingSettings, 'steps_1_title', 'Daftarkan Akun');
    $steps_1_desc = landingVal($landingSettings, 'steps_1_desc', 'Lengkapi formulir pendaftaran di atas menggunakan data asli Anda. Tanpa pungutan biaya apa pun (100% Gratis).');
    $steps_2_title = landingVal($landingSettings, 'steps_2_title', 'Ikuti Kelas Pelatihan');
    $steps_2_desc = landingVal($landingSettings, 'steps_2_desc', 'Gunakan NIK Anda untuk masuk, akses modul pembelajaran komprehensif, dan ikuti instruksi mentor kami secara terarah.');
    $steps_3_title = landingVal($landingSettings, 'steps_3_title', 'Raih Hasil & Sertifikat');
    $steps_3_desc = landingVal($landingSettings, 'steps_3_desc', 'Tingkatkan nilai jual keterampilan, kembangkan usaha baru, dan dapatkan Sertifikat Kompetensi resmi di akhir pelatihan.');
    $pelatihan_badge = landingVal($landingSettings, 'pelatihan_badge', 'Program Unggulan');
    $pelatihan_title = landingVal($landingSettings, 'pelatihan_title', 'Pelatihan yang Tersedia');
    $pelatihan_subtitle = landingVal($landingSettings, 'pelatihan_subtitle', 'Pilih kelas sesuai minat Anda. Kuota terbatas, segera daftar sebelum pendaftaran ditutup.');
    $pelatihan_empty_title = landingVal($landingSettings, 'pelatihan_empty_title', 'Belum Ada Pelatihan Aktif');
    $pelatihan_empty_desc = landingVal($landingSettings, 'pelatihan_empty_desc', 'Silakan kembali beberapa saat lagi untuk melihat program pelatihan terbaru kami.');
    $why_badge = landingVal($landingSettings, 'why_badge', 'Mengapa Memilih');
    $why_title = landingVal($landingSettings, 'why_title', 'Mengapa Memilih Pelatihan Kami?');
    $why_subtitle = landingVal($landingSettings, 'why_subtitle', 'Program pembinaan terstruktur yang dirancang agar setiap peserta siap terjun ke dunia industri dan wirausaha kreatif');
    $cta_badge = landingVal($landingSettings, 'cta_badge', 'SEGERA BERGABUNG');
    $cta_title = landingVal($landingSettings, 'cta_title', 'Siap Memulai Perjalanan Anda?');
    $cta_subtitle = landingVal($landingSettings, 'cta_subtitle', 'Daftarkan diri Anda hari ini dan jadilah bagian dari perubahan ekonomi kreatif yang mandiri dan berdaya saing.');
    $cta_button_text = landingVal($landingSettings, 'cta_button_text', 'Daftar Sekarang — Gratis!');
    $cta_login_text = landingVal($landingSettings, 'cta_login_text', 'Sudah Punya Akun? Login');
@endphp

@extends('layouts/blankLayout')

@section('title', __('Pendaftaran Pelatihan Ekonomi Kreatif') . ' 2026')

@section('page-style')
<style>
  /* ============================================================
     CUSTOM STYLES — Landing Page Pelatihan Ekonomi Kreatif 2026
     ============================================================ */

  /* --- Fonts Import for Premium Look --- */
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  /* --- Typography Base --- */
  #beranda-page-wrapper {
    font-family: 'Outfit', sans-serif;
    background-color: #0b0f19;
    color: #f8fafc;
    overflow-x: hidden;
  }
  #beranda-page-wrapper h1,
  #beranda-page-wrapper h2,
  #beranda-page-wrapper h3,
  #beranda-page-wrapper h4,
  #beranda-page-wrapper h5,
  #beranda-page-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }

  /* --- Floating Navigation Bar --- */
  .navbar-glass-floating {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: calc(100% - 40px);
    max-width: 1200px;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 5px;
    padding: 12px 28px;
    z-index: 1000;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  }
  .navbar-glass-floating.scrolled {
    top: 10px;
    background: rgba(15, 23, 42, 0.85);
    border-color: rgba(99, 102, 241, 0.25);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), 0 0 25px rgba(99, 102, 241, 0.15);
  }
  .logo-icon-glow {
    width: 38px;
    height: 38px;
    border-radius: 5px;
    background: linear-gradient(135deg, #6366f1, #d946ef);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
    transition: transform 0.3s ease;
  }
  .navbar-logo:hover .logo-icon-glow {
    transform: rotate(15deg) scale(1.05);
  }
  .logo-text-glow {
    font-family: 'Sora', sans-serif;
    font-size: 1.25rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.5px;
  }
  .nav-link-premium {
    font-weight: 500;
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    padding: 6px 0;
  }
  .nav-link-premium::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #6366f1, #d946ef);
    transition: width 0.3s ease;
    border-radius: 2px;
  }
  .nav-link-premium:hover {
    color: #ffffff;
  }
  .nav-link-premium:hover::after {
    width: 100%;
  }
  .btn-login-premium {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 8px 22px;
    border-radius: 5px;
    transition: all 0.3s ease;
    text-decoration: none;
  }
  .btn-login-premium:hover {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    border-color: transparent;
    color: #ffffff;
    box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
    transform: translateY(-2px);
  }

  /* --- Premium Mesh Gradient Backdrop --- */
  .hero-gradient-animated {
    background: #0b0f19;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%);
    position: relative;
    padding-top: 140px;
    padding-bottom: 130px;
  }
  @media (max-width: 991.98px) {
    .hero-gradient-animated {
      padding-top: 100px;
      padding-bottom: 60px;
    }
  }

  /* --- Dynamic Floating Orbs --- */
  .glow-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.4;
    mix-blend-mode: screen;
    pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out;
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

  /* --- Glass Card Redesign --- */
  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border-radius: 5px;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .glass-card-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5), 0 0 30px rgba(99, 102, 241, 0.08);
  }

  /* --- Input Fields Redesign --- */
  .form-control-custom {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
  }
  .form-control-custom:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25), 0 0 20px rgba(99, 102, 241, 0.15) !important;
    color: #ffffff !important;
  }
  .form-control-custom::placeholder {
    color: rgba(255, 255, 255, 0.35) !important;
  }
  .form-control-custom.is-invalid {
    border-color: #f87171 !important;
    box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.2) !important;
  }
  .form-control-custom:-webkit-autofill,
  .form-control-custom:-webkit-autofill:hover,
  .form-control-custom:-webkit-autofill:focus,
  .form-control-custom:-webkit-autofill:active {
    -webkit-text-fill-color: #ffffff !important;
    transition: background-color 5000s ease-in-out 0s;
    background-clip: padding-box !important;
    box-shadow: 0 0 0 1000px #131824 inset !important;
    -webkit-box-shadow: 0 0 0 1000px #131824 inset !important;
  }
  .form-label-custom {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 600 !important;
    font-size: 0.75rem !important;
    letter-spacing: 0.08em !important;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.7) !important;
    margin-bottom: 4px;
  }
  .input-group-text {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    font-weight: 600;
    border-radius: 5px !important;
  }
  .input-group > :not(:last-child):not(.dropdown-toggle):not(.dropdown-menu) {
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
  }
  .input-group > :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
  }

  /* --- Benefit Tags in Hero --- */
  .benefit-badge {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 5px;
    padding: 10px 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
  }
  .benefit-badge:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(99, 102, 241, 0.4);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.25);
  }

  /* --- Buttons with Pulse Glow --- */
  .btn-glow {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    border: none;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    color: #0b0f19 !important;
  }
  .btn-glow:hover {
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 10px 30px rgba(255, 152, 0, 0.5);
    background: linear-gradient(135deg, #ffca28, #ffa726);
  }

  /* --- Step Items --- */
  .step-card-premium {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.04);
    border-radius: 5px;
    padding: 36px 28px;
    position: relative;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.01);
    height: 100%;
    z-index: 1;
  }
  .step-card-premium:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(99, 102, 241, 0.08);
    border-color: rgba(99, 102, 241, 0.15);
  }
  .step-icon-glow {
    width: 54px;
    height: 54px;
    border-radius: 5px;
    background: rgba(99, 102, 241, 0.06);
    color: #6366f1;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    font-size: 1.5rem;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .step-card-premium:hover .step-icon-glow {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    color: #ffffff;
    box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
  }
  .step-number-pill {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6366f1;
    background: rgba(99, 102, 241, 0.08);
    padding: 4px 12px;
    border-radius: 5px;
    display: inline-block;
    margin-bottom: 12px;
  }

  /* --- Bento Grid Benefits --- */
  .bento-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }
  .bento-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.04);
    border-radius: 5px;
    padding: 36px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.01);
  }
  .bento-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(99, 102, 241, 0.1);
    border-color: rgba(99, 102, 241, 0.2);
  }
  .bento-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.03), transparent 70%);
    pointer-events: none;
  }
  .bento-card-large {
    grid-column: span 2;
  }
  .bento-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    margin-bottom: 24px;
  }
  .bento-icon-primary {
    background: rgba(99, 102, 241, 0.08);
    color: #6366f1;
  }
  .bento-icon-success {
    background: rgba(16, 185, 129, 0.08);
    color: #10b981;
  }
  .bento-icon-warning {
    background: rgba(245, 158, 11, 0.08);
    color: #f59e0b;
  }

  /* --- Interactive FAQ Accordion --- */
  .faq-accordion-item {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.04) !important;
    border-radius: 5px !important;
    margin-bottom: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.01);
  }
  .faq-accordion-item:hover {
    border-color: rgba(99, 102, 241, 0.15) !important;
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.04);
  }
  .faq-accordion-button {
    width: 100%;
    padding: 22px 28px;
    background: none;
    border: none;
    text-align: left;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    font-size: 1.05rem;
    color: #1e293b;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
  }
  .faq-accordion-button:focus {
    outline: none;
    box-shadow: none;
  }
  .faq-accordion-button.collapsed {
    color: #1e293b;
  }
  .faq-accordion-button:not(.collapsed) {
    color: #6366f1;
    background-color: rgba(99, 102, 241, 0.02);
  }
  .faq-chevron {
    transition: transform 0.3s ease;
    color: #64748b;
    font-size: 1.2rem;
  }
  .faq-accordion-button:not(.collapsed) .faq-chevron {
    transform: rotate(180deg);
    color: #6366f1;
  }
  .faq-accordion-body {
    padding: 6px 28px 24px 28px;
    font-family: 'Outfit', sans-serif;
    color: #475569;
    line-height: 1.6;
    font-size: 0.95rem;
  }

  /* --- Premium Mesh CTA Section --- */
  .cta-mesh-container {
    background: radial-gradient(circle at 20% 30%, #4f46e5, transparent 60%),
                radial-gradient(circle at 80% 70%, #c084fc, transparent 60%),
                linear-gradient(135deg, #0f172a 0%, #090c15 100%);
    border-radius: 5px;
    padding: 70px 40px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 30px 70px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
  }
  .btn-glow-outline {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
  }
  .btn-glow-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: #ffffff;
    color: #ffffff;
    transform: translateY(-2px);
  }

  /* --- Premium Footer --- */
  .footer-premium {
    background: #06080e;
    border-top: 1px solid rgba(255,255,255,0.04);
  }
  .footer-link {
    color: rgba(255, 255, 255, 0.5);
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.3s ease;
  }
  .footer-link:hover {
    color: #ffc107;
    transform: translateX(4px);
    display: inline-block;
  }
  .social-icon-btn {
    width: 38px;
    height: 38px;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.6);
    font-size: 1.15rem;
    transition: all 0.3s ease;
    text-decoration: none;
  }
  .social-icon-btn:hover {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    border-color: transparent;
    color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
  }

  /* --- Scroll reveal --- */
  .reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
  }
  .reveal.revealed {
    opacity: 1;
    transform: translateY(0);
  }

  /* --- Wave Separator --- */
  .section-divider {
    position: relative;
    height: 80px;
    margin-top: -80px;
    z-index: 1;
  }
  .section-divider svg {
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 80px;
  }

  /* --- Custom Badge pulse --- */
  .badge-pulse {
    animation: badgePulse 2.5s ease-in-out infinite;
  }
  @keyframes badgePulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
  }

  /* --- Responsive Styles --- */
  @media (max-width: 991.98px) {
    .bento-grid {
      grid-template-columns: 1fr;
    }
    .bento-card-large {
      grid-column: span 1;
    }
    .navbar-glass-floating {
      width: calc(100% - 20px);
      padding: 10px 18px;
    }
  }

  /* --- Custom Scrollbar --- */
  ::-webkit-scrollbar { width: 8px; }
  ::-webkit-scrollbar-track { background: #0b0f19; }
  ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 4px; }
  ::-webkit-scrollbar-thumb:hover { background: #d946ef; }

  html { scroll-behavior: smooth; }

  /* ============================================================
     MOBILE SLIDE-IN MENU — Premium Drawer dari Kiri
     ============================================================ */

  /* Overlay blur di belakang panel */
  .mobile-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    height: 100dvh;
    background: rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 1040;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.35s ease, visibility 0.35s ease;
    cursor: pointer;
  }
  .mobile-overlay.active {
    opacity: 1;
    visibility: visible;
  }

  /* Panel slide-in dari kiri */
  .mobile-slide-panel {
    position: fixed;
    top: 0;
    left: 0;
    width: 290px;
    max-width: 80vw;
    height: 100vh;
    height: 100dvh;
    background: rgba(12, 16, 28, 0.97);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border-right: 1px solid rgba(255, 255, 255, 0.07);
    z-index: 1050;
    overflow-y: auto;
    transform: translateX(-100%) translateX(-30px);
    transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 30px rgba(0, 0, 0, 0.3);
  }
  .mobile-slide-panel.active {
    transform: translateX(0);
  }

  /* Header panel: judul + tombol close */
  .panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 24px 18px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
  }
  .panel-title {
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    font-size: 1.15rem;
    color: #ffffff;
    letter-spacing: -0.3px;
  }
  .panel-close-btn {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #ffffff;
    width: 36px;
    height: 36px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s ease;
  }
  .panel-close-btn:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.2);
  }
  .panel-close-btn:active {
    transform: scale(0.92);
  }

  /* Navigasi panel */
  .panel-nav {
    flex: 1;
    padding: 16px 12px;
    display: flex;
    flex-direction: column;
    gap: 2px;
  }
  .panel-link {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    color: rgba(255, 255, 255, 0.65);
    text-decoration: none;
    font-weight: 500;
    font-size: 1rem;
    border-radius: 5px;
    transition: all 0.2s ease;
    font-family: 'Outfit', sans-serif;
    position: relative;
  }
  .panel-link i {
    font-size: 1.25rem;
    width: 22px;
    text-align: center;
    color: rgba(255, 255, 255, 0.35);
    transition: color 0.2s ease;
    flex-shrink: 0;
  }
  .panel-link:hover {
    background: rgba(255, 255, 255, 0.06);
    color: #ffffff;
  }
  .panel-link:hover i {
    color: #818cf8;
  }
  .panel-link:active {
    background: rgba(99, 102, 241, 0.15);
    color: #ffffff;
    transform: scale(0.98);
  }

  /* Footer panel: login button */
  .panel-footer {
    padding: 16px 20px 28px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
  }
  .panel-footer .btn-login-premium {
    font-size: 0.95rem;
    padding: 10px 22px;
  }

  /* Animated Hamburger: 3 bar jadi X */
  .mobile-menu-btn {
    display: none; /* override dengan d-lg-none (Bootstrap) */
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 5px;
    width: 38px;
    height: 38px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 5px;
    cursor: pointer;
    padding: 8px 9px;
    transition: all 0.25s ease;
  }
  .mobile-menu-btn:hover {
    background: rgba(255, 255, 255, 0.1);
  }
  .mobile-menu-btn .bar {
    display: block;
    width: 18px;
    height: 2px;
    background: #ffffff;
    border-radius: 2px;
    transition: all 0.3s ease;
    transform-origin: center;
  }
  .mobile-menu-btn.active .bar:nth-child(1) {
    transform: translateY(7px) rotate(45deg);
  }
  .mobile-menu-btn.active .bar:nth-child(2) {
    opacity: 0;
    transform: scaleX(0);
  }
  .mobile-menu-btn.active .bar:nth-child(3) {
    transform: translateY(-7px) rotate(-45deg);
  }

  /* Body scroll lock saat menu terbuka */
  body.mobile-menu-open {
    overflow: hidden;
  }

  /* Fix navbar padding di mobile */
  @media (max-width: 991.98px) {
    .navbar-glass-floating {
      padding: 10px 16px;
    }
    .mobile-menu-btn {
      display: flex; /* override d-lg-none: tampil di mobile */
    }
  }

  /* ============================================================
     GRID CARDS PELATIHAN PUBLIK — Premium Dark Selection
     ============================================================ */
  .pelatihan-grid-section {
    background: #0b0f19;
    position: relative;
    overflow: hidden;
  }
  .pelatihan-grid-section::before {
    content: '';
    position: absolute;
    top: -10%;
    right: -5%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
    pointer-events: none;
  }
  .pelatihan-grid-section::after {
    content: '';
    position: absolute;
    bottom: -10%;
    left: -5%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
    pointer-events: none;
  }

  .pelatihan-card {
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
  }
  .pelatihan-card:hover {
    transform: translateY(-8px);
    border-color: rgba(255, 193, 7, 0.35);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4), 0 0 30px rgba(255, 193, 7, 0.08);
  }

  .pelatihan-card .card-cover {
    position: relative;
    height: 180px;
    overflow: hidden;
  }
  .pelatihan-card .card-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .pelatihan-card:hover .card-cover img {
    transform: scale(1.08);
  }
  .pelatihan-card .card-cover::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(11, 15, 25, 0.9) 0%, rgba(11, 15, 25, 0.2) 50%, transparent 100%);
  }

  .pelatihan-card .card-badge-category {
    position: absolute;
    top: 14px;
    left: 14px;
    z-index: 2;
    padding: 6px 12px;
    background: rgba(11, 15, 25, 0.75);
    border: 1px solid rgba(255, 193, 7, 0.3);
    border-radius: 20px;
    color: #ffc107;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    backdrop-filter: blur(8px);
  }

  .pelatihan-card .card-badge-status {
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 2;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    backdrop-filter: blur(8px);
  }
  .card-status-open {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #34d399;
  }
  .card-status-limited {
    background: rgba(245, 158, 11, 0.15);
    border: 1px solid rgba(245, 158, 11, 0.3);
    color: #fbbf24;
  }
  .card-status-full {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #f87171;
  }

  .pelatihan-card .card-body {
    padding: 22px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .pelatihan-card .batch-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #6366f1;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 6px;
  }

  .pelatihan-card .card-title {
    font-family: 'Sora', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.4;
    margin-bottom: 14px;
  }

  .pelatihan-card .card-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.65);
    margin-bottom: 10px;
  }
  .pelatihan-card .card-meta i {
    color: rgba(255, 255, 255, 0.4);
    font-size: 1rem;
  }

  .pelatihan-card .quota-bar {
    margin-top: auto;
    padding-top: 16px;
  }
  .pelatihan-card .quota-label {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 6px;
  }
  .pelatihan-card .quota-label strong {
    color: #ffc107;
  }
  .pelatihan-card .progress {
    height: 5px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    overflow: hidden;
  }
  .pelatihan-card .progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease;
  }

  .pelatihan-card .card-footer-action {
    padding: 16px 22px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .pelatihan-card .price-tag {
    font-family: 'Sora', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #ffc107;
  }
  .pelatihan-card .price-tag small {
    display: block;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.45);
    font-weight: 500;
    text-decoration: line-through;
  }

  .pelatihan-card .btn-daftar-card {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    border: none;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: #0b0f19;
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
  }
  .pelatihan-card .btn-daftar-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 152, 0, 0.35);
    background: linear-gradient(135deg, #ffca28, #ffa726);
  }

  .pelatihan-empty-state {
    text-align: center;
    padding: 60px 20px;
    background: rgba(15, 23, 42, 0.4);
    border: 1px dashed rgba(255, 255, 255, 0.12);
    border-radius: 12px;
  }
  .pelatihan-empty-state i {
    font-size: 3rem;
    color: rgba(255, 255, 255, 0.2);
    margin-bottom: 16px;
  }
  .pelatihan-empty-state h5 {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 8px;
  }
  .pelatihan-empty-state p {
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 0;
  }

  @media (max-width: 767.98px) {
    .pelatihan-card .card-cover {
      height: 160px;
    }
    .pelatihan-card .card-title {
      font-size: 1rem;
    }
    .pelatihan-card .btn-daftar-card {
      padding: 8px 14px;
      font-size: 0.75rem;
    }
  }
</style>
@endsection

@section('content')
<div id="beranda-page-wrapper">

  @include('partials.floating-navbar')

  <!-- ============================================================
       HERO SECTION
       ============================================================ -->
  <section id="beranda" class="hero-gradient-animated position-relative overflow-hidden min-vh-100 d-flex align-items-center">

    <!-- Floating Gradient Background Orbs -->
    <div class="glow-orb orb-1"></div>
    <div class="glow-orb orb-2"></div>
    <div class="glow-orb orb-3"></div>

    <div class="container position-relative py-lg-5 py-3" style="z-index: 10;">
      <div class="row align-items-center justify-content-between py-lg-4 py-0 mt-3 mt-lg-0">

        <!-- LEFT: Hero Copywriting -->
        <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">


          <!-- Main Typography Heading -->
          <h1 class="display-4 fw-bold text-white mb-4 hero-title" style="line-height: 1.15; font-family: 'Sora', sans-serif;">
            {{ $hero_title }}<br/>
            <span class="text-warning text-gradient-creative">{{ $hero_subtitle }}</span><br/>
          </h1>

          <!-- Body Description -->
          <p class="text-white-50 fs-5 mb-4 lh-lg" style="max-width: 560px;">
            {{ $hero_description }}
          </p>

          <!-- Interactive Category Tags -->
          <div class="d-flex flex-wrap gap-3 mb-5 justify-content-center justify-content-lg-start">
            <div class="benefit-badge">
              <i class="icon-base ti tabler-{{ $hero_tag_1_icon }} fs-5 text-warning"></i>
              <span>{{ $hero_tag_1_text }}</span>
            </div>
            <div class="benefit-badge">
              <i class="icon-base ti tabler-{{ $hero_tag_2_icon }} fs-5 text-warning"></i>
              <span>{{ $hero_tag_2_text }}</span>
            </div>
            <div class="benefit-badge">
              <i class="icon-base ti tabler-{{ $hero_tag_3_icon }} fs-5 text-warning"></i>
              <span>{{ $hero_tag_3_text }}</span>
            </div>
          </div>

          <!-- Elevated Statistics Grid -->
          <div class="row g-4 pt-4 border-top border-white-subtle" style="border-top: 1px solid rgba(255, 255, 255, 0.08) !important;">
            <div class="col-4 col-sm-4">
              <div class="text-white fw-bold display-6" style="font-family: 'Sora', sans-serif;">{{ $hero_stat_1_value }}</div>
              <div class="text-white-50 small">{{ $hero_stat_1_label }}</div>
            </div>
            <div class="col-4 col-sm-4">
              <div class="text-white fw-bold display-6 text-gradient-creative" style="font-family: 'Sora', sans-serif;">{{ $hero_stat_2_value }}</div>
              <div class="text-white-50 small">{{ $hero_stat_2_label }}</div>
            </div>
            <div class="col-4 col-sm-4">
              <div class="text-white fw-bold display-6" style="font-family: 'Sora', sans-serif;">{{ $hero_stat_3_value }}</div>
              <div class="text-white-50 small">{{ $hero_stat_3_label }}</div>
            </div>
          </div>

          <!-- Bottom Floating Scroll Indicator -->
          <div class="d-none d-lg-flex align-items-center text-white-50 mt-5 pt-3" style="opacity: 0.45;">
            <span class="me-3 small">{{ $hero_scroll_text }}</span>
            <svg width="16" height="24" viewBox="0 0 16 24" fill="none" style="animation: bounce 2s infinite;">
              <rect x="1" y="1" width="14" height="22" rx="7" stroke="currentColor" stroke-width="2"/>
              <circle cx="8" cy="8" r="2" fill="currentColor">
                <animate attributeName="cy" values="8;14;8" dur="2s" repeatCount="indefinite"/>
              </circle>
            </svg>
          </div>
        </div>

        <!-- RIGHT: Glassmorphic Registration Card Form -->
        <div class="col-lg-5 col-xl-5">
          <div class="glass-card-premium px-4 px-xl-5 py-4">

            <!-- Card Form Header -->
            <div class="text-center mb-3">
              <h4 class="fw-bold text-white mb-0">{{ $form_title }}</h4>
            </div>

            <!-- Success Dynamic Laravel Alert -->
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
              <div class="d-flex align-items-center">
                <i class="icon-base ti tabler-check-circle fs-4 me-2"></i>
                <span class="small fw-semibold">{{ session('success') }}</span>
              </div>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Registration HTML Form -->
            <form id="formRegistration" action="{{ route('landing.register') }}" method="POST" novalidate>
              @csrf

              <!-- Input Nama — Full width, auto uppercase -->
              <div class="mb-3">
                <label for="name" class="form-label form-label-custom">{{ __('Nama Lengkap (Sesuai KTP)') }}</label>
                <input type="text" id="name" name="name"
                  class="form-control form-control-custom @error('name') is-invalid @enderror"
                  placeholder="Contoh: ANDI PRATAMA" value="{{ old('name') }}"
                  style="text-transform: uppercase;"
                  oninput="this.value = this.value.toUpperCase()" required />
                @error('name') <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div> @enderror
              </div>

              <!-- NIK + Email — Side by side (half-half) -->
              <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                  <label for="nik" class="form-label form-label-custom">{{ __('NIK (Nomor Induk Kependudukan)') }}</label>
                  <input type="text" id="nik" name="nik"
                    class="form-control form-control-custom @error('nik') is-invalid @enderror"
                    placeholder="15-16 digit NIK" maxlength="16" inputmode="numeric" required />
                  <div id="nik-feedback" class="small mt-2 d-none"></div>
                  @error('nik') <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label form-label-custom">{{ __('Alamat Email') }}</label>
                  <input type="email" id="email" name="email"
                    class="form-control form-control-custom @error('email') is-invalid @enderror"
                    placeholder="contoh@email.com" value="{{ old('email') }}" required />
                  @error('email') <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div> @enderror
                </div>
              </div>

              <!-- Input WhatsApp — Full width -->
              <div class="mb-3">
                <label for="whatsapp" class="form-label form-label-custom">{{ __('Nomor WhatsApp Aktif') }}</label>
                <input type="tel" id="whatsapp" name="whatsapp"
                  class="form-control form-control-custom @error('whatsapp') is-invalid @enderror"
                  placeholder="0821xxxxxxxx" value="{{ old('whatsapp') }}" required />
                <div class="d-flex justify-content-between mt-1">
                  <div id="wa-feedback" class="small d-none"></div>
                  <small id="wa-format-hint" class="text-white-50">Format: 08xx atau 628xx</small>
                </div>
                @error('whatsapp') <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div> @enderror
              </div>

              <!-- Info: Password default -->
              <div class="d-flex align-items-start gap-2 mb-3 p-3" style="background: rgba(255, 193, 7, 0.08); border: 1px solid rgba(255, 193, 7, 0.15); border-radius: 5px;">
                <i class="icon-base ti tabler-info-circle text-warning mt-1 flex-shrink-0"></i>
                <div>
                  <p class="small text-white-50 mb-0" style="line-height: 1.45;">
                    {{ $form_password_info }}.
                    <span class="text-warning fw-semibold">{{ $form_password_value }}</span>
                  </p>
                </div>
              </div>

              <!-- Animated Button Submit -->
              <button type="submit" id="btnDaftar" class="btn btn-warning btn-lg w-100 fw-semibold btn-glow py-3" style="font-family: 'Sora', sans-serif; border-radius: 5px;">
                <span id="btn-text">{{ $form_button_text }} <i class="icon-base ti tabler-arrow-right ms-2"></i></span>
                <span id="btn-loading" class="d-none">
                  <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                  {{ $form_button_loading }}
                </span>
              </button>

              <!-- Link back to login -->
              <p class="text-center mt-3 mb-0">
                <span class="text-white-50 small">{{ $form_login_text }} </span>
                <a href="{{ route('login') }}" class="small fw-semibold text-warning text-decoration-none hover-white">{{ $form_login_link }}</a>
              </p>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ============================================================
       WAVE DIVIDER (Separator)
       ============================================================ -->
  <div class="section-divider">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none" style="fill: #fcfbf9;">
      <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z"/>
    </svg>
  </div>

  <!-- ============================================================
       HOW IT WORKS SECTION (Timeline Stepper)
       ============================================================ -->
  <section id="langkah" class="py-8 py-lg-10 text-dark position-relative" style="background: #fcfbf9;">
    <div class="container py-4">

      <!-- Section Title Header -->
      <div class="text-center mb-5 pb-3 reveal">
        <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 fw-semibold mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em; border-radius: 5px;">
          {{ strtoupper($steps_badge) }}
        </span>
        <h2 class="fw-bold mb-3 display-6" style="color: #0b0f19;">{{ $steps_title }}</h2>
        <p class="text-muted fs-5 mx-auto" style="max-width: 520px;">
          {{ $steps_subtitle }}
        </p>
      </div>

      <!-- Steps Timeline Row -->
      <div class="row g-4 justify-content-center">
        <!-- Step 1 -->
        <div class="col-md-4 reveal" style="transition-delay: 0.1s;">
          <div class="step-card-premium">
            <span class="step-number-pill">{{ __('Langkah') }} 01</span>
            <div class="step-icon-glow">
              <i class="icon-base ti tabler-user-check"></i>
            </div>
            <h5 class="fw-bold mt-0 mb-3" style="color: #0b0f19; font-family: 'Sora', sans-serif;">{{ $steps_1_title }}</h5>
            <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6;">
              {{ $steps_1_desc }}
            </p>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="col-md-4 reveal" style="transition-delay: 0.2s;">
          <div class="step-card-premium">
            <span class="step-number-pill">{{ __('Langkah') }} 02</span>
            <div class="step-icon-glow">
              <i class="icon-base ti tabler-school"></i>
            </div>
            <h5 class="fw-bold mt-0 mb-3" style="color: #0b0f19; font-family: 'Sora', sans-serif;">{{ $steps_2_title }}</h5>
            <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6;">
              {{ $steps_2_desc }}
            </p>
          </div>
        </div>

        <!-- Step 3 -->
        <div class="col-md-4 reveal" style="transition-delay: 0.3s;">
          <div class="step-card-premium">
            <span class="step-number-pill">{{ __('Langkah') }} 03</span>
            <div class="step-icon-glow">
              <i class="icon-base ti tabler-award"></i>
            </div>
            <h5 class="fw-bold mt-0 mb-3" style="color: #0b0f19; font-family: 'Sora', sans-serif;">{{ $steps_3_title }}</h5>
            <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.6;">
              {{ $steps_3_desc }}
            </p>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ============================================================
       PELATIHAN GRID SECTION — Daftar Kelas Tersedia
       ============================================================ -->
  <section id="pelatihan" class="py-8 py-lg-10 pelatihan-grid-section">
    <div class="container py-4 position-relative" style="z-index: 1;">

      <!-- Section Header -->
      <div class="text-center mb-5 pb-3 reveal">
        <span class="badge bg-warning bg-opacity-10 text-warning px-4 py-2 fw-semibold mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em; border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 5px;">
          {{ $pelatihan_badge }}
        </span>
        <h2 class="fw-bold mb-3 display-6" style="color: #ffffff; font-family: 'Sora', sans-serif;">
          {{ $pelatihan_title }}
        </h2>
        <p class="text-white-50 fs-5 mx-auto" style="max-width: 580px;">
          {{ $pelatihan_subtitle }}
        </p>
      </div>

      @php
        \Carbon\Carbon::setLocale('id');

        $coverImages = [
          'kuliner' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=800&auto=format&fit=crop',
          'kriya' => 'https://images.unsplash.com/photo-1452860606245-08befc0ff44b?q=80&w=800&auto=format&fit=crop',
          'desain' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?q=80&w=800&auto=format&fit=crop',
          'film' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?q=80&w=800&auto=format&fit=crop',
          'foto' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=800&auto=format&fit=crop',
          'animasi' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?q=80&w=800&auto=format&fit=crop',
          'marketing' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=800&auto=format&fit=crop',
          'bisnis' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop',
          'teknologi' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=800&auto=format&fit=crop',
          'default' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop',
        ];

        $keywordMap = [
          'kuliner' => 'kuliner', 'makanan' => 'kuliner', 'pastry' => 'kuliner',
          'kriya' => 'kriya', 'kreasi' => 'kriya', 'diy' => 'kriya',
          'desain' => 'desain', 'design' => 'desain',
          'film' => 'film', 'video' => 'film',
          'animasi' => 'animasi',
          'foto' => 'foto', 'fotografi' => 'foto',
          'marketing' => 'marketing', 'iklan' => 'marketing', 'periklanan' => 'marketing',
          'bisnis' => 'bisnis', 'usaha' => 'bisnis',
          'teknologi' => 'teknologi', 'it' => 'teknologi', 'software' => 'teknologi',
        ];
      @endphp

      @if($pelatihans->count() > 0)
        <div class="row g-4">
          @foreach($pelatihans as $pelatihan)
            @php
              $namaLower = strtolower($pelatihan->nama);
              $imageKey = 'default';
              foreach ($keywordMap as $keyword => $key) {
                if (str_contains($namaLower, $keyword)) {
                  $imageKey = $key;
                  break;
                }
              }
              $coverUrl = $coverImages[$imageKey] ?? $coverImages['default'];

              $approvedCount = $pelatihan->approved_enrollments_count ?? 0;
              $quota = $pelatihan->kuota;
              $isKuotaUnlimited = is_null($quota) || $quota <= 0;
              $percentage = $isKuotaUnlimited ? 0 : min(100, round(($approvedCount / $quota) * 100, 1));

              if ($isKuotaUnlimited) {
                $statusClass = 'card-status-open';
                $statusText = __('Pendaftaran Dibuka');
                $quotaText = __('Kuota Terbuka');
                $barColor = 'bg-success';
              } elseif ($percentage >= 100) {
                $statusClass = 'card-status-full';
                $statusText = __('Kuota Penuh');
                $quotaText = __('Kuota Penuh');
                $barColor = 'bg-danger';
              } elseif ($percentage >= 80) {
                $statusClass = 'card-status-limited';
                $statusText = __('Sisa Sedikit');
                $quotaText = ($quota - $approvedCount) . ' ' . __('kursi tersisa');
                $barColor = 'bg-warning';
              } else {
                $statusClass = 'card-status-open';
                $statusText = __('Pendaftaran Dibuka');
                $quotaText = $approvedCount . ' / ' . $quota . ' ' . __('kursi terisi');
                $barColor = 'bg-success';
              }

              $dateRange = '';
              if ($pelatihan->tanggal_mulai && $pelatihan->tanggal_selesai) {
                $dateRange = $pelatihan->tanggal_mulai->translatedFormat('d M') . ' - ' . $pelatihan->tanggal_selesai->translatedFormat('d M Y');
              } elseif ($pelatihan->tanggal_mulai) {
                $dateRange = $pelatihan->tanggal_mulai->translatedFormat('d M Y');
              } else {
                $dateRange = __('Jadwal Menyusul');
              }

              $kecamatanNames = $pelatihan->kecamatans->pluck('name')->filter()->values();
              if ($kecamatanNames->isNotEmpty()) {
                $displayKecamatan = $kecamatanNames->take(3)->implode(', ');
                $remainingCount = $kecamatanNames->count() - 3;
                if ($remainingCount > 0) {
                  $displayKecamatan .= ' +' . $remainingCount . ' ' . __('lainnya');
                }
                $lokasiText = __('Khusus') . ': ' . $displayKecamatan;
              } else {
                $lokasiText = __('Untuk semua kecamatan');
              }

              $batchDisplay = str_starts_with(strtoupper($pelatihan->batch), 'BATCH ') 
                ? substr($pelatihan->batch, 6) 
                : $pelatihan->batch;
            @endphp

            <div class="col-md-6 col-lg-4 reveal" style="transition-delay: {{ $loop->iteration * 0.1 }}s;">
              <div class="pelatihan-card h-100">
                <!-- Cover Image -->
                <div class="card-cover">
                  <img src="{{ $coverUrl }}" alt="{{ $pelatihan->nama }}" loading="lazy">
                  <span class="card-badge-category">{{ $imageKey }}</span>
                  <span class="card-badge-status {{ $statusClass }}">{{ $statusText }}</span>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                  <div class="batch-label">{{ __('Batch') }} {{ $batchDisplay }}</div>
                  <h5 class="card-title">{{ $pelatihan->nama }}</h5>

                  <div class="card-meta">
                    <i class="icon-base ti tabler-building"></i>
                    <span>{{ $pelatihan->dinas?->nama_dinas ?? __('Dinas Penyelenggara') }}</span>
                  </div>

                  <div class="card-meta">
                    <i class="icon-base ti tabler-calendar"></i>
                    <span>{{ $dateRange }}</span>
                  </div>

                  <div class="card-meta">
                    <i class="icon-base ti tabler-map-pin"></i>
                    <span>{{ $lokasiText }}</span>
                  </div>

                  @if(!$isKuotaUnlimited)
                    <div class="quota-bar">
                      <div class="quota-label">
                        <span>{{ __('Terisi') }}</span>
                        <strong>{{ $quotaText }}</strong>
                      </div>
                      <div class="progress">
                        <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  @endif
                </div>

                <!-- Card Footer -->
                <div class="card-footer-action">
                  <div class="price-tag">
                    <small>Rp 0</small>
                    {{ __('Gratis') }}
                  </div>
                  <a href="#beranda" class="btn-daftar-card">
                    {{ __('Daftar') }} <i class="icon-base ti tabler-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="pelatihan-empty-state reveal">
          <i class="icon-base ti tabler-calendar-off"></i>
          <h5>{{ $pelatihan_empty_title }}</h5>
          <p>{{ $pelatihan_empty_desc }}</p>
        </div>
      @endif

    </div>
  </section>

  <!-- ============================================================
       WHY SECTION (Modern Bento Grid Proposition)
       ============================================================ -->
  <section id="mengapa" class="py-8 py-lg-10" style="background: #f8f6ff; color: #1e293b;">
    <div class="container py-4">

      <!-- Section Header -->
      <div class="text-center mb-5 pb-3 reveal">
        <span class="badge bg-warning bg-opacity-10 text-warning px-4 py-2 fw-semibold mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em; border: 1px solid rgba(245, 158, 11, 0.15); border-radius: 5px;">
          {{ $why_badge }} {{ $institutionName }}
        </span>
        <h2 class="fw-bold mb-3 display-6" style="color: #0b0f19; font-family: 'Sora', sans-serif;">{{ $why_title }}</h2>
        <p class="text-muted fs-5 mx-auto" style="max-width: 580px;">
          {{ $why_subtitle }}
        </p>
      </div>

      <!-- Bento Grid Design -->
      <div class="bento-grid">

        <!-- Bento Card 1: Wide Card -->
        <div class="bento-card bento-card-large reveal" style="transition-delay: 0.1s;">
          <div class="bento-icon-box bento-icon-primary">
            <i class="icon-base ti tabler-briefcase"></i>
          </div>
          <h4 class="fw-bold mb-3" style="color: #0b0f19;">Peningkatan Pendapatan Mandiri</h4>
          <p class="text-muted mb-4" style="line-height: 1.7; font-size: 0.95rem;">
            Pelatihan membekali Anda dengan kompetensi teknis di bidang kuliner, kriya, desain, atau produksi konten kreatif yang bernilai ekonomi tinggi. Sangat tepat digunakan sebagai modal utama merintis bisnis dari nol maupun mencari penghasilan tambahan di sela kesibukan Anda.
          </p>
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-check text-success fs-5"></i>
                <span class="fw-semibold small text-dark">Ide Bisnis Siap Jalan</span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-check text-success fs-5"></i>
                <span class="fw-semibold small text-dark">Mentoring Praktik Intensif</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Bento Card 2: Square Card -->
        <div class="bento-card reveal" style="transition-delay: 0.2s;">
          <div class="bento-icon-box bento-icon-success">
            <i class="icon-base ti tabler-devices"></i>
          </div>
          <h4 class="fw-bold mb-3" style="color: #0b0f19;">Peluang Pasar Digital</h4>
          <p class="text-muted mb-0" style="line-height: 1.7; font-size: 0.95rem;">
            Kami mendesain silabus terintegrasi dengan strategi pemasaran digital, mengajarkan Anda cara menjual karya, produk makanan, atau jasa secara online.
          </p>
        </div>

        <!-- Bento Card 3: Square Card -->
        <div class="bento-card reveal" style="transition-delay: 0.1s;">
          <div class="bento-icon-box bento-icon-warning">
            <i class="icon-base ti tabler-users"></i>
          </div>
          <h4 class="fw-bold mb-3" style="color: #0b0f19;">Komunitas & Jejaring Alumni</h4>
          <p class="text-muted mb-0" style="line-height: 1.7; font-size: 0.95rem;">
            Setelah lulus, Anda otomatis bergabung dengan ikatan alumni yang berkolaborasi aktif serta saling mendukung proses promosi usaha satu sama lain.
          </p>
        </div>

        <!-- Bento Card 4: Wide Card -->
        <div class="bento-card bento-card-large reveal" style="transition-delay: 0.2s;">
          <div class="bento-icon-box bento-icon-primary" style="background: rgba(217, 70, 239, 0.08); color: #d946ef;">
            <i class="icon-base ti tabler-award"></i>
          </div>
          <h4 class="fw-bold mb-3" style="color: #0b0f19;">Sertifikat Kompetensi Resmi</h4>
          <p class="text-muted mb-4" style="line-height: 1.7; font-size: 0.95rem;">
            Setiap kelulusan program pelatihan dilengkapi dengan penerbitan sertifikat resmi yang membuktikan keikutsertaan dan keahlian Anda. Sertifikat ini diakui secara luas dan sangat berguna untuk memperkuat portofolio karier Anda, melamar pekerjaan, atau mendapatkan kepercayaan mitra bisnis.
          </p>
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-discount-check-filled text-primary fs-5"></i>
                <span class="fw-semibold small text-dark">Sertifikat Resmi {{ $institutionName }}</span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-discount-check-filled text-primary fs-5"></i>
                <span class="fw-semibold small text-dark">Penilaian Transparan & Kredibel</span>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- ============================================================
       FAQ SECTION (New Section for enriched UX)
       ============================================================ -->
  <section id="faq" class="py-8 py-lg-10" style="background: #f1f5f9; color: #1e293b;">
    <div class="container py-4">

      <!-- Section Title -->
      <div class="text-center mb-5 pb-3 reveal">
        <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 fw-semibold mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em; border-radius: 5px;">
          {{ __('PERTANYAAN UMUM') }}
        </span>
        <h2 class="fw-bold mb-3 display-6" style="color: #0b0f19; font-family: 'Sora', sans-serif;">{{ __('Pertanyaan yang Sering Diajukan') }}</h2>
        <p class="text-muted fs-5 mx-auto" style="max-width: 540px;">
          {{ __('Cari jawaban atas keraguan Anda mengenai pendaftaran dan metode pelatihan kami') }}
        </p>
      </div>

      <!-- Bootstrap Accordion Redesigned -->
      <div class="row justify-content-center reveal">
        <div class="col-lg-8">
          <div class="accordion" id="accordionFaq">
            @forelse($faqs as $index => $faq)
              <div class="faq-accordion-item border-0">
                <h3 class="accordion-header" id="heading{{ $index }}">
                  <button class="faq-accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                    <span class="flex-grow-1">{{ $faq->question }}</span>
                    <i class="icon-base ti tabler-chevron-down faq-chevron"></i>
                  </button>
                </h3>
                <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                  aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionFaq">
                  <div class="faq-accordion-body">
                    {{ $faq->answer }}
                  </div>
                </div>
              </div>
            @empty
              <div class="text-center py-4">
                <p class="text-muted">{{ __('Belum ada FAQ.') }}</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ============================================================
       HIGH-IMPACT FLOATING MESH CTA SECTION
       ============================================================ -->
  <section class="py-8 py-lg-10" style="background: #f8fafc;">
    <div class="container py-4">
      <div class="cta-mesh-container text-center text-white position-relative overflow-hidden reveal">

        <!-- Glowing background visual shapes inside container -->
        <div style="position:absolute; top:-25%; left:-10%; width:300px; height:300px; background:rgba(99,102,241,0.3); filter:blur(60px); border-radius:50%; pointer-events:none;"></div>
        <div style="position:absolute; bottom:-25%; right:-10%; width:300px; height:300px; background:rgba(192,132,252,0.25); filter:blur(60px); border-radius:50%; pointer-events:none;"></div>

        <div class="position-relative" style="z-index: 5;">
          <span class="badge bg-white bg-opacity-10 text-warning px-4 py-2 fw-semibold mb-4" style="font-size: 0.8rem; letter-spacing: 0.05em; border: 1px solid rgba(255,255,255,0.15); border-radius: 5px;">
            🚀 {{ $cta_badge }}
          </span>
          <h2 class="fw-bold text-white mb-3 display-5" style="font-family: 'Sora', sans-serif;">{{ $cta_title }}</h2>
          <p class="fs-5 mb-5 text-white-50 mx-auto" style="max-width: 580px;">
            {{ $cta_subtitle }}
          </p>
          <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="#beranda" class="btn btn-warning btn-lg px-5 fw-semibold btn-glow py-3" style="font-family: 'Sora', sans-serif; border-radius: 5px;">
              <i class="icon-base ti tabler-user-plus me-2"></i>{{ $cta_button_text }}
            </a>
            <a href="{{ route('login') }}" class="btn btn-glow-outline btn-lg px-5 fw-semibold py-3 d-flex align-items-center gap-2" style="border-radius: 5px;">
              <i class="icon-base ti tabler-login fs-5"></i>{{ $cta_login_text }}
            </a>
          </div>
        </div>

      </div>
    </div>
  </section>

  @include('partials.site-footer')

</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
  'use strict';

  // ============================================================
  // 1. DYNAMIC NAVIGATION ON SCROLL
  // ============================================================
  const navbar = document.querySelector('.navbar-glass-floating');
  if (navbar) {
    window.addEventListener('scroll', function() {
      if (window.scrollY > 40) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  }

  // ============================================================
  // 2. REVEAL ON SCROLL (Intersection Observer)
  // ============================================================
  const revealElements = document.querySelectorAll('.reveal');
  if (revealElements.length > 0) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    revealElements.forEach(el => revealObserver.observe(el));
  }

  // ============================================================
  // 3. NIK INPUT — Auto filter & Check via AJAX
  // ============================================================
  const nikInput = document.getElementById('nik');
  const nikFeedback = document.getElementById('nik-feedback');

  if (nikInput) {
    let nikTimeout = null;

    nikInput.addEventListener('input', function() {
      clearTimeout(nikTimeout);

      // Filter only digits
      const nik = this.value.replace(/\D/g, '');
      this.value = nik;

      if (nik.length < 15 || nik.length > 16) {
        nikFeedback.classList.add('d-none');
        nikFeedback.className = 'small mt-2 d-none';
        nikFeedback.textContent = '';
        return;
      }

      // Show checking loader indicator
      nikFeedback.className = 'small mt-2 d-flex align-items-center text-info';
      nikFeedback.innerHTML = '<div class="spinner-border spinner-border-xs me-2" style="width:12px;height:12px;border-width:2px;"></div> Memeriksa nomor NIK Anda...';
      nikFeedback.classList.remove('d-none');

      nikTimeout = setTimeout(function() {
        fetch('{{ route('landing.check-nik') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ nik: nik })
        })
        .then(res => res.json())
        .then(data => {
          nikFeedback.classList.remove('d-none');
          if (data.exists) {
            nikFeedback.className = 'small mt-2 d-flex align-items-center text-warning';
            nikFeedback.innerHTML = '<i class="icon-base ti tabler-alert-circle me-2 fs-5"></i> ' + data.message;
          } else {
            nikFeedback.className = 'small mt-2 d-flex align-items-center text-success';
            nikFeedback.innerHTML = '<i class="icon-base ti tabler-check-circle me-2 fs-5"></i> NIK tersedia untuk didaftarkan ✓';
            setTimeout(function() {
              nikFeedback.classList.add('d-none');
            }, 4000);
          }
        })
        .catch(function() {
          nikFeedback.className = 'small mt-2 d-flex align-items-center text-danger';
          nikFeedback.innerHTML = '<i class="icon-base ti tabler-cloud-off me-2 fs-5"></i> Koneksi bermasalah, gagal memeriksa NIK';
        });
      }, 500);
    });
  }

  // ============================================================
  // 4. WHATSAPP INPUT — Auto-convert & Check WA Registration
  // ============================================================
  const waInput = document.getElementById('whatsapp');
  const waFeedback = document.getElementById('wa-feedback');
  const waHint = document.getElementById('wa-format-hint');
  let waRegistered = null; // null = belum dicek, true = terdaftar, false = tidak terdaftar

  // Helper: convert 08xxx → 628xxx
  function convertWaNumber(num) {
    num = num.replace(/\D/g, ''); // strip non-digits
    if (num.startsWith('0')) {
      return '62' + num.substring(1);
    }
    if (num.startsWith('62') && num.length >= 10) {
      return num;
    }
    return '62' + num;
  }

  if (waInput) {
    let waTimeout = null;

    waInput.addEventListener('input', function() {
      clearTimeout(waTimeout);

      // Filter only digits
      const raw = this.value.replace(/\D/g, '');
      this.value = raw;

      // Show converted format hint
      if (raw.length >= 4) {
        const converted = convertWaNumber(raw);
        waHint.textContent = '→ ' + converted + ' (format internasional)';
      } else {
        waHint.textContent = 'Format: 08xx atau 628xx';
      }

      if (raw.length < 8) {
        waFeedback.classList.add('d-none');
        waFeedback.className = 'small mt-1 d-none';
        waFeedback.textContent = '';
        waRegistered = null;
        return;
      }

      // Show checking indicator
      waFeedback.className = 'small mt-1 d-flex align-items-center text-info';
      waFeedback.innerHTML = '<div class="spinner-border spinner-border-xs me-1" style="width:12px;height:12px;border-width:2px;"></div> Memeriksa nomor WhatsApp...';
      waFeedback.classList.remove('d-none');

      waTimeout = setTimeout(function() {
        const finalNumber = convertWaNumber(raw);
        fetch('{{ route('landing.check-wa') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ number: finalNumber })
        })
        .then(res => res.json())
        .then(data => {
          waFeedback.classList.remove('d-none');
          if (data.exists) {
            waFeedback.className = 'small mt-1 d-flex align-items-center text-success';
            waFeedback.innerHTML = '<i class="icon-base ti tabler-brand-whatsapp me-1"></i> Nomor WhatsApp terdaftar ✓';
            waRegistered = true;
          } else {
            waFeedback.className = 'small mt-1 d-flex align-items-center text-danger';
            waFeedback.innerHTML = '<i class="icon-base ti tabler-alert-triangle me-1"></i> Nomor WhatsApp tidak terdaftar. Pastikan nomor ini aktif di WA.';
            waRegistered = false;
          }
        })
        .catch(function() {
          waFeedback.className = 'small mt-1 d-flex align-items-center text-warning';
          waFeedback.innerHTML = '<i class="icon-base ti tabler-cloud-off me-1"></i> Gagal memverifikasi WA, tapi kamu tetap bisa daftar.';
          waRegistered = true; // allow submission if API fails
        });
      }, 600);
    });
  }

  // ============================================================
  // 5. FORM SUBMIT — Loading state spinner
  // ============================================================
  const form = document.getElementById('formRegistration');
  const btnDaftar = document.getElementById('btnDaftar');
  const btnText = document.getElementById('btn-text');
  const btnLoading = document.getElementById('btn-loading');

  if (form) {
    form.addEventListener('submit', function(e) {
      // BLOCK if WA number is confirmed NOT registered on WhatsApp
      if (waInput && waInput.value.replace(/\D/g, '').length >= 8 && waRegistered === false) {
        e.preventDefault();
        waInput.focus();
        waFeedback.className = 'small mt-1 d-flex align-items-center text-danger fw-semibold';
        waFeedback.innerHTML = '<i class="icon-base ti tabler-alert-triangle me-1"></i> Nomor WhatsApp tidak terdaftar. Daftar dengan nomor WhatsApp yang aktif.';
        waFeedback.classList.remove('d-none');
        return;
      }

      // Convert WA number to international format before submit
      if (waInput) {
        const waRaw = waInput.value.replace(/\D/g, '');
        if (waRaw.length > 0) {
          waInput.value = convertWaNumber(waRaw);
        }
      }

      // Basic empty fields verification check before setting loading state
      const requiredFields = form.querySelectorAll('[required]');
      let valid = true;
      requiredFields.forEach(f => {
        if (!f.value.trim()) valid = false;
      });

      if (valid) {
        btnDaftar.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        btnDaftar.innerHTML = '';
        btnDaftar.appendChild(btnLoading);
      }
    });
  }

  // ============================================================
  // 5. AUTO-HIDE SUCCESS ALERT
  // ============================================================
  const alertSukses = document.querySelector('.alert-success');
  if (alertSukses) {
    setTimeout(function() {
      alertSukses.classList.remove('show');
      alertSukses.classList.add('fade');
      setTimeout(function() {
        if (alertSukses.parentNode) alertSukses.remove();
      }, 300);
    }, 6000);
  }

  // ============================================================
  // 6. SMOOTH SCROLL FOR ALL ANCHOR LINKS
  // ============================================================
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        // Offset scroll for floating navbar
        const headerOffset = 90;
        const elementPosition = target.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    });
  });

  // ============================================================
  // 7. MOBILE SLIDE-IN MENU — Toggle, Overlay, Auto-close
  // ============================================================
  const mobileToggle = document.getElementById('mobileMenuToggle');
  const mobileClose = document.getElementById('mobileMenuClose');
  const mobileOverlay = document.getElementById('mobileOverlay');
  const mobilePanel = document.getElementById('mobileSlidePanel');
  const bodyEl = document.body;

  if (mobileToggle && mobilePanel && mobileOverlay) {
    // Buka menu
    function openMobileMenu() {
      mobileToggle.classList.add('active');
      mobilePanel.classList.add('active');
      mobileOverlay.classList.add('active');
      bodyEl.classList.add('mobile-menu-open');
      mobileToggle.setAttribute('aria-label', 'Tutup menu');
      // Prevent background scroll on touch devices
      mobileOverlay.style.touchAction = 'none';
    }

    // Tutup menu
    function closeMobileMenu() {
      mobileToggle.classList.remove('active');
      mobilePanel.classList.remove('active');
      mobileOverlay.classList.remove('active');
      bodyEl.classList.remove('mobile-menu-open');
      mobileToggle.setAttribute('aria-label', 'Buka menu');
      mobileOverlay.style.touchAction = '';
    }

    // Toggle via hamburger
    mobileToggle.addEventListener('click', function() {
      if (mobilePanel.classList.contains('active')) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });

    // Tutup via overlay klik
    mobileOverlay.addEventListener('click', closeMobileMenu);

    // Tutup via tombol close (X)
    if (mobileClose) {
      mobileClose.addEventListener('click', closeMobileMenu);
    }

    // Tutup via tombol Escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && mobilePanel.classList.contains('active')) {
        closeMobileMenu();
      }
    });

    // Tutup saat link di panel diklik
    // (smooth scroll tetap jalan oleh handler #6 di atas)
    mobilePanel.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', function() {
        // Beri jeda kecil agar scroll mulai dulu sebelum panel nutup
        setTimeout(closeMobileMenu, 120);
      });
    });

    // Tutup otomatis saat resize ke layar desktop
    window.addEventListener('resize', function() {
      if (window.innerWidth >= 992 && mobilePanel.classList.contains('active')) {
        closeMobileMenu();
      }
    });
  }
});
</script>
@endsection
