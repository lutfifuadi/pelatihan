<?php
$configData = Helper::appClasses();
$isDitutup = $is_ditutup ?? false;
?>



<?php $__env->startSection('page-style'); ?>
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

  /* Inline alert variants for registration status */
  .alert-registration {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border-radius: 12px;
    border: 1px solid transparent;
    margin-bottom: 1rem;
  }
  .alert-registration .alert-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.4rem;
  }
  .alert-registration-info {
    background: rgba(96, 165, 250, 0.08);
    border-color: rgba(96, 165, 250, 0.2);
    color: #bfdbfe;
  }
  .alert-registration-info .alert-icon {
    background: rgba(96, 165, 250, 0.12);
    color: #60a5fa;
  }
  .alert-registration-success {
    background: rgba(16, 185, 129, 0.08);
    border-color: rgba(16, 185, 129, 0.2);
    color: #a7f3d0;
  }
  .alert-registration-success .alert-icon {
    background: rgba(16, 185, 129, 0.12);
    color: #34d399;
  }
  .alert-registration-warning {
    background: rgba(245, 158, 11, 0.08);
    border-color: rgba(245, 158, 11, 0.2);
    color: #fde68a;
  }
  .alert-registration-warning .alert-icon {
    background: rgba(245, 158, 11, 0.12);
    color: #fbbf24;
  }

  .btn-register {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.75rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
  }
  .btn-register-primary {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #ffffff;
    box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
  }
  .btn-register-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(99, 102, 241, 0.4);
    color: #ffffff;
  }
  .btn-register-disabled,
  .btn-register:disabled {
    background: #4b5563 !important;
    color: #9ca3af !important;
    cursor: not-allowed !important;
    box-shadow: none !important;
    transform: none !important;
  }

  .countdown-text {
    font-variant-numeric: tabular-nums;
    font-weight: 700;
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
  /*
    Variabel yang diharapkan dari backend (disiapkan oleh Bayu):

    [Mode A] — Variabel string sederhana (PRD DeteksiPendaftaranGanda):
      - $enrollmentStatus = 'none' | 'active' | 'completed' | 'rejected_cooldown' | 'rejected_available'
      - $sisaWaktu = string|null  (contoh: "3 hari 4 jam")
      - $tanggalBolehDaftar = string|null  (contoh: "15-07-2026 08:00")

    [Mode B] — Variabel object (legacy/full object):
      - $registrationState = object dengan properti:
          - status: 'none' | 'active' | 'completed' | 'cooldown_active' | 'cooldown_expired'
          - message: string
          - button_disabled: bool
          - countdown: string|null
          - end_date: string|null
      - $enrollmentHistory: model Enrollment|null (opsional, fallback)
  */

  // === NORMALISASI: Resolusi state tunggal dari berbagai variabel input ===
  $state = null;

  // Mode A: $enrollmentStatus (string) — dari PRD DeteksiPendaftaranGanda
  if (isset($enrollmentStatus) && is_string($enrollmentStatus)) {
    $statusMap = [
      'none'               => 'none',
      'active'             => 'active',
      'completed'          => 'completed',
      'rejected_cooldown'  => 'cooldown_active',
      'rejected_available' => 'cooldown_expired',
      'completed_cooldown' => 'completed_cooldown',
      'completed_available'=> 'completed_available',
    ];
    $mappedStatus = $statusMap[$enrollmentStatus] ?? 'none';

    // Pesan default per status
    $defaultMessages = [
      'none'                => null,
      'active'              => 'Anda sudah terdaftar pada pelatihan ini.',
      'completed'           => 'Anda telah menyelesaikan pelatihan ini.',
      'cooldown_active'     => null,  // dibangun dinamis dengan countdown
      'cooldown_expired'    => 'Anda dapat mendaftar kembali untuk pelatihan ini.',
      'completed_cooldown'  => null,
      'completed_available' => 'Anda diperbolehkan mendaftar kembali pada pelatihan ini.',
    ];

    $countdown = $sisaWaktu ?? null;
    $endDate   = $tanggalBolehDaftar ?? null;

    $state = (object) [
      'status'          => $mappedStatus,
      'message'         => $defaultMessages[$mappedStatus] ?? null,
      'button_disabled' => !in_array($mappedStatus, ['none', 'cooldown_expired', 'completed_available']),
      'countdown'       => $countdown,
      'end_date'        => $endDate,
    ];
  }

  // Mode B: $registrationState (object) — format legacy/full
  if (!$state && isset($registrationState) && is_object($registrationState)) {
    // Normalisasi status baru di legacy format jika dikirim langsung sebagai completed_cooldown / completed_available
    $status = $registrationState->status ?? 'none';
    $buttonDisabled = $registrationState->button_disabled ?? true;
    if ($status === 'completed_cooldown') {
      $buttonDisabled = true;
    } elseif ($status === 'completed_available') {
      $buttonDisabled = false;
    }
    
    $state = (object) [
      'status'          => $status,
      'message'         => $registrationState->message ?? null,
      'button_disabled' => $buttonDisabled,
      'countdown'       => $registrationState->countdown ?? null,
      'end_date'        => $registrationState->end_date ?? null,
    ];
  }

  // Mode C: fallback dari $enrollmentHistory
  if (!$state && isset($enrollmentHistory)) {
    $historyStatus  = is_object($enrollmentHistory) ? ($enrollmentHistory->status?->value ?? $enrollmentHistory->status) : null;
    $activeStatuses   = ['pending', 'approved', 'waitlist', 'processing'];
    $completedStatuses = ['completed', 'passed'];

    if (in_array($historyStatus, $activeStatuses)) {
      $state = (object) ['status' => 'active', 'message' => 'Anda sudah terdaftar pada pelatihan ini.', 'button_disabled' => true, 'countdown' => null, 'end_date' => null];
    } elseif (in_array($historyStatus, $completedStatuses)) {
      $state = (object) ['status' => 'completed', 'message' => 'Anda telah menyelesaikan pelatihan ini.', 'button_disabled' => true, 'countdown' => null, 'end_date' => null];
    } else {
      $state = (object) ['status' => 'cooldown_expired', 'message' => 'Anda dapat mendaftar kembali untuk pelatihan ini.', 'button_disabled' => false, 'countdown' => null, 'end_date' => null];
    }
  }

  // Default: tidak ada riwayat
  if (!$state) {
    $state = (object) ['status' => 'none', 'message' => null, 'button_disabled' => false, 'countdown' => null, 'end_date' => null];
  }

  $isGuest = !auth()->check();
?>

<div class="container py-5">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDitutup): ?>
    <div class="alert alert-closed d-flex align-items-center gap-3 px-4 py-3 mb-4" role="alert">
        <div class="alert-icon">
            <i class="icon-base ti tabler-clock-off fs-1"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1" style="color: #f87171;">Pendaftaran Ditutup</h5>
            <p class="mb-0" style="color: rgba(255,255,255,0.7);">
                Pendaftaran untuk pelatihan ini telah ditutup pada <?php echo e($pelatihan->batas_pendaftaran?->format('d/m/Y') ?? 'tanggal yang ditentukan'); ?>.
                Silakan cari pelatihan lain yang masih tersedia.
            </p>
        </div>
        <a href="<?php echo e(route('pelatihan.index')); ?>" class="btn btn-outline-light btn-sm ms-auto flex-shrink-0">
            Lihat Pelatihan Lain
        </a>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <h1><?php echo e($pelatihan->nama); ?></h1>
    <p><?php echo e($pelatihan->deskripsi); ?></p>

    <div class="mt-4 mb-4">
        <strong>Batch:</strong> <?php echo e($pelatihan->batch); ?><br>
        <strong>Tanggal:</strong> <?php echo e($pelatihan->tanggal_mulai?->format('d M Y')); ?> - <?php echo e($pelatihan->tanggal_selesai?->format('d M Y')); ?><br>
        <strong>Kuota:</strong> <?php echo e($pelatihan->kuota); ?><br>
        <strong>Status:</strong> <?php echo e($pelatihan->is_active ? 'Aktif' : 'Tidak Aktif'); ?>

    </div>

    
    <div class="mt-5">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($state->status === 'active'): ?>
            <div class="alert-registration alert-registration-info" role="status" aria-live="polite">
                <div class="alert-icon">
                    <i class="icon-base ti tabler-info-circle"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Anda sudah terdaftar</h6>
                    <p class="mb-0" style="font-size: 0.95rem;">
                        <?php echo e($state->message ?? 'Anda sudah terdaftar pada pelatihan ini.'); ?>

                    </p>
                </div>
            </div>
            <button type="button" class="btn-register btn-register-disabled" disabled aria-disabled="true">
                <i class="icon-base ti tabler-lock"></i> Daftar Pelatihan
            </button>
        <?php elseif($state->status === 'completed'): ?>
            <div class="alert-registration alert-registration-success" role="status" aria-live="polite">
                <div class="alert-icon">
                    <i class="icon-base ti tabler-circle-check"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Pelatihan telah diselesaikan</h6>
                    <p class="mb-0" style="font-size: 0.95rem;">
                        <?php echo e($state->message ?? 'Anda telah menyelesaikan pelatihan ini.'); ?>

                    </p>
                </div>
            </div>
            <button type="button" class="btn-register btn-register-disabled" disabled aria-disabled="true">
                <i class="icon-base ti tabler-lock"></i> Daftar Pelatihan
            </button>
        <?php elseif($state->status === 'cooldown_active'): ?>
            <div class="alert-registration alert-registration-warning" role="status" aria-live="polite">
                <div class="alert-icon">
                    <i class="icon-base ti tabler-clock-pause"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Masa tunggu aktif</h6>
                    <p class="mb-0" style="font-size: 0.95rem;">
                        Pendaftaran Anda sebelumnya ditolak/dibatalkan. Anda dapat mendaftar kembali dalam
                        <span class="countdown-text"><?php echo e($state->countdown ?? '-'); ?></span>.
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($state->end_date): ?>
                            <br><small style="opacity: 0.75;">(mulai tanggal <?php echo e($state->end_date); ?>)</small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
            </div>
            <button type="button" class="btn-register btn-register-disabled" disabled aria-disabled="true">
                <i class="icon-base ti tabler-lock"></i> Daftar Pelatihan
            </button>
        <?php elseif($state->status === 'completed_cooldown'): ?>
            <div class="alert-registration alert-registration-warning" role="status" aria-live="polite">
                <div class="alert-icon">
                    <i class="icon-base ti tabler-clock-pause"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Masa tunggu kelulusan aktif</h6>
                    <p class="mb-0" style="font-size: 0.95rem;">
                        <?php echo e($state->message ?? 'Anda telah menyelesaikan pelatihan ini sebelumnya. Sesuai kebijakan, Anda dapat mendaftar kembali nanti.'); ?>

                    </p>
                </div>
            </div>
            <button type="button" class="btn-register btn-register-disabled" disabled aria-disabled="true">
                <i class="icon-base ti tabler-lock"></i> Daftar Pelatihan
            </button>
        <?php elseif($state->status === 'cooldown_expired'): ?>
            <div class="alert-registration alert-registration-info" role="status" aria-live="polite">
                <div class="alert-icon">
                    <i class="icon-base ti tabler-refresh"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Anda dapat mendaftar kembali</h6>
                    <p class="mb-0" style="font-size: 0.95rem;">
                        <?php echo e($state->message ?? 'Anda dapat mendaftar kembali untuk pelatihan ini.'); ?>

                    </p>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isGuest): ?>
                <a href="<?php echo e(route('register')); ?>" class="btn-register btn-register-primary">
                    <i class="icon-base ti tabler-edit"></i> Daftar Pelatihan
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('dashboard.peserta.form-pendaftaran')); ?>" class="btn-register btn-register-primary">
                    <i class="icon-base ti tabler-edit"></i> Daftar Pelatihan
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php elseif($state->status === 'completed_available'): ?>
            <div class="alert-registration alert-registration-success" role="status" aria-live="polite">
                <div class="alert-icon">
                    <i class="icon-base ti tabler-circle-check"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Masa tunggu kelulusan berakhir</h6>
                    <p class="mb-0" style="font-size: 0.95rem;">
                        <?php echo e($state->message ?? 'Anda diperbolehkan mendaftar kembali pada pelatihan ini.'); ?>

                    </p>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isGuest): ?>
                <a href="<?php echo e(route('register')); ?>" class="btn-register btn-register-primary">
                    <i class="icon-base ti tabler-edit"></i> Daftar Pelatihan
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('dashboard.peserta.form-pendaftaran')); ?>" class="btn-register btn-register-primary">
                    <i class="icon-base ti tabler-edit"></i> Daftar Pelatihan
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php else: ?>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isGuest): ?>
                <a href="<?php echo e(route('register')); ?>" class="btn-register btn-register-primary">
                    <i class="icon-base ti tabler-edit"></i> Daftar Pelatihan
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('dashboard.peserta.form-pendaftaran')); ?>" class="btn-register btn-register-primary">
                    <i class="icon-base ti tabler-edit"></i> Daftar Pelatihan
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/publicLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/pelatihan/show.blade.php ENDPATH**/ ?>