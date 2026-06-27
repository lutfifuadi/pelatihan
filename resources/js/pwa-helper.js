/**
 * PWA Helper untuk Pelatihanku
 *
 * Utility untuk mendeteksi iOS / Safari / standalone mode,
 * serta mengelola frekuensi tampilan prompt "Install ke Home Screen".
 *
 * Fungsi-fungsi ini diekspos ke window.PwaHelper agar bisa digunakan
 * oleh Alpine.js / Blade components (Ayu) untuk UI prompt iOS.
 */

const STORAGE_KEYS = {
  promptShownAt: 'pelatihanku_ios_install_prompt_shown_at',
  promptDismissedSession: 'pelatihanku_ios_install_prompt_dismissed',
};

const PwaHelper = {
  /**
   * Deteksi apakah perangkat adalah iOS (iPhone / iPad / iPod).
   * Perhatikan: iPadOS 13+ bisa menyamar sebagai desktop,
   * tapi untuk keperluan install prompt kita fokus ke iPhone Safari.
   */
  isIos() {
    const ua = window.navigator.userAgent.toLowerCase();
    return /iphone|ipad|ipod/.test(ua);
  },

  /**
   * Deteksi apakah browser adalah Safari asli di iOS.
   * Menghindari Chrome iOS (CriOS), Firefox iOS (FxiOS), dan Edge iOS (EdgiOS).
   */
  isSafariOnIos() {
    const ua = window.navigator.userAgent.toLowerCase();
    const isIos = /iphone|ipad|ipod/.test(ua);
    const isSafari = /safari/.test(ua);
    const isOtherIosBrowser = /crios|fxios|edgios|opios|mercuro/.test(ua);

    return isIos && isSafari && !isOtherIosBrowser;
  },

  /**
   * Deteksi apakah PWA sudah berjalan dalam mode standalone
   * (dibuka dari Home Screen / installed app).
   */
  isInStandaloneMode() {
    return (
      window.navigator.standalone === true ||
      window.matchMedia('(display-mode: standalone)').matches
    );
  },

  /**
   * Cek apakah user adalah target prompt install iOS:
   * iOS Safari + belum install ke Home Screen.
   */
  shouldShowIosInstallPrompt() {
    return this.isSafariOnIos() && !this.isInStandaloneMode();
  },

  /**
   * Cek apakah prompt sudah pernah ditampilkan dalam N hari terakhir.
   * Default: 7 hari (sesuai BR-008).
   */
  hasPromptBeenShownRecently(days = 7) {
    if (typeof Storage === 'undefined') return false;

    const lastShown = localStorage.getItem(STORAGE_KEYS.promptShownAt);
    if (!lastShown) return false;

    const msPerDay = 24 * 60 * 60 * 1000;
    const diff = Date.now() - parseInt(lastShown, 10);

    return diff < days * msPerDay;
  },

  /**
   * Tandai prompt sudah ditampilkan sekarang.
   */
  markPromptAsShown() {
    if (typeof Storage === 'undefined') return;
    localStorage.setItem(STORAGE_KEYS.promptShownAt, Date.now().toString());
  },

  /**
   * Reset penandaan prompt (berguna untuk testing).
   */
  resetPromptHistory() {
    if (typeof Storage === 'undefined') return;
    localStorage.removeItem(STORAGE_KEYS.promptShownAt);
    sessionStorage.removeItem(STORAGE_KEYS.promptDismissedSession);
  },

  /**
   * Dismiss prompt untuk sesi ini saja (tidak menyentuh localStorage).
   */
  dismissForSession() {
    if (typeof Storage === 'undefined') return;
    sessionStorage.setItem(STORAGE_KEYS.promptDismissedSession, '1');
  },

  /**
   * Cek apakah prompt sudah di-dismiss untuk sesi ini.
   */
  isDismissedForSession() {
    if (typeof Storage === 'undefined') return false;
    return sessionStorage.getItem(STORAGE_KEYS.promptDismissedSession) === '1';
  },

  /**
   * Helper lengkap: apakah boleh menampilkan prompt install iOS saat ini.
   * Memperhitungkan: iOS Safari, belum standalone, belum pernah ditampilkan
   * dalam periode tertentu, dan belum di-dismiss di sesi ini.
   */
  canShowIosInstallPrompt(days = 7) {
    return (
      this.shouldShowIosInstallPrompt() &&
      !this.hasPromptBeenShownRecently(days) &&
      !this.isDismissedForSession()
    );
  },

  /**
   * Dapatkan informasi platform yang terdeteksi (untuk analytics/debugging).
   */
  getPlatformInfo() {
    return {
      isIos: this.isIos(),
      isSafariOnIos: this.isSafariOnIos(),
      isInStandaloneMode: this.isInStandaloneMode(),
      userAgent: window.navigator.userAgent,
    };
  },
};

// Alias sesuai permintaan PRD agar konsisten
function isIos() {
  return PwaHelper.isIos();
}

function isInStandaloneMode() {
  return PwaHelper.isInStandaloneMode();
}

// Expose ke global scope agar bisa dipakai di Blade / Alpine.js
window.PwaHelper = PwaHelper;
window.isIos = isIos;
window.isInStandaloneMode = isInStandaloneMode;

// Log ringkas di console untuk debugging
if (typeof console !== 'undefined') {
  console.log('[PWA] Platform info:', PwaHelper.getPlatformInfo());
}

export { PwaHelper, isIos, isInStandaloneMode };
export default PwaHelper;
