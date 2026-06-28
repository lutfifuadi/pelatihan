<?php
$configData = Helper::appClasses();

// Config lookup helpers
$fLabels = $fields->pluck('label', 'field_key');
$fPlaceholders = $fields->pluck('placeholder', 'field_key');
$fActive = $fields->where('is_active', true)->pluck('field_key')->toArray();
?>



<?php $__env->startSection('title', 'Form Minat Pelatihan'); ?>

<?php $__env->startSection('vendor-style'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/select2/select2.scss']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-style'); ?>
<style>


  /* ============================================================
     FULL-WIDTH LAYOUT — override template constraints
     ============================================================ */

  /* Neutralkan padding dari container-fluid layout + container-p-y */
  body .content-wrapper > .container-fluid,
  body .content-wrapper > .container-p-y {
    max-width: 100% !important;
    width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    --bs-gutter-x: 0 !important;
  }

  /* Layout-page: full available width */
  body .layout-page {
    max-width: 100% !important;
    width: 100% !important;
  }
  
  body .content-wrapper {
    max-width: 100% !important;
    padding: 0 !important;
  }

  /* Halaman container-fluid sendiri: minimalkan padding, full-width */
  body .container-fluid.px-1.px-lg-2 {
    width: 100% !important;
    max-width: 100% !important;
    padding-left: 0.5rem !important;
    padding-right: 0.5rem !important;
    --bs-gutter-x: 0 !important;
  }
  @media (min-width: 992px) {
    body .container-fluid.px-1.px-lg-2 {
      padding-left: 1rem !important;
      padding-right: 1rem !important;
    }
  }
  @media (min-width: 1400px) {
    body .container-fluid.px-1.px-lg-2 {
      padding-left: 1.5rem !important;
      padding-right: 1.5rem !important;
    }
  }

  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container {
    background-color: #0b0f19 !important;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }

  .layout-navbar-fixed .layout-page::before { display: none !important; }
  .content-wrapper > .container-xxl { max-width: 100% !important; padding: 0 !important; }

  .glass-card-dashboard {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
    border-radius: 5px;
    position: relative;
    z-index: 1;
    padding: 24px 20px;
  }
  @media (min-width: 992px) {
    .glass-card-dashboard {
      padding: 32px 28px;
    }
  }
  @media (min-width: 1400px) {
    .glass-card-dashboard {
      padding: 36px 32px;
    }
  }
  @media (max-width: 660px) {
    .glass-card-dashboard {
      padding: 16px 12px;
    }
  }

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
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }
  .form-control-custom::placeholder { color: rgba(255, 255, 255, 0.35) !important; }
  .form-control-custom.is-invalid { border-color: #f87171 !important; box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.2) !important; }
  .form-control-custom:disabled, .form-control-custom[readonly] { background: rgba(255, 255, 255, 0.02) !important; opacity: 0.6; }
  .form-control-uppercase { text-transform: uppercase !important; }

  textarea.form-control-custom { resize: vertical; min-height: 90px; }
  select.form-control-custom option { background: #1a1f2e; color: #f8fafc; }

  .form-label-custom {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 600 !important;
    font-size: 0.7rem !important;
    letter-spacing: 0.08em !important;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.7) !important;
    margin-bottom: 4px;
  }

  .btn-glow {
    position: relative; overflow: hidden; transition: all 0.3s ease; border: none;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    color: #0b0f19 !important;
  }
  .btn-glow:hover {
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 10px 30px rgba(255, 152, 0, 0.5);
    background: linear-gradient(135deg, #ffca28, #ffa726);
  }
  .btn-glow-outline {
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: rgba(255, 255, 255, 0.8) !important;
    transition: all 0.3s ease;
  }
  .btn-glow-outline:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.3);
    color: #ffffff !important;
  }

  .form-check-input-custom {
    background-color: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
  }
  .form-check-input-custom:checked { background-color: #6366f1 !important; border-color: #6366f1 !important; }
  .form-check-input-custom[type="radio"]:checked {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='%23fff'/%3e%3c/svg%3e") !important;
  }
  .text-white-50-custom { color: rgba(255, 255, 255, 0.5) !important; }
  .text-white-70-custom { color: rgba(255, 255, 255, 0.7) !important; }

  .field-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  @media (min-width: 1200px) {
    .field-group {
      gap: 24px;
    }
  }
  @media (max-width: 660px) {
    .field-group { grid-template-columns: 1fr; gap: 12px; }
  }
  .field-full { grid-column: 1 / -1; }

  /* ============================================================
     LAYOUT ENHANCEMENTS for Full-Width PC
     ============================================================ */

  /* Step indicator: pastikan mengambil lebar penuh */
  .step-indicator {
    width: 100%;
  }

  /* Navigation buttons: spacing lebih lega di PC */
  @media (min-width: 992px) {
    .form-actions-nav {
      margin-top: 2rem !important;
      padding-top: 0.5rem;
    }
  }

  /* Form section title area: lebih lega */
  @media (min-width: 1200px) {
    .form-section-title {
      font-size: 1.05rem !important;
      margin-bottom: 1.25rem !important;
    }
  }

  .tab-pane-step { animation: fadeSlideIn 0.35s ease forwards; }
  @keyframes fadeSlideIn {
    0% { opacity: 0; transform: translateY(12px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  .invalid-feedback-custom { color: #f87171; font-size: 11px; margin-top: 3px; display: none; }
  .invalid-feedback-custom.d-block { display: block; }

  .checkbox-group label { font-size: 13px; }

  /* ============================================================
     REDESIGNED CARDS — Premium Package Style (Step 4)
     Minimalis Elegan — seperti milih paket liburan
     ============================================================ */

  .grid-cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 20px;
  }
  
  @media (max-width: 768px) {
    .grid-cards-container {
      display: flex;
      flex-wrap: nowrap;
      overflow-x: auto;
      gap: 16px;
      padding-bottom: 10px;
      padding-right: 1rem;
      scroll-snap-type: x mandatory;
      scrollbar-width: none;
      -ms-overflow-style: none;
      cursor: grab;
      user-select: none;
      -webkit-user-select: none;
    }
    .grid-cards-container::-webkit-scrollbar {
      display: none;
    }
    .grid-cards-container.is-dragging {
      cursor: grabbing;
      scroll-snap-type: none;
    }
    .grid-cards-container.is-dragging .training-card {
      pointer-events: none;
    }
    .training-card {
      flex: 0 0 85vw;
      scroll-snap-align: start;
    }
  }

  @media (min-width: 1400px) {
    .grid-cards-container {
      grid-template-columns: repeat(auto-fill, minmax(420px, 1fr));
      gap: 24px;
    }
  }
  @media (min-width: 1800px) {
    .grid-cards-container {
      grid-template-columns: repeat(auto-fill, minmax(480px, 1fr));
      gap: 28px;
    }
  }

  /* Empty State — Vertical + Horizontal Centering */
  .empty-state-container {
    min-height: 45vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
  }

  @media (min-width: 992px) {
    .empty-state-container {
      min-height: 50vh;
      padding: 2rem 0;
    }
  }

  @media (max-width: 660px) {
    .empty-state-container {
      min-height: 35vh;
      padding: 1.5rem 0;
    }
  }

  /* Pastikan grid-cards-container punya ruang yang cukup secara vertikal */
  .grid-cards-container.has-cards {
    min-height: 200px;
  }

  /* --- Base Card --- */
  .training-card {
    position: relative;
    background: linear-gradient(145deg, rgba(255,255,255,0.04) 0%, rgba(255,255,255,0.01) 100%);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 16px;
    padding: 24px 22px 20px;
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    gap: 14px;
    overflow: hidden;
    animation: cardFadeUp 0.5s ease forwards;
    animation-delay: var(--card-delay, 0ms);
    opacity: 0;
  }
  @media (min-width: 1200px) {
    .training-card {
      padding: 28px 26px 24px;
      gap: 16px;
    }
  }
  @media (min-width: 1600px) {
    .training-card {
      padding: 32px 30px 26px;
      gap: 18px;
    }
  }
  @keyframes cardFadeUp {
    0%   { opacity: 0; transform: translateY(24px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  .training-card:hover:not(.disabled) {
    transform: translateY(-6px);
    border-color: rgba(99, 102, 241, 0.3);
    background: linear-gradient(145deg, rgba(255,255,255,0.07) 0%, rgba(255,255,255,0.02) 100%);
    box-shadow:
      0 24px 48px -16px rgba(0,0,0,0.5),
      0 0 40px rgba(99,102,241,0.06);
  }

  .training-card.active {
    border-color: #6366f1;
    background: linear-gradient(145deg, rgba(99,102,241,0.13) 0%, rgba(139,92,246,0.06) 100%);
    box-shadow:
      0 0 0 1px #6366f1,
      0 24px 48px -16px rgba(99,102,241,0.15),
      inset 0 1px 0 rgba(255,255,255,0.06);
  }

  .training-card.disabled {
    opacity: 0.45;
    cursor: not-allowed;
    border-color: rgba(239, 68, 68, 0.12);
  }

  /* --- Top Row: Badge + Radio --- */
  .card-top-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .badge-batch {
    font-family: 'Outfit', sans-serif;
    font-size: 10.5px !important;
    font-weight: 700 !important;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 5px 12px !important;
    border-radius: 8px !important;
    background: linear-gradient(135deg, rgba(99,102,241,0.18), rgba(139,92,246,0.12)) !important;
    color: #a5b4fc !important;
    border: 1px solid rgba(99,102,241,0.12);
  }
  .training-card.active .badge-batch {
    background: linear-gradient(135deg, rgba(99,102,241,0.3), rgba(139,92,246,0.2)) !important;
    color: #c7d2fe !important;
    border-color: rgba(99,102,241,0.25);
  }
  .training-card.disabled .badge-batch {
    background: rgba(239, 68, 68, 0.1) !important;
    color: #fca5a5 !important;
    border-color: rgba(239, 68, 68, 0.12);
  }

  /* --- Radio Indicator (lingkaran yang terisi saat aktif) --- */
  .card-radio-indicator {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    flex-shrink: 0;
  }
  .training-card.active .card-radio-indicator {
    border-color: #6366f1;
    background: #6366f1;
    box-shadow: 0 0 14px rgba(99,102,241,0.35);
  }
  .card-radio-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ffffff;
    transform: scale(0);
    transition: transform 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  .training-card.active .card-radio-dot {
    transform: scale(1);
  }

  /* --- Card Title --- */
  .card-title {
    font-family: 'Sora', sans-serif !important;
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    line-height: 1.4 !important;
    color: #ffffff !important;
    margin: 0 !important;
    padding-right: 8px;
  }
  .training-card.active .card-title {
    color: #e0e7ff !important;
  }

  /* --- Info Section --- */
  .card-info-section {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .card-info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px !important;
    color: rgba(255,255,255,0.6) !important;
    line-height: 1.35;
  }
  .card-info-item i {
    font-size: 15px !important;
    color: rgba(255,255,255,0.25) !important;
    width: 18px;
    text-align: center;
    flex-shrink: 0;
    transition: color 0.3s ease;
  }
  .card-info-item span {
    transition: color 0.3s ease;
  }
  .training-card.active .card-info-item {
    color: rgba(255,255,255,0.8) !important;
  }
  .training-card.active .card-info-item i {
    color: #a5b4fc !important;
  }

  /* --- CTA Button (Gradient Warna-warni) --- */
  .card-cta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
    font-size: 0.82rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 45%, #d946ef 100%);
    color: #ffffff;
    box-shadow: 0 4px 18px rgba(99,102,241,0.25);
    position: relative;
    overflow: hidden;
    margin-top: 2px;
  }
  .card-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 45%, #db2777 100%);
    opacity: 0;
    transition: opacity 0.35s ease;
    border-radius: 10px;
  }
  .card-cta:hover::before {
    opacity: 1;
  }
  .card-cta span,
  .card-cta i {
    position: relative;
    z-index: 1;
  }
  .card-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(99,102,241,0.35);
  }
  .card-cta:active {
    transform: translateY(0) scale(0.98);
  }

  .training-card.active .card-cta {
    background: linear-gradient(135deg, #4f46e5, #7c3aed, #db2777);
    box-shadow: 0 4px 24px rgba(99,102,241,0.3);
  }
  .training-card.active .card-cta::before {
    opacity: 1;
  }

  /* Disabled card → hide CTA */
  .training-card.disabled .card-cta {
    display: none;
  }

  /* --- Restricted Warning --- */
  .restricted-warning-box {
    padding: 10px 12px;
    background: rgba(239, 68, 68, 0.08);
    border: 1px solid rgba(239, 68, 68, 0.15);
    border-radius: 10px;
    font-size: 11.5px !important;
    color: #fca5a5 !important;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.5;
  }
  .restricted-warning-box i {
    margin-top: 2px;
    font-size: 14px;
    flex-shrink: 0;
  }

  /* --- Overlay & Watermark Ditutup --- */
  .watermark-overlay-card {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    pointer-events: none;
    z-index: 2;
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
  }
  .watermark-text-card {
    transform: rotate(-18deg);
    font-size: 1.5rem;
    font-weight: 800;
    color: rgba(239, 68, 68, 0.6);
    border: 3px solid rgba(239, 68, 68, 0.35);
    padding: 6px 20px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    font-family: 'Sora', sans-serif;
    border-radius: 6px;
  }

  /* --- Hidden Radio (for form fallback) --- */
  .sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
  }

  /* ============================================================
     POPUP DITUTUP STYLES
     ============================================================ */
  .popup-ditutup-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .popup-ditutup-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
  }
  .popup-ditutup-card {
    position: relative;
    width: 100%;
    max-width: 440px;
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 40px 32px 28px;
    text-align: center;
    z-index: 10;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
  }
  .popup-ditutup-close {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.05);
    color: rgba(255, 255, 255, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 1.1rem;
  }
  .popup-ditutup-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
  }
  .popup-ditutup-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
    color: #ef4444;
  }
  .popup-ditutup-title {
    font-family: 'Sora', sans-serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 12px;
  }
  .popup-ditutup-message {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.75);
    line-height: 1.6;
    margin-bottom: 6px;
  }
  .popup-ditutup-message strong {
    color: #ffffff;
  }
  .popup-ditutup-submessage {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 24px;
  }
  .popup-ditutup-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .popup-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 24px;
    border-radius: 8px;
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.25s ease;
    cursor: pointer;
    border: none;
  }
  .popup-btn-primary {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
  }
  .popup-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    color: #ffffff;
  }
  .popup-btn-secondary {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.7);
  }
  .popup-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
  }

  /* --- Popup Transitions --- */
  .popup-fade-enter { transition: opacity 0.25s ease; }
  .popup-fade-enter-start { opacity: 0; }
  .popup-fade-enter-end { opacity: 1; }
  .popup-fade-leave { transition: opacity 0.2s ease; }
  .popup-fade-leave-start { opacity: 1; }
  .popup-fade-leave-end { opacity: 0; }

  .popup-scale-enter { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
  .popup-scale-enter-start { opacity: 0; transform: scale(0.9) translateY(20px); }
  .popup-scale-enter-end { opacity: 1; transform: scale(1) translateY(0); }
  .popup-scale-leave { transition: all 0.2s ease; }
  .popup-scale-leave-start { opacity: 1; transform: scale(1) translateY(0); }
  .popup-scale-leave-end { opacity: 0; transform: scale(0.95) translateY(10px); }

  @media (max-width: 767.98px) {
    .popup-ditutup-card {
      padding: 32px 20px 24px;
      margin: 10px;
    }
    .popup-ditutup-icon {
      width: 60px;
      height: 60px;
      font-size: 1.6rem;
    }
  }

  /* Mode Grid di Mobile — override horizontal scroll */
  @media (max-width: 768px) {
    .grid-cards-container.view-grid {
      display: flex;
      flex-direction: column;
      flex-wrap: wrap;
      overflow-x: visible;
      gap: 16px;
      padding-bottom: 0;
      scroll-snap-type: none;
    }

    .grid-cards-container.view-grid .training-card {
      flex: 0 0 100%;
      width: 100%;
      scroll-snap-align: none;
    }
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="glow-orb-wrapper" aria-hidden="true">
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>
</div>

<div class="container-fluid px-1 px-lg-2 position-relative" style="z-index: 1;">
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
  <div class="alert alert-danger alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 5px;">
    <div class="d-flex align-items-center">
      <i class="icon-base ti tabler-alert-circle fs-5 me-2"></i>
      <span><?php echo e(session('error')); ?></span>
    </div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="glass-card-dashboard mb-4">
    <div class="d-flex align-items-center gap-3">
      <div style="width: 48px; height: 48px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);">
        <i class="icon-base ti tabler-heart text-white fs-4"></i>
      </div>
      <div>
        <h4 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Form Minat Pelatihan</h4>
        <p class="text-white-50-custom mb-0 small">Pilih bidang minat dan preferensi pelatihan Anda</p>
      </div>
    </div>
  </div>

  <?php
    $profile = \App\Models\PesertaProfile::where('user_id', auth()->id())->first();
    $step1Done = $profile && !empty($profile->nama_lengkap) && !empty($profile->nik);
    $step2Done = $profile && !empty($profile->alamat_ktp) && !empty($profile->whatsapp);
    $step3Done = $profile && !empty($profile->pendidikan_terakhir) && !empty($profile->nama_institusi);
    $step4Done = $profile && !empty($profile->pelatihan_id);
    $step5Done = $profile && !empty($profile->jawaban_pertanyaan);
  ?>

  <!-- Step Indicator: 6 Steps -->
  <div class="step-indicator mb-4">
    <div class="step-progress-line" style="transform: scaleX(0.6); transform-origin: left;"></div>
    
    <!-- Step 1: Data Diri -->
    <div class="step-item <?php echo e($step1Done ? 'completed' : ''); ?>" <?php if($step1Done): ?> onclick="window.location.href='<?php echo e(route('dashboard.peserta.form-pendaftaran')); ?>'" style="cursor: pointer;" <?php endif; ?>>
      <div class="step-circle">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($step1Done): ?>
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        <?php else: ?>
          1
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
      <div class="step-label">Data Diri</div>
    </div>
    
    <!-- Step 2: Alamat -->
    <div class="step-item <?php echo e($step2Done ? 'completed' : ''); ?>" <?php if($step2Done): ?> onclick="window.location.href='<?php echo e(route('dashboard.peserta.form-alamat')); ?>'" style="cursor: pointer;" <?php endif; ?>>
      <div class="step-circle">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($step2Done): ?>
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        <?php else: ?>
          2
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
      <div class="step-label">Alamat</div>
    </div>
    
    <!-- Step 3: Pendidikan -->
    <div class="step-item <?php echo e($step3Done ? 'completed' : ''); ?>" <?php if($step3Done): ?> onclick="window.location.href='<?php echo e(route('dashboard.peserta.form-pendidikan')); ?>'" style="cursor: pointer;" <?php endif; ?>>
      <div class="step-circle">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($step3Done): ?>
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        <?php else: ?>
          3
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
      <div class="step-label">Pendidikan</div>
    </div>
    
    <!-- Step 4: Pelatihan (active) -->
    <div class="step-item active">
      <div class="step-circle">4</div>
      <div class="step-label">Pilihan Pelatihan</div>
    </div>
    
    <!-- Step 5: Dokumen -->
    <div class="step-item <?php echo e($step5Done ? 'completed' : ''); ?>" <?php if($step5Done): ?> onclick="window.location.href='<?php echo e(route('dashboard.peserta.form-dokumen')); ?>'" style="cursor: pointer;" <?php endif; ?>>
      <div class="step-circle">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($step5Done): ?>
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        <?php else: ?>
          5
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
      <div class="step-label">Dokumen</div>
    </div>
    
    <!-- Step 6: Review -->
    <div class="step-item">
      <div class="step-circle">6</div>
      <div class="step-label">Review</div>
    </div>
  </div>

  <div class="glass-card-dashboard" x-data="minatForm()" x-cloak>
    <form id="formMinat" action="<?php echo e(route('dashboard.peserta.form-minat.store')); ?>" method="POST">
      <?php echo csrf_field(); ?>

      <div class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3 form-section-title">
          <i class="icon-base ti tabler-heart me-2" style="color: #6366f1;"></i><?php echo e($fLabels['section_title'] ?? 'MINAT PELATIHAN'); ?>

        </h5>

        <div class="field-group">
          <div class="field-full">
            <div x-show="batchList.length > 0">
              <label class="form-label form-label-custom">
                <?php echo e($fLabels['batch_pelatihan'] ?? 'PILIH PELATIHAN (BATCH) YANG ANDA MINATI'); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fields->where('field_key', 'batch_pelatihan')->first()?->is_required): ?> <span class="text-danger">*</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              </label>
              <p class="text-white-50-custom small mb-3" style="font-size: 12px; font-style: italic;">
                <?php echo e($fPlaceholders['batch_pelatihan'] ?? 'PELAKSANAAN BATCH SELANJUTNYA AKAN DI INFORMASIKAN LEWAT EMAIL ATAU WA TERDAFTAR'); ?>

              </p>
            </div>
            <!-- Cards Container -->
            <div>
              <div
                class="grid-cards-container mt-1 <?php echo e(($mobileViewMode ?? 'horizontal') === 'grid' ? 'view-grid' : ''); ?>"
                :class="{ 'has-cards': batchList.length > 0, 'is-dragging': isDragging }"
                x-ref="dragContainer"
                @mousedown="startDrag($event)"
                @mousemove="onDrag($event)"
                @mouseup="endDrag"
                @mouseleave="endDrag"
                @touchstart="startDrag($event)"
                @touchmove="onDrag($event)"
                @touchend="endDrag"
              >
                <template x-for="(batch, index) in batchList" :key="index">
                  <div 
                    class="training-card"
                    :style="`--card-delay: ${index * 80}ms`"
                    :class="{ 
                      'active': form.batch_pelatihan === batch.value.toString() || form.batch_pelatihan === batch.value, 
                      'disabled': batch.restricted || batch.ditutup 
                    }"
                    @click="handleCardClick(batch)"
                  >
                    <!-- Watermark DITUTUP -->
                    <template x-if="batch.ditutup">
                      <div class="watermark-overlay-card">
                        <span class="watermark-text-card">DITUTUP</span>
                      </div>
                    </template>

                    <!-- Hidden Radio for standard form submit -->
                    <input 
                      type="radio" 
                      name="batch_pelatihan" 
                      :value="batch.value" 
                      :checked="form.batch_pelatihan === batch.value.toString() || form.batch_pelatihan === batch.value" 
                      :disabled="batch.restricted || batch.ditutup" 
                      class="sr-only"
                    />

                    <!-- Top Row: Badge Batch + Radio Indicator -->
                    <div class="card-top-row">
                      <span class="badge-batch" x-text="'Batch ' + (batch.value.toUpperCase().startsWith('BATCH ') ? batch.value.substring(6) : batch.value)"></span>
                      <div class="card-radio-indicator">
                        <div class="card-radio-dot"></div>
                      </div>
                    </div>

                    <!-- Nama Pelatihan -->
                    <h5 class="card-title" x-text="batch.label.split(' : ')[1] ? batch.label.split(' : ')[1].split(' (')[0] : batch.label"></h5>

                    <!-- Info: Dinas, Tanggal, Lokasi -->
                    <div class="card-info-section">
                      <div class="card-info-item">
                        <i class="icon-base ti tabler-building"></i>
                        <span x-text="batch.dinas_name"></span>
                      </div>
                      <div class="card-info-item">
                        <i class="icon-base ti tabler-calendar"></i>
                        <span x-text="batch.label.includes('(') ? batch.label.substring(batch.label.indexOf('(') + 1, batch.label.lastIndexOf(')')) : 'COMING SOON'"></span>
                      </div>
                      <div class="card-info-item">
                        <i class="icon-base ti tabler-map-pin"></i>
                        <span x-text="batch.kecamatans && batch.kecamatans.length > 0 ? 'Khusus: ' + batch.kecamatans.join(', ') : 'Untuk semua kecamatan'"></span>
                      </div>
                    </div>

                    <!-- CTA Button: Gradient Warna-warni -->
                    <button type="button" class="card-cta" @click.stop="handleCardClick(batch)">
                      <template x-if="form.batch_pelatihan === batch.value.toString() || form.batch_pelatihan === batch.value">
                        <i class="icon-base ti tabler-check" style="font-size: 15px;"></i>
                      </template>
                      <template x-if="!(form.batch_pelatihan === batch.value.toString() || form.batch_pelatihan === batch.value)">
                        <i class="icon-base ti tabler-plus" style="font-size: 15px;"></i>
                      </template>
                      <span x-text="form.batch_pelatihan === batch.value.toString() || form.batch_pelatihan === batch.value ? 'Terpilih' : 'Pilih Ini'"></span>
                      <template x-if="!(form.batch_pelatihan === batch.value.toString() || form.batch_pelatihan === batch.value)">
                        <i class="icon-base ti tabler-arrow-right" style="font-size: 14px;"></i>
                      </template>
                    </button>

                    <!-- Restricted Warning -->
                    <template x-if="batch.restricted">
                      <div class="restricted-warning-box">
                        <i class="icon-base ti tabler-alert-triangle"></i>
                        <span>
                          Sudah pernah mengikuti pelatihan di <strong x-text="batch.restricted_dinas"></strong>. Tersedia setelah <strong x-text="batch.restricted_until"></strong>
                        </span>
                      </div>
                    </template>

                    <!-- Ditutup Info -->
                    <template x-if="batch.ditutup">
                      <div class="restricted-warning-box" style="border-color: rgba(239, 68, 68, 0.25);">
                        <i class="icon-base ti tabler-ban"></i>
                        <span>Pendaftaran ditutup pada <strong x-text="batch.batas_ditutup"></strong></span>
                      </div>
                    </template>
                  </div>
                </template>
              </div>
            </div>

              <!-- Empty State: Tidak ada pelatihan di wilayah user -->
              <template x-if="batchList.length === 0">
                <div class="empty-state-container text-center py-5 px-3">
                  <!-- Icon / Ilustrasi -->
                  <div class="mb-4" style="font-size: 4rem; line-height: 1;">
                    🗺️
                  </div>

                  <h5 class="fw-bold text-white mb-2" style="font-family: 'Sora', sans-serif;">
                    Belum Ada Pelatihan untuk Wilayah Anda
                  </h5>

                  <p class="text-body-premium mb-3 px-4" style="max-width: 600px; margin: 0 auto; font-size: 0.95rem; line-height: 1.7;">
                    Hai <span class="fw-semibold text-white"><?php echo e(auth()->user()->name); ?></span>, 
                    saat ini belum ada pelatihan yang dibuka khusus untuk wilayah
                  </p>

                  <!-- Location Badge -->
                  <div class="d-inline-flex align-items-center gap-2 px-3 py-2 mb-3" 
                       style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); border-radius: 5px;">
                    <i class="icon-base ti tabler-map-pin" style="color: #818cf8;"></i>
                    <span class="fw-semibold" style="color: #e2e8f0; font-size: 0.9rem;">
                      <span x-text="userLocation?.kecamatan || 'Wilayah Anda'"></span>
                    </span>
                  </div>

                  <!-- Alternative Recommendations (if any) -->
                  <template x-if="alternativePelatihans && alternativePelatihans.length > 0">
                    <div class="text-start mx-auto mb-4 p-3" 
                         style="max-width: 580px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 5px;">
                      <p class="fw-semibold text-white mb-2" style="font-size: 0.85rem;">
                        🗺️ Pelatihan di Kecamatan Terdekat
                      </p>
                      <template x-for="(alt, idx) in alternativePelatihans" :key="idx">
                        <div class="d-flex align-items-start gap-2 mb-2" style="font-size: 0.85rem;">
                          <span style="color: #818cf8;">•</span>
                          <div>
                            <span class="text-white fw-semibold" x-text="alt.nama"></span>
                            <span class="text-body-premium"> — Batch <span x-text="alt.batch"></span></span>
                            <br>
                            <span class="text-body-premium" style="font-size: 0.8rem;">
                              <span x-text="alt.dinas_name"></span> — 
                              <span x-text="alt.kecamatans?.join(', ') || ''"></span>
                            </span>
                          </div>
                        </div>
                      </template>
                    </div>
                  </template>

                  <!-- Info text when no alternatives -->
                  <template x-if="!alternativePelatihans || alternativePelatihans.length === 0">
                    <p class="text-body-premium mb-4 px-4" style="max-width: 580px; margin: 0 auto; font-size: 0.9rem; font-style: italic;">
                      Pantau terus halaman ini untuk informasi pelatihan terbaru di wilayah Anda.
                    </p>
                  </template>

                  <!-- CTA Buttons -->
                  <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                    <a :href="'https://wa.me/' + adminWa + '?text=' + encodeURIComponent('Halo admin, saya ' + '<?php echo e(auth()->user()->name); ?>' + ' dari ' + (userLocation?.kecamatan || '') + '. Apakah ada informasi pelatihan terbaru untuk wilayah saya?')"
                       target="_blank"
                       class="btn btn-success d-inline-flex align-items-center gap-2 px-4 py-2"
                       style="border-radius: 5px; font-weight: 600; font-size: 0.9rem;">
                      <i class="icon-base ti tabler-brand-whatsapp" style="font-size: 1.2rem;"></i>
                      Hubungi Admin via WhatsApp
                    </a>
                  </div>
                </div>
              </template>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.batch_pelatihan }" x-text="errors.batch_pelatihan"></div>
          </div>
        </div>


      </div>

      <div x-data="popupDitutupForm()" @keydown.window="handleKeydown($event)">
        <?php if (isset($component)) { $__componentOriginal434d5b2ae69b3cc42f22ab6b937eee83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal434d5b2ae69b3cc42f22ab6b937eee83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.popup-ditutup','data' => ['namaPelatihan' => '','batch' => '','tanggalDitutup' => '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('popup-ditutup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['namaPelatihan' => '','batch' => '','tanggalDitutup' => '']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal434d5b2ae69b3cc42f22ab6b937eee83)): ?>
<?php $attributes = $__attributesOriginal434d5b2ae69b3cc42f22ab6b937eee83; ?>
<?php unset($__attributesOriginal434d5b2ae69b3cc42f22ab6b937eee83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal434d5b2ae69b3cc42f22ab6b937eee83)): ?>
<?php $component = $__componentOriginal434d5b2ae69b3cc42f22ab6b937eee83; ?>
<?php unset($__componentOriginal434d5b2ae69b3cc42f22ab6b937eee83); ?>
<?php endif; ?>
      </div>

      <div class="d-flex justify-content-between mt-4 form-actions-nav">
        <a href="<?php echo e(route('dashboard.peserta.form-pendidikan')); ?>" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;">
          <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
        </a>
        <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="submitForm()" :disabled="saving">
          <span x-show="!saving">Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i></span>
          <span x-show="saving"><i class="icon-base ti tabler-loader animate-spin me-1"></i> Menyimpan...</span>
        </button>
      </div>

    </form>
  </div>
</div>

<style>
  [x-cloak] { display: none !important; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/select2/select2.js']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
  // Data dari server untuk diisi otomatis ke form (Pola PMBM)
  window._formData = <?php echo json_encode($data); ?>;

  document.addEventListener('alpine:init', function() {
    Alpine.data('popupDitutupForm', function() {
      return {
        show: false,
        popupNama: '',
        popupBatch: '',
        popupTanggal: '',
        init() {
          this.$watch('show', val => {
            document.body.style.overflow = val ? 'hidden' : '';
          });
          window.addEventListener('open-popup-ditutup', (e) => {
            this.popupNama = e.detail.nama;
            this.popupBatch = e.detail.batch || '';
            this.popupTanggal = e.detail.tanggal;
            this.show = true;
            this.$nextTick(() => {
              const dialog = this.$refs.popupDialog;
              if (dialog) {
                const focusable = dialog.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (focusable.length) focusable[0].focus();
              }
            });
          });
        },
        close() {
          this.show = false;
        },
        handleKeydown(e) {
          if (e.key === 'Escape' && this.show) {
            this.close();
          }
          if (e.key === 'Tab' && this.show) {
            const dialog = this.$refs.popupDialog;
            if (!dialog) return;
            const focusable = dialog.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (!focusable.length) return;
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (e.shiftKey && document.activeElement === first) {
              e.preventDefault();
              last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
              e.preventDefault();
              first.focus();
            }
          }
        }
      }
    });

    Alpine.data('minatForm', function() {
      var fd = window._formData || {};
      return {
        saving: false,
        batchList: <?php echo json_encode($batchList ?? [], 15, 512) ?>,
        // --- NEW: Empty State Data ---
        userLocation: <?php echo json_encode($userLocation, 15, 512) ?>,
        alternativePelatihans: <?php echo json_encode($alternativePelatihans ?? [], 15, 512) ?>,
        adminWa: <?php echo json_encode($adminWa ?? '6285212345678', 15, 512) ?>,
        // --- Drag-to-scroll state ---
        isDragging: false,
        startX: 0,
        scrollLeft: 0,
        moved: false,
        dragThreshold: 5,
        // --- View mode from admin setting ---
        mode: '<?php echo e($mobileViewMode ?? 'horizontal'); ?>',
        form: {
          batch_pelatihan: fd.batch_pelatihan || '',
        },
        errors: {},

        clearErrors() { this.errors = {}; },

        startDrag(e) {
          if (this.mode === 'grid') return;
          const container = this.$refs.dragContainer;
          const pageX = e.touches ? e.touches[0].pageX : e.pageX;
          this.isDragging = true;
          this.moved = false;
          this.startX = pageX - container.offsetLeft;
          this.scrollLeft = container.scrollLeft;
        },
        onDrag(e) {
          if (!this.isDragging) return;
          e.preventDefault();
          const container = this.$refs.dragContainer;
          const pageX = e.touches ? e.touches[0].pageX : e.pageX;
          const x = pageX - container.offsetLeft;
          const walk = (x - this.startX) * 1.5;
          if (Math.abs(walk) > this.dragThreshold) this.moved = true;
          container.scrollLeft = this.scrollLeft - walk;
        },
        endDrag() {
          this.isDragging = false;
          // Reset moved setelah siklus click selesai agar klik berikutnya tetap berfungsi
          setTimeout(() => { this.moved = false; }, 50);
        },
        handleCardClick(batch) {
          if (this.isDragging || this.moved) return;
          if (batch.ditutup) {
            window.dispatchEvent(new CustomEvent('open-popup-ditutup', { detail: { nama: batch.label, batch: batch.value, tanggal: batch.batas_ditutup || '-' } }));
          } else if (!batch.restricted) {
            this.form.batch_pelatihan = batch.value.toString();
          }
        },

        validate() {
          this.clearErrors();
          var errs = {};
          var valid = true;

          if (!this.form.batch_pelatihan) { errs.batch_pelatihan = 'PILIH MINIMAL 1 BATCH PELATIHAN'; valid = false; }

          this.errors = errs;
          return valid;
        },

        submitForm() {
          if (!this.validate()) return;
          this.saving = true;
          document.getElementById('formMinat').submit();
        },
      };
    });
  });
</script>
<?php $__env->stopSection(); ?>







<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/dashboard/peserta/form-minat.blade.php ENDPATH**/ ?>