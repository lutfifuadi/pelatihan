<?php
$configData = Helper::appClasses();

// Config lookup helpers
$fLabels = $fields->pluck('label', 'field_key');
$fPlaceholders = $fields->pluck('placeholder', 'field_key');
$fRequired = $fields->where('is_required', true)->pluck('field_key')->toArray();
$fActive = $fields->where('is_active', true)->pluck('field_key')->toArray();
?>



<?php $__env->startSection('title', 'Form Dokumen & Konfirmasi'); ?>

<?php $__env->startSection('vendor-style'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/select2/select2.scss']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-style'); ?>
<style>


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

  .glow-orb {
    position: fixed; border-radius: 50%; filter: blur(120px); opacity: 0.4;
    mix-blend-mode: screen; pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out; z-index: 0;
  }
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #06b6d4 0%, transparent 70%); top: 35%; left: 25%; animation-duration: 24s; }
  @keyframes orbFloat {
    0% { transform: translate(0,0) scale(1) rotate(0deg); }
    50% { transform: translate(60px,40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px,-50px) scale(0.92) rotate(360deg); }
  }

  .glass-card-dashboard {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
    border-radius: 5px;
    position: relative;
    z-index: 1;
    padding: 28px 24px;
  }
  @media (max-width: 660px) {
    .glass-card-dashboard { padding: 20px 16px; }
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
  .text-white-50-custom { color: rgba(255, 255, 255, 0.5) !important; }
  .text-white-70-custom { color: rgba(255, 255, 255, 0.7) !important; }

  .file-upload-area {
    background: rgba(255, 255, 255, 0.03);
    border: 1px dashed rgba(255, 255, 255, 0.15);
    border-radius: 5px;
    padding: 24px 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .file-upload-area:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(99, 102, 241, 0.4);
  }
  .file-upload-area.has-file { border-color: #10b981; background: rgba(16, 185, 129, 0.05); }

  .review-section {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    padding: 16px;
    margin-bottom: 12px;
  }
  .review-section-title {
    font-family: 'Sora', sans-serif;
    font-size: 0.8rem; font-weight: 700;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 10px;
    display: flex; align-items: center; gap: 8px;
  }
  .review-item {
    display: flex; justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    font-size: 13px;
  }
  .review-item:last-child { border-bottom: none; }
  .review-item-label { color: rgba(255, 255, 255, 0.5); }
  .review-item-value { color: rgba(255, 255, 255, 0.9); font-weight: 500; text-align: right; max-width: 60%; }

  .invalid-feedback-custom { color: #f87171; font-size: 11px; margin-top: 3px; display: none; }
  .invalid-feedback-custom.d-block { display: block; }

  .field-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  @media (max-width: 660px) {
    .field-group { grid-template-columns: 1fr; gap: 12px; }
  }
  .field-full { grid-column: 1 / -1; }

  .tab-pane-step { animation: fadeSlideIn 0.35s ease forwards; }
  @keyframes fadeSlideIn {
    0% { opacity: 0; transform: translateY(12px); }
    100% { opacity: 1; transform: translateY(0); }
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
  <div class="glass-card-dashboard mb-4">
    <div class="d-flex align-items-center gap-3">
      <div style="width: 48px; height: 48px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);">
        <i class="icon-base ti tabler-file-check text-white fs-4"></i>
      </div>
      <div>
        <h4 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Form Dokumen &amp; Konfirmasi</h4>
        <p class="text-white-50-custom mb-0 small">Unggah dokumen dan konfirmasi data Anda</p>
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
    <div class="step-progress-line" style="transform: scaleX(0.8); transform-origin: left;"></div>
    
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
    
    <!-- Step 4: Pelatihan -->
    <div class="step-item <?php echo e($step4Done ? 'completed' : ''); ?>" <?php if($step4Done): ?> onclick="window.location.href='<?php echo e(route('dashboard.peserta.form-minat')); ?>'" style="cursor: pointer;" <?php endif; ?>>
      <div class="step-circle">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($step4Done): ?>
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        <?php else: ?>
          4
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
      <div class="step-label">Pilihan Pelatihan</div>
    </div>
    
    <!-- Step 5: Dokumen (active) -->
    <div class="step-item active">
      <div class="step-circle">5</div>
      <div class="step-label">Dokumen</div>
    </div>
    
    <!-- Step 6: Review -->
    <div class="step-item">
      <div class="step-circle">6</div>
      <div class="step-label">Review</div>
    </div>
  </div>

  <div class="glass-card-dashboard" x-data="dokumenForm()" x-cloak>
    <form id="formDokumen" action="<?php echo e(route('dashboard.peserta.form-dokumen.store')); ?>" method="POST" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>

      <div class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-file-check me-2" style="color: #6366f1;"></i>Dokumen &amp; Konfirmasi
        </h5>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$field->is_active): ?> <?php continue; ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->field_key === 'pengetahuan_asep'): ?>
            <h6 class="text-white-70-custom fw-semibold mb-3 mt-2" style="font-size: 0.95rem;">
              <i class="icon-base ti tabler-question-mark me-2" style="color: #6366f1;"></i>PERTANYAAN UMUM
            </h6>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->field_key === 'rencana_setelah_pelatihan'): ?>
            <h6 class="text-white-70-custom fw-semibold mb-3 mt-4" style="font-size: 0.95rem;">
              <i class="icon-base ti tabler-briefcase me-2" style="color: #6366f1;"></i>PERTANYAAN MINAT & USAHA
            </h6>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->field_key === 'usaha_dimiliki'): ?>
            <h6 class="text-white-70-custom fw-semibold mb-3 mt-4" style="font-size: 0.95rem;">
              <i class="icon-base ti tabler-chart-bar me-2" style="color: #6366f1;"></i>PERTANYAAN USAHA & KENDALA
            </h6>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->type === 'textarea'): ?>
            <div class="field-group mb-3">
              <div class="field-full">
                <label class="form-label form-label-custom" for="<?php echo e($field->field_key); ?>">
                  <?php echo e($field->label); ?>

                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->is_required): ?> <span class="text-danger">*</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </label>
                <textarea id="<?php echo e($field->field_key); ?>" name="<?php echo e($field->field_key); ?>"
                  class="form-control form-control-custom"
                  rows="4"
                  placeholder="<?php echo e($field->placeholder ?? 'Tulis jawaban anda...'); ?>"
                  x-model="form.<?php echo e($field->field_key); ?>"
                  :class="{ 'is-invalid': errors.<?php echo e($field->field_key); ?> }"
                  <?php echo e($field->is_required ? 'required' : ''); ?>></textarea>
                <div class="invalid-feedback-custom" :class="{ 'd-block': errors.<?php echo e($field->field_key); ?> }" x-text="errors.<?php echo e($field->field_key); ?>"></div>
              </div>
            </div>
            <?php elseif($field->type === 'radio'): ?>
            <?php
              $options = app(\App\Services\FormConfigService::class)->getOptions($field->options_group);
            ?>
            <div class="field-group mb-3">
              <div class="<?php echo e($field->width === 'full' ? 'field-full' : ''); ?>"
                   style="grid-column: <?php echo e($field->width === 'full' ? '1 / -1' : 'span 1'); ?>">
                <label class="form-label form-label-custom">
                  <?php echo e($field->label); ?>

                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->is_required): ?> <span class="text-danger">*</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </label>
                <div class="d-flex flex-wrap gap-3 mt-1">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="form-check">
                    <input class="form-check-input form-check-input-custom" type="radio"
                      id="<?php echo e($field->field_key); ?>_<?php echo e($loop->index); ?>"
                      name="<?php echo e($field->field_key); ?>"
                      value="<?php echo e($val); ?>"
                      x-model="form.<?php echo e($field->field_key); ?>" />
                    <label class="form-check-label text-white-50-custom small" for="<?php echo e($field->field_key); ?>_<?php echo e($loop->index); ?>">
                      <?php echo e($label); ?>

                    </label>
                  </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="invalid-feedback-custom" :class="{ 'd-block': errors.<?php echo e($field->field_key); ?> }" x-text="errors.<?php echo e($field->field_key); ?>"></div>
              </div>
            </div>
            <?php elseif($field->type === 'radio_other'): ?>
            <?php
              $options = app(\App\Services\FormConfigService::class)->getOptions($field->options_group);
            ?>
            <div class="field-group mb-3">
              <div class="<?php echo e($field->width === 'full' ? 'field-full' : ''); ?>"
                   style="grid-column: <?php echo e($field->width === 'full' ? '1 / -1' : 'span 1'); ?>">
                <label class="form-label form-label-custom">
                  <?php echo e($field->label); ?>

                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->is_required): ?> <span class="text-danger">*</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </label>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->placeholder): ?>
                  <small class="text-white-50-custom d-block mb-2" style="font-size: 11px;"><?php echo e($field->placeholder); ?></small>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="d-flex flex-wrap gap-3 mt-1">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="form-check">
                    <input class="form-check-input form-check-input-custom" type="radio"
                      id="<?php echo e($field->field_key); ?>_<?php echo e($loop->index); ?>"
                      name="<?php echo e($field->field_key); ?>"
                      value="<?php echo e($val); ?>"
                      x-model="form.<?php echo e($field->field_key); ?>" />
                    <label class="form-check-label text-white-50-custom small" for="<?php echo e($field->field_key); ?>_<?php echo e($loop->index); ?>">
                      <?php echo e($label); ?>

                    </label>
                  </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <!-- Input untuk 'Yang lain' -->
                <div x-show="form.<?php echo e($field->field_key); ?> === 'Yang lain'" x-cloak class="mt-2">
                  <input type="text"
                    name="<?php echo e($field->field_key); ?>_other"
                    class="form-control form-control-custom form-control-uppercase"
                    x-model="form.<?php echo e($field->field_key); ?>_other"
                    placeholder="TULISKAN JAWABAN ANDA..."
                    @input="form.<?php echo e($field->field_key); ?>_other = form.<?php echo e($field->field_key); ?>_other.toUpperCase()" />
                </div>
                <div class="invalid-feedback-custom" :class="{ 'd-block': errors.<?php echo e($field->field_key); ?> }" x-text="errors.<?php echo e($field->field_key); ?>"></div>
              </div>
            </div>
            <?php elseif($field->type === 'checkbox'): ?>
            <div class="field-group mb-3">
              <div class="field-full">
                <div class="form-check">
                  <input class="form-check-input form-check-input-custom" type="checkbox"
                    id="<?php echo e($field->field_key); ?>"
                    name="<?php echo e($field->field_key); ?>"
                    value="1"
                    x-model="form.<?php echo e($field->field_key); ?>" />
                  <label class="form-check-label text-white-50-custom small" for="<?php echo e($field->field_key); ?>">
                    <?php echo e($field->label); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->is_required): ?> <span class="text-danger">*</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                  </label>
                </div>
                <div class="invalid-feedback-custom" :class="{ 'd-block': errors.<?php echo e($field->field_key); ?> }" x-text="errors.<?php echo e($field->field_key); ?>"></div>
              </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="<?php echo e(route('dashboard.peserta.form-minat')); ?>" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;">
          <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
        </a>
        <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="submitForm()" :disabled="submitting">
          <template x-if="!submitting">
            <span><i class="icon-base ti tabler-arrow-right me-1"></i> Lanjutkan ke Review</span>
          </template>
          <template x-if="submitting">
            <span><span class="spinner-border spinner-border-sm me-1" style="width:14px;height:14px;border-width:2px;"></span> Menyimpan...</span>
          </template>
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
    Alpine.data('dokumenForm', function() {
      var fd = window._formData || {};
      return {
        form: {
          pengetahuan_asep: fd.pengetahuan_asep || '',
          alasan_pelatihan: fd.alasan_pelatihan || '',
          pengalaman_bisnis: fd.pengalaman_bisnis || '',
          rencana_setelah_pelatihan: fd.rencana_setelah_pelatihan || '',
          punya_usaha: fd.punya_usaha || '',
          jenis_usaha: fd.jenis_usaha || '',
          usaha_dimiliki: fd.usaha_dimiliki || '',
          usaha_dimiliki_other: fd.usaha_dimiliki_other || '',
          nama_usaha: fd.nama_usaha || '',
          nama_usaha_other: fd.nama_usaha_other || '',
          kendala_usaha: fd.kendala_usaha || '',
        },
        errors: {},
        submitting: false,

        clearErrors() { this.errors = {}; },

        validate() {
          this.clearErrors();
          var errs = {};
          var valid = true;

          <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($field->is_active && $field->is_required && $field->type !== 'checkbox'): ?>
              <?php if($field->type === 'textarea'): ?>
          if (!this.form.<?php echo e($field->field_key); ?>.trim()) { errs['<?php echo e($field->field_key); ?>'] = '<?php echo e($field->label); ?> wajib diisi'; valid = false; }
              <?php elseif($field->type === 'radio' || $field->type === 'radio_other'): ?>
          if (!this.form.<?php echo e($field->field_key); ?>) { errs['<?php echo e($field->field_key); ?>'] = 'Pilih salah satu opsi'; valid = false; }
              <?php endif; ?>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          this.errors = errs;
          return valid;
        },

        submitForm() {
          if (!this.validate()) return;
          this.submitting = true;
          document.getElementById('formDokumen').submit();
        },
      };
    });
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/dashboard/peserta/form-dokumen.blade.php ENDPATH**/ ?>