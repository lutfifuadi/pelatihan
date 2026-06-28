<?php
    $configData = Helper::appClasses();
?>



<?php $__env->startSection('title', 'Preferensi Notifikasi'); ?>

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

    .layout-navbar-fixed .layout-page::before {
        display: none !important;
    }

    .content-wrapper > .container-xxl {
        max-width: 100% !important;
        padding: 0 !important;
    }

    .layout-menu,
    #layout-menu {
        background-color: #0b0f19 !important;
        border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    .pref-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 5px;
        backdrop-filter: blur(12px);
        padding: 24px;
    }

    .form-control {
        background: rgba(255,255,255,0.04) !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
        color: #e8e9ed !important;
        border-radius: 5px !important;
        font-family: 'Outfit', sans-serif;
    }
    .form-control:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.2) !important;
    }
    .form-control::placeholder {
        color: #6b7084;
    }

    .form-check-input {
        background: rgba(255,255,255,0.08) !important;
        border: 1px solid rgba(255,255,255,0.15) !important;
        width: 44px !important;
        height: 24px !important;
        cursor: pointer;
    }
    .form-check-input:checked {
        background: #6366f1 !important;
        border-color: #6366f1 !important;
    }
    .form-check-input:focus {
        box-shadow: 0 0 0 2px rgba(99,102,241,0.3) !important;
    }
    .form-check-label {
        color: #c8cad4;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-save {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 5px;
        padding: 12px 32px;
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        color: #fff;
        transition: all 0.3s;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(99,102,241,0.35);
    }

    .channel-icon {
        width: 44px;
        height: 44px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-1 fw-bold" style="color: #f8fafc; font-family: 'Sora', sans-serif;">Preferensi Notifikasi</h3>
            <p class="mb-0" style="color: #9ca0b0; font-size: 14px;">Atur bagaimana anda ingin menerima notifikasi</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="<?php echo e(route('notifications.index')); ?>" class="filter-btn" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #b0b4c2; border-radius: 5px; padding: 8px 18px; font-size: 13px; text-decoration: none;">
                <i class="icon-base ti tabler-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.25); color: #86efac; border-radius: 5px;">
            <i class="icon-base ti tabler-check-circle me-1"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1) brightness(2);"></button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form action="<?php echo e(route('notifications.preferences.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="pref-card mb-4">
            <h5 class="fw-semibold mb-4" style="color: #e8e9ed; font-family: 'Sora', sans-serif;">
                <i class="icon-base ti tabler-bell-ringing me-2" style="color: #6366f1;"></i>
                Saluran Notifikasi
            </h5>

            <div class="d-flex align-items-center justify-content-between py-3 px-3 rounded-3 mb-3"
                style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                <div class="d-flex align-items-center gap-3">
                    <div class="channel-icon" style="background: rgba(245,158,11,0.12);">
                        <i class="icon-base ti tabler-bell" style="color: #f59e0b;"></i>
                    </div>
                    <div>
                        <strong style="color: #e8e9ed; font-size: 14px;">In-App Notifications</strong>
                        <p class="mb-0" style="color: #9ca0b0; font-size: 12px;">Notifikasi di dalam aplikasi</p>
                    </div>
                </div>
                <div class="form-check form-switch mb-0">
                    <input type="hidden" name="in_app_enabled" value="0">
                    <input class="form-check-input" type="checkbox" name="in_app_enabled" value="1"
                        id="in_app_enabled" <?php echo e($preferences->in_app_enabled ? 'checked' : ''); ?>>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between py-3 px-3 rounded-3 mb-3"
                style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                <div class="d-flex align-items-center gap-3">
                    <div class="channel-icon" style="background: rgba(37,211,102,0.12);">
                        <i class="icon-base ti tabler-brand-whatsapp" style="color: #25D366;"></i>
                    </div>
                    <div>
                        <strong style="color: #e8e9ed; font-size: 14px;">WhatsApp Notifications</strong>
                        <p class="mb-0" style="color: #9ca0b0; font-size: 12px;">Terima notifikasi via WhatsApp</p>
                    </div>
                </div>
                <div class="form-check form-switch mb-0">
                    <input type="hidden" name="whatsapp_enabled" value="0">
                    <input class="form-check-input" type="checkbox" name="whatsapp_enabled" value="1"
                        id="whatsapp_enabled" <?php echo e($preferences->whatsapp_enabled ? 'checked' : ''); ?>>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between py-3 px-3 rounded-3"
                style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                <div class="d-flex align-items-center gap-3">
                    <div class="channel-icon" style="background: rgba(99,102,241,0.12);">
                        <i class="icon-base ti tabler-mail" style="color: #6366f1;"></i>
                    </div>
                    <div>
                        <strong style="color: #e8e9ed; font-size: 14px;">Email Notifications</strong>
                        <p class="mb-0" style="color: #9ca0b0; font-size: 12px;">Terima notifikasi via Email</p>
                    </div>
                </div>
                <div class="form-check form-switch mb-0">
                    <input type="hidden" name="email_enabled" value="0">
                    <input class="form-check-input" type="checkbox" name="email_enabled" value="1"
                        id="email_enabled" <?php echo e($preferences->email_enabled ? 'checked' : ''); ?>>
                </div>
            </div>
        </div>

        <div class="pref-card mb-4">
            <h5 class="fw-semibold mb-4" style="color: #e8e9ed; font-family: 'Sora', sans-serif;">
                <i class="icon-base ti tabler-moon-stars me-2" style="color: #6366f1;"></i>
                Quiet Hours
            </h5>
            <p style="color: #9ca0b0; font-size: 13px;" class="mb-3">
                Selama quiet hours, notifikasi WhatsApp dan Email tidak akan dikirim.
            </p>

            <div x-data="{ quietEnabled: <?php echo e(($preferences->quiet_hours_start && $preferences->quiet_hours_end) ? 'true' : 'false'); ?> }">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" class="form-check-input" id="quiet_hours_toggle"
                            x-model="quietEnabled">
                    </div>
                    <label class="form-check-label" for="quiet_hours_toggle">
                        Aktifkan Quiet Hours
                    </label>
                </div>

                <div x-show="quietEnabled" x-transition class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="color: #c8cad4; font-size: 13px;">Mulai</label>
                        <input type="time" name="quiet_hours_start"
                            class="form-control"
                            value="<?php echo e($preferences->quiet_hours_start ?? '22:00'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="color: #c8cad4; font-size: 13px;">Selesai</label>
                        <input type="time" name="quiet_hours_end"
                            class="form-control"
                            value="<?php echo e($preferences->quiet_hours_end ?? '06:00'); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-save">
                <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Preferensi
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('preferencesForm', () => ({
            quietEnabled: <?php echo e(($preferences->quiet_hours_start && $preferences->quiet_hours_end) ? 'true' : 'false'); ?>

        }));
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/notifications/preferences.blade.php ENDPATH**/ ?>