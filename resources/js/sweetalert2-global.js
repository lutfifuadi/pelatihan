/**
 * SweetAlert2 Global Configuration — Admin Premium Theme
 * Semua halaman admin menggunakan tema dark glassmorphism yang konsisten.
 *
 * Load setelah SweetAlert2 CDN agar default mixin diterapkan
 * ke seluruh pemanggilan Swal.fire() di halaman admin.
 */

const SwalConfig = {
  background: '#0f172a',
  color: '#f8fafc',
  customClass: {
    popup: 'rounded-3 shadow-lg',
    title: 'fw-bold text-white',
    htmlContainer: 'text-body-premium',
    confirmButton: 'btn btn-primary px-4 py-2 border-0 me-2 fw-semibold',
    cancelButton: 'btn btn-secondary-custom px-4 py-2 border-0 fw-semibold',
  },
  buttonsStyling: false,
  reverseButtons: true,
};

// Terapkan mixin sebagai default global
// SweetAlert2.mixin() mengembalikan instance baru dengan konfigurasi tergabung
if (typeof Swal !== 'undefined') {
  window.Swal = Swal.mixin(SwalConfig);
}

export { SwalConfig };
