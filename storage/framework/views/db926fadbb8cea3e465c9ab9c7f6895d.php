<?php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
?>



<?php $__env->startSection('title', 'Daftar Akun Baru'); ?>

<?php $__env->startSection('page-style'); ?>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');

  #auth-page-wrapper {
    font-family: 'Outfit', sans-serif;
    background-color: #0b0f19;
    color: #f8fafc;
    overflow-y: auto;
    height: 100dvh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  @media (max-height: 750px) {
    .glass-card-wide { padding: 20px 16px; }
    .field-group { gap: 8px; }
  }
  #auth-page-wrapper::-webkit-scrollbar { display: none; }
  #auth-page-wrapper h1, #auth-page-wrapper h2, #auth-page-wrapper h3,
  #auth-page-wrapper h4, #auth-page-wrapper h5, #auth-page-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }
  .hero-gradient-animated {
    background: #0b0f19;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%);
    position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;
  }
  .glow-orb {
    position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.4;
    mix-blend-mode: screen; pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out; z-index: 2;
  }
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #06b6d4 0%, transparent 70%); top: 35%; left: 25%; animation-duration: 24s; }
  @keyframes orbFloat {
    0% { transform: translate(0,0) scale(1) rotate(0deg); }
    50% { transform: translate(60px,40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px,-50px) scale(0.92) rotate(360deg); }
  }

  /* --- Glass Card Lebar --- */
  .glass-card-wide {
    background: rgba(15, 23, 42, 0.25);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
    border-radius: 5px;
    position: relative;
    width: 100%;
    max-width: 620px;
    z-index: 10;
    padding: 32px 30px;
  }
  @media (max-width: 660px) {
    .glass-card-wide { max-width: 420px; padding: 24px 20px; }
  }

  .logo-icon-glow {
    width: 38px; height: 38px; border-radius: 5px;
    background: linear-gradient(135deg, #6366f1, #d946ef);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
  }
  .logo-text-glow {
    font-family: 'Sora', sans-serif; font-size: 1.25rem;
    font-weight: 800; color: #ffffff; letter-spacing: -0.5px;
  }

  /* --- Input Fields --- */
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

  /* Select dropdown options — matching dark theme */
  .form-control-custom option,
  .form-control-custom optgroup {
    background: #1a1f2e !important;
    color: #f8fafc !important;
  }
  .form-control-custom option:hover,
  .form-control-custom option:focus,
  .form-control-custom option:checked {
    background: #6366f1 !important;
    color: #ffffff !important;
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
    font-size: 0.7rem !important;
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
    border-left: none !important;
    padding: 10px 14px !important;
    transition: all 0.3s ease !important;
  }
  .input-group-merge .form-control-custom { border-right: none !important; border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important; }
  .input-group-merge .input-group-text { border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; }
  .input-group-merge:focus-within .input-group-text { border-color: #6366f1 !important; }

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
  .form-check-input-custom { background-color: rgba(255, 255, 255, 0.05) !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; }
  .form-check-input-custom:checked { background-color: #6366f1 !important; border-color: #6366f1 !important; }
  .text-white-50-custom { color: rgba(255, 255, 255, 0.5) !important; }

  /* --- Grid 2 Kolom --- */
  .field-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  @media (max-width: 660px) {
    .field-group { grid-template-columns: 1fr; gap: 0; }
  }
  .field-full { grid-column: 1 / -1; }
  .field-group .is-invalid ~ .invalid-feedback,
  .field-group .is-invalid .form-check-label { color: #f87171 !important; }

  /* Autocomplete untuk pencarian koordinator */
  .autocomplete-suggestions {
    position: absolute;
    left: 0;
    right: 0;
    z-index: 999;
    background: #1a1f2e;
    border: 1px solid rgba(255,255,255,0.12);
    border-top: none;
    border-radius: 0 0 5px 5px;
    max-height: 200px;
    overflow-y: auto;
    box-sizing: border-box;
  }
  .autocomplete-item {
    padding: 10px 14px;
    cursor: pointer;
    color: #f8fafc;
    font-size: 14px;
    transition: background 0.2s;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }
  .autocomplete-item:last-child { border-bottom: none; }
  .autocomplete-item:hover,
  .autocomplete-item.active {
    background: rgba(99,102,241,0.2);
  }
  .autocomplete-item small {
    color: rgba(255,255,255,0.5);
    font-size: 11px;
    display: block;
  }
  .autocomplete-no-result {
    padding: 14px;
    text-align: center;
    color: rgba(255,255,255,0.4);
    font-size: 13px;
  }
  /* Responsive fix: di layar kecil suggestions tetap selebar input */
  @media (max-width: 660px) {
    .autocomplete-suggestions {
      left: 0;
      right: 0;
    }
  }

</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div id="auth-page-wrapper">
  <div class="hero-gradient-animated"></div>
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

    <div class="glass-card-wide">

    <!-- Title -->
    <div class="text-center mb-4">
      <h4 class="mb-0 text-white fw-bold">Daftar Akun Baru 🚀</h4>
      <p class="text-white-50-custom small mt-1">Lengkapi data diri Anda untuk memulai</p>
    </div>

    <form id="formRegister" action="<?php echo e(route('register')); ?>" method="POST">
      <?php echo csrf_field(); ?>

      <!-- Grid 2 Kolom -->
      <div class="field-group">

        <!-- Nama Lengkap -->
        <div class="mb-0">
          <label for="name" class="form-label form-label-custom">Nama Lengkap Sesuai KTP</label>
          <input type="text" id="name" name="name"
            class="form-control form-control-custom <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            placeholder="Andi Pratama" value="<?php echo e(old('name')); ?>" required />
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback small mt-1 text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- NIK -->
        <div class="mb-0">
          <label for="nik" class="form-label form-label-custom">NIK (Username Login)</label>
          <input type="text" id="nik" name="nik"
            class="form-control form-control-custom <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            placeholder="15-16 digit NIK" maxlength="16" inputmode="numeric" value="<?php echo e(old('nik')); ?>" required />
          <div id="nik-feedback" class="small mt-1 d-none"></div>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback small mt-1 text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- WhatsApp -->
        <div class="mb-0">
          <label for="whatsapp" class="form-label form-label-custom">Nomor WhatsApp Aktif</label>
          <input type="tel" id="whatsapp" name="whatsapp"
            class="form-control form-control-custom <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            placeholder="0821xxxxxxxx" value="<?php echo e(old('whatsapp')); ?>" required />
          <div id="wa-feedback" class="small mt-1 d-none"></div>
          <small id="wa-format-hint" class="text-white-50-custom d-block mt-1" style="font-size: 11px;">Format: 08xx, otomatis jadi 628xx</small>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback small mt-1 text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Email -->
        <div class="mb-0">
          <label for="email" class="form-label form-label-custom">Email</label>
          <input type="email" id="email" name="email"
            class="form-control form-control-custom <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            placeholder="contoh@email.com" value="<?php echo e(old('email')); ?>" required />
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback small mt-1 text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Sumber Informasi -->
        <div class="mb-0 field-full">
          <label for="sumber_informasi" class="form-label form-label-custom">Sumber Informasi Pelatihan</label>
          <select id="sumber_informasi" name="sumber_informasi" class="form-control form-control-custom <?php $__errorArgs = ['sumber_informasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
            <option value="" disabled <?php echo e(old('sumber_informasi') ? '' : 'selected'); ?>>Pilih sumber informasi</option>
            <option value="koordinator" <?php echo e(old('sumber_informasi') == 'koordinator' ? 'selected' : ''); ?>>Nama Koordinator</option>
            <option value="sosmed" <?php echo e(old('sumber_informasi') == 'sosmed' ? 'selected' : ''); ?>>Sosial Media</option>
            <option value="lainnya" <?php echo e(old('sumber_informasi') == 'lainnya' ? 'selected' : ''); ?>>Lainnya</option>
          </select>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['sumber_informasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback small mt-1 text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Sumber Informasi Detail (show/hide) -->
        <div class="mb-0 field-full" id="sumber_informasi_detail_wrapper" style="display: none;">
          <!-- Dropdown Koordinator (muncul jika pilih "Nama Koordinator") -->
          <div id="koordinator_input_wrapper" style="display: none; position: relative;">
            <label for="koordinator_search" class="form-label form-label-custom">Cari Nama Koordinator</label>
            <input type="text" id="koordinator_search" class="form-control form-control-custom"
              placeholder="Ketik minimal 3 huruf nama koordinator..."
              value="<?php echo e(old('sumber_informasi_detail')); ?>" autocomplete="off" />
            <input type="hidden" id="sumber_informasi_detail" name="sumber_informasi_detail" value="<?php echo e(old('sumber_informasi_detail')); ?>" />
            <div id="koordinator_suggestions" class="autocomplete-suggestions" style="display: none;"></div>
            <small class="text-white-50-custom mt-1 d-block" style="font-size: 11px;">Sistem akan mencari secara otomatis setelah 3 huruf</small>
          </div>
          <!-- Input Teks (muncul jika pilih "Lainnya") -->
          <div id="lainnya_input_wrapper" style="display: none;">
            <label for="sumber_informasi_detail_lainnya" class="form-label form-label-custom">Sebutkan</label>
            <input type="text" id="sumber_informasi_detail_lainnya" name="sumber_informasi_detail"
              class="form-control form-control-custom"
              placeholder="Sebutkan sumber informasi" value="<?php echo e(old('sumber_informasi_detail')); ?>" />
          </div>
        </div>

        <!-- Password Info -->
        <div class="mb-0 field-full">
          <div class="d-flex align-items-start gap-2 p-3 rounded-3" style="background: rgba(255, 193, 7, 0.08); border: 1px solid rgba(255, 193, 7, 0.15);">
            <i class="icon-base ti tabler-info-circle text-warning mt-1 flex-shrink-0"></i>
            <div>
              <p class="text-white-50-custom small mb-0" style="line-height: 1.4;">
                Password akun Anda akan diisi secara otomatis. 
                <span class="text-warning fw-semibold">pelatihanku2026</span>
              </p>
            </div>
          </div>
        </div>

      </div>

      <!-- Full Width: Terms -->
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature()): ?>
      <div class="mt-4 mb-4 field-full">
        <div class="form-check ms-1 <?php $__errorArgs = ['terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
          <input class="form-check-input form-check-input-custom <?php $__errorArgs = ['terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="checkbox" id="terms" name="terms" required />
          <label class="form-check-label text-white-50-custom small" for="terms">
            Saya menyetujui <a href="<?php echo e(route('policy.show')); ?>" target="_blank" class="text-warning text-decoration-none fw-semibold">kebijakan privasi</a> &amp; <a href="<?php echo e(route('terms.show')); ?>" target="_blank" class="text-warning text-decoration-none fw-semibold">ketentuan layanan</a>
          </label>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback small mt-1 text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
      <?php else: ?>
      <div class="mt-4 mb-4 field-full"></div>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

      <!-- Full Width: Submit -->
      <button class="btn btn-warning w-100 fw-semibold btn-glow py-2 field-full" type="submit" style="border-radius: 5px; font-size: 14px;">
        Daftar Sekarang <i class="icon-base ti tabler-arrow-right ms-2"></i>
      </button>
    </form>


  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // ============================================================
  // 1. NIK INPUT — Auto filter & Check via AJAX
  // ============================================================
  const nikInput = document.getElementById('nik');
  const nikFeedback = document.getElementById('nik-feedback');

  if (nikInput) {
    let nikTimeout = null;
    nikInput.addEventListener('input', function() {
      clearTimeout(nikTimeout);
      const nik = this.value.replace(/\D/g, '');
      this.value = nik;
      if (nik.length < 15 || nik.length > 16) {
        nikFeedback.classList.add('d-none');
        nikFeedback.className = 'small mt-1 d-none';
        nikFeedback.textContent = '';
        return;
      }
      nikFeedback.className = 'small mt-1 d-flex align-items-center text-info';
      nikFeedback.innerHTML = '<div class="spinner-border spinner-border-xs me-1" style="width:12px;height:12px;border-width:2px;"></div> Memeriksa NIK...';
      nikFeedback.classList.remove('d-none');
      nikTimeout = setTimeout(function() {
        fetch('<?php echo e(route('landing.check-nik')); ?>', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
          body: JSON.stringify({ nik: nik })
        })
        .then(res => res.json())
        .then(data => {
          nikFeedback.classList.remove('d-none');
          if (data.exists) {
            nikFeedback.className = 'small mt-1 d-flex align-items-center text-warning';
            nikFeedback.innerHTML = '<i class="icon-base ti tabler-alert-circle me-1"></i> ' + data.message;
          } else {
            nikFeedback.className = 'small mt-1 d-flex align-items-center text-success';
            nikFeedback.innerHTML = '<i class="icon-base ti tabler-check-circle me-1"></i> NIK tersedia';
            setTimeout(function() { nikFeedback.classList.add('d-none'); }, 3000);
          }
        })
        .catch(function() {
          nikFeedback.className = 'small mt-1 d-flex align-items-center text-danger';
          nikFeedback.innerHTML = '<i class="icon-base ti tabler-cloud-off me-1"></i> Gagal memeriksa NIK';
        });
      }, 500);
    });
  }

  // ============================================================
  // 2. WHATSAPP INPUT — Auto-convert & Check WA Registration
  // ============================================================
  const waInput = document.getElementById('whatsapp');
  const waFeedback = document.getElementById('wa-feedback');
  const waHint = document.getElementById('wa-format-hint');

  function convertWaNumber(num) {
    num = num.replace(/\D/g, '');
    if (num.startsWith('0')) return '62' + num.substring(1);
    if (num.startsWith('62') && num.length >= 10) return num;
    return '62' + num;
  }

  if (waInput) {
    let waTimeout = null;
    waInput.addEventListener('input', function() {
      clearTimeout(waTimeout);
      const raw = this.value.replace(/\D/g, '');
      this.value = raw;
      if (raw.length >= 4) {
        const converted = convertWaNumber(raw);
        if (waHint) waHint.textContent = 'Format: 08xx → ' + converted;
      } else {
        if (waHint) waHint.textContent = 'Format: 08xx, otomatis jadi 628xx';
      }
      if (raw.length < 8) {
        waFeedback.classList.add('d-none');
        waFeedback.className = 'small mt-1 d-none';
        waFeedback.textContent = '';
        return;
      }
      waFeedback.className = 'small mt-1 d-flex align-items-center text-info';
      waFeedback.innerHTML = '<div class="spinner-border spinner-border-xs me-1" style="width:12px;height:12px;border-width:2px;"></div> Memeriksa nomor WhatsApp...';
      waFeedback.classList.remove('d-none');
      waTimeout = setTimeout(function() {
        const finalNumber = convertWaNumber(raw);
        fetch('<?php echo e(route('landing.check-wa')); ?>', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
          body: JSON.stringify({ number: finalNumber })
        })
        .then(res => res.json())
        .then(data => {
          waFeedback.classList.remove('d-none');
          if (data.exists) {
            waFeedback.className = 'small mt-1 d-flex align-items-center text-success';
            waFeedback.innerHTML = '<i class="icon-base ti tabler-brand-whatsapp me-1"></i> Nomor WhatsApp terdaftar';
          } else {
            waFeedback.className = 'small mt-1 d-flex align-items-center text-danger';
            waFeedback.innerHTML = '<i class="icon-base ti tabler-alert-triangle me-1"></i> Nomor tidak terdaftar di WA';
          }
        })
        .catch(function() {
          waFeedback.className = 'small mt-1 d-flex align-items-center text-warning';
          waFeedback.innerHTML = '<i class="icon-base ti tabler-cloud-off me-1"></i> Gagal verifikasi WA';
        });
      }, 600);
    });
  }

  // ============================================================
  // 3. SUMBER INFORMASI — Autocomplete Koordinator
  // ============================================================
  const sumberInfo = document.getElementById('sumber_informasi');
  const detailWrapper = document.getElementById('sumber_informasi_detail_wrapper');
  const koordinatorWrapper = document.getElementById('koordinator_input_wrapper');
  const lainnyaWrapper = document.getElementById('lainnya_input_wrapper');

  if (sumberInfo && detailWrapper) {
    function toggleDetail() {
      const val = sumberInfo.value;
      if (val === 'koordinator' || val === 'lainnya') {
        detailWrapper.style.display = 'block';
        if (val === 'koordinator') {
          koordinatorWrapper.style.display = 'block';
          lainnyaWrapper.style.display = 'none';
        } else {
          koordinatorWrapper.style.display = 'none';
          lainnyaWrapper.style.display = 'block';
        }
      } else {
        detailWrapper.style.display = 'none';
      }
    }
    sumberInfo.addEventListener('change', toggleDetail);
    toggleDetail();
  }

  // — Autocomplete Logic —
  const koordinatorSearch = document.getElementById('koordinator_search');
  const koordinatorHidden = document.getElementById('sumber_informasi_detail');
  const suggestionsBox = document.getElementById('koordinator_suggestions');

  if (koordinatorSearch && suggestionsBox) {
    let searchTimeout = null;
    let selectedIndex = -1;
    let currentResults = [];

    koordinatorSearch.addEventListener('input', function () {
      clearTimeout(searchTimeout);
      const q = this.value.trim();
      koordinatorHidden.value = q; // Simpan teks ketikan

      if (q.length < 3) {
        suggestionsBox.style.display = 'none';
        suggestionsBox.innerHTML = '';
        return;
      }

      searchTimeout = setTimeout(function () {
        fetch('<?php echo e(route('api.koordinator')); ?>?q=' + encodeURIComponent(q))
          .then(res => res.json())
          .then(data => {
            currentResults = data;
            selectedIndex = -1;
            if (data.length === 0) {
              suggestionsBox.innerHTML = '<div class="autocomplete-no-result">Koordinator tidak ditemukan</div>';
              suggestionsBox.style.display = 'block';
              return;
            }
            let html = '';
            data.forEach(function (item, idx) {
              html += '<div class="autocomplete-item" data-index="' + idx + '" data-value="' + item.name + '">' +
                      item.name + '<small>NIK: ' + (item.nik || '-') + '</small></div>';
            });
            suggestionsBox.innerHTML = html;
            suggestionsBox.style.display = 'block';

            // Click handler for each item
            suggestionsBox.querySelectorAll('.autocomplete-item').forEach(function (el) {
              el.addEventListener('click', function () {
                const name = this.getAttribute('data-value');
                koordinatorSearch.value = name;
                koordinatorHidden.value = name;
                suggestionsBox.style.display = 'none';
                suggestionsBox.innerHTML = '';
              });
            });
          })
          .catch(function () {
            console.error('Gagal mencari koordinator');
          });
      }, 300);
    });

    // Keyboard navigation (arrows + enter)
    koordinatorSearch.addEventListener('keydown', function (e) {
      const items = suggestionsBox.querySelectorAll('.autocomplete-item');
      if (items.length === 0) return;

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (selectedIndex < items.length - 1) selectedIndex++;
        updateActiveItem(items);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (selectedIndex > 0) selectedIndex--;
        updateActiveItem(items);
      } else if (e.key === 'Enter') {
        e.preventDefault();
        if (selectedIndex >= 0 && items[selectedIndex]) {
          items[selectedIndex].click();
        }
      } else if (e.key === 'Escape') {
        suggestionsBox.style.display = 'none';
        suggestionsBox.innerHTML = '';
      }
    });

    function updateActiveItem(items) {
      items.forEach(function (el, idx) {
        el.classList.toggle('active', idx === selectedIndex);
      });
      if (selectedIndex >= 0 && items[selectedIndex]) {
        koordinatorSearch.value = items[selectedIndex].getAttribute('data-value');
      }
    }

    // Hide suggestions on blur (with delay to allow click)
    koordinatorSearch.addEventListener('blur', function () {
      setTimeout(function () {
        suggestionsBox.style.display = 'none';
      }, 200);
    });

    // Show suggestions on focus if there are results
    koordinatorSearch.addEventListener('focus', function () {
      if (currentResults.length > 0) {
        suggestionsBox.style.display = 'block';
      }
    });
  }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/blankLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/auth/register.blade.php ENDPATH**/ ?>