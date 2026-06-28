<?php
$configData = Helper::appClasses();
?>



<?php $__env->startSection('title', 'Dashboard Peserta'); ?>

<?php $__env->startSection('page-style'); ?>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  .content-wrapper {
    font-family: 'Outfit', sans-serif;
    color: #f8fafc;
    position: relative !important;
    overflow: hidden !important;
  }
  .content-wrapper h1,
  .content-wrapper h2,
  .content-wrapper h3,
  .content-wrapper h4,
  .content-wrapper h5,
  .content-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }

  /* --- LAYOUT OVERRIDES FOR LANDING PAGE ALIGNMENT --- */
  /* Main layouts backgrounds */
  html,
  body,
  .layout-page,
  .content-wrapper,
  .layout-wrapper,
  .layout-container {
    background-color: #0b0f19 !important;
    background-image: 
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }

  /* Remove top layout blur/gradient bar that clashes with the dark premium theme */
  .layout-navbar-fixed .layout-page::before {
    display: none !important;
  }

  /* Override outer container-xxl to span edge-to-edge and remove default padding */
  .content-wrapper > .container-xxl {
    max-width: 100% !important;
    padding: 0 !important;
  }

  /* Sidebar styling */
  .layout-menu,
  #layout-menu {
    background-color: #0b0f19 !important;
    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
  }
  .layout-menu .app-brand {
    background-color: #0b0f19 !important;
  }
  .layout-menu .menu-inner {
    background-color: #0b0f19 !important;
  }
  .layout-menu .menu-link {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  .layout-menu .menu-item.active > .menu-link {
    color: #ffffff !important;
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3) !important;
  }
  .layout-menu .menu-item.active > .menu-link i {
    color: #ffffff !important;
  }
  .layout-menu .menu-header-text {
    color: rgba(255, 255, 255, 0.4) !important;
  }
  .layout-menu .menu-link:hover {
    background-color: rgba(255, 255, 255, 0.04) !important;
    color: #ffffff !important;
  }
  .layout-menu .menu-inner-shadow {
    background: linear-gradient(#0b0f19 5%, rgba(11, 15, 25, 0) 95%) !important;
  }
  .layout-menu .app-brand .app-brand-text {
    color: #ffffff !important;
  }
  .layout-menu .layout-menu-toggle i {
    color: rgba(255, 255, 255, 0.6) !important;
  }

  /* Top Navbar styling */
  .layout-navbar,
  #layout-navbar {
    background: rgba(15, 23, 42, 0.45) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
  }
  .navbar-detached {
    background: rgba(15, 23, 42, 0.45) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    margin-top: 12px !important;
  }
  #layout-navbar .nav-link {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  #layout-navbar .nav-link:hover {
    color: #ffffff !important;
  }
  #layout-navbar .dropdown-menu {
    background-color: #0b0f19 !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
  }
  #layout-navbar .dropdown-item {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  #layout-navbar .dropdown-item:hover {
    background-color: rgba(255, 255, 255, 0.04) !important;
    color: #ffffff !important;
  }
  #layout-navbar .dropdown-divider {
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  #layout-navbar .text-body-secondary {
    color: rgba(255, 255, 255, 0.5) !important;
  }
  #layout-navbar h6 {
    color: #ffffff !important;
  }

  /* Dynamic Floating Orbs */
  .glow-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.4;
    mix-blend-mode: screen;
    pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out;
    z-index: 0;
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

  .bg-dark-premium {
    background-color: #0b0f19 !important;
  }
  .text-body-premium {
    color: rgba(255, 255, 255, 0.65) !important;
  }

  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border-radius: 5px !important;
    position: relative;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
  }
  .glass-card-premium:hover {
    transform: translateY(-4px) !important;
    border-color: rgba(99, 102, 241, 0.3) !important;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), 0 0 30px rgba(99, 102, 241, 0.15) !important;
  }

  .stat-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 5px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    flex-shrink: 0;
    transition: all 0.3s ease;
  }
  .glass-card-premium:hover .stat-icon-box {
    transform: scale(1.08);
  }

  .stat-icon-primary {
    background: rgba(99, 102, 241, 0.12);
    color: #6366f1;
  }
  .stat-icon-success {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
  }
  .stat-icon-info {
    background: rgba(6, 182, 212, 0.12);
    color: #06b6d4;
  }
  .stat-icon-warning {
    background: rgba(245, 158, 11, 0.12);
    color: #f59e0b;
  }
  .stat-icon-danger {
    background: rgba(248, 113, 113, 0.12);
    color: #f87171;
  }
  .stat-icon-secondary {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.4);
  }

  .progress-dark-premium {
    background: rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    height: 8px;
  }
  .progress-dark-premium .progress-bar {
    border-radius: 5px;
    background: linear-gradient(90deg, #6366f1, #d946ef);
  }

  .badge-premium {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 4px 12px;
    font-weight: 500;
    font-size: 0.75rem;
  }
  .badge-premium-primary {
    background: rgba(99, 102, 241, 0.15);
    border-color: rgba(99, 102, 241, 0.3);
    color: #818cf8;
  }
  .badge-premium-success {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.3);
    color: #34d399;
  }
  .badge-premium-warning {
    background: rgba(245, 158, 11, 0.15);
    border-color: rgba(245, 158, 11, 0.3);
    color: #fbbf24;
  }
  .badge-premium-info {
    background: rgba(6, 182, 212, 0.15);
    border-color: rgba(6, 182, 212, 0.3);
    color: #22d3ee;
  }

  .instructor-avatar {
    width: 42px;
    height: 42px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
  }

  .schedule-item {
    border-left: 2px solid rgba(255, 255, 255, 0.06);
    padding-left: 16px;
    position: relative;
  }
  .schedule-item::before {
    content: '';
    position: absolute;
    left: -5px;
    top: 6px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #6366f1;
  }
  .schedule-item.completed::before {
    background: #10b981;
  }
  .schedule-item.upcoming::before {
    background: #f59e0b;
  }

  .btn-glow-premium {
    background: linear-gradient(135deg, #ffc107, #ff9800) !important;
    border: none;
    color: #0b0f19 !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.4);
    background: linear-gradient(135deg, #ffca28, #ffa726) !important;
    color: #0b0f19 !important;
  }

  .btn-outline-glass {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    transition: all 0.3s ease;
  }
  .btn-outline-glass:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    transform: translateY(-2px);
  }

  /* --- Pagination styling --- */
  .pagination .page-item .page-link {
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    transition: all 0.3s ease !important;
    border-radius: 5px !important;
    margin: 0 2px !important;
  }
  .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border-color: transparent !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
  }
  .pagination .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.02) !important;
    border-color: rgba(255, 255, 255, 0.04) !important;
    color: rgba(255, 255, 255, 0.3) !important;
  }
  .pagination .page-item .page-link:hover:not(.disabled) {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
  }

  hr.dark-premium {
    border-color: rgba(255, 255, 255, 0.06);
    opacity: 1;
  }

  ::-webkit-scrollbar { width: 8px; }
  ::-webkit-scrollbar-track { background: #0b0f19; }
  ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 4px; }
  ::-webkit-scrollbar-thumb:hover { background: #d946ef; }

  .text-gradient {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hover-text-primary:hover {
    color: #818cf8 !important;
  }

  .info-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: rgba(255, 255, 255, 0.4);
    font-weight: 600;
    margin-bottom: 2px;
  }
  .info-value {
    font-size: 0.95rem;
    color: #f8fafc;
    font-weight: 500;
  }

  /* === VERTICAL TIMELINE === */
  .timeline-vert {
    position: relative;
    padding-left: 48px;
    list-style: none;
    margin-bottom: 0;
  }
  .timeline-vert::before {
    content: '';
    position: absolute;
    left: 19px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: linear-gradient(to bottom, #6366f1, rgba(99, 102, 241, 0.1));
  }
  .timeline-item {
    position: relative;
    margin-bottom: 32px;
  }
  .timeline-item:last-child {
    margin-bottom: 0;
  }
  .timeline-icon {
    position: absolute;
    left: -48px;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    z-index: 2;
    flex-shrink: 0;
    border: 2px solid transparent;
    transition: all 0.3s ease;
  }
  .timeline-icon.done {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.4);
    color: #34d399;
  }
  .timeline-icon.active {
    background: rgba(99, 102, 241, 0.15);
    border-color: rgba(99, 102, 241, 0.4);
    color: #818cf8;
    box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
    animation: timelinePulse 2s ease-in-out infinite;
  }
  .timeline-icon.waiting {
    background: rgba(255, 255, 255, 0.04);
    border-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.3);
  }
  @keyframes timelinePulse {
    0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
    70% { box-shadow: 0 0 0 12px rgba(99, 102, 241, 0); }
    100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
  }
  .timeline-content h6 {
    font-size: 0.9rem;
    margin-bottom: 2px;
  }
  .timeline-content p {
    font-size: 0.78rem;
    margin-bottom: 0;
  }
  /* === END VERTICAL TIMELINE === */

  /* Override container-p-y padding top khusus halaman ini */
  body .content-wrapper > .container-p-y {
    padding-top: 1.5rem !important; /* Disamakan persis dengan admin dashboard (1.5rem) */
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->enrollments()->where('status', 'waiting_wa_confirmation')->whereNotNull('verification_code')->whereNull('wa_confirmed_at')->exists()): ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('peserta.waiting-confirmation', []);

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-4233737706-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

  <!-- Floating Gradient Background Orbs -->
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <!-- Main Content container with z-index to sit on top of orbs -->
  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
    
    
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="row align-items-center">
        <div class="col-12 col-lg-8">
          <div class="d-flex align-items-center gap-3 mb-2">
            <div class="stat-icon-box stat-icon-primary">
              <i class="icon-base ti tabler-user-star fs-4"></i>
            </div>
            <div>
              <h4 class="fw-bold text-white mb-0">Selamat datang, <span class="text-gradient fw-extrabold"><?php echo e(optional($profile)->nama_lengkap ?? auth()->user()->name); ?></span> <i class="icon-base ti tabler-hand-wave"></i></h4>
              <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
                Terus semangat belajar dan tingkatkan skill kreatifmu!
              </p>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-4 mt-3 mt-lg-0">
          <div class="d-flex align-items-center gap-4 justify-content-lg-end">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['isProfileCompleted'] && $data['hasPelatihan'] && $data['enrollment'] && in_array($data['enrollment']->status?->value, ['approved', 'confirmed'])): ?>
              <div class="text-center">
                <p class="text-body-premium small mb-0">Kehadiran</p>
                <h5 class="text-white fw-bold mb-0"><?php echo e($data['attendanceRate']); ?>%</h5>
              </div>
              <div class="text-center">
                <p class="text-body-premium small mb-0">Sertifikat</p>
                <h5 class="text-white fw-bold mb-0">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['hasCertificate']): ?>
                    <span class="text-success"><i class="icon-base ti tabler-certificate me-1"></i>Ada</span>
                  <?php else: ?>
                    <span class="text-muted"><i class="icon-base ti tabler-certificate-off me-1"></i>Belum</span>
                  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </h5>
              </div>
            <?php else: ?>
              <div class="text-center">
                <p class="text-body-premium small mb-0">Kelengkapan Profil</p>
                <h5 class="text-white fw-bold mb-0"><?php echo e($data['profileCompletion']); ?>%</h5>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================
         STATE 1: Pendaftaran Belum Lengkap
         ============================================================ -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$data['isProfileCompleted']): ?>
      <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-user text-primary"></i>
                Kelengkapan Profil &amp; Pendaftaran
              </h5>
              <span class="badge-premium badge-premium-warning"><?php echo e($data['profileCompletion']); ?>% Selesai</span>
            </div>

            <div class="mb-4">
              <p class="text-body-premium" style="font-size: 0.95rem;">
                Profil Anda belum lengkap. Silakan lengkapi data profil Anda terlebih dahulu melalui tahapan formulir pendaftaran untuk dapat memilih dan mengikuti pelatihan yang tersedia.
              </p>
            </div>

            <div class="mb-4">
              <div class="progress progress-dark-premium" style="height: 12px;">
                <div class="progress-bar" style="width: <?php echo e($data['profileCompletion']); ?>%;"></div>
              </div>
            </div>

            <hr class="dark-premium my-4">

            <h6 class="text-white fw-semibold mb-3">Tahapan Pendaftaran:</h6>
            <div class="row g-3 mb-4">
              
              <?php
                $step1Done = !empty($profile->nama_lengkap) && !empty($profile->nik);
              ?>
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-<?php echo e($step1Done ? 'success' : 'secondary'); ?>" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-<?php echo e($step1Done ? 'check' : 'user'); ?>"></i>
                  </div>
                  <div>
                    <a href="<?php echo e(route('dashboard.peserta.form-pendaftaran')); ?>" class="text-white fw-semibold text-decoration-none hover-text-primary" style="font-size: 0.9rem;">1. Data Pribadi</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      <?php echo e($step1Done ? 'Sudah Diisi' : 'Belum Lengkap'); ?>

                    </p>
                  </div>
                </div>
              </div>

              
              <?php
                $step2Done = !empty($profile->alamat_ktp) && !empty($profile->whatsapp);
              ?>
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-<?php echo e($step2Done ? 'success' : 'secondary'); ?>" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-<?php echo e($step2Done ? 'check' : 'map-pin'); ?>"></i>
                  </div>
                  <div>
                    <a href="<?php echo e($step1Done ? route('dashboard.peserta.form-alamat') : 'javascript:void(0);'); ?>" 
                       class="text-white fw-semibold text-decoration-none <?php echo e(!$step1Done ? 'text-muted' : 'hover-text-primary'); ?>" 
                       style="font-size: 0.9rem; <?php if(!$step1Done): ?> cursor: not-allowed; opacity: 0.5; <?php endif; ?>">2. Alamat &amp; Kontak</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      <?php echo e($step2Done ? 'Sudah Diisi' : 'Belum Lengkap'); ?>

                    </p>
                  </div>
                </div>
              </div>

              
              <?php
                $step3Done = !empty($profile->pendidikan_terakhir) && !empty($profile->nama_institusi);
              ?>
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-<?php echo e($step3Done ? 'success' : 'secondary'); ?>" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-<?php echo e($step3Done ? 'check' : 'school'); ?>"></i>
                  </div>
                  <div>
                    <a href="<?php echo e($step2Done ? route('dashboard.peserta.form-pendidikan') : 'javascript:void(0);'); ?>" 
                       class="text-white fw-semibold text-decoration-none <?php echo e(!$step2Done ? 'text-muted' : 'hover-text-primary'); ?>" 
                       style="font-size: 0.9rem; <?php if(!$step2Done): ?> cursor: not-allowed; opacity: 0.5; <?php endif; ?>">3. Riwayat Pendidikan</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      <?php echo e($step3Done ? 'Sudah Diisi' : 'Belum Lengkap'); ?>

                    </p>
                  </div>
                </div>
              </div>

              
              <?php
                $step4Done = !empty($profile->pelatihan_id);
              ?>
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-<?php echo e($step4Done ? 'success' : 'secondary'); ?>" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-<?php echo e($step4Done ? 'check' : 'heart'); ?>"></i>
                  </div>
                  <div>
                    <a href="<?php echo e($step3Done ? route('dashboard.peserta.form-minat') : 'javascript:void(0);'); ?>" 
                       class="text-white fw-semibold text-decoration-none <?php echo e(!$step3Done ? 'text-muted' : 'hover-text-primary'); ?>" 
                       style="font-size: 0.9rem; <?php if(!$step3Done): ?> cursor: not-allowed; opacity: 0.5; <?php endif; ?>">4. Pilihan Pelatihan</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      <?php echo e($step4Done ? 'Sudah Diisi' : 'Belum Lengkap'); ?>

                    </p>
                  </div>
                </div>
              </div>

              
              <?php
                $step5Done = !empty($profile->jawaban_pertanyaan);
              ?>
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-<?php echo e($step5Done ? 'success' : 'secondary'); ?>" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-<?php echo e($step5Done ? 'check' : 'file-check'); ?>"></i>
                  </div>
                  <div>
                    <a href="<?php echo e($step4Done ? route('dashboard.peserta.form-dokumen') : 'javascript:void(0);'); ?>" 
                       class="text-white fw-semibold text-decoration-none <?php echo e(!$step4Done ? 'text-muted' : 'hover-text-primary'); ?>" 
                       style="font-size: 0.9rem; <?php if(!$step4Done): ?> cursor: not-allowed; opacity: 0.5; <?php endif; ?>">5. Dokumen &amp; Pertanyaan</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      <?php echo e($step5Done ? 'Sudah Diisi' : 'Belum Lengkap'); ?>

                    </p>
                  </div>
                </div>
              </div>

              
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-<?php echo e($step5Done ? 'success' : 'secondary'); ?>" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-<?php echo e($step5Done ? 'check' : 'send'); ?>"></i>
                  </div>
                  <div>
                    <a href="<?php echo e($step5Done ? route('dashboard.peserta.form-review') : 'javascript:void(0);'); ?>" 
                       class="text-white fw-semibold text-decoration-none <?php echo e(!$step5Done ? 'text-muted' : 'hover-text-primary'); ?>" 
                       style="font-size: 0.9rem; <?php if(!$step5Done): ?> cursor: not-allowed; opacity: 0.5; <?php endif; ?>">6. Review &amp; Kirim</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      <?php echo e($step5Done ? 'Siap Dikirim' : 'Belum Lengkap'); ?>

                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center mt-3">
              <?php
                $nextRoute = route('dashboard.peserta.form-pendaftaran');
                if ($step5Done) {
                    $nextRoute = route('dashboard.peserta.form-review');
                } elseif ($step4Done) {
                    $nextRoute = route('dashboard.peserta.form-dokumen');
                } elseif ($step3Done) {
                    $nextRoute = route('dashboard.peserta.form-minat');
                } elseif ($step2Done) {
                    $nextRoute = route('dashboard.peserta.form-pendidikan');
                } elseif ($step1Done) {
                    $nextRoute = route('dashboard.peserta.form-alamat');
                }
              ?>
              <a href="<?php echo e($nextRoute); ?>" class="btn btn-glow-premium px-5 py-2 fw-bold text-uppercase" style="letter-spacing: 0.05em;">
                <i class="icon-base ti tabler-player-play me-1"></i> Mulai / Lanjutkan Pengisian
              </a>
            </div>
          </div>
        </div>

        <div class="col-12 col-xl-4">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-help-circle text-info"></i>
              Butuh Bantuan?
            </h5>
            <p class="text-body-premium mb-4" style="font-size: 0.9rem; line-height: 1.5;">
              Jika Anda mengalami kesulitan saat mengisi formulir pendaftaran atau membutuhkan informasi lebih lanjut mengenai pelatihan kerja, silakan hubungi kami.
            </p>
            
            <div class="d-flex align-items-center gap-3 mb-4">
              <div class="stat-icon-box stat-icon-success" style="width: 40px; height: 40px; font-size: 1.2rem;">
                <i class="icon-base ti tabler-brand-whatsapp"></i>
              </div>
              <div>
                <span class="info-label d-block">WhatsApp Service</span>
                <a href="https://wa.me/<?php echo e($data['whatsapp_sender']); ?>" target="_blank" class="text-white fw-bold text-decoration-none hover-text-primary">Hubungi Admin</a>
              </div>
            </div>
            
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon-box stat-icon-primary" style="width: 40px; height: 40px; font-size: 1.2rem;">
                <i class="icon-base ti tabler-info-circle"></i>
              </div>
              <div>
                <span class="info-label d-block">FAQ &amp; Panduan</span>
                <a href="<?php echo e(url('/#faq')); ?>" target="_blank" class="text-white fw-bold text-decoration-none hover-text-primary">Lihat Tanya Jawab</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    <!-- ============================================================
         STATE 2: Pendaftaran Selesai, Menunggu Verifikasi / Cadangan / Ditolak
         ============================================================ -->
    <?php elseif(!$data['enrollment'] || in_array($data['enrollment']->status?->value, ['pending', 'rejected', 'waitlist', 'waiting_wa_confirmation'])): ?>
      <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <div class="text-center py-4">
              <div class="stat-icon-box stat-icon-info mx-auto mb-3" style="width: 64px; height: 64px; font-size: 2rem; border-radius: 50% !important;">
                <i class="icon-base ti tabler-send fs-1"></i>
              </div>
              <h4 class="fw-bold text-white mb-2">Pendaftaran Anda Berhasil Dikirim!</h4>
              <p class="text-body-premium mx-auto" style="max-width: 550px; font-size: 0.95rem; line-height: 1.6;">
                Terima kasih telah melengkapi data pendaftaran Anda. Saat ini data Anda sedang dalam proses peninjauan dan verifikasi oleh tim Admin/Dinas penyelenggara.
              </p>

              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['enrollment'] && $data['enrollment']->status?->value === 'waitlist'): ?>
                <div class="alert alert-warning border-warning border-opacity-20 bg-warning bg-opacity-10 text-warning mx-auto p-3 mt-4 text-start" style="max-width: 550px; border-radius: 5px;">
                  <div class="d-flex gap-2">
                    <i class="icon-base ti tabler-alert-triangle mt-1 flex-shrink-0"></i>
                    <div>
                      <strong class="d-block mb-1">Status: Cadangan (Waiting List)</strong>
                      <span>Kuota utama untuk pelatihan ini saat ini sudah penuh. Anda masuk ke daftar cadangan dan akan otomatis dipromosikan jika ada peserta utama yang mengundurkan diri atau ditolak.</span>
                      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['enrollment']->notes): ?>
                        <p class="mb-0 mt-2 small text-warning text-opacity-80">Catatan Admin: <?php echo e($data['enrollment']->notes); ?></p>
                      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php elseif($data['enrollment'] && $data['enrollment']->status?->value === 'rejected'): ?>
                <div class="alert alert-danger border-danger border-opacity-20 bg-danger bg-opacity-10 text-danger mx-auto p-3 mt-4 text-start" style="max-width: 550px; border-radius: 5px;">
                  <div class="d-flex gap-2">
                    <i class="icon-base ti tabler-circle-x mt-1 flex-shrink-0"></i>
                    <div>
                      <strong class="d-block mb-1">Status: Pendaftaran Ditolak</strong>
                      <span>Mohon maaf, pendaftaran Anda belum dapat disetujui.</span>
                      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['enrollment']->notes): ?>
                        <p class="mb-0 mt-2 small text-danger text-opacity-80">Alasan Penolakan: <strong><?php echo e($data['enrollment']->notes); ?></strong></p>
                      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                      <div class="mt-3">
                        <a href="<?php echo e(route('dashboard.peserta.form-minat')); ?>" class="btn btn-sm btn-danger fw-semibold px-3 py-1" style="border-radius: 5px;">
                          <i class="icon-base ti tabler-refresh me-1"></i> Pilih Pelatihan Lain
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              <?php else: ?>
                <div class="d-inline-flex align-items-center gap-2 badge bg-primary bg-opacity-15 text-white border border-primary border-opacity-30 px-3 py-2 mt-3" style="border-radius: 20px;">
                  <span class="spinner-grow spinner-grow-sm text-white" role="status"></span>
                  <span class="fw-semibold small text-uppercase" style="letter-spacing: 0.05em;">Menunggu Verifikasi Admin</span>
                </div>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <hr class="dark-premium my-4">

            
            <div class="text-center">
              <a href="<?php echo e(route('dashboard.peserta.status')); ?>" class="btn btn-outline-glass px-4 py-2 fw-semibold" style="border-radius: 5px; font-size: 0.85rem;">
                <i class="icon-base ti tabler-external-link me-1"></i> Lihat Status Lengkap <span aria-hidden="true">&rarr;</span>
              </a>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['pelatihan']): ?>
              <hr class="dark-premium my-4">
              <h5 class="fw-bold text-white mb-3">Detail Pelatihan Pilihan:</h5>
              <div class="p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
                <div class="row g-3">
                  <div class="col-12 col-md-6">
                    <span class="info-label d-block">Nama Pelatihan</span>
                    <span class="info-value fw-bold text-white"><?php echo e($data['pelatihan']->nama); ?></span>
                  </div>
                  <div class="col-6 col-md-3">
                    <span class="info-label d-block">Batch</span>
                    <span class="info-value text-white"><?php echo e($data['pelatihan']->batch); ?></span>
                  </div>
                  <div class="col-6 col-md-3">
                    <span class="info-label d-block">Dinas Penyelenggara</span>
                    <span class="info-value text-white"><?php echo e($data['pelatihan']->dinas->nama_dinas ?? '-'); ?></span>
                  </div>
                  <div class="col-12">
                    <span class="info-label d-block">Tanggal Pelaksanaan</span>
                    <span class="info-value text-white">
                      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['pelatihan']->tanggal_mulai): ?>
                        <?php echo e($data['pelatihan']->tanggal_mulai->format('d M Y')); ?> s/d <?php echo e($data['pelatihan']->tanggal_selesai ? $data['pelatihan']->tanggal_selesai->format('d M Y') : '-'); ?>

                      <?php else: ?>
                        Akan segera diumumkan
                      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </span>
                  </div>
                </div>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>
        </div>

        <div class="col-12 col-xl-4">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-clock text-info"></i>
              Proses Selanjutnya
            </h5>
            <ul class="timeline-custom mb-0 ps-0" style="list-style: none;">
              <li class="d-flex gap-3 mb-4">
                <div class="stat-icon-box stat-icon-success" style="width: 32px; height: 32px; font-size: 1rem; border-radius: 50% !important;">
                  <i class="icon-base ti tabler-check"></i>
                </div>
                <div>
                  <h6 class="text-white fw-bold mb-1" style="font-size: 0.85rem;">1. Data Dikirim</h6>
                  <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">Anda telah mengirimkan seluruh data pendaftaran.</p>
                </div>
              </li>
              <li class="d-flex gap-3 mb-4">
                <div class="stat-icon-box stat-icon-warning" style="width: 32px; height: 32px; font-size: 1rem; border-radius: 50% !important;">
                  <span class="spinner-border spinner-border-sm" role="status" style="width: 14px; height: 14px; color: #fbbf24;"></span>
                </div>
                <div>
                  <h6 class="text-white fw-bold mb-1" style="font-size: 0.85rem;">2. Verifikasi Data</h6>
                  <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">Tim Admin akan memverifikasi kesesuaian berkas dan kuota.</p>
                </div>
              </li>
              <li class="d-flex gap-3">
                <div class="stat-icon-box stat-icon-secondary" style="width: 32px; height: 32px; font-size: 1rem; border-radius: 50% !important;">
                  <i class="icon-base ti tabler-bell"></i>
                </div>
                <div>
                  <h6 class="text-white fw-bold mb-1" style="font-size: 0.85rem;">3. Hasil Seleksi</h6>
                  <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">Hasil seleksi akan diumumkan di dashboard dan dikirimkan via WhatsApp.</p>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

    <!-- ============================================================
         STATE 4: Cek Newbimma — Menunggu pengecekan admin
         ============================================================ -->
    <?php elseif($data['enrollment'] && $data['enrollment']->status?->value === 'waiting_newbimma_check'): ?>
      <div class="row g-4 mb-4">
        
        <div class="col-12 col-xl-8">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            
            <div class="text-center py-4">
              <div class="stat-icon-box stat-icon-info mx-auto mb-3" style="width: 64px; height: 64px; font-size: 2rem; border-radius: 50% !important; background: rgba(59,130,246,0.12) !important; color: #60a5fa !important;">
                <i class="icon-base ti tabler-search fs-1"></i>
              </div>
              <h4 class="fw-bold text-white mb-2">Status: 🔄 Cek Newbimma</h4>
              <p class="text-body-premium mx-auto" style="max-width: 550px; font-size: 0.95rem; line-height: 1.6;">
                Pendaftaran Anda telah disetujui dan terkonfirmasi. Saat ini data Anda sedang dalam proses pengecekan Newbimma oleh Admin/Dinas penyelenggara.
              </p>
              <div class="d-inline-flex align-items-center gap-2 px-3 py-2 mt-3" style="border-radius: 20px; border: 1px solid rgba(59,130,246,0.3); background: rgba(59,130,246,0.15); color: #60a5fa;">
                <span class="spinner-grow spinner-grow-sm" role="status" style="width: 10px; height: 10px; color: #60a5fa;"></span>
                <span class="fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: #60a5fa;">🔄 Cek Newbimma</span>
              </div>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['elapsedTime'] ?? null): ?>
                <p class="text-body-premium mt-3 mb-0" style="font-size: 0.85rem;">
                  <i class="icon-base ti tabler-clock me-1"></i> Menunggu pengecekan sejak <?php echo e($data['elapsedTime']); ?>

                </p>
              <?php else: ?>
                <p class="text-body-premium mt-3 mb-0" style="font-size: 0.85rem;">
                  <i class="icon-base ti tabler-clock me-1"></i> Segera diperiksa
                </p>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <hr class="dark-premium my-4">

            
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-timeline text-primary"></i>
              Alur Seleksi
            </h5>

            <ul class="timeline-vert">
              
              <li class="timeline-item">
                <div class="timeline-icon done">
                  <i class="icon-base ti tabler-check"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="fw-bold text-white">✅ Pendaftaran Disetujui</h6>
                  <p class="text-body-premium">
                    Pendaftaran Anda telah diverifikasi dan disetujui oleh Admin.
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['approvedAt'] ?? null): ?>
                      <br><span class="text-white-50" style="font-size: 0.75rem;">
                        <i class="icon-base ti tabler-clock me-1"></i><?php echo e($data['approvedAt']->format('d M Y H:i')); ?>

                      </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                  </p>
                </div>
              </li>

              
              <li class="timeline-item">
                <div class="timeline-icon done">
                  <i class="icon-base ti tabler-check"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="fw-bold text-white">✅ Konfirmasi WA</h6>
                  <p class="text-body-premium">
                    Anda telah mengkonfirmasi pendaftaran melalui WhatsApp.
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['waConfirmedAt'] ?? null): ?>
                      <br><span class="text-white-50" style="font-size: 0.75rem;">
                        <i class="icon-base ti tabler-clock me-1"></i><?php echo e($data['waConfirmedAt']->format('d M Y H:i')); ?>

                      </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                  </p>
                </div>
              </li>

              
              <li class="timeline-item">
                <div class="timeline-icon active">
                  <i class="icon-base ti tabler-search"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="fw-bold text-white">🔄 Cek Newbimma</h6>
                  <p class="text-body-premium">
                    Data Anda sedang diperiksa oleh Admin melalui sistem Newbimma.
                    <br><span class="fw-semibold" style="font-size: 0.78rem; color: #818cf8;">SEDANG DIPROSES</span>
                  </p>
                </div>
              </li>

              
              <li class="timeline-item">
                <div class="timeline-icon waiting">
                  <i class="icon-base ti tabler-clock"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="text-white-50 fw-bold">⏳ Hasil Seleksi</h6>
                  <p class="text-body-premium">
                    Menunggu hasil pengecekan Newbimma dari Admin.
                  </p>
                </div>
              </li>
            </ul>

            
            <hr class="dark-premium my-4">
            <div class="text-center">
              <a href="<?php echo e(route('dashboard.peserta.status')); ?>" class="btn btn-outline-glass px-4 py-2 fw-semibold" style="border-radius: 5px; font-size: 0.85rem;">
                <i class="icon-base ti tabler-external-link me-1"></i> Lihat Status Lengkap <span aria-hidden="true">&rarr;</span>
              </a>
            </div>
          </div>
        </div>

        
        <div class="col-12 col-xl-4 d-flex flex-column gap-4">

          
          <div class="glass-card-premium px-4 px-xl-5 py-4">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-book text-success"></i>
              Info Pelatihan
            </h5>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['pelatihan']): ?>
              <div class="p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
                <div class="row g-3">
                  <div class="col-12">
                    <span class="info-label d-block">Nama Pelatihan</span>
                    <span class="info-value fw-bold text-white"><?php echo e($data['pelatihan']->nama); ?></span>
                  </div>
                  <div class="col-6">
                    <span class="info-label d-block">Batch</span>
                    <span class="info-value text-white"><?php echo e($data['pelatihan']->batch); ?></span>
                  </div>
                  <div class="col-6">
                    <span class="info-label d-block">Dinas Penyelenggara</span>
                    <span class="info-value text-white"><?php echo e($data['pelatihan']->dinas->nama_dinas ?? '-'); ?></span>
                  </div>
                  <div class="col-12">
                    <span class="info-label d-block">Jadwal Pelaksanaan</span>
                    <span class="info-value text-white">
                      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['pelatihan']->tanggal_mulai): ?>
                        <?php echo e($data['pelatihan']->tanggal_mulai->format('d M Y')); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['pelatihan']->tanggal_selesai): ?>
                          — <?php echo e($data['pelatihan']->tanggal_selesai->format('d M Y')); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                      <?php else: ?>
                        Akan segera diumumkan
                      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </span>
                  </div>
                </div>
              </div>
            <?php else: ?>
              <div class="text-center py-4 rounded border border-white border-opacity-5" style="background: rgba(255, 255, 255, 0.05);">
                <i class="icon-base ti tabler-book-off fs-2 text-muted mb-2 d-block"></i>
                <span class="text-body-premium small">Belum ada data pelatihan.</span>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>

          
          <div class="glass-card-premium px-4 px-xl-5 py-4">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-headset text-info"></i>
              💬 Butuh Bantuan?
            </h5>
            <p class="text-body-premium mb-4" style="font-size: 0.9rem; line-height: 1.5;">
              Jika Anda memiliki pertanyaan seputar proses pengecekan Newbimma, silakan hubungi Admin melalui WhatsApp.
            </p>

            <?php
              $waNamaPeserta = optional($profile)->nama_lengkap ?? auth()->user()->name ?? '-';
              $waNikPeserta = optional($profile)->nik ?? '-';
              $waMessage = "Halo Admin, saya ingin menanyakan status pengecekan Newbimma saya. Nama: {$waNamaPeserta}, NIK: {$waNikPeserta}";
              $waNumber = $data['whatsapp_sender'] ?? \App\Models\Setting::where('key', 'whatsapp_sender')->value('value') ?? '62888888888';
            ?>
            <a href="https://wa.me/<?php echo e($waNumber); ?>?text=<?php echo e(urlencode($waMessage)); ?>"
               target="_blank"
               class="btn btn-glow-premium w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
              <i class="icon-base ti tabler-brand-whatsapp fs-5"></i>
              Hubungi Admin via WhatsApp
            </a>
          </div>

          
          <div class="glass-card-premium px-4 px-xl-5 py-4">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-user-circle text-info"></i>
              Profil Peserta
            </h5>
            <div class="row g-3">
              <div class="col-12">
                <span class="info-label d-block">Nama Lengkap</span>
                <span class="info-value text-white"><?php echo e(optional($profile)->nama_lengkap ?? auth()->user()->name ?? '-'); ?></span>
              </div>
              <div class="col-6">
                <span class="info-label d-block">NIK</span>
                <span class="info-value text-white" style="font-family: monospace;"><?php echo e(optional($profile)->nik ?? '-'); ?></span>
              </div>
              <div class="col-6">
                <span class="info-label d-block">WhatsApp</span>
                <span class="info-value text-white"><?php echo e(optional($profile)->whatsapp ?? '-'); ?></span>
              </div>
            </div>
          </div>

        </div>
      </div>

    <!-- ============================================================
         STATE 3: Pelatihan Aktif / Approved
         ============================================================ -->
    <?php else: ?>
      <div class="row g-4 mb-4">
        
        <div class="col-12 col-xl-8">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-trending-up text-primary"></i>
                Progress &amp; Kehadiran Pelatihan
              </h5>
              <span class="badge-premium <?php echo e(($data['pelatihan'] && $data['pelatihan']->tanggal_selesai && now()->gt($data['pelatihan']->tanggal_selesai)) ? 'badge-premium-info' : 'badge-premium-success'); ?>">
                <?php echo e(($data['pelatihan'] && $data['pelatihan']->tanggal_selesai && now()->gt($data['pelatihan']->tanggal_selesai)) ? 'Selesai' : 'Aktif'); ?>

              </span>
            </div>

            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.95rem;"><?php echo e($data['pelatihan'] ? $data['pelatihan']->nama : 'Data pelatihan tidak tersedia'); ?></h6>
                  <small class="text-body-premium">Penyelenggara: <?php echo e($data['pelatihan'] ? ($data['pelatihan']->dinas->nama_dinas ?? '-') : '-'); ?></small>
                </div>
                <span class="text-white fw-bold small"><?php echo e($data['attendanceRate']); ?>% Kehadiran</span>
              </div>
              <div class="progress progress-dark-premium" style="height: 10px;">
                <div class="progress-bar" style="width: <?php echo e($data['attendanceRate']); ?>%;"></div>
              </div>
            </div>

            <hr class="dark-premium my-4">

            <h6 class="text-white fw-semibold mb-3">Daftar Pertemuan &amp; Absensi:</h6>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['enrollment']->attendances && $data['enrollment']->attendances->count() > 0): ?>
              <div class="row g-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data['enrollment']->attendances->sortBy('pertemuan_ke'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="col-12 col-sm-6">
                    <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                      <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon-box stat-icon-primary" style="width: 36px; height: 36px; font-size: 1rem;">
                          <?php echo e($att->pertemuan_ke); ?>

                        </div>
                        <div>
                          <span class="text-white fw-semibold small d-block">Pertemuan <?php echo e($att->pertemuan_ke); ?></span>
                          <small class="text-body-premium" style="font-size: 0.75rem;"><?php echo e($att->created_at ? $att->created_at->format('d/m/Y') : '-'); ?></small>
                        </div>
                      </div>
                      <div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($att->status):
                          case ('hadir'): ?>
                            <span class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-30 px-2.5 py-1 small" style="border-radius: 4px;">Hadir</span>
                            <?php break; ?>
                          <?php case ('izin'): ?>
                            <span class="badge bg-warning bg-opacity-15 text-warning border border-warning border-opacity-30 px-2.5 py-1 small" style="border-radius: 4px;">Izin</span>
                            <?php break; ?>
                          <?php case ('sakit'): ?>
                            <span class="badge bg-info bg-opacity-15 text-info border border-info border-opacity-30 px-2.5 py-1 small" style="border-radius: 4px;">Sakit</span>
                            <?php break; ?>
                          <?php case ('alpa'): ?>
                            <span class="badge bg-danger bg-opacity-15 text-danger border border-danger border-opacity-30 px-2.5 py-1 small" style="border-radius: 4px;">Alpa</span>
                            <?php break; ?>
                          <?php default: ?>
                            <span class="badge bg-secondary bg-opacity-15 text-secondary border border-secondary border-opacity-30 px-2.5 py-1 small" style="border-radius: 4px;">-</span>
                        <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                      </div>
                    </div>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              </div>
            <?php else: ?>
              <div class="text-center py-4 rounded border border-white border-opacity-5" style="background: rgba(255, 255, 255, 0.05);">
                <i class="icon-base ti tabler-calendar-off fs-2 text-muted mb-2 d-block"></i>
                <span class="text-body-premium small">Belum ada riwayat absensi yang tercatat untuk pelatihan ini.</span>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>
        </div>

        
        <div class="col-12 col-xl-4">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-certificate text-warning"></i>
              Sertifikat &amp; Kelulusan
            </h5>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['hasCertificate']): ?>
              <div class="text-center py-3">
                <div class="stat-icon-box stat-icon-warning mx-auto mb-3" style="width: 58px; height: 58px; font-size: 1.8rem; border-radius: 50% !important; background: rgba(251,191,36,0.15); color: #fbbf24;">
                  <i class="icon-base ti tabler-award fs-1"></i>
                </div>
                <h5 class="fw-bold text-white mb-2">Selamat, Anda Lulus!</h5>
                <p class="text-body-premium small mb-3" style="line-height: 1.4;">
                  Anda dinyatakan lulus dari pelatihan <strong><?php echo e($data['pelatihan']->nama ?? 'Anda'); ?></strong>. Sertifikat resmi Anda telah diterbitkan.
                </p>

                <div class="p-3 mb-4 rounded border border-white border-opacity-5 text-start" style="background: rgba(255, 255, 255, 0.05);">
                  <span class="info-label d-block">Nomor Sertifikat</span>
                  <span class="info-value fw-mono text-warning" style="font-size: 0.85rem; font-family: monospace;"><?php echo e($data['certificate']->certificate_number); ?></span>
                </div>

                <div class="d-flex flex-column gap-2">
                  <a href="<?php echo e(route('admin.certificates.download', $data['certificate']->id)); ?>" class="btn btn-glow-premium w-100 py-2">
                    <i class="icon-base ti tabler-download me-1"></i> Unduh Sertifikat PDF
                  </a>
                  <a href="<?php echo e(route('certificates.verify', ['nomor' => $data['certificate']->certificate_number])); ?>" target="_blank" class="btn btn-outline-secondary w-100 py-2" style="border-color: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7);">
                    <i class="icon-base ti tabler-qrcode me-1"></i> Verifikasi Online
                  </a>
                </div>
              </div>
            <?php else: ?>
              <div class="text-center py-4 rounded border border-white border-opacity-5" style="background: rgba(255, 255, 255, 0.05);">
                <i class="icon-base ti tabler-award fs-2 text-muted mb-2 d-block"></i>
                <span class="text-white fw-semibold d-block mb-1">Pelatihan Sedang Berlangsung</span>
                <p class="text-body-premium mb-0 small" style="font-size: 0.75rem; line-height: 1.4;">
                  Sertifikat kelulusan akan diterbitkan oleh Admin/Dinas setelah seluruh rangkaian pelatihan dan absensi selesai diverifikasi.
                </p>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <hr class="dark-premium my-4">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['pelatihan']): ?>
            <h6 class="text-white fw-semibold mb-3">Info Kelas Offline:</h6>
            <ul class="list-unstyled mb-0">
              <li class="d-flex justify-content-between mb-2">
                <span class="text-body-premium small">Batch</span>
                <span class="text-white small fw-semibold"><?php echo e($data['pelatihan']->batch ?? '-'); ?></span>
              </li>
              <li class="d-flex justify-content-between mb-2">
                <span class="text-body-premium small">Mulai</span>
                <span class="text-white small fw-semibold"><?php echo e($data['pelatihan']->tanggal_mulai ? $data['pelatihan']->tanggal_mulai->format('d M Y') : '-'); ?></span>
              </li>
              <li class="d-flex justify-content-between">
                <span class="text-body-premium small">Selesai</span>
                <span class="text-white small fw-semibold"><?php echo e($data['pelatihan']->tanggal_selesai ? $data['pelatihan']->tanggal_selesai->format('d M Y') : '-'); ?></span>
              </li>
            </ul>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>
        </div>
      </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- ============================================================
         BOTTOM ROW: Hanya tampil di State 3 (Approved)
         ============================================================ -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['isProfileCompleted'] && $data['enrollment'] && in_array($data['enrollment']->status?->value, ['approved', 'confirmed'])): ?>
    <div class="row g-4">

      <!-- Instruktur Saya (Placeholder) -->
      <div class="col-12 col-xl-4">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-users text-success"></i>
              Instruktur Saya
            </h5>
            <span class="badge-premium badge-premium-success">Info</span>
          </div>

          <div class="text-center py-4">
            <div class="stat-icon-box stat-icon-success mx-auto mb-3" style="width: 56px; height: 56px; font-size: 1.6rem; border-radius: 50% !important;">
              <i class="icon-base ti tabler-users"></i>
            </div>
            <h6 class="text-white fw-semibold mb-2" style="font-size: 0.95rem;">Data Instruktur Segera Hadir</h6>
            <p class="text-body-premium mb-0" style="font-size: 0.85rem; line-height: 1.5;">
              Informasi instruktur akan ditampilkan setelah pelatihan <strong class="text-white"><?php echo e($data['pelatihan']->nama ?? 'Anda'); ?></strong> resmi dimulai dan jadwal pertemuan telah diterbitkan oleh penyelenggara.
            </p>
          </div>

          <hr class="dark-premium my-4">

          <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
            <div class="stat-icon-box stat-icon-primary" style="width: 36px; height: 36px; font-size: 1rem;">
              <i class="icon-base ti tabler-bell"></i>
            </div>
            <div>
              <span class="text-white fw-semibold small d-block">Notifikasi</span>
              <small class="text-body-premium" style="font-size: 0.75rem;">Kami akan memberitahu Anda saat data instruktur tersedia.</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Aktivitas Terakhir -->
      <div class="col-12 col-xl-4">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-activity text-warning"></i>
              Aktivitas Terakhir
            </h5>
            <span class="badge-premium badge-premium-warning">Update</span>
          </div>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['enrollment']): ?>
            
            <div class="d-flex align-items-start gap-3 mb-3">
              <div class="stat-icon-box stat-icon-success" style="width: 36px; height: 36px; font-size: 1rem;">
                <i class="icon-base ti tabler-send"></i>
              </div>
              <div>
                <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Pendaftaran dikirim</h6>
                <small class="text-body-premium">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['enrollment']->created_at): ?>
                    <?php echo e($data['enrollment']->created_at->format('d M Y H:i')); ?>

                  <?php else: ?>
                    Semua data pribadi dan dokumen lengkap
                  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </small>
              </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['enrollment']->status?->value === 'approved' && $data['enrollment']->approved_at): ?>
              <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon-box stat-icon-success" style="width: 36px; height: 36px; font-size: 1rem;">
                  <i class="icon-base ti tabler-check"></i>
                </div>
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Disetujui</h6>
                  <small class="text-body-premium">
                    <?php echo e($data['enrollment']->approved_at->format('d M Y H:i')); ?>

                  </small>
                </div>
              </div>
            <?php elseif($data['enrollment']->status?->value === 'waitlist'): ?>
              <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon-box stat-icon-warning" style="width: 36px; height: 36px; font-size: 1rem;">
                  <i class="icon-base ti tabler-clock"></i>
                </div>
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Masuk antrean cadangan</h6>
                  <small class="text-body-premium">Pelatihan: <?php echo e($data['pelatihan']->nama ?? '-'); ?></small>
                </div>
              </div>
            <?php elseif($data['enrollment']->status?->value === 'rejected'): ?>
              <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon-box stat-icon-danger" style="width: 36px; height: 36px; font-size: 1rem;">
                  <i class="icon-base ti tabler-circle-x"></i>
                </div>
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Pendaftaran ditolak Admin</h6>
                  <small class="text-body-premium">Silakan pilih pelatihan lain</small>
                </div>
              </div>
            <?php else: ?>
              <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon-box stat-icon-info" style="width: 36px; height: 36px; font-size: 1rem;">
                  <i class="icon-base ti tabler-clock"></i>
                </div>
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Menunggu Verifikasi</h6>
                  <small class="text-body-premium">Data sedang diperiksa oleh Admin</small>
                </div>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <?php else: ?>
            
            <div class="d-flex align-items-start gap-3 mb-3">
              <div class="stat-icon-box stat-icon-secondary" style="width: 36px; height: 36px; font-size: 1rem;">
                <i class="icon-base ti tabler-minus"></i>
              </div>
              <div>
                <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Belum ada aktivitas</h6>
                <small class="text-body-premium">Lengkapi pendaftaran Anda untuk memulai</small>
              </div>
            </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>

      <!-- Rekomendasi Pelatihan Lainnya -->
      <div class="col-12 col-xl-4">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100 d-flex flex-column">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-star text-danger"></i>
              Rekomendasi Pelatihan Lainnya
            </h5>
            <span class="badge-premium badge-premium-primary">Baru</span>
          </div>

          <div class="text-center py-3 flex-grow-1 d-flex flex-column align-items-center justify-content-center">
            <div class="stat-icon-box stat-icon-danger mx-auto mb-3" style="width: 56px; height: 56px; font-size: 1.6rem; border-radius: 50% !important;">
              <i class="icon-base ti tabler-library"></i>
            </div>
            <h6 class="text-white fw-semibold mb-2" style="font-size: 0.95rem;">Jelajahi Pelatihan Lainnya</h6>
            <p class="text-body-premium mb-3" style="font-size: 0.85rem; line-height: 1.5; max-width: 280px;">
              Temukan berbagai pelatihan kreatif dan kejuruan lainnya yang tersedia untuk Anda ikuti.
            </p>

            <a href="<?php echo e(route('pelatihan.index')); ?>" class="btn btn-glow-premium px-4 py-2 fw-semibold">
              <i class="icon-base ti tabler-arrow-right me-1"></i> Lihat Semua Pelatihan
            </a>
          </div>

          <hr class="dark-premium my-4">

          <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
            <div class="stat-icon-box stat-icon-primary" style="width: 36px; height: 36px; font-size: 1rem;">
              <i class="icon-base ti tabler-info-circle"></i>
            </div>
            <div>
              <span class="text-white fw-semibold small d-block">Sedang Aktif</span>
              <small class="text-body-premium" style="font-size: 0.75rem;">
                Anda saat ini mengikuti <strong class="text-white"><?php echo e($data['pelatihan']->nama ?? 'pelatihan'); ?></strong>
              </small>
            </div>
          </div>
        </div>
      </div>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
  // ===== REALTIME NOTIFICATION via Echo/Reverb =====
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Echo !== 'undefined') {
      const userId = <?php echo e(auth()->id()); ?>;

      window.Echo.private('App.Models.User.' + userId)
        .listen('.NotificationReceived', (e) => {
          // Refresh bell badge
          if (window.Alpine && window.Alpine.$data) {
            const bellEl = document.querySelector('[x-data="notificationBell()"]');
            if (bellEl) {
              const bellData = window.Alpine.$data(bellEl);
              if (bellData && bellData.refresh) {
                bellData.refresh();
              }
            }
          }

          // Show toast
          const notification = e.notification || e;
          showNotificationToast(notification.title, notification.body, notification.wa_data || notification.data?.wa_data);
        });
    }
  });

  function showNotificationToast(title, body, waData) {
    const toast = document.createElement('div');
    toast.style.cssText = `
      position: fixed; top: 20px; right: 20px; z-index: 99999;
      background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 5px; padding: 16px; max-width: 380px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.4);
      animation: slideInRight 0.3s ease-out;
      font-family: 'Outfit', sans-serif;
    `;

    // Build WA URL helper
    let waHtml = '';
    if (waData) {
      const adminWa = waData.admin_wa || waData.admin_phone || '62888888888';
      let waMessage = waData.message || `Halo Admin, saya telah melakukan pendaftaran pelatihan.\n\nNama Lengkap Sesuai KTP : ${waData.nama_lengkap || '-'}\nJenis Pelatihan : ${waData.pelatihan || '-'}\nKelurahan : ${waData.kelurahan || '-'}\nKecamatan : ${waData.kecamatan || '-'}\nNo. HP Peserta Terdaftar : ${waData.no_hp || '-'}\n\n#pelatihanku2026`;
      const waUrl = `https://wa.me/${adminWa}?text=${encodeURIComponent(waMessage)}`;
      waHtml = `<a href="${waUrl}" target="_blank" style="display: inline-block; background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.2); color: #34d399; border-radius: 5px; padding: 4px 10px; font-size: 0.75rem; text-decoration: none; margin-top: 8px;"><i class="icon-base ti tabler-brand-whatsapp me-1"></i> Hubungi Admin</a>`;
    }

    toast.innerHTML = `
      <div class="d-flex gap-3">
        <div style="font-size: 1.5rem; flex-shrink: 0;">🎉</div>
        <div style="flex: 1; min-width: 0;">
          <h6 style="color: #f8fafc; font-weight: 700; margin: 0 0 4px; font-size: 0.9rem; font-family: 'Sora', sans-serif;">${escapeHtml(title)}</h6>
          <p style="color: rgba(255,255,255,0.65); margin: 0; font-size: 0.8rem; line-height: 1.4;">${escapeHtml(body)}</p>
          ${waHtml}
        </div>
        <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: rgba(255,255,255,0.3); cursor: pointer; font-size: 1.2rem; padding: 0; line-height: 1; flex-shrink: 0;">&times;</button>
      </div>
    `;

    document.body.appendChild(toast);

    // Auto dismiss 5 detik
    setTimeout(() => {
      toast.style.animation = 'slideOutRight 0.3s ease-in forwards';
      setTimeout(() => toast.remove(), 300);
    }, 5000);
  }

  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // CSS untuk animasi toast
  (function() {
    const style = document.createElement('style');
    style.textContent = `
      @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
      @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
    `;
    document.head.appendChild(style);
  })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/dashboard/peserta.blade.php ENDPATH**/ ?>